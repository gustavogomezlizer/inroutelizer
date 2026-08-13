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
									Reportes / Reporte de NC
								</small>
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row">
									<div class="col-xs-2">
										<label for="txtFechaDe">Inicio</label>
										<input id="txtFechaDe" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
									</div>
									<div class="col-xs-2">
										<label for="txtFechaA">Final</label>
										<input id="txtFechaA" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
									</div>
									<div class="col-xs-2"><label for="">Sucursal</label><br>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<option value="0">TODAS</option>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value="<?php echo $item->id; ?>"><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="col-md-2"><label for="">Negocio</label><br>
										<select name="cmbNegocio" id="cmbNegocio">
											<option value="0">Todos</option>
											<?php foreach($proveedores as $proveedor) { ?>
												<option value="<?php echo $proveedor->id; ?>"><?php echo $proveedor->nombre; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-md-3">
										<label for="cmbTipoNcFiltro">Tipo</label><br>
										<select name="cmbTipoNcFiltro" id="cmbTipoNcFiltro"  class="select2 form-control" multiple="">
											<!--<option value="0">TODAS</option>-->
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
								</div>

									<div class="row">
									<div class="col-xs-12"><br>
									</div>
									</div>
									</div>
									<div class="row"><div class="col-xs-12"><hr></div></div>
									
									<div class="row">
										<div class="col-xs-12">
											<div class="clearfix">
										</div>

										<div class="clearfix col-md-12" align="right">
											<div class="pull-right">
												<!--<button id="btnNuevo" class="btn btn-warning">Nuevo</button>-->
												<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
												<button class="btn btn-success btnActualizar">Actualizar</button> <br><br>
											</div>
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container">
												<!--<button class="btn btn-white btnSacarTabla"><i class="ace-icon fa fa-file-excel-o bigger-130"></i>Generar Excel</button>-->
											</div>
										</div>
										</div>
										</div>
										<div class="col-xs-12">
										<div class="table-header">
											Listado
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="table_visitas" width="100%" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="10%" style="text-align: center;">Fecha Recepción</th>
														<th width="10%" style="text-align: center;">Fecha Pago</th>
														<th width="10%" style="text-align: center;">Negocio</th>
														<th width="10%" style="text-align: center;">Sucursal</th>
														<th width="10%" style="text-align: center;">No. Factura</th>
														<th width="10%" style="text-align: center;">Importe Factura</th>
														<th width="10%" style="text-align: center;">Tipo</th>
														<th width="10%" style="text-align: center;">Importe NC</th>
														<th width="10%" style="text-align: center;">Importe Total</th>
														<th width="10%" style="text-align: center;">Numero NC</th>
														<th width="10%" style="text-align: center;">Acciones</th>
													</tr>
												</thead>
												<tbody>
														
												</tbody>
												<tfoot>
													<tr>
														<td colspan="5"><b>Totales:</b></td>
														<td align="right"><b id="lblTotalImporteFactura">0</b></td>
														<td>&nbsp;</td>
														<td align="right"><b id="lblTotalImporteNc">0</b></td>
														<td align="right"><b id="lblTotalImporteTotal">0</b></td>
														<!--<td align="right"><b id="lblTotalIngresos">0</b></td>
														<td align="right"><b id="lblTotalCosto">0</b></td>
														<td align="right"><b id="lblTotalUtilidadBruta">0</b></td>
														<td align="right"><b id="lblTotalGastos">0</b></td>
														<td align="right"><b id="lblTotalPorcentajeGastos">0</b></td>
														<td align="right"><b id="lblTotalImporteMargen">0</b></td>
														<td align="right"><b id="lblTotalPorcentajeMargen">0</b></td>
														<td align="right"><b id="lblTotalUtilidadNeta">0</b></td>-->
													</tr>
												</tfoot>
											</table>
											
										</div><!-- empieza div que contiene a la tabla -->
									</div><!--  termina div.col-xs-12 de la tabla clientes-->
								</div><!--  termina div.row de la tabla clientes-->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div id="modal_nuevo_dpp" class="modal fade" role="dialog">
				<div class="modal-dialog modal-lg">					

					<div class="modal-content">
					
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 id="modal_rutas_title" class="modal-title">Captura de NC</h4>
						</div>

						<div class="modal-body">
							<div class="row">
								<div class="col-md-3">
									<label for="txtFechaRecepcion">Fecha de Recepción</label>
									<input id="txtFechaRecepcion" type="date" class="form-control" />
								</div>
								<div class="col-md-3">
									<label for="txtFechaPago">Fecha de Pago</label>
									<input id="txtFechaPago" type="date" class="form-control" />
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
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
							<button id="btnGuardarDpp" type="button" class="btn btn-success">GUARDAR</button>
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

	var i_fecharecepcion=0, 
	i_fechapago=1,
	i_fechapago=2,
	i_sucursal=3,
	i_numerofactura=4, 
	i_importefactura=5, 
	i_tipo=6, 
	i_importenc=7, 
	i_importetotal=8,
	i_numeronc=9,
	i_acciones=10;

	var CARGAR_BOTONES_TABLA = "0";

	function formatMoney(n, c, d, t) {
	var c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = d == undefined ? "." : d,
		t = t == undefined ? "," : t,
		s = n < 0 ? "-" : "",
		i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
		j = (j = i.length) > 3 ? j % 3 : 0;

	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};

	window.onload = function()
	{

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

	$("#btnAplicar").on("click", function(){
		var fecha_inicio = $("#txtFechaDe").val();
		var fecha_final = $("#txtFechaA").val();
		var idsucursal = $("#cmbSucursal").val();
		var negocio = $("#cmbNegocio").val();
		var tipo = $("#cmbTipoNcFiltro").val();

		if(idsucursal == "0")
		{
			idsucursal = "";

			$("#cmbSucursal option").each(function()
			{
				if($(this).val() != "0")
					idsucursal = idsucursal + $(this).val() + ","; 
			});

			idsucursal = idsucursal.slice(0, -1);
		}

		if(negocio == "0")
		{
			negocio = "";

			$("#cmbNegocio option").each(function()
			{
				if($(this).val() != "0")
					negocio = negocio + $(this).val() + ","; 
			});

			negocio = negocio.slice(0, -1);
		}

		if(tipo == null)
		{
			tipo = "";

			$("#cmbTipoNcFiltro option").each(function()
			{
				if($(this).val() != "0")
					tipo = tipo + $(this).val() + ","; 
			});

			tipo = tipo.slice(0, -1);
		}
		else
		{
			tipo = tipo.toString();
		}

		cargarTablaProductos(fecha_inicio, fecha_final, idsucursal, tipo, negocio);
	});

	$("#btnNuevo").on("click", function(){
		$("#modal_nuevo_dpp").modal("show");
	});

	$("#btnGuardarDpp").on("click", function(){
		guardarDpp();
	});

	$('#table_visitas tbody').on( 'click', 'button.editrow', function () {
		var row = myTable.row( $(this).parents('tr') ).data();								
		window.location.href = "<?php echo LINKPROYECTO('EditarNc/'); ?>" + row.id;
	});

	$('.select2').css('width','300px').select2({allowClear:false})
	$('#select2-multiple-style .btn').on('click', function(e){
		var target = $(this).find('input[type=radio]');
		var which = parseInt(target.val());
		if(which == 2) $('.select2').addClass('tag-input-style');
			else $('.select2').removeClass('tag-input-style');
	});

	function cargarTablaProductos(pFechaDe, pFechaA, pSucursal, pTipo, pNegocio)
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		$.post("<?php echo LINKPROYECTO('Administracion/getListadoNc') ?>", 
		{fecha_inicio:pFechaDe, fecha_final:pFechaA, sucursal:pSucursal, tipo:pTipo, negocio:pNegocio}, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#table_visitas').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 50,
					"ordering": false,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"aaData": datos,
					"columns": [
						{ "data": "fecha_recepcion" },
						{ "data": "fecha_pago" },
						{ "data": "negocio_nombre" },
						{ "data": "sucursal" },
						{ "data": "factura" },
						{ "data": "importe_factura" },
						{ "data": "tipo" },
						{ "data": "importe_nc" },
						{ "data": "importe_total" },
						{ "data": "numero_nc" },
						{ "data": null },
					],
					"columnDefs": [
						{
							"targets": i_acciones,
							"data" : "id",
							"defaultContent": 									
							"<button class='editrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-pencil-square-o bigger-120'></i></span></button>"
						},
						{ className: "text-right", "targets": [i_importefactura, i_importenc, i_importetotal ] },
					],
					drawCallback: function () {
						var api = this.api();

						/*var data = myTable.rows().data();
						data.each(function (value, index)
						{
							//var venta = 
							//console.log(value);
							myTable.cell(index, 2).data(1);
						});*/

						var totalimportefactura = dollarUS.format(api.column(i_importefactura, {page:'current'} ).data().sum()).replace("$", "");
						var totalimportenc = dollarUS.format(api.column(i_importenc, {page:'current'} ).data().sum()).replace("$", "");
						var totalimportetotal = dollarUS.format(api.column(i_importetotal, {page:'current'} ).data().sum()).replace("$", "");
						/*var totalcosto = dollarUS.format(api.column(i_costoventa, {page:'current'} ).data().sum()).replace("$", "");
						var totalutilidadbruta = dollarUS.format(api.column(i_utilidadbruta, {page:'current'} ).data().sum()).replace("$", "");
						var totalgastos = dollarUS.format(api.column(i_gastos, {page:'current'} ).data().sum()).replace("$", "");
						var totalimportemargen = dollarUS.format(api.column(i_importemargen, {page:'current'} ).data().sum()).replace("$", "");
						var totalutilidadneta = dollarUS.format(api.column(i_utilidadneta, {page:'current'} ).data().sum()).replace("$", "");*/

						$("#lblTotalImporteFactura").text(totalimportefactura);
						$("#lblTotalImporteNc").text(totalimportenc);
						$("#lblTotalImporteTotal").text(totalimportetotal);
						/*$("#lblTotalCosto").text(totalcosto);
						$("#lblTotalUtilidadBruta").text(totalutilidadbruta);
						$("#lblTotalGastos").text(totalgastos);
						$("#lblTotalImporteMargen").text(totalimportemargen);
						$("#lblTotalUtilidadNeta").text(totalutilidadneta);*/
					}
				});

				cargarBotonesTabla();
			}
			else
			{
				myTable.clear().draw();
			}
		}).always(function() {
			$('#table_visitas').removeClass('loadingtable');
		});
	}

	function cargarBotonesTabla()
	{
		$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

		new $.fn.dataTable.Buttons( myTable, {
			buttons: [

				{
				"extend": "excel",
				"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
				"className": "btn btn-white btn-primary btn-bold",
				"titleAttr": "LISTADO",
				"title": 'Listado - Efectividad Agenda',
				"exportOptions": {
						columns: [ 
									i_fecharecepcion, 
									i_fechapago, 
									i_sucursal,
									i_numerofactura, 
									i_importefactura, 
									i_tipo, 
									i_importenc, 
									i_importetotal, 
									i_numeronc 
								]
					}
				},					 
				{
				"extend": "print",
				"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
				"className": "btn btn-white btn-primary btn-bold",
				autoPrint: false,
				message: 'This print was produced using the Print button for DataTables'
				}
			]
		});

		myTable.buttons().container().appendTo( $('.tableTools-container') );
	}

	function calculoImportes()
	{
		var importefactura = $("#txtImporteFactura").val() == "" ? 0 : parseFloat($("#txtImporteFactura").val());
		var nc = $("#txtNc").val() == "" ? 0 : parseFloat($("#txtNc").val());
		var subtotal = importefactura - nc;
		var porcentajedpp = $("#txtPorcentajeDpp").val();
		var importedpp = subtotal * porcentajedpp;
		var importepago = subtotal - importedpp;

		$("#txtSubtotal").val(subtotal);
		$("#txtImporteDpp").val(importedpp);
		$("#txtImportePago").val(importepago);
	}

	function guardarDpp()
	{
		var fecharecepcion = $("#txtFechaRecepcion").val();
		var fechapago = $("#txtFechaPago").val();
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
			fecharecepcion,
			fechapago,
			cmbSucursal,
			cmbTipo,
			factura,
			importefactura,
			importenc,
			importetotal,
			numeronc
		};

		$.post("<?php echo LINKPROYECTO('Administracion/saveNc') ?>", datos, function(data){
			$("#btnAplicar").trigger("click");
			$("#modal_nuevo_dpp").modal("hide");
		}).always(function(){

		});
	}
</script>