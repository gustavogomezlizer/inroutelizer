<?php 
$data['title']="LIZER Agregar Ruta";
$this->load->view("vHead",$data); 
$coordenadas='[{"lat":"0","lon":"0","pop":"0"}]';?>
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
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Rutas</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-12">				
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
							<div class="col-md-12 col-xs-12 col-sm-12" align="right">
								<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
								<a href="<?php echo CCATALOGOS('listaRutas') ?>" class="btn btn-danger">REGRESAR</a>
							</div>
						</div>

						<div class="col-xs-12"><br></div>
						<div class="space-40"></div>

						<div class="col-xs-12">
							<form id="form_saveruta" action="<?php echo CCATALOGOS('saveNuevaRuta'); ?>" method="POST">
								<div class="row">
									<div class="col-sm-6">
										
										<div class="row" align="center">
											<div class="col-xs-12">
												<h4 id="titlepage" class="control-label green">NUEVA RUTA</h4>
											</div>
										</div>

										<div class="space-40"><br></div>
											
										<div class="row">
											<div class="col-xs-12">

												<input id="txtId" type="hidden" value="0" name="id" />

												<div class="form-horizontal" role="form">

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Ruta <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">															
															<input type="text" id="txtRuta" name="ruta" class="form-control obligatorio" value=""/>
														</div>
													</div>

													<div class="form-group">
														<label  class="col-sm-offset-4 col-sm-2 no-padding-right blue">
														<input id="checkActivo" name="status" class="ace" type="checkbox" checked />
														<span class="lbl">Activo</span>
														</label>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Comentarios: </label>
														<div class="col-sm-8">
															<textarea id="txtComentarios" name="descripcion" class="form-control"></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Sucursal <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">																
															<select id="cmbSucursal" name="sucursal" class="form-control">
																<option value=0 selected>(Selecciona)</option>
																<?php foreach ($listaSucursales->result() as $kSuc) { ?>
																	<option value=<?php echo $kSuc->id; ?>><?php echo $kSuc->sucursal; ?></option>
																<?php } ?>
															</select>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Proveedor <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
														<select multiple="" id="cmbProveedor" class="select2" data-placeholder="Elige opcion">
														</select>
														<input type="hidden" id="txtProveedor" name="proveedor" value="0">
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Zona <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<select multiple="" id="cmbZona1" class="select2 zona1" data-placeholder="Elige opcion">
															</select>
															<input type="hidden" id="txtZona" name="zona" value="0">
														</div>
													</div>
														
													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Agente <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<select id="cmbAgente" name="agente" class="form-control">
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
					</div>
				</div><!--  termina div.row de la tabla clientes-->								
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

	var BANDERA_SELECCIONAR_ZONA = 0, BANDERA_SELECCIONAR_PROVEEDOR = 0, BANDERA_SELECCIONAR_AGENTE = 0;

	var colorPoli='';

	var banderaMapa=0;
	var banderaPoligono=0;	
	var markers;
	var polygon;
	var map = L.map('mapid',{zoomControl: false}).setView([23.242251, -106.442509], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    var zoom_bar = new L.Control.ZoomBar({position: 'topright'}).addTo(map);
    $("#mapid").css("display", "block");
	var marker = new Array();
	var poligonos= new Array();
	var marcadores=L.markerClusterGroup();
	var coordenadasPol='';
	var items;

	window.onload = function()
	{
		<?php if($opcion=="editar" || $opcion=="ver") { ?>
			$("#cmbSucursal").change();
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
		var ruta = $("#txtRuta").val();
		var sucursal = $("#cmbSucursal").val();
		var proveedor = $("#txtProveedor").val();
		var zona = $("#txtZona").val();
		var agente = $("#cmbAgente").val();

		if(ruta=="")
		{
			dialogAvisoGlobal.show("Favor de escribir una ruta", "alert alert-warning");
		}
		else if(sucursal==0){
			dialogAvisoGlobal.show("Favor de seleccionar una sucursal", "alert alert-warning");
		}		
		else if(proveedor==""){
			dialogAvisoGlobal.show("Favor de seleccionar un proveedor", "alert alert-warning");
		}
		else if(zona==""){
			dialogAvisoGlobal.show("Favor de seleccionar una zona", "alert alert-warning");
		}
		else if(agente==0){
			dialogAvisoGlobal.show("Favor de seleccionar un agente", "alert alert-warning");
		}
		else
		{
			$.post("<?php echo LINKPROYECTO('GuardarRuta') ?>", $("#form_saveruta").serialize(), function(data){
				if(data.trim()=="existe"){
					dialogAvisoGlobal.show("El nombre de la ruta ya existe", "alert alert-danger");
				}
				else if( parseFloat(data.trim())>0 ){
					dialogAvisoGlobal.show("Ruta guardada correctamente", "alert alert-success");
					//window.location = "<?php echo LINKPROYECTO('Rutas') ?>";
					window.close();
				}else{
					dialogAvisoGlobal.show("Ocurrio un error al guardar la ruta", "alert alert-danger");
				}
			});
		}
	});

	$("#cmbZona1").change(function(event) {	 	
		var cadena = "";
		var zona = $("#cmbZona1").val();

		var texto = $("#cmbZona1").val();		
		//texto = texto + "," + $("#cmbZona1").val();

		$("#txtZona").val(texto);
		
		/*if(banderaPoligono==0){
			banderaPoligono=1;		 				
		}
		else{
			borrarPoligono();
		}*/

		$.post("<?php echo CCATALOGOS('obtenerPoligono2');?>", {zona: $("#txtZona").val()},function(data){
			if(data.trim()!="")
			{
				var cadenaori=data;	                
				var arregloori=cadenaori.split("&");
				var cantidadarregloori=arregloori.length;

				for (var i = 0; i < cantidadarregloori; i++) 
				{
					cadena=arregloori[i];	                	
					arreglo=cadena.split("/");
					colorPoli=arreglo[0];
					coordenadasPol=arreglo[1];				
					crearPoligono();
				}

				crearMarcadores($("#txtZona").val());
			}			
		});
	});

	$("#cmbSucursal").change(function(event) {	 	
	 	var pro="";
	 	var idSucursal=$("#cmbSucursal").val();

		$("#cmbZona1").select2("val", "");

	 	$.post("<?php echo CCATALOGOS('createComboZona2');?>", {sucursal: idSucursal},function(data){
			$("#cmbZona1").html(data);
			$("#txtZona").val(pro);

			<?php if($opcion=="editar" || $opcion=="ver") { ?>
				if(BANDERA_SELECCIONAR_ZONA==0){
					BANDERA_SELECCIONAR_ZONA=1;
					
					var values = "<?php echo $ruta->zonas; ?>";			
					var multi = document.getElementById('cmbZona1');

					multi.value = null; // Reset pre-selected options (just in case)
					var multiLen = multi.options.length;
					for (var i = 0; i < multiLen; i++) {
						if(values.includes(multi.options[i].text))
						{
							multi.options[i].selected = true;
						}
					}

					$("#cmbZona1").change();
				}
			<?php } ?>

        });

		$.post("<?php echo CCATALOGOS('createComboProveedores');?>", {sucursal: idSucursal},function(data){			
			$("#cmbProveedor").html(data);
			$("#cmbProveedor").change();
			$("#txtProveedor").val(pro);

			<?php if($opcion=="editar" || $opcion=="ver") { ?>
				if(BANDERA_SELECCIONAR_PROVEEDOR==0){
					BANDERA_SELECCIONAR_PROVEEDOR=1;
					
					var values = "<?php echo $ruta->proveedores; ?>";			
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

		$.post("<?php echo CCATALOGOS('createComboAgente');?>", {sucursal: idSucursal},function(data){
			$("#cmbAgente").html(data);
			
			<?php if($opcion=="editar" || $opcion=="ver") { ?>
				if(BANDERA_SELECCIONAR_AGENTE==0){
					BANDERA_SELECCIONAR_AGENTE=1;
					$("#cmbAgente").val("<?php echo $ruta->chofer; ?>");
				}
			<?php } ?>

		});

	});

	$("#cmbProveedor").change(function(event) {	 	
		var texto = $("#cmbProveedor").val();
		//texto = $("#cmbProveedor").val() + texto + ",";
		$("#txtProveedor").val(texto);
		//crearMarcadores($("#txtZona").val());
	});

	$('.select2').css('width','200px').select2({allowClear:false})
	$('#select2-multiple-style .btn').on('click', function(e){
		var target = $(this).find('input[type=radio]');
		var which = parseInt(target.val());
		if(which == 2) $('.select2').addClass('tag-input-style');
			else $('.select2').removeClass('tag-input-style');
	});

	function crearMarcadores(zona)
	{
		map.off();
		map.remove();
		map = L.map('mapid',{zoomControl: false}).setView([23.242251, -106.442509], 12);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    	}).addTo(map);
    	zoom_bar = new L.Control.ZoomBar({position: 'topright'}).addTo(map);

		if(zona=="") return;		

		//markerDelAgain();
		$.post("<?php echo CCATALOGOS('obtenerMarcadoresZona');?>", {zona: zona, proveedor: $("#txtProveedor").val()},function(data){
			var cadenaori=data;
			var arregloori1=cadenaori.split("&");
			var arregloori=arregloori1[1].split("%");
			var cantidadarregloori=arregloori.length;
			var cantidad=arregloori1[0];
			var contador=0;

			for (var i = 0; i < cantidadarregloori; i++) 
			{				
				cadena=arregloori[i];				
				arreglo=arregloori[i].split("/");
				nombre=arreglo[0];
				latitud = (arreglo[1] == "" || arreglo[1] === undefined || arreglo[1] === null) ? "0" : arreglo[1];
				longitud = (arreglo[2] == "" || arreglo[2] === undefined || arreglo[2] === null) ? "0" : arreglo[2];
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
				$("#cantidadClientes").html('<strong>'+cantidad+'</strong>');		
			}
		});
	}

	//mapa	

	function itemWrap()
	{
		for(i=0;i<items.length;i++){
			var LamMarker = new L.marker([items[i].lat, items[i].lon],{
				draggable: true,
				icon: L.AwesomeMarkers.icon({icon: 'spinner', prefix: 'fa', markerColor: 'darkblue', spin:true}) 
			}).bindPopup(items[i].pop);
			LamMarker.on('dragend', function (e) {							

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
	//fin mapa

	<?php if($opcion=="editar" || $opcion=="ver") { ?>

		<?php if($opcion=="editar"){ ?>
			$("#titlepage").text("Editar Ruta: " + "<?php echo $ruta->ruta; ?>");
		<?php } else if($opcion=="ver") { ?>
			$("#titlepage").text("Ver Ruta: " + "<?php echo $ruta->ruta; ?>");
		<?php } ?>

		function cargarDatosFormulario()
		{
			$("#txtId").val("<?php echo $ruta->id; ?>");
			$("#txtRuta").val("<?php echo $ruta->ruta; ?>");
			$("#txtComentarios").val("<?php echo $ruta->descripcion; ?>");
			$("#cmbSucursal").val("<?php echo $ruta->sucursal; ?>");			
			$("#checkActivo").prop("checked", "<?php echo (($ruta->status==1) ? true : false); ?>");
		}
	<?php } ?>

	function disabledFormulario()
	{
		$("#txtId").prop("disabled", true);
		$("#txtRuta").prop("disabled", true);
		$("#txtComentarios").prop("disabled", true);
		$("#cmbSucursal").prop("disabled", true);
		$("#cmbProveedor").prop("disabled", true);
		$("#cmbZona").prop("disabled", true);
		$("#cmbAgente").prop("disabled", true);
		$("#checkActivo").prop("disabled", true);
	}

</script>