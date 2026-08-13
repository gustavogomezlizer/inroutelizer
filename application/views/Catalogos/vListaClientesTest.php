<?php 
$data['title']="LIZER Listado";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu");
$editar=VERIFICARPERFILFUNCION("Catalogos","editarClientes",$this->session->userdata('perfilLIZER'));
$nuevo=VERIFICARPERFILFUNCION("Catalogos","nuevoCliente",$this->session->userdata('perfilLIZER'));
 ?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Catalogos / Clientes
									
									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
										<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
										<div class="clearfix">
											<div class="pull-right">
											<?php 
												if($nuevo==1){
											 ?>
											<button class="btn btn-success btnAgregar">Agregar</button>
											<?php 
											}
											 ?>
											<button class="btn btn-primary btnActualizar">Actualizar</button></div>
										</div>
										<br>
										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											Listado de Clientes.
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Codigo</th>
														<th>Cliente</th>
														<th>Domicilio</th>
														<th>Zona</th>
														<th>Proveedores</th>
														<!-- <th>Proveedores</th> -->
														<th>Sucursal</th>
														<th>Activo</th>
														<th>Acciones</th>

														<!-- 
														<th>Zona</th>
														<th>Proveedores</th>
														<th>Sucursal</th>
														<th>Activo</th>
														<th>Acciones</th> -->
														
													</tr>
												</thead>
												
											</table>
										</div><!-- empieza div que contiene a la tabla -->
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
			
			var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax":{
						url :"http://lizer.com.mx/lizerFB/index.php/DataTables/dataClientes", // json datasource
						type: "post"  // method  , by default get
						
					},
					"language": {
				            "url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
				        },
				              "pageLength": <?php echo $cantidad; ?>,
				              "lengthMenu": [[10, 25, 50, <?php echo $cantidad; ?>], [10, 25, 50, <?php echo $cantidad; ?>]],
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
				$(".verMapa1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("MAP1","");
					id=id.replace("MAP2","");
					//$("#modalMapa").modal("show");
					var link="<?php echo CCATALOGOS(); ?>" + "verCliente/"+id;
					window.location.href=link;
				  	//window.open(link,"_blank");
					//alert(id);
				});
					$(".verCliente1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("VER1","");
					id=id.replace("VER2","");
					//$("#modalMapa").modal("show");
					var link="<?php echo CCATALOGOS(); ?>" + "verCliente/"+id;
				  	window.location.href=link;
				  	//window.open(link,"_blank");
					//alert(id);
				});
					$(".editarCliente1").click(function(event) {
						/* Act on the event */
						var id=$(this).attr("id").replace("EDIT1","");
						id=id.replace("EDIT2","");
						//$("#modalMapa").modal("show");
						var link="<?php echo CCATALOGOS(); ?>" + "editarCliente/"+id;
					  	window.location.href=link;
					  	//window.open(link,"_blank");
						//alert(id);
				});
					$(".btnAgregar").click(function(event) {
						/* Act on the event */
						var link="<?php echo CCATALOGOS(); ?>" + "nuevoCliente/";
				  		window.location.href=link;
				  		//window.open(link,"_blank");
					});
					$(".btnActualizar").click(function(event) {
							/* Act on the event */
							location.reload();
						});
			})

		</script>
			

	</body>
</html>
