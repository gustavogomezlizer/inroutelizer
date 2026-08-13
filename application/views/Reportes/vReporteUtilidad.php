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
									Reportes / Reporte de Utilidad
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row">
									<div class="col-xs-2">
										<label for="txtFechaDe">Inicio</label>
										<input id="txtFechaDe" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
									</div>
									<div class="col-xs-2">
										<label for="txtFechaA">Final</label>
										<input id="txtFechaA" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
									</div>
									<div class="col-xs-2"><label for="">Tipo</label><br>
										<select name="cmbTipo" id="cmbTipo"  class="form-control">
											<option value="preventa">Preventa</option>
											<option value="devolucion">Devolución</option>
											<option value="preventa,devolucion">Preventa - Devolución</option>
										</select>
									</div>
									<div class="col-xs-2"><label for="">Negocio</label><br>
										<select name="cmbNegocio" id="cmbNegocio">
											<option value="0">Todos</option>
											<?php foreach($proveedores as $proveedor) { ?>
												<option value="<?php echo $proveedor->id; ?>"><?php echo $proveedor->nombre; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-xs-2"><label for="">Sucursal</label><br>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<option value="0">TODAS</option>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value="<?php echo $item->id; ?>"><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="col-xs-2"><label for="">Con Impuestos</label><br>
										<input type="checkbox" id="chkImpuestos" />
									</div>
								</div>

									<div class="row">
									<div class="col-xs-12"><br>
									</div>
									</div>
									</div>
									<div class="row"><div class="col-xs-12"><hr></div></div>
									
									<div class="row">
										<div class="col-xs-12">
											<div class="clearfix">
										</div>

										<div class="clearfix col-md-12" align="right">
											<div class="pull-right">
												<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
												<button class="btn btn-success btnActualizar">Actualizar</button> <br><br>
											</div>
										</div>

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
														<th width="9%" style="text-align: center;">Venta</th>
														<th width="9%" style="text-align: center;">% Participacion de Ventas</th>
														<th width="9%" style="text-align: center;">Otros Ingresos</th>
														<th width="9%" style="text-align: center;">Total Ingresos</th>
														<th width="9%" style="text-align: center;">Costo de Venta</th>
														<th width="9%" style="text-align: center;">Utilidad Bruta</th>
														<th width="9%" style="text-align: center;">Gastos</th>
														<th width="9%" style="text-align: center;">% Gastos/Ventas</th>
														<th width="9%" style="text-align: center;">Margen Utilidad</th>
														<th width="9%" style="text-align: center;">% Margen</th>
														<th width="9%" style="text-align: center;">Utilidad de Operación</th>
													</tr>
												</thead>
												<tbody>
														
												</tbody>
												<tfoot>
													<tr>
														<td><b>Totales:</b></td>
														<td align="right"><b id="lblTotalVenta">0</b></td>
														<td align="right"><b id="lblTotalPorcentajeParticipacion">0</b></td>
														<td align="right"><b id="lblTotalOtrosIngresos">0</b></td>
														<td align="right"><b id="lblTotalIngresos">0</b></td>
														<td align="right"><b id="lblTotalCosto">0</b></td>
														<td align="right"><b id="lblTotalUtilidadBruta">0</b></td>
														<td align="right"><b id="lblTotalGastos">0</b></td>
														<td align="right"><b id="lblTotalPorcentajeGastos">0</b></td>
														<td align="right"><b id="lblTotalImporteMargen">0</b></td>
														<td align="right"><b id="lblTotalPorcentajeMargen">0</b></td>
														<td align="right"><b id="lblTotalUtilidadNeta">0</b></td>
													</tr>
												</tfoot>
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
	i_venta=1, 
	i_porcentajeparticipacion=2, 
	i_otrosingresos=3, 
	i_totalingresos=4, 
	i_costoventa=5, 
	i_utilidadbruta=6, 
	i_gastos=7, 
	i_porcentajegastos=8, 
	i_importemargen=9, 
	i_porcentajemargen=10, 
	i_utilidadneta=11;

	var CARGAR_BOTONES_TABLA = "0";

	function formatMoney(n, c, d, t) {
	var c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = d == undefined ? "." : d,
		t = t == undefined ? "," : t,
		s = n < 0 ? "-" : "",
		i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
		j = (j = i.length) > 3 ? j % 3 : 0;

	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};

	window.onload = function()
	{
		$('#chkImpuestos').prop("checked", true);
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

	$("#btnAplicar").on("click", function(){
		var negocio = $("#cmbNegocio").val();

		if(negocio == "0")
		{
			negocio = "";

			$("#cmbNegocio option").each(function()
			{
				if($(this).val() != "0")
					negocio = negocio + $(this).val() + ","; 
			});

			negocio = negocio.slice(0, -1);
		}

		cargarTablaProductos($("#txtFechaDe").val(), $("#txtFechaA").val(), $("#cmbTipo").val(), negocio, $("#cmbSucursal").val());
	});

	function cargarTablaProductos(pFechaDe, pFechaA, pTipo, pNegocio, pSucursal)
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		if(pTipo==null) pTipo = "0";
		if(pNegocio==null) pNegocio = "0";
		var pImpuestos = $('#chkImpuestos').is(":checked") ? "1" : "0";

		$.post("<?php echo LINKPROYECTO('Reportes/listaReporteUtilidadJson') ?>", 
		{fecha_inicio:pFechaDe, fecha_final:pFechaA, tipo:pTipo, negocio:pNegocio, sucursal:pSucursal, impuestos:pImpuestos}, function(data)
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
						{ "data": "nombre_sucursal" },
						{ "data": "venta" },
						{ "data": "porcentaje_participacion" },
						{ "data": "otrosingresos" },
						{ "data": "totalingresos" },
						{ "data": "costo" },
						{ "data": "utilidad_bruta" },
						{ "data": "gastos" },
						{ "data": "porcentaje_gastos" },
						{ "data": "importe_margen" },
						{ "data": "porcentaje_margen" },
						{ "data": "utilidad_neta" }
					],
					"columnDefs": [
						{ className: "text-right", "targets": [i_venta, i_porcentajeparticipacion, i_otrosingresos, i_totalingresos, i_costoventa, i_utilidadbruta, i_gastos, i_porcentajegastos, i_importemargen, i_porcentajemargen, i_utilidadneta] },
					],
					drawCallback: function () {
						var api = this.api();

						var data = myTable.rows().data();
						/*data.each(function (value, index)
						{
							//var venta = 
							//console.log(value);
							myTable.cell(index, 2).data(1);
						});*/

						var rows = myTable.rows()[0].length;

						var totalventa = dollarUS.format(api.column(i_venta, {page:'current'} ).data().sum()).replace("$", "");
						var totalporcentajeparticipacion = dollarUS.format(api.column(i_porcentajeparticipacion, {page:'current'} ).data().sum()).replace("$", "");
						var totalotrosingresos = dollarUS.format(api.column(i_otrosingresos, {page:'current'} ).data().sum()).replace("$", "");
						var totalingresos = dollarUS.format(api.column(i_totalingresos, {page:'current'} ).data().sum()).replace("$", "");
						var totalcosto = dollarUS.format(api.column(i_costoventa, {page:'current'} ).data().sum()).replace("$", "");
						var totalutilidadbruta = dollarUS.format(api.column(i_utilidadbruta, {page:'current'} ).data().sum()).replace("$", "");
						var totalgastos = dollarUS.format(api.column(i_gastos, {page:'current'} ).data().sum()).replace("$", "");
						var totalporcentajegastos = dollarUS.format((api.column(i_gastos, {page:'current'} ).data().sum() / api.column(i_venta, {page:'current'} ).data().sum()) * 100).replace("$", "");
						var totalimportemargen = dollarUS.format(api.column(i_importemargen, {page:'current'} ).data().sum()).replace("$", "");
						var totalporcentajemargen = dollarUS.format(((api.column(i_venta, {page:'current'} ).data().sum() - api.column(i_costoventa, {page:'current'} ).data().sum()) / api.column(i_venta, {page:'current'} ).data().sum()) * 100).replace("$", "");
						var totalutilidadneta = dollarUS.format(api.column(i_utilidadneta, {page:'current'} ).data().sum()).replace("$", "");

						$("#lblTotalVenta").text(totalventa);
						$("#lblTotalPorcentajeParticipacion").text(totalporcentajeparticipacion + "%");
						$("#lblTotalOtrosIngresos").text(totalotrosingresos);
						$("#lblTotalIngresos").text(totalingresos);
						$("#lblTotalCosto").text(totalcosto);
						$("#lblTotalUtilidadBruta").text(totalutilidadbruta);
						$("#lblTotalGastos").text(totalgastos);
						$("#lblTotalPorcentajeGastos").text(totalporcentajegastos + "%");
						$("#lblTotalImporteMargen").text(totalimportemargen);
						$("#lblTotalPorcentajeMargen").text(totalporcentajemargen + "%");
						$("#lblTotalUtilidadNeta").text(totalutilidadneta);
					}
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
						columns: [ i_sucursal, i_venta, i_otrosingresos, i_totalingresos, i_costoventa, i_utilidadbruta, i_gastos, i_utilidadneta ]
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