<?php 
$data['title']="LIZER Agregar Usuario";
$this->load->view("vHead",$data); 
$coordenadas='[{"lat":"0","lon":"0","pop":"0"}]';
$empresa = GETEMPRESA();
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
						<i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Clientes Consumidores
					</small>
				</h1>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-md-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
							<div class="col-md-12" align="right">
								<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
								<a href="<?php echo CCATALOGOS('listaClientes') ?>" class="btn btn-danger">REGRESAR</a>								
							</div>
						</div>
										
						<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										
										<div class="row" align="center">
											<div class="col-md-12">
												<h4 id="titlepage" class="control-label green">NUEVO CLIENTE</h4>
											</div>
										</div>
										
											
										<div class="row">
											<div class="col-md-12">

												<input id="txtId" type="hidden" value="0" name="id" />

													<div class="form-group col-md-12">
														<label for="txtNombre" class="control-label blue">*Nombre del Negocio:</label>
														<input type="text" id="txtNombre" name="nombre" class="form-control" />
													</div>

													<div class="form-group col-md-6">
														<label for="txtEncargado" class="control-label blue">*Nombre Dueño:</label>
														<input type="text" id="txtEncargado" name="encargado" class="form-control" />
													</div>

													<div class="form-group col-md-6">
														<label for="txtEncargadoApellidos" class="control-label blue">*Apellidos Dueño:</label>
														<input type="text" id="txtEncargadoApellidos" name="encargadoapellidos" class="form-control" />
													</div>

													<div class="form-group col-md-6">
														<label for="txtTelefono" class="control-label blue">*Telefono:</label>
														<input type="text" id="txtTelefono" name="telefono" class="form-control" />
													</div>

													<div class="form-group col-md-6">
														<label for="txtCorreo" class="control-label blue">*Correo:</label>
														<input type="text" id="txtCorreo" name="email" class="form-control" />
													</div>

													<div class="form-group col-md-10">
														<label for="txtCalle" class="control-label blue">*Calle:</label>
														<input type="text" id="txtCalle" name="calle" class="form-control" />
													</div>

													<div class="form-group col-md-2">
														<label for="txtNumero" class="control-label blue">*Numero:</label>
														<input type="text" id="txtNumero" name="numero" class="form-control" />
													</div>

													<div class="form-group col-md-4">
														<label for="txtColonia" class="control-label blue">*Colonia:</label>
														<input type="text" id="txtColonia" name="colonia" class="form-control" />
													</div>

													<div class="form-group col-md-2">
														<label for="txtCp" class="control-label blue">*CP:</label>
														<input type="text" id="txtCp" name="cp" class="form-control" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtCiudad" class="control-label blue">*Ciudad:</label>
														<input type="text" id="txtCiudad" name="ciudad" class="form-control" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtEstado" class="control-label blue">*Estado:</label>
														<input type="text" id="txtEstado" name="estado" class="form-control" />
													</div>
													
													<div class="form-group col-md-4">
														<label for="cmbSucursal" class="control-label blue">*Sucursal:</label>
														<select id="cmbSucursal" name="sucursal" class="form-control">
															<option value=0 selected>(Selecciona)</option>
															<?php foreach ($listaSucursales->result() as $kSuc) { ?>
																<option value=<?php echo $kSuc->id; ?>><?php echo $kSuc->sucursal; ?></option>
															<?php } ?>
														</select>
													</div>

													<div class="form-group col-md-4">
														<label for="cmbZona" class="control-label blue">*Zona:</label>
														<select id="cmbZona" name="zona" class="form-control">
															<option value=0 selected>(Selecciona)</option>
														</select>
													</div>

													<div class="form-group col-md-4">
														<label for="cmbClasificacionCliente" class="control-label blue">*Clasificación Cliente:</label>
														<select id="cmbClasificacionCliente" name="clasificacion" class="form-control">
															<option value=0 selected>[NA]</option>
															<?php foreach ($listaClasificacionCliente->result() as $kSuc) { ?>
																<option value=<?php echo $kSuc->id; ?>><?php echo $kSuc->clasificacion; ?></option>
															<?php } ?>
														</select>
													</div>

													<div class="form-group col-md-4">
														<label for="cmbCompradorActivo" class="control-label blue">*Comprador Activo:</label>
														<select id="cmbCompradorActivo" name="activo_comprador" class="form-control">
															<option value="1" selected>SI</option>
															<option value="2">NO</option>
														</select>
													</div>

													<!--<div class="form-group col-md-4">
														<label for="cmbProveedor" class="control-label blue">*Proveedor:</label>
														<select multiple="" id="cmbProveedor" class="select2 form-control" data-placeholder="Elige opcion">
														</select>
													</div>-->

													<div class="form-group col-md-12">
														<label for="txtComentarios" class="control-label blue">Comentarios:</label>
														<textarea id="txtComentarios" name="comentarios" class="form-control"></textarea>
													</div>

													<div class="form-group col-md-12">
														<label class="control-label blue">*Dias Visita:</label>
														<input name="diasvisita" id="txtDiasVisita" type="hidden"/>

														<div class="col-md-12">
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
														</div>
													</div>

													<div class="form-group col-md-4">
														<label for="checkActivo" class=" blue">
															<input id="checkActivo" name="status" class="ace" type="checkbox"/>
															<span class="lbl">Activo</span>
														</label>
													</div>

													<?php if($empresa == "01100480" || $empresa == "01220601") { ?>
														<div class="form-group">
															<div class="col-md-8">
																<span class="button-checkbox">
																	<button id="Chuponera" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">Chuponera</button>
																	<input id="chkChuponera" name="isChuponera" type="checkbox" class="hidden"/>
																</span>

																<span class="button-checkbox">
																	<button id="MundoCafe" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">Mundo Café</button>
																	<input id="chkMundoCafe" name="isMundoCafe" type="checkbox" class="hidden"/>
																</span>

																<span class="button-checkbox">
																	<button id="Enfriador" type="button" class="btn btn-default btn-sm checkdias" data-color="primary">Enfriador</button>
																	<input id="chkEnfriador" name="isEnfriador" type="checkbox" class="hidden"/>
																</span>
															</div>
														</div>
													<?php } ?>
											</div>
										</div>
									</div><!-- /.col -->

									<!--<div class="col-md-12">
										<div id="mapid"></div>
									</div>-->
								</div><!-- /.row -->

								<!--<input type="hidden" id="txtLatitud" name="latitud" value="">
								<input type="hidden" id="txtLongitud" name="longitud" value="">-->
						</div><!-- empieza div que contiene a la tabla -->
					</div><!--  termina div.col-xs-12 de la tabla clientes-->

					<div class="space-40"><br></div>

					<div class="col-md-12 col-xs-12 col-sm-12" align="center"><br>
						<button id="btnGuardar" class="btn btn-success btnGuardar">GUARDAR</button>
						<a href="<?php echo LINKPROYECTO('Clientes') ?>" class="btn btn-danger">REGRESAR</a>								
					</div>

				</div>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.page-content -->
</div>


<?php $this->load->view("vCopyright"); ?>

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
	<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>

<?php $this->load->view("vFooter"); ?>
</body>
</html>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>
		
<script>
	var validarDias=0;
	var colorPoli='';

	var BANDERA_SELECCIONAR_ZONA = 0, BANDERA_SELECCIONAR_PROVEEDOR = 0;

	window.onload = function()
	{
		<?php if($opcion=="editar" || $opcion=="ver") { ?>
			var diasvisita = "<?php echo $cliente->diasvisita; ?>";			

			$("#cmbSucursal").change();
			$(".direccion").blur();
			if(diasvisita.includes("1")) $("#Domingo")[0].click();
			if(diasvisita.includes("2")) $("#Lunes")[0].click();
			if(diasvisita.includes("3")) $("#Martes")[0].click();
			if(diasvisita.includes("4")) $("#Miercoles")[0].click();
			if(diasvisita.includes("5")) $("#Jueves")[0].click();
			if(diasvisita.includes("6")) $("#Viernes")[0].click();
			if(diasvisita.includes("7")) $("#Sabado")[0].click();

			<?php if($empresa == "01100480" || $empresa == "01220601") { ?>
				var isChuponera = "<?php echo $cliente->isChuponera; ?>";
				var isMundoCafe = "<?php echo $cliente->isMundoCafe; ?>";
				var isEnfriador = "<?php echo $cliente->isEnfriador; ?>";

				if(isChuponera == "1") $("#Chuponera")[0].click();
				if(isMundoCafe == "1") $("#MundoCafe")[0].click();
				if(isEnfriador == "1") $("#Enfriador")[0].click();
			<?php } ?>

		<?php } ?>
	}

	<?php if($opcion=="editar") { ?>
		cargarDatosFormulario();
	<?php } else if($opcion=="ver") { ?>
		cargarDatosFormulario();
		disabledFormulario();
		$(".btnGuardar").hide();
	<?php } ?>
			
	$(".btnGuardar").click(function(event)
	{
		var idservidor = $("#txtId").val();
		var nombre = $("#txtNombre").val();
		var nombredueno = $("#txtEncargado").val();
		var apellidosdueno = $("#txtEncargadoApellidos").val();
		var telefono = $("#txtTelefono").val();
		var correo = $("#txtCorreo").val();
		var calle = $("#txtCalle").val();
		var numero = $("#txtNumero").val();
		var colonia = $("#txtColonia").val();
		var cp = $("#txtCp").val();
		var ciudad = $("#txtCiudad").val();
		var estado = $("#txtEstado").val();
		var sucursal = $("#cmbSucursal").val();
		var zona = $("#cmbZona").val();
		var clasificacion = $("#cmbClasificacionCliente").val();
		var proveedor = "0";//$("#txtProveedor").val();
		var compradoractivo = $("#cmbCompradorActivo").val();

		if(nombre==""){
			dialogAvisoGlobal.show("Favor de escribir un nombre del negocio", "alert alert-warning");
		}
		else if(nombredueno==""){
			dialogAvisoGlobal.show("Favor de escribir un nombre del dueño", "alert alert-warning");
		}
		else if(apellidosdueno==""){
			dialogAvisoGlobal.show("Favor de escribir apellidos del dueño", "alert alert-warning");
		}
		else if(telefono==""){
			dialogAvisoGlobal.show("Favor de escribir un telefono", "alert alert-warning");
		}
		else if(correo==""){
			dialogAvisoGlobal.show("Favor de escribir un correo", "alert alert-warning");
		}
		else if(calle==""){
			dialogAvisoGlobal.show("Favor de escribir una calle", "alert alert-warning");
		}
		else if(numero==""){
			dialogAvisoGlobal.show("Favor de escribir un numero", "alert alert-warning");
		}
		else if(colonia==""){
			dialogAvisoGlobal.show("Favor de escribir una colonia", "alert alert-warning");
		}
		else if(cp==""){
			dialogAvisoGlobal.show("Favor de escribir un codigo postal", "alert alert-warning");
		}
		else if(ciudad==""){
			dialogAvisoGlobal.show("Favor de escribir una ciudad", "alert alert-warning");
		}
		else if(ciudad==""){
			dialogAvisoGlobal.show("Favor de escribir un estado", "alert alert-warning");
		}
		else if(sucursal=="0"){
			dialogAvisoGlobal.show("Favor de seleccionar una sucursal", "alert alert-warning");
		}
		else if(clasificacion=="0"){
			dialogAvisoGlobal.show("Favor de seleccionar una clasificacion", "alert alert-warning");
		}
		else if(zona=="0" || zona==null){
			dialogAvisoGlobal.show("Favor de seleccionar una zona", "alert alert-warning");
		}else if(proveedor==""){
			dialogAvisoGlobal.show("Favor de seleccionar un proveedor", "alert alert-warning");
		}
		else if(diasVisita()==""){
			dialogAvisoGlobal.show("Debe seleccionar al menos un dia de visita", "alert alert-warning");
		}
		else
		{
			var datoscliente = [
				{
					idservidor: idservidor,
					nombre: nombre,
					direccion: calle,
					calle: calle,
					numero: numero,
					colonia: colonia,
					ciudad: ciudad,
					estado: estado,
					cp: cp,
					//latitud: 0,
					//longitud: 0,
					encargado: nombredueno,
					encargadoapellidos: apellidosdueno,
					telefono: telefono,
					email: correo,
					diasvisita: diasVisita(),
					diasentrega: diasVisita(),
					esclientemovil: "SI",
					clasificacion: clasificacion,
					zona: zona,
					sucursal: sucursal,
					activo_comprador: compradoractivo,
					idusuarioactualiza: "<?php echo GETIDUSUARIO() ?>",
					ultima_actualizacion: "<?php echo GETFECHAHORA(); ?>"
				}
			];

			$.post("<?php echo LINKPROYECTO('App/InsertClient') ?>", 
			{
				cliente: JSON.stringify(datoscliente[0]),
				usuario: "<?php echo GETIDUSUARIO() ?>",
				empresa: "<?php echo GETEMPRESA(); ?>"
			},
			function(data)
			{
				dialogAvisoGlobal.show("Todo bien", "alert alert-success");
				window.location = "<?php echo LINKPROYECTO('Clientes') ?>";
			})
			.fail(function(data) {
				dialogAvisoGlobal.show("Ocurrio un error al guardar el cliente", "alert alert-danger");
			})
			.always(function() {
			});
		}
	});

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
			$("#txtDiasVisita").val(dias);
		}

		return dias;
	}	

	var banderaMapa=0;
	var banderaPoligono=0;
	var markers;
	var polygon;


    /*var map = L.map('mapid',{zoomControl: false}).setView([23.242251, -106.442509], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var zoom_bar = new L.Control.ZoomBar({position: 'topright'}).addTo(map);    

    $("#mapid").css("display", "block");
	var marker = new Array();
	var coordenadasPol='';
    var items = [{"lat":"23.255460","lon":"-106.422843","pop":"Hola"}];*/

	function itemWrap()
	{
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

		if (banderaMapa==0)
		{			
			banderaMapa=1;
		}
	}

	function centerLeafletMapOnMarker(map, marker)
	{
		var latLngs = [ marker.getLatLng() ];
		var markerBounds = L.latLngBounds(latLngs);
		map.fitBounds(markerBounds);
	}
        
	function markerDelAgain() 
	{
		for(i=0;i<marker.length;i++)
		{
			map.removeLayer(marker[i]);
		}  
		marker.splice(0, marker.length);
	}


	function crearPoligono()
	{		
		var itemsPol=eval(coordenadasPol);	
		polygon = L.polygon(itemsPol, {color: colorPoli}).addTo(map);
	}

	function borrarPoligono()
	{
		map.removeLayer(polygon);
	}

	$("#cmbSucursal").change(function(event) {
		var pro="";
		var idSucursal=$("#cmbSucursal").val();

		$("#cmbProveedor").select2("val", "");

		$.post("<?php echo CCATALOGOS('createComboZona');?>", {sucursal: idSucursal},function(data){
			$("#cmbZona").html(data);

			<?php if($opcion=="editar" || $opcion=="ver") { ?>
				if(BANDERA_SELECCIONAR_ZONA==0){
					BANDERA_SELECCIONAR_ZONA=1;
					$("#cmbZona").val("<?php echo $cliente->zona; ?>");
				}
			<?php } ?>
		});		

		$.post("<?php echo CCATALOGOS('createComboProveedores');?>", {sucursal: idSucursal},function(data){

			$("#cmbProveedor").html(data);
			$("#txtProveedor").val(pro);

			<?php if($opcion=="editar" || $opcion=="ver") { ?>
				if(BANDERA_SELECCIONAR_PROVEEDOR==0){
					BANDERA_SELECCIONAR_PROVEEDOR=1;
					
					var values = "<?php echo $cliente->proveedores; ?>";			
					var multi = document.getElementById('cmbProveedor');

					multi.value = null; // Reset pre-selected options (just in case)
					var multiLen = multi.options.length;
					for (var i = 0; i < multiLen; i++) {
						if(values.includes(multi.options[i].text))
						{
							multi.options[i].selected = true;
						}
					}

					$("#cmbProveedor").change();
				}
			<?php } ?>

		});
	});

	$("#cmbProveedor").change(function(event) {	
		var texto=$("#txtProveedor").val();
		texto=texto+","+$("#cmbProveedor").val();
		$("#txtProveedor").val($("#cmbProveedor").val());
	});

	var geocoder = new google.maps.Geocoder();
	var conteo=0;

	$(".direccion").blur(function(event) {	
		if(($("#txtCalle").val()!="")&&($("#txtCiudad").val()!="")&&($("#txtColonia").val()!="")&&($("#txtNumero").val()!=""))
		{
			var domicilio=$("#txtCalle").val()+" "+$("#txtNumero").val()+" "+$("#txtColonia").val()+", "+$("#txtCiudad").val();
			geocoder.geocode({ 'address': domicilio}, function(results, status)
			{
				if (status == 'OK')
				{
					$("#txtLatitud").val(results[0].geometry.location.lat());
					$("#txtLongitud").val(results[0].geometry.location.lng());
					items = [{"lat":results[0].geometry.location.lat(),"lon":results[0].geometry.location.lng(),"pop":$("#txtDomicilio").val()}];						
					if(conteo==0)
					{
						conteo=1;
						itemWrap();
					}
					else
					{
						markerDelAgain();
						itemWrap();
					}
				}
			});
		}
	});

	$(".checkdias").on("click", function() {
		var id = $(this).attr("id");
		var valor = $("#chk"+id).prop("checked");		
		if(valor){
			validarDias=validarDias-1;
			$("#chk"+id).prop("checked", false);
			$("#"+id).removeClass("btn btn-primary");
			$("#"+id).addClass("btn btn-default");			
		}
		else
		{
			$("#chk"+id).prop("checked", true);
			validarDias = validarDias+1;
			$("#"+id).removeClass("btn btn-default");
			$("#"+id).addClass("btn btn-primary");
		}
	});

	$('.select2').css('width','200px').select2({allowClear:true})
	$('#select2-multiple-style .btn').on('click', function(e){
		var target = $(this).find('input[type=radio]');
		var which = parseInt(target.val());
		if(which == 2) $('.select2').addClass('tag-input-style');
			else $('.select2').removeClass('tag-input-style');
	});

	<?php if($opcion=="editar" || $opcion=="ver") { ?>

		<?php if($opcion=="editar"){ ?>
			$("#titlepage").text("Editar Cliente: " + "<?php echo $cliente->codigo; ?>");
		<?php } else if($opcion=="ver") { ?>
			$("#titlepage").text("Ver Cliente: " + "<?php echo $cliente->codigo; ?>");
		<?php } ?>

		function cargarDatosFormulario()
		{
			$("#txtId").val("<?php echo $cliente->id; ?>");
			$("#txtNombre").val("<?php echo $cliente->nombre; ?>");
			$("#txtEncargado").val("<?php echo $cliente->encargado; ?>");
			$("#txtEncargadoApellidos").val("<?php echo $cliente->encargadoapellidos; ?>");
			$("#txtTelefono").val("<?php echo $cliente->telefono; ?>");
			$("#txtCorreo").val("<?php echo $cliente->email; ?>");
			$("#txtCalle").val("<?php echo $cliente->calle; ?>");
			$("#txtNumero").val("<?php echo $cliente->numero; ?>");
			$("#txtColonia").val("<?php echo $cliente->colonia; ?>");
			$("#txtCp").val("<?php echo $cliente->cp; ?>");
			$("#txtCiudad").val("<?php echo $cliente->ciudad; ?>");
			$("#txtEstado").val("<?php echo $cliente->estado; ?>");
			$("#cmbSucursal").val("<?php echo $cliente->sucursal; ?>");
			$("#cmbClasificacionCliente").val("<?php echo $cliente->clasificacion; ?>");
			$("#txtComentarios").val("<?php echo $cliente->comentarios; ?>");
			$("#cmbCompradorActivo").val("<?php echo $cliente->activo_comprador; ?>");
			$("#checkActivo").prop("checked", "<?php echo (($cliente->status==1) ? true : false); ?>");
		}
	<?php } ?>

	function disabledFormulario()
	{
		$("#txtId").prop("disabled", true);
		$("#txtCodigo").prop("disabled", true);
		$("#txtNombre").prop("disabled", true);
		$("#txtPrecio").prop("disabled", true);
		$("#txtIeps").prop("disabled", true);
		$("#txtIva").prop("disabled", true);
		$("#cmbClasificacionCliente").prop("disabled", true);
		$("#txtTipo").prop("disabled", true);
		$("#cmbProveedor").prop("disabled", true);
		$("#txtClaveSAT").prop("disabled", true);
		$("#checkActivo").prop("disabled", true);
	}

</script>