<?php 
$data['title']="LIZER Objetivos";
$categoria=str_replace("%20", " ", $categoria);

$categoria=str_replace("%C3%B1", "ñ", $categoria);
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu");
$categoria=str_replace(".COMACONTROL.", ",", $categoria);
$ruta=str_replace(".COMACONTROL.", ",", $ruta);
$sucursal=str_replace(".COMACONTROL.", ",", $sucursal);
$categoria=str_replace("%20"," ",$categoria);

$categoria=str_replace("%C3%B1","ñ",$categoria);
$total=0;
$cantidad=0;
$totaldias=21;
$diaspendientes=11;
//print_r($listaSucursales->result()); ?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Estadisticas / Objetivos
									
									
								</small>
							</h1>
							<?php echo "<br>".$ruta; ?>
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
									<div class="col-xs-2"><label for="">Mes</label><input id="txtPeriodo" type="input" class="form-control" value="<?php echo $periodo; ?>"></div>
									
									
									<div class="col-xs-2"><label for="">Ruta</label><br>
										<select name="cmbRuta" id="cmbRuta" class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Ruta)">
											<?php 
													if($ruta=="TODOS"){
														$rutatodos="selected";
													}
													else{
														$rutatodos="";
													}
													?>
														<option value="TODOS" <?php echo $rutatodos; ?>>Todos</option>
											<?php 
												
												foreach ($listaRutas->result() as $kR) {
													# code...
													if($ruta==$kR->ruta){
														$rutatodos="selected";
													}
													else{
														$rutatodos="";
													}
													?>
													<option value="<?php echo $kR->ruta; ?>"  <?php echo $rutatodos; ?>><?php echo $kR->ruta; ?></option>
													<?php 
													
												}
											 ?>
										</select>
									
									</div> 
									<div class="col-xs-2"><label for="">Categoria</label><br>
										<select name="cmbCategoria" id="cmbCategoria" class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Usuario)">
											<?php 
												if($categoria=="TODOS"){
													?>
														<option value="TODOS" selected>Todas</option>
												
											<?php 
												}
													else{
													?>
														<option value="TODOS">Todas</option>
												
											<?php 
												}
												foreach ($listaCategorias->result() as $kU) {
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
										<!-- 
										<div class="clearfix col-md-6" align="left">
										<div class="col-md-4">
										<h4><strong>No. Pedidos: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumPedidos">0</label></span>
										</div>
										<div class="col-md-4">
										<h4><strong>Total de Pedidos: </strong></h4><span class="label label-xlg label-primary"><label id="lblTotPedidos">$0.00</label></span><br>
										</div> -->
										
									</div>
										<div class="clearfix col-md-6 ">
											
										</div>
										<div class="clearfix col-md-6"><br></div>
										
										</div>
									
										<div class="row">

											<div class="col-xs-12"><div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
												<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
												<div class="clearfix">
													<!-- <div class="pull-right"><button class="btn btn-primary btnActualizar">Actualizar</button></div> -->
												</div>
												<?php 
												$datosObj=$this->EstadisticasModel->getDatosObjetivos($periodo);
												if($datosObj->num_rows()!=0){
													$diasMes=$datosObj->row()->diasMes;
													$diasTranscurridos=$datosObj->row()->diasTranscurridos;
												}
												else{
													$diasMes=0;
													$diasTranscurridos=0;
													}	
												 ?>
												
												<div class="clearfix col-md-6" align="left">
												<div class="col-md-4">
												<h4><strong>Dias Habiles: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumVisitas"><?php echo $diasMes; ?></label></span>
												
												</div>
												<div class="col-md-4">
												<h4><strong>Transcurridos: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumVisitas"><?php echo $diasTranscurridos; ?></label></span>
												
												</div>
												<div class="col-md-4">
												<h4><strong>Restantes: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumVisitas"><?php echo $diasMes-$diasTranscurridos; ?></label></span>
												
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
											

										<div class="col-xs-12">
										<div class="table-header">
											Objetivos y Acumulados.
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive">
											<table id="dynamic-table" class="table table-striped table-bordered table-hover tablex">
												<thead>
													<tr>
														<th colspan="3" style="text-align: center">Datos</th>
														<th colspan="3" style="text-align: center">Valores Netos</th>
														<th colspan="2" style="text-align: center">Proyección</th>
														<th colspan="2" style="text-align: center">Analisis</th>
														
													</tr>
													<tr>
														<th>Sucursal</th>
														<th>Ruta</th>
														<th>Categorias</th>
														<th>Objetivo</th>
														<th>Venta</th>
														<th>Alcance</th>
														
														<th>Venta</th>
														<th>Alcance</th>
														<!-- <th>Incentivo x<br>Categoria</th> -->
														<th>GAP</th>
														<th>Objetivo Diario</th>
														
														
														<!-- <th>Movil</th>
														<th>Prospecto</th> -->
														
														
														
													</tr>
												</thead>
												<tbody>
												<?php 
												if($lista->num_rows()!=0){
												 ?>
													<tr>
													<?php 
													
													foreach ($lista->result() as $kA) {
														# code...
																	
														?>
														<td>
															<?php 
															$sucursalA=$this->EstadisticasModel->getSucursalName($kA->idVendedor);
															echo $sucursalA->row()->sucursal;
															 ?>
															
															
														</td>
														<td>
															<?php 

															$rutaA=$this->EstadisticasModel->getRutaName($kA->idVendedor);
															if($rutaA->num_rows()!=0){
																echo $rutaA->row()->ruta;
															}
															else{
																echo "NR";
															}
															//echo $kA->idVendedor; ?>
														</td>
														<td>
															<?php echo $kA->categoria; ?>
														</td>
														<td>
															<?php echo FORMATO_DINERO($kA->objetivo); ?>
														</td>
														<td>
															<?php echo FORMATO_DINERO($kA->importe); ?>
														</td>
														<td>
															<?php 
																if($kA->objetivo!=0){
																	$porc=$kA->importe/($kA->objetivo/100);
																}
																else{
																	$porc=0;
																}
																echo FORMATO_PORCENTAJEDEC($porc);
															 ?>
														</td>
														<td>
															<?php 
																if($kA->diasTranscurridos!=0){
																	$ventaProy=($kA->importe/$kA->diasTranscurridos)*($kA->diasMes-$kA->diasTranscurridos);
																}
																else{
																	$ventaProy=0;
																}
																
																echo FORMATO_DINERO($ventaProy+$kA->importe);
															 ?>
														</td>
														<td>
															<?php 
																if($kA->objetivo!=0){
																	$alcanceProy=($kA->importe+$ventaProy)/($kA->objetivo/100);
																}
																else{
																	$alcanceProy=0;
																}
																
																echo FORMATO_PORCENTAJEDEC($alcanceProy);
															 ?>
														</td>
														<!-- <td>
															-
														</td> -->
														<td>
															
															<?php 
															
															$gap=($kA->objetivo)-$kA->importe;
															echo FORMATO_DINERO($gap); ?>
														</td>
														<td>
														<?php 
															//$objetivodiario=$kA->objetivo/$kA->diasMes;
															$diasrestantes=$kA->diasMes-$kA->diasTranscurridos;
															if($diasrestantes!=0){
																$objetivodiario=$gap/($diasrestantes);
															}
															else{
																$objetivodiario=0;
															}
															
															echo FORMATO_DINERO($objetivodiario);
														 ?>
														
														</td>
														</tr>
														<?php 

													}
												}
													 ?>
												</tbody>
											</table>
										</div>
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
		//var myTable=$('#dynamic-tablex').DataTable();
		var myTable = 
				$('.dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					"language": {
				            "url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
				        },
				              "pageLength": -1,
				              "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
				              "order": [[0,"asc"],[1,"asc"]],
				              
				              
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
						"titleAttr": "OBJETIVOS",
			            "title": 'Reporte de Objetivos',
			            "exportOptions": {
			                    columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 9 ]
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

				
			
			
					$("#txtPeriodo").change(function (event) {
						cambio=1;
						
					});
					
					$("#btnAplicar").click(function(event){
							var cadComa=/,/g;
							

							var ruta=$("#cmbRuta").val().toString();
							 var categoria=$("#cmbCategoria").val().toString();
							 var sucursal=$("#cmbSucursal").val().toString();
							
					        var seleccionadosCategoria = categoria.replace(cadComa, "|");
					        var seleccionadosCategoria2 =  categoria.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosSucursal = sucursal.replace(cadComa, "|");
					        var seleccionadosSucursal2 =  sucursal.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosRuta = ruta.replace(cadComa, "|");
					        var seleccionadosRuta2 =  ruta.replace(cadComa, ".COMACONTROL.");
												$("#lblTotPedidos").html("<?php echo FORMATO_DINERO($total); ?>");
						                   		 $("#lblNumPedidos").html("<?php echo $cantidad; ?>");
					        
						if(cambio==0){

							 
					       
							
							  if(seleccionadosCategoria.includes("TODOS") ){
			                    myTable.column(2).search("").draw();
			                    
			                  }
			                  else
			                  {

			                    myTable.column(2).search(seleccionadosCategoria, true, false ).draw();  
			                  }
			               
			                  if( seleccionadosSucursal.includes("TODOS") ){
			                    myTable.column(0).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(0).search(seleccionadosSucursal, true, false ).draw();  
			                  }
			                   if( seleccionadosRuta.includes("TODOS") ){
			                    myTable.column(1).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(1).search(seleccionadosRuta, true, false ).draw();  
			                  }
						}
						else{
							window.location.href = "<?php echo CESTADISTICAS(); ?>"+"ObjetivosAcumulados"+"/"+$("#txtPeriodo").val()+"/"+seleccionadosCategoria2+"/"+seleccionadosRuta2+"/"+seleccionadosSucursal2;
						}
						
					});
				
				$(".btnSacarTabla").click(function(event) {
					var link="<?php echo CREPORTES(); ?>" + "verPedidos/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val();
				  		window.open(link,"_blank");
				});
				var elusuario="<?php echo $categoria; ?>";
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
							var lacategoria="<?php echo $categoria; ?>";
							var arrayCategoria=lacategoria.split(",");
							for (var i = 0; i < arraySucursal.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbCategoria option[value='"+arrayCategoria[i]+"']").attr("selected",true);
								$("#cmbCategoria").change();
							}

							//alert($("#cmbUsuario").val());
							var categoria =$("#cmbCategoria").val();
							var ruta=$("#cmbRuta").val().toString();
							// var usuario=$("#cmbUsuario").val().toString();
							 var sucursal=$("#cmbSucursal").val().toString();
							
					       var seleccionadosCategoria = categoria.replace(cadComa, "|");
					        var seleccionadosCategoria2 =  categoria.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosSucursal = sucursal.replace(cadComa, "|");
					        var seleccionadosSucursal2 =  sucursal.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosRuta = ruta.replace(cadComa, "|");
					        var seleccionadosRuta2 =  ruta.replace(cadComa, ".COMACONTROL.");
					        
					        
							 if(seleccionadosCategoria.includes("TODOS") ){
			                    myTable.column(2).search("").draw();
			                    
			                  }
			                  else
			                  {

			                    myTable.column(2).search(seleccionadosCategoria, true, false ).draw();  
			                  }
			               
			                  if( seleccionadosSucursal.includes("TODOS") ){
			                    myTable.column(0).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(0).search(seleccionadosSucursal, true, false ).draw();  
			                  }
			                   if( seleccionadosRuta.includes("TODOS") ){
			                    myTable.column(1).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(1).search(seleccionadosRuta, true, false ).draw();  
			                  }
				}
				
		</script>
			

	</body>
</html>
