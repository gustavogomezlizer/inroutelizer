<?php 
$data['title']="LIZER Reportes-Visitas";

$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu");
	$cuantasvisitas=0;
	$usuario=str_replace(".COMACONTROL.", ",", $usuario);
	$ruta=str_replace(".COMACONTROL.", ",", $ruta);
	$sucursal=str_replace(".COMACONTROL.", ",", $sucursal);
	$usuario=str_replace("%20"," ",$usuario);
	$usuario=str_replace("%C3%B1","ñ",$usuario);
 ?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Reportes / Visitas
									
									
								</small>
							</h1>
							<?php //echo $sucursal; ?>
						</div><!-- /.page-header -->
						<!-- <div class="row">
							<div class="col-xs-12">
								<h5><strong>Filtros</strong></h5>
							</div>
						</div> -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									<div class="col-xs-2"><label for="">Inicio</label><input id="txtFInicio" type="date" class="form-control" value="<?php echo $fIni; ?>"></div>
									<div class="col-xs-2"><label for="">Final</label><input id="txtFFinal" type="date" class="form-control" value="<?php echo $fFin; ?>"></div>
									<!-- <div class="col-xs-2"><label for="">Tipo</label>
										<select name="cmbTipo" id="cmbTipo" class="form-control">
											<?php 
											$tipoSel0="";
											$tipoSel1="";
											$tipoSel2="";
											if($tipo=="TODOS"){
													$tipoSel0="selected";
												}
												elseif($tipo=="PREVENTA"){
													$tipoSel1="selected";
												}
												else{
													$tipoSel2="selected";
												}
												 ?>
												
												
											<option value="TODOS" <?php echo $tipoSel0 ?>>Todos</option>
											<option value="PREVENTA" <?php echo $tipoSel1 ?>>Preventa</option>
											<option value="DEVOLUCION" <?php echo $tipoSel2 ?>>Devolucion</option>
										</select>
									</div> -->
									<div class="col-xs-2"><label for="">Rutas</label><br>
										<select name="cmbRuta" id="cmbRuta" class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Ruta)">
											<?php 
												if($usuario=="TODOS"){
													?>
													<option value="TODOS" selected>Todos</option>
													<?php 
												}
											
												else{
													?>
														<option value="TODOS">Todos</option>
											<?php 
												}
												foreach ($listaRutas->result() as $kU) {
													# code...
													?>
													<option value="<?php echo $kU->ruta; ?>"><?php echo $kU->ruta; ?></option>
													<?php 
												}
											 ?>
										</select>
									</div>
									<div class="col-xs-2"><label for="">Usuario</label><br>
										<select name="cmbUsuario" id="cmbUsuario" class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Usuario)">
											<?php 
												if($usuario=="TODOS"){
													?>
													<option value="TODOS" selected>Todos</option>
													<?php 
												}
											
												else{
													?>
														<option value="TODOS">Todos</option>
											<?php 
												}
												foreach ($listaUsuarios->result() as $kU) {
													# code...
													
													?>
													<option value="<?php echo $kU->nombre; ?>"><?php echo $kU->nombre; ?></option>
													<?php 
												}
											 ?>
										</select>
									</div>
									<div class="col-xs-2"><label for="">Sucursal</label><br>
										<select name="cmbSucursal" id="cmbSucursal"  class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Sucursal)">
											<?php if(VERIFICAMULTISUCURSAL()==1){
												if($sucursal=="TODOS"){
													
													?>
													<option value="TODOS" selected>Todas</option>
													<?php 

												}
											
												else{
													?>
														<option value="TODOS">Todas</option>
											<?php 
												}
											}
												foreach ($listaSucursales->result() as $kS) {
													# code...
													if($kS->sucursal==$sucursal){
														?>
														<option value="<?php echo $kS->sucursal; ?>" selected><?php echo $kS->sucursal; ?></option>
														<?php 
													}
													else{
														?>
														<option value="<?php echo $kS->sucursal; ?>"><?php echo $kS->sucursal; ?></option>
														<?php 
													}
													
													
												}
											 ?>
										</select>
									</div>
									
									</div>
									<div class="row"><div class="col-xs-12"><hr></div></div>
									<div class="row">

									<div class="col-xs-12"><div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
										<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
										<div class="clearfix">
											<!-- <div class="pull-right"><button class="btn btn-primary btnActualizar">Actualizar</button></div> -->
										</div>
										
										<div class="clearfix col-md-6" align="left">
										<div class="col-md-4">
										<h4><strong>No. Visitas: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumVisitas">0</label></span>
										
										</div>
										
									</div>
										<div class="clearfix col-md-6" align="right">
											<div class="pull-right"><button id="btnAplicar" class="btn btn-primary">Aplicar</button><button class="btn btn-success btnActualizar">Actualizar</button></div>
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
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
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
														<th>Acciones</th>
														
													</tr>
												</thead>
												<tbody>
													
													
													<?php 

													foreach ($lista->result() as $kLC) {
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

													
													}
													 ?>
														
												</tbody>
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
			var cambio=0;
			var conroller="<?php echo CREPORTES(); ?>";
			
			
				
				
				
				
				/*termina la configuracion de #dinamyc-table*/
			
				/*empieza configuracion para ver mapas*/
				
				$(".verPedido1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("VER1","");
					id=id.replace("VER2","");
					//$("#modalMapa").modal("show");
					var link="<?php echo CREPORTES(); ?>" + "verPedido/"+id;
				  	window.open(link,"_blank");
					//alert(id);
				});
				

					$(".btnActualizar").click(function(event) {
							/* Act on the event */
							location.reload();
						});


					$("#btnAplicar").click(function(event) {
							
					});
			
			var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					"language": {
				            "url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
				        },
				              "pageLength": -1,
				              "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
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
						"text": "<i class='fa fa-file-excel-o bigger-110 green'>Generar Excel</i> <span class='hidden'>Exportar a Excel Concentrado</span>",
						"className": "btn btn-white btn-primary btn-bold",
						"titleAttr": "EXCEL CONCENTRADO",
			            "title": 'Reporte de Pedidos - Concentrado',
			            "exportOptions": {
			                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
			                }
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
					$("#txtFInicio").change(function (event) {
						cambio=1;
						
					});
					$("#txtFFinal").change(function (event) {
						cambio=1;
					});
					$("#btnAplicar").click(function(event){
							var cadComa=/,/g;
							var ruta=$("#cmbRuta").val().toString();
							 var usuario=$("#cmbUsuario").val().toString();
							 var sucursal=$("#cmbSucursal").val().toString();
							 var seleccionadosUsuario = usuario.replace(cadComa, "|");
					        var seleccionadosUsuario2 =  usuario.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosSucursal = sucursal.replace(cadComa, "|");
					        var seleccionadosSucursal2 =  sucursal.replace(cadComa, ".COMACONTROL.");
					        var seleccionadosRuta = ruta.replace(cadComa, "|");
					        var seleccionadosRuta2 =  ruta.replace(cadComa, ".COMACONTROL.");
						if(cambio==0){

							 
							 //alert("<?php echo $cuantasvisitas; ?>");
							 $("#lblNumVisitas").html("<?php echo $cuantasvisitas; ?>");
					       $.post("<?php echo CREPORTES('postTotalesEfectividad');?>", {usuario: usuario, sucursal: sucursal, fechaI: $("#txtFInicio").val(), fechaF: $("#txtFFinal").val(), ruta: ruta},function(data){
											//alert(data);
												/*var losdatos=data.split("-");
													$("#lblTotPedidos").html(losdatos[1]);*/
						                   		 $("#lblNumVisitas").html(data);
										});
					        
							
							  if( seleccionadosRuta.includes("TODOS") ){
			                    myTable.column(7).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(7).search(seleccionadosRuta, true, false ).draw();  
			                  }
			                 
			               if( seleccionadosUsuario.includes("TODOS") ){
			                    myTable.column(6).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(6).search(seleccionadosUsuario, true, false ).draw();  
			                  }
			                  if( seleccionadosSucursal.includes("TODOS") ){
			                    myTable.column(8).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(8).search(seleccionadosSucursal, true, false ).draw();  
			                  }
						}
						else{
							window.location.href = "<?php echo CREPORTES(); ?>"+"listadoVisitas"+"/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val()+"/"+seleccionadosRuta2+"/"+seleccionadosUsuario2+"/"+seleccionadosSucursal2;
						}
						
					});
			
				$(".btnSacarTabla").click(function(event) {
					var link="<?php echo CREPORTES(); ?>" + "verPedidos/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val();
				  		window.open(link,"_blank");
				});
				$('.select2').css('width','200px').select2({allowClear:false})
				$('#select2-multiple-style .btn').on('click', function(e){
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('.select2').addClass('tag-input-style');
					 else $('.select2').removeClass('tag-input-style');
				});
				var elusuario="<?php echo $usuario; ?>";
							//alert(elusuario);
							var arrayUsuario=elusuario.split(",");

							for (var i = 0; i < arrayUsuario.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbUsuario option[value='"+arrayUsuario[i]+"']").attr("selected",true);
								$("#cmbUsuario").change();
							}
				var laruta="<?php echo $ruta; ?>";
							//alert(elusuario);
							var arrayRuta=laruta.split(",");

							for (var i = 0; i < arrayRuta.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbRuta option[value='"+arrayRuta[i]+"']").attr("selected",true);
								$("#cmbRuta").change();
							}
				var lasucursal="<?php echo $ruta; ?>";
							//alert(elusuario);
							var arraySucursal=lasucursal.split(",");

							for (var i = 0; i < arraySucursal.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbSucursal option[value='"+arraySucursal[i]+"']").attr("selected",true);
								$("#cmbSucursal").change();
							}
				window.onload = function(){
					var cadComa=/,/g;
							var ruta=$("#cmbRuta").val().toString();
							 var usuario=$("#cmbUsuario").val().toString();
							 var sucursal=$("#cmbSucursal").val().toString();
							//alert(sucursal);
							 //alert("<?php echo $cuantasvisitas; ?>");
							 $("#lblNumVisitas").html("<?php echo $cuantasvisitas; ?>");
					       $.post("<?php echo CREPORTES('postTotalesEfectividad');?>", {usuario: usuario, sucursal: sucursal, fechaI: $("#txtFInicio").val(), fechaF: $("#txtFFinal").val(), ruta: ruta},function(data){
											//alert(data);
												/*var losdatos=data.split("-");
													$("#lblTotPedidos").html(losdatos[1]);*/
						                   		 $("#lblNumVisitas").html(data);
										});
					        var seleccionadosUsuario = usuario.replace(cadComa, "|");
					        var seleccionadosUsuario2 =  usuario.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosSucursal = sucursal.replace(cadComa, "|");
					        var seleccionadosSucursal2 =  sucursal.replace(cadComa, ".COMACONTROL.");
					        var seleccionadosRuta = ruta.replace(cadComa, "|");
					        var seleccionadosRuta2 =  ruta.replace(cadComa, ".COMACONTROL.");
							
							  if( seleccionadosRuta.includes("TODOS") ){
			                    myTable.column(7).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(7).search(seleccionadosRuta, true, false ).draw();  
			                  }
			                 
			               if( seleccionadosUsuario.includes("TODOS") ){
			                    myTable.column(6).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(6).search(seleccionadosUsuario, true, false ).draw();  
			                  }
			                  if( seleccionadosSucursal.includes("TODOS") ){
			                    myTable.column(8).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(8).search(seleccionadosSucursal, true, false ).draw();  
			                  }
				}
		</script>
			

	</body>
</html>
