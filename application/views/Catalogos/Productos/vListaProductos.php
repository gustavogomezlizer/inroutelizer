<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); 
$editar=VERIFICARPERFILFUNCION("Catalogos","editarProducto",$this->session->userdata('perfil'));
$nuevo=VERIFICARPERFILFUNCION("Catalogos","nuevoProducto",$this->session->userdata('perfil'));
?>

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">			
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Productos</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-12">
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
								<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
								<div class="clearfix">
									<p>Última Actualización Bees: <?php //echo GETBEESDATOS()->ultima_actualizacion; ?></p>
									<div class="pull-right">

										<?php if($nuevo==1){ ?>
											<button class="btn btn-success btnAgregar">Agregar</button>
										<?php } ?>

										<button class="btn btn-primary btnActualizar">Actualizar</button></div>
								</div>
								<br>

								<div class="clearfix">
									<div class="pull-right tableTools-container"></div>
								</div>							

								<div class="table-header">Listado de Productos.</div>
								
								<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
									<table id="tabla_productos" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Codigo</th>
												<th>CB</th>
												<th>Nombre</th>
												<th>Clasificacion</th>
												<th>Proveedor</th>
												<th>Clave SAT</th>
												<th>Costo</th>
												<th>Precio</th>
												<th>IEPS</th>
												<th>Activo</th>
												<th>Bees</th>
												<th>Acciones</th>
											</tr>
										</thead>
										<tbody>
											<!--<?php 

											/*foreach ($lista->result() as $kLC) {
												?>
											<tr>
												<td><?php echo $kLC->codigo; ?></td>
												<td><?php echo $kLC->nombre; ?></td>
												<td><?php echo FORMATO_DINERO($kLC->precio); ?></td>
												<td>
													<?php echo $kLC->ieps;
														?>

												</td>
												<td>
													<?php 
														
														echo $kLC->clasificacionNombre; ?>

												</td>
												<td><?php echo $kLC->tipo; ?></td>
												<td>
													<?php 
														
														echo $kLC->proveedorNombre; ?>

												</td>
												<td><?php echo $kLC->clavesat; ?></td>

												<td>
													<?php 
													if($kLC->status==1){
														$EStatus="SI";
														?>
														<span class="label label-sm label-success"><?php echo $EStatus; ?></span>
													<?php 
													}
													else{ 
														$EStatus="NO";
														?>
														<span class="label label-sm label-danger"><?php echo $EStatus; ?></span>
															<?php 
													}
												?>
												</td>
												
												
												
												<td><div class="hidden-sm hidden-xs action-buttons">
														<a id="VER1<?php echo $kLC->id; ?>" class="blue verProducto1" href="#">
															<i class="ace-icon fa fa-eye bigger-130"></i>
														</a>
														<?php if($editar==1){ ?>
														<a id="EDIT1<?php echo $kLC->id; ?>" class="green editarProducto1" href="#">
															<i class="ace-icon fa fa-pencil bigger-130"></i>
														</a>
													<?php } ?>
														
													</div>

													<div class="hidden-md hidden-lg">
														<div class="inline pos-rel">
															<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
															</button>

															<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																<li>
																	<a id="VER2<?php echo $kLC->id; ?>" href="#" class="tooltip-info verProducto1" data-rel="tooltip" title="Ver">
																		<span class="blue">
																			<i class="ace-icon fa fa-eye bigger-120"></i>
																		</span>
																	</a>
																</li>
																<?php if($editar==1){ ?>
																<li>
																	<a id="EDIT2<?php echo $kLC->id; ?>" href="#" class="tooltip-success editarProducto1" data-rel="tooltip" title="Editar">
																		<span class="green">
																			<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																		</span>
																	</a>
																</li>
																<?php } ?>
																
															</ul>
														</div>
													</div></td>
											</tr>
												<?php 

											
											}*/
												?>
												
										</tbody>-->
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

<!--</div>--><!-- /.main-container -->

<!-- basic scripts -->
<?php $this->load->view("vFooter"); ?>
				
<script type="text/javascript">

	var CARGAR_BOTONES_TABLA = "0";

	var i_codigo=0, i_codigobarras=1, i_nombre=2, i_clasificacion=3, i_proveedor=4, i_clavesat=5, i_costo=6, i_precio=7, i_ieps=8, i_activo=9, i_bees=10, i_acciones=11;

	var perfil = "<?php echo GETPERFILUSUARIO();?>";

	var columnas_hidden = (perfil == "ADMINISTRADOR") ? [] : [i_costo];

	var myTable = $('#tabla_productos').DataTable({
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},
			/*"pageLength": -1,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
			"order": [[0,"asc"]]*/
	});

	var Editar = "<?php echo $editar; ?>";

	window.onload = function()
	{
		cargarTablaProductos();
	}

	$(".verProducto1").click(function(event) {					
		var id=$(this).attr("id").replace("VER1","");
		id=id.replace("VER2","");					
		var link="<?php echo CCATALOGOS(); ?>" + "verProducto/"+id;				  	
		window.location.href=link;					
	});

	$(".editarProducto1").click(function(event) {					
		var id=$(this).attr("id").replace("EDIT1","");
		id=id.replace("EDIT2","");					
		var link="<?php echo CCATALOGOS(); ?>" + "editarProducto/"+id;					
		window.location.href=link;					
	});

	$(".btnAgregar").click(function(event) {					
		var link = "<?php echo LINKPROYECTO('NuevoProducto'); ?>";
		window.location.href = link;
	});

	$(".btnActualizar").click(function(event) {						
		location.reload();
	});

	function cargarTablaProductos()
	{
		$('#tabla_productos').addClass('loadingtable');
		$('#tabla_productos tbody').html("");

		$.post("<?php echo LINKPROYECTO('ListadoProductosJson/PRODUCTO') ?>", function(data)
		{
			var datos = JSON.parse(data);						
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#tabla_productos').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 50,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "codigo" },
						{ "data": "codigobarras" },
						{ "data": "nombre" },
						{ "data": "nombre_clasificacion" },
						{ "data": "nombre_proveedor" },
						{ "data": "clavesat" },
						{ "data": "costo_format" },
						{ "data": "precio_format" },
						{ "data": "ieps_format" },
						{ "data": "status2" },
						{ "data": null },
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
						{
							"render": function ( data, type, row ) {
									return (row.subidobees==1) ? "<span class='badge badge-success'><i class='ace-icon fa fa-cloud-upload bigger-120'></i></span>" : "<span class='badge badge-danger'><i class='ace-icon fa fa-cloud-download bigger-120'></i></span>";
							},
							"targets": i_bees,
						},
						{ className: "text-right", "targets": [i_precio, i_ieps] },
						{
							"targets": columnas_hidden,
							"visible": false
						}
					]
				});

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#tabla_productos tbody').on( 'click', 'button.editrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();								
						window.location.href = "<?php echo LINKPROYECTO('EditarProducto/'); ?>" + row.id;
					});
					$('#tabla_productos tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						window.location.href = "<?php echo LINKPROYECTO('VerProducto/'); ?>" + row.id;
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
			$('#tabla_productos').removeClass('loadingtable');
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
				"title": 'Listado - Productos',
				"exportOptions": {
						columns: [ 0, 1, 2, 3, 4, 5, 7, i_ieps, i_activo ]
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
						
	/*var defaultCopyAction = myTable.button(1).action();
	myTable.button(1).action(function (e, dt, button, config) {
		defaultCopyAction(e, dt, button, config);
		$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
	});*/
		
		/*myTable.on( 'select', function ( e, dt, type, index ) {
			if ( type === 'row' ) {
				$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
			}
		} );
		myTable.on( 'deselect', function ( e, dt, type, index ) {
			if ( type === 'row' ) {
				$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
			}
		} );*/
	
		//$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
						
		/*$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
			var th_checked = this.checked;//checkbox inside "TH" table header
			
			$('#dynamic-table').find('tbody > tr').each(function(){
				var row = this;
				if(th_checked) myTable.row(row).select();
				else  myTable.row(row).deselect();
			});
		});*/
					
		/*$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
			var row = $(this).closest('tr').get(0);
			if(this.checked) myTable.row(row).deselect();
			else myTable.row(row).select();
		});*/
	
		/*$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
			e.stopImmediatePropagation();
			e.stopPropagation();
			e.preventDefault();
		}); */

</script>
			

	</body>
</html>
