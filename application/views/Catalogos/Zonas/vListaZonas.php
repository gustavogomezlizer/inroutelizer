<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); 
$editar=VERIFICARPERFILFUNCION("Catalogos","editarZona",$this->session->userdata('perfil'));
$nuevo=VERIFICARPERFILFUNCION("Catalogos","nuevaZona",$this->session->userdata('perfil'));
?>

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Zonas</small>
				</h1>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-12">										
							<div class="clearfix">

								<?php if($nuevo == 1) { ?>
									<div class="pull-right"><button class="btn btn-success btnAgregar">Agregar</button>
								<?php } ?>

								<button class="btn btn-primary btnActualizar">Actualizar</button></div>
							</div>
							<br>

							<div class="row">
								<div class="col-sm-2">
									<select id="cmbFiltroSucursal" name="sucursal" class="form-control">
										<?php if(ISMULTISUCURSAL()) { ?>
											<option value=0 selected>TODAS</option>
											<?php foreach (GETLISTASUCURSALES() as $item) { ?>
												<option value="<?php echo $item->id; ?>"><?php echo $item->sucursal; ?></option>
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
							</div>

							<div class="clearfix">
								<div class="pull-right tableTools-container"></div>
							</div>
							<div class="table-header">
								Listado de Zonas.
							</div>

							<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
								<table id="tabla_zonas" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>Zona</th>
											<th>Sucursal</th>
											<th>Rutas</th>
											<th>Ciudad</th>
											<th>Clientes</th>
											<th>Observacion</th>
											<th>Activo</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
													<!--<?php 

													/*foreach ($lista->result() as $kLC) {
														?>
													<tr>
														<td><?php echo $kLC->zona; ?></td>
														<td><?php echo $kLC->sucursal; ?></td>
														
														<td>
															<?php
																$lasZonas=$this->CatalogosModel->getRutasZona($kLC->id);
																$cuentaZonas=0;
																$cadenaZonas="";
																foreach ($lasZonas->result() as $kLZ) {
																	if($cuentaZonas==0){
																		$cadenaZonas.=$kLZ->ruta;
																		$cuentaZonas=1;
																	}
																	else{
																		$cadenaZonas.=", ".$kLZ->ruta;
																	}
																}
																echo $cadenaZonas;
															 ?>

														</td>
														<td><?php echo $kLC->ciudad; ?></td>
														<td>
															<?php 
																$nClientes=$this->CatalogosModel->getNumeroClientesZona($kLC->id);
																echo $nClientes;
															 ?>

														</td>
														<td>
															<?php 
															if($kLC->status==1){
																$EP="SI";
																?>
																<span class="label label-sm label-success"><?php echo $EP; ?></span>
															<?php 
															}
																														
															else{ 
																$EP="NO";
																?>
																<span class="label label-sm label-danger"><?php echo $EP; ?></span>
																 <?php 
															}
														?>
														</td>
														
														<td><?php echo $kLC->observacion; ?></td>
														

																
															</div>
														<td><div class="hidden-sm hidden-xs action-buttons">
																<a id="VER1<?php echo $kLC->id; ?>" class="blue verZona1" href="#">
																	<i class="ace-icon fa fa-eye bigger-130"></i>
																</a>

																<a id="EDIT1<?php echo $kLC->id; ?>" class="green editarZona1" href="#">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>
																<!-- <a id="MAP1<?php echo $kLC->id; ?>" class="red verMapa1" href="#">
																	<i class="ace-icon fa fa-map-marker bigger-130"></i>
																</a> -->

																
															</div>

															<div class="hidden-md hidden-lg">
																<div class="inline pos-rel">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>
																			<a id="VER2<?php echo $kLC->id; ?>" href="#" class="tooltip-info verZona1" data-rel="tooltip" title="Ver">
																				<span class="blue">
																					<i class="ace-icon fa fa-eye bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a id="EDIT2<?php echo $kLC->id; ?>" href="#" class="tooltip-success editarZona1" data-rel="tooltip" title="Editar">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
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
		
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>-->
<script>
	var CARGAR_BOTONES_TABLA = "0";

	var i_zona=0, i_sucursal=1, i_rutas=2, i_ciudad=3, i_clientes=4, i_observacion=5, i_activo=6, i_acciones=7;

	var myTable = $('#tabla_zonas').DataTable({
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},			
	});

	var Editar = "<?php echo $editar; ?>";

	window.onload = function()
	{
		cargarTablaProductos($("#cmbFiltroSucursal").val());
	}

	$(".btnAgregar").click(function(event) {					
		var link = "<?php echo LINKPROYECTO('NuevoZona'); ?>";
		window.location.href = link;
	});

	$(".btnActualizar").click(function(event) {						
		location.reload();
	});

	$("#cmbFiltroSucursal").on("change", function(){
		cargarTablaProductos($("#cmbFiltroSucursal").val());
	});

	function cargarTablaProductos(pIdsucursal)
	{
		$('#tabla_zonas').addClass('loadingtable');
		$('#tabla_zonas tbody').html("");

		$.post("<?php echo LINKPROYECTO('ListadoZonasJson') ?>", { idsucursal:pIdsucursal }, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#tabla_zonas').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 50,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [						
						{ "data": "zona" },
						{ "data": "sucursal_nombre" },
						{ "data": "rutas" },
						{ "data": "ciudad" },
						{ "data": "num_clientes" },
						{ "data": "observacion" },
						{ "data": "status2" },
						{ "data": null },
					],
					"columnDefs": [
						{
							"targets": i_acciones,
							"data" : "id",
							"defaultContent": 									
							"<button style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>"+
							((Editar == 1) ? "<button class='editrow btn btn-minier btn-green dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='green'><i class='ace-icon fa fa-pencil-square-o bigger-120'></i></span></button>" : "")
						},
						{
                            "render": function ( data, type, row ) {
                                return (row.status==1) ? "<span class='label label-sm label-success'>SI</span>" : "<span class='label label-sm label-danger'>NO</span>";
                            },
                            "targets": i_activo,
                        },
						{ className: "text-right", "targets": [i_clientes] },						
					]
				});

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#tabla_zonas tbody').on( 'click', 'button.editrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();								
						window.location.href = "<?php echo LINKPROYECTO('EditarZona/'); ?>" + row.id;
					});
					$('#tabla_zonas tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						window.location.href = "<?php echo LINKPROYECTO('VerZona/'); ?>" + row.id;
					});

					cargarBotonesTabla();
				}
			}
			else
			{
				myTable.clear().draw();
				//alert("Ocurrio un error al eliminar el empleado");
			}
		}).always(function() {
			$('#tabla_zonas').removeClass('loadingtable');
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
				"title": 'Listado - Proveedores',
				"exportOptions": {
						columns: [ 0, 1, 2, 3, 4 ]
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