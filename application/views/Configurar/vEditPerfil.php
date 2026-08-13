<?php 
$data['title']="LIZER Agregar Perfil";
$this->load->view("vHead",$data); 
//print_r($listaModulos->result());
//print_r($poligonoDatos->result());
//$coordenadas='[{"lat":"'.$latitud.'","lon":"'.$longitud.'","pop":"Hola"}]';

//echo GETNEWCLIENTENAME(1);
//echo $coordenadas;.
/*foreach ($poligonoDatos->result() as $k) {
	# code...
	$poligono=$k->coordenadas;
	//echo "<br>".$poligono;
	$poligonoC=$k->color; 
}*/
?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
 




			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								LIZER Sistema de Distribucion
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Configuracion / Perfiles
									
									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-10">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
									<div class="col-md-12 col-xs-12 col-sm-12" align="right">
										<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
										<button class="btn btn-danger" onclick="window.close();">CERRAR</button>
									</div>
									</div>
									<div class="col-xs-12"><br></div>
									<div class="space-40"></div>
									
										
										<div class="col-xs-12">
									<form id="frmDatos" action="<?php echo CCONFIGURAR('saveEditarPerfil'); ?>" method="POST">
										<div class="row">
									<div class="col-sm-12">
										
															<div class="row" align="center">
																<div class="col-xs-12">
																	<h4 class="control-label green">EDITAR PERFIL</h4>
																</div>
															</div>
															<div class="space-40"><br></div>
											
													<div class="row">
														<div class="col-xs-12">
															<div class="form-horizontal" role="form">
																<!-- <div class="form-group row">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Codigo: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtCodigo" name="txtCodigo" class="col-xs-10 col-sm-5" value=""/>
																	</div>
																</div> -->
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Perfil: </label>

																	<div class="col-sm-8">
																		<input type="hidden" name="txtId" value="<?php echo $datosPerfil->row()->id; ?>">
																		<input type="hidden" name="txtPerfilOld" value="<?php echo $datosPerfil->row()->perfil; ?>">
																		<input type="text" id="txtPerfil" name="txtPerfil" class="form-control" value="<?php echo $datosPerfil->row()->perfil; ?>"/>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Descripcion: </label>

																	<div class="col-sm-8">
																		
																		<textarea id="txtDescripcion" name="txtDescripcion" class="form-control"><?php echo $datosPerfil->row()->descripcion; ?></textarea>
																	</div>
																</div>
																<div class="form-group">
																	
																	<label  class="col-sm-offset-4 col-sm-2 no-padding-right blue">  
																		<?php 
																		$status=$datosPerfil->row()->status;
																			if($status==1){
																				?>
																					<input id="checkActivo" name="checkActivo" class="ace" type="checkbox"/ checked>
																				<?php 
																			}
																			else{
																				?>
																					<input id="checkActivo" name="checkActivo" class="ace" type="checkbox"/>
																				<?php 
																			}
																		 ?>
																		
																		<span class="lbl">Activo</span>
																	</label>
																	
																
																
																

																</div>
																
																
																
															
																

															</div>
														</div>
													</div>
													<div class="col-sm-10 col-sm-offset-3">
														<div class="row">
															<div class="col-xs-12">
															<div class="widget-box widget-color-blue">
															<div class="widget-header">
																	<h4 class="widget-title">Permisos de Acceso</h4>

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
																
																<div class="tabbable">
																	<ul class="nav nav-tabs padding-18">
																	<?php 
																		$contador1=0;
																		foreach ($listaModulos->result() as $kMod1) {
																			if($contador1==0){
																				echo '<li class="active">';
																				$contador1=1;
																			}
																			else{

																				echo '<li>';
																			}
																	 ?>
																		
																			<a data-toggle="tab" href="#<?php echo str_replace(" ", "", $kMod1->modulo); ?>">
																				<i class="<?php echo $kMod1->color; ?> ace-icon <?php echo $kMod1->icono; ?> bigger-120"></i>
																				<?php echo $kMod1->modulo; ?>
																			</a>
																		</li>

																		<?php 
																			}
																		 ?>
																	</ul>
																	<div class="tab-content no-border padding-24">
																		<?php 
																		$contador2=0;
																		foreach ($listaModulos->result() as $kMod1) {
																				if($contador2==0){
																					$cadena="in active";
																					$contador2=$contador2+1;
																				}
																				else{
																					$cadena="";
																					$contador2=$contador2+1;
																				}
																			
																		 ?>
																			<div id="<?php echo str_replace(" ", "", $kMod1->modulo); ?>" class="tab-pane <?php echo $cadena; ?>">

																				<?php 
																					$submodulo=$this->ConfigurarModel->getListaSubModulos($kMod1->modulo);
																					foreach ($submodulo->result() as $kSM) {
																						?>
																							<div class="row">
																							<label  class="col-sm-offset-2 col-sm-10 no-padding-right blue">  
																								<?php 
																								$status=GETACCESOX($kSM->controlador,$kSM->funcion,$datosPerfil->row()->perfil);
																								//$status=0;
																									if($status==1){
																										?>
																											<input id="Check<?php echo $kSM->id; ?>" name="Check<?php echo $kSM->id; ?>" class="ace" type="checkbox"/ checked>
																										<?php 
																									}
																									else{
																										?>
																											<input id="Check<?php echo $kSM->id; ?>" name="Check<?php echo $kSM->id; ?>" class="ace" type="checkbox"/>
																										<?php 
																									}
																								 ?>
																								
																								<span class="lbl"><strong><?php echo $kSM->submodulo; ?> -</strong> <span class="green"> <?php echo $kSM->descripcion; ?></span></span>
																							</label>
																							</div>
																						<?php 
																					}
																				 ?>
																			</div>
																		
																		<?php 
																			}
																		 ?>
																	</div>
																</div><!--  tabbable -->
																</div></div></div>
															</div>
														</div>
													</div>
													
												
									</div><!-- /.col -->
									

								</div><!-- /.row -->
									
								</form>
										
											
										</div><!-- empieza div que contiene a la tabla -->
									</div><!--  termina div.col-xs-12 de la tabla clientes-->

									<div class="space-40"><br></div>
									<div class="col-md-12 col-xs-12 col-sm-12" align="center"><br>
										<button id="btnGuardar" class="btn btn-success btnGuardar">GUARDAR</button>
								<!-- <button class="btn btn-danger" onclick="window.close();">CERRAR</button> -->
										<!-- <button id="btnOcultar" class="btn btn-warning">OCULTAR</button> -->
									</div>
								</div><!--  termina div.row de la tabla clientes-->
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
			</body>
</html>

		<!-- inline scripts related to this page -->
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>

			<script type="text/javascript">
			//var coordPoli="<?php //echo $poligono; ?>";
			//var colorPoli="<?php //echo $poligonoC; ?>";
			var colorPoli='';

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


    /*create array:*/
 
       



	 $("#cmbZona1").change(function(event) {
	 	/* Act on the event */

			//var numPoligonos=parseInt($("txtNZona").val());
			var cadena="";

		 	var zona=$("#cmbZona1").val();
		 	$("#txtZona").val($("#cmbZona1").val());

		 	//alert(zona);
		 	//alert($("#txtZona").val());
		 			if(banderaPoligono==0){
		 				banderaPoligono=1;
		 				
		 			}
		 			else{
		 				borrarPoligono();
		 				 //markerDelAgain();
		 				//markerDelAgain();
		 			}

		 	$.post("<?php echo CCATALOGOS('obtenerPoligono2');?>", {zona: $("#txtZona").val()},function(data){  
	                //alert(data);
	                //$("#cmbRutas"+id0).html(data);
	                //myFunction();
	                
	               
	                var cadenaori=data;
	                //alert(cadenaori);
	                var arregloori=cadenaori.split("&");
	                var cantidadarregloori=arregloori.length;
	                
	              //  alert(cantidadarregloori);
	                for (var i = 0; i < cantidadarregloori; i++) {
	                	//alert(i+" "+arregloori[i]);
	                	
	                	cadena=arregloori[i];
	                	//alert(cadena);
		                arreglo=cadena.split("/");
		                colorPoli=arreglo[0];
		                coordenadasPol=arreglo[1];
		                //alert(arreglo[2]);
		                
		                		crearPoligono();
		               
		                
		                //numPoligonos++;
		                //$("#txtNZona").val(numPoligonos);
		                
		               // crearMarcadores(arreglo[2]);
		                 
	                }
	                crearMarcadores($("#txtZona").val());
	              });
		
	 });
	 function crearMarcadores(zona){
	 	//alert("La zona es: "+zona);
			markerDelAgain();
			$.post("<?php echo CCATALOGOS('obtenerMarcadoresZona');?>", {zona: zona, proveedor: $("#txtProveedor").val()},function(data){
				//alert(data);
				 var cadenaori=data;
				 var arregloori1=cadenaori.split("&");
				// alert(arregloori1[1]);
				 	var arregloori=arregloori1[1].split("%");
	                var cantidadarregloori=arregloori.length;
	                 var cantidad=arregloori1[0];
	                 var contador=0;

	              // alert("cantidad del arreglo "+cantidadarregloori);

			                for (var i = 0; i < cantidadarregloori; i++) {
			                	//alert(i+" "+arregloori[i]);
			                	
			                	cadena=arregloori[i];
			                	
				                arreglo=arregloori[i].split("/");
				                nombre=arreglo[0];
				                latitud=arreglo[1];
				                longitud=arreglo[2];
				                codigo=arreglo[3];
				                direccion=arreglo[4];
				               
						            	 var LamMarker = new L.marker([latitud, longitud],{
							            	draggable: false,
							            	icon: L.AwesomeMarkers.icon({icon: 'shopping-basket', prefix: 'fa', markerColor: 'darkgreen', spin:false}) 
							            }).bindPopup("Codigo: <strong>"+codigo+"</strong><br> Razon Social: <strong>"+nombre+"</strong><br> Domicilio: <strong>"+direccion+"</strong>");
						            	 marcadores.addLayer(LamMarker);
						            	 marker.push(LamMarker);
						            	 map.addLayer(marcadores);
						            	 map.fitBounds(marcadores.getBounds());
						            	 contador++;
						            	 $("#cantidadClientes").html('<strong>'+contador+'</strong>');

						            
			                }
	               
	                //itemWrap();
			});
	 }
	 $("#cmbSucursal").change(function(event) {
	 	/* Act on the event */
	 	//alert("hola");
	 	var pro="";
	 	var idSucursal=$("#cmbSucursal").val();
	 		$.post("<?php echo CCATALOGOS('createComboZona2');?>", {sucursal: idSucursal},function(data){  
              // alert(data);
               
               $("#cmbZona1").html(data);
               $("#cmbZona1").change();
               $("#txtZona").val(pro);
              });
	 		$.post("<?php echo CCATALOGOS('createComboProveedores');?>", {sucursal: idSucursal},function(data){  
               //alert(data);
              $("#cmbProveedor").html(data);
			  $("#cmbProveedor").change();
              $("#txtProveedor").val(pro);
              });
	 		$.post("<?php echo CCATALOGOS('createComboAgente');?>", {sucursal: idSucursal},function(data){  
               //alert(data);
              $("#cmbAgente").html(data);
			  $("#cmbAgente").change();
             
              });
	 });
	 $("#cmbProveedor").change(function(event) {
	 	/* Act on the event */
	 	//alert($("#cmbProveedor").val());
	 	var texto=$("#txtProveedor").val();
	 	texto=texto+","+$("#cmbProveedor").val();
	 	$("#txtProveedor").val($("#cmbProveedor").val());
	 	crearMarcadores($("#txtZona").val());

	 	
	 });
	  var geocoder = new google.maps.Geocoder();
	  var conteo=0;

	  $("#txtDomicilio").blur(function(event) {
	  	/* Act on the event */
	  	if(($("#txtDomicilio").val()!="")&&($("#txtCiudad").val()!="")){
	  	var domicilio=$("#txtDomicilio").val()+', '+$("#txtCiudad").val();
	  	//alert(domicilio);
	  	geocoder.geocode({ 'address': domicilio}, function(results, status)
			 {
			 	
			   if (status == 'OK')
			   {
			// Mostramos las coordenadas obtenidas en el p con id coordenadas
			   $("#txtLatitud").val(results[0].geometry.location.lat());
			   $("#txtLongitud").val(results[0].geometry.location.lng());
				 items = [{"lat":results[0].geometry.location.lat(),"lon":results[0].geometry.location.lng(),"pop":$("#txtDomicilio").val()}];
      			 
      			 if(conteo==0){
			 		conteo=1;

			 		itemWrap();
			 	}
			 	else{
			 		
					markerDelAgain();
					itemWrap();
			 	}
			   }
			  });
	  }
	  });
	  	  $("#txtCiudad").blur(function(event) {
	  	/* Act on the event */
	  	if(($("#txtDomicilio").val()!="")&&($("#txtCiudad").val()!="")){
	  	var domicilio=$("#txtDomicilio").val()+', '+$("#txtCiudad").val();
	  	//alert(domicilio);
	  	geocoder.geocode({ 'address': domicilio}, function(results, status)
			 {
			 	
			   if (status == 'OK')
			   {
			// Mostramos las coordenadas obtenidas en el p con id coordenadas
			   $("#txtLatitud").val(results[0].geometry.location.lat());
			   $("#txtLongitud").val(results[0].geometry.location.lng());
				 items = [{"lat":results[0].geometry.location.lat(),"lon":results[0].geometry.location.lng(),"pop":$("#txtDomicilio").val()}];
      			 
      			 if(conteo==0){
			 		conteo=1;

			 		itemWrap();
			 	}
			 	else{
			 		
					markerDelAgain();
					itemWrap();
			 	}
			   }
			  });
	  }
	  });
	  	  $(".checkdias").click(function(event) {
	  	  	/* Act on the event */
	  	  	var id=$(this).attr("id");
	  	  	var valor=$("#chk"+id).prop("checked");
	  	  	//alert(id+" "+valor);
	  	  	if(valor){
	  	  		$("#chk"+id).prop("checked", false);
	  	  		$("#"+id).removeClass("btn btn-primary");
	  	  		$("#"+id).addClass("btn btn-default");
	  	  	}
	  	  	else{
				$("#chk"+id).prop("checked", true);
				$("#"+id).removeClass("btn btn-default");
	  	  		$("#"+id).addClass("btn btn-primary");
	  	  	}

	  	  });
//select2
				$('.select2').css('width','200px').select2({allowClear:false})
				$('#select2-multiple-style .btn').on('click', function(e){
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('.select2').addClass('tag-input-style');
					 else $('.select2').removeClass('tag-input-style');
				});

</script>





		

