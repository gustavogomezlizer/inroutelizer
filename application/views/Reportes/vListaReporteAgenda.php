<?php 
$data['title']="LIZER Reportes-Agenda de Visitas";

$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); ?>

<div id="principal" class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						Reportes / Lista de Cumplimiento de Agenda.
					</small>
				</h1>
			</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row">
									<div class="col-xs-2">
										<label for="txtFecha">Inicio</label>
										<input id="txtFecha" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
									</div>
									<div class="col-xs-2"><label for="">Sucursal</label><br>
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
									<div class="col-xs-2"><label for="">Ruta</label><br>							
										<select name="cmbRuta" id="cmbRuta">
										</select>
									</div>
									<div class="col-xs-2"><label for="">Usuario</label><br>							
										<select name="cmbUsuario" id="cmbUsuario">
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
									<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
										<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
										<div class="clearfix">
											<!-- <div class="pull-right"><button class="btn btn-primary btnActualizar">Actualizar</button></div> -->
										</div>
										
										<div class="clearfix col-md-9" align="left">
										<div class="col-md-4">
										<h5><strong>Visitas Programadas: </strong></h5><span class="label label-xlg label-primary"><label id="lblNumVisitas">0</label></span>
											</div>
										<div class="col-md-4">
										<h5><strong>Visitas Programadas Hechas: </strong></h5><span class="label label-xlg label-primary"><label id="lblNumPedidos">0</label></span>
										</div>
										<div class="col-md-4">
										<h5><strong>Porcentaje Efectividad: </strong></h5><span class="label label-xlg label-primary"><label id="lblTotPedidos">%0.00</label></span><br>
										</div>
										
									</div>
										<div class="clearfix col-md-3">
											<div class="pull-right tableTools-container">
												<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
												<button class="btn btn-success btnActualizar">Actualizar</button> <br><br>
											</div>
										</div>
										
										<!--<div class="clearfix">
											<div class="pull-right tableTools-container"><button class="btn btn-white btnSacarTabla"><i class="ace-icon fa fa-file-excel-o bigger-130"></i>Generar Excel</button></div>
										</div>-->

										</div>
										</div>
										<div class="col-xs-12">
										<div class="table-header">
											Listado de Cumplimiento de Agenda.
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="table_visitas" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="10%">Fecha</th>
														<th width="20%">Usuario</th>
														<th width="10%">Ruta</th>
														<th width="5%">Hechas</th>
														<th width="5%">Programadas</th>
														<th width="15%">Efectividad (Hechas/Programadas)</th>
														<th width="10%">Sucursal</th>
														<th width="15%">Acciones</th>
													</tr>
												</thead>
												<tbody>
													
													
													<!--<?php 
													
														/*$contador=0;
													if(($lista->num_rows()!=0)){
													

													foreach ($lista->result() as $kLC) {
														
														$datosPedidos=$this->ReportesModel->getPedidosDatos($fIni,$fFin,$kLC->idUsuario);
														$datosVisitas=$this->ReportesModel->getDatosAgenda($fIni,$fFin,$kLC->idRuta);
														$cadenaVisitas=explode("-", $datosVisitas);
														$programadas=$cadenaVisitas[0];
														$hechas=$cadenaVisitas[1];
														$numeroVisitas=1;
														$cuantasvisitas=1;
														$numeroVentas=1;
														$cuantospedidos=1;
														$primera=$fIni;
														$ultima=$fFin;
														
														?>
													<tr>
														<td>
														
															<?php 
															
															 echo $kLC->nombre;
														?>
														</td>
														<td><?php echo $kLC->ruta; ?></td>
														<td><?php 
														//$numeroVisitas."/".$numeroVentas;
														
														if(($programadas==0)OR($hechas==0)){
															$porcTot=0;
															$porcPar=0;

														}
														else{
															$porcTot=$programadas/100;
															$porcPar=$hechas/$porcTot;
														}
															echo FORMATO_PORCENTAJEDEC($porcPar)." (".$hechas."/".$programadas.")"; ?>
															</td>
															<td><?php echo $hechas; ?></td>
															<td><?php echo $programadas; ?></td>
														<td><?php echo $primera; ?></td>
														<td><?php echo $ultima; ?></td>
														
													
														
														<td>
															<?php 
															 echo $kLC->sucursal;
														?>
														</td>
														
														<td>
															<?php 
																//$idP=$this->ReportesModel->getIdPedido($kLC->idcliente,$kLC->fecha);
																$idP=1;
															 ?>
														<div class="hidden-sm hidden-xs action-buttons">
															<?php if($idP!=0){ ?>
																<a id="VER1<?php echo $kLC->idUsuario; ?>" class="blue verPedido1">
																	<i class="ace-icon fa fa-eye bigger-130"></i>
																</a>
															<?php } ?>
																
																<!-- <a id="MAP1<?php echo $kLC->id; ?>" class="red verMapa1" href="#">
																	<i class="ace-icon fa fa-map-marker bigger-130"></i>
																</a> -->

																
															</div>

															<div class="hidden-md hidden-lg">
																<div class="inline pos-rel">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>
<?php if($idP!=0){ ?>
																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>

																			<a id="VER2<?php echo $kLC->idUsuario; ?>" class="tooltip-info verPedido1" data-rel="tooltip" title="Ver">
																				<span class="blue">
																					<i class="ace-icon fa fa-eye bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		
																		<!-- <li>
																			<a id="MAP2<?php echo $kLC->id; ?>" href="#" class="tooltip-danger" data-rel="tooltip" title="Ubicacion">
																				<span class="green">
																					<i class="ace-icon fa fa-map-marker bigger-120"></i>
																				</span>
																			</a>
																		</li> -->

																		
																	</ul>
																	<?php }} ?>
																</div>
															</div></td>
													</tr>
														<?php 

													$contador=$contador+1;
													}*/
													 ?>-->
														
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

	var i_fecha=0, i_usuario=1, i_ruta=2, i_hechas=3, i_programadas=4, i_efectividad=5, i_sucursal=6, i_acciones=7;
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
			$("#cmbUsuario").html("");
			$("#cmbRuta").html("");			
			return;
		}
		var idSucursal = $(this).val().toString();
		$.post("<?php echo CCATALOGOS('createComboAgente');?>", {sucursal: idSucursal},function(data){
			$("#cmbUsuario").html(data);			
		});

		$.post("<?php echo CCATALOGOS('createComboRutas');?>", {sucursal: idSucursal},function(data){
			$("#cmbRuta").html(data);
		});
	});

	$("#btnAplicar").on("click", function(){
		cargarTablaProductos($("#txtFecha").val(), $("#cmbSucursal").val(), $("#cmbRuta").val(), $("#cmbUsuario").val());
	});

	function cargarTablaProductos(pFecha, pSucursal, pRuta, pUsuario)
	{
		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		if(pSucursal==null) pSucursal = "0";
		if(pRuta==null) pRuta = "0";
		if(pUsuario==null) pUsuario = "0";

		$.post("<?php echo LINKPROYECTO('CumplimientoAgendaJson') ?>", 
		{ fecha:pFecha, sucursal:pSucursal, ruta:pRuta, usuario:pUsuario}, function(data)
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
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "fecha_agenda" },
						{ "data": "usuario_nombre" },
						{ "data": "ruta" },						
						{ "data": "visito" },
						{ "data": "clientes" },
						{ "data": null },
						{ "data": "sucursal_nombre" },
						{ "data": null },
					],
					"columnDefs": [
						{
							"render": function ( data, type, row ) {
								return (row.visito>0) ? "<button title='Ver Visita' style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>" : "&nbsp;";
							},
							"targets": i_acciones,
						},
						{
							"render": function ( data, type, row ) {
								return formatMoney((parseFloat(row.visito) / parseFloat(row.clientes)) * 100) + "%";
							},
							"targets": i_efectividad,
						},
						{ className: "text-right", "targets": [i_hechas, i_programadas, i_efectividad] },
					]
				});

				var total_clientes = parseFloat(myTable.column( i_programadas ).data().sum());
				var total_visitadas = parseFloat(myTable.column( i_hechas ).data().sum());

				$("#lblNumVisitas").text(total_clientes);
				$("#lblNumPedidos").text(total_visitadas);
				$("#lblTotPedidos").text( formatMoney((total_visitadas/total_clientes)*100) + "%" );

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#table_visitas tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						window.open("<?php echo LINKPROYECTO('VerVisitas/'); ?>" + row.chofer + "/" + row.fecha_agenda + "/0", "_blank");
					});
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
				"title": 'Listado - Cumplimiento Agenda',
				"exportOptions": {
						columns: [ i_fecha, i_usuario, i_ruta, i_hechas, i_programadas, i_efectividad, i_sucursal ]
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