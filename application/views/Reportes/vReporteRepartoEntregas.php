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
									Reportes / Entregas Reparto
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
													<option value="<?php echo $item->id; ?>" <?php echo (GETSUCURSAL()==$item->id) ? 'selected' : ''; ?> ><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } else { ?>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<?php if(GETSUCURSAL()==$item->id) { ?>
														<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
													<?php } ?>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="col-xs-2"><label for="">Ruta</label><br>							
										<select name="cmbRuta" id="cmbRuta">
										</select>
									</div>
									<div class="col-xs-2"><label for="">Usuario</label><br>							
										<select name="cmbUsuario" id="cmbUsuario">
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
										
										<!--<div class="clearfix col-md-6" align="left">
											<div class="col-md-4">
											<h4><strong>No. Visitas: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumVisitas">0</label></span>
												</div>
											<div class="col-md-4">
											<h4><strong>No. Pedidos: </strong></h4><span class="label label-xlg label-primary"><label id="lblNumPedidos">0</label></span>
											</div>
											<div class="col-md-4">
											<h4><strong>Total de Pedidos: </strong></h4><span class="label label-xlg label-primary"><label id="lblTotPedidos">$0.00</label></span><br>
											</div>
										
										</div>-->

										<div class="clearfix col-md-12" align="right">
											<div class="pull-right">
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
											Listado de Entregas
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="table_visitas" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="10%">Fecha</th>
														<th width="10%">Inicio</th>
														<th width="10%">Fin</th>
														<th width="25%">Usuario</th>
														<th width="10%">Ruta</th>
														<th width="15%">Efectividad (Entregas/Pedidos)</th>
														<th width="10%">Sucursal</th>
														<th width="15%">Acciones</th>
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

			<!-- ################# INICIO MODAL LISTADO DE DEPOSITOS ################## -->
			<div id="modal_depositos" class="modal fade">
				<div class="modal-dialog tamano">

					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 id="modal_depositos_title" class="modal-title">Modal Header</h4>
						</div>

						<div class="modal-body">
							<div class="row">

								<div class="col-md-12">

									<!-- div.dataTables_borderWrap -->
									<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
										<table id="modal_depositos_table" width="100%" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th width="10%">Fecha</th>
													<th width="10%">Hora</th>
													<th width="10%">Importe Total</th>
													<th width="10%">Importe Real</th>
													<th width="10%">Importe Deposito</th>
													<th width="10%">Importe Disponible</th>
													<th width="25%">Comentarios</th>
												</tr>
											</thead>
											<tbody>
													
											</tbody>
										</table>
										
									</div>
									<!-- empieza div que contiene a la tabla -->

								</div>

							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
						</div>
					</div>
				</div>
			</div>
			<!-- ################# FIN MODAL LISTADO DE DEPOSITOS ################## -->

			<!-- ################# INICIO MODAL LISTADO DE ENTREGAS ################## -->
			<div id="modal_entregas" class="modal fade">
				<div class="modal-dialog tamano">

					<div class="modal-content">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 id="modal_entregas_title" class="modal-title">Modal Header</h4>
						</div>

						<div class="modal-body">
							<div class="row">

								<div class="col-md-12">

									<!-- div.dataTables_borderWrap -->
									<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
										<table id="modal_entregas_table" width="100%" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th width="10%">Fecha</th>
													<th width="10%">Hora</th>
													<th width="10%">Duración</th>
													<th width="10%">Pedido</th>
													<th width="10%">Tipo</th>
													<th width="10%">Cliente</th>
													<th width="10%">Estatus</th>
												</tr>
											</thead>
											<tbody>
													
											</tbody>
										</table>
										
									</div>
									<!-- empieza div que contiene a la tabla -->

								</div>

							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
						</div>
					</div>
				</div>
			</div>
			<!-- ################# FIN MODAL LISTADO DE ENTREGAS ################## -->


<?php $this->load->view("vCopyright"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

<?php $this->load->view("vFooter"); ?>

</body>
</html>
		

<script>

	var i_fecha=0, i_inicio=1, i_fin=2, i_usuario=3, i_ruta=4, i_efectividad=5, i_sucursal=6, i_acciones=7;
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

	var table_depositos = $("#modal_depositos_table").DataTable();
	var table_entregas = $("#modal_entregas_table").DataTable();

	$("#cmbSucursal").on("change", function(){
		if($(this).val()==null){
			$("#cmbUsuario").html("");
			$("#cmbRuta").html("");			
			return;
		}
		var idSucursal = $(this).val().toString();
		$.post("<?php echo CCATALOGOS('createComboReparto');?>", {sucursal: idSucursal},function(data){
			$("#cmbUsuario").html(data);			
		});

		$.post("<?php echo CCATALOGOS('createComboRutas');?>", {sucursal: idSucursal},function(data){
			$("#cmbRuta").html(data);
		});
	});

	$("#btnAplicar").on("click", function(){
		cargarTablaProductos($("#txtFechaDe").val(), $("#txtFechaA").val(), $("#cmbSucursal").val(), $("#cmbRuta").val(), $("#cmbUsuario").val());
	});

	function cargarTablaProductos(pFechaDe, pFechaA, pSucursal, pRuta, pUsuario)
	{
		$('#table_visitas').addClass('loadingtable');
		$('#table_visitas tbody').html("");

		if(pSucursal==null) pSucursal = "0";
		if(pRuta==null) pRuta = "0";
		if(pUsuario==null) pUsuario = "0";

		$.post("<?php echo LINKPROYECTO('Reportes/listaReporteRepartoEntregasJson') ?>", 
		{fecha_inicio:pFechaDe, fecha_final:pFechaA, idsucursal:pSucursal, ruta:pRuta, idreparto:pUsuario}, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				var total_pedidos = 0, total_visitas = 0, total_monto = 0;

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
						{ "data": "fecha_descarga" },
						{ "data": "hora_inicio" },
						{ "data": "hora_final" },
						{ "data": "usuario_nombre" },						
						{ "data": "rutas" },
						{ "data": "efectividad" },
						{ "data": "sucursal_nombre" },
						{ "data": null },
					],
					"columnDefs": [
						{
							"render": function ( data, type, row ) {
								return (
									"<button title='Ver Depositos' style='margin-right:5px;' class='showdepositos btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-money bigger-120'></i></span></button>" +
									"<button title='Ver Entregas' style='margin-right:5px;' class='showentregas btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>"
								);
							},
							"targets": i_acciones,
						},						
						//{ className: "text-right", "targets": [ i_efectividad] },
					]
				});

				/*$("#lblNumVisitas").text(total_visitas);
				$("#lblNumPedidos").text(total_pedidos);
				$("#lblTotPedidos").text("$" + formatMoney(parseFloat(total_monto)));*/

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#table_visitas tbody').on( 'click', 'button.showdepositos', function () {
						var row = myTable.row( $(this).parents('tr') ).data();

						cargarDepositos(row.fecha_descarga, row.idusuario);
						$("#modal_depositos_title").text("Depositos " + row.usuario_nombre + " Fecha: " + row.fecha);
						$("#modal_depositos").modal("show");
					});

					$('#table_visitas tbody').on( 'click', 'button.showentregas', function () {
						var row = myTable.row( $(this).parents('tr') ).data();

						cargarEntregas(row.id, row.fecha, row.idusuario);
						$("#modal_entregas_title").text("Entregas " + row.usuario_nombre + " Fecha: " + row.fecha);
						$("#modal_entregas").modal("show");
					});
				}

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
						columns: [ i_inicio, i_fin, i_usuario, i_ruta, i_efectividad, i_sucursal ]
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

	function cargarDepositos(pFecha, pIdusuario)
	{
		$('#modal_depositos_table').addClass('loadingtable');

		$.ajax({
		method: "POST",
        url: "<?php echo LINKPROYECTO('Reportes/listaReporteRepartoDepositosJson') ?>",
		data: "fecha=" + pFecha + "&idusuario=" + pIdusuario,
        success: function(respuesta)
        {
          var rows = JSON.parse(respuesta);

          if(rows.length > 0)
          {
            table_depositos.destroy();
            table_depositos = $('#modal_depositos_table').DataTable({
              "language": {
                  "url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
              },
              dom: 'Bfrtlip',
              buttons: [
                {
                  
                  extend: 'excelHtml5',
                  className: "excelButton btn btn-default",
                  text: '<img height="20" width="20" src="<?php echo RUTAFOLDERASSETS("icons/excel.png"); ?>"> GENERAR EXCEL',
                  titleAttr: 'CONCENTRADO',
                  title: 'Depositos',
                  exportOptions: {
                          columns: [ 0,1,2,3,4,5 ]
                      }

                }
              ],
              "aaData": rows,
              "columns": [
                  { "data": "fecha" },
                  { "data": "hora" },
                  { "data": "importe_total", className: "text-right" },
                  { "data": "importe_real", className: "text-right" },
                  { "data": "importe_deposito", className: "text-right" },
                  { "data": "importe_disponible", className: "text-right" },
                  { "data": "comentarios" },
              ]
            });

          }
          else
          {
            table_depositos.clear().draw();
          }

          $('#modal_depositos_table').removeClass('loadingtable');
        },
        error: function()
        {
          alert("No se ha podido obtener la información");
          $('#modal_depositos_table').removeClass('loadingtable');
        }
      });
	}

	function cargarEntregas(pIdreparto, pFecha, pIdusuario)
	{
		$('#modal_entregas_table').addClass('loadingtable');

		$.ajax({
		method: "POST",
        url: "<?php echo LINKPROYECTO('Reportes/listaReporteRepartoEntregasUsuarioJson') ?>",
		data: "id_reparto= " + pIdreparto + "&fecha=" + pFecha + "&idusuario=" + pIdusuario,
        success: function(respuesta)
        {
          var rows = JSON.parse(respuesta);

          if(rows.length > 0)
          {
            table_entregas.destroy();
            table_entregas = $('#modal_entregas_table').DataTable({
              "language": {
                  "url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
              },
              dom: 'Bfrtlip',
              buttons: [
                {
                  
                  extend: 'excelHtml5',
                  className: "excelButton btn btn-default",
                  text: '<img height="20" width="20" src="<?php echo RUTAFOLDERASSETS("icons/excel.png"); ?>"> GENERAR EXCEL',
                  titleAttr: 'CONCENTRADO',
                  title: 'Entregas',
                  exportOptions: {
                          columns: [ 0,1,2,3,4,5 ]
                      }

                }
              ],
              "aaData": rows,
              "columns": [
                  { "data": "fecha_entrega" },
                  { "data": "hora_entrega" },
                  { "data": "duracion" },
                  { "data": "folio" },
				  { "data": "tipo" },
                  { "data": "cliente" },
                  { "data": "estatus" },
              ]
            });

          }
          else
          {
            table_entregas.clear().draw();
          }

          $('#modal_entregas_table').removeClass('loadingtable');
        },
        error: function()
        {
          alert("No se ha podido obtener la información");
          $('#modal_entregas_table').removeClass('loadingtable');
        }
      });
	}
</script>