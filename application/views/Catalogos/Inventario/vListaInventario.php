<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); 
$editar=VERIFICARPERFILFUNCION("Catalogos","editarUsuario",$this->session->userdata('perfil'));
$nuevo=VERIFICARPERFILFUNCION("Catalogos","nuevoUsuario",$this->session->userdata('perfil'));
$liberar=VERIFICARPERFILFUNCION("Catalogos","liberaUsuario",$this->session->userdata('perfil'));
?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Catalogos / <?php echo $vista; ?>
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
										<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
										<div class="clearfix">
											<div class="pull-right">
											<?php 
											if($nuevo==1){
												 ?>
											<!--<button class="btn btn-success btnAgregar">Agregar</button>-->
											<?php } ?>
											<!--<button class="btn btn-primary btnActualizar">Actualizar</button></div>-->
										</div>
										<br>
											
										<div class="row">
											<div class="col-sm-2">
												<select id="cmbFiltroSucursal" name="sucursal" class="form-control">
													<?php if(ISMULTISUCURSAL()) { ?>
														<!--<option value=0 selected>TODAS</option>-->
														<?php foreach (GETLISTASUCURSALES() as $item) { ?>
															<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
														<?php } ?>
													<?php } else { ?>
														<?php foreach (GETLISTASUCURSALES() as $item) { ?>
																<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
														<?php } ?>
													<?php } ?>
												</select> 
											</div>
										</div>

										<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
										</div>

										<div class="table-header">
											Listado de <?php echo $vista; ?>
										</div>										

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="tabla_usuarios" width="100%" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="10%">Fecha</th>
														<th width="7%">Sucursal</th>
														<th width="5%">Codigo</th>
														<th width="20%">Producto</th>
														<th width="5%">Cantidad</th>
														<th width="5%">Cantidad Disponible</th>
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
						

<?php $this->load->view("vCopyright"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

</div><!-- /.main-container -->

<?php $this->load->view("vFooter"); ?>

</body>
</html>
				
<script>
	var idprincipal=0;

	var CARGAR_BOTONES_TABLA = "0";
	var i_fecha=0, i_sucursal=1, i_codigo=2, i_producto=3, i_cantidad=4, i_cantidaddisponible=5;
	var hide_columnas;

	var myTable = $('#tabla_usuarios')				
	.DataTable( {
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},
					"pageLength": -1,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]]
	});

	window.onload = function()
	{
		cargarTableUsuarios($("#cmbFiltroSucursal").val());
	}

	$("#cmbFiltroSucursal").on("change", function(){
		cargarTableUsuarios($("#cmbFiltroSucursal").val());
	});

	function cargarTableUsuarios(pIdsucursal)
	{
		$('#tabla_usuarios').addClass('loadingtable');
		$('#tabla_usuarios tbody').html("");

		$.post("<?php echo LINKPROYECTO('ListadoInventarioJson') ?>", { idsucursal:pIdsucursal }, function(data){
			var datos = JSON.parse(data);						
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#tabla_usuarios').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS("json/datatables-spanish.json"); ?>"
					},
					"pageLength": 50,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "fecha_registro" },
						{ "data": "sucursal" },
						{ "data": "codigo" },
						{ "data": "nombre" },
						{ "data": "cantidad", className: "text-right" },
						{ "data": "cantidaddisponible", className: "text-right" }
					],
					"columnDefs": [
						
					]
				});

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";
				}

				cargarBotonesTabla();
			}
			else
			{
				myTable.clear().draw();
			}
		}).always(function() {
			$('#tabla_usuarios').removeClass('loadingtable');
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
				"title": 'Listado - Usuarios',
				"exportOptions": {
						columns: [ i_fecha, i_sucursal, i_codigo, i_producto, i_cantidad, i_cantidaddisponible ]
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