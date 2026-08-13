<?php 
$data['title']="LIZER Reportes-Visitas";



$this->load->view("vHead",$data); ?>
<style>
	.tamano{
		width: 90% !important;
	}
</style>
<?php $this->load->view("vMenu"); ?>

			<div id="principal" class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Administracion / Captura de NC
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->									

									<div class="row">
										<input id="txtId" type="hidden" value="0" />
										<div class="col-md-3">
											<label for="txtFechaRecepcion">Fecha de Recepción</label>
											<input id="txtFechaRecepcion" type="date" class="form-control" />
										</div>
										<div class="col-md-3">
											<label for="txtFechaPago">Fecha de Pago</label>
											<input id="txtFechaPago" type="date" class="form-control" />
										</div>
										<div class="col-md-3">
											<label for="cmbNegocio">Negocio</label>
											<select name="cmbNegocio" id="cmbNegocio" class="form-control">
												<option value="0">[SELECCIONE UN NEGOCIO]</option>
												<?php foreach($proveedores as $proveedor) { ?>
													<option value="<?php echo $proveedor->id; ?>"><?php echo $proveedor->nombre; ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-md-3"><label for="cmbTipoNc">Tipo</label><br>
											<select name="cmbTipoNc" id="cmbTipoNc"  class="form-control">
												<option value="0">[SELECCIONE UNA TIPO DE NC]</option>
												<option value="NC POR DPP 2%">NC POR DPP 2%</option>
												<option value="NC POR DPP 1%">NC POR DPP 1%</option>
												<option value="NC POR FALTANTE DE MCIA">NC POR FALTANTE DE MCIA</option>
												<option value="NC POR APOYO A RUTAS F&B">NC POR APOYO A RUTAS F&B</option>
												<option value="NC POR APOYO A RUTAS IMPULSO">NC POR APOYO A RUTAS IMPULSO</option>
												<option value="NC POR PROMOCIONES">NC POR PROMOCIONES</option>
												<option value="NC POR DEVOLUCIONES">NC POR DEVOLUCIONES</option>
												<option value="NC APOYO A SELLOUT">NC APOYO A SELLOUT</option>
												<option value="NC OTROS">NC OTROS</option>
											</select>
										</div>
										<div class="col-md-3"><label for="cmbSucursalDpp">Sucursal</label><br>
											<select name="cmbSucursalDpp" id="cmbSucursalDpp"  class="form-control">
												<option value="0">[SELECCIONE UNA SUCURSAL]</option>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value="<?php echo $item->id; ?>"><?php echo $item->sucursal; ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-md-3">
											<label for="txtFactura">Factura</label>
											<input id="txtFactura" type="text" class="form-control" />
										</div>

										<div class="col-md-3">
											<label for="txtImporteFactura">Importe Factura</label>
											<input id="txtImporteFactura" type="number" class="form-control" />
										</div>
										<div class="col-md-3">
											<label for="txtImporteNc">Importe NC</label>
											<input id="txtImporteNc" type="number" class="form-control" />
										</div>

										<div class="col-md-3">
											<label for="txtImporteTotal">Importe Total</label>
											<input id="txtImporteTotal" type="text" class="form-control" />
										</div>
										<div class="col-md-3">
											<label for="txtNumeroNotaCredito">No. Nota Credito</label>
											<input id="txtNumeroNotaCredito" type="text" class="form-control" />
										</div>
									</div>

									<div align="center"><br/>
										<button id="btnGuardarDpp" class="btn btn-success">Guardar</button>
									</div>

								</div><!--  termina div.row de la tabla clientes-->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

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
		<?php if(isset($infonc)) { ?>
			$("#txtId").val("<?php echo $infonc->id; ?>");
			$("#txtFechaRecepcion").val("<?php echo $infonc->fecha_recepcion; ?>");
			$("#txtFechaPago").val("<?php echo $infonc->fecha_pago; ?>");
			$("#cmbNegocio").val("<?php echo $infonc->negocio; ?>");
			$("#cmbTipoNc").val("<?php echo $infonc->tipo; ?>");
			$("#cmbSucursalDpp").val("<?php echo $infonc->idsucursal; ?>");
			$("#txtFactura").val("<?php echo $infonc->factura; ?>");
			$("#txtImporteFactura").val("<?php echo $infonc->importe_factura; ?>");
			$("#txtImporteNc").val("<?php echo $infonc->importe_nc; ?>");
			$("#txtImporteTotal").val("<?php echo $infonc->importe_total; ?>");
			$("#txtNumeroNotaCredito").val("<?php echo $infonc->numero_nc; ?>");
		<?php } ?>
	}

	var myTable = 
	$('#table_visitas')
	.DataTable({
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},
			"pageLength": -1,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
	});

	$("#btnGuardarDpp").on("click", function(){
		guardarDpp();
	});

	function guardarDpp()
	{
		var id = $("#txtId").val();
		var fecharecepcion = $("#txtFechaRecepcion").val();
		var fechapago = $("#txtFechaPago").val();
		var negocio = $("#cmbNegocio").val();
		var cmbSucursal = $("#cmbSucursalDpp").val();
		var cmbTipo = $("#cmbTipoNc").val();
		var factura = $("#txtFactura").val();
		var importefactura = $("#txtImporteFactura").val();
		var importenc = $("#txtImporteNc").val();
		var importetotal = $("#txtImporteTotal").val();
		var numeronc = $("#txtNumeroNotaCredito").val();

		if(fecharecepcion == "")
		{
			alert("Favor de capturar una fecha de recepción");
			return;
		}

		if(fechapago == "")
		{
			alert("Favor de capturar una fecha de pago");
			return;
		}

		if(negocio == "")
		{
			alert("Favor de capturar un negocio");
			return;
		}

		if(cmbSucursal == "0")
		{
			alert("Favor de seleccionar una sucursal");
			return;
		}

		if(cmbTipo == "0")
		{
			alert("Favor de seleccionar un tipo de Nota de Credito");
			return;
		}

		if(importefactura == "")
		{
			alert("Favor de capturar un importe de factura");
			return;
		}

		if(factura == "")
		{
			alert("Favor de capturar un número de factura");
			return;
		}

		if(importenc == "")
		{
			alert("Favor de capturar el Importe NC");
			return;
		}

		if(importetotal == "")
		{
			alert("Favor de capturar el importe de pago");
			return;
		}

		if(numeronc == "")
		{
			alert("Favor de capturar numero depp");
			return;
		}

		var datos = {
			id,
			fecharecepcion,
			fechapago,
			negocio,
			cmbSucursal,
			cmbTipo,
			factura,
			importefactura,
			importenc,
			importetotal,
			numeronc
		};

		$.post("<?php echo LINKPROYECTO('Administracion/saveNc') ?>", datos, function(data){
			window.location.reload();
		}).always(function(){

		});
	}
</script>