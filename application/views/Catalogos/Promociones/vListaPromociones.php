<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); 
$editar=VERIFICARPERFILFUNCION("Catalogos","editarProducto",$this->session->userdata('perfilLIZER'));
$nuevo=VERIFICARPERFILFUNCION("Catalogos","nuevoProducto",$this->session->userdata('perfilLIZER'));
?>

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">			
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Promociones</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-12">
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
								<!-- <h3 class="header smaller lighter blue">jQuery dataTables</h3> -->
								<div class="clearfix">
									<div class="pull-right">
										<?php if($nuevo==1){ ?>
										<button class="btn btn-success btnAgregar">Agregar</button>
										<?php } ?>
										<button class="btn btn-primary btnActualizar">Actualizar</button></div>
								</div>
								<br>

								<div class="clearfix">
									<div class="pull-right tableTools-container"></div>
								</div>							

								<div class="table-header">Listado de Promociones</div>
								
								<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
									<table id="tabla_productos" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Codigo</th>
												<th>Tipo</th>
												<th>Sucursales</th>
												<th>Activo</th>
												<th>Acciones</th>
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

<!--</div>--><!-- /.main-container -->

<!-- basic scripts -->
<?php $this->load->view("vFooter"); ?>
				
<script type="text/javascript">

	var CARGAR_BOTONES_TABLA = "0";

	var i_codigo=0, i_tipo=1, i_sucursales=2, i_activo=3, i_acciones=4;

	var myTable = $('#tabla_productos').DataTable({
		"language": {
			"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
		},
	});

	window.onload = function()
	{
		cargarTablaProductos();
	}

	$(".btnAgregar").click(function(event) {					
		var link = "<?php echo LINKPROYECTO('NuevaPromocion'); ?>";
		window.location.href = link;
	});

	$(".btnActualizar").click(function(event) {						
		location.reload();
	});

	function cargarTablaProductos()
	{
		$('#tabla_productos').addClass('loadingtable');
		$('#tabla_productos tbody').html("");

		$.post("<?php echo LINKPROYECTO('ListadoPromocionesJson') ?>", function(data)
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
						{ "data": "tipo2" },
						{ "data": "sucursales2" },
						{ "data": "status2" },
						{ "data": null },
					],
					"columnDefs": [
						{
							"targets": i_acciones,
							"data" : "id",
							"defaultContent": 									
							"<button style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>"+
							"<button class='editrow btn btn-minier btn-green dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='green'><i class='ace-icon fa fa-pencil-square-o bigger-120'></i></span></button>"
						},
						{
                            "render": function ( data, type, row ) {
                                return (row.status==1) ? "<span class='label label-sm label-success'>SI</span>" : "<span class='label label-sm label-danger'>NO</span>";
                            },
                            "targets": i_activo,
                        },
					]
				});

				if(CARGAR_BOTONES_TABLA=="0")
				{
					CARGAR_BOTONES_TABLA = "1";

					$('#tabla_productos tbody').on( 'click', 'button.editrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();								
						window.location.href = "<?php echo LINKPROYECTO('EditarPromocion/'); ?>" + row.id;
					});
					$('#tabla_productos tbody').on( 'click', 'button.showrow', function () {
						var row = myTable.row( $(this).parents('tr') ).data();
						window.location.href = "<?php echo LINKPROYECTO('VerPromocion/'); ?>" + row.id;
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
						columns: [ 0, 1, 2, 3, 4, 5 ]
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
