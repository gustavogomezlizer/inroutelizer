<?php 
$data['title']="LIZER Objetivos";
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
									Estadisticas / Objetivos
									
									
								</small>
							</h1>
							
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									<div class="col-xs-2">
										<label for="">Mes</label>
										<input id="txtPeriodo" type="input" class="form-control" value="<?php echo $periodo; ?>">
									</div>
									
									<div class="col-xs-2"><label for="">Sucursal</label><br>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<option value="0" >TODAS</option>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value="<?php echo $item->id; ?>" ><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } else { ?>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
														<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>

									<div class="col-xs-2"><label for="">Ruta</label><br>							
										<select name="cmbRuta" id="cmbRuta">
										</select>
									</div>									
							
									<div class="col-xs-2"><label for="">Categoria</label><br>
										<select name="cmbCategoria" id="cmbCategoria">
											<option value="0" selected>Todas</option>																						
											<?php foreach ($listaCategorias->result() as $kU) { ?>
												<option value="<?php echo $kU->nombre; ?>"><?php echo $kU->nombre; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
									
									<div class="row"><div class="col-xs-12"><hr></div></div>
									<div class="row">
									<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
										<div class="clearfix">
											<!-- <div class="pull-right"><button class="btn btn-primary btnActualizar">Actualizar</button></div> -->
										</div>																				
									</div>
										<div class="clearfix col-md-6 ">
											
										</div>
										<div class="clearfix col-md-6"><br></div>
										
										</div>
									
										<div class="row">

											<div class="col-xs-12"><div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
												<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
												<div class="clearfix">
													<!-- <div class="pull-right"><button class="btn btn-primary btnActualizar">Actualizar</button></div> -->
												</div>
												
												<?php 
												$datosObj=$this->EstadisticasModel->getDatosObjetivos($periodo);
												if($datosObj->num_rows()!=0){
													$diasMes=$datosObj->row()->diasMes;
													$diasTranscurridos=$datosObj->row()->diasTranscurridos;
												}
												else{
													$diasMes=0;
													$diasTranscurridos=0;
													}	
												 ?>
												
												<div class="clearfix col-md-6" align="left">
												<div class="col-md-4">
												<h4><strong>Dias Habiles: </strong></h4><span class="label label-xlg label-primary"><label id="lblDiasHabiles"><?php echo $diasMes; ?></label></span>
												
												</div>
												<div class="col-md-4">
												<h4><strong>Transcurridos: </strong></h4><span class="label label-xlg label-primary"><label id="lblDiasTranscurridos"><?php echo $diasTranscurridos; ?></label></span>
												
												</div>
												<div class="col-md-4">
												<h4><strong>Restantes: </strong></h4><span class="label label-xlg label-primary"><label id="lblDiasRestantes"><?php echo $diasMes-$diasTranscurridos; ?></label></span>
												
												</div>
												
											</div>
												<div class="clearfix col-md-6" align="right">
													<div class="pull-right">
														<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
														<button class="btn btn-success btnActualizar">Actualizar</button> <br><br>
													</div>
												</div>
												<div><br></div>
												<div class="clearfix col-md-12" align="right">
													<div class="pull-right tableTools-container"></div>
												</div>
											</div>
											</div>
											</div>
											

										<div class="col-xs-12">
										<div class="table-header">
											Objetivos y Acumulados.
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive">
											<table id="table_visitas" class="table table-striped table-bordered table-hover tablex">
												<thead>
													<tr>
														<th colspan="3" style="text-align: center">Datos</th>
														<th colspan="3" style="text-align: center">Valores Netos</th>
														<th colspan="2" style="text-align: center">Proyección</th>
														<th colspan="2" style="text-align: center">Analisis</th>
														
													</tr>
													<tr>
														<th>Sucursal</th>
														<th>Ruta</th>
														<th>Categoria</th>
														<th>Objetivo</th>
														<th>Venta</th>
														<th>Alcance</th>
														<th>Proyección $</th>
														<th>Proyección %</th>
														<th>GAP</th>
														<th>Objetivo Diario</th>
													</tr>
												</thead>
												<tbody>
												<!--<?php 
												/*if($lista->num_rows()!=0){
												 ?>
													<tr>
													<?php 
													
													foreach ($lista->result() as $kA) {
														# code...
																	
														?>
														<td>
															<?php 
															$sucursalA=$this->EstadisticasModel->getSucursalName($kA->idVendedor);
															echo $sucursalA->row()->sucursal;
															 ?>
															
															
														</td>
														<td>
															<?php 

															$rutaA=$this->EstadisticasModel->getRutaName($kA->idVendedor);
															if($rutaA->num_rows()!=0){
																echo $rutaA->row()->ruta;
															}
															else{
																echo "NR";
															}
															//echo $kA->idVendedor; ?>
														</td>
														<td>
															<?php echo $kA->categoria; ?>
														</td>
														<td>
															<?php echo FORMATO_DINERO($kA->objetivo); ?>
														</td>
														<td>
															<?php echo FORMATO_DINERO($kA->importe); ?>
														</td>
														<td>
															<?php 
																if($kA->objetivo!=0){
																	$porc=$kA->importe/($kA->objetivo/100);
																}
																else{
																	$porc=0;
																}
																echo FORMATO_PORCENTAJEDEC($porc);
															 ?>
														</td>
														<td>
															<?php 
																if($kA->diasTranscurridos!=0){
																	$ventaProy=($kA->importe/$kA->diasTranscurridos)*($kA->diasMes-$kA->diasTranscurridos);
																}
																else{
																	$ventaProy=0;
																}
																
																echo FORMATO_DINERO($ventaProy+$kA->importe);
															 ?>
														</td>
														<td>
															<?php 
																if($kA->objetivo!=0){
																	$alcanceProy=($kA->importe+$ventaProy)/($kA->objetivo/100);
																}
																else{
																	$alcanceProy=0;
																}
																
																echo FORMATO_PORCENTAJEDEC($alcanceProy);
															 ?>
														</td>														
														<td>
															
															<?php 
															
															$gap=($kA->objetivo)-$kA->importe;
															echo FORMATO_DINERO($gap); ?>
														</td>
														<td>
														<?php 
															//$objetivodiario=$kA->objetivo/$kA->diasMes;
															$diasrestantes=$kA->diasMes-$kA->diasTranscurridos;
															if($diasrestantes!=0){
																$objetivodiario=$gap/($diasrestantes);
															}
															else{
																$objetivodiario=0;
															}
															
															echo FORMATO_DINERO($objetivodiario);
														 ?>
														
														</td>
														</tr>
														<?php 

													}
												}*/
													 ?>-->
												</tbody>
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

	var i_sucursal=0, i_ruta=1, i_categoria=2, i_objetivo=3, i_venta1=4, i_alcance1=5, i_venta2=6, i_alcance2=7, i_alcancegap=8, i_objetivodiario=9;
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
		$("#btnAplicar").click();
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
		/*$.post("<?php echo CCATALOGOS('createComboAgente');?>", {sucursal: idSucursal},function(data){
			$("#cmbUsuario").html(data);			
		});*/

		$.post("<?php echo CCATALOGOS('createComboRutas');?>", {sucursal: idSucursal},function(data){
			$("#cmbRuta").html(data);
		});
	});

	$("#btnAplicar").on("click", function(){
		cargarTablaProductos($("#txtPeriodo").val(), $("#cmbSucursal").val(), $("#cmbRuta").val(), $("#cmbCategoria").val());
	});

	/*$("#cmbSucursal").on("change",function(){
        myTable.column(i_sucursal).search("^" + $(this).val() + "$",true, false, true).draw();
    });*/

	function cargarTablaProductos(pPeriodo, pSucursal, pRuta, pCategoria)
	{
		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		if(pSucursal==null) pSucursal = "0";
		if(pRuta==null) pRuta = "0";
		if(pCategoria==null) pCategoria = "0";

		$.post("<?php echo LINKPROYECTO('ObjetivosAcumuladosJson') ?>", {periodo:pPeriodo, sucursal:pSucursal, ruta:pRuta, categoria:pCategoria}, function(data)
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
						{ "data": "objetivo_format" },
						{ "data": "importe_format" },
						{ "data": "alcance_format" },
						{ "data": "venta2_format" },
						{ "data": "alcance2_format" },
						{ "data": "gap_format" },
						{ "data": "objetivo_diario_format" },
					],
					"columnDefs": [
						/*{
							"render": function ( data, type, row ) {								
								return row.categoria + " (" + row.pago_categoria + ")";
							},
							"targets": i_categoria,
						},*/
						
						{ className: "text-right", "targets": [i_objetivo, i_venta1, i_alcance1, i_venta2, i_alcance2, i_alcancegap, i_objetivodiario] },
					]
				});

				if($("#cmbSucursal").val()!="0"){
					myTable.column(i_sucursal).search("^" + $("#cmbSucursal option:selected").text() + "$",true, false, true).draw();
				}

				if($("#cmbRuta").val()!="0"){
					myTable.column(i_ruta).search("^" + $("#cmbRuta option:selected").text() + "$",true, false, true).draw();
				}

				var rowData = myTable.rows(0).data()[0];

				$("#lblDiasHabiles").text(rowData["diasMes"]);
				$("#lblDiasTranscurridos").text(rowData["diasTranscurridos"]);
				$("#lblDiasRestantes").text(parseInt(rowData["diasMes"])-parseInt(rowData["diasTranscurridos"]));

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					/*$('#table_visitas tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						var myarr = row.datos_visita.split("|");
						var fecha_ini = myarr[1].split(" ")[0];
						var fecha_fin = myarr[2].split(" ")[0];
						window.open("<?php echo LINKPROYECTO('VerVisitas/'); ?>" + row.idusuario + "/" + fecha_ini + "/" + fecha_fin, "_blank");
					});*/
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
						columns: [ i_sucursal, i_ruta, i_categoria, i_objetivo, i_venta1, i_alcance1, i_venta2, i_alcance2, i_alcancegap, i_objetivodiario ]
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