<?php 
$data['title']="LIZER Reportes-Agenda de Visitas";

$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); ?>

<div id="principal" class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						Reportes / Distribución
					</small>
				</h1>
			</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row">

									<div class="col-md-2">
										<label for="txtFechaDe">Inicio</label>
										<input id="txtFechaDe" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
									</div>

									<div class="col-md-2">
										<label for="txtFechaA">Final</label>
										<input id="txtFechaA" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
									</div>

									<div class="col-md-2"><label for="">Sucursal</label><br>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<option value="0">TODAS</option>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value="<?php echo $item->id; ?>" <?php echo (GETSUCURSAL()==$item->id) ? 'selected' : ''; ?> ><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } else { ?>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
														<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
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

									<div class="col-md-4"><label for="">Clasificacion:</label><br>
										<select multiple="" id="cmbClasificacion" class="form-control select2" data-placeholder="	[TODAS]">

											<?php foreach($clasificaciones->result() as $item) { ?>
												<option value="<?php echo $item->id; ?>"><?php echo $item->nombre; ?></option>
											<?php } ?>

										</select>
									</div>

								</div>								

							</div>

							
							<div class="col-md-12"><br/>
								<div class="pull-right tableTools-container">
									<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
									<button class="btn btn-success btnActualizar">Actualizar</button> <br><br>
								</div>
							</div>

							<div class="row"><div class="col-xs-12"><hr></div></div>
							
							<div class="row">							
								</div>
										<div class="col-xs-12">
										<div class="table-header">
											Listado Distribución
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="table_visitas" width="100%" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="10%">Ruta</th>
														<th width="10%">Visi. Programadas</th>
														<th width="10%">Visi. Hechas</th>
														<th width="10%">Cumpl. Agenda</th>
														<th width="10%">No. Pedidos</th>
														<th width="10%">Efectividad</th>
														<th width="10%">Venta</th>
														<th width="10%">Drop Size</th>
													</tr>
												</thead>
												<tbody>												
														
												</tbody>
											</table>
											
										</div><!-- empieza div que contiene a la tabla -->
									</div><!--  termina div.col-xs-12 de la tabla clientes-->
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

	var i_ruta=0, i_visitasprogramadas=1, i_visitashechas=2, i_cumplimientoagenda=3, i_numpedidos=4, i_efectividad=5, i_venta=6, i_dropsize=7;
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
		$("#cmbSucursal").change();
		$("#btnAplicar").click();
	}

	$('.select2').css('width','500px').select2({allowClear:false})
	$('#select2-multiple-style .btn').on('click', function(e){
		var target = $(this).find('input[type=radio]');
		var which = parseInt(target.val());
		if(which == 2) $('.select2').addClass('tag-input-style');
			else $('.select2').removeClass('tag-input-style');
	});

	var myTable = 
	$('#table_visitas')
	.DataTable({
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},
			"pageLength": -1,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
			"order": [[0,"asc"]],
	});

	$("#cmbSucursal").on("change", function(){
		if($(this).val()==null){
			$("#cmbUsuario").html("");
			$("#cmbRuta").html("");			
			return;
		}
		var idSucursal = $(this).val().toString();
		$.post("<?php echo CCATALOGOS('createComboAgente');?>", {sucursal: idSucursal},function(data){
			$("#cmbUsuario").html(data);			
		});

		$.post("<?php echo CCATALOGOS('createComboRutas');?>", {sucursal: idSucursal},function(data){
			$("#cmbRuta").html(data);
		});
	});

	$("#btnAplicar").on("click", function(){
		cargarDistribucion($("#txtFechaDe").val(), $("#txtFechaA").val(), $("#cmbSucursal").val(), $("#cmbClasificacion").val(), $("#cmbNegocio").val());
	});

	function cargarDistribucion(pFechaDe, pFechaA, pSucursal, pClasificacion, pNegocio)
	{
		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		if(pFechaDe==null) dialogAvisoGlobal.show("Favor de seleccionar una fecha inicial");
		if(pFechaA==null) dialogAvisoGlobal.show("Favor de seleccionar una fecha final");
		if(pSucursal==null) dialogAvisoGlobal.show("Favor de seleccionar una sucursal");

		$.post("<?php echo LINKPROYECTO('ReporteDistribucionJson') ?>", 
		{ fechaDe: pFechaDe, fechaA: pFechaA, sucursal: pSucursal, clasificacion: pClasificacion, proveedor: pNegocio}, function(data)
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
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "nombre_ruta" },
						{ "data": "visitasprogramadas" },
						{ "data": "visitasrealizadas" },						
						{ "data": "cumplimientoagenda" },
						{ "data": "numpedidos" },
						{ "data": "efectividad" },
						{ "data": "venta" },
						{ "data": "dropsize" },
					],
					"columnDefs": [
						{ className: "text-right", "targets": [i_numpedidos, i_venta, i_dropsize] },
					]
				});

				/*var total_clientes = parseFloat(myTable.column( i_programadas ).data().sum());
				var total_visitadas = parseFloat(myTable.column( i_hechas ).data().sum());

				$("#lblNumVisitas").text(total_clientes);
				$("#lblNumPedidos").text(total_visitadas);
				$("#lblTotPedidos").text( formatMoney((total_visitadas/total_clientes)*100) + "%" );

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#table_visitas tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						window.open("<?php echo LINKPROYECTO('VerVisitas/'); ?>" + row.chofer + "/" + row.fecha_agenda + "/0", "_blank");
					});
				}

				*/
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
				"title": 'Reporte - Distribucion',
				"exportOptions": {
						columns: [ i_ruta, i_visitasprogramadas, i_visitashechas, i_cumplimientoagenda, i_numpedidos, i_efectividad, i_venta, i_dropsize ]
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
</script>