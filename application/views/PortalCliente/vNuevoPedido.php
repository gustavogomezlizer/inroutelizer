<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data);
?>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<style>
	html, body {
  height: 100%;
  margin: 0;
  background: white;
}
.wrapper {
  height: 100%;
  display: flex;
  flex-direction: column;
}
.header, .footer {
}
.content {
  flex: 1;
  overflow: auto;
}
</style>

									
<div class="wrapper">
  	<div class="header">
	 	<div class="page-header">
			<h1>
				<strong>In Route</strong> <i>Sofware de Venta</i>
				<small>
					<i class="ace-icon fa fa-angle-double-right"></i>
					Nuevo Pedido
					<i class="ace-icon fa fa-angle-double-right"></i>					
					<div class="profile-info-value">
						<span id="lblNombre"></span>
					</div>
				</small>
			</h1>
		</div><!-- /.page-header -->

		<div class="tableTools-container" style="display:inline;">
			<a id="btnModalBuscarProducto" class="btn btn-app btn-primary btn-xs">
				<i class="ace-icon fa fa-search-plus bigger-160"></i>
				Productos
			</a>
		</div>
		<div class="tableTools-container" style="display:inline;">
			<a id="btnLimpiar" class="btn btn-app btn-yellow btn-xs">
				<i class="ace-icon fa fa-trash bigger-160"></i>
				Limpiar
			</a>
		</div>
		<div class="tableTools-container" style="display:inline;">
			<a id="btnRegresar" class="btn btn-app btn-default btn-xs">
				<i class="ace-icon fa fa-arrow-circle-left bigger-160"></i>
				Regresar
			</a>
		</div>
		<div class="tableTools-container" style="display:inline;">
			<a id="btnGuardar" class="btn btn-app btn-success btn-xs">
				<i class="ace-icon fa fa-save bigger-160"></i>
				Guardar
			</a>
		</div>

		<h4 id="lblTotal" style="display:inline;float:right"><b>Total:</b></h4>
	</div>

  	<div class="content">
    	<div style="height:50%;">
			

				<table id="tabla_items" class="table table-striped table-hover">
				</table>
			
		</div>
  	</div>
	  
  	<div class="footer">
	</div>
	
</div>

<?php $this->load->view("vCopyright"); ?>

<?php $this->load->view("vFooter"); ?>

</body>
</html>


<!-- Modal -->
<div id="modal_buscar_producto" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="modal_buscar_producto_title" class="modal-title">Modal Header</h4>
			</div>

		<div class="modal-body">

			<div class="row">

				<div class="col-md-12">

					<table id="tabla_buscar_producto" width="100%" class="table table-condensed">

						<thead>
							<tr>
								<th>Codigo</th>
								<th>Producto</th>
								<th>Disponible</th>
							</tr>
						</thead>

						<tbody></tbody>

					</table>

				</div>

			</div>
		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
		</div>

	</div>
</div>

<script>
	var idprincipal=0;

	var CARGAR_BOTONES_TABLA = "0";
	var i_fecha=0, i_sucursal=1, i_codigo=2, i_producto=3, i_cantidad=4, i_cantidaddisponible=5;
	var hide_columnas;

	var items = [];
	var productos = [];

	var tabla_buscar_producto = $('#tabla_buscar_producto').DataTable();

	getProductos();

	window.onload = function()
	{
		getCliente();
	}

	$("#btnModalBuscarProducto").on("click", function(){
		$("#modal_buscar_producto_title").text("Lista de Productos");
		$("#modal_buscar_producto").modal("show");
	});

	$("#btnLimpiar").on("click", function(){
		items = [];
		renderItemsTable();
	});

	$("#btnRegresar").on("click", function(){
		history.back();
	});

	$('#tabla_buscar_producto tbody').on('click', 'tr', function () {
        var data = tabla_buscar_producto.row( this ).data();
		$("#modal_buscar_producto").modal("hide");
        addItem(data);
    });
				
	function getCliente()	
	{
		$('#divInfoCliente').addClass('loadingtable');

		var codigo = "<?php echo $this->session->userdata("codigocliente"); ?>";

		axios.get("<?php echo LINKPROYECTO('GetClienteByCodigo/'); ?>" + codigo, {
			responseType: 'json'
		})
	    .then(function(res) {
	    	$("#lblIdCliente").text(res.data.codigo);
			$("#lblNombre").text(res.data.nombre);
			$("#lblDireccion").text(res.data.calle + " #" + res.data.numero + ", " + res.data.colonia + ", " + res.data.ciudad + ", " + res.data.estado + ", C.P. " + res.data.cp);

			$('#divInfoCliente').removeClass('loadingtable');
	    })
	    .catch(function(err) {
	    	$('#divInfoCliente').removeClass('loadingtable');
	    })
	    .then(function() {
	    });
	}

	function getProductos()	
	{
		$('#tabla_buscar_producto').addClass('loadingtable');
		$('#tabla_buscar_producto tbody').html("");

		axios.get("<?php echo LINKPROYECTO('ListadoProductosInventarioJson/'); ?>" + "1", {
			responseType: 'json'
		})
	    .then(function(res) {
			productos = res.data;
			
			if(productos.length > 0)
			{
				tabla_buscar_producto.destroy();
				tabla_buscar_producto = $('#tabla_buscar_producto').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS("json/datatables-spanish.json"); ?>"
					},
					"pageLength": 10,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[1,"asc"]],
					"aaData": productos,
					"columns": [
						{ "data": "codigo" },
						{ "data": "nombre" },
						{ "data": "cantidaddisponible", className: "text-right" }
					],
					"columnDefs": [
						
					]
				});
			}

			var itemsguardados = localStorage.getItem('items');
			if(itemsguardados != null)
			{
				itemsguardados = JSON.parse(itemsguardados);
				localStorage.clear();
				for(var x in itemsguardados)
				{
					items.push(itemsguardados[x]);
				}

				renderItemsTable();
			}

			//$('#tabla_buscar_producto').removeClass('loadingtable');
	    })
	    .catch(function(err) {
	    })
	    .then(function() {
			$('#tabla_buscar_producto').removeClass('loadingtable');
	    });
	}

	function addItem(data)
	{
		var idproducto = data.id;
		var cantidad = 0;

		cantidad = parseFloat(cantidad);
		idproducto = parseInt(idproducto);

		var producto = productos.filter(obj => {
			return obj.id == idproducto
		});

		producto = producto[0];

		var existe = items.filter(obj => {
			return obj.id == idproducto
		});
		
		if(existe.length > 0)
		{
			/*var indexexiste = items.findIndex((obj => obj.id == idproducto));
			var cantidaddespues = items[indexexiste].cantidad + cantidad;

			if(producto.cantidaddisponible >= cantidaddespues)
			{
				items[indexexiste].cantidad = cantidaddespues;
				items[indexexiste].subtotal = parseFloat(items[indexexiste].cantidad) * parseFloat(items[indexexiste].precio);
			}
			else
			{
				alert("El produto " + producto.nombre + " solo tiene " + producto.cantidaddisponible + " piezas disponibles. Favor de ingresar otra cantidad");
				return;
			}*/
			return;
		}
		else
		{
			/*if(producto.cantidaddisponible >= cantidad)
			{
				var item = {
					id: producto.id,
					codigo: producto.codigo,
					producto: producto.nombre,
					cantidad: cantidad,
					precio: producto.precio,
					subtotal: (parseFloat(cantidad) * parseFloat(producto.precio))
				};

				items.push(item);
			}
			else
			{
				alert("El produto " + producto.nombre + " solo tiene " + producto.cantidaddisponible + " piezas disponibles. Favor de ingresar otra cantidad");
				return;
			}*/

			var item = {
				id: producto.id,
				codigo: producto.codigo,
				producto: producto.nombre,
				cantidad: cantidad,
				precio: producto.precio,
				subtotal: (parseFloat(cantidad) * parseFloat(producto.precio))
			};

			items.push(item);
		}

		renderItemsTable();
	}

	function removeItem(idproducto)
	{
		var newarray = items.filter(obj => {
			return obj.id != idproducto
		});

		items = newarray;

		renderItemsTable();
	}

	function addCantidad(idproducto)
	{
		var index = items.findIndex((obj => obj.id == idproducto));
		var cantidaddespues = items[index].cantidad + 1;

		var producto = productos.filter(obj => {
			return obj.id == idproducto
		});

		producto = producto[0];

		items[index].cantidad = cantidaddespues;
		items[index].subtotal = parseFloat(items[index].cantidad) * parseFloat(items[index].precio);
		/*if(producto.cantidaddisponible >= cantidaddespues)
		{
			items[index].cantidad = cantidaddespues;
			items[index].subtotal = parseFloat(items[index].cantidad) * parseFloat(items[index].precio);
		}
		else
		{
			alert("El produto " + producto.nombre + " solo tiene " + producto.cantidaddisponible + " piezas disponibles. Favor de ingresar otra cantidad");
			return;
		}*/

		renderItemsTable();
	}

	function quitarCantidad(idproducto)
	{
		var index = items.findIndex((obj => obj.id == idproducto));
		var cantidaddespues = items[index].cantidad - 1;

		if(cantidaddespues < 0) return;

		items[index].cantidad = cantidaddespues;
		items[index].subtotal = parseFloat(items[index].cantidad) * parseFloat(items[index].precio);

		renderItemsTable();
	}

	function renderItemsTable()
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		var cadena = "";
		var total = 0;

		for(var x in items)
		{
			cadena = cadena + "<tr>";
			cadena = cadena + "<td><i class='ace-icon fa fa-product-hunt bigger-200'></i></td>";
			cadena = cadena + "<td>" +
					"<p><b>" + items[x].producto  + "</b></p>" +					
					"<div style='display:inline'>" + items[x].codigo + "</div>" +
					"<div style='display:inline;float:right'><b>Precio: " + dollarUS.format(parseFloat(items[x].precio)) + "</b></div><br/><br/>" +
					"<div class='form-inline'>" +
					"	<button onclick='quitarCantidad(" + items[x].id + ")' class='btn btn-app btn-sm'><b>-</b></button>" +
					"	<input style='width:100px' type='number' value='" + items[x].cantidad + "' readonly/>" +
					"	<button onclick='addCantidad(" + items[x].id + ")' class='btn btn-app btn-sm'><b>+</b></button>" +
					"</div><br/>" +
					"<a role='button' onclick='removeItem(" + items[x].id + ")'>Eliminar</a>" +
					"<div style='display:inline;float:right;color:green;'><b>Subtotal: " + dollarUS.format(parseFloat(items[x].subtotal)) + "</b></div><br/><br/>" +
				"</td>";
			cadena = cadena + "</tr>";

			total = total + parseFloat(items[x].subtotal);
		}

		$("#tabla_items").html(cadena);
		$("#lblTotal").text("Total: " + dollarUS.format(total));
		localStorage.setItem('items', JSON.stringify(items));

		/*for(var x in items)
		{
			cadena = cadena + "<tr>";
			cadena = cadena + "<td>" + items[x].codigo + "</td>";
			cadena = cadena + "<td>" + items[x].producto + "</td>";
			cadena = cadena + "<td align='right'>" + items[x].cantidad + "</td>";
			cadena = cadena + "<td align='right'>" + dollarUS.format(parseFloat(items[x].precio)) + "</td>";
			cadena = cadena + "<td align='right'>" + dollarUS.format(parseFloat(items[x].subtotal)) + "</td>";
			cadena = cadena + "<td onclick='removeItem(" + items[x].id + ")'><span class='red' role='button'><i class='ace-icon fa fa-trash bigger-120'></i></span></td>";
			cadena = cadena + "</tr>";

			total = total + parseFloat(items[x].subtotal);
		}

		$("#tabla_nuevo_pedido tbody").html(cadena);
		$("#lblTotal").text(dollarUS.format(total));

		$('#cmbProducto').selectpicker('toggle');		
		$('.dropdown-menu li').removeClass('active');
		$('#cmbProducto').selectpicker('val', '');
		$('#cmbProducto').selectpicker('refresh');
		$('#txtCantidad').val("");*/
	}
</script>