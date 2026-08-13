<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); 
//$editar=VERIFICARPERFILFUNCION("Catalogos","editarProducto",$this->session->userdata('perfil'));
$perfiles = array("ADMINISTRADOR", "SISTEMAS");
$editar = "0";
if(in_array($this->session->userdata('perfil'), $perfiles))
{
	$editar = "1";
}

$nuevo=VERIFICARPERFILFUNCION("Catalogos","nuevoProducto",$this->session->userdata('perfil'));
?>

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
				<div class="col-xs-12">
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
								<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
								<div class="clearfix">
									<p>Última Actualización Bees: <?php //echo GETBEESDATOS()->ultima_actualizacion; ?></p>

									<div class="col-sm-2">
										<label for="cmbEstatus">Estatus</label>
										<select id="cmbEstatus" name="estatus" class="form-control">
											<option value="TODOS">TODOS</option>
											<option value="SI">ACTIVOS</option>
											<option value="NO">INACTIVOS</option>
										</select> 
									</div>

									<div class="pull-right">

										<?php if(GETPERFILUSUARIO() == "SISTEMAS" || GETPERFILUSUARIO() == "ADMINISTRADOR") { ?>
											<button id="btnDeshabilitarPaquetes" class="btn btn-danger">Deshabilitar todos los paquetes</button>
										<?php } ?>

										<?php if($nuevo==1){ ?>
											<button class="btn btn-success btnAgregar">Agregar</button>
										<?php } ?>

										<button class="btn btn-primary btnActualizar">Actualizar</button></div>										
								</div>
								<br>

								<div class="clearfix">
									<div class="pull-right tableTools-container"></div>
								</div>							

								<div class="table-header">Listado de Paquetes</div>
								
								<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
									<table id="tabla_productos" width="100%" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Codigo</th>
												<th>CB</th>
												<th>Nombre</th>
												<th>Clasificacion</th>
												<th>Proveedor</th>
												<th>Clave SAT</th>
												<th>Sucursales</th>
												<th>Costo</th>
												<th>Precio</th>
												<th>IEPS</th>
												<th>Activo</th>
												<th>Bees</th>
												<th>Acciones</th>
											</tr>
										</thead>
										<tbody></tbody>
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

<!--</div>--><!-- /.main-container -->

<!-- basic scripts -->
<?php $this->load->view("vFooter"); ?>
				
<script type="text/javascript">

	var CARGAR_BOTONES_TABLA = "0";

	var i_codigo=0, i_codigobarras=1, i_nombre=2, i_clasificacion=3, i_proveedor=4, i_clavesat=5, i_sucursales=6, i_costo=7, i_precio=8, i_ieps=9, i_activo=10, i_bees=11, i_acciones=12;

	var perfil = "<?php echo GETPERFILUSUARIO();?>";

	var columnas_hidden = (perfil == "ADMINISTRADOR") ? [] : [i_costo];

	var myTable = $('#tabla_productos').DataTable({
		"language": {
				"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
			},
			/*"pageLength": -1,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
			"order": [[0,"asc"]]*/
	});

	var Editar = "<?php echo $editar; ?>";

	window.onload = function()
	{
		cargarTablaProductos();
	}

	$(".verProducto1").click(function(event) {					
		var id=$(this).attr("id").replace("VER1","");
		id=id.replace("VER2","");					
		var link="<?php echo CCATALOGOS(); ?>" + "verPaquete/"+id;				  	
		window.location.href=link;					
	});

	$(".editarProducto1").click(function(event) {					
		var id=$(this).attr("id").replace("EDIT1","");
		id=id.replace("EDIT2","");					
		var link="<?php echo CCATALOGOS(); ?>" + "editarPaquete/"+id;					
		window.location.href=link;					
	});

	$(".btnAgregar").click(function(event)
	{
		var link = "<?php echo LINKPROYECTO('NuevoPaquete'); ?>";
		window.location.href = link;
	});

	$(".btnActualizar").click(function(event)
	{
		location.reload();
	});

	$("#btnDeshabilitarPaquetes").on("click", function()
	{
		if (confirm('¿Está seguro de deshabilitar todos los paquetes?'))
		{
			$.post("<?php echo LINKPROYECTO('Catalogos/deshabilitarPaquetes') ?>", {status: 1}, function(data){
				window.location = "<?php echo LINKPROYECTO('Paquetes') ?>";
			});
		}
	});

	$("#cmbEstatus").on("change", function()
	{
		if($(this).val() == "TODOS")
		{
			myTable.column(i_activo).search('').draw();
		}
		else
		{
			myTable.column(i_activo).search($(this).val()).draw();
		}
	});

	function cargarTablaProductos()
	{
		$('#tabla_productos').addClass('loadingtable');
		$('#tabla_productos tbody').html("");

		$.post("<?php echo LINKPROYECTO('ListadoProductosJson/PAQUETE') ?>", function(data)
		{
			var datos = JSON.parse(data);						
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#tabla_productos').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 50,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "codigo" },
						{ "data": "codigobarras" },
						{ "data": "nombre" },
						{ "data": "nombre_clasificacion" },						
						{ "data": "nombre_proveedor" },
						{ "data": "clavesat" },
						{ "data": "sucursales2" },
						{ "data": "costo_format" },
						{ "data": "precio_format" },
						{ "data": "ieps_format" },
						{ "data": "status2" },
						{ "data": null },
						{ "data": null },
					],
					"columnDefs": [
						{
							"targets": i_acciones,
							"data" : "id",
							"defaultContent": 									
							"<button style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>"+
							((Editar == 1) ? "<button class='editrow btn btn-minier btn-green dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='green'><i class='ace-icon fa fa-pencil-square-o bigger-120'></i></span></button>" : "")
						},
						{
                            "render": function ( data, type, row ) {
                                return (row.status==1) ? "<span class='label label-sm label-success'>SI</span>" : "<span class='label label-sm label-danger'>NO</span>";
                            },
                            "targets": i_activo,
                        },
						{
							"render": function ( data, type, row ) {
									return (row.subidobees==1) ? "<span class='badge badge-success'><i class='ace-icon fa fa-cloud-upload bigger-120'></i></span>" : "<span class='badge badge-danger'><i class='ace-icon fa fa-cloud-download bigger-120'></i></span>";
							},
							"targets": i_bees,
						},
						{ className: "text-right", "targets": [i_precio, i_ieps] },
						{
							"targets": columnas_hidden,
							"visible": false
						}
					]
				});

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#tabla_productos tbody').on( 'click', 'button.editrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();								
						window.location.href = "<?php echo LINKPROYECTO('EditarPaquete/'); ?>" + row.id;
						//window.open("<?php echo LINKPROYECTO('EditarPaquete/'); ?>" + row.id, '_blank');
					});
					$('#tabla_productos tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						window.location.href = "<?php echo LINKPROYECTO('VerPaquete/'); ?>" + row.id;
						//window.open("<?php echo LINKPROYECTO('VerPaquete/'); ?>" + row.id, '_blank');
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
			$('#tabla_productos').removeClass('loadingtable');
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
				"title": 'Listado - Productos',
				"exportOptions": {
						columns: [ 0, 1, 2, 3, 4, 5, 7, i_activo ]
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
			

	</body>
</html>