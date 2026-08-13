<?php 
$data['title']="LIZER Agregar Sucursal";
$this->load->view("vHead",$data); ?>
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
				<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Sucursal</small>
			</h1>
		</div>

		<div class="row">
			<div class="col-xs-10">
				<div class="row"><!--  empieza div.row de la tabla clientes -->
					<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
						<div class="col-md-12 col-xs-12 col-sm-12" align="right">
							<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
							<a href="<?php echo LINKPROYECTO('Sucursales') ?>" class="btn btn-danger">REGRESAR</a>
						</div>
					</div>

					<div class="col-xs-12"><br></div>
					<div class="space-40"></div>
									
										
					<div class="col-xs-12">
						<form id="form_savesucursal" action="<?php echo CCATALOGOS('saveNuevaSucursal'); ?>" method="POST">
							<div class="row">
								<div class="col-sm-12">
									<div class="row" align="center">
										<div class="col-xs-12">
											<h4 class="control-label green">NUEVA SUCURSAL</h4>
										</div>
									</div>
															
									<div class="space-40"><br></div>
											
									<div class="row">
										<div class="col-xs-12">
											<div class="form-horizontal" role="form">

												<input id="txtId" type="hidden" value="0" name="id" />

												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Clave <small>(Obligatorio)</small>: </label>
													<div class="col-sm-8">
														<input type="text" id="txtClave" name="clave" class="form-control obligatorio" value=""/>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Clave 2: </label>
													<div class="col-sm-8">
														<input type="text" id="txtClave2" name="clave2" class="form-control" value=""/>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Clave modulo: </label>
													<div class="col-sm-8">
														<input type="text" id="txtClaveModulo" name="clavemodulo" class="form-control obligatorio" value=""/>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Sucursal <small>(Obligatorio)</small>: </label>
													<div class="col-sm-8">
														<input type="text" id="txtSucursal" name="sucursal" class="form-control obligatorio" value=""/>
													</div>
												</div>
																
												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Direccion: </label>
													<div class="col-sm-8">
														<input type="text" id="txtDireccion" name="direccion" class="form-control" value=""/>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Ciudad: </label>
													<div class="col-sm-8">
														<input type="text" id="txtCiudad" name="ciudad" class="form-control" value=""/>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Descripcion: </label>
													<div class="col-sm-8">
														<textarea id="txtDescripcion" name="descripcion" class="form-control"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Proveedor <small>(Obligatorio)</small>: </label>
													<div class="col-sm-8">
														<select multiple="" id="cmbProveedor" class="select2 form-control" data-placeholder="Elige opcion">
																<?php $textoProveedores="0";foreach ($listaProveedores->result() as $kProv) { ?>
																	<option value="<?php echo $kProv->id; ?>"><?php echo $kProv->nombre; ?></option>
																<?php } ?>
														</select>
														<input type="hidden" id="txtProveedor" name="proveedor" value="">
													</div>
												</div>

												<div class="form-group">
													<label  class="col-sm-offset-4 col-sm-2 no-padding-right blue">
														<input id="checkActivo" name="status" class="ace" type="checkbox" checked/>
														<span class="lbl">Activo</span>
													</label>
													<label  class="col-sm-2 no-padding-right blue">
														<input id="checkAutoVenta" name="autoventa" class="ace" type="checkbox" checked/>
														<span class="lbl">Auto Venta</span>
													</label>
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

<?php $this->load->view("vCopyright"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

<?php $this->load->view("vFooter"); ?>

</body>
</html>

<script>

	window.onload = function()
	{
		<?php if($opcion=="editar" || $opcion=="ver") { ?>
			
			$("#cmbProveedor").change();

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
		var clave = $("#txtClave").val();
		var sucursal = $("#txtSucursal").val();
		var proveedor = $("#txtProveedor").val();

		if(clave=="")
		{
			dialogAvisoGlobal.show("Favor de escribir una clave", "alert alert-warning");
		}
		else if(sucursal==""){
			dialogAvisoGlobal.show("Favor de escribir una sucursal", "alert alert-warning");
		}
		else if(proveedor==""){
			dialogAvisoGlobal.show("Favor de seleccionar un proveedor", "alert alert-warning");
		}
		else
		{
			$.post("<?php echo LINKPROYECTO('GuardarSucursal') ?>", $("#form_savesucursal").serialize(), function(data){				
				if(data.trim()=="existe"){
					dialogAvisoGlobal.show("El codigo de sucursal ingresado ya esta registrado en otra sucursal", "alert alert-warning");
				}
				else if( parseFloat(data.trim())>0 ){
					dialogAvisoGlobal.show("Sucursal guardada correctamente", "alert alert-success");
					window.location = "<?php echo LINKPROYECTO('Sucursales') ?>";
				}else{
					dialogAvisoGlobal.show("Ocurrio un error al guardar la sucursal", "alert alert-danger");
				}
			});
		}
	});

	$("#cmbProveedor").change(function(event) {	
	 	var texto = $("#txtProveedor").val();
	 	texto = texto+","+$("#cmbProveedor").val();
	 	$("#txtProveedor").val($("#cmbProveedor").val());
	});

	$('.select2').css('width','300px').select2({allowClear:false});
	$('#select2-multiple-style .btn').on('click', function(e){
		var target = $(this).find('input[type=radio]');
		var which = parseInt(target.val());
		if(which == 2) $('.select2').addClass('tag-input-style');
			else $('.select2').removeClass('tag-input-style');
	});

	<?php if($opcion=="editar" || $opcion=="ver") { ?>
		function cargarDatosFormulario()
		{
			$("#txtId").val("<?php echo $sucursal->id; ?>");
			$("#txtClave").val("<?php echo $sucursal->clave; ?>");
			$("#txtClave2").val("<?php echo $sucursal->clave2; ?>");
			$("#txtClaveModulo").val("<?php echo $sucursal->clavemodulo; ?>");
			$("#txtSucursal").val("<?php echo $sucursal->sucursal; ?>");
			$("#txtDireccion").val("<?php echo $sucursal->direccion; ?>");
			$("#txtCiudad").val("<?php echo $sucursal->ciudad; ?>");
			$("#txtDescripcion").val("<?php echo $sucursal->descripcion; ?>");			
			$("#checkActivo").prop("checked", "<?php echo (($sucursal->status==1) ? true : false); ?>");
			$("#checkAutoVenta").prop("checked", "<?php echo isset($sucursal->autoventa) ? $sucursal->autoventa==1 ? true : false : false; ?>");

			var values = "<?php echo $sucursal->proveedores; ?>";			
			var multi = document.getElementById('cmbProveedor');

			multi.value = null; // Reset pre-selected options (just in case)
			var multiLen = multi.options.length;
			for (var i = 0; i < multiLen; i++) {
				if(values.includes(multi.options[i].text))
				{
					multi.options[i].selected = true;
				}
			}
		}
	<?php } ?>

	function disabledFormulario()
	{
		$("#txtId").prop("disabled", true);
		$("#txtClave").prop("disabled", true);
		$("#txtClave2").prop("disabled", true);
		$("#txtClaveModulo").prop("disabled", true);
		$("#txtSucursal").prop("disabled", true);
		$("#txtDireccion").prop("disabled", true);
		$("#txtCiudad").prop("disabled", true);
		$("#txtDescripcion").prop("disabled", true);
		$("#cmbProveedor").prop("disabled", true);
		$("#checkActivo").prop("disabled", true);
		$("#checkAutoVenta").prop("disabled", true);
	}

</script>