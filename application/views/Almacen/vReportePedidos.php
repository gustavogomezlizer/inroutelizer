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
									Almacen / Reporte Pedidos
								</small>
							</h1>
						</div><!-- /.page-header -->						

						<div class="row">							

							<div class="col-xs-12">
								<div class="row">

									<div class="col-xs-2">
										<label for="txtFecha">Fecha</label>
										<input type="date" name="fecha" id="txtFecha"  class="form-control" value="<?php echo GETFECHA(); ?>" />
									</div>

									<div class="col-xs-2">
										<label for="cmbSucursal">Sucursal</label>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<!--<option value="0">TODAS</option>-->
												<?php foreach ($listaSucursales->result() as $item) { ?>
													<option value="<?php echo $item->id; ?>" <?php echo (GETSUCURSAL()==$item->id) ? 'selected' : ''; ?> ><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } else { ?>
												<?php foreach ($listaSucursales->result() as $item) { ?>
														<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
													<?php /*if(GETSUCURSAL()==$item->id) { ?>
														<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
													<?php }*/ ?>
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

										<div class="col-xs-12">

											<div class="table-header">
												<b id="lblTituloDistribucionNegocio">Reporte Pedidos</b>
											</div>

											<div class="table-responsive">
												<table id="table_rutas" width="100%" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Ruta</th>
															<th>Pedidos</th>
															<th>Preventa</th>
															<th>Devolución</th>
															<th>Total</th>
														</tr>
													</thead>

													<tbody></tbody>

													<tfoot></tfoot>
												</table>
											</div>
										</div>

										<div class="col-xs-12" align="right">
											<button id="btbReporteOtReparto" class="btn btn-info">
												<i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
												Reporte de OT Reparto
											</button>
											<button id="btbReporteOts" class="btn btn-info">
												<i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
												Reporte de OT's
											</button>
											<button id="btbReporteLibros" class="btn btn-info">
												<i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
												Reporte Libros
											</button>
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
		
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
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

		//$("#btnAbrirConfirmacionEntregas").hide();
	}

	$("#btnAplicar").on("click", function(){
		var fecha = $("#txtFecha").val();
		var idsucursal = $("#cmbSucursal").val();

		cargarRutas(fecha, idsucursal);
	});

	$("#btbReporteOtReparto").on("click", function(){
		var fecha = $("#txtFecha").val();
		var idsucursal = $("#cmbSucursal").val();

		window.open("<?php echo LINKPROYECTO('Almacen/reporteOtRepartoPdf/') ?>" + fecha + "/" + idsucursal, "_blank");
	});

	$("#btbReporteOts").on("click", function(){
		var fecha = $("#txtFecha").val();
		var idsucursal = $("#cmbSucursal").val();

		window.open("<?php echo LINKPROYECTO('Almacen/reporteOtsPdf/') ?>" + fecha + "/" + idsucursal, "_blank");
	});

	$("#btbReporteLibros").on("click", function(){
		var fecha = $("#txtFecha").val();
		var idsucursal = $("#cmbSucursal").val();

		window.open("<?php echo LINKPROYECTO('Almacen/reporteLibrosPdf/') ?>" + fecha + "/" + idsucursal, "_blank");
	});

	function cargarRutas(pFecha, pIdSucursal)
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		var cadena = "";		

		$('#table_rutas').addClass('loadingtable');
		$('#table_rutas tbody').html("");
		$('#table_rutas tfoot').html("");

		$.get("<?php echo LINKPROYECTO('Almacen/getReportePedidosJson/') ?>" + pFecha + "/" + pIdSucursal, function(data)
		{
			var datos = JSON.parse(data);
			
			if(datos.length > 0)
			{
				cadena = "";
				for(var x in datos)
				{
					var total = parseFloat(datos[x]["total_preventa"]) - parseFloat(datos[x]["total_devolucion"]);

					cadena = cadena + "<tr>";
					cadena = cadena + "<td>" + datos[x]["ruta_nombre"] + "</td>";
					cadena = cadena + "<td align='right'>" + datos[x]["cantidad_pedidos"] +"</td>";
					cadena = cadena + "<td align='right'>" + dollarUS.format(datos[x]["total_preventa"]).replace('$', '') + "</td>";
					cadena = cadena + "<td align='right'>" + dollarUS.format(datos[x]["total_devolucion"]).replace('$', '') + "</td>";
					cadena = cadena + "<td align='right'>" + dollarUS.format(total).replace('$', '') + "</td>";
					cadena = cadena + "</tr>";
				}

				$("#table_rutas tbody").html(cadena);
			}
			else
			{
				
			}
		}).always(function() {
			$('#table_rutas').removeClass('loadingtable');
		});
	}	
	
</script>