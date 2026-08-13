<?php 
$data['title']="LIZER Acciones de Usuario";
$this->load->view("vHead",$data);
$coordenadas='[{"lat":"0","lon":"0","pop":"0"}]';
$editar=VERIFICARPERFILFUNCION("Catalogos","editarClientes",$this->session->userdata('perfil'));
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
   </style>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Clientes / Mapa
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">

							<div class="col-md-12">

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

								<div class="col-md-4">
									<label for="cmbZona">Zona</label>
									<select id="cmbZona" name="zona" multiple class="form-control">
										<!--<option value=0 selected disabled>SELECCIONE UNA ZONA</option>-->
									</select>
								</div>

								<div class="col-md-4">
									<label for="cmbDia">Dia</label>
									<select id="cmbDia" name="dia" multiple class="form-control">
										<!--<option value=0 selected disabled>SELECCIONE UN DIA</option>-->
										<option value=2>LUNES</option>
										<option value=3>MARTES</option>
										<option value=4>MIERCOLES</option>
										<option value=5>JUEVES</option>
										<option value=6>VIERNES</option>
										<option value=7>SABADO</option>
										<option value=1>DOMINGO</option>
									</select>
								</div>

								<div class="clearfix col-md-2">
									<br/>
									<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
								</div>

								<div class="col-md-2">
									<table>
										<tr>
											<td style="background: #E85141;">&nbsp;</td>
											<td>LUNES</td>
										</tr>
										<tr>
											<td style="background: #3352FF;">&nbsp;</td>
											<td>MARTES</td>
										</tr>
										<tr>
											<td style="background: #DBF13C;">&nbsp;</td>
											<td>MIERCOLES</td>
										</tr>
										<tr>
											<td style="background: #37C127;">&nbsp;</td>
											<td>JUEVES</td>
										</tr>
										<tr>
											<td style="background: #4C4C4C;">&nbsp;</td>
											<td>VIERNES</td>
										</tr>
										<tr>
											<td style="background: #EE3CF1;">&nbsp;</td>
											<td>SABADO</td>
										</tr>
										<tr>
											<td style="background: #8A2ADF;">&nbsp;</td>
											<td>DOMINGO</td>
										</tr>
									</table>
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


			<div id="modal_editar_cliente" class="modal fade" role="dialog">
				<div class="modal-dialog modal-lg">					

					<div class="modal-content">
					
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Editar Clientes</h4>
						</div>

						<div class="modal-body">
							<div class="row">

								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Dias Visita <small>(Obligatorio)</small>: </label>
									<input name="diasvisita" id="txtDiasVisita" type="hidden"/>

									<div class="col-sm-8">
										<span class="button-checkbox">
											<button id="Domingo" name="Domingo" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">D</button>
											<input id="chkDomingo" type="checkbox" class="hidden"/>
										</span>
										<span class="button-checkbox">
											<button id="Lunes" name="Lunes" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">L</button>
											<input id="chkLunes"  type="checkbox" class="hidden" />
										</span>
										<span class="button-checkbox">
											<button id="Martes" name="Martes" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">M</button>
											<input id="chkMartes" type="chkMartes" class="hidden"/>
										</span>
										<span class="button-checkbox">
											<button id="Miercoles" name="Miercoles" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">M</button>
											<input id="chkMiercoles" type="checkbox" class="hidden" />
										</span>
										<span class="button-checkbox">
											<button id="Jueves" name="Jueves" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">J</button>
											<input id="chkJueves" type="checkbox" class="hidden" />
										</span>
										<span class="button-checkbox">
											<button id="Viernes" name="Viernes" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">V</button>
											<input id="chkViernes" type="checkbox" class="hidden"/>
										</span>
										<span class="button-checkbox">
											<button id="Sabado" name="Sabado" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">S</button>
											<input id="chkSabado" type="checkbox" class="hidden" />
										</span>
									</div><br/><br/>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Zona <small>(Obligatorio)</small>: </label>
									<div class="col-sm-8">
										<select id="cmbZona2" name="zona" class="form-control obligatorio">
											<option value=0 selected>(Selecciona)</option>
										</select>
									</div>
								</div>

							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
							<button id="btnGuardarCliente" type="button" class="btn btn-success">GUARDAR</button>
						</div>

					</div>

				</div>
			</div>

</body>
</html>

<?php $this->load->view("vCopyright"); ?>
<?php $this->load->view("vFooter"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

<script type="text/javascript">

	$(function () {
		$("#dialog-page").dialog("destroy");
	});

	/*var lunesIcon = new L.Icon({
		iconUrl: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|E85141&chf=a,s,ee00FFFF',
	});*/

	var lunesIcon = new L.NumberedDivIcon({
		iconUrl: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
	});

	var martesIcon = new L.NumberedDivIcon({
		iconUrl: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
	});

	var miercolesIcon = new L.NumberedDivIcon({
		iconUrl: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
	});

	var juevesIcon = new L.NumberedDivIcon({
		iconUrl: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
	});

	var viernesIcon = new L.NumberedDivIcon({
		iconUrl: 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png'
	});

	var sabadoIcon = new L.NumberedDivIcon({
		iconUrl: 'https://maps.google.com/mapfiles/ms/icons/pink-dot.png'
	});

	var domingoIcon = new L.NumberedDivIcon({
		iconUrl: 'https://maps.google.com/mapfiles/ms/icons/purple-dot.png'
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
		$('#cmbDia').select2({closeOnSelect:false});
		$('#cmbZona').select2({closeOnSelect:false});
	}

	<?php if($editar == 0) { ?>
		$("#lblTotalClientesSeleccionados").hide();
	<?php } ?>

	$("#cmbSucursal").on("change", function()
	{
		if($(this).val()==null){
			$("#cmbZona").html("");			
			return;
		}

		$.post("<?php echo CCATALOGOS('createComboZonas');?>", {sucursal: $(this).val()},function(data){
			$("#cmbZona").html(data);
			$('#cmbZona option').filter('[value="0"]').remove();

			$("#cmbZona2").html(data);
			$('#cmbZona2 option').filter('[value="0"]').remove();
		});
	});

	$("#btnAplicar").on("click", function(){
		//var zona = $("#cmbZona").val();
		//var diavisita = $("#cmbDia").val();

		var diasseleccionados = $('#cmbDia').select2('data');
		var zonasseleccionados = $('#cmbZona').select2('data');

		if(zonasseleccionados.length == 0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una zona", "alert alert-warning");
			return;
		}

		if(diasseleccionados.length == 0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar un dia de visita", "alert alert-warning");
			return;
		}

		var dias = "";
		var zonas = "";

		for(var x in diasseleccionados)
		{
			dias = dias + diasseleccionados[x].id + ",";
		}

		for(var x in zonasseleccionados)
		{
			zonas = zonas + zonasseleccionados[x].id + ",";
		}

		dias = dias.slice(0, -1);
		zonas = zonas.slice(0, -1)

		$.post("<?php echo CCATALOGOS('getListaClientesJsonByZonaDia');?>", {zona: zonas, diavisita: dias},function(data){
			var datos = JSON.parse(data);

			if(datos.length > 0)
			{
				$("#lblTotalClientes").text("Total Clientes: " + datos.length).css("color", "green");

				map.off();
				map.remove();
				map = L.map('mapid',{zoomControl: false}).setView([datos[0].latitud, datos[0].longitud], 12);
				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
				}).addTo(map);
				zoom_bar = new L.Control.ZoomBar({position: 'topright'}).addTo(map);

				for(var x in datos)
				{
					if(datos[x].diasvisita == 1)
					{
						iconColor = domingoIcon;
					}
					else if(datos[x].diasvisita == 2)
					{
						iconColor = lunesIcon;
					}
					else if(datos[x].diasvisita == 3)
					{
						iconColor = martesIcon;
					}
					else if(datos[x].diasvisita == 4)
					{
						iconColor = miercolesIcon;
					}
					else if(datos[x].diasvisita == 5)
					{
						iconColor = juevesIcon;
					}
					else if(datos[x].diasvisita == 6)
					{
						iconColor = viernesIcon;
					}
					else if(datos[x].diasvisita == 7)
					{
						iconColor = sabadoIcon;
					}

					iconColor.options.number = datos[x].zona_nombre.slice(-2);

					marker = new L.marker([datos[x].latitud, datos[x].longitud], {icon: iconColor})
					.bindPopup(
						"<a onclick='showDialog(" + datos[x].id + ")'>" + datos[x].codigo + "</a><br/>" +
						datos[x].nombre						
					)
					.addTo(map);
					marker.bindTooltip(datos[x].codigo);

					marker.on("popupopen", function(e){

						var title = e.target.options.title;

						var codigoa = this._tooltip._content;

						clientes_seleccionados++;
						//e.target.options.title = datos[x].codigo;
						e.target.setIcon(iconSelected);

						codigos_seleccionados = codigos_seleccionados + codigoa + "|";

						$("#lblTotalClientesSeleccionados").text("Clientes Seleccionados: " + clientes_seleccionados).css("color", "green");

						if(title == "")
						{
							
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

	$("#lblTotalClientesSeleccionados").on("click", function(){
		$("#modal_editar_cliente").modal("show");
	});

	$(".checkdias").on("click", function() {
		var id = $(this).attr("id");
		var valor = $("#chk"+id).prop("checked");		
		if(valor){
			//validarDias=validarDias-1;
			$("#chk"+id).prop("checked", false);
			$("#"+id).removeClass("btn btn-primary");
			$("#"+id).addClass("btn btn-default");			
		}
		else
		{
			$("#chk"+id).prop("checked", true);
			//validarDias = validarDias+1;
			$("#"+id).removeClass("btn btn-default");
			$("#"+id).addClass("btn btn-primary");
		}
	});

	$("#btnGuardarCliente").on("click", function(){
		var dias = diasVisita();
		var zona = $("#cmbZona2").val();
		var idsucursal = $("#cmbSucursal").val();

		codigos_seleccionados = codigos_seleccionados.substring(0, codigos_seleccionados.length - 1);

		$.post("<?php echo LINKPROYECTO('Catalogos/editClienteDiaZona') ?>", {codigos: codigos_seleccionados, idzona: zona, dias: dias, idsucursal: idsucursal}, function(data){
			window.location.reload();
		});
	});

	function showDialog(pId)
	{
		javascript:window.open('<?php echo LINKPROYECTO('EditarCliente/'); ?>' + pId, 'Window','width=800,height=900,menubar=no');
	}

	function diasVisita()
	{
		var dias = "";
		dias = dias + ($("#chkDomingo").prop('checked') ? "1," : "");
		dias = dias + ($("#chkLunes").prop('checked') ? "2," : "");
		dias = dias + ($("#chkMartes").prop('checked') ? "3," : "");
		dias = dias + ($("#chkMiercoles").prop('checked') ? "4," : "");
		dias = dias + ($("#chkJueves").prop('checked') ? "5," : "");
		dias = dias + ($("#chkViernes").prop('checked') ? "6," : "");
		dias = dias + ($("#chkSabado").prop('checked') ? "7," : "");

		if(dias.length>0)
		{
			dias = dias.substring(0, dias.length - 1);
		}

		return dias;
	}

</script>