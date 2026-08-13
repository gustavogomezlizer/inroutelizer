<?php 
$data['title']="LIZER Reportes-Visitas";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); ?>

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Reportes / Visitas	</small>
				</h1>
				<div class="row">
					<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-2">
							<label for="">Inicio</label>
							<input id="txtFInicio" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
						</div>
						<div class="col-xs-2">
							<label for="">Final</label>
							<input id="txtFFinal" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
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

					<div class="row"><div class="col-xs-12"><hr></div></div>
						<div class="row">

							<div class="col-xs-12">
								<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->										
									<div class="clearfix">											</div>
									
									<div class="clearfix col-md-6" align="left">
									<div class="col-md-4">
										<h4><strong>No. Visitas: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumVisitas">0</label></span>									
									</div>
									
									</div>
									<div class="clearfix col-md-6" align="right">
										<div class="pull-right">
											<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
											<button class="btn btn-success btnActualizar">Actualizar</button>
										</div>
									</div>
									<div><br></div>
									<div class="clearfix col-md-12" align="right">
										<div class="pull-right tableTools-container"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
									
									
									
										<div class="col-xs-12">
										<div class="table-header">
											Listado de Visitas.
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="table_visitas" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														
														
														<th>Fecha</th>
														<th>Inicio</th>
														<th>Fin</th>

														<th>Codigo Cliente</th>
														<th>Cliente</th>
														<th>Resultado</th>
														<th>Usuario</th>
														<th>Ruta</th>
														<th>Sucursal</th>
														<th>Comentarios</th>
														<th>Acciones</th>
														
													</tr>
												</thead>
												<tbody>
													
													
													<!--<?php 

													/*foreach ($lista->result() as $kLC) {
														$cuantasvisitas=$cuantasvisitas+1;
														?>
													<tr>
														<td><?php echo $kLC->fecha; ?></td>
														<td><?php echo $kLC->inicio; ?></td>
														<td><?php echo $kLC->fin; ?></td>
														<td>
															<?php echo $kLC->codigocliente;
															 ?>

														</td>
														<td>
															<?php 
																
																	echo $kLC->cliente;
																
																
																 ?>
																

														</td>
														<td>
															<?php 
															if($kLC->resultado=="Venta registrada"){
																$banderaver=1;
																?>
																<span class="label label-sm label-success"><?php echo $kLC->resultado; ?></span>
															<?php 
															}
																														
															else{ 
																
																?>
																<span class="label label-sm label-danger"><?php echo $kLC->resultado; ?></span>
																 <?php 
																 $banderaver=0;
															}
														?>
														</td>
														<td>
															<?php 
															 echo $kLC->nombre;
														?>
														</td>
														<td><?php 
															echo $kLC->ruta;
															?>
														</td>
														<td>
															<?php 
															 echo $kLC->sucursal;
														?>
														</td>
														
														<td>
															<?php 
																$idP=$this->ReportesModel->getIdPedido($kLC->idcliente,$kLC->fecha);

															 ?>
														<div class="hidden-sm hidden-xs action-buttons">
															<?php if(($idP!=0)AND($banderaver==1)){ ?>
																<a id="VER1<?php echo $idP; ?>" class="blue verPedido1">
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
																<?php if(($idP!=0)AND($banderaver==1)){ ?>
																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>

																			<a id="VER2<?php echo $kLC->idP; ?>" class="tooltip-info verPedido1" data-rel="tooltip" title="Ver">
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
																	<?php } ?>
																</div>
															</div></td>
													</tr>
														<?php 

													
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

	var i_fecha=0, i_inicio=1, i_fin=2, i_codigocliente=3, i_cliente=4, i_resultado=5, i_usuario=6, i_ruta=7, i_sucursal=8, i_comentarios=9, i_acciones=10;
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
		cargarTablaProductos($("#txtFInicio").val(), $("#txtFFinal").val(), $("#cmbTipo").val(), $("#cmbSucursal").val(), $("#cmbRuta").val(), $("#cmbUsuario").val());
	});

	function cargarTablaProductos(pFechade, pFechaa, pTipo, pSucursal, pRuta, pUsuario)
	{
		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		if(pTipo==null) pTipo = "0";
		if(pSucursal==null) pSucursal = "0";
		if(pRuta==null) pRuta = "0";
		if(pUsuario==null) pUsuario = "0";


		$.post("<?php echo LINKPROYECTO('VisitasJson') ?>", 
		{ fechade:pFechade, fechaa:pFechaa, tipo:pTipo, sucursal:pSucursal,ruta:pRuta, usuario:pUsuario}, function(data)
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
						{ "data": "fecha" },
						{ "data": "inicio" },
						{ "data": "fin" },
						{ "data": "codigocliente" },
						{ "data": "cliente" },
						{ "data": null },
						{ "data": "usuario" },
						{ "data": "ruta_nombre" },
						{ "data": "sucursal_nombre" },
						{ "data": "comentarios" },
						{ "data": null },
					],
					"columnDefs": [
						{
							"render": function ( data, type, row ) {
                                return "<button title='Ver Visita' style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>";
                            },
                            "targets": i_acciones,
						},
						{
                            "render": function ( data, type, row ) {
                                return "<span class='label label-sm " + ((row.resultado=='Venta registrada') ? 'label-success' : 'label-danger') + "'>" + row.resultado + "</span>";
                            },
                            "targets": i_resultado,
                        },
						{
							"targets": [ i_comentarios ],
							"visible": false
						}
					]
				});

				$("#lblNumVisitas").text(datos.length);

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#table_visitas tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						window.open("<?php echo LINKPROYECTO('VerVisita/'); ?>" + row.id, "_blank");
					});
					/*$('#table_visitas tbody').on( 'click', 'button.cancelarrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();								
						window.location.href = "<?php echo LINKPROYECTO('EditarZona/'); ?>" + row.id;
					});					
					$('#table_visitas tbody').on( 'click', 'button.printrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
				  		window.open("<?php echo LINKPROYECTO('ImprimirPedido/'); ?>" + row.id, "_blank");
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
				"title": 'Listado - Visitas',
				"exportOptions": {
						columns: [ i_fecha, i_inicio, i_fin, i_codigocliente, i_cliente, i_resultado, i_ruta, i_sucursal, i_comentarios ]
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
