<?php 
$data['title']="LIZER Reportes-Agenda de Visitas";

$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); 

$ruta=str_replace(".COMACONTROL.", ",", $ruta);
$sucursal=str_replace(".COMACONTROL.", ",", $sucursal);
$usuario=str_replace(".COMACONTROL.", ",", $usuario);
$usuario=str_replace("%20"," ",$usuario);

$usuario=str_replace("%C3%B1","ñ",$usuario);
$cuantospedidos=0;
$cuantasvisitas=0;
$totalpedidos=0;
$porcentaje=0;
//print_r($listaUsuarios->result());

//echo AGREGARDIAS(3,$fIni);
//echo $ruta;
?>

			<div id="principal" class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Reportes / Lista de Cumplimiento de Agenda.
									<!-- <br><?php //echo $fIni." ".$fFin; 
										phpinfo();
									?> -->
									<?php 	
										$dia=date('w', strtotime('2018-10-23'));
										echo $dia+1;
	 								?>
								</small>
							</h1>
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
									<div class="col-xs-2"><label for="">Inicio</label><input id="txtFInicio" type="date" class="form-control filtros" value="<?php echo $fIni; ?>"></div>
									<div class="col-xs-2"><label for="">Final</label><input id="txtFFinal" type="date" class="form-control filtros" value="<?php echo $fFin; ?>"></div>
									<div class="col-xs-2"><label for="">Rutas</label><br>
										<select name="cmbRuta" id="cmbRuta" class="selectpicker form-control filtros" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Ruta)">
											<?php 

												if($ruta=="TODOS"){
													?>
													<option value="TODOS" selected>Todas</option>
													<?php 
												}
											
												else{
													?>
														<option value="TODOS">Todas</option>
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
									 <div class="col-xs-2"><label for="">Usuarios</label>
										<select name="cmbUsuario" id="cmbUsuario" class="selectpicker form-control filtros" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Usuario)">
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
										<select name="cmbSucursal" id="cmbSucursal"  class="selectpicker form-control filtros" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Sucursal)">
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
											<div class="pull-right tableTools-container"><button id="btnAplicar" class="btn btn-primary">Aplicar</button><button class="btn btn-success btnActualizar">Actualizar</button></div>
										</div>
										<div class="clearfix">
											<div class="pull-right tableTools-container"><button class="btn btn-white btnSacarTabla"><i class="ace-icon fa fa-file-excel-o bigger-130"></i>Generar Excel</button></div>
										</div>
										</div>
										</div>
										<div class="col-xs-12">
										<div class="table-header">
											Listado de Cumplimiento de Agenda.
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														
														
														
														<th>Usuario</th>
														<th>Ruta</th>
														<th>Efectividad (Hechas/Programadas)</th>
														<th>Hechas</th>
														<th>Programadas</th>
														<th>Inicio</th>
														<th>Fin</th>														
														
														<th>Sucursal</th>
														
														<th>Acciones</th>
														
													</tr>
												</thead>
												<tbody>
													
													
													<?php 
													
														$contador=0;
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
														/*$numeroVisitas=$datosVisitas->row()->numeroVisitas;
														$cuantasvisitas=$cuantasvisitas+$numeroVisitas;
														$numeroVentas=$datosPedidos->row()->numeroVentas;
														$cuantospedidos=$cuantospedidos+$numeroVentas;
														$primera=$datosVisitas->row()->primera;
														$ultima=$datosVisitas->row()->ultima;*/
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
			var fechaInicio="<?php echo $fIni; ?>";
			var fechaFin="<?php echo $fFin; ?>";
			//alert(fechaInicio+" "+fechaFin);
			
				
				
				
				
				/*termina la configuracion de #dinamyc-table*/
			
				/*empieza configuracion para ver mapas*/
				function verRegistro(idUsuario,fIni,fFin){
					alert(idUsuario,fIni,fFin);
					/*var link="<?php echo CREPORTES(); ?>" + "verAcciones/"+idUsuario+"/"+fIni+"/"+fFin;
				  	window.open(link,"_blank");*/
				}
				$(".verPedido1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("VER1","");
					id=id.replace("VER2","");
					//alert(id+" "+fechaInicio+" "+fechaFin);
					//$("#modalMapa").modal("show");
					var link="<?php echo CREPORTES(); ?>" + "verAcciones/"+id+"/"+fechaInicio+"/"+fechaFin;
					//alert(link);
				  	window.open(link,"_blank");
					//alert(id);
				});
				

					$(".btnActualizar").click(function(event) {
							/* Act on the event */
							location.reload();
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
				              "order": [[0,"asc"]],
				   drawCallback: function (){
				   	var api = this.api();
				   	//alert(api.column( 3, {page:'all'} ).data().sum());
				   	var lasprogramadas=api.column( 4, {filter: "applied"} ).data().sum();
				   	var lashechas=api.column( 3, {filter: "applied"} ).data().sum();
				   		if (lashechas==0){
				   			var porcentaje=0;
				   		}
				   		else{
				   			var porcentaje=lashechas/(lasprogramadas/100);
				   		}
				   	//alert(lasprogramadas+" - "+lashechas);
				   		$("#lblNumVisitas").html(lasprogramadas);
						                   		 $("#lblNumPedidos").html(lashechas);
						                   		 $.post("<?php echo CREPORTES('postPorcentajeObtener');?>", {porcentaje: porcentaje},function(data){
						                   		 	$("#lblTotPedidos").html(data);
						                   		 });
						                   		 
				   }           
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

					/*  {
						"extend": "excel",
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Exportar a Excel Concentrado</span>",
						"className": "btn btn-white btn-primary btn-bold",
						"titleAttr": "EXCEL CONCENTRADO",
			            "title": 'Reporte de Pedidos - Concentrado',
			            "exportOptions": {
			                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
			                }
					  }*/
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
							
							
								hacerFiltrado();	
				
							
							
						
					});
				function hacerFiltrado(){

							var cadComa=/,/g;
						/*$("#lblTotPedidos").html("<?php echo FORMATO_DINERO($totalpedidos); ?>");
						$("#lblNumVisitas").html("<?php echo $cuantasvisitas; ?>");
												
						                   		 $("#lblNumPedidos").html("<?php echo $cuantospedidos; ?>");*/
							var laruta="<?php echo $ruta; ?>";
							var elusuario="<?php echo $usuario; ?>";
							/*alert(elusuario);
							alert(laruta);*/
							var arrayRuta=laruta.split(",");
							for (var i = 0; i < arrayRuta.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbRuta option[value='"+arrayRuta[i]+"']").attr("selected",true);
								$("#cmbRuta").change();
							}
							var arrayUsuario=elusuario.split(",");
							for (var i = 0; i < arrayUsuario.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbUsuario option[value='"+arrayUsuario[i]+"']").attr("selected",true);
								$("#cmbUsuario").change();
							}
							
							var sucursal="<?php echo $sucursal; ?>";
							//alert(laruta);
							//alert(sucursal);
							var arraySucursal=sucursal.split(",");
							for (var i = 0; i < arraySucursal.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbSucursal option[value='"+arraySucursal[i]+"']").attr("selected",true);
								$("#cmbSucursal").change();
							}
								var usuario=$("#cmbUsuario").val().toString();
							
							var ruta=$("#cmbRuta").val().toString();
							var sucursal=$("#cmbSucursal").val().toString();
							//alert(ruta);
							 //alert($("#cmbUsuario").val());
							//alert("Hola");
					
					        var seleccionadosRuta = ruta.replace(cadComa, "|");
					        var seleccionadosRuta2 =  ruta.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosSucursal = sucursal.replace(cadComa, "|");
					        var seleccionadosSucursal2 =  sucursal.replace(cadComa, ".COMACONTROL.");
						
							var seleccionadosUsuario = usuario.replace(cadComa, "|");
					        var seleccionadosUsuario2 =  usuario.replace(cadComa, ".COMACONTROL.");
							     					
					       if(cambio==1){
								window.location.href = "<?php echo CREPORTES(); ?>"+"listaCumplimientoAgenda"+"/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val()+"/"+seleccionadosUsuario2+"/"+seleccionadosRuta2+"/"+seleccionadosSucursal2;	
							}
							 else{
					       
							
							  
			                 if( seleccionadosUsuario.includes("TODOS") ){
			                    myTable.column(0).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(0).search(seleccionadosUsuario, true, false ).draw();  
			                  }
			               if( seleccionadosRuta.includes("TODOS") ){
			                    myTable.column(1).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(1).search(seleccionadosRuta, true, false ).draw();  
			                  }
			                  if( seleccionadosSucursal.includes("TODOS") ){
			                    myTable.column(7).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(7).search(seleccionadosSucursal, true, false ).draw();  
			                  }
			              }
				}
				$(".filtros").keypress(function(event) {
					/* Act on the event */
					 if(event.which == 13) {
				          hacerFiltrado();
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
				//totales();
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
				var lasucursal="<?php echo $sucursal; ?>";
							//alert(elusuario);
							var arraySucursal=lasucursal.split(",");

							for (var i = 0; i < arraySucursal.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbSucursal option[value='"+arraySucursal[i]+"']").attr("selected",true);
								$("#cmbSucursal").change();
							}
				window.onload = function(){
						
						var cadComa=/,/g;
						//$("#lblTotPedidos").html("<?php echo FORMATO_DINERO($totalpedidos); ?>");
						//$("#lblNumVisitas").html("<?php echo $cuantasvisitas; ?>");
												
						  //                 		 $("#lblNumPedidos").html("<?php echo $cuantospedidos; ?>");
						                   		 //hacerFiltrado();
							var laruta="<?php echo $ruta; ?>";
							var elusuario="<?php echo $usuario; ?>";
							/*alert(elusuario);
							alert(laruta);*/
							var arrayRuta=laruta.split(",");
							for (var i = 0; i < arrayRuta.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbRuta option[value='"+arrayRuta[i]+"']").attr("selected",true);
								$("#cmbRuta").change();
							}
							var arrayUsuario=elusuario.split(",");
							for (var i = 0; i < arrayUsuario.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbUsuario option[value='"+arrayUsuario[i]+"']").attr("selected",true);
								$("#cmbUsuario").change();
							}
							
							var sucursal="<?php echo $sucursal; ?>";
							//alert(laruta);
							//alert(sucursal);
							var arraySucursal=sucursal.split(",");
							for (var i = 0; i < arraySucursal.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbSucursal option[value='"+arraySucursal[i]+"']").attr("selected",true);
								$("#cmbSucursal").change();
							}
								var usuario=$("#cmbUsuario").val().toString();
							
							var rutax=$("#cmbRuta").val().toString();
							var sucursalx=$("#cmbSucursal").val().toString();
							var usuariox=$("#cmbUsuario").val().toString();
							 //alert($("#cmbUsuario").val());
							//alert("Hola");
							//alert(laruta);
						hacerFiltrado();
							   			
					       
							 
					       
							
							/*$.post("<?php echo CREPORTES('postVisitasProgramadas');?>", {usuario: usuariox, sucursal: sucursalx, fechaI: $("#txtFInicio").val(), fechaF: $("#txtFFinal").val(), ruta: rutax},function(data){
											alert(data);
												var losdatos=data.split("-");
													$("#lblNumVisitas").html(losdatos[0]);
						                   		 $("#lblNumPedidos").html(losdatos[1]);
						                   		 $("#lblTotPedidos").html(losdatos[2]);
										});*/
					        
						
						
				}
		</script>
			

	</body>
</html>
