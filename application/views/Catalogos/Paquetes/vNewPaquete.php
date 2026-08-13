<?php 
$data['title']="LIZER Agregar Usuario";
$this->load->view("vHead",$data); 
$perfiles = array("ADMINISTRADOR", "SISTEMAS");
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Paquetes</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-md-12">
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
							<div class="col-md-12 col-xs-12 col-sm-12" align="right">
								<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
								<a href="<?php echo LINKPROYECTO('Paquetes') ?>" class="btn btn-danger">REGRESAR</a>
							</div>
						</div>
										
						<div class="col-md-12">
							<form id="form_saveproducto" action="<?php echo CCATALOGOS('saveNuevoProducto'); ?>" method="POST">
								<div class="row">
									<div class="col-md-12">

										<input id="txtTipo2" type="hidden" value="PAQUETE" name="tipo2" />
										<input id="txtSucursales" type="hidden" value="0" name="sucursales" />
										<input id="txtId" type="hidden" value="0" name="id" />

										<div class="row" align="center">
											<div class="col-xs-12"><h4 class="control-label green">NUEVO PAQUETE</h4></div>
										</div>
															
										<div class="space-40"><br></div>
											
										<div class="row">
											<div class="col-md-12">

												<div class="col-md-3 form-group">
													<label for="txtCodigo" class="control-label blue">Codigo:</label>
													<input type="text" id="txtCodigo" name="codigo" class="form-control" value=""/>
												</div>

												<div class="col-md-3 form-group">
													<label for="txtCodigoBarras" class="control-label blue">Codigo barras:</label>
													<input type="text" id="txtCodigoBarras" name="codigobarras" class="form-control" value=""/>
												</div>

												<div class="col-md-6 form-group">
													<label for="txtNombre" class="control-label blue">Nombre:</label>
													<input type="text" id="txtNombre" name="nombre" class="form-control" value=""/>
												</div>

												<div class="col-md-3 form-group">
													<label for="txtPrecio" class="control-label blue">Precio:</label>
													<input type="text" id="txtPrecio" name="precio" class="form-control decimal2" value=""/>
												</div>

												<div id="div_costo" class="col-md-3 form-group" hidden>
													<label for="txtCosto" class="control-label blue">Costo:</label>
													<input type="text" id="txtCosto" name="costo" class="form-control decimal2" value=""/>
												</div>

												<div class="col-md-3 form-group">
													<label for="txtIeps" class="control-label blue">IEPS:</label>
													<input type="text" id="txtIeps" name="ieps" class="form-control entero" value=""/>
												</div>

												<div class="col-md-3 form-group">
													<label for="txtIva" class="control-label blue">IVA:</label>
													<input type="text" id="txtIva" name="iva" class="form-control entero" value=""/>
												</div>

												<div class="col-md-3 form-group">
													<label for="cmbClasificacion" class="control-label blue">Clasificacion:</label>
													<select name="clasificacion" id="cmbClasificacion" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Selecciona Clasificacion)" required>
														<?php foreach ($listaClasificacionesProductos->result() as $kU) { ?>
															<option value="<?php echo $kU->id; ?>"><?php echo strtoupper($kU->nombre); ?></option>
														<?php } ?>
													</select>
												</div>

												<div class="col-md-3 form-group">
													<label for="txtTipo" class="control-label blue">Tipo:</label>
													<input type="text" id="txtTipo" name="tipo" class="form-control" value=""/>
												</div>

												<div class="col-md-3 form-group">
													<label for="cmbProveedor" class="control-label blue">Proveedor:</label>
													<select name="proveedor" id="cmbProveedor" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Selecciona Proveedor)" required>
														<?php foreach ($listaProveedores->result() as $kUX) { ?>
															<option value="<?php echo $kUX->id; ?>"><?php echo strtoupper($kUX->nombre); ?></option>
														<?php } ?>
													</select>
												</div>

												<div class="form-group col-md-3">
													<label for="cmbMarca" class="control-label blue">Marca:</label>
													<select name="idmarca" id="cmbMarca" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Selecciona Marca)" required>
														<?php foreach ($listamarcas->result() as $item) { ?>
															<option value="<?php echo $item->id.'-'.$item->idmarca.'-'.$item->nombre; ?>"><?php echo strtoupper($item->nombre); ?></option>
														<?php } ?>
													</select>
												</div>

												<!--<div class="col-md-12 form-group">
													<label for="cmbSucursales" class="control-label blue">Sucursales:</label>
													<select multiple="" id="cmbSucursales" class="select2 form-control" data-placeholder="Elige opcion">
														<?php foreach($listaSucursales->result() AS $item) { ?>
															<option value="<?php echo $item->id; ?>"><?php echo $item->sucursal; ?></option>
														<?php } ?>
													</select><input type="checkbox" id="check_sucursales" />Seleccionar todas
												</div>-->

												<div class="col-md-2 form-group">
													<label for="txtClaveSAT" class="control-label blue">Clave SAT:</label>
													<input type="text" id="txtClaveSAT" name="clavesat" class="form-control" value=""/>
												</div>

												<div class="col-md-2 form-group">
													<label for="txtFechaInicio" class="control-label blue">Fecha Inicio:</label>
													<input type="date" id="txtFechaInicio" name="fechainicio" class="form-control" value="<?php echo date('Y-m-d') ?>" />
												</div>

												<div class="col-md-2 form-group">
													<label for="txtFechaFinal" class="control-label blue">Fecha Final:</label>
													<input type="date" id="txtFechaFinal" name="fechafinal" class="form-control" value="<?php echo date('Y-m-d') ?>" />
												</div>

												<div class="col-md-2 form-group">
													<label for="txtLimiteMensual" class="control-label blue">Limite Mensual:</label>
													<input type="number" id="txtLimiteMensual" name="limitemensual" class="form-control" value="1"/>
												</div>

												<div class="col-md-4 form-group">
													<label  class="control-label blue">Activo</label>
													<input id="checkActivo" name="status" class="form-control" type="checkbox" checked />
												</div>

												<div class="form-group col-md-12">
													<label for="txtDescripcionLarga" class="control-label blue">Descripción Larga:</label>
													<textarea id="txtDescripcionLarga" name="descripcionlarga" class="form-control" rows="3"></textarea>
												</div>

												<div class="col-md-3 form-group">
													<label for="cmbTipoCombo" class="control-label blue">Tipo Combo:</label>
													<select id="cmbTipoCombo" name="tipocombo" class="form-control">
														<option value="D">Descuento</option>
														<option value="FG">Producto Gratis</option>
													</select>
												</div>

												<div class="col-md-1 form-group">
													<label  class="control-label blue">Audiencia</label>
													<input id="checkAudiencia" name="audiencia" class="form-control" type="checkbox" />
												</div>

											</div>
										</div>
									</div>
								</div><!-- /.col -->
							</div><!-- /.row -->
						</form>
					</div><!-- empieza div que contiene a la tabla -->
				</div><!--  termina div.col-xs-12 de la tabla clientes-->

				<div class="col-md-6">

					<div class="col-md-6">
						<select id="cmbProducto" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Productos)" required>
							<?php foreach ($listaProductos->result() as $item) { if($item->status == 1) { ?>
								<option value="<?php echo "$item->id|$item->codigo|$item->nombre"; ?>"><?php echo $item->codigo; ?></option>
							<?php }} ?>
						</select>
					</div>

					<div class="col-md-2">
						<input id="txtCantidad" type="number" class="form-control" placeholder="Cantidad"/>
					</div>

					<table id="table_componentes" class="table table-sm">
						<thead>
							<tr>
								<th colspan="4" talign="center" class="thead-light">Componentes del Paquete Inroute</th>
							</tr>
							<tr>
								<th hidden>idproducto</th>
								<th>codigo</th>
								<th>Producto</th>
								<th>Cantidad</th>
							</tr>
						</thead>

						<tbody>
						</tbody>
					</table>
				</div>

				<div class="col-md-6">

					<div class="col-md-6">
						<select id="cmbProductoBees" class="selectpicker form-control" data-style="btn-white" data-live-search="true" title="(Productos)" required>
							<?php foreach ($listaProductos->result() as $item) { if($item->status == 1) { ?>
								<option value="<?php echo "$item->id|$item->codigo|$item->nombre"; ?>"><?php echo $item->codigo; ?></option>
							<?php }} ?>
						</select>
					</div>

					<div id="div_cmbTipoPromo" class="col-md-3 form-group" hidden>
						<select id="cmbTipoPromo" class="form-control">
							<option value="P">Principal</option>
							<option value="G">Gratis</option>
						</select>
					</div>

					<div class="col-md-2">
						<input id="txtCantidadBees" type="number" class="form-control" placeholder="Cantidad"/>
					</div>

					<table id="table_componentes_bees" class="table table-sm">
						<thead>
							<tr>
								<th colspan="4" talign="center" class="thead-light">Componentes del Paquete Bees</th>
							</tr>
							<tr>
								<th hidden>idproducto</th>
								<th>codigo</th>
								<th>Producto</th>
								<th>Tipo</th>
								<th>Cantidad</th>
							</tr>
						</thead>

						<tbody>
						</tbody>
					</table>
				</div>

				<div class="col-md-12">
					<div class="col-md-6">

						<div class="col-md-10 form-group">
							<label for="cmbSucursal" class="control-label blue">Sucursales:</label>
							<select id="cmbSucursal" class="form-control">
								<?php foreach($listaSucursales->result() AS $item) { ?>
									<option value="<?php echo $item->id; ?>"><?php echo $item->sucursal; ?></option>
								<?php } ?>
							</select>							
						</div>

						<br/>
						<button id="btnAgregarSucursal" class="btn btn-primary">Agregar</button>

						<table id="tabla_sucursales" class="table table-striped table-hover">
							<thead>
								<tr>
									<th>Sucursal</th>
									<th>Cantidad Presupuesto</th>
									<th>Cantidad Vendidos</th>
									<th>Activo</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

					<div class="col-md-6">
						<div id="divExcel" class="mb-3" hidden>
							<label for="fileExcel" class="form-label">Seleccionar Excel</label>
							<input class="form-control" type="file" id="fileExcel" accept=".xlsx, .xls" />

							<table id="tabla_audiencia" class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Sucursal</th>
										<th>Clientes</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>

				</div>

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

<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script type="text/javascript">

	window.onload = function()
	{		
	}

	var items_sucursal = [];
	var items_audiencia = [];

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
		var fechainicio = $("#txtFechaInicio").val();
		var fechafinal = $("#txtFechaFinal").val();
		var limitemensual = $("#txtLimiteMensual").val();
		var clasificacion = $("#cmbClasificacion").val();
		var proveedor = $("#cmbProveedor").val();
		var idmarca = $("#cmbMarca").val();
		var sucursales = items_sucursal.length;//$("#cmbSucursales").val();

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
		else if(clasificacion==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una clasificacion", "alert alert-warning");
		}
		else if(fechainicio=="")
		{
			dialogAvisoGlobal.show("Favor de seleccionar una fecha de inicio", "alert alert-warning");
		}
		else if(fechafinal=="")
		{
			dialogAvisoGlobal.show("Favor de seleccionar una fecha de final", "alert alert-warning");
		}
		else if(proveedor==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar un proveedor", "alert alert-warning");
		}
		else if(idmarca==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una marca", "alert alert-warning");
		}
		else if(limitemensual == "" || limitemensual == 0)
		{
			dialogAvisoGlobal.show("Favor de poner un limite mensual", "alert alert-warning");
		}
		else if(sucursales==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar al menos una sucursal donde se aplicará el paquete", "alert alert-warning");
		}
		else if(document.getElementById("table_componentes").tBodies[0].rows.length == 0)
		{
			dialogAvisoGlobal.show("Favor agregar un componente del paquete", "alert alert-warning");
		}
		else
		{
			sucursales = "";
			for(var x in items_sucursal)
			{
				sucursales = sucursales + items_sucursal[x].idsucursal + ",";
			}
			sucursales = sucursales.slice(0, -1);
			
			$("#txtSucursales").val(sucursales);

			if($("#txtIva").val().trim()=="") $("#txtIva").val("0");
			if($("#txtIeps").val().trim()=="") $("#txtIeps").val("0");

			var myTableArray = [];
			var myTableArray_bees = [];

			$("#table_componentes tbody").each(function() {
				var arrayOfThisRow = [];
				var tableData = $(this).find('td');
				if (tableData.length > 0) {
					tableData.each(function() { arrayOfThisRow.push($(this).text()); });
					myTableArray.push(arrayOfThisRow);
				}
			});

			$("#table_componentes_bees tbody").each(function() {
				var arrayOfThisRow = [];
				var tableData = $(this).find('td');
				if (tableData.length > 0) {
					tableData.each(function() { arrayOfThisRow.push($(this).text()); });
					myTableArray_bees.push(arrayOfThisRow);
				}
			});

			$.post("<?php echo LINKPROYECTO('GuardarProducto') ?>", $("#form_saveproducto").serialize(), function(data){
				if(data.trim()=="existe"){
					dialogAvisoGlobal.show("El codigo de producto ingresado ya esta registrado en otro producto", "alert alert-warning");
				}
				else if( parseFloat(data.trim())>0 ){
					dialogAvisoGlobal.show("Producto guardado correctamente", "alert alert-success");

					var datos = {
						id: data.trim(),
						componentes: myTableArray,
						componentesbees: myTableArray_bees
					};

					for(var x in items_sucursal)
					{
						items_sucursal[x].idpaquete = data.trim();
					}

					$.post("<?php echo LINKPROYECTO('Catalogos/saveComponentesPaquete') ?>", datos, function(data){});
					$.post("<?php echo LINKPROYECTO('Catalogos/savePaquetesSucursal') ?>", {items_sucursal}, function(data){});
					$.post("<?php echo LINKPROYECTO('Catalogos/savePaquetesAudiencia') ?>", {idpaquete: data.trim(), codigo, items_audiencia}, function(data){});

					window.location = "<?php echo LINKPROYECTO('Paquetes') ?>";
				}else{
					dialogAvisoGlobal.show("Ocurrio un error al guardar el producto", "alert alert-danger");
				}
			});
		}
	});

	$('.select2').css('width','600px').select2({allowClear:false})
	$('#select2-multiple-style .btn').on('click', function(e){
		var target = $(this).find('input[type=radio]');
		var which = parseInt(target.val());
		if(which == 2) $('.select2').addClass('tag-input-style');
			else $('.select2').removeClass('tag-input-style');
	});

	$("#cmbTipoCombo").on("change", function()
	{
		var tipo = $(this).val();

		if(tipo == "D")
		{
			//$("#div_combodescuento").show();
			//$("#div_comboproductosgratis").hide();
			$("#div_cmbTipoPromo").hide();
			//$("#thTipo").hide();
		}
		else if(tipo == "FG")
		{
			//$("#div_combodescuento").hide();
			//$("#div_comboproductosgratis").show();
			$("#div_cmbTipoPromo").show();
			//$("#thTipo").show();
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
			$("#cmbClasificacion").val("<?php echo $producto->clasificacion; ?>");
			$("#txtTipo").val("<?php echo $producto->tipo; ?>");
			$("#cmbProveedor").val("<?php echo $producto->proveedor; ?>");
			$("#cmbMarca").val("<?php echo $producto->idmarcasistema.'-'.$producto->idmarca.'-'.$producto->marca; ?>");
			$("#cmbTipoCombo").val("<?php echo $producto->tipocombo; ?>");
			$("#txtClaveSAT").val("<?php echo $producto->clavesat; ?>");
			$("#txtFechaInicio").val("<?php echo date('Y-m-d', strtotime($producto->fechainicio)); ?>");
			$("#txtFechaFinal").val("<?php echo date('Y-m-d', strtotime($producto->fechafinal)); ?>");
			$("#txtLimiteMensual").val("<?php echo $producto->limitemensual; ?>");
			$("#txtDescripcionLarga").val("<?php echo $producto->descripcionlarga; ?>");
			$("#checkActivo").prop("checked", "<?php echo (($producto->status==1) ? true : false); ?>");
			$("#checkAudiencia").prop("checked", "<?php echo (($producto->audiencia==1) ? true : false); ?>");

			<?php if($producto->audiencia==1) { ?>
				$('#divExcel').show();
				items_audiencia = combinarDatosJson("<?php echo $producto->audencia_sucursales; ?>", "<?php echo $producto->audiencia_clientes; ?>");
				cargarTablaAudiencia(items_audiencia);
			<?php } ?>

			<?php foreach($componentes as $componente) { ?>
				var producto = "<?php echo $componente->idproducto.'|'.$componente->codigo.'|'.$componente->nombre; ?>";
				var cantidad = "<?php echo $componente->cantidad; ?>";

				addProducto(producto, cantidad);
			<?php } ?>

			<?php foreach($componentesbees as $componente) { ?>
				var producto = "<?php echo $componente->idproducto.'|'.$componente->codigo.'|'.$componente->nombre; ?>";
				var tipo = "<?php echo $componente->tipo; ?>";
				var cantidad = "<?php echo $componente->cantidad; ?>";

				addProductoBees(producto, tipo, cantidad);
			<?php } ?>

			<?php foreach($sucursales as $sucursal) { ?>
				var item = {
					idpaquete: "<?php echo $sucursal->idpaquete; ?>",
					idsucursal: "<?php echo $sucursal->idsucursal; ?>",
					sucursal: "<?php echo $sucursal->sucursal; ?>",
					cantidad: "<?php echo $sucursal->cantidad; ?>",
					cantidadvendidos: "<?php echo $sucursal->paquetesvendidos; ?>",
					activo: "<?php echo $sucursal->activo; ?>"
				};

				items_sucursal.push(item);
			<?php } ?>

			renderItemsTable();
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
		$("#checkAudiencia").prop("disabled", true);
	}

	$("#txtCantidad").on("keypress", function(e) {
        if (e.keyCode == 13) {
			var producto = $("#cmbProducto").val();
			var cantidad = $("#txtCantidad").val();

            addProducto(producto, cantidad);
        }
	});

	$("#btnAgregarSucursal").on("click", function()
	{
		var idsucursal = $("#cmbSucursal").val();
		addItemSucursal(idsucursal);
	});

	$("#checkAudiencia").on("change", function()
	{
		if ($(this).is(':checked'))
		{
			$('#divExcel').show();
		} 
		else
		{
			$('#divExcel').hide();
		}
	});

	document.getElementById('fileExcel').addEventListener('change', function(e) 
	{
		const file = e.target.files[0];
		if (!file) return;

		const reader = new FileReader();
		reader.onload = function(event) 
		{
			const data = new Uint8Array(event.target.result);
			const workbook = XLSX.read(data, { type: 'array' }); // Parse the workbook

			// Assume we want the first sheet
			const sheetName = workbook.SheetNames[0];
			const worksheet = workbook.Sheets[sheetName];

			// Convert the worksheet to a JSON array of objects
			const jsonOutput = XLSX.utils.sheet_to_row_object_array(worksheet);
			items_audiencia = jsonOutput;			

			cargarTablaAudiencia(items_audiencia)

			document.getElementById("fileExcel").value = "";
		};

		reader.onerror = function(ex)
		{
			console.error(ex);
		};

		reader.readAsArrayBuffer(file); // Read the file as an ArrayBuffer
	});

	function combinarDatosJson(sucursalesStr, codigosStr) 
	{
		// Convertir las cadenas en arrays
		const sucursales = sucursalesStr.split("|");
		const codigos = codigosStr.split("|");

		// Validar que tengan la misma cantidad de elementos
		if (sucursales.length !== codigos.length) {
			throw new Error("Las dos cadenas no tienen la misma cantidad de elementos.");
		}

		// Crear el JSON combinado
		const resultado = sucursales.map((sucursal, index) => ({
			sucursal: sucursal,
			codigocliente: codigos[index]
		}));

		return resultado;
	}

	function cargarTablaAudiencia(pDatos)
	{
		const agrupado = pDatos.reduce((acc, item) => {
			const suc = item.sucursal;

			if (!acc[suc]) {
				acc[suc] = 0;
			}

			acc[suc]++;

			return acc;
		}, {});

		var cadena = "";
		for (const sucursal in agrupado)
		{
			cadena = cadena + "<tr>";
			cadena = cadena + "<td>" + sucursal + "</td>";
			cadena = cadena + "<td>" + agrupado[sucursal] + "</td>";
			cadena = cadena + "</tr>";
		}

		$("#tabla_audiencia tbody").html(cadena);
	}

	function addProducto(pProducto, pCantidad)
	{
		if(pProducto == null || pProducto == "")
		{
			dialogAvisoGlobal.show("Favor de seleccionar un producto", "alert alert-warning");
			return;	
		}

		if(pCantidad == "0" || pCantidad == "")
		{
			dialogAvisoGlobal.show("Favor de ingresar una cantidad válida", "alert alert-warning");
			return;	
		}

		pProducto = pProducto.split('|');

		var cadena = "<tr>";
		cadena = cadena + "<td hidden>" + pProducto[0] + "</td>";
		cadena = cadena + "<td>" + pProducto[1] + "</td>";
		cadena = cadena + "<td>" + pProducto[2] + "</td>";
		cadena = cadena + "<td>" + pCantidad + "</td>";
		cadena = cadena + "<td><button onclick='deleteRowComponente(this)'>-</button></td>";
		cadena = cadena + "</tr>";

		$("#table_componentes tbody").append(cadena);
	}

	function deleteRowComponente(e)
	{
		$(e).parent().parent().remove();
	}

	$("#txtCantidadBees").on("keypress", function(e) {
        if (e.keyCode == 13) {
			var producto = $("#cmbProductoBees").val();
			var tipo = $("#div_cmbTipoPromo").is(":hidden") ? "0" : $("#cmbTipoPromo").val();
			var cantidad = $("#txtCantidadBees").val();

            addProductoBees(producto, tipo, cantidad);
        }
	});

	function addProductoBees(pProducto, pTipo, pCantidad)
	{
		if(pProducto == null || pProducto == "")
		{
			dialogAvisoGlobal.show("Favor de seleccionar un producto", "alert alert-warning");
			return;	
		}

		if(pCantidad == "0" || pCantidad == "")
		{
			dialogAvisoGlobal.show("Favor de ingresar una cantidad válida", "alert alert-warning");
			return;	
		}

		pProducto = pProducto.split('|');

		var cadena = "<tr>";
		cadena = cadena + "<td hidden>" + pProducto[0] + "</td>";
		cadena = cadena + "<td>" + pProducto[1] + "</td>";
		cadena = cadena + "<td>" + pProducto[2] + "</td>";
		if(pTipo == "0")
		{
			cadena = cadena + "<td>D</td>";
		}
		else
		{
			cadena = cadena + "<td>" + pTipo + "</td>";
		}
		cadena = cadena + "<td>" + pCantidad + "</td>";
		cadena = cadena + "<td><button onclick='deleteRowComponenteBees(this)'>-</button></td>";
		cadena = cadena + "</tr>";

		$("#table_componentes_bees tbody").append(cadena);
	}

	function deleteRowComponenteBees(e)
	{
		$(e).parent().parent().remove();
	}

	function addItemSucursal(idsucursal)
	{
		var existe = items_sucursal.filter(obj => {
			return obj.idsucursal == idsucursal
		});
		
		if(existe.length > 0)
		{
			return;
		}
		else
		{
			var sucursal = $("#cmbSucursal option:selected").text();

			var item = {
				idpaquete: 0,
				idsucursal: idsucursal,
				sucursal: sucursal,
				cantidad: 0,
				cantidadvendidos: 0,
				activo: 1
			};

			items_sucursal.push(item);
		}

		renderItemsTable();
	}

	function renderItemsTable()
	{
		var cadena = "";

		for(var x in items_sucursal)
		{
			cadena = cadena + "<tr>";
			cadena = cadena + "<td>" + items_sucursal[x].sucursal + "</td>";
			cadena = cadena + "<td><input <?php if($opcion=="ver") {echo "readonly";} ?> type='number' onkeyup='addCantidad(" + items_sucursal[x].idsucursal + ", this)' value='" + items_sucursal[x].cantidad + "' /></td>";
			cadena = cadena + "<td>" + items_sucursal[x].cantidadvendidos + "</td>";

			<?php if($opcion=="editar") { ?>
			if(items_sucursal[x].activo == 1)
				cadena = cadena + "<td><input type='checkbox' onchange='changeActivo(" + items_sucursal[x].idsucursal + ", this)' checked /></td>";
			else
				cadena = cadena + "<td><input type='checkbox' onchange='changeActivo(" + items_sucursal[x].idsucursal + ", this)' /></td>";
			<?php } else { ?>
			if(items_sucursal[x].activo == 1)
				cadena = cadena + "<td style='color:green'>SI</td>";
			else
				cadena = cadena + "<td style='color:red'>NO</td>";
				//cadena = cadena + "<td>&nbsp;</td>";
			<?php } ?>
			
			<?php if($opcion == "nuevo") { ?>
			cadena = cadena + "<td><button onclick='removeItem(" + items_sucursal[x].idsucursal + ")'>-</button></td>";
			<?php } ?>

			cadena = cadena + "</tr>";
		}

		$("#tabla_sucursales tbody").html(cadena);
	}

	function removeItem(idsucursal)
	{
		var newarray = items_sucursal.filter(obj => {
			return obj.idsucursal != idsucursal
		});

		items_sucursal = newarray;

		renderItemsTable();
	}

	function addCantidad(idsucursal, elemento)
	{
		var index = items_sucursal.findIndex((obj => obj.idsucursal == idsucursal));
		var cantidaddespues = $(elemento).val();

		items_sucursal[index].cantidad = cantidaddespues;

		//renderItemsTable();
	}

	function changeActivo(idsucursal, elemento)
	{
		var index = items_sucursal.findIndex((obj => obj.idsucursal == idsucursal));

		items_sucursal[index].activo = $(elemento).is(':checked') ? 1 : 0;
	}
</script>