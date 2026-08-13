<?php 
$data['title']="LIZER Agregar Usuario";
$this->load->view("vHead",$data); 
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Proveedores</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-10">
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
							<div class="col-md-12 col-xs-12 col-sm-12" align="right">
								<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
								<a href="<?php echo LINKPROYECTO('Proveedores') ?>" class="btn btn-danger">REGRESAR</a>
							</div>
						</div>

						<div class="col-xs-12"><br></div>
						<div class="space-40"></div>
						
							
						<div class="col-xs-12">
							<form id="form_saveproveedor" action="<?php echo CCATALOGOS('saveNuevoProveedor'); ?>" method="POST">
								<div class="row">
									<div class="col-sm-12">
										<div class="row" align="center">
											<div class="col-xs-12">
												<h4 class="control-label green">NUEVO PROVEEDOR</h4>
											</div>
										</div>

										<div class="space-40"><br></div>
								
										<div class="row">
											<div class="col-xs-12">

												<input id="txtId" type="hidden" value="0" name="id" />

												<div class="form-horizontal" role="form">

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Nombre <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<input type="text" id="txtNombre" name="nombre" class="form-control obligatorio" value=""/>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Domicilio: </label>
														<div class="col-sm-8">
															<input type="text" id="txtDomicilio" name="domicilio" class="form-control decimal2" value=""/>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Comentario: </label>
														<div class="col-sm-8">
															<textarea id="txtComentario" name="comentario" rows="3" class="form-control"></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Telefono: </label>
														<div class="col-sm-8">
															<input type="text" id="txtTelefono" name="telefono" class="form-control entero" value=""/>
														</div>
													</div>

													<div class="form-group">														
														<label  class="col-sm-offset-4 col-sm-2 no-padding-right blue">  															
														<input id="checkActivo" name="status" class="ace" type="checkbox" checked />
														<span class="lbl">Activo</span>
														</label>
													</div>

													</div>
												</div>
											</div>
										</div>
								</div>
							</div>
						</form>														
					</div>
				</div>

				<div class="space-40"><br></div>
				<div class="col-md-12 col-xs-12 col-sm-12" align="center"><br>
					<button id="btnGuardar" class="btn btn-success btnGuardar">GUARDAR</button>								
				</div>
			</div>
		</div>
	</div>
</div>								

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
		var nombre = $("#txtNombre").val();

		if(nombre=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un nombre", "alert alert-warning");
		}		
		else
		{
			$.post("<?php echo LINKPROYECTO('GuardarProveedor') ?>", $("#form_saveproveedor").serialize(), function(data){				
				if( parseFloat(data.trim())>0 ){
					dialogAvisoGlobal.show("Proveedor guardado correctamente", "alert alert-success");
					window.location = "<?php echo LINKPROYECTO('Proveedores') ?>";
				}else{
					dialogAvisoGlobal.show("Ocurrio un error al guardar el proveedor", "alert alert-danger");
				}
			});
		}
	});

	<?php if($opcion=="editar" || $opcion=="ver") { ?>
		function cargarDatosFormulario()
		{
			$("#txtId").val("<?php echo $proveedor->id; ?>");
			$("#txtNombre").val("<?php echo $proveedor->nombre; ?>");
			$("#txtDomicilio").val("<?php echo $proveedor->domicilio; ?>");
			$("#txtComentario").val("<?php echo $proveedor->comentario; ?>");
			$("#txtTelefono").val("<?php echo $proveedor->telefono; ?>");
			$("#checkActivo").prop("checked", "<?php echo (($proveedor->status==1) ? true : false); ?>");
		}
	<?php } ?>

	function disabledFormulario()
	{
		$("#txtId").prop("disabled", true);
		$("#txtNombre").prop("disabled", true);
		$("#txtDomicilio").prop("disabled", true);
		$("#txtComentario").prop("disabled", true);
		$("#txtTelefono").prop("disabled", true);
		$("#checkActivo").prop("disabled", true);
	}

</script>