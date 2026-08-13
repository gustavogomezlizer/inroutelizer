<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu");
$editar=VERIFICARPERFILFUNCION("Catalogos","editarRuta",$this->session->userdata('perfil'));
$nuevo=VERIFICARPERFILFUNCION("Catalogos","nuevaRuta",$this->session->userdata('perfil'));
 ?>

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Rutas</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-12">								
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
							<div class="clearfix">
								<div class="pull-right">
									<?php if($nuevo==1){ ?>
										<button class="btn btn-success btnAgregar">Agregar</button>
									<?php } ?>
									<button class="btn btn-primary btnActualizar">Actualizar</button>
								</div>
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
												<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
											<?php } ?>
										<?php } ?>
									</select> 
								</div>
							</div>

							<div class="clearfix">
								<div class="pull-right tableTools-container"></div>
							</div>
							<div class="table-header">
								Listado de Rutas.
							</div>
										
							<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
								<table id="tabla_rutas" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>Ruta</th>
											<th>Sucursal</th>
											<th>Vendedor</th>
											<th>Zonas</th>
											<th>Clientes</th>											
											<th>Proveedores</th>
											<th>Descripcion</th>
											<th>Activo</th>
											<th>Acciones</th>
										</tr>
									</thead>

									<tbody>
										<!--<?php /*foreach ($lista->result() as $kLC) { ?>
													<tr>
														<td><?php echo $kLC->ruta; ?></td>
														<td><?php echo $kLC->sucursal; ?></td>
														<td><?php echo $this->CatalogosModel->getNameChofer($kLC->vendedor); ?></td>
														<td>
															<?php
																$lasZonas=$this->CatalogosModel->getZonasRuta($kLC->id);
																$cuentaZonas=0;
																$cadenaZonas="";
																foreach ($lasZonas->result() as $kLZ) {
																	if($cuentaZonas==0){
																		$cadenaZonas.=$kLZ->zona;
																		$cuentaZonas=1;
																	}
																	else{
																		$cadenaZonas.=", ".$kLZ->zona;
																	}
																}
																echo $cadenaZonas;
															 ?>

														</td>
														<td>
															<?php 
																$nClientes=$this->CatalogosModel->getNumeroClientes($kLC->id);
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
														<td>
															<?php
																$losProveedores=$this->CatalogosModel->getProveedoresRuta($kLC->id);
																$cuentaProveedores=0;
																$cadenaProveedores="";
																foreach ($losProveedores->result() as $kLP) {
																	if($cuentaProveedores==0){
																		$cadenaProveedores.=$kLP->nombre;
																		$cuentaProveedores=1;
																	}
																	else{
																		$cadenaProveedores.=", ".$kLP->nombre;
																	}
																}
																echo $cadenaProveedores;
															 ?>
														</td>
														<td><?php echo $kLC->descripcion; ?></td>
														<!-- <td>
														<?php 
															if($kLC->esclientemovil==1){
																$ECM="SI";
																?>
																<span class="label label-sm label-success"><?php echo $ECM; ?></span>
															<?php 
															}
															

															
															else{ 
																$ECM="NO";
																?>
																<span class="label label-sm label-danger"><?php echo $ECM; ?></span>
																 <?php 
															}
														?>
														</td>
														<td><?php 
															if($kLC->esprospecto==1){
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
														?></td> -->
														
														<td><div class="hidden-sm hidden-xs action-buttons">
																<a id="VER1<?php echo $kLC->id; ?>" class="blue verRuta1" href="#">
																	<i class="ace-icon fa fa-eye bigger-130"></i>
																</a>
																<?php if($editar==1){ ?>
																<a id="EDIT1<?php echo $kLC->id; ?>" class="green editarRuta1" href="#">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
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

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>
																			<a id="VER2<?php echo $kLC->id; ?>" href="#" class="tooltip-info verCliente1" data-rel="tooltip" title="Ver">
																				<span class="blue">
																					<i class="ace-icon fa fa-eye bigger-120"></i>
																				</span>
																			</a>
																		</li>
																		<?php if($editar==1){ ?>
																		<li>
																			<a id="EDIT2<?php echo $kLC->id; ?>" href="#" class="tooltip-success editarRuta1" data-rel="tooltip" title="Editar">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	<?php } ?>
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
		
<script>
	var CARGAR_BOTONES_TABLA = "0";

	var i_ruta=0, i_sucursal=1, i_vendedor=2, i_zonas=3, i_clientes=4, i_proveedores=5, i_descripcion=6, i_activo=7, i_acciones=8;

	var myTable = $('#tabla_rutas').DataTable({
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},			
	});

	var Editar = "<?php echo $editar; ?>";

	window.onload = function()
	{
		cargarTablaProductos($("#cmbFiltroSucursal").val());
	}

	$("#cmbFiltroSucursal").on("change", function(){
		cargarTablaProductos($("#cmbFiltroSucursal").val());
	});

	$(".btnAgregar").click(function(event) {					
		var link = "<?php echo LINKPROYECTO('NuevoRuta'); ?>";
		window.location.href = link;
	});

	$(".btnActualizar").click(function(event) {						
		location.reload();
	});

	function cargarTablaProductos(pIdsucursal)
	{
		$('#tabla_rutas').addClass('loadingtable');
		$('#tabla_rutas tbody').html("");

		$.post("<?php echo LINKPROYECTO('ListadoRutasJson') ?>", { idsucursal:pIdsucursal }, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#tabla_rutas').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 50,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "ruta" },
						{ "data": "sucursal_nombre" },
						{ "data": "nombre_chofer" },
						{ "data": "zonas" },
						{ "data": "num_clientes" },
						{ "data": "proveedores" },
						{ "data": "descripcion" },						
						{ "data": "status2" },
						{ "data": null },
					],
					"columnDefs": [
						{
							"targets": i_acciones,
							"data" : "id",
							"defaultContent": 									
							"<button style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>"+
							((Editar==1) ? "<button class='editrow btn btn-minier btn-green dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='green'><i class='ace-icon fa fa-pencil-square-o bigger-120'></i></span></button>" : "")
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

					$('#tabla_rutas tbody').on( 'click', 'button.editrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();								
						//window.location.href = "<?php echo LINKPROYECTO('EditarRuta/'); ?>" + row.id;

						window.open("<?php echo LINKPROYECTO('EditarRuta/'); ?>" + row.id, '_blank');
					});
					$('#tabla_rutas tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						//window.location.href = "<?php echo LINKPROYECTO('VerRuta/'); ?>" + row.id;
						window.open("<?php echo LINKPROYECTO('VerRuta/'); ?>" + row.id, '_blank');
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
			$('#tabla_rutas').removeClass('loadingtable');
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