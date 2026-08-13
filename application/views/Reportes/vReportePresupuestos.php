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
									Reportes / Reporte de Presupuestos
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row">
									<div class="col-xs-2">
										<label for="txtMes1">Inicio</label>
										<input id="txtMes1" type="month" placeholder="MES de AÑO" class="form-control" value="<?php echo $periodo; ?>" />
									</div>
									<div class="col-xs-2">
										<label for="txtMes2">Fin</label>
										<input id="txtMes2" type="month" placeholder="MES de AÑO" class="form-control" value="<?php echo $periodo; ?>" />
									</div>
									<div class="col-xs-2"><label for="">Presupuesto</label><br>
										<select name="cmbPresupuesto" id="cmbPresupuesto">
											<option value="0">[Seleccione uno]</option>
											<option value="v">Ventas</option>
											<option value="cv">Costo Ventas</option>
											<option value="g">Gastos</option>
											<option value="oi">Otros Ingresos</option>
											<option value="uo">Utilidad Operativa</option>
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
														<th width="20%" style="text-align: center;">Sucursal</th>
														<th width="20%" style="text-align: center;">Presupuestado</th>
														<th width="20%" style="text-align: center;">Real</th>
														<th width="20%" style="text-align: center;">Comparativo</th>
														<th width="20%" style="text-align: center;">%</th>
													</tr>
												</thead>
												<tbody>
														
												</tbody>
												<tfoot>
													<tr>
														<td><b>Totales:</b></td>
														<td align="right"><b id="lblTotalPresupuestado">0</b></td>
														<td align="right"><b id="lblTotalReal">0</b></td>
														<td align="right"><b id="lblTotalComparativo">0</b></td>
														<td align="right"><b id="lblTotalPorcentaje">0</b></td>
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

	var i_sucursal=0, i_presupuestado=1, i_real=2, i_comparativo=3, i_porcentaje=4;
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
		
	}	

	var myTable = 
	$('#table_visitas')
	.DataTable({
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},
			"pageLength": -1,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
	});

	$("#btnAplicar").on("click", function(){
		var mes1 = $("#txtMes1").val();
		var mes2 = $("#txtMes2").val();
		var negocio = $("#cmbNegocio").val();
		var presupuesto = $("#cmbPresupuesto").val();

		if(mes1.length == "")
		{
			alert("Favor de seleccionar el mes 1");
			return;
		}

		if(mes2.length == "")
		{
			alert("Favor de seleccionar el mes 2");
			return;
		}

		if(presupuesto == "0")
		{
			alert("Favor de seleccionar un presupuesto");
			return;
		}

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

		cargarTablaProductos(mes1, mes2, presupuesto, negocio);
	});

	function cargarTablaProductos(pMes1, pMes2, pPresupuesto, pNegocio)
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		$.post("<?php echo LINKPROYECTO('Reportes/listaReportePresupuestosJson') ?>", 
		{mes1:pMes1, mes2:pMes2, presupuesto:pPresupuesto, negocio:pNegocio}, function(data)
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
					"aaData": datos,
					"columns": [
						{ "data": "sucursal" },
						{ "data": "presupuestado" },
						{ "data": "real" },
						{ "data": "comparativo" },
						{ "data": "porcentaje" },
					],
					"columnDefs": [
						{ className: "text-right", "targets": [i_presupuestado, i_real, i_comparativo, i_porcentaje] },
					],
					drawCallback: function () {
						var api = this.api();

						var totalpresupuestado = dollarUS.format(api.column(i_presupuestado, {page:'current'} ).data().sum()).replace("$", "");
						var totalreal = dollarUS.format(api.column(i_real, {page:'current'} ).data().sum()).replace("$", "");
						var totalcomparativo = dollarUS.format(api.column(i_comparativo, {page:'current'} ).data().sum()).replace("$", "");

						var porcentaje = (api.column(i_real, {page:'current'} ).data().sum() / api.column(i_presupuestado, {page:'current'} ).data().sum()) * 100;
						var totalporcentaje = dollarUS.format(porcentaje).replace("$", "");

						$("#lblTotalPresupuestado").text(totalpresupuestado);
						$("#lblTotalReal").text(totalreal);
						$("#lblTotalComparativo").text(totalcomparativo);
						$("#lblTotalPorcentaje").text(totalporcentaje);
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
						columns: [ i_sucursal, i_presupuestado, i_real, i_comparativo, i_porcentaje ]
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