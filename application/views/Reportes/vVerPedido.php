<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data);

function Get_Address_From_Google_Maps($lat, $lon)
{
	$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=false&key=AIzaSyCpk9wSp_vMX3xjwunB-wzp-HMxuyKj6d8";
	$data = @file_get_contents($url);
	$jsondata = json_decode($data,true);

	if(count($jsondata["results"]) > 0)
	{
		return $jsondata["results"][0]["formatted_address"];
	}
	else
	{
		return "SIN INFORMACION";
	}
}

$cadenadireccion = Get_Address_From_Google_Maps($datos_pedido->row()->latitud, $datos_pedido->row()->longitud);//$datosCliente->row()->calle." ".$datosCliente->row()->numero." ".$datosCliente->row()->colonia.", ".$datosCliente->row()->ciudad;
$coordenadas='[{"lat":"'.$datos_pedido->row()->latitud.'","lon":"'.$datos_pedido->row()->longitud.'","pop":"'.$cadenadireccion.'"}]';

if($poligonoDatos->num_rows()!=0)
{
	foreach ($poligonoDatos->result() as $k) 
	{
		# code...
		$poligono=$k->coordenadas;
		//echo "<br>".$poligono;
		$poligonoC=$k->color; 
	}
}
else{
	$poligono="";
	$poligonoC="";
}
?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
 
<link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leaflet/leaflet.css"); ?>" />
<link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leafmarkers/leaflet.awesome-markers.css"); ?>" />
<link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leaflet/leaflet.css"); ?>" />
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
									
									
										
										
									
									<input type="hidden" name="txtID" value="<?php echo $idcliente; ?>">
										<div class="row">
									<div class="col-sm-6">
										
															<div class="row" align="center">
																<div class="col-xs-12">
																	<h4 class="control-label green">VER DATOS</h4>
																</div>
															</div>
															<div class="space-40"><br></div>
											
													<div class="row">
														<div class="col-xs-12">
															<div class="form-horizontal" role="form">
																<div class="form-group row">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Folio de Pedido: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtCodigo" name="txtCodigo" class="form-control" value="<?php echo ($datos_pedido->num_rows() > 0) ? $datos_pedido->row()->folio : ""; ?>" readonly/>
																	</div>
																</div> 
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Cliente: </label>

																	<div class="col-sm-8">
																		<input type="text" id="txtDescripcion" name="txtDescripcion" class="form-control" value="<?php echo ($datos_pedido->num_rows() == 0) ? "" : $datos_pedido->row()->cliente; ?>" readonly/>
																	</div>
																</div>																
																<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Domicilio: </label>

																	<div class="col-sm-8">
																		
																		<input type="text" id="txtCalle" name="txtCalle" class="form-control" direccion value="<?php echo $datosCliente->row()->direccion." ".$datosCliente->row()->numero.",".$datosCliente->row()->colonia; ?>" readonly/>
																	</div>
																</div>
																
																
																
																<div class="form-group">
																<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Sucursal: </label>
																	<div class="col-sm-8">
																		<input type="text" id="cmbSucursal" name="cmbSucursal" class="form-control" value="<?php echo ($datos_pedido->num_rows() == 0) ? "" : $datos_pedido->row()->sucursal; ?>" readonly/>
																		</div>
															</div>
															<div class="form-group">
																<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Vendedor: </label>
																	<div class="col-sm-8">
																		<input type="text" id="cmbSucursal" name="cmbSucursal" class="form-control" value="<?php echo ($datos_pedido->num_rows() == 0) ? "" : GETUSUARIOBYID($datos_pedido->row()->idusuario)->nombre; ?>" readonly/>
																		</div>
															</div>
															<div class="form-group">
																<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Fecha: </label>
																	<div class="col-sm-8">
																		<input type="text" id="cmbSucursal" name="cmbSucursal" class="form-control" value="<?php echo ($datos_pedido->num_rows() == 0) ? "" : FORMATO_FECHA($datos_pedido->row()->fecha); ?>" readonly/>
																		</div>
															</div>
															<div class="form-group">
																	<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Importe: </label>
																		<div class="col-sm-8">
																			<input type="text" id="txtImporte" name="txtImporte" class="form-control" value="<?php echo ($datos_pedido->num_rows() > 0) ? FORMATO_DINERO($datos_pedido->row()->total) : ""; ?>" readonly/>
																			</div>
																</div>
																<div class="form-group">
																<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Tipo: </label>
																	<div class="col-sm-8">
																		<input type="text" id="cmbSucursal" name="cmbSucursal" class="form-control" value="<?php echo ($datos_pedido->num_rows() > 0) ? $datos_pedido->row()->tipo : ""; ?>" readonly/>
																		</div>
															</div>
															<div class="form-group">
																
																	<div class="col-sm-12 table-responsive">
																			<table id="dynamic-table" class="table table-striped table-bordered table-hover">
																				<thead>
																					<tr>
																						<th>Cantidad</th>
																						<th>Codigo</th>
																						<th>Producto</th>
																						<th>Precio</th>
																						<th>Importe</th>
																						<th>Proveedor</th>
																					</tr>
																				</thead>
																				<tbody>
																					<?php 
																					$grantotal = 0;
																					foreach ($datosPedido->result() as $kDP) { $grantotal = $grantotal + $kDP->importe_real;
																						?>
																						<tr>
																							<td><?php echo $kDP->cantidad_real; ?></td>
																							<td><?php echo $kDP->codigoproducto; ?></td>
																							<td><?php echo $kDP->producto; ?></td>
																							<td><?php echo FORMATO_DINERO($kDP->precio); ?></td>
																							<td><?php echo FORMATO_DINERO($kDP->importe_real); ?></td>
																							<td><?php echo $kDP->nombreProveedor; ?></td>
																						</tr>
																						<?php } ?>
																				</tbody>
																			</table>
																		</div>
															</div>
															

															</div>
														</div>
													</div>
													
												
									</div><!-- /.col -->
									<div class="col-md-6">
										<div id="mapid"> <!-- empieza div que contiene a la tabla -->
														</div>
									</div>

								</div><!-- /.row -->
									<input type="hidden" id="txtLatitud" name="txtLatitud" value="<?php echo $latitud; ?>">
																		
																		<input type="hidden" id="txtLongitud" name="txtLongitud" value="<?php echo $longitud; ?>">
								
										
											
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

				$("#txtImporte").val("<?php echo FORMATO_DINERO($grantotal); ?>");
				
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
   // $("#mapid").css("display", "block");
        var marker = new Array();
        var coordenadasPol='';
        var coordenadas='<?php echo $coordenadas; ?>';
       
       var coordenadasPol='<?php echo $poligono; ?>';
        //alert(xxx);
        /*Some Coordinates (here simulating somehow json string)*/
       //var items = [{"lat":"23.255460","lon":"-106.422843","pop":"Hola"}];
       var items=eval(coordenadas);
        

        /*pushing items into array each by each and then add markers*/
        function itemWrap() {
        for(i=0;i<items.length;i++){
            var LamMarker = new L.marker([items[i].lat, items[i].lon],{
            	draggable: false,
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
itemWrap();
if(coordenadasPol!=''){
	crearPoligono();
}
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

	 				borrarPoligono();
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
              $("#divProveedor").empty();
              $("#divProveedor").html('<select multiple="" id="cmbProveedor" name="cmbProveedor" class="select2" data-placeholder="Elige opcion"></select>');
              $('.select2').css('width','200px').select2({allowClear:true});
              $("#cmbProveedor").change(function(event) {
				 	/* Act on the event */
				 	//alert($("#cmbProveedor").val());
				 	var texto=$("#txtProveedor").val();
				 	texto=texto+","+$("#cmbProveedor").val();
				 	$("#txtProveedor").val($("#cmbProveedor").val());
				 	//alert($("#txtProveedor").val());
				 });
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
//select2
				$('.select2').css('width','200px').select2({allowClear:true});
				$('#select2-multiple-style .btn').on('click', function(e){
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());

					if(which == 2) $('.select2').addClass('tag-input-style');
					 else $('.select2').removeClass('tag-input-style');
				});

</script>





		

