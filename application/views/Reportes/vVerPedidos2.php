<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); 

?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
 
 <link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leaflet/leaflet.css"); ?>" />
 <!-- <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leafmarkers/leaflet.awesome-markers.css"); ?>" />
 <link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leaflet/leaflet.css"); ?>" />
<!--   <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" /> -->
 <!-- <link rel="stylesheet" href="http://lizer.com.mx/leaf/leaflet.css" /> -->
<script src="<?php echo RUTAFOLDERASSETS("leaflet/leaflet.js"); ?>"></script>
<script src="<?php echo RUTAFOLDERASSETS("leafmarkers/leaflet.awesome-markers.min.js"); ?>"></script>
<script src="<?php echo RUTAFOLDERASSETS("leafletzoom/L.Control.ZoomBar.js"); ?>"></script>
 <style>
 
   </style>
			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Reportes / Ver Pedidos
									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
									<div class="col-md-12 col-xs-12 col-sm-12" align="right">
										<!-- <button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button> -->
										<button class="btn btn-danger" onclick="window.close();">CERRAR</button>
										<br/>
									</div>
									
									<div class="row col-sm-12"><br/></div>
									
									
										
										
									
									
										<div class="row">
									<div class="col-sm-12">
										
															<div class="row" align="center">
																<div class="col-xs-12">
																	<h4 class="control-label green">VER DATOS</h4>
																</div>
															</div>
															<div class="space-40"><br></div>
											
													<div class="row">
														<div class="clearfix">
															<div class="pull-right tableTools-container"></div>
														</div>
															
															
															
																
																	<div class="col-sm-12 table-responsive">
																			<table id="dynamic-table" class="table table-striped table-bordered table-hover">
																				<thead>
																					<tr>
																						<th>ID Pedido</th>
																						<th>ID Item</th>
																						<th>ID Vendedor</th>
																						<th>Ruta</th>
																						<th>Codigo Cliente</th>
																						<th>Cliente</th>
																						<th>Codigo de Producto</th>
																						<th>Producto</th>
																						<th>Cantidad</th>
																						<th>Precio</th>
																						<th>Total Producto</th>
																						<th>Fecha</th>
																						<th>Tipo</th>
																						
																					</tr>
																				</thead>
																				<tbody>
																					<?php 
																					foreach ($lista->result() as $kDP) {
																						?>
																						<tr>
																							<td><?php echo $kDP->folio; ?></td>
																							<td><?php echo $kDP->codigoproducto; ?></td>
																							<td><?php echo $kDP->idusuario; ?></td>
																							<td><?php echo $kDP->ruta; ?></td>
																							<td><?php echo $kDP->codigoCliente; ?></td>
																							<td><?php echo $kDP->nombreCliente; ?></td>
																							<td><?php echo $kDP->codigoproducto; ?></td>
																							<td><?php echo $kDP->producto; ?></td>
																							<td><?php echo $kDP->cantidad; ?></td>
																							<td><?php echo FORMATO_DINERO($kDP->precio); ?></td>
																							<td><?php echo FORMATO_DINERO($kDP->importe); ?></td>
																							<td><?php echo FORMATO_FECHA($kDP->fecha); ?></td>
																							<td><?php echo $kDP->tipo; ?></td>
																							
																						</tr>
																						<?php 
																					}
																					 ?>
																				</tbody>
																				
																			</table>
																		</div>
															
															

															
														
													</div>
													
												
									</div><!-- /.col -->
									
								</div><!-- /.row -->
								
								
										
											
										</div><!-- empieza div que contiene a la tabla -->
									</div><!--  termina div.col-xs-12 de la tabla clientes-->

									<div class="space-40"><br></div>
									<div class="col-md-12 col-xs-12 col-sm-12" align="center"><br>
										<!-- <button id="btnGuardar" class="btn btn-success btnGuardar">GUARDAR</button> -->
										<button class="btn btn-danger" onclick="window.close();">CERRAR</button>
										<!-- <button id="btnOcultar" class="btn btn-warning">OCULTAR</button> -->
									</div>
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
			</body>
</html>

		<!-- inline scripts related to this page -->
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>
		<script type="text/javascript">
				var ver=true;
				
			jQuery(function($) {				
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
						"text": "<i class='fa fa-file-excel-o bigger-110 green'>Exportar a Excel</i> <span class='hidden'>Exportar a Excel Concentrado</span>",
						"className": "btn btn-white btn-primary btn-bold btnExcel",
						"titleAttr": "EXCEL CONCENTRADO",
			            "title": 'Reporte de Pedidos - Concentrado Detalles'
					  }
					 /* {
						"extend": "pdf",
						"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },*/
					  /*{
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					  }	*/	  
					]
				} );
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
				
			})

			function close_window() {
			    if (confirm("¿Seguro que quieres salir?")) {
			        window.close();
			    }
			}
			$("#btnOcultar").click(function(event) {
				/* Act on the event */
				$("#myModal").modal("show");
			});
		</script>
			<script type="text/javascript">
			
	 $(".btnGuardar").click(function(event) {
	 	/* Act on the event */
	 	$("#frmDatos").submit();
	 });
	 $("#verelmapa").click(function(event) {
	 	/* Act on the event */
	 	$("#mapid").css("display", "block");
	 });
	 $("#verdatos").click(function(event) {
	 	/* Act on the event */
	 	$("#mapid").css("display", "block");
	 });
	


   

    

</script>





		

