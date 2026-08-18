<?php 
$this->load->view("vHead"); ?>
<?php $this->load->view("vMenu");
$corte=VERIFICARPERFILFUNCION("Reportes","hacerCorte",$this->session->userdata('perfil'));
?>

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Reportes / Pedidos - Preventas</small>
				</h1>				
			</div>
						
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-2">
							<label for="">Inicio</label>
							<input id="txtFInicio" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
						</div>
						<div class="col-xs-2">
							<label for="">Final</label>
							<input id="txtFFinal" type="date" class="form-control" value="<?php echo GETFECHA(); ?>">
						</div>
						<div class="col-xs-2"><label for="">Tipo</label>
							<select name="cmbTipo" id="cmbTipo" class="form-control">
								<option value="0">TODOS</option>
								<option value="PREVENTA">Preventa</option>
								<option value="DEVOLUCION">Devolucion</option>
								<option value="CLUB_B">CLUB B</option>
							</select>
						</div>
						<div class="col-xs-2"><label for="">Sucursal</label><br>
							<!--<select name="cmbSucursal" id="cmbSucursal"  class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false">-->
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
						<div class="col-xs-2"><label for="">Ruta</label><br>
							<!--<select name="cmbRuta" id="cmbRuta" class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Ruta)">
								<option value="0">TODOS</option>
								<?php /*foreach ($listaRutas->result() as $kR) { ?>										
									<option value="<?php echo $kR->ruta; ?>"><?php echo $kR->ruta; ?></option>
								<?php }*/ ?>
							</select>-->
							<select name="cmbRuta" id="cmbRuta">
							</select>
						</div>
						<div class="col-xs-2"><label for="">Usuario</label><br>
							<!--<select name="cmbUsuario" id="cmbUsuario" class="selectpicker form-control" multiple="multiple" data-style="btn-white" data-live-search="false" title="(Selecciona Usuario)">
								<option value="0">TODOS</option>
								<?php /*foreach ($listaUsuarios->result() as $kU) { ?>
									<option value="<?php echo $kU->nombre; ?>"><?php echo $kU->nombre; ?></option>
								<?php }*/ ?>
							</select>-->
							<select name="cmbUsuario" id="cmbUsuario">
							</select>
						</div>
					</div>
									
					<div class="row"><div class="col-xs-12"><hr></div></div>
					<div class="row">
						<div class="col-xs-12">
							<div class="clearfix"></div>

							<p>Última Actualización Bees: <?php echo GETBEESDATOS()->ultima_actualizacion; ?></p>
										
							<div class="clearfix col-md-6" align="left">
								<div class="col-md-4">
									<h4><strong>No. Pedidos: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumPedidos">0</label></span>
								</div>
								<div class="col-md-4">
									<h4><strong>Total de Pedidos: </strong></h4><span class="label label-xlg label-primary"><label id="lblTotPedidos">$0.00</label></span><br>
								</div>
							</div>

							<div class="clearfix col-md-6" align="right">
								<div class="pull-right">
									<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
									<button class="btn btn-success btnActualizar">Actualizar</button><br><br>
								</div>
								<?php /*if($corte==1){ ?>
								<!--<button class="btn btn-warning btnCorte">Corte</button>--><?php } */?><!-- <button class="btn btn-default btnAcumulados">Acumulados</button></div> -->
							<div><br></div>
							<div class="clearfix col-md-12" align="right">
								<div class="pull-right tableTools-container"></div>
								<div style="margin-right:5px;" class="pull-right"><button id="btnReporteBdd" class="btn btn-white"><i class="ace-icon fa fa-file-excel-o bigger-130"></i>Reporte BDD</button></div>
								<div style="margin-right:5px;" class="pull-right"><button class="btn btn-white btnSacarTabla"><i class="ace-icon fa fa-file-excel-o bigger-130"></i>Preventa Excel</button></div>
								<div style="margin-right:5px;" class="pull-right"><button class="btn btn-white btnSacarTablaLiquidado"><i class="ace-icon fa fa-file-excel-o bigger-130"></i>Liquidado Excel</button></div><br><br>
							</div>
						</div>
					</div>

					<!--<div class="row">
						<div class="col-xs-12">
							<div class="pull-right"><button class="btn btn-white btnSacarTabla"><i class="ace-icon fa fa-file-excel-o bigger-130"></i>Generar Excel</button></div>
						</div>
					</div>-->

					<div class="col-xs-12">
						<div class="table-header">Listado de Pedidos.</div>
							<div class="table-responsive">
								<table id="table_pedidos" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="5%">Folio</th>
											<th width="5%">FolioBees</th>
											<th width="5%">Tipo</th>
											<th width="10%">Fecha</th>
											<th width="15%">Cliente</th>
											<th width="10%">Usuario</th>
											<th width="10%">Ruta</th>
											<th width="10%">Sucursal</th>
											<th width="5%">Total</th>
											<th width="5%">Estatus</th>
											<th width="10%">Acciones</th>
										</tr>
									</thead>
									<tbody>
														
									</tbody>
								</table>
							</div><!-- empieza div que contiene a la tabla -->
						</div><!--  termina div.col-xs-12 de la tabla clientes-->
					</div><!--  termina div.row de la tabla clientes-->

					<div id="modalDepurar" class="modal fade">
								<div class="modal-dialog modal-sm">
								<div class="modal-content">
									<!-- dialog body -->
									<div class="modal-header">
									<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
										<h4>Corte de Pedidos.</h4>
									
									
									
									<div class="col-md-12 row">
											<h5>¿Hasta que fecha se hara el corte?</h5>
									</div>
									<div class="col-md-12 row">
										<input type="date" name="txtFechaCorte" id="txtFechaCorte">
									</div>
									</div>
									<div class="modal-footer">
										
															
										<button id="btnAceptarX" type="button" class="btn btn-success">REALIZAR</button><button  type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
									</div>
								</div>
								</div>
							</div>
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

	var i_folio=0, i_foliobees=1, i_tipo=2, i_fecha=3, i_cliente=4, i_usuario=5, i_ruta=6, i_sucursal=7, i_total=8, i_status=9, i_acciones=10;
	var CARGAR_BOTONES_TABLA = "0";
	var PRIMER_CLICK = "0";
	var usuarios = "ggomez|isidro.lizarraga|andrea.pardo|admin";
	var usuario = "<?php echo $this->session->userdata('user'); ?>";

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
		$("#cmbSucursal").trigger("change");
		$("#btnAplicar").trigger("click");
	}

	var myTable = 
	$('#table_pedidos')
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
		cargarTablaProductos($("#txtFInicio").val(), $("#txtFFinal").val(), $("#cmbTipo").val(), $("#cmbSucursal").val(), $("#cmbRuta").val(), $("#cmbUsuario").val());
	});

	$("#btnReporteBdd").click(function(event) {
		var link="<?php echo LINKPROYECTO('Reportes/generarExcelPedidos/'); ?>" + $("#txtFInicio").val() + "/" + $("#txtFFinal").val() + "/" + $("#cmbSucursal").val();
		window.open(link,"_blank");
	});

	$(".btnSacarTabla").click(function(event) {
		var link="<?php echo LINKPROYECTO('ReporteVentasDetalle/'); ?>" + $("#txtFInicio").val() + "/" + 
		$("#txtFFinal").val() + "/" + $("#cmbTipo").val() + "/" + $("#cmbUsuario").val() + "/" + $("#cmbSucursal").val() + "/" + $("#cmbRuta").val();
		window.open(link,"_blank");
	});

	$(".btnSacarTablaLiquidado").click(function(event) {
		var link="<?php echo LINKPROYECTO('ReporteVentasDetalleLiquidado/'); ?>" + $("#txtFInicio").val() + "/" + 
		$("#txtFFinal").val() + "/" + $("#cmbTipo").val() + "/" + $("#cmbUsuario").val() + "/" + $("#cmbSucursal").val() + "/" + $("#cmbRuta").val();
		window.open(link,"_blank");
	});

	function cargarTablaProductos(pFechade, pFechaa, pTipo, pSucursal, pRuta, pUsuario)
	{
		$('#table_pedidos').addClass('loadingtable');
		$('#table_pedidos tbody').html("");

		if(pTipo==null) pTipo = "0";
		if(pSucursal==null) pSucursal = "0";
		if(pRuta==null) pRuta = "0";
		if(pUsuario==null) pUsuario = "0";

		$.post("<?php echo LINKPROYECTO('PedidosJson') ?>", 
		{ fechade:pFechade, fechaa:pFechaa, tipo:pTipo, sucursal:pSucursal,ruta:pRuta, usuario:pUsuario}, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#table_pedidos').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 50,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [						
						{ "data": "folio" },
						{ "data": "foliobees" },
						{ "data": "tipo" },
						{ "data": "fechacreacion" },
						{ "data": "cliente" },
						{ "data": "usuario" },
						{ "data": "ruta_nombre" },
						{ "data": "sucursal_nombre" },
						{ "data": "total_format" },
						{ "data": null },
						{ "data": null },
					],
					"columnDefs": [
						{
							/*"targets": i_acciones,
							"data" : "id",
							"defaultContent": 									
							"<button title='Ver Pedido' style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>"+
							"<button title='Cancelar Pedido' style='margin-right:5px;' class='cancelarrow btn btn-minier btn-red dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='red'><i class='ace-icon fa fa-trash bigger-120'></i></span></button>" +
							"<button title='Imprimir Pedido' class='printrow btn btn-minier btn-red dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='red'><i class='ace-icon fa fa-print bigger-120'></i></span></button>"*/
							"render": function ( data, type, row ) {
                                return "<button title='Ver Pedido' style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>"+
								(usuarios.includes(usuario) ? "<button title='Cancelar Pedido' style='margin-right:5px;' class='cancelarrow btn btn-minier btn-red dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='red'><i class='ace-icon fa fa-trash bigger-120'></i></span></button>" : "") +
								"<button title='Imprimir Pedido' class='printrow btn btn-minier btn-red dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class=" + ((row.impreso==1) ? 'green' : 'red') + "><i class='ace-icon fa fa-print bigger-120'></i></span></button>";
                            },
                            "targets": i_acciones,
						},
						{
                            "render": function ( data, type, row ) {
                                return (row.status==1) ? "<span class='label label-sm label-success'>ACTIVO</span>" : "<span class='label label-sm label-danger'>CANCELADO</span>";
                            },
                            "targets": i_status,
                        },
						{ className: "text-right", "targets": [i_total] },						
					]
				});

				var datostabla = myTable.rows().data();
				var numpedidosactivos = 0;
				var sumapedidos = 0;
                             
                for (var i = 0; i < datostabla.length; i++) {
                    if (datostabla[i]["status"] == "1" && datostabla[i]["tipo"] == "PREVENTA"){
						numpedidosactivos = numpedidosactivos + 1;
						sumapedidos = sumapedidos + parseFloat(datostabla[i]["total2"]);
					}						
                }

				$("#lblNumPedidos").text(numpedidosactivos);
				$("#lblTotPedidos").text("$" + formatMoney(sumapedidos) );

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#table_pedidos tbody').on( 'click', 'button.cancelarrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();

						let userResponse = confirm("¿Está seguro de cancelar el pedido?");

						if (userResponse) 
						{
							$.post("<?php echo LINKPROYECTO('Reportes/eliminarPedido');?>", {id: row.id},function(data){
								location.reload();
							});
						}
						//window.location.href = "<?php echo LINKPROYECTO('EditarZona/'); ?>" + row.id;
					});
					$('#table_pedidos tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						window.open("<?php echo LINKPROYECTO('VerPedido/'); ?>" + row.id, "_blank");
					});
					$('#table_pedidos tbody').on( 'click', 'button.printrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
				  		window.open("<?php echo LINKPROYECTO('ImprimirPedido/'); ?>" + row.id, "_blank");
					});
				}

				cargarBotonesTabla();
			}
			else
			{
				myTable.clear().draw();
				//alert("Ocurrio un error al eliminar el empleado");
			}
		}).always(function() {
			$('#table_pedidos').removeClass('loadingtable');
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
				"title": 'Listado - Ventas',
				"exportOptions": {
						columns: [ i_fecha, i_tipo, i_folio, i_cliente, i_ruta, i_sucursal, i_total, i_status ]
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
