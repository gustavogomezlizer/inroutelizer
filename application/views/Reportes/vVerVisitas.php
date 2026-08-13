<?php 
$data['title']="LIZER Acciones de Usuario";
$this->load->view("vHead",$data);
$coordenadas='[{"lat":"0","lon":"0","pop":"0"}]';
?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
 
<link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leaflet/leaflet.css"); ?>" />
<link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leafmarkers/leaflet.awesome-markers.css"); ?>" />
<link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leaflet/leaflet.css"); ?>" />
<script src="<?php echo RUTAFOLDERASSETS("leaflet/leaflet.js"); ?>"></script>
<script src="<?php echo RUTAFOLDERASSETS("leafmarkers/leaflet.awesome-markers.min.js"); ?>"></script>
<link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leafletclusters/MarkerCluster.css"); ?>" />
<link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leafletclusters/MarkerCluster.Default.css"); ?>" />
<script src="<?php echo RUTAFOLDERASSETS("leafletclusters/leaflet.markercluster-src.js"); ?>"></script>
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
									Reportes / Acciones de Vendedor
									
									
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
									</div>
									</div>
									<div class="col-xs-12"><br></div>
									<div class="space-40"></div>
									
										
										<div class="col-xs-12">
									
										<div class="row">
									
									<div class="col-sm-12">
										<div id="mapid"> <!-- empieza div que contiene a la tabla -->
														</div>
									</div>

								</div><!-- /.row -->
									
							
										
											
										</div><!-- empieza div que contiene a la tabla -->
									</div><!--  termina div.col-xs-12 de la tabla clientes-->

									<div class="space-40"><br></div>
									<div class="col-md-12 col-xs-12 col-sm-12" align="center"><br>										
									</div>
								</div><!--  termina div.row de la tabla clientes-->
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
var usuario=<?php echo $idUsuario; ?>;
var fechaI='<?php echo $fIni; ?>';
var fechaF='<?php echo $fFin; ?>';


    var map = L.map('mapid',{
                                zoomControl: false
                            }).setView([23.242251, -106.442509], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    /*create array:*/
    var zoom_bar = new L.Control.ZoomBar({position: 'topright'}).addTo(map);
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



	 function crearMarcadores(){
	 	//alert("La zona es: "+zona);
	 	//alert("Hola");
			markerDelAgain();
			$.post("<?php echo CREPORTES('getAcciones');?>", {idUsuario: usuario, fIni: fechaI, fFin: fechaF},function(data){
				//alert(data);
				 var cadenaori=data;
				 var arregloori1=cadenaori.split("&");
				// alert(arregloori1[1]);
				 	var arregloori=arregloori1[1].split("%");
	                var cantidadarregloori=arregloori.length;
	                 var cantidad=arregloori1[0];
	                 var contador=0;

	              // alert("cantidad del arreglo "+cantidadarregloori);
	              			if(cantidad!=0){
			                for (var i = 0; i < cantidadarregloori; i++) {
			                	//alert(i+" "+arregloori[i]);
			                	
			                	cadena=arregloori[i];
			                	
				                arreglo=arregloori[i].split("/");
				                nombre=arreglo[0];
				                latitud=arreglo[1];
				                longitud=arreglo[2];
				                codigo=arreglo[3];
				                direccion=arreglo[4];
				                total=arreglo[5];
				                tipo=arreglo[6];
				                var marcadorcolor="";
				                var marcadoricono="";
				                if(tipo=="PREVENTA"){
				                	marcadorcolor='darkgreen';
				                	marcadoricono='shopping-basket';
				                }
				                if(tipo=="DEVOLUCION"){
				                	marcadorcolor='yellow';
				                	marcadoricono='reply';
				                }
				                if(tipo=="VISITA"){
				                	marcadorcolor='red';
				                	marcadoricono='street-view';
				                }
				               
								var LamMarker = new L.marker([latitud, longitud],{
								draggable: false,
								icon: L.AwesomeMarkers.icon({icon: marcadoricono, prefix: 'fa', markerColor: marcadorcolor, spin:false}) 
								}).bindPopup("Codigo: <strong>"+codigo+"</strong><br> Razon Social: <strong>"+nombre+"</strong><br> Domicilio: <strong>"+direccion+"</strong><br>Total: <strong>"+total+"</strong>");
								marcadores.addLayer(LamMarker);
								marker.push(LamMarker);
								map.addLayer(marcadores);
								map.fitBounds(marcadores.getBounds());
								contador++;
								//$("#cantidadClientes").html('<strong>'+contador+'</strong>'); 								
			                }
			            }
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
		window.onload=function()
		{

			
	                crearMarcadores();
					//map.setView([latitud, longitud], 12);
		}
</script>





		

