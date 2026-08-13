
<?php 
$data['title']="LIZER Listado";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu");
$editar=VERIFICARPERFILFUNCION("Catalogos","editarClientes",$this->session->userdata('perfil'));
$nuevo=VERIFICARPERFILFUNCION("Catalogos","nuevoCliente",$this->session->userdata('perfil'));
$clientesMapa = VERIFICARPERFILFUNCION("Catalogos","clientesMapa",$this->session->userdata('perfil'));
 ?>



			<div class="main-content">
				<div class="main-content-inner">					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Catalogos / Clientes
									
									
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
											<p>Última Actualización Bees: <?php //echo GETBEESDATOS()->ultima_actualizacion; ?></p>
											<div class="pull-right">

											<?php if($clientesMapa==1){ ?>
												<a target="_blank" href="<?php echo LINKPROYECTO('ClientesMapa'); ?>" class="btn btn-warning">Ver Clientes En Mapa</a>
											<?php } ?>

											<?php if($nuevo==1) { ?>
												<button class="btn btn-success btnAgregar">Agregar</button>
											<?php } ?>

											<button class="btn btn-primary btnActualizar">Actualizar</button></div>
										</div>
										<br>

										<div class="row">
											<div class="col-sm-2">
												<select id="cmbFiltroSucursal" name="sucursal" class="form-control">
													<?php if(ISMULTISUCURSAL()) { ?>
														<option value=0 selected>TODAS</option>
														<?php foreach (GETLISTASUCURSALES() as $item) { ?>
															<option value="<?php echo $item->id; ?>" <?php echo (GETSUCURSAL()==$item->id) ? 'selected' : '' ?>  ><?php echo $item->sucursal; ?></option>
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
											Listado de Clientes.
										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
											<table id="tabla_clientes" width="100%" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Codigo</th>
														<th>Cliente</th>
														<th>Domicilio</th>
														<th>Zona</th>
														<th>Proveedores</th>
														<th>Sucursal</th>
														<th>Activo</th>
														<th>Bees</th>
														<th>Acciones</th>
														<th>localidad</th>
														<th>latitud</th>
														<th>longitud</th>
														<th>diasvisita</th>
														<th>telefono</th>
														<th>clasificacion</th>
														<th>isChuponera</th>
														<th>isMundoCafe</th>
														<th>isEnfriador</th>
														<th>clientedigitalizado</th>
													</tr>
												</thead>
												<tbody></tbody>

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

		<!-- basic scripts -->
	<?php $this->load->view("vFooter"); ?>
		
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>
		<!-- inline scripts related to this page -->
<script type="text/javascript">

	var CARGAR_BOTONES_TABLA = "0";
	var i_codigo=0, i_cliente=1, i_domicilio=2, i_zona=3, i_proveedores=4, i_sucursal=5, i_activo=6, i_bees=7, i_acciones=8, i_ciudad=9, i_latitud=10, i_longitud=11, i_diasvisita=12, i_telefono=13, i_clasificacion=14, i_isChuponera=15, i_isMundoCafe=16, i_isEnfriador=17, i_clientedigitalizado=18;

	var columns_excel;

	columns_excel = [ i_codigo, i_cliente, i_domicilio, i_zona, i_proveedores, i_sucursal, i_ciudad, i_latitud, i_longitud, i_diasvisita, i_activo, i_telefono, i_clasificacion, i_isChuponera, i_isMundoCafe, i_isEnfriador, i_clientedigitalizado ];

	var Editar = "<?php echo $editar; ?>";

		window.onload = function()
		{
			cargarTableClientes($("#cmbFiltroSucursal").val());
		}

		$("#cmbFiltroSucursal").on("change", function(){
			cargarTableClientes($("#cmbFiltroSucursal").val());
		});

			//jQuery(function($) {
			var myTable = 
				$('#tabla_clientes')
				.DataTable( {
					"language": {
				            "url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
				        },
						"pageLength": 10,
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
						"order": [[0,"asc"]]
			    });				

				function cargarTableClientes(pIdsucursal)
				{
					$('#tabla_clientes').addClass('loadingtable');
					$('#tabla_clientes tbody').html("");

					$.post("<?php echo LINKPROYECTO('ListadoClientesJson') ?>", { idsucursal:pIdsucursal }, function(data){
						var datos = JSON.parse(data);
						if(datos.length > 0)
						{
							myTable.destroy();
							myTable = $('#tabla_clientes').DataTable({
								"language": {
									"url": "<?php echo RUTAFOLDERASSETS("json/datatables-spanish.json"); ?>"
								},
								"pageLength": 50,
								"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
								"order": [[0,"asc"]],
								"aaData": datos,
								"columns": [
									{ "data": "codigo" },
									{ "data": "nombre" },
									{ "data": "domicilio" },
									{ "data": "zona" },
									{ "data": "proveedores" },
									{ "data": "sucursal" },
									{ "data": "status2" },
									{ "data": null },
									{ "data": null },
									{ "data": "ciudad" },
									{ "data": "latitud" },
									{ "data": "longitud" },
									{ "data": null },
									{ "data": "telefono" },
									{ "data": "clasificacion_cliente" },
									{ "data": "isChuponera" },
									{ "data": "isMundoCafe" },
									{ "data": "isEnfriador" },
									{ "data": "cliente_digitalizado" },
								],
								"columnDefs": [
									{
										"targets": i_acciones,
										"data" : "id",
										"defaultContent": 									
										"<button style='margin-right:5px;' class='showrow btn btn-minier btn-blue dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='blue'><i class='ace-icon fa fa-eye bigger-120'></i></span></button>" +
										((Editar == 1) ? "<button class='editrow btn btn-minier btn-green dropdown-toggle' data-toggle='dropdown' data-position='auto'><span class='green'><i class='ace-icon fa fa-pencil-square-o bigger-120'></i></span></button>" : "")
									},
									{
										"render": function ( data, type, row ) {
												return DiasVisita(row.diasvisita);
										},
										"targets": i_diasvisita,
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
									{
										"targets": [ i_ciudad, i_latitud, i_longitud, i_diasvisita, i_telefono, i_clasificacion, i_isChuponera, i_isMundoCafe, i_isEnfriador, i_clientedigitalizado ],
										"visible": false
									}
								]
							});

							if(CARGAR_BOTONES_TABLA=="0")
							{
								CARGAR_BOTONES_TABLA = "1";

								$('#tabla_clientes tbody').on( 'click', 'button.editrow', function () {
									var row = myTable.row( $(this).parents('tr') ).data();								
									window.location.href = "<?php echo LINKPROYECTO('EditarCliente/'); ?>" + row.id;
								});
								$('#tabla_clientes tbody').on( 'click', 'button.showrow', function () {
									var row = myTable.row( $(this).parents('tr') ).data();
									window.location.href = "<?php echo LINKPROYECTO('VerCliente/'); ?>" + row.id;
								});
							}

							botonesTabla();
						}
						else
						{
							myTable.clear().draw();
							//alert("Ocurrio un error al eliminar el empleado");
						}
					}).always(function() {
						$('#tabla_clientes').removeClass('loadingtable');
					});
				}

				function botonesTabla()
				{
					$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
				
					new $.fn.dataTable.Buttons( myTable, {
						buttons: [

						{
							"extend": "excel",
							"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
							"className": "btn btn-white btn-primary btn-bold",
							"titleAttr": "CONCENTRADO",
							"title": 'Listado de clientes',
							"exportOptions": {
									columns: columns_excel
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
					} );
					myTable.buttons().container().appendTo( $('.tableTools-container') );
					
					//style the message box
					/*var defaultCopyAction = myTable.button(1).action();
					myTable.button(1).action(function (e, dt, button, config) {
						defaultCopyAction(e, dt, button, config);
						$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
					});
					
					myTable.on( 'select', function ( e, dt, type, index ) {
						if ( type === 'row' ) {
							$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
						}
					} );
					myTable.on( 'deselect', function ( e, dt, type, index ) {
						if ( type === 'row' ) {
							$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
						}
					} );*/
				}
			
				
				function DiasVisita(pDiasvisita)
				{
					if(pDiasvisita==null){
						return "";
					}
					
					var dias = ['ninguno','Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
					var diavisita = pDiasvisita.split(',');
					var misdias = "";
					for(var item in diavisita){
						misdias = misdias + dias[diavisita[item]] + ',';
					}

					return misdias.substring(0, misdias.length - 1);
				}
				
			
			
			
			
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#tabla_clientes > thead > tr > th input[type=checkbox], #tabla_clientes_wrapper input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$('#tabla_clientes').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) myTable.row(row).select();
						else  myTable.row(row).deselect();
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#tabla_clientes').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(this.checked) myTable.row(row).deselect();
					else myTable.row(row).select();
				});
			
			
			
				$(document).on('click', '#tabla_clientes .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();
					e.stopPropagation();
					e.preventDefault();
				}); 
				/*termina la configuracion de #dinamyc-table*/
			
				/*empieza configuracion para ver mapas*/
				$(".verMapa1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("MAP1","");
					id=id.replace("MAP2","");
					//$("#modalMapa").modal("show");
					var link="<?php echo CCATALOGOS(); ?>" + "verCliente/"+id;
					window.location.href=link;
				  	//window.open(link,"_blank");
					//alert(id);
				});

				$(".btnAgregar").click(function(event) {
					window.location.href = "<?php echo CCATALOGOS('NuevoCliente'); ?>";
				});

				$(".btnActualizar").click(function(event) {						
					location.reload();
				});
			//})

		</script>
			

	</body>
</html>
