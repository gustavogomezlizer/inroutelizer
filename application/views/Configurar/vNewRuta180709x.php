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
<!-- <script src="<?php echo RUTAFOLDERASSETS("leafletclusters/PruneCluster.js"); ?>"></script> -->
<link rel="stylesheet" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.Default.css" />
<script src="http://leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>

 <style>
       #mapid { width:100%; height: 600px; }
   </style>

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
									<form id="frmDatos" action="<?php echo CCATALOGOS('saveNuevaRuta'); ?>" method="POST">
										<div class="row">
									<div class="col-sm-6">
										
															<div class="row" align="center">
																<div class="col-xs-12">
																	<h4 class="control-label green">NUEVA RUTA</h4>
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
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Ruta: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtRuta" name="txtRuta" class="form-control" value=""/>
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
																
																
															
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Comentarios: </label>

																	<div class="col-sm-8">
																		<textarea id="txtComentarios" name="txtComentarios" class="form-control"></textarea>
																		
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
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Proveedor: </label>

																	<div class="col-sm-8">
																	<select multiple="" id="cmbProveedor" name="cmbProveedor" class="select2" data-placeholder="Elige opcion">
											
																	</select>
																	<input type="hidden" id="txtProveedor" name="txtProveedor" value="0">

																	
																	</div>
																</div>
															<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Zona: </label>

																	<div class="col-sm-8">
																	<select multiple="" id="cmbZona1" name="cmbZona1" class="select2 zona1" data-placeholder="Elige opcion">
											
																	</select>
																	<input type="hidden" id="txtZona" name="txtZona" value="0">
																	

																	
																	</div>
																</div>
																<div class="form-group">
																		<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Agente: </label>
																			<div class="col-sm-8">
																				
																				<select id="cmbAgente" name="cmbAgente" class="form-control">
																				
																				
																				
																				</select> 
																				</div>
																	</div>
															
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Cantidad de Clientes: </label>
																	<div class="col-sm-8">
																		<h5 id="cantidadClientes"></h5>
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
//var numPoligonos=0;
var markers;
var polygon;


    var map = L.map('mapid').setView([23.242251, -106.442509], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    /*create array:*/
    $("#mapid").css("display", "block");
        var marker = new Array();
        var poligonos= new Array();
        var marcadores=L.markerClusterGroup();
        //var assetLayerGroup = new L.LayerGroup();
        var coordenadasPol='';

        //var coordenadas='<?php //echo $coordenadas; ?>';
       // var coordenadasPol='<?php //echo $poligono; ?>';
        //alert(xxx);
        /*Some Coordinates (here simulating somehow json string)*/
       var items;
       //var items=eval(coordenadas);
        

        /*pushing items into array each by each and then add markers*/
        function itemWrap() {
        	items=eval(items);
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
            //centerLeafletMapOnMarker(map, marker[i]);
			
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
            marcadores.removeLayer(marker[i]);
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
    polygon = new L.polygon(itemsPol, {color: colorPoli}).addTo(map);
    poligonos.push(polygon);
   
    //assetLayerGroup.addLayer(polygon);
}
function borrarPoligono(){
	for (var i=0; i < poligonos.length; i++) {
		map.removeLayer(poligonos[i]);
	}
    poligonos.splice(0,poligonos.length);
   
}
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
		 				 markerDelAgain();
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

			$.post("<?php echo CCATALOGOS('obtenerMarcadoresZona');?>", {zona: zona, proveedor: $("#txtProveedor").val()},function(data){
				//alert(data);
				 var cadenaori=data;
				 var arregloori1=cadenaori.split("&");
				// alert(arregloori1[1]);
				 	var arregloori=arregloori1[1].split("%");
	                var cantidadarregloori=arregloori.length;
	                  cantidad=arregloori1[0];
	              // alert("cantidad del arreglo "+cantidadarregloori);

			                for (var i = 0; i < cantidadarregloori; i++) {
			                	//alert(i+" "+arregloori[i]);
			                	
			                	cadena=arregloori[i];
			                	
				                arreglo=arregloori[i].split("/");
				                nombre=arreglo[0];
				                latitud=arreglo[1];
				                longitud=arreglo[2];
				                //alert(nombre+" "+latitud+" "+longitud);
			                	//items = [{"lat":latitud,"lon":longitud,"pop":nombre}];
			                	
				            	 var LamMarker = new L.marker([latitud, longitud],{
					            	draggable: false,
					            	icon: L.AwesomeMarkers.icon({icon: 'shopping-basket', prefix: 'fa', markerColor: 'darkgreen', spin:false}) 
					            }).bindPopup(nombre);
				            	 marcadores.addLayer(LamMarker);
				            	 marker.push(LamMarker);
				            	 map.addLayer(marcadores);
				            	 map.fitBounds(marcadores.getBounds());
				            	 $("#cantidadClientes").html('<strong>'+cantidad+'</strong>');
		            			
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
	 	if(banderaPoligono==0){
		 				banderaPoligono=1;
		 				
		 			}
		 			else{
		 				borrarPoligono();
		 				 markerDelAgain();
		 				//markerDelAgain();
		 			}
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





		

