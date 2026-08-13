<?php 
$data['title']="Proyección Nomina";
$this->load->view("vHead", $data); ?>
<?php $this->load->view("vMenu");?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Reportes / Proyección Nomina
								</small>
							</h1>
							
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-md-12">

									<div class="col-md-2">
										<label for="txtPeriodo">Mes</label>
										<input id="txtPeriodo" type="input" class="form-control" value="<?php echo $periodo; ?>" />
									</div>
									
									<div class="col-md-2">
										<label for="">Sucursal</label>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<option value="0" >TODAS</option>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value="<?php echo $item->id; ?>" ><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } else { ?>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<?php if(GETSUCURSAL()==$item->id) { ?>
														<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
													<?php } ?>
												<?php } ?>
											<?php } ?>
										</select>
									</div>

									<div class="col-md-2">
										<label for="">Ruta</label>							
										<select name="cmbRuta" id="cmbRuta">
										</select>
									</div>

									<div class="col-md-4" align="right"><br/>
										<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
										<button class="btn btn-success btnActualizar">Actualizar</button>
									</div>

									<div class="col-md-12"><hr></div>
									
									<div class="row">
									
									<div class="row">
									
										<div class="row">

											<div class="col-md-12">
												
												<?php

												$datosObj = $this->EstadisticasModel->getDatosObjetivos($periodo);
												//if($datosObj->num_rows()!=0){
												if(1==2){
													$diasMes = $datosObj->row()->diasMes;
													$diasTranscurridos = $datosObj->row()->diasTranscurridos;
													$promediopedidos = 1;
												}
												else{
													$diasMes = 0;
													$diasTranscurridos = 0;
													$promediopedidos = 0;
													}	
												?>
												
												<div class="row">

													<div align="center" class="col-md-6">
														<div class="col-md-12">
															<table border=0 width="50%">
																<tr>
																	<td><b>Concepto</b></td>
																	<td align="right"><b>Cumplimiento</b></td>
																	<td align="right"><b>Incentivo</b></td>
																</tr>
																<tr>
																	<td><b>Drop Size</b></td>
																	<td id="tdDropsize" align="right">0</td>
																	<td id="tdIncentivo" align="right" rowspan="2">0</td>
																</tr>
																<tr>
																	<td><b>Pedidos</b></td>
																	<td id="tdPedidosDa" align="right">0</td>
																</tr>
																<tr>
																	<td><b>Categorias</b></td>
																	<td id="tdCategoriasDaPorcentaje" align="right">0</td>
																	<td id="tdCategorasDaIncentivo" align="right">0</td>
																</tr>
																<tr>
																	<td align="right" colspan="2"><b>Total</b></td>
																	<td id="tdTotal" align="right">0</td>
																</tr>
															</table>
														</div>														
													</div>

													<div align="center" class="col-md-6">
														<div class="col-md-12">
															<table border=0 width="50%">
																<tr>
																	<td align="right"><b>Fecha Corte</b></td>
																	<td id="tdFechaCorte" align="right">0</td>
																</tr>
																<tr>
																	<td align="right"><b>Dias Operación</b></td>
																	<td id="tdDiasOperacion" align="right">0</td>
																</tr>
																<tr>
																	<td align="right"><b>Dias Pendientes</b></td>
																	<td id="tdDiasPendientes" align="right">0</td>
																</tr>
															</table>
														</div>
													</div>

													<!--<div class="col-md-12" align="left">
														<div class="col-md-3">
															<h4><b>Dias Habiles:</b></h4>
															<span class="label label-xlg label-primary">
																<label id="lblDiasHabiles"><?php echo $diasMes; ?></label>
															</span>
														</div>

														<div class="col-md-3">
															<h4><b>Transcurridos: </b></h4>
															<span class="label label-xlg label-primary">
																<label id="lblDiasTranscurridos"><?php echo $diasTranscurridos; ?></label>
															</span>
														</div>
														<div class="col-md-3">
															<h4><b>Restantes: </b></h4>
															<span class="label label-xlg label-primary">
																<label id="lblDiasRestantes"><?php echo $diasMes-$diasTranscurridos; ?></label>
															</span>
														</div>
														<div class="col-md-3">
															<h4><b>Pro.Pedidos: </b></h4>
															<span class="label label-xlg label-primary">
																<label id="lblPromedioPedidos"><?php echo $promediopedidos; ?></label>
															</span>
														</div>
													</div>-->
												</div>												
												
												<br/>
												

												<div class="clearfix col-md-12" align="right">
													<div class="pull-right tableTools-container"></div>
												</div>

											</div>
											</div>
											</div>
											

										<div class="row col-md-12">
											<div class="table-header">
												Proyección Nomina
											</div>

											<div class="table-responsive">
												<table id="table_visitas" width="100%" class="table table-striped table-bordered table-hover tablex">
													<thead>
														<tr>														
															<th colspan="3" style="text-align: center">Datos</th>
															<th colspan="3" style="text-align: center">Valores Netos</th>
															<th colspan="2" style="text-align: center">Proyección Venta</th>
															<th colspan="2" style="text-align: center">Proyección Nomina</th>
														</tr>
														<tr>
															<th>Sucursal</th>
															<th>Ruta</th>
															<th>Categoria</th>
															<th>Objetivo</th>
															<th>Venta</th>
															<th>Alcance</th>
															<th>Venta</th>
															<th>Alcance</th>
															<th>Pago categoria</th>
															<th>Total</th>
														</tr>
													</thead>

													<tbody>
													</tbody>
													
													<tfoot>
														<th colspan="8" align="left">Totales:</th>
														<th align="right"></th>
														<th align="right"></th>
													</tfoot>

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

</div><!-- /.main-container -->

<?php $this->load->view("vFooter"); ?>

	</body>
</html>
		
<script>

	var i_sucursal=0, i_ruta=1, i_categoria=2, i_objetivo=3, i_venta1=4, i_alcance1=5, i_venta2=6, i_alcance2=7,i_pago_categoria=8,i_total=9  /*, i_alcancegap=8, i_objetivodiario=9*/;
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
		$("#cmbSucursal").change();
		//$("#btnAplicar").click();
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

	$("#cmbSucursal").on("change", function(){
		if($(this).val()==null){			
			$("#cmbRuta").html("");			
			return;
		}

		var idSucursal = $(this).val().toString();

		$.post("<?php echo CCATALOGOS('createComboRutasUsuarios');?>", {sucursal: idSucursal},function(data){
			$("#cmbRuta").html(data);
		});
	});

	$("#btnAplicar").on("click", function(){
		if($("#cmbRuta").val()==0){
			dialogAvisoGlobal.show("Favor de seleccionar una ruta", "alert alert-warning");
		}else{
			cargarTablaProductos($("#txtPeriodo").val(), $("#cmbSucursal").val(), $("#cmbRuta").val());
		}		
	});

	function cargarTablaProductos(pPeriodo, pSucursal, pRuta)
	{
		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		if(pSucursal==null) pSucursal = "0";
		if(pRuta==null) pRuta = "0";

		$.post("<?php echo LINKPROYECTO('ProyeccionNominaJson') ?>", {periodo:pPeriodo, sucursal:pSucursal, ruta:pRuta}, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				var diashabiles=0, diastranscurridos=0, diasrestantes=0;
				myTable.destroy();
				myTable = $('#table_visitas').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 50,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "sucursal" },
						{ "data": "ruta" },
						{ "data": "categoria" },						
						{ "data": "objetivo" },
						{ "data": "importe" },
						{ "data": "alcance" },
						{ "data": "venta2" },
						{ "data": "alcance2" },
						{ "data": "pago_categoria" },
						{ "data": "pago_categoria" },
					],
					"columnDefs": [
						
						{ className: "text-right", "targets": [i_objetivo, i_venta1, i_alcance1, i_venta2, i_alcance2, i_pago_categoria, i_total/*, i_alcancegap, i_objetivodiario*/] },
					],
					"footerCallback": function ( row, data, start, end, display ) {
            			var api = this.api(), data;

						var intVal = function ( i ) {
							return typeof i === 'string' ?
								i.replace(/[\$,]/g, '')*1 :
								typeof i === 'number' ?
									i : 0;
						};

						var total_pedidos = data[0].pago_pedidos.replace("$","").replace(",","");
						var total_pago_categorias = api.column( i_pago_categoria ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
						var gran_total = api.column( i_total ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
						//pageTotal = api.column( 4, { page: 'current'} ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );

						//$( api.column( i_pedidos ).footer() ).html('$' + formatMoney(total_pedidos));
						$( api.column( i_pago_categoria ).footer() ).html('$' + formatMoney(total_pago_categorias));
						$( api.column( i_total ).footer() ).html('$' + formatMoney( parseFloat(total_pedidos) + gran_total ));
					}
				});

				var rowData = myTable.rows(0).data()[0];
				var total_categorias = 0;

				myTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
					var data = this.data();
					var total = data["pago_categoria"].replace('$', '');
					total = total.replace(',', '');
					total_categorias = total_categorias + parseFloat(total);
				});

				var porcentaje_categorias = (total_categorias / parseFloat(rowData["pago_total_categorias"])) * 100

				$("#tdFechaCorte").text(rowData["fecha"]);
				$("#tdDiasOperacion").text(rowData["diasTranscurridos"]);
				$("#tdDiasPendientes").text(parseInt(rowData["diasMes"])-parseInt(rowData["diasTranscurridos"]));

				$("#tdDropsize").text(rowData["dropsize"]);
				$("#tdPedidosDa").text(rowData["promedio_ventas"]);
				$("#tdIncentivo").text(formatMoney(rowData["incentivo_pedidos"]));
				$("#tdCategoriasDaPorcentaje").text(formatMoney(porcentaje_categorias) + '%');
				$("#tdCategorasDaIncentivo").text(formatMoney(total_categorias));

				$("#tdTotal").text(formatMoney(total_categorias + parseFloat(rowData["incentivo_pedidos"])));

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
				"title": 'Listado - Acumulados',
				"exportOptions": {
						columns: [ i_sucursal, i_ruta, i_categoria, i_objetivo, i_venta1, i_alcance1, i_venta2, i_alcance2, i_pago_categoria, i_total/*, i_alcancegap, i_objetivodiario*/ ]
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