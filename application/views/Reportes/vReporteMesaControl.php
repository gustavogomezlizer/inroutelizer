<?php 
$data['title']="LIZER Reportes-Visitas";


$this->load->view("vHead",$data); ?>
<style>
	.tamano{
		width: 90% !important;
	}
</style>
<?php $this->load->view("vMenu"); ?>

			<div id="principal" class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Reportes / Mesa Control
								</small>
							</h1>
						</div><!-- /.page-header -->						

						<div class="row">

							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-2">
										<label for="txtFecha">Fecha</label>
										<input id="txtFecha" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
									</div>

									<div class="col-xs-2">
										<label for="cmbSucursal">Sucursal</label>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<option value="0">TODAS</option>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value="<?php echo $item->id; ?>" <?php echo (GETSUCURSAL()==$item->id) ? 'selected' : ''; ?> ><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } else { ?>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="col-xs-2">
										<br/><button id="btnAplicar" class="btn btn-primary">Aplicar</button>
									</div>
								</div>

								<hr>
							</div>

							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

									<div class="row">

										<div class="col-md-4">
											<label for="cmbNegocio">Negocio</label>
											<select name="cmbNegocio" id="cmbNegocio" class="form-control">
												<option value="0">[SELECCIONE UN NEGOCIO]</option>
												<?php foreach($proveedores as $proveedor) { ?>
													<option value="<?php echo $proveedor->id; ?>"><?php echo $proveedor->nombre; ?></option>
												<?php } ?>
											</select><br/>
										</div>

										<div class="col-xs-12">

											<div class="table-header">
												<b id="lblTituloDistribucionNegocio">Distribucion x Negocio</b>
											</div>											

											<div class="table-responsive">
												<table id="table_distribucion_negocio" width="100%" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Negocio</th>
															<th>Ruta</th>
															<th>Preventa</th>
															<th>Vis. Programadas</th>
															<th>Cum. Agenda</th>
															<th>Drop Siza</th>
															<th>No. Pedidos</th>
															<th>Inicio Ruta</th>
															<th>Fin Ruta</th>
															<th>Tiempo Laborado</th>
															<th>Efectividad</th>
														</tr>
													</thead>

													<tbody></tbody>

													<tfoot></tfoot>
												</table>											
											</div>
										</div>
									</div>

									<div class="row">

										<div class="col-xs-0"></div>
										<div class="col-xs-6">

											<div class="table-header">
												<b id="lblTituloVisitasHora">Visitas x Hora</b>
											</div>

											<!-- div.table-responsive -->

											<!-- div.dataTables_borderWrap -->
											<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
												<table id="table_visitas" width="100%" class="table table-striped table-bordered table-hover">
													<thead>
														<!--<tr>
															<?php /*foreach($columnas as $item) { ?>
																<th><?php echo $item ?></th>
															<?php }*/ ?>
														</tr>-->
													</thead>
													<tbody>
														<?php /*foreach($valores as $item) { ?>
															<!--<tr>
																<?php foreach($columnas as $columna) { ?>
																	<th><?php echo $item[$columna] ?></th>
																<?php } ?>
															</tr>-->
														<?php } */?>
													</tbody>

													<tfoot></tfoot>
												</table>											
											</div><!-- empieza div que contiene a la tabla -->
										</div><!--  termina div.col-xs-12 de la tabla clientes-->
									</div><!--  termina div.row de la tabla clientes-->

									<div class="row">
										<div class="col-xs-5">

											<div class="table-header">
												<b id="lblTituloVentaCategoria">Venta x Categoria</b>
											</div>
											<div class="table-responsive">
												<table id="table_venta_categoria" width="100%" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Categoria</th>
															<th>Venta</th>
															<th>CCC</th>
															<th>SKU</th>
														</tr>
													</thead>

													<tbody></tbody>

													<tfoot></tfoot>
												</table>
											</div>
										</div>

										<div class="col-xs-7">

											<div class="table-header">
												<b id="lblTituloVentaCliente">Venta x Cliente</b>
											</div>
											<div class="table-responsive">
												<table id="table_venta_cliente" width="100%" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>ID Pedido</th>
															<th>Cliente</th>
															<th>Venta</th>
															<th>SKU</th>
														</tr>
													</thead>

													<tbody></tbody>

													<tfoot></tfoot>
												</table>											
											</div>
										</div>
									</div>

							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->			

<?php $this->load->view("vCopyright"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

<?php $this->load->view("vFooter"); ?>

</body>
</html>
		

<script>

	var i_sucursal=0, 
	i_pendientes=1, 
	i_fechas=2;

	var CARGAR_BOTONES_TABLA = "0";

	window.onload = function()
	{
		//cargarTablaVisitasDia("2024-10-22", "1");
		//cargarTablaDistribucionNegocio("2024-10-22", "5", "1");
		//cargarTablaVentaCategoria("2024-10-22", "1", "PREVENTA");
		//cargarTablaVentaCliente("2024-10-22", "1", "PREVENTA", "1");
	}

	$("#btnAplicar").on("click", function(){
		var fecha = $("#txtFecha").val();
		var idsucursal = $("#cmbSucursal").val();
		var idproveedor = $("#cmbNegocio").val();

		cargarTablaVisitasDia(fecha, idsucursal);
		cargarTablaDistribucionNegocio(fecha, idproveedor, idsucursal);

		$("#lblTituloVentaCategoria").text("Venta x Categoria");
		$("#lblTituloVentaCliente").text("Venta x Cliente");

		$('#table_venta_categoria tbody').html("");
		$('#table_venta_categoria tfoot').html("");

		$('#table_venta_cliente tbody').html("");
		$('#table_venta_cliente tfoot').html("");
	});

	$("#cmbNegocio").on("change", function(){
		var fecha = $("#txtFecha").val();
		var idsucursal = $("#cmbSucursal").val();
		var idproveedor = $(this).val();

		cargarTablaDistribucionNegocio(fecha, idproveedor, idsucursal);
	});

	function cargarFunciones1(pRuta)
	{
		var fecha = $("#txtFecha").val();
		var tipo = "PREVENTA";

		cargarTablaVentaCategoria(fecha, pRuta, tipo);

		$("#lblTituloVentaCliente").text("Venta x Cliente");

		$('#table_venta_cliente tbody').html("");
		$('#table_venta_cliente tfoot').html("");
	}

	function cargarFunciones2(pRuta, pCategoria, pIdRuta, pIdCategoria)
	{
		var fecha = $("#txtFecha").val();
		var tipo = "PREVENTA";

		$("#lblTituloVentaCliente").text("Venta x Cliente Ruta: " + pRuta + " Categoria: " + pCategoria);

		cargarTablaVentaCliente(fecha, pIdRuta, tipo, pIdCategoria);
	}

	function computeTableColumnTotal(colNumber)
	{
		var result = 0;

		try 
		{
			var tableBody = document.querySelector("#table_visitas tbody");
			var howManyRows = tableBody.rows.length;

			for (var i = 0; i < howManyRows; i++) 
			{
				var thisNumber = parseInt(tableBody.rows[i].cells[colNumber].childNodes.item(0).data);

				if (!isNaN(thisNumber))
					result += thisNumber;
			}
		} 
		finally 
		{
			return result;
		}
	}

	function cargarTablaVisitasDia(pFecha, pIdSucursal)
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		$("#lblTituloVisitasHora").text("Visitas x Hora: " + $("#cmbSucursal option:selected").text() + " Fecha: " + $("#txtFecha").val());

		var cadena = "";

		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");
		$('#table_visitas thead').html("");
		$('#table_visitas tfoot').html("");

		$.get("<?php echo LINKPROYECTO('Reportes/getReporteMesaControlJson/') ?>" + pFecha + "/" + pIdSucursal, function(data)
		{
			var datos = JSON.parse(data);
			var columnas = datos["columnas"];
			var valores = datos["valores"];
			
			if(columnas.length > 0)
			{
				cadena = cadena + "<tr>";
				for(var x in columnas)
				{
					if(columnas[x] == "Ruta")
					{
						cadena = cadena + "<th>" + columnas[x] + "</th>";	
					}
					else
					{
						cadena = cadena + "<th style='text-align:center'>" + columnas[x] + "</th>";
					}
				}
				cadena = cadena + "</tr>";

				$("#table_visitas thead").html(cadena);

				cadena = "";
				for(var x in valores)
				{
					cadena = cadena + "<tr>";
					for(var col in columnas)
					{
						if(columnas[col] == "Ruta")
						{
							cadena = cadena + "<td><button class='btn btn-link' onclick=cargarFunciones1('" + valores[x][columnas[col]] + "')>" + valores[x][columnas[col]] + "</button></td>";
						}
						else
						{
							cadena = cadena + "<td align='right'>" + valores[x][columnas[col]] + "</td>";
						}
					}
					cadena = cadena + "</tr>";
				}

				$("#table_visitas tbody").html(cadena);

				cadena = "";
				cadena = cadena + "<tr>";
				for(var x in columnas)
				{
					if(columnas[x] == "Ruta")
					{
						cadena = cadena + "<th>Total</th>";	
					}
					else
					{
						cadena = cadena + "<th style='text-align:right' id='total" + x + "'></th>";
					}
				}
				cadena = cadena + "</tr>";

				$("#table_visitas tfoot").html(cadena);

				var final = 0
				var tbody = document.querySelector("#table_visitas tbody");
				var howManyCols = tbody.rows[0].cells.length;
				var totalRow = tbody.rows[tbody.rows.length - 1];
				for (var j = 1; j < howManyCols; j++)
				{
					final = computeTableColumnTotal(j);
					$("#total" + j).text(final);
					//totalRow.cells[j].innerText = final;
				}
			}
			else
			{
				
			}
		}).always(function() {
			$('#table_visitas').removeClass('loadingtable');
		});
	}

	function cargarTablaDistribucionNegocio(pFecha, pIdProveedor, pIdSucursal)
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		$("#lblTituloDistribucionNegocio").text("Distribucion x Negocio: " + $("#cmbNegocio option:selected").text() + " Fecha: " + pFecha);

		var cadena = "";

		if(pIdProveedor == 0)
		{
			$('#table_distribucion_negocio tbody').html("");
			$('#table_distribucion_negocio tfoot').html("");
			return;	
		}

		$('#table_distribucion_negocio').addClass('loadingtable');
		$('#table_distribucion_negocio tbody').html("");
		$('#table_distribucion_negocio tfoot').html("");

		$.get("<?php echo LINKPROYECTO('Reportes/getReporteDistribucionNegocioJson/') ?>" + pFecha + "/" + pIdProveedor + "/" + pIdSucursal, function(data)
		{
			var datos = JSON.parse(data);
			
			if(datos.length > 0)
			{
				var totalpreventa = 0, totalpedidos = 0, totalvisitasprogramadas = 0, efectividad = 0;
				cadena = "";
				for(var x in datos)
				{
					cadena = cadena + "<tr>";
					cadena = cadena + "<td>" + datos[x]["negocio"] + "</td>";
					cadena = cadena + "<td>" + datos[x]["ruta_nombre"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["grantotal"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["visitas_programadas"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["cumplimiento_agenda"] + "%</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["dropsize"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["pedidos"] + "</td>";
					cadena = cadena + "<td>" + datos[x]["inicio_ruta"] + "</td>";
					cadena = cadena + "<td>" + datos[x]["fin_ruta"] + "</td>";
					cadena = cadena + "<td>" + datos[x]["tiempo_laborado"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["efectividad"] + "%</td>";
					cadena = cadena + "</tr>";

					totalpreventa = totalpreventa + parseFloat(datos[x]["grantotal"].replace(',', ''));
					totalpedidos = totalpedidos + parseFloat(datos[x]["pedidos"]);
					totalvisitasprogramadas = totalvisitasprogramadas + parseFloat(datos[x]["visitas_programadas"]);
				}

				efectividad = (totalpedidos / totalvisitasprogramadas) * 100;

				$("#table_distribucion_negocio tbody").html(cadena);

				cadena = "";
				cadena = cadena + "<tr>";
				cadena = cadena + "<th></th>";
				cadena = cadena + "<th></th>";
				cadena = cadena + "<th style='text-align:right'>" + dollarUS.format(totalpreventa).replace('$', '') + "</th>";
				cadena = cadena + "<th style='text-align:right'>" + totalvisitasprogramadas + "</th>";
				cadena = cadena + "<th></th>";
				cadena = cadena + "<th></th>";
				cadena = cadena + "<th style='text-align:right'>" + totalpedidos + "</th>";
				cadena = cadena + "<th></th>";
				cadena = cadena + "<th></th>";
				cadena = cadena + "<th></th>";
				cadena = cadena + "<th style='text-align:right'>" + efectividad.toFixed(1) + "%</th>";
				cadena = cadena + "</tr>";

				$("#table_distribucion_negocio tfoot").html(cadena);
			}
			else
			{
				
			}
		}).always(function() {
			$('#table_distribucion_negocio').removeClass('loadingtable');
		});
	}

	function cargarTablaVentaCategoria(pFecha, pIdRuta, pTipo)
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		$("#lblTituloVentaCategoria").text("Venta x Categoria: " + pIdRuta);

		var cadena = "";

		$('#table_venta_categoria').addClass('loadingtable');
		$('#table_venta_categoria tbody').html("");
		$('#table_venta_categoria tfoot').html("");

		$.get("<?php echo LINKPROYECTO('Reportes/getReporteVentaCategoriaJson/') ?>" + pFecha + "/" + pIdRuta + "/" + pTipo, function(data)
		{
			var datos = JSON.parse(data);
			
			if(datos.length > 0)
			{
				var totalpreventa = 0, totalclientes = 0, totalproductos = 0;
				cadena = "";
				for(var x in datos)
				{
					cadena = cadena + "<tr>";
					cadena = cadena + "<td><button class='btn btn-link' onclick='cargarFunciones2(\"" + datos[x]["ruta_nombre"] + "\",\"" + datos[x]["categoria_nombre"] + "\",\"" + datos[x]["ruta"] + "\",\"" + datos[x]["idclasificacion"] + "\")'>" + datos[x]["categoria_nombre"] + "</button></td>";
					//cadena = cadena + "<td><button class='btn btn-link' onclick='cargarFunciones2(\"" + datos[x] + "\")'>" + datos[x]["categoria_nombre"] + "</button></td>";
					cadena = cadena + "<td align='right'>" + datos[x]["grantotal"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["clientes"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["productos"] + "</td>";
					cadena = cadena + "</tr>";

					totalpreventa = totalpreventa + parseFloat(datos[x]["grantotal"].replace(',', ''));
					totalclientes = totalclientes + parseFloat(datos[x]["clientes"]);
					totalproductos = totalproductos + parseFloat(datos[x]["productos"]);
				}

				$("#table_venta_categoria tbody").html(cadena);

				cadena = "";
				cadena = cadena + "<tr>";
				cadena = cadena + "<th></th>";
				cadena = cadena + "<th style='text-align:right'>" + dollarUS.format(totalpreventa).replace('$', '') + "</th>";
				cadena = cadena + "<th style='text-align:right'>" + totalclientes + "</th>";
				cadena = cadena + "<th style='text-align:right'>" + totalproductos + "</th>";
				cadena = cadena + "</tr>";

				$("#table_venta_categoria tfoot").html(cadena);
			}
			else
			{
				
			}
		}).always(function() {
			$('#table_venta_categoria').removeClass('loadingtable');
		});
	}

	function cargarTablaVentaCliente(pFecha, pIdRuta, pTipo, pIdCategoria)
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});		

		var cadena = "";

		$('#table_venta_cliente').addClass('loadingtable');
		$('#table_venta_cliente tbody').html("");

		$.get("<?php echo LINKPROYECTO('Reportes/getReporteVentaClienteJson/') ?>" + pFecha + "/" + pIdRuta + "/" + pTipo + "/" + pIdCategoria, function(data)
		{
			var datos = JSON.parse(data);
			
			if(datos.length > 0)
			{
				var totalpreventa = 0, totalclientes = 0, totalproductos = 0, totalpedidos = 0;
				cadena = "";
				for(var x in datos)
				{
					cadena = cadena + "<tr>";
					cadena = cadena + "<td>" + datos[x]["folio"] + "</td>";
					cadena = cadena + "<td>" + datos[x]["cliente"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["grantotal"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["productos"] + "</td>";
					cadena = cadena + "</tr>";

					if(datos[x]["folio"] != "")
					{
						totalpedidos = totalpedidos + 1;
					}

					totalpreventa = totalpreventa + parseFloat(datos[x]["grantotal"].replace(',', ''));
					totalclientes = totalclientes + 1;
					totalproductos = totalproductos + parseFloat(datos[x]["productos"]);
				}

				$("#table_venta_cliente tbody").html(cadena);

				cadena = "";
				cadena = cadena + "<tr>";
				cadena = cadena + "<th style='text-align:right'>" + totalpedidos + "</th>";
				cadena = cadena + "<th style='text-align:right'>" + totalclientes + "</th>";
				cadena = cadena + "<th style='text-align:right'>" + dollarUS.format(totalpreventa).replace('$', '') + "</th>";
				cadena = cadena + "<th style='text-align:right'>" + totalproductos + "</th>";
				cadena = cadena + "</tr>";

				$("#table_venta_cliente tfoot").html(cadena);
			}
			else
			{
				
			}
		}).always(function() {
			$('#table_venta_cliente').removeClass('loadingtable');
		});
	}
	
</script>