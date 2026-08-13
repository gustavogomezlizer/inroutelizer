<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu");
 ?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								LIZER Sistema de Distribucion
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Configuraciones
									
									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="clearfix">
											<div class="pull-right"><button class="btn btn-primary btnActualizar">Actualizar</button></div>
										</div>
										<br>
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									<div class="col-xs-12 col-md-10 col-md-offset-1">	<!--  empieza div.col-xs-12 de la tabla clientes -->
										<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
										
										
										<div class="widget-box widget-color-blue collapsed">
											<div class="widget-header">
													<h4 class="widget-title">Configuraciones</h4>

													<div class="widget-toolbar">
														<a href="#" data-action="collapse">
															<i class="ace-icon fa fa-chevron-up"></i>
														</a>
										
														<!-- <a href="#" data-action="close">
															<i class="ace-icon fa fa-times"></i>
														</a> -->
													</div>
												</div>
												<div class="widget-body">
													<div class="widget-main">
														<form action="<?php echo CCONFIGURAR('saveConfigurar'); ?>" method="POST" enctype="multipart/form-data">
														<div class="row">
														<div class="row">
														<div class="col-md-10 col-md-offset-1">
															<label for="">NOMBRE COMERCIAL</label>
															</div>
														</div>

														
														

														<div class="row">
															<div class="col-md-10 col-md-offset-1">
															<input type="text" class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $datosConf->row()->nombre; ?>">
															<!-- <input type="hidden" name="txtId" id="txtId" value="<?php echo $datosConf->row()->id; ?>"> -->
															</div>
														</div>
														<div class="row">
														<div class="col-md-10 col-md-offset-1">
															<label for="">RAZON SOCIAL</label>
															</div>
														</div>

														<div class="row">
															<div class="col-md-10 col-md-offset-1">
															<input type="text" class="form-control" name="txtNombreCorto" id="txtNombreCorto" value="<?php echo $datosConf->row()->nombrecorto; ?>">
															</div>
														</div>
														<div class="row">
														<div class="col-md-10 col-md-offset-1">
															<label for="">DOMICILIO</label>
															</div>
														</div>

														<div class="row">
															<div class="col-md-10 col-md-offset-1">
															<input type="text" class="form-control" name="txtDomicilio" id="txtDomicilio" value="<?php echo $datosConf->row()->domicilio; ?>">

															</div>
														</div>
														
														<div class="row">
														<div class="col-md-10 col-md-offset-1">
															<label for="">TELEFONO</label>
															</div>
														</div>

														<div class="row">
															<div class="col-md-10 col-md-offset-1">
															<input type="text" class="form-control" name="txtTelefono" id="txtTelefono" value="<?php echo $datosConf->row()->telefono; ?>">
															</div>
														</div>
														<div class="row">
														<div class="col-md-10 col-md-offset-1">
															<label for="">CORREO</label>
															</div>
														</div>

														<div class="row">
															<div class="col-md-10 col-md-offset-1">
															<input type="text" class="form-control" name="txtCorreo" id="txtCorreo" value="<?php echo $datosConf->row()->telefono; ?>">
															</div>
														</div>
														<div class="row">
														<div class="col-md-10 col-md-offset-1">
															<label for="">LOGOTIPO</label>
															</div>
														</div>

														<div class="row">
															<div class="col-md-10 col-md-offset-1" align="center">
															<img src="<?php echo RUTAFOLDERASSETS("images/logos/Logotipo.jpg"); ?>" alt="" width="300" height="100">
															</div>
														</div>
														<div class="row">
															<input multiple="" type="file" id="id-input-file-3" name="logo" />
														</div>
														<div class="row" align="center"><button class="btn btn-primary">Guardar</button></div>
													</div>
													</form>
												</div>

										</div>
										<!-- <div class="table-header">
											Listado de Usuarios.
										</div> -->

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
									<div class="widget-box widget-color-blue">
									<div class="widget-header">
													<h4 class="widget-title">Perfiles</h4>

													<div class="widget-toolbar">
														<a href="#" class="btnAgregar">
															<i class="ace-icon fa fa-plus white"></i>
														</a>
														<a href="#" data-action="collapse">
															<i class="ace-icon fa fa-chevron-up"></i>
														</a>
														
										
														<!-- <a href="#" data-action="close">
															<i class="ace-icon fa fa-times"></i>
														</a> -->
													</div>
												</div>
												<div class="widget-body">
													<div class="widget-main">
													<div class="clearfix">
														<div class="pull-right tableTools-container"></div>
													</div>
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Perfil</th>
														<th>Descripcion</th>
														<th>Status</th>
														
														<th>Acciones</th>
														
													</tr>
												</thead>
												<tbody>
													
													<?php 
														foreach ($listaPerfiles->result() as $kPer) {
															# code...
														
													 ?>
													<tr>
														<td><?php echo $kPer->perfil; ?></td>
														<td><?php echo $kPer->descripcion; ?></td>
														<td>
															<?php 
															if($kPer->status==1){
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
														
														
														
														<td><div class="hidden-sm hidden-xs action-buttons">
																<input type="hidden" id="perfil<?php echo $kPer->id; ?>" value="<?php echo $kPer->perfil; ?>">
																<a id="VER1<?php echo $kPer->id; ?>" class="blue verPerfil1" href="#">
																	<i class="ace-icon fa fa-eye bigger-130"></i>
																</a>

																<a id="EDIT1<?php echo $kPer->id; ?>" class="green editarPerfil1" href="#">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>
																<a id="DEL1<?php echo $kPer->id; ?>" class="red borrarPerfil1" href="#">
																	<i class="ace-icon fa fa-trash bigger-130"></i>
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
																			<a id="VER2" href="#" class="tooltip-info verPerfil1" data-rel="tooltip" title="Ver">
																				<span class="blue">
																					<i class="ace-icon fa fa-eye bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a id="EDIT2" href="#" class="tooltip-success editarPerfil1" data-rel="tooltip" title="Editar">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>
																		<li>
																			<a id="DEL2" href="#" class="tooltip-success borrarPerfil1" data-rel="tooltip" title="Eliminar">
																				<span class="red">
																					<i class="ace-icon fa fa-trash bigger-120"></i>
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
														<?php } ?>
														
												</tbody>
											</table>
										</div><!-- empieza div que contiene a la tabla -->
										</div><!-- termina div  -->
										</div></div>
									</div><!--  termina div.col-xs-12 de la tabla clientes-->
								</div><!--  termina div.row de la tabla clientes-->
								

								

							

								<!-- PAGE CONTENT ENDS -->
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

		<!-- basic scripts -->
	<?php $this->load->view("vFooter"); ?>
		
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				$('#id-input-file-3').ace_file_input({
					style: 'well',
					btn_choose: 'Arrastra un archivo hasta aqui o haz click aqui',
					btn_change: null,
					no_icon: 'ace-icon fa fa-cloud-upload',
					droppable: true,
					thumbnail: 'large'//large | fit
					//,icon_remove:null//set null, to hide remove/reset button
					/**,before_change:function(files, dropped) {
						//Check an example below
						//or examples/file-upload.html
						return true;
					}*/
					/**,before_remove : function() {
						return true;
					}*/
					,
					allowExt: ["jpeg", "jpg", "png", "gif" , "bmp"],
					allowMime: ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"],
					preview_error : function(filename, error_code) {
						//name of the file that failed
						//error_code values
						//1 = 'FILE_LOAD_FAILED',
						//2 = 'IMAGE_LOAD_FAILED',
						//3 = 'THUMBNAIL_FAILED'
						//alert(error_code);
					}
			
				}).on('change', function(){
					//console.log($(this).data('ace_input_files'));
					//console.log($(this).data('ace_input_method'));
				});
				
			var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					"language": {
				            "url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
				        },
				              "pageLength": 10,
				              "order": [[0,"asc"]]
			    } );
			
				
				
				$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
				
				new $.fn.dataTable.Buttons( myTable, {
					buttons: [
					 /* {
						"extend": "colvis",
						"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
						"className": "btn btn-white btn-primary btn-bold",
						columns: ':not(:first):not(:last)'
					  },*/
					  /*{
						"extend": "copy",
						"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },*/
					  /*{
						"extend": "csv",
						"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },*/

					  {
						"extend": "excel",
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
						"className": "btn btn-white btn-primary btn-bold",
						"titleAttr": "CONCENTRADO",
			            "title": 'Monitoreo de Mensajeros - Concentrado',
			            "exportOptions": {
			                    columns: [ 0, 1, 2, 3, 4 ]
			                }
					  },
					 /* {
						"extend": "pdf",
						"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },*/
					  {
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					  }		  
					]
				} );
				myTable.buttons().container().appendTo( $('.tableTools-container') );
				
				//style the message box
				var defaultCopyAction = myTable.button(1).action();
				myTable.button(1).action(function (e, dt, button, config) {
					defaultCopyAction(e, dt, button, config);
					$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
				});
				
				
				/*var defaultColvisAction = myTable.button(0).action();
				myTable.button(0).action(function (e, dt, button, config) {
					
					defaultColvisAction(e, dt, button, config);
					
					
					if($('.dt-button-collection > .dropdown-menu').length == 0) {
						$('.dt-button-collection')
						.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
						.find('a').attr('href', '#').wrap("<li />")
					}
					$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
				});
			
				////
			
				setTimeout(function() {
					$($('.tableTools-container')).find('a.dt-button').each(function() {
						var div = $(this).find(' > div').first();
						if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
						else $(this).tooltip({container: 'body', title: $(this).text()});
					});
				}, 500);*/
				
				
				
				
				
				myTable.on( 'select', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
					}
				} );
				myTable.on( 'deselect', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
					}
				} );
			
			
			
			
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$('#dynamic-table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) myTable.row(row).select();
						else  myTable.row(row).deselect();
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(this.checked) myTable.row(row).deselect();
					else myTable.row(row).select();
				});
			
			
			
				$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();
					e.stopPropagation();
					e.preventDefault();
				}); 
				/*termina la configuracion de #dinamyc-table*/
			
				/*empieza configuracion para ver mapas*/
				
					$(".verPerfil1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("VER1","");
					id=id.replace("VER2","");
					//$("#modalMapa").modal("show");
					var link="<?php echo CCONFIGURAR(); ?>" + "verPerfil/"+id;
				  	window.open(link,"_blank");
					//alert(id);
				});
					$(".editarPerfil1").click(function(event) {
						/* Act on the event */
						var id=$(this).attr("id").replace("EDIT1","");
						id=id.replace("EDIT2","");
						//$("#modalMapa").modal("show");
						var link="<?php echo CCONFIGURAR(); ?>" + "editarPerfil/"+id;
					  	window.open(link,"_blank");
						//alert(id);
				});
					$(".borrarPerfil1").click(function(event) {
						/* Act on the event */
						var id=$(this).attr("id").replace("DEL1","");
						var perfil=$("#perfil"+id).val();
						alert(id+" - "+perfil);
						id=id.replace("DEL2","");
						//$("#modalMapa").modal("show");
						var link="<?php echo CCONFIGURAR(); ?>" + "borrarPerfil/"+id+"/"+perfil;
					  	 window.location.href=link;
					  	//window.open(link,"_blank");
						//alert(id);
				});
					$(".btnAgregar").click(function(event) {
						/* Act on the event */
						var link="<?php echo CCONFIGURAR(); ?>" + "nuevoPerfil/";
				  		window.open(link,"_blank");
					});
					$(".btnActualizar").click(function(event) {
							/* Act on the event */
							location.reload();
						});
				
			})

		</script>
			

	</body>
</html>
