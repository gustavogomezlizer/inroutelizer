<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); 
//print_r($poligonoDatos->result());
//$cadenadireccion=$datosCliente->row()->calle." ".$datosCliente->row()->numero." ".$datosCliente->row()->colonia.", ".$datosCliente->row()->ciudad;
//$coordenadas='[{"lat":"'.$datos->row()->latitud.'","lon":"'.$datos->row()->longitud.'","pop":"'.$cadenadireccion.'"}]';
//$coordenadas='[{"lat":"0","lon":"0","pop":"0"}]';
//echo GETNEWCLIENTENAME(1);
//echo $coordenadas;
//print_r($poligonoDatos->result());
//echo $datos->row()->latitud." - ".$datos->row()->longitud;
		
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
			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Reportes / Ver Pedido
									
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
									<div class="col-md-12">
										<div id="mapid"> <!-- empieza div que contiene a la tabla -->
														</div>
									</div>

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
						
				/*empieza configuracion para ver mapas*/
				
				
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
			var coordPoli="<?php //echo $poligono; ?>";
			var colorPoli="<?php //echo $poligonoC; ?>";
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

var banderaMapa=0;
var banderaPoligono=0;
var markers;
var polygon;
//var marcadores=L.markerClusterGroup();
var coordenadasPol='';
var items;


    var map = L.map('mapid',{
                                zoomControl: false
                            }).setView([23.242251, -106.442509], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    var marcadores=L.markerClusterGroup();
     var zoom_bar = new L.Control.ZoomBar({position: 'topright'}).addTo(map);
    /*create array:*/
   // $("#mapid").css("display", "block");
        var marker = new Array();
        var coordenadasPol='';
        var coordenadas='';
  		     
        /*pushing items into array each by each and then add markers*/
        
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
function agregarMarcadores(){
	var acciones='<?php echo $acciones; ?>';

	var cadenaori=acciones;
				 var arregloori1=cadenaori.split("&");
				//alert(arregloori1[1]);
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
				                alert(nombre+" "+latitud+" "+longitud+" "+codigo+" "+direccion);
				               
						            	 var LamMarker = new L.marker([latitud, longitud],{
							            	draggable: false,
							            	icon: L.AwesomeMarkers.icon({icon: 'shopping-basket', prefix: 'fa', markerColor: 'darkgreen', spin:false}) 
							            }).bindPopup("Codigo: <strong>"+codigo+"</strong><br> Razon Social: <strong>"+nombre+"</strong><br> Domicilio: <strong>"+direccion+"</strong>");
						            	 marcadores.addLayer(LamMarker);
						            	 marker.push(LamMarker);
						            	 map.addLayer(marcadores);
						            	 map.fitBounds(marcadores.getBounds());
						            	 contador++;
						            	 //$("#cantidadClientes").html('<strong>'+contador+'</strong>');

						            
			                }
	     
}
	 

	 
agregarMarcadores();
//select2
				$('.select2').css('width','200px').select2({allowClear:true});
				$('#select2-multiple-style .btn').on('click', function(e){
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());

					if(which == 2) $('.select2').addClass('tag-input-style');
					 else $('.select2').removeClass('tag-input-style');
				});

</script>





		

