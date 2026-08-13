<?php 
$data['title']="LIZER Reportes-Pedidos";
$usuario=str_replace("%20", " ", $usuario);

$usuario=str_replace("%C3%B1", "ñ", $usuario);
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu");
$usuario=str_replace(".COMACONTROL.", ",", $usuario);
$ruta=str_replace(".COMACONTROL.", ",", $ruta);
$sucursal=str_replace(".COMACONTROL.", ",", $sucursal);
$usuario=str_replace("%20"," ",$usuario);

$usuario=str_replace("%C3%B1","ñ",$usuario);
$total=0;
$cantidad=0;
$corte=VERIFICARPERFILFUNCION("Reportes","hacerCorte",$this->session->userdata('perfilLIZER'));
//print_r($listaSucursales->result()); ?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Reportes / Pedidos - Preventas
									
									
								</small>
							</h1>
							<?php //echo "<br>".$sucursal; ?>
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
									<div class="col-xs-2"><label for="">Tipo</label>
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
									</div>
									<div class="col-xs-2"><label for="">Ruta</label><br>
										<select name="cmbRuta" id="cmbRuta" class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Ruta)">
											<?php 

													?>
														<option value="TODOS">Todos</option>
											<?php 
												
												foreach ($listaRutas->result() as $kR) {
													# code...
													
													?>
													<option value="<?php echo $kR->ruta; ?>"><?php echo $kR->ruta; ?></option>
													<?php 
													
												}
											 ?>
										</select>
									
									</div>
									<div class="col-xs-2"><label for="">Usuario</label><br>
										<select name="cmbUsuario" id="cmbUsuario" class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Usuario)">
											<?php 

													?>
														<option value="TODOS">Todos</option>
											<?php 
												
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
													
														?>
														<option value="<?php echo $kS->sucursal; ?>"><?php echo $kS->sucursal; ?></option>
														<?php 
													
													
												}
											 ?>
										</select>
									</div>
									
									</div>
									
									<div class="row"><div class="col-xs-12"><hr></div></div>
									<div class="row">
									<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
										<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
										<div class="clearfix">
											<!-- <div class="pull-right"><button class="btn btn-primary btnActualizar">Actualizar</button></div> -->
										</div>
										
										<div class="clearfix col-md-6" align="left">
										<div class="col-md-4">
										<h4><strong>No. Pedidos: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumPedidos">0</label></span>
										</div>
										<div class="col-md-4">
										<h4><strong>Total de Pedidos: </strong></h4><span class="label label-xlg label-primary"><label id="lblTotPedidos">$0.00</label></span><br>
										</div>
										
									</div>
										<div class="clearfix col-md-6">
											<div class="pull-right"><button id="btnAplicar" class="btn btn-primary">Aplicar</button><button class="btn btn-success btnActualizar">Actualizar</button><?php if($corte==1){ ?><button class="btn btn-warning btnCorte">Corte</button><?php } ?><!-- <button class="btn btn-default btnAcumulados">Acumulados</button></div> -->
										</div>
										<div class="clearfix col-md-6"><br></div>
										
										</div>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="pull-right"><button class="btn btn-white btnSacarTabla"><i class="ace-icon fa fa-file-excel-o bigger-130"></i>Generar Excel</button></div>
											</div>
										</div>
										<div class="col-xs-12">
										<div class="table-header">
											Listado de Pedidos.
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Folio</th>
														<th>Tipo</th>
														<th>Fecha</th>
														<th>Cliente</th>
														<th hidden>Total</th>
														<th>Total</th>
														<th>Usuario</th>
														<th>Ruta</th>
														<th>Sucursal</th>
														<th>Estatus</th>
														<!-- <th>Movil</th>
														<th>Prospecto</th> -->
														<th>Acciones</th>
														
														
													</tr>
												</thead>
												<tbody>
													
													
													<?php 

													foreach ($lista->result() as $kLC) {
														?>
													<tr>
														<td><?php echo $kLC->folio; ?></td>
														<td><?php echo $kLC->tipo; ?></td>
														<td><?php echo $kLC->fechacreacion; ?></td>
														<td>
															<?php echo $kLC->nombreCliente;
															 ?>

														</td>
														<td hidden><?php 
															$eltotal=0;
																if($kLC->status!=0){
																	
																
															
																if($kLC->tipo=="PREVENTA"){
																	$eltotal=$kLC->total;
																	$total=$total+$eltotal;
																	$cantidad=$cantidad+1;
																}
																else{
																	//echo $kLC->total*(-1);
																}
																}
																echo $eltotal;
																 ?></td>
														<td>
															<?php 
																if($kLC->tipo=="PREVENTA"){
																	echo FORMATO_DINERO($kLC->total);
																}
																else{
																	echo FORMATO_DINERO($kLC->total*(-1));
																}
																
																 ?>
																

														</td>
														<td>
															<?php 
															 echo $kLC->nombreUsuario;
														?>
														</td>
														<td>
															<?php echo $kLC->ruta; ?>
														</td>
														<td>
															<?php 
															 echo $kLC->sucursal;
														?>
														</td>
														<td>
															<?php
															
															if($kLC->status==1){
																$EP="ACTIVO";
																?>
																<span class="label label-sm label-success"><?php echo $EP; ?></span>
															<?php 
															}
																														
															else{ 
																$EP="CANCELADO";
																?>
																<span class="label label-sm label-danger"><?php echo $EP; ?></span>
																 <?php 
															}
														?>
														</td>
														<td><div class="hidden-sm hidden-xs action-buttons">
																<a id="VER1<?php echo $kLC->id; ?>" class="blue verPedido1">
																	<i class="ace-icon fa fa-eye bigger-130"></i>
																</a>

																<a id="CANCEL1<?php echo $kLC->id; ?>" class="red cancelarPedido1" href="#" title="Cancelar">
																	<i class="ace-icon fa fa-trash bigger-130"></i>
																</a>
																<?php 
																if($kLC->impreso==0){
																?>
																<a id="IMPRIMIR1<?php echo $kLC->id; ?>" class="red imprimirPedido1" href="#" title="Imprimir">
																	<i class="ace-icon fa fa-print bigger-130"></i>
																</a>
																<?php 
																} 
																else{
																?>
																<a id="IMPRIMIR1<?php echo $kLC->id; ?>" class="green imprimirPedido1" href="#" title="Imprimir">
																	<i class="ace-icon fa fa-print bigger-130"></i>
																</a>
																<?php 
																}
																 ?>
																
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
																			<a id="VER2<?php echo $kLC->id; ?>" class="tooltip-info verPedido1" data-rel="tooltip" title="Ver">
																				<span class="blue">
																					<i class="ace-icon fa fa-eye bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a id="CANCEL2<?php echo $kLC->id; ?>" href="#" class="tooltip-success cancelarPedido1" data-rel="tooltip" title="Cancelar">
																				<span class="red">
																					<i class="ace-icon fa fa-trash bigger-120"></i>
																				</span>
																			</a>
																		</li>
																		<li>
																			<a id="IMPRIMIR2<?php echo $kLC->id; ?>" href="#" class="tooltip-success imprimirPedido1" data-rel="tooltip" title="Imprimir">
																				
																				<?php 
																				if($kLC->impreso==0){
																				 ?>
																				<span class="red">
																					<i class="ace-icon fa fa-print bigger-120"></i>
																				</span>
																				 <?php 
																				}
																				else{
																				?>
																				<span class="green">
																					<i class="ace-icon fa fa-print bigger-120"></i>
																				</span>
																				 <?php	
																				}
																				?>
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

													
													}
													 ?>
														
												</tbody>
												<!-- <tfoot>
										                                          
										                                                <td>Totales</td>
										                                                <td></td>
										                                                <td></td>
										                                                <td></td>
										                                                <td hidden></td>
										                                                <td><label id="lblTotales"></label></td>
										                                                <td></td>
										                                                <td></td>
										                                                <td></td>
										                                                <td></td>
										                                                <td></td>
										                                           
										                                                      
										                                          </tfoot> -->
											</table>
											<?php 
												$ttotal=FORMATO_DINERO($total);
											 ?>
										</div><!-- empieza div que contiene a la tabla -->
									</div><!--  termina div.col-xs-12 de la tabla clientes-->
								</div><!--  termina div.row de la tabla clientes-->
								
<!-- Modal -->
							<div id="modalDepurar" class="modal fade">
							          <div class="modal-dialog modal-sm">
							            <div class="modal-content">
							              <!-- dialog body -->
							              <div class="modal-header">
							              <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
							                  <h4>Corte de Pedidos.</h4>
							              
							                
							                
							                <div class="col-md-12 row">
							                       <h5>¿Hasta que fecha se hara el corte?</h5>
							                </div>
							               	<div class="col-md-12 row">
							               		<input type="date" name="txtFechaCorte" id="txtFechaCorte">
							               	</div>
							              </div>
							              <div class="modal-footer">
							                    
							                                      
							                    <button id="btnAceptarX" type="button" class="btn btn-success">REALIZAR</button><button  type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
							                </div>
							            </div>
							          </div>
							        </div>
								

							

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
			
			function numberFormat(numero){
		        // Variable que contendra el resultado final
		        var resultado = "";
		 
		        // Si el numero empieza por el valor "-" (numero negativo)
		        if(numero[0]=="-")
		        {
		            // Cogemos el numero eliminando los posibles puntos que tenga, y sin
		            // el signo negativo
		            nuevoNumero=numero.substring(1);
		        }else{
		            // Cogemos el numero eliminando los posibles puntos que tenga
		            nuevoNumero=numero;
		        }
		// var nuevoNumero=numero;
		        // Si tiene decimales, se los quitamos al numero
		        if(numero.indexOf(".")>=0)
		            nuevoNumero=nuevoNumero.substring(0,nuevoNumero.indexOf("."));
		 
		        // Ponemos un punto cada 3 caracteres
		        for (var j, i = nuevoNumero.length - 1, j = 0; i >= 0; i--, j++)
		            resultado = nuevoNumero.charAt(i) + ((j > 0) && (j % 3 == 0)? ",": "") + resultado;
		 
		        // Si tiene decimales, se lo añadimos al numero una vez forateado con 
		        // los separadores de miles
		        if(numero.indexOf(".")>=0)
		            resultado+=numero.substring(numero.indexOf("."),numero.indexOf(".")+3);
		 
		        if(numero[0]=="-")
		        {
		            // Devolvemos el valor añadiendo al inicio el signo negativo
		            return "-"+resultado;
		        }else{
		            return resultado;
		        }
		    }
			
				
				
				
				
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
				$(".cancelarPedido1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("CANCEL1","");
					id=id.replace("CANCEL2","");
					//$("#modalMapa").modal("show");
					var link="<?php echo CREPORTES(); ?>" + "verPedido/"+id;
				  	$.post("<?php echo CREPORTES("eliminarPedido");?>", {id: id},function(data){ 
				  		window.location.href = "<?php echo CREPORTES(); ?>"+"listadoPedidos"+"/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val()+"/"+$("#cmbTipo").val()+"/"+$("#cmbUsuario").val()+"/"+$("#cmbSucursal").val();
				          });
						
				});
				$(".imprimirPedido1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("IMPRIMIR1","");
					id=id.replace("IMPRIMIR2","");
					$(this).removeClass("red");
					$(this).addClass("green")
					//$("#modalMapa").modal("show");
					var link="<?php echo CREPORTES(); ?>" + "imprimirPedido/"+id;
				  	window.open(link,"_blank");
					//alert(id);
				});
					$("#btnAceptarX").click(function(event) {
						/* Act on the event */
						//alert("Hola");
						$.post("<?php echo CREPORTES('hacerCorte');?>", {fecha: $("#txtFechaCorte").val()},function(data){
											//alert(data);
											//alert(data);
										});
						$("#modalDepurar").modal("hide");//
					});
					$(".btnActualizar").click(function(event) {
							/* Act on the event */
							location.reload();
						});
					$(".btnCorte").click(function(event) {
						/* Act on the event */
						$("#modalDepurar").modal("show");
					});
					$(".btnAcumulados").click(function(event) {
						/* Act on the event */
						//alert("Hola");
						$.post("<?php echo CREPORTES('leerAcumuladosJson');?>", {usuario: "usuario"},function(data){
											//alert(data);
											//alert(data);
										});

					/*	var link="<?php echo CREPORTES(); ?>" + "leerAcumuladosJson/";
				  			window.open(link,"_blank");*/
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
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Exportar a Excel Concentrado</span>",
						"className": "btn btn-white btn-primary btn-bold",
						"titleAttr": "EXCEL CONCENTRADO",
			            "title": 'Reporte de Pedidos - Concentrado',
			            "exportOptions": {
			                    columns: [ 0, 1, 2, 3, 4, 6, 7, 8 ]
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
							var tipo = $("#cmbTipo").val();

							var ruta=$("#cmbRuta").val().toString();
							 var usuario=$("#cmbUsuario").val().toString();
							 var sucursal=$("#cmbSucursal").val().toString();
							
					        var seleccionadosUsuario = usuario.replace(cadComa, "|");
					        var seleccionadosUsuario2 =  usuario.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosSucursal = sucursal.replace(cadComa, "|");
					        var seleccionadosSucursal2 =  sucursal.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosRuta = ruta.replace(cadComa, "|");
					        var seleccionadosRuta2 =  ruta.replace(cadComa, ".COMACONTROL.");
												$("#lblTotPedidos").html("<?php echo FORMATO_DINERO($total); ?>");
						                   		 $("#lblNumPedidos").html("<?php echo $cantidad; ?>");
					        $.post("<?php echo CREPORTES('postTotalesPedidos');?>", {usuario: usuario, sucursal: sucursal, tipo: tipo, fechaI: $("#txtFInicio").val(), fechaF: $("#txtFFinal").val(), ruta: ruta},function(data){
											//alert(data);
												var losdatos=data.split("-");
													$("#lblTotPedidos").html(losdatos[1]);
						                   		 $("#lblNumPedidos").html(losdatos[0]);
										});
						if(cambio==0){

							 
					       
							
							  if( tipo.includes("TODOS") ){
			                    myTable.column(1).search("").draw();
			                    
			                  }
			                  else
			                  {

			                    myTable.column(1).search(tipo, true, false ).draw();  
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
			                   if( seleccionadosRuta.includes("TODOS") ){
			                    myTable.column(7).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(7).search(seleccionadosRuta, true, false ).draw();  
			                  }
						}
						else{
							window.location.href = "<?php echo CREPORTES(); ?>"+"listadoPedidos"+"/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val()+"/"+$("#cmbTipo").val()+"/"+seleccionadosUsuario2+"/"+seleccionadosSucursal2;
						}
						
					});
				
				$(".btnSacarTabla").click(function(event) {
					var link="<?php echo CREPORTES(); ?>" + "verPedidos/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val();
				  		window.open(link,"_blank");
				});
				var elusuario="<?php echo $usuario; ?>";
							//alert(elusuario);
							var arrayUsuario=elusuario.split(",");

							for (var i = 0; i < arrayUsuario.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbUsuario option[value='"+arrayUsuario[i]+"']").attr("selected",true);
								$("#cmbUsuario").change();
							}
				
				window.onload = function(){
							var cadComa=/,/g;
							var laruta="<?php echo $ruta; ?>";
							var arrayRuta=laruta.split(",");

							for (var i = 0; i < arrayRuta.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbRuta option[value='"+arrayRuta[i]+"']").attr("selected",true);
								$("#cmbRuta").change();
							}
							
							var lasucursal="<?php echo $sucursal; ?>";
							var arraySucursal=lasucursal.split(",");

							for (var i = 0; i < arraySucursal.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbSucursal option[value='"+arraySucursal[i]+"']").attr("selected",true);
								$("#cmbSucursal").change();
							}
							
							//alert($("#cmbUsuario").val());
							var tipo =$("#cmbTipo").val();
							var ruta=$("#cmbRuta").val().toString();
							 var usuario=$("#cmbUsuario").val().toString();
							 var sucursal=$("#cmbSucursal").val().toString();
							
					        var seleccionadosUsuario = usuario.replace(cadComa, "|");
					        var seleccionadosUsuario2 =  usuario.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosSucursal = sucursal.replace(cadComa, "|");
					        var seleccionadosSucursal2 =  sucursal.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosRuta = ruta.replace(cadComa, "|");
					        var seleccionadosRuta2 =  ruta.replace(cadComa, ".COMACONTROL.");
					        $("#lblTotPedidos").html("<?php echo FORMATO_DINERO($total); ?>");
						                   		 $("#lblNumPedidos").html("<?php echo $cantidad; ?>");
					        $.post("<?php echo CREPORTES('postTotalesPedidos');?>", {usuario: usuario, sucursal: sucursal, tipo: tipo, fechaI: $("#txtFInicio").val(), fechaF: $("#txtFFinal").val(), ruta: ruta},function(data){
											//alert(data);
												var losdatos=data.split("-");
													$("#lblTotPedidos").html(losdatos[1]);
						                   		 $("#lblNumPedidos").html(losdatos[0]);
										});
					        /*
					        var seleccionadosSucursal = replaceAll(sucursal, ",", "|");
					        var seleccionadosSucursal2 = replaceAll(sucursal, ",", ".COMACONTROL.");*/
							//alert(usuario);
							
							  if( tipo.includes("TODOS") ){
			                    myTable.column(1).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(1).search(tipo, true, false ).draw();  
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
			                  if( seleccionadosRuta.includes("TODOS") ){
			                    myTable.column(7).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(7).search(seleccionadosRuta, true, false ).draw();  
			                  }
				}
				
		</script>
			

	</body>
</html>
