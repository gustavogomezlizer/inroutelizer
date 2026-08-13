<?php 
$data['title']="LIZER Agregar Usuario";
$this->load->view("vHead",$data); 

//print_r($poligonoDatos->result());
//$coordenadas='[{"lat":"'.$latitud.'","lon":"'.$longitud.'","pop":"Hola"}]';
$coordenadas='[{"lat":"0","lon":"0","pop":"0"}]';
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
       #mapid { width:100%; height: 600px; }
       .leaflet-control-zoom-to-start {
			background:#fff url(<?php echo RUTAFOLDERASSETS("images/mapas/home.png"); ?>) no-repeat 0 0;
			background-size:26px 26px;
		}

		.leaflet-control-zoom-to-area {
			background:#fff url(<?php echo RUTAFOLDERASSETS("images/mapas/area.png"); ?>) no-repeat 0 0;
			background-size:26px 26px;
		}
		.leaflet-control-zoom-in{
			background:#fff url(<?php echo RUTAFOLDERASSETS("images/mapas/zoom_in.png"); ?>) no-repeat 0 0;
			background-size:26px 26px;
		}
		.leaflet-control-zoom-out{
			background:#fff url(<?php echo RUTAFOLDERASSETS("images/mapas/zoom_out.png"); ?>) no-repeat 0 0;
			background-size:26px 26px;
		}
		.leaflet-container.crosshair-cursor-enabled {
		    cursor:crosshair;
		}

		@media
		(-webkit-min-device-pixel-ratio:2),
		(min-resolution:192dpi) {
		    .leaflet-control-zoom-to-start {
		        background-image:url(<?php echo RUTAFOLDERASSETS("images/mapas/home.png"); ?>);
		    }
		    .leaflet-control-zoom-to-area {
		        background-image:url(<?php echo RUTAFOLDERASSETS("images/mapas/area.png"); ?>);
		    }
		}
   </style>

<?php $this->load->view("vMenu");  ?>
			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								LIZER Sistema de Distribucion
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Catalogos / Clientes Consumidores
									
									
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
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
									<form id="frmDatos" action="<?php echo CCATALOGOS('saveNuevoClienteX'); ?>" method="POST">
										<div class="row">
									<div class="col-sm-6">
										
															<div class="row" align="center">
																<div class="col-xs-12">
																	<h4 class="control-label green">NUEVO CLIENTE</h4>
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
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Cliente: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtNombre" name="txtNombre" class="form-control" value=""/>
																	</div>
																</div>
																<div class="form-group">
																	
																	<label  class="col-sm-offset-4 col-sm-2 no-padding-right blue">  
																		<?php 
																			$status=1;
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
																<!-- <div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Direccion: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtDomicilio" name="txtDomicilio" class="form-control direccion" value=""/>
																	</div>
																</div> -->
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Calle: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtCalle" name="txtCalle" class="form-control direccion" value=""/>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Numero: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtNumero" name="txtNumero" class="form-control direccion" value=""/>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Colonia: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtColonia" name="txtColonia" class="form-control direccion" value=""/>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Ciudad: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtCiudad" name="txtCiudad" class="form-control direccion" value=""/>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> CP: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtCP" name="txtCP" class="form-control" value=""/>
																	</div>
																</div>
																
															<!-- 	<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1">  </label>
																	<label  class="col-sm-2 no-padding-right blue"> Usuario Movil <br>
																		
																		<input id="cmbUMovil" name="cmbUMovil" class="ace ace-switch ace-switch-6" type="checkbox"/>
																		<span class="lbl"></span>
																	</label>
																	<label  class="col-sm-2 no-padding-left blue"> Usuario Prospecto <br>
																		
																		 <input id="cmbUP" name="cmbUP" class="ace ace-switch ace-switch-6" type="checkbox"/>
																		<span class="lbl"></span>
																	</label>
																</div> -->
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Encargado: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtEncargado" name="txtEncargado" class="form-control" value=""/>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Telefono: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtTelefono" name="txtTelefono" class="form-control" value=""/>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Correo: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtCorreo" name="txtCorreo" class="form-control" value=""/>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Comentarios: </label>

																	<div class="col-sm-8">
																		<textarea id="txtComentarios" name="txtComentarios" class="form-control"></textarea>
																		
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Dias Visita: </label>

																	<div class="col-sm-8">
																		<span class="button-checkbox">
																	        <button id="Domingo" name="Domingo" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">D</button>
																	        <input name="chkDomingo" id="chkDomingo" type="checkbox" class="hidden"/>
																	    </span>
																	    <span class="button-checkbox">
																	        <button id="Lunes" name="Lunes" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">L</button>
																	        <input id="chkLunes" name="chkLunes" type="checkbox" class="hidden" />
																	    </span>
																	    <span class="button-checkbox">
																	        <button id="Martes" name="Martes" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">M</button>
																	        <input id="chkMartes" name="chkMartes" type="chkMartes" class="hidden"/>
																	    </span>
																	    <span class="button-checkbox">
																	        <button id="Miercoles" name="Miercoles" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">M</button>
																	        <input id="chkMiercoles" name="chkMiercoles" type="checkbox" class="hidden" />
																	    </span>
																		<span class="button-checkbox">
																	        <button id="Jueves" name="Jueves" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">J</button>
																	        <input id="chkJueves" name="chkJueves" type="checkbox" class="hidden" />
																	    </span>
																	    <span class="button-checkbox">
																	        <button id="Viernes" name="Viernes" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">V</button>
																	        <input id="chkViernes" name="chkViernes" type="checkbox" class="hidden"/>
																	    </span>
																	    <span class="button-checkbox">
																	        <button id="Sabado" name="Sabado" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">S</button>
																	        <input id="chkSabado" name="chkSabado" type="checkbox" class="hidden" />
																	    </span>
																	</div>
																</div>
																
																<div class="form-group">
																<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Sucursal: </label>
																	<div class="col-sm-8">
																		
																		<select id="cmbSucursal" name="cmbSucursal" class="form-control">
																			<option value=0 disabled selected>(Selecciona)</option>
																			<?php 
																				foreach ($listaSucursales->result() as $kSuc) {
																					
																				
																					?>
																					<option value=<?php echo $kSuc->id; ?>><?php echo $kSuc->sucursal; ?></option>
																					<?php 
																					}

																		?>

																		
																		</select> 
																		</div>
															</div>
															<div class="form-group">
																<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Zona: </label>
																	<div class="col-sm-8">
																		
																		

																		
																		<select id="cmbZona" name="cmbZona" class="form-control">
																			<option value=0 disabled selected>(Selecciona)</option>
																			
																		</select>

																		</div>
															</div>
															<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Proveedor: </label>

																	<div class="col-sm-8">
																	<select multiple="" id="cmbProveedor" name="cmbProveedor" class="select2" data-placeholder="Elige opcion">
											
																	</select>
																	<input type="hidden" id="txtProveedor" name="txtProveedor" value="0">

																	
																	</div>
																</div>

															</div>
														</div>
													</div>
													
												
									</div><!-- /.col -->
									<div class="col-sm-6">
										<div id="mapid"> <!-- empieza div que contiene a la tabla -->
														</div>
									</div>

								</div><!-- /.row -->
									<input type="hidden" id="txtLatitud" name="txtLatitud" value="">
																		
									<input type="hidden" id="txtLongitud" name="txtLongitud" value="">
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
						
				/*empieza configuracion para ver mapas*/
				$(".verMapa1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("MAP1","");
					//$("#modalMapa").modal("show");
					var link="<?php echo CCATALOGOS(); ?>" + "verMapaCliente/"+id;
				  	window.open(link,"_blank");
					//alert(id);
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
			//var coordPoli="<?php //echo $poligono; ?>";
			//var colorPoli="<?php //echo $poligonoC; ?>";
			var colorPoli='';
			//var rescoord=coordenadasPoli.split("");

/*	 $("#btnOcultar").click(function(event) {
	    	 Act on the event 
	    	ver=false;
	    	initMap();
	    });*/
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

var banderaMapa=0;
var banderaPoligono=0;
var markers;
var polygon;


    var map = L.map('mapid',{
                                zoomControl: false
                            }).setView([23.242251, -106.442509], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    var zoom_bar = new L.Control.ZoomBar({position: 'topright'}).addTo(map);
    /*create array:*/
    $("#mapid").css("display", "block");
        var marker = new Array();
        var coordenadasPol='';
        //var coordenadas='<?php //echo $coordenadas; ?>';
       // var coordenadasPol='<?php //echo $poligono; ?>';
        //alert(xxx);
        /*Some Coordinates (here simulating somehow json string)*/
       var items = [{"lat":"23.255460","lon":"-106.422843","pop":"Hola"}];
       //var items=eval(coordenadas);
        

        /*pushing items into array each by each and then add markers*/
        function itemWrap() {
        for(i=0;i<items.length;i++){
            var LamMarker = new L.marker([items[i].lat, items[i].lon],{
            	draggable: true,
            	icon: L.AwesomeMarkers.icon({icon: 'spinner', prefix: 'fa', markerColor: 'darkblue', spin:true}) 
            }).bindPopup(items[i].pop);
	        LamMarker.on('dragend', function (e) {
			   
			    $("#txtLatitud").val(LamMarker.getLatLng().lat);
			    $("#txtLongitud").val(LamMarker.getLatLng().lng);

			});
            marker.push(LamMarker);

            map.addLayer(marker[i]);
            centerLeafletMapOnMarker(map, marker[i]);
			
            }
            if (banderaMapa==0){
                /* $("#mapid").css("display", "none");*/
                 banderaMapa=1;
            }

        }
        function centerLeafletMapOnMarker(map, marker) {
		  var latLngs = [ marker.getLatLng() ];
		  var markerBounds = L.latLngBounds(latLngs);
		  map.fitBounds(markerBounds);
		}
        /*Going through these marker-items again removing them*/
        function markerDelAgain() {
        for(i=0;i<marker.length;i++) {
            map.removeLayer(marker[i]);
            }  
        marker.splice(0, marker.length);
        }
//itemWrap();
//crearPoligono();
//alert(colorPoli);

function crearPoligono(){
	//alert("Hola");
	var itemsPol=eval(coordenadasPol);
   // var latlngs = [[23.247616, -106.430303],[23.246463, -106.435667],[23.254103, -106.437330]];
    polygon = L.polygon(itemsPol, {color: colorPoli}).addTo(map);
}
function borrarPoligono(){
    map.removeLayer(polygon);
}
	 $("#cmbZona").change(function(event) {
	 	/* Act on the event */

	 	var zona=$("#cmbZona").val();
	 	//alert($("#cmbZona").val());
	 	$.post("<?php echo CCATALOGOS('obtenerPoligono');?>", {zona: $("#cmbZona").val()},function(data){  
                //alert(data);
                //$("#cmbRutas"+id0).html(data);
                //myFunction();
                var cadena=data;
                var arreglo=cadena.split("/");
                colorPoli=arreglo[0];
                //var coor=arreglo[1];
                coordenadasPol=arreglo[1];
                //alert(coordenadasPol);
	 			//map.removeLayer(polygon);
	 			if(banderaPoligono==0){
	 				banderaPoligono=1;
	 				
	 			}
	 			else{
	 				borrarPoligono();
	 			}
                crearPoligono();                
                //alert(colorPoli);
                //alert(coordPoli);
              });

	 });
	 $("#cmbSucursal").change(function(event) {
	 	/* Act on the event */
	 	//alert("hola");
	 	var pro="";
	 	var idSucursal=$("#cmbSucursal").val();
	 		$.post("<?php echo CCATALOGOS('createComboZona');?>", {sucursal: idSucursal},function(data){  
              // alert(data);
               $("#cmbZona").html(data);
              });
	 		$.post("<?php echo CCATALOGOS('createComboProveedores');?>", {sucursal: idSucursal},function(data){  
               //alert(data);
              $("#cmbProveedor").html(data);

              $("#txtProveedor").val(pro);
              });
	 });
	 $("#cmbProveedor").change(function(event) {
	 	/* Act on the event */
	 	//alert($("#cmbProveedor").val());
	 	var texto=$("#txtProveedor").val();
	 	texto=texto+","+$("#cmbProveedor").val();
	 	$("#txtProveedor").val($("#cmbProveedor").val());
	 });
	  var geocoder = new google.maps.Geocoder();
	  var conteo=0;
	  $(".direccion").blur(function(event) {
	  	/* Act on the event */
	  	if(($("#txtCalle").val()!="")&&($("#txtCiudad").val()!="")&&($("#txtColonia").val()!="")&&($("#txtNumero").val()!="")){
	  		var domicilio=$("#txtCalle").val()+" "+$("#txtNumero").val()+" "+$("#txtColonia").val()+", "+$("#txtCiudad").val();
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
				$('.select2').css('width','200px').select2({allowClear:true})
				$('#select2-multiple-style .btn').on('click', function(e){
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('.select2').addClass('tag-input-style');
					 else $('.select2').removeClass('tag-input-style');
				});

</script>





		

