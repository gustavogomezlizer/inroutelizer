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
									Reportes / Reporte Cortes
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
									
									<div class="row">
										<div class="col-xs-12">
											<div class="clearfix">
										</div>

										<!--<div class="clearfix col-md-12" align="right">
											<div class="pull-right">
												<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
												<button class="btn btn-success btnActualizar">Actualizar</button> <br><br>
											</div>
										</div>-->

										<div class="clearfix">
											<div class="pull-right tableTools-container">
												<!--<button class="btn btn-white btnSacarTabla"><i class="ace-icon fa fa-file-excel-o bigger-130"></i>Generar Excel</button>-->
											</div>
										</div>
										</div>
										</div>
										<div class="col-xs-12">
										<div class="table-header">
											Listado
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="table_visitas" width="100%" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="9%" style="text-align: center;">Sucursal</th>
														<th width="9%" style="text-align: center;">Pendientes por Hacer</th>
														<th width="9%" style="text-align: center;">Fecha Pendientes por Hacer</th>
														<th width="9%" style="text-align: center;">Pendientes por Facturar</th>
														<th width="9%" style="text-align: center;">Fecha Pendientes por Facturar</th>
													</tr>
												</thead>
												<tbody>
														
												</tbody>
											</table>
											
										</div><!-- empieza div que contiene a la tabla -->
									</div><!--  termina div.col-xs-12 de la tabla clientes-->
								</div><!--  termina div.row de la tabla clientes-->
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
		cargarTablaProductos();
	}

	var myTable = 
	$('#table_visitas')
	.DataTable({
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},
			"pageLength": -1,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
			"order": [[0,"asc"]],
	});

	function cargarTablaProductos()
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		$.post("<?php echo LINKPROYECTO('Reportes/listaCortesPendientesJson') ?>", 
		{}, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#table_visitas').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 50,
					"ordering": false,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "sucursal" },
						{ "data": "pendientes_hacer" },
						{ "data": "pendientes_hacer_numero" },
						{ "data": "pendientes" },
						{ "data": "fechas" }
					],
					"columnDefs": [
						{ className: "text-right", "targets": [i_pendientes] },
					],
				});

				cargarBotonesTabla();
			}
			else
			{
				myTable.clear().draw();
			}
		}).always(function() {
			$('#table_visitas').removeClass('loadingtable');
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
				"title": 'Listado - Efectividad Agenda',
				"exportOptions": {
						columns: [ 0, 1, 2 ]
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
</script>