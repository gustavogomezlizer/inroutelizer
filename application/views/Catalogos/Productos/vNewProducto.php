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
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Usuarios</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-md-12">
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-md-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
							<div class="col-md-12" align="right">
								<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
								<a href="<?php echo LINKPROYECTO('Productos') ?>" class="btn btn-danger">REGRESAR</a>
							</div>
						</div>
										
						<div class="col-md-12">
							<form id="form_saveproducto" action="<?php echo CCATALOGOS('saveNuevoProducto'); ?>" method="POST">
								<div class="row">
									<div class="col-sm-12">

										<input id="txtId" type="hidden" value="0" name="id" />

										<div class="row" align="center">
											<div class="col-md-12"><h4 class="control-label green">NUEVO PRODUCTO</h4></div>
										</div>
											
										<div class="row">
											<div class="col-md-12">

													<div class="form-group col-md-2">
														<label for="txtCodigo" class="control-label blue">Codigo:</label>
														<input type="text" id="txtCodigo" name="codigo" class="form-control" />
													</div>

													<div class="form-group col-md-2">
														<label for="txtCodigoBarras" class="control-label blue">Codigo barras:</label>
														<input type="text" id="txtCodigoBarras" name="codigobarras" class="form-control" />
													</div>

													<div class="form-group col-md-8">
														<label for="txtNombre" class="control-label blue">Nombre:</label>
														<input type="text" id="txtNombre" name="nombre" class="form-control" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtPrecio" class="control-label blue">Precio:</label>
														<input type="text" id="txtPrecio" name="precio" class="form-control decimal2" />
													</div>

													<div id="div_costo" class="form-group col-md-3" hidden>
														<label for="txtCosto" class=" control-label blue">Costo:</label>
														<input type="text" id="txtCosto" name="costo" class="form-control decimal2" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtIeps" class="control-label blue">IEPS:</label>
														<input type="text" id="txtIeps" name="ieps" class="form-control"/>
													</div>

													<div class="form-group col-md-3">
														<label for="txtIva" class="control-label blue">IVA:</label>
														<input type="text" id="txtIva" name="iva" class="form-control" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtContenedorNombre" class="control-label blue">Contenedor Nombre:</label>
														<input type="text" id="txtContenedorNombre" name="contenedornombre" class="form-control" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtContenedorTamano" class="control-label blue">Contenedor Tamaño:</label>
														<input type="text" id="txtContenedorTamano" name="contenedortamano" class="form-control" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtContenedorUnidadMedida" class="control-label blue">Contenedor Unidad Medida:</label>
														<input type="text" id="txtContenedorUnidadMedida" name="contenedorunidadmedida" class="form-control" />
													</div>

													<!--<div class="form-group col-md-3">
														<label for="cmbContenedorUnidadMedida" class="control-label blue">Contenedor Unidad Medida:</label>
														<select name="contenedorunidadmedida" id="cmbContenedorUnidadMedida" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Selecciona una Unidad de Medida)" required>
															<?php /*foreach ($listaunidadesmedida->result() as $kU) { ?>
																<option value="<?php echo $kU->id; ?>"><?php echo strtoupper($kU->nombre); ?></option>
															<?php }*/ ?>
														</select>
													</div>-->

													<div class="form-group col-md-3">
														<label for="txtContenedorRetornable" class="control-label blue">Contenedor Retornable:</label>
														<input type="checkbox" id="txtContenedorRetornable" name="contenedorretornable" class="form-control" />
													</div>

													<!--<div class="form-group col-md-3">
														<label for="cmbPaqueteTipo" class="control-label blue">Paquete Tipo:</label>
														<select name="paqueteid" id="cmbPaqueteTipo" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Selecciona Paquete Tipo)" required>
															<?php foreach ($listatipopaquetes->result() as $item) { ?>
																<option value="<?php echo $item->id; ?>"><?php echo strtoupper($item->nombre); ?></option>
															<?php } ?>
														</select>
													</div>-->

													<div class="form-group col-md-3">
														<label for="txtPaqueteId" class="control-label blue">Paquete ID:</label>
														<input type="text" id="txtPaqueteId" name="paqueteid" class="form-control" value="1" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtPaqueteNombre" class="control-label blue">Paquete Nombre:</label>
														<input type="text" id="txtPaqueteNombre" name="paquetenombre" class="form-control" value="1" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtPaqueteCantidad" class="control-label blue">Paquete Cantidad:</label>
														<input type="text" id="txtPaqueteCantidad" name="paquetecantidad" class="form-control" value="1" />
													</div>

													<div class="form-group col-md-3">
														<label for="txtPaqueteCantidadProducto" class="control-label blue">Paquete Cantidad Producto:</label>
														<input type="text" id="txtPaqueteCantidadProducto" name="paquetecantidadproducto" class="form-control" value="1" />
													</div>

													<div class="form-group col-md-6">
														<label for="cmbClasificacion" class="control-label blue">Clasificacion:</label>
														<select name="clasificacion" id="cmbClasificacion" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Selecciona Clasificacion)" required>
															<?php foreach ($listaClasificacionesProductos->result() as $kU) { ?>
																<option value="<?php echo $kU->id; ?>"><?php echo strtoupper($kU->nombre); ?></option>
															<?php } ?>
														</select>
													</div>

													<div class="form-group col-md-6">
														<label for="txtTipo" class="control-label blue">Tipo:</label>
														<input type="text" id="txtTipo" name="tipo" class="form-control" />
													</div>

													<div class="form-group col-md-4">
														<label for="cmbProveedor" class="control-label blue">Proveedor:</label>
														<select name="proveedor" id="cmbProveedor" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Selecciona Proveedor)" required>
															<?php foreach ($listaProveedores->result() as $kUX) { ?>
																<option value="<?php echo $kUX->id; ?>"><?php echo strtoupper($kUX->nombre); ?></option>
															<?php } ?>
														</select>
													</div>

													<!--<div class="form-group col-md-4">
														<label for="txtIdMarca" class="control-label blue">ID Marca:</label>
														<input type="text" id="txtIdMarca" name="idmarca" class="form-control" />
													</div>

													<div class="form-group col-md-4">
														<label for="txtMarca" class="control-label blue">Marca:</label>
														<input type="text" id="txtMarca" name="marca" class="form-control" />
													</div>-->

													<div class="form-group col-md-4">
														<label for="cmbMarca" class="control-label blue">Marca:</label>
														<select name="idmarca" id="cmbMarca" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Selecciona Marca)" required>
															<?php foreach ($listamarcas->result() as $item) { ?>
																<option value="<?php echo $item->id.'-'.$item->idmarca.'-'.$item->nombre; ?>"><?php echo strtoupper($item->nombre); ?></option>
															<?php } ?>
														</select>
													</div>

													<div class="form-group col-md-12">
														<label for="txtClaveSAT" class="control-label blue">Clave SAT:</label>
														<input type="text" id="txtClaveSAT" name="clavesat" class="form-control" />
													</div>

													<div class="form-group col-md-12">
														<label for="txtDescripcionLarga" class="control-label blue">Descripción Larga:</label>
														<textarea id="txtDescripcionLarga" name="descripcionlarga" class="form-control" rows="3"></textarea>
													</div>

													<div class="form-group col-md-3">
														<label for="checkActivo" class="control-label blue">Activo</label>
														<input type="checkbox" id="checkActivo" name="status" class="form-control" checked />
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

		<!--</div>
	</div>
</div>-->

<?php $this->load->view("vCopyright"); ?>

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
	<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>

<?php $this->load->view("vFooter"); ?>
</body>
</html>

<script type="text/javascript">	

	window.onload = function()
	{		
	}

	var perfil = "<?php echo GETPERFILUSUARIO();?>";

	if(perfil == "ADMINISTRADOR" || perfil == "SISTEMAS")
	{
		$("#div_costo").show();
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
		var codigo = $("#txtCodigo").val();
		var nombre = $("#txtNombre").val();
		var precio = $("#txtPrecio").val();
		var contenedornombre = $("#txtContenedorNombre").val();
		var contenedortamano = $("#txtContenedorTamano").val();
		var contenedorunidadmedida = $("#txtContenedorUnidadMedida").val();
		var paqueteid = $("#txtPaqueteId").val();
		var paquetenombre = $("#txtPaqueteNombre").val();
		var paquetecantidad = $("#txtPaqueteCantidad").val();
		var paquetecantidadproducto = $("#txtPaqueteCantidadProducto").val();
		var clasificacion = $("#cmbClasificacion").val();
		var proveedor = $("#cmbProveedor").val();
		//var idmarcasistema = $("#cmbMarca").val().split('-')[0];
		var idmarca = $("#cmbMarca").val();
		//var marca = $("#cmbMarca option:selected").text();

		if(codigo=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un codigo", "alert alert-warning");
		}
		else if(nombre=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un nombre", "alert alert-warning");
		}
		else if(precio=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un precio", "alert alert-warning");
		}
		else if(contenedornombre=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un nombre de contenedor", "alert alert-warning");
		}
		else if(contenedortamano=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un tamaño de contenedor", "alert alert-warning");
		}
		else if(contenedorunidadmedida=="")
		{
			dialogAvisoGlobal.show("Favor de escribir una unidad de menida de contenedor", "alert alert-warning");
		}
		else if(paqueteid=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un ID de paquete", "alert alert-warning");
		}
		else if(paquetenombre=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un nombre de paquete", "alert alert-warning");
		}
		else if(paquetecantidad=="")
		{
			dialogAvisoGlobal.show("Favor de escribir un cantidad de paquete", "alert alert-warning");
		}
		else if(paquetecantidadproducto=="")
		{
			dialogAvisoGlobal.show("Favor de escribir una cantidad de productos de paquete", "alert alert-warning");
		}
		else if(clasificacion==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una clasificacion", "alert alert-warning");
		}
		else if(proveedor==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar un proveedor", "alert alert-warning");
		}
		else if(idmarca==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una marca", "alert alert-warning");
		}
		else
		{			
			if($("#txtIva").val().trim()=="") $("#txtIva").val("0");
			if($("#txtIeps").val().trim()=="") $("#txtIeps").val("0");

			$.post("<?php echo LINKPROYECTO('GuardarProducto') ?>", $("#form_saveproducto").serialize(), function(data){
				if(data.trim()=="existe"){
					dialogAvisoGlobal.show("El codigo de producto ingresado ya esta registrado en otro producto", "alert alert-warning");
				}
				else if( parseFloat(data.trim())>0 ){
					dialogAvisoGlobal.show("Producto guardado correctamente", "alert alert-success");
					window.location = "<?php echo LINKPROYECTO('Productos') ?>";
				}else{
					dialogAvisoGlobal.show("Ocurrio un error al guardar el producto", "alert alert-danger");
				}
			});
		}
	});

	<?php if($opcion=="editar" || $opcion=="ver") { ?>
		function cargarDatosFormulario()
		{
			$("#txtId").val("<?php echo $producto->id; ?>");
			$("#txtCodigo").val("<?php echo $producto->codigo; ?>");
			$("#txtCodigoBarras").val("<?php echo $producto->codigobarras; ?>");
			$("#txtNombre").val("<?php echo $producto->nombre; ?>");
			$("#txtPrecio").val("<?php echo $producto->precio; ?>");
			$("#txtCosto").val("<?php echo $producto->costo; ?>");
			$("#txtIeps").val("<?php echo $producto->ieps; ?>");
			$("#txtIva").val("<?php echo $producto->iva; ?>");
			$("#txtContenedorNombre").val("<?php echo $producto->contenedornombre; ?>");
			$("#txtContenedorTamano").val("<?php echo $producto->contenedortamano; ?>");
			$("#txtContenedorUnidadMedida").val("<?php echo $producto->contenedorunidadmedida; ?>");
			$("#txtContenedorRetornable").prop("checked", "<?php echo (($producto->contenedorretornable==1) ? true : false); ?>");
			$("#txtPaqueteId").val("<?php echo $producto->paqueteid; ?>");
			$("#txtPaqueteNombre").val("<?php echo $producto->paquetenombre; ?>");
			$("#txtPaqueteCantidad").val("<?php echo $producto->paquetecantidad; ?>");
			$("#txtPaqueteCantidadProducto").val("<?php echo $producto->paquetecantidadproducto; ?>");
			$("#cmbClasificacion").val("<?php echo $producto->clasificacion; ?>");
			$("#txtTipo").val("<?php echo $producto->tipo; ?>");
			$("#cmbProveedor").val("<?php echo $producto->proveedor; ?>");
			//$("#txtIdMarca").val("<?php echo $producto->idmarca; ?>");
			//$("#txtMarca").val("<?php echo $producto->marca; ?>");
			$("#cmbMarca").val("<?php echo $producto->idmarcasistema.'-'.$producto->idmarca.'-'.$producto->marca; ?>");
			$("#txtClaveSAT").val("<?php echo $producto->clavesat; ?>");
			$("#txtDescripcionLarga").val("<?php echo $producto->descripcionlarga; ?>");
			$("#checkActivo").prop("checked", "<?php echo (($producto->status==1) ? true : false); ?>");
		}
	<?php } ?>

	function disabledFormulario()
	{
		$("#txtId").prop("disabled", true);
		$("#txtCodigo").prop("disabled", true);
		$("#txtCodigoBarras").prop("disabled", true);
		$("#txtNombre").prop("disabled", true);
		$("#txtPrecio").prop("disabled", true);
		$("#txtCosto").prop("disabled", true);
		$("#txtIeps").prop("disabled", true);
		$("#txtIva").prop("disabled", true);
		$("#cmbClasificacion").prop("disabled", true);
		$("#txtTipo").prop("disabled", true);
		$("#cmbProveedor").prop("disabled", true);
		$("#cmbMarca").prop("disabled", true);
		$("#txtClaveSAT").prop("disabled", true);
		$("#checkActivo").prop("disabled", true);
	}

</script>