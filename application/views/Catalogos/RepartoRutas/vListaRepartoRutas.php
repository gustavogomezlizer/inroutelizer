<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); 
$editar=VERIFICARPERFILFUNCION("Catalogos","editarCategorias",$this->session->userdata('perfil'));
$nuevo=VERIFICARPERFILFUNCION("Catalogos","nuevaCategorias",$this->session->userdata('perfil'));
?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Catalogos / Reparto Rutas
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
										<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
										<div class="clearfix">
											<div class="pull-right">
												<button class="btn btn-primary btnActualizar">Actualizar</button></div>
										</div>
										<br>

										<div class="row">
											<div class="col-md-2">
												<select id="cmbFiltroSucursal" name="sucursal" class="form-control">
													<?php if(ISMULTISUCURSAL()) { ?>
														<option value=0 selected>TODAS</option>
														<?php foreach (GETLISTASUCURSALES() as $item) { ?>
															<option value="<?php echo $item->id; ?>" <?php echo (GETSUCURSAL()==$item->id) ? 'selected' : '' ?>  ><?php echo $item->sucursal; ?></option>
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
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>
										<div class="table-header">
											Listado de Reparto Rutas.
										</div>										

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="tabla_categorias" width="100%" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Sucursal</th>
														<th>Usuario</th>
														<th>Nombre</th>
														<th>Rutas Asignadas</th>
														<th>Acciones</th>
													</tr>
												</thead>
												<tbody>
														
												</tbody>
											</table>
										</div><!-- empieza div que contiene a la tabla -->
									</div><!--  termina div.col-xs-12 de la tabla clientes-->
								</div><!--  termina div.row de la tabla clientes-->
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<!-- Modal -->
			<div id="modal_rutas" class="modal fade" role="dialog">
				<div class="modal-dialog modal-lg">

					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 id="modal_rutas_title" class="modal-title">Modal Header</h4>
						</div>

					<div class="modal-body">
						<div class="row">

							<div class="col-md-6">

								<table id="tabla_rutas_asignadas" width="100%" class="table table-condensed">

									<thead>
										<tr>
											<td align="center" colspan="2"><b>Rutas Asignadas</b></td>
										</tr>
										<tr>
											<th>Ruta</th>
											<th>&nbsp;</th>
										</tr>
									</thead>

									<tbody></tbody>

								</table>
							</div>

							<div class="col-md-6">

								<table id="tabla_rutas_disponibles" width="100%" class="table table-condensed">

									<thead>
										<tr>
											<td align="center" colspan="2"><b>Rutas Disponibles</b></td>
										</tr>
										<tr>
											<th>Ruta</th>
											<th>&nbsp;</th>
										</tr>
									</thead>

									<tbody></tbody>

								</table>

							</div>

						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
						<button type="button" class="btn btn-success" onclick="guardarRutas();">GUARDAR</button>
					</div>

				</div>
			</div>

	<?php $this->load->view("vCopyright"); ?>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>

		</div><!-- /.main-container -->
		
	<?php $this->load->view("vFooter"); ?>
	</body>
</html>
		
<script>
	var CARGAR_BOTONES_TABLA = "0";

	var i_sucursal=0, i_usuario=1, i_nombre=2, i_rutas=3, i_acciones=4;

	var ID_USUARIO = 0;

	var myTable = $('#tabla_categorias').DataTable({
		"language": {
			"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
		},
	});

	window.onload = function()
	{
		cargarTablaProductos();
	}

	$(".btnActualizar").click(function(event) {						
		location.reload();
	});

	$("#cmbFiltroSucursal").on("change", function(){
		cargarTablaProductos();
	});

	function cargarTablaProductos()
	{
		var idsucursal = $("#cmbFiltroSucursal").val();

		if(idsucursal == "0")
		{
			dialogAvisoGlobal.show("Favor de seleccionar una sucursal", "alert alert-warning");
			return;
		}

		$('#tabla_categorias').addClass('loadingtable');
		$('#tabla_categorias tbody').html("");

		$.post("<?php echo LINKPROYECTO('ListadoRepartoRutasJson/') ?>" + idsucursal, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#tabla_categorias').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 50,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "sucursal_nombre" },
						{ "data": "usuario" },
						{ "data": "nombre" },
						{ "data": "rutas_asignadas" },
						{ "data": null },
					],
					"columnDefs": [
						{
							"targets": i_acciones,
							"data" : "id",
							"defaultContent":
							"<button class='editrow btn btn-minier btn-green dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='green'><i class='ace-icon fa fa-pencil-square-o bigger-120'></i></span></button>"
						},						
					]
				});

				/*
				"<button style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>"+
				"<button class='editrow btn btn-minier btn-green dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='green'><i class='ace-icon fa fa-pencil-square-o bigger-120'></i></span></button>"
				*/

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#tabla_categorias tbody').on( 'click', 'button.editrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();

						ID_USUARIO = row.id;

						$("#modal_rutas_title").text("Editar Rutas Reparto: " + row.usuario);
						$("#modal_rutas").modal("show");

						cargarRutasAsignadas(row.id, row.sucursal);
					});

					$('#tabla_categorias tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
					});

					cargarBotonesTabla();
				}
			}
			else
			{
				myTable.clear().draw();
				//alert("Ocurrio un error al eliminar el empleado");
			}
		}).always(function() {
			$('#tabla_categorias').removeClass('loadingtable');
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
				"title": 'Listado - Proveedores',
				"exportOptions": {
						columns: [ 0, 1, 2, 3, 4 ]
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

	function cargarRutasAsignadas(pIdusuario, pIdsucursal)
	{
		$('#tabla_rutas_asignadas').addClass('loadingtable');
		$("#tabla_rutas_asignadas tbody").html("");

		$('#tabla_rutas_disponibles').addClass('loadingtable');
		$("#tabla_rutas_disponibles tbody").html("");

		$.ajax({
			url: "<?php echo LINKPROYECTO('Catalogos/getListaRepartoRutasAsignadasJson/') ?>" + pIdusuario,
			async: true,
			success: function(respuesta)
			{
				$('#tabla_rutas_asignadas').removeClass('loadingtable');

				var datos = JSON.parse(respuesta);

				var tabla = "";

				for(var x in datos)
				{
					tabla = tabla + "<tr>";
					tabla = tabla + "<td>" + datos[x].ruta + "</td>";
					tabla = tabla + "<td><input type='checkbox' name='" + datos[x].id + "' checked/></td>";
					tabla = tabla + "</tr>";
				}

				$("#tabla_rutas_asignadas tbody").html(tabla);
			},
			error: function()
			{
				$('#tabla_rutas_asignadas').removeClass('loadingtable');
				alert("Ocurrio un error al cargar la informacion")
			}
		});

		$.ajax({
			url: "<?php echo LINKPROYECTO('Catalogos/getListaRepartoRutasDisponiblesJson/') ?>" + pIdsucursal,
			async: true,
			success: function(respuesta)
			{
				$('#tabla_rutas_disponibles').removeClass('loadingtable');

				var datos = JSON.parse(respuesta);

				var tabla = "";

				for(var x in datos)
				{
					tabla = tabla + "<tr>";
					tabla = tabla + "<td>" + datos[x].ruta + "</td>";
					tabla = tabla + "<td><input type='checkbox' name='" + datos[x].id + "'/></td>";
					tabla = tabla + "</tr>";
				}

				$("#tabla_rutas_disponibles tbody").html(tabla);
			},
			error: function()
			{
				$('#tabla_rutas_disponibles').removeClass('loadingtable');
				alert("Ocurrio un error al cargar la informacion")
			}
		});
	}

	function guardarRutas()
	{
		var id_rutas = "";

		$('#tabla_rutas_asignadas tbody input:checked').each(function() {
			id_rutas = id_rutas + this.name + ",";
		});

		$('#tabla_rutas_disponibles tbody input:checked').each(function() {
			id_rutas = id_rutas + this.name + ",";
		});

		id_rutas = id_rutas.slice(0, -1);

		$.ajax({
			url: "<?php echo LINKPROYECTO('Catalogos/asignacionRepartoRutas') ?>",
			type: "POST",
			data: "rutas=" + id_rutas + "&idusuario=" + ID_USUARIO,
			async: true,
			success: function(respuesta)
			{
				$("#modal_rutas").modal("hide");
				cargarTablaProductos();
			},
			error: function()
			{
				alert("Ocurrio un error al cargar la informacion")
			}
		});
	}

</script>