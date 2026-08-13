<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data);
?>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Pedidos
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div id="divInfoCliente" class="col-xs-6">
								<div class="profile-user-info profile-user-info-striped">
									<div class="profile-info-row">
										<div class="profile-info-name">ID Cliente:</div>

										<div class="profile-info-value">
											<span class="editable" id="lblIdCliente"></span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name">Nombre:</div>

										<div class="profile-info-value">
											<span class="editable" id="lblNombre"></span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name">Dirección:</div>

										<div class="profile-info-value">
											<i class="fa fa-map-marker light-orange bigger-110"></i>
											<span class="editable" id="lblDireccion"></span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
										<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
										
										<div class="clearfix">
											<div class="pull-right tableTools-container">
												<a id="btnNuevoPedido" class="btn btn-app btn-yellow btn-xs">
													<i class="ace-icon fa fa-shopping-cart bigger-160"></i>
													Comprar
												</a>
											</div>
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container">
												<a href="<?php echo LINKPROYECTO("PortalClienteNuevoPedido"); ?>" class="btn btn-app btn-yellow btn-xs">
													<i class="ace-icon fa fa-shopping-cart bigger-160"></i>
													Nuevo
												</a>
											</div>
										</div>

										<div class="table-header">
											Listado de Pedidos
										</div>										

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="tabla_usuarios" width="100%" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="10%">Fecha</th>
														<th width="7%">Sucursal</th>
														<th width="5%">Codigo</th>
														<th width="20%">Producto</th>
														<th width="5%">Cantidad</th>
														<th width="5%">Cantidad Disponible</th>
													</tr>
												</thead>
												<tbody>
														
												</tbody>
											</table>
										</div><!-- empieza div que contiene a la tabla -->
									</div><!--  termina div.col-xs-12 de la tabla clientes-->
								</div><!--  termina div.row de la tabla clientes-->

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>

			</div><!-- /.main-content -->
						
			<!-- Modal -->
			<div id="modal_nuevo_pedido" class="modal fade" role="dialog">
				<div class="modal-dialog modal-lg">

					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 id="modal_nuevo_pedido_title" class="modal-title">Modal Header</h4>
						</div>

					<div class="modal-body">

						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									<label class="block clearfix">
										<select id="cmbProducto" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Productos)" required>
										</select>
									</label>
								</div>
								<div class="col-md-4">
									<label class="block clearfix">
										<span class="block input-icon input-icon-right">
											<input type="number" class="form-control" placeholder="Cantidad" id="txtCantidad" />
											<i class="ace-icon fa fa-user"></i>
										</span>
									</label>
								</div>
								<div class="col-md-4">
									<button id="btnAgregarProducto" class="btn btn-success btn-xs">
										<i class="ace-icon fa fa-shopping-cart bigger-50"></i>
										Agregar
									</button>
								</div>
							</div>
						</div>

						<div class="space-4"></div>
						<div class="space-4"></div>

						<div class="row">

							<div class="col-md-12">

								<table id="tabla_nuevo_pedido" width="100%" class="table table-condensed">

									<thead>
										<tr>
											<th>Codigo</th>
											<th>Producto</th>
											<th>Cantidad</th>
											<th>Precio</th>
											<th>Subtotal</th>
											<th>&nbsp;</th>
										</tr>
									</thead>

									<tbody></tbody>

									<tfoot>
										<td align="right" colspan="4"><b>Total:</b></td>
										<td id="lblTotal" align="right"><b>$0.00</b></td>
										<td>&nbsp;</td>
									</tfoot>

								</table>

							</div>

						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
						<button type="button" class="btn btn-success" onclick="guardarRutas();">GUARDAR</button>
					</div>

				</div>
			</div>

<?php $this->load->view("vCopyright"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

</div><!-- /.main-container -->

<?php $this->load->view("vFooter"); ?>

</body>
</html>
				
<script>
	var idprincipal=0;

	var CARGAR_BOTONES_TABLA = "0";
	var i_fecha=0, i_sucursal=1, i_codigo=2, i_producto=3, i_cantidad=4, i_cantidaddisponible=5;
	var hide_columnas;

	var items = [];
	var productos = [];

	var myTable = $('#tabla_usuarios')				
	.DataTable( {
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},
					"pageLength": -1,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]]
	});

	getProductos();

	window.onload = function()
	{
		getCliente();
	}

	$("#cmbFiltroSucursal").on("change", function(){
		cargarTableUsuarios($("#cmbFiltroSucursal").val());
	});

	$("#btnNuevoPedido").on("click", function(){
		$("#modal_nuevo_pedido_title").text("Nuevo Pedido");
		$("#modal_nuevo_pedido").modal("show");
	});

	$("#txtCantidad").keypress(function(e) {		
	    if (e.keyCode === 13) {
	    	addItem();
	    }
	});

	$("#btnAgregarProducto").on("click", function(){		
		addItem();
	});

	$("#cmbProducto").on("change", function(){
		$("#txtCantidad").focus();
	});

	function cargarTableUsuarios(pIdsucursal)
	{
		$('#tabla_usuarios').addClass('loadingtable');
		$('#tabla_usuarios tbody').html("");

		$.post("<?php echo LINKPROYECTO('ListadoInventarioJson') ?>", { idsucursal:pIdsucursal }, function(data){
			var datos = JSON.parse(data);						
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#tabla_usuarios').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS("json/datatables-spanish.json"); ?>"
					},
					"pageLength": 50,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "fecha_registro" },
						{ "data": "sucursal" },
						{ "data": "codigo" },
						{ "data": "nombre" },
						{ "data": "cantidad", className: "text-right" },
						{ "data": "cantidaddisponible", className: "text-right" }
					],
					"columnDefs": [
						
					]
				});

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";
				}

				cargarBotonesTabla();
			}
			else
			{
				myTable.clear().draw();
			}
		}).always(function() {
			$('#tabla_usuarios').removeClass('loadingtable');
		});
	}

	function cargarBotonesTabla()
	{
		$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
	
		new $.fn.dataTable.Buttons( myTable, {
			buttons: [

				{
				"extend": "excel",
				"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
				"className": "btn btn-white btn-primary btn-bold",
				"titleAttr": "LISTADO",
				"title": 'Listado - Usuarios',
				"exportOptions": {
						columns: [ i_fecha, i_sucursal, i_codigo, i_producto, i_cantidad, i_cantidaddisponible ]
					}
				},					 
				{
				"extend": "print",
				"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
				"className": "btn btn-white btn-primary btn-bold",
				autoPrint: false,
				message: 'This print was produced using the Print button for DataTables'
				}
			]
		});

		myTable.buttons().container().appendTo( $('.tableTools-container') );
	}
				
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
		axios.get("<?php echo LINKPROYECTO('ListadoProductosInventarioJson/'); ?>" + "1", {
			responseType: 'json'
		})
	    .then(function(res) {
			productos = res.data;
			
			for(var x in productos)
			{
				$('#cmbProducto').append($('<option>', {
					value: productos[x].id,
					text: productos[x].nombre
				}));

				$('#cmbProducto').selectpicker('refresh');
			}
	    })
	    .catch(function(err) {
	    })
	    .then(function() {
	    });
	}

	function addItem()
	{
		var idproducto = $("#cmbProducto").val();
		var cantidad = $("#txtCantidad").val();
		
		if(idproducto == "")
		{
			alert("Seleccione un producto");
			return;
		}

		if(cantidad == "" || cantidad == "0" || isNaN(cantidad))
		{
			alert("Ingrese una cantidad válida");
			return;
		}

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
			var indexexiste = items.findIndex((obj => obj.id == idproducto));
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
			}
		}
		else
		{
			if(producto.cantidaddisponible >= cantidad)
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
			}
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
		$('#txtCantidad').val("");
	}
</script>