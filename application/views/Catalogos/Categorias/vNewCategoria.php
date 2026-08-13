<?php 
$data['title']="LIZER Agregar Usuario";
$this->load->view("vHead",$data); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i></small>
				</h1>
			</div>

			<div class="row">
				<div class="col-xs-10">
					<div class="row">
						<div class="col-xs-12">
							<div class="col-md-12 col-xs-12 col-sm-12" align="right">
								<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
								<a href="<?php echo LINKPROYECTO('Categorias') ?>" class="btn btn-danger">REGRESAR</a>
							</div>
						</div>

						<div class="col-xs-12"><br></div>
						<div class="space-40"></div>
									
										
						<div class="col-xs-12">
							<form id="form_savecategoria" action="<?php echo CCATALOGOS('saveNuevaCategoria'); ?>" method="POST">
								<div class="row">
									<div class="col-sm-12">
									
										<div class="row" align="center">
											<div class="col-xs-12">
												<h4 class="control-label green">NUEVA CLASIFICACION DE PRODUCTO</h4>
											</div>
										</div>

										<div class="space-40"><br></div>
											
										<div class="row">
											<div class="col-xs-12">
												<div class="form-horizontal" role="form">

													<input id="txtId" type="hidden" value="0" name="id" />

													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Nombre <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<input type="text" id="txtNombre" name="nombre" class="form-control obligatorio" value=""/>
														</div>
													</div>
																
													<div class="form-group">
														<label class="col-sm-4 control-label no-padding-right blue" for="form-field-1"> Proveedor <small>(Obligatorio)</small>: </label>
														<div class="col-sm-8">
															<select id="cmbProveedor" name="clientePro" class="selectpicker form-control" data-style="btn-white">
																<option value="0">(Seleccione un proveedor)</option>
																<?php foreach ($listaProveedores->result() as $kUX) { ?>
																	<option value="<?php echo $kUX->id; ?>"><?php echo strtoupper($kUX->nombre); ?></option>
																<?php } ?>
															</select>															
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
		var proveedor = $("#cmbProveedor").val();

		if(nombre=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un nombre", "alert alert-warning");
		}else if(proveedor==0){
			dialogAvisoGlobal.show("Favor de seleccionar un proveedor", "alert alert-warning");
		}else{
			$.post("<?php echo LINKPROYECTO('GuardarCategoria') ?>", $("#form_savecategoria").serialize(), function(data){				
				if( parseFloat(data.trim())>0 ){
					dialogAvisoGlobal.show("Categoria guardada correctamente", "alert alert-success");
					window.location = "<?php echo LINKPROYECTO('Categorias') ?>";
				}else{
					dialogAvisoGlobal.show("Ocurrio un error al guardar la categoria", "alert alert-danger");
				}
			});
		}
	});

	<?php if($opcion=="editar" || $opcion=="ver") { ?>
		function cargarDatosFormulario()
		{
			$("#txtId").val("<?php echo $categoria->id; ?>");
			$("#txtNombre").val("<?php echo $categoria->nombre; ?>");
			$("#cmbProveedor").val("<?php echo $categoria->clientePro; ?>");			
			$("#checkActivo").prop("checked", "<?php echo (($categoria->status==1) ? true : false); ?>");
		}
	<?php } ?>

	function disabledFormulario()
	{
		$("#txtId").prop("disabled", true);
		$("#txtNombre").prop("disabled", true);
		$("#cmbProveedor").prop("disabled", true);
		$("#checkActivo").prop("disabled", true);
	}

</script>