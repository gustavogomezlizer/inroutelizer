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
						<small><i class="ace-icon fa fa-angle-double-right"></i>Mi Empresa/Perfiles</small>
					</h1>
				</div><!-- /.page-header -->

				<div class="row">
					<div class="col-md-12">						

						<div class="col-md-12">

							<div class="clearfix">
								<div class="pull-right">
									<button class="btn btn-primary btnActualizar">Actualizar</button>
								</div>
							</div><br/>

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
													<?php foreach ($listaPerfiles->result() as $kPer) { ?>
													<tr>
														<td><?php echo $kPer->perfil; ?></td>
														<td><?php echo $kPer->descripcion; ?></td>
														<td>
															<?php if($kPer->status==1){ $EP="SI"; ?>
																<span class="label label-sm label-success"><?php echo $EP; ?></span>
															<?php } else { $EP="NO"; ?>
																<span class="label label-sm label-danger"><?php echo $EP; ?></span>
															<?php } ?>
														</td>

														<td>
															<div class="hidden-sm hidden-xs action-buttons">
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
																	</ul>
																</div>
															</div>
														</td>
													</tr>
												<?php } ?>														
											</tbody>
										</table>
									</div><!-- empieza div que contiene a la tabla -->
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

		<!-- basic scripts -->
	<?php $this->load->view("vFooter"); ?>
		
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
				
			var myTable = 
				$('#dynamic-table')
				.DataTable( {
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
					},
					"pageLength": 10,
					"order": [[0,"asc"]]
			    });

				$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
				
				new $.fn.dataTable.Buttons( myTable, {
					buttons: [

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
				
				//style the message box
				var defaultCopyAction = myTable.button(1).action();
				myTable.button(1).action(function (e, dt, button, config) {
					defaultCopyAction(e, dt, button, config);
					$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
				});
				
				myTable.on( 'select', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
					}
				} );
				myTable.on( 'deselect', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
					}
				});

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

		</script>
	</body>
</html>