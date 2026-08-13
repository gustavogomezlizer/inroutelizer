<?php 
$data['title']="LIZER Agregar Usuario";
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



<div class="main-content">
	<div class="main-content-inner">	
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Usuarios</small>
				</h1>
				<div align="right">
					<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
					<a href="<?php echo LINKPROYECTO('Usuarios') ?>" class="btn btn-danger">REGRESAR</a>
				</div>				
			</div>

			<div class="row">
				<div class="col-xs-10">
					<div class="row">

						<h2 id="titlepage" align="center" class="control-label green">NUEVO USUARIO</h2>

						<div class="col-xs-12">
							<form id="form_saveusuario" action="<?php echo CCATALOGOS('saveNuevoUsuario'); ?>" method="POST">
								<div class="row">
									<div class="col-sm-12">	
										<div class="row">
											<div class="col-xs-12">

												<input id="txtId" type="hidden" value="0" name="id" />
												<input id="txtSucursalesAsignadas" type="hidden" value="" name="sucursal_asignadas" />

												<div class="form-horizontal" role="form">

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Usuario <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<input type="text" id="txtUsuario" name="usuario" class="form-control obligatorio" value=""/>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Clave <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<input type="text" id="txtClave" name="clave" class="form-control obligatorio" value=""/>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Nombre <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<input type="text" id="txtNombre" name="nombre" class="form-control obligatorio" value=""/>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Puesto <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<input type="text" id="txtPuesto" name="puesto" class="form-control obligatorio" value=""/>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Domicilio: </label>
														<div class="col-sm-8">
															<input type="text" id="txtDomicilio" name="domicilio" class="form-control" value=""/>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Telefono: </label>
														<div class="col-sm-8">
															<input type="text" id="txtTelefono" name="telefono" class="form-control" value=""/>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Correo: </label>
														<div class="col-sm-8">
															<input type="text" id="txtCorreo" name="correo" class="form-control" value=""/>
														</div>
													</div>
												
													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Comentarios: </label>
														<div class="col-sm-8">
															<textarea id="txtComentarios" name="observaciones" class="form-control"></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Perfil <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<select id="cmbPerfil" name="perfil" class="form-control">
																<option value=0 selected>(Selecciona)</option>
																<?php foreach ($listaPerfiles->result() as $kPer) { ?>
																	<option value=<?php echo $kPer->id; ?>><?php echo $kPer->perfil; ?></option>
																<?php } ?>
															</select> 
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
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Sucursal Asignadas: </label>
														<div class="col-sm-8">																
															<select id="cmbSucursalAsignadas" multiple="" class="select2 form-control" data-placeholder="Elige opcion">
																<?php foreach ($listaSucursales->result() as $kSuc) { ?>
																	<option value=<?php echo $kSuc->id; ?>><?php echo $kSuc->sucursal; ?></option>
																<?php } ?>
															</select> 
														</div>
													</div>

													<div class="form-group">														
														<label  class="col-sm-offset-4 col-sm-2 no-padding-right blue">
															<input id="checkActivo" name="status" class="ace" type="checkbox" checked />																	
															<span class="lbl">Activo</span>
														</label>													

														<label  class="col-sm-2 no-padding-right blue">  
															<input id="checkNuevo" name="nuevo" class="ace" type="checkbox" checked />
															<span class="lbl">Cambiar Clave</span>
														</label>

														<label  class="col-sm-2 no-padding-right blue">  															
															<input id="checkVendedor" name="vendedor" class="ace" type="checkbox" checked />
															<span class="lbl">Vendedor</span>
														</label>

														<label  class="col-sm-2 no-padding-right blue">  															
															<input id="checkMultiSucursal" name="multisucursal" class="ace" type="checkbox" checked />
															<span class="lbl">Multisucursal</span>
														</label>

													</div>
													
													<?php 
														$cuenta=0;
														
														$cadena="Selecciona Avatar:";
														?>

														<div class="form-group">
																<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> <?php echo $cadena; ?> </label>
															<div class="col-sm-8">
																<div class="radio">
																	<?php 
																	$cuantos=$listaAvatares->num_rows();
																	foreach ($listaAvatares->result() as $kAV) 
																	{
																		if($cuenta==0)
																		{
																			$cuenta=1;
																			$checkBB="checked";
																		}
																		else
																		{
																			$checkBB="";
																		}
																		?>

																		<label>
																			<input id="rdbFoto" name="foto" type="radio" class="ace" value="<?php echo $kAV->avatar; ?>" <?php echo $checkBB; ?>/>
																			<span class="lbl"> <img class="nav-user-photo" src="<?php echo RUTAFOLDERASSETS("images/avatars/".$kAV->avatar); ?>" /></span>
																		</label>
																	<?php } ?>
																</div>
															</div>
														</div>
												</div>
											</div>
										</div>
									</div>
								</div><!-- /.col -->
							</div><!-- /.row -->
						</form>
					</div><!-- empieza div que contiene a la tabla -->
				</div><!--  termina div.col-xs-12 de la tabla clientes-->

				<div class="space-40"><br></div>
				<div class="col-md-12 col-xs-12 col-sm-12" align="center"><br>
					<button id="btnGuardar" class="btn btn-success btnGuardar">GUARDAR</button>
				</div>
			</div><!--  termina div.row de la tabla clientes-->
		</div>
	</div><!-- /.col -->
</div><!-- /.row -->


<?php $this->load->view("vCopyright"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>
	
<?php $this->load->view("vFooter"); ?>

</body>
</html>		

			
<script>

	<?php if($opcion=="editar") { ?>
		cargarDatosFormulario();
	<?php } else if($opcion=="ver") { ?>
		cargarDatosFormulario();
		disabledFormulario();
		$(".btnGuardar").hide();
	<?php } ?>

	$('.select2').css('width','600px').select2({allowClear:false})
	$('#select2-multiple-style .btn').on('click', function(e){
		var target = $(this).find('input[type=radio]');
		var which = parseInt(target.val());
		if(which == 2) $('.select2').addClass('tag-input-style');
			else $('.select2').removeClass('tag-input-style');
	});

	$(".btnGuardar").click(function(event) 
	{
		var usuario = $("#txtUsuario").val();
		var clave = $("#txtClave").val();
		var nombre = $("#txtNombre").val();
		var puesto = $("#txtPuesto").val();

		var perfil = $("#cmbPerfil").val();
		var sucursal = $("#cmbSucursal").val();
		var sucursales_asignadas = $("#cmbSucursalAsignadas").val();

		if(usuario==""){
			dialogAvisoGlobal.show("Favor de capturar el usuario", "alert alert-warning");
		}else if(clave==""){
			dialogAvisoGlobal.show("Favor de capturar la clave", "alert alert-warning");
		}else if(nombre==""){
			dialogAvisoGlobal.show("Favor de capturar el nombre", "alert alert-warning");
		}else if(puesto==""){
			dialogAvisoGlobal.show("Favor de capturar el puesto", "alert alert-warning");
		}else if(perfil==0){
			dialogAvisoGlobal.show("Favor de capturar el perfil", "alert alert-warning");
		}else if(sucursal==0){
			dialogAvisoGlobal.show("Favor de capturar la sucursal", "alert alert-warning");
		}else{

			if(sucursales_asignadas!=null)
			{
				$("#txtSucursalesAsignadas").val(sucursales_asignadas.toString());
			}

			$.post("<?php echo LINKPROYECTO('GuardarUsuario'); ?>", $("#form_saveusuario").serialize(), function(data){
				if(data.trim()=="existe"){
					dialogAvisoGlobal.show("Ya se encuntra registrado el nombre de usuario " + usuario, "alert alert-danger");
				}
				else if(parseFloat(data.trim()) > 0){
					dialogAvisoGlobal.show("El usuario se guardo correctamente", "alert alert-success");
					window.location = "<?php echo LINKPROYECTO('Usuarios') ?>";
				}else{
					dialogAvisoGlobal.show("Ocurrio un error al guardar el usuario", "alert alert-danger");
				}
			});
		}

	});

	<?php if($opcion=="editar" || $opcion=="ver") { ?>

		<?php if($opcion=="editar"){ ?>
			$("#titlepage").text("Editar Usuario");
		<?php } else if($opcion=="ver") { ?>
			$("#titlepage").text("Ver Usuario");
		<?php } ?>

		function cargarDatosFormulario()
		{
			$("#txtId").val("<?php echo $usuario->id; ?>");
			$("#txtUsuario").val("<?php echo $usuario->usuario; ?>");
			$("#txtClave").val("<?php echo $usuario->clave; ?>");
			$("#txtNombre").val("<?php echo $usuario->nombre; ?>");
			$("#txtPuesto").val("<?php echo $usuario->puesto; ?>");
			$("#txtDomicilio").val("<?php echo $usuario->domicilio; ?>");
			$("#txtTelefono").val("<?php echo $usuario->telefono; ?>");
			$("#txtCorreo").val("<?php echo $usuario->correo; ?>");
			$("#txtComentarios").val("<?php echo $usuario->observaciones; ?>");
			$("#cmbPerfil").val("<?php echo $usuario->perfil; ?>");
			$("#cmbSucursal").val("<?php echo $usuario->sucursal; ?>");
			$("#checkActivo").prop("checked", "<?php echo (($usuario->status==1) ? true : false); ?>");
			$("#checkNuevo").prop("checked", "<?php echo (($usuario->nuevo==1) ? true : false); ?>");
			$("#checkVendedor").prop("checked", "<?php echo (($usuario->vendedor==1) ? true : false); ?>");
			$("#checkMultiSucursal").prop("checked", "<?php echo (($usuario->multisucursal==1) ? true : false); ?>");
			$("input[name=foto][value='<?php echo $usuario->foto ?>']").prop("checked", true);

			var values = "<?php echo $usuario->sucursal_asignadas; ?>";
			var array = values.split(',');//JSON.parse("[" + values + "]");
			var multi = document.getElementById('cmbSucursalAsignadas');

			multi.value = null; // Reset pre-selected options (just in case)
			var multiLen = multi.options.length;
			for (var i = 0; i < multiLen; i++)
			{
				if(array.includes(multi.options[i].value))
				{
					multi.options[i].selected = true;
				}
			}

			//$("#cmbSucursalAsignadas").trigger('change');
		}
	<?php } ?>

	function disabledFormulario()
	{
		$("#txtId").prop("disabled", true);
		$("#txtUsuario").prop("disabled", true);
		$("#txtClave").prop("disabled", true);
		$("#txtNombre").prop("disabled", true);
		$("#txtPuesto").prop("disabled", true);
		$("#txtDomicilio").prop("disabled", true);
		$("#txtTelefono").prop("disabled", true);
		$("#txtCorreo").prop("disabled", true);
		$("#txtComentarios").prop("disabled", true);
		$("#cmbPerfil").prop("disabled", true);
		$("#checkActivo").prop("disabled", true);
		$("#checkNuevo").prop("disabled", true);
		$("#checkVendedor").prop("disabled", true);
		$("#checkMultiSucursal").prop("disabled", true);
		$("#checkActivo").prop("disabled", true);

		$("#cmbSucursalAsignadas").prop("disabled", true);
	}

</script>





		

