<?php 
$data['title']="LIZER Editar Ruta";
$this->load->view("vHead",$data); 
$fecha1=date('y-m-d');
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
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Zonas		</small>
				</h1>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
							<div class="col-md-12 col-xs-12 col-sm-12" align="right">
								<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
								<a class="btn btn-danger" href="<?php echo LINKPROYECTO('Zonas'); ?>" >REGRESAR</a>
							</div>
						</div>

						<div class="col-xs-12"><br></div>
						<div class="space-40"></div>
									
										
						<div class="col-xs-12">
						<form id="form_savezona" action="<?php echo CCATALOGOS('saveEditarZona'); ?>" method="POST">
							<div class="row">
								<div class="col-sm-6">
									<div class="row" align="center">
										<div class="col-xs-12">
											<h4 id="titlepage" class="control-label green">NUEVA ZONA</h4>
										</div>
									</div>

									<div class="space-40"><br></div>
										<div class="row">
											<div class="col-xs-12">

												<input type="hidden" id="txtId" name="id" class="form-control" value="0" />

												<div class="form-horizontal" role="form">

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Zona(obligatorio): </label>
														<div class="col-sm-8">
															<input type="text" id="txtZona" name="zona" class="form-control" />
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Ciudad(obligatorio): </label>
														<div class="col-sm-8">
															<input type="text" id="txtCiudad" name="ciudad" class="form-control" />
														</div>
													</div>

													<div class="form-group">
														<label  class="col-sm-offset-4 col-sm-2 no-padding-right blue">  															
														<input id="checkActivo" name="status" class="ace" type="checkbox" checked />
														<span class="lbl">Activo</span>
														</label>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Observaciones: </label>
														<div class="col-sm-8">
															<textarea id="txtObservacion" name="observacion" class="form-control"></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Sucursal(obligatorio): </label>
														<div class="col-sm-8">
															<select id="cmbSucursal" name="idSucursal" class="form-control">
																<option value=0 selected>(Selecciona)</option>
																<?php foreach ($listaSucursales->result() as $kSuc) { ?>
																	<option value=<?php echo $kSuc->id; ?>><?php echo $kSuc->sucursal; ?></option>
																<?php } ?>
															</select>
														</div>
													</div>

													<!--<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Poligono: </label>
														<div class="col-sm-8">
															<select id="cmbPoligonos" name="cmbPoligonos" class="form-control">
																<option value=0 disabled selected>(Selecciona)</option>
																<?php /*foreach ($listaPoligonos->result() as $kPol) { ?>
																	<option value=<?php echo $kPol->id; ?>><?php echo $kPol->nombre; ?></option>
																<?php }*/ ?>
															</select>
														</div>
													</div>-->
													<input type="hidden" id="txtPoligono" name="poligono" value="1" />

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Cantidad de Clientes: </label>
														<div class="col-sm-8">
															<h5 id="cantidadClientes">0</h5>
														</div>
													</div>
												</div>
											</div>
										</div>		
								</div><!-- /.col -->

								<!--<div class="col-sm-6">
									<div id="mapid"></div>
								</div>-->

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

<?php $this->load->view("vCopyright"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

<?php $this->load->view("vFooter"); ?>

</body>
</html>

<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>-->

<script>

	window.onload = function()
	{		
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
		var nombre = $("#txtZona").val();
		var ciudad = $("#txtCiudad").val();
		var sucursal = $("#cmbSucursal").val();

		if(nombre=="")
		{
			dialogAvisoGlobal.show("Favor de escribir nombre de zona", "alert alert-warning");
		}
		else if(ciudad=="")
		{
			dialogAvisoGlobal.show("Favor de escribir una ciudad", "alert alert-warning");
		}
		else if(sucursal==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una sucursal", "alert alert-danger");
		}
		else
		{
			$.post("<?php echo LINKPROYECTO('GuardarZona') ?>", $("#form_savezona").serialize(), function(data){
				if(data.trim()=="existe"){
					dialogAvisoGlobal.show("El nombre de zona ingresado ya existe " + nombre, "alert alert-danger");
				}
				else if( parseFloat(data.trim())>0 ){
					dialogAvisoGlobal.show("Zona guardada correctamente", "alert alert-success");
					window.location = "<?php echo LINKPROYECTO('Zonas') ?>";
				}else{
					dialogAvisoGlobal.show("Ocurrio un error al guardar la zona", "alert alert-danger");
				}
			});
		}
	});

	<?php if($opcion=="editar" || $opcion=="ver") { ?>

		<?php if($opcion=="editar"){ ?>
			$("#titlepage").text("Editar Zona: " + "<?php echo $zona->zona; ?>");
		<?php } else if($opcion=="ver") { ?>
			$("#titlepage").text("Ver Zona: " + "<?php echo $zona->zona; ?>");
		<?php } ?>

		function cargarDatosFormulario()
		{
			$("#txtId").val("<?php echo $zona->id; ?>");
			$("#txtZona").val("<?php echo $zona->zona; ?>");
			$("#txtCiudad").val("<?php echo $zona->ciudad; ?>");
			$("#txtObservacion").val("<?php echo $zona->observacion; ?>");
			$("#cmbSucursal").val("<?php echo $zona->idSucursal; ?>");
			$("#checkActivo").prop("checked", "<?php echo (($zona->status==1) ? true : false); ?>");
			$("#cantidadClientes").text("<?php echo $zona->num_clientes; ?>");
		}
	<?php } ?>

	function disabledFormulario()
	{
		$("#txtId").prop("disabled", true);
		$("#txtZona").prop("disabled", true);
		$("#txtCiudad").prop("disabled", true);
		$("#txtObservacion").prop("disabled", true);
		$("#cmbSucursal").prop("disabled", true);
		$("#checkActivo").prop("disabled", true);
	}

</script>