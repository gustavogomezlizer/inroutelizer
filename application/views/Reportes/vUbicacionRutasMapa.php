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

<link rel="stylesheet" href="<?php echo RUTAFOLDERASSETS("leafmarkers/leaflet-text-icon.css"); ?>" />
<script src="<?php echo RUTAFOLDERASSETS("leafmarkers/leaflet-text-icon.js"); ?>"></script>

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

		.leaflet-marker-hover {
  transition-property: opacity;
  transition-duration: 0.5s;
  opacity: 1;
  color: #fff;
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

		.leaflet-marker-icon .number{
			position: relative;
			top: -50px;
			font-size: 18px;
			width: 20px;
			text-align: center;
			color: black;
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
									Reportes / Ubicacion Rutas En Mapa
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">

							<div class="col-md-12">

								<!--<div class="col-md-2">
									<label for="txtFecha">Fecha</label>
									<input id="txtFecha" type="date" class="form-control" />
								</div>-->

								<div class="col-md-2">
									<label for="cmbSucursal">Sucursal</label>
									<select id="cmbSucursal" name="sucursal" class="form-control">
										<?php if(ISMULTISUCURSAL()) { ?>
											<?php foreach (GETLISTASUCURSALES() as $item) { ?>
												<option value="<?php echo $item->id; ?>" <?php echo (GETSUCURSAL()==$item->id) ? 'selected' : '' ?>  ><?php echo $item->sucursal; ?></option>
											<?php } ?>
										<?php } else { ?>
											<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
											<?php } ?>
										<?php } ?>
									</select> 
								</div>

								<div class="clearfix col-md-2">
									<br/>
									<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
								</div>

								<br/><br/><br/><br/>								
										
								<div class="col-md-12">

									<label id="lblTotalClientes" style="margin-right: 10px;"></label>
									<button id="lblTotalClientesSeleccionados" type="button" class="btn btn-link"></button>

									<div class="row">									
										<div class="col-md-12">
											<div id="mapid"></div>
										</div>
									</div>
								</div><!-- empieza div que contiene a la tabla -->
							</div><!--  termina div.col-xs-12 de la tabla clientes-->
						</div>

						<div class="space-40"><br></div>

						<div class="col-md-12 col-xs-12 col-sm-12" align="center"><br></div>

					</div><!--  termina div.row de la tabla clientes-->
				</div><!-- /.col -->
			</div><!-- /.row -->

</body>
</html>

<?php $this->load->view("vCopyright"); ?>
<?php $this->load->view("vFooter"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

<script type="text/javascript">

	/*var lunesIcon = new L.Icon({
		iconUrl: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|E85141&chf=a,s,ee00FFFF',
	});*/

	var redIcon = new L.NumberedDivIcon({
		iconUrl: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
	});

	var iconSelected = new L.NumberedDivIcon({
		iconUrl: 'https://maps.google.com/mapfiles/ms/icons/purple-dot.png'
	});

	var map = L.map('mapid');

	var clientes_seleccionados = 0;
	var codigos_seleccionados = "";

	mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';

	L.tileLayer(
		'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; ' + mapLink + ' Contributors',
		maxZoom: 18,
	}).addTo(map);

	window.onload = function()
	{
		$("#cmbSucursal").trigger("change");
	}

	/*$("#cmbSucursal").on("change", function(){
		if($(this).val()==null){			
			$("#cmbRuta").html("");			
			return;
		}

		var idSucursal = $(this).val().toString();

		$.post("<?php echo CCATALOGOS('createComboRutasUsuariosRuta');?>", {sucursal: idSucursal},function(data){
			$("#cmbRuta").html(data);
		});
	});*/

	$("#btnAplicar").on("click", function(){

		var idsucursal = $("#cmbSucursal").val();
		//var idruta = $("#cmbRuta").val();
		//var fecha = $("#txtFecha").val();

		/*if(fecha == "")
		{
			dialogAvisoGlobal.show("Favor de seleccionar una fecha", "alert alert-warning");
			return;
		}*/

		if(idsucursal == 0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una sucursal", "alert alert-warning");
			return;
		}

		$.post("<?php echo CREPORTES('listadoUbicacionRutasJson');?>", {idsucursal: idsucursal},function(data){
			var datos = JSON.parse(data);

			if(datos.length > 0)
			{
				map.off();
				map.remove();
				map = L.map('mapid',{zoomControl: false}).setView([datos[0].latitud, datos[0].longitud], 12);
				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
				}).addTo(map);
				zoom_bar = new L.Control.ZoomBar({position: 'topright'}).addTo(map);

				for(var x in datos)
				{
					iconColor = redIcon;

					iconColor.options.number = datos[x].ruta_nombre;

					marker = new L.marker([datos[x].latitud, datos[x].longitud], {icon: iconColor})
					.bindPopup(
						datos[x].cliente + "<br/><br/>" + datos[x].tipo + "<br/><br/>" + datos[x].fechacreacion
					)
					.addTo(map);

					marker.on("popupopen", function(e){

						var title = e.target.options.title;

						if(title == "")
						{
							//e.target.options.title = datos[x].codigocliente;
							//e.target.setIcon(iconSelected);
						}
						else
						{
						}
					});
				}
			}
			else
			{
				dialogAvisoGlobal.show("No se encontró información con los parametros seleccionados", "alert alert-warning");
			}
		});
	});

</script>