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
									Almacen / Armado de Ruta
								</small>
							</h1>
						</div><!-- /.page-header -->						

						<div class="row">							

							<div class="col-xs-12">
								<div class="row">

									<div class="col-xs-2">
										<label for="txtFecha">Fecha</label>
										<input type="date" id="txtFecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" />
									</div>

									<div class="col-xs-2">
										<label for="cmbSucursal">Sucursal</label>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<!--<option value="0">TODAS</option>-->
												<?php foreach ($listaSucursales->result() as $item) { ?>
													<option value="<?php echo $item->id; ?>" <?php echo (GETSUCURSAL()==$item->id) ? 'selected' : ''; ?> ><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } else { ?>
												<?php foreach ($listaSucursales->result() as $item) { ?>
														<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
													<?php /*if(GETSUCURSAL()==$item->id) { ?>
														<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
													<?php }*/ ?>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="col-xs-2">
										<br/><button id="btnAplicar" class="btn btn-primary">Aplicar</button>
									</div>
								</div>

								<hr>
							</div>							

							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

									<h4 id="lblHora">Fecha: <?php echo date('d/m/Y H:i:s'); ?></h4>

									<div class="row">
										<div align="right" class="col-xs-6">
											
											<button id="btnCerrarTodo" class="btn btn-success">Cerrar Todas las Rutas</button>											

										</div>
									</div><br/>

									<div class="row">

										<div class="col-xs-6">

											<div class="table-header">
												<b id="lblTituloDistribucionNegocio">Rutas</b>
											</div>

											<div class="table-responsive">
												<table id="table_rutas" width="100%" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Ruta</th>
															<th>Estatus</th>
															<th>Acciones</th>
														</tr>
													</thead>

													<tbody></tbody>

													<tfoot></tfoot>
												</table>											
											</div>
										</div>

										<div class="col-xs-6" style="border: thin solid black">
											<h3>Estatus Confirmacion de Entregas:</h3>
											<h2 id="lblEstatusConfirmacionEntregas"></h2>

											<button id="btnAbrirConfirmacionEntregas" class="btn btn-app btn-info btn-sm no-radius" style="display:none;">
												<i class="ace-icon fa fa-key bigger-200"></i>
												Abrir
											</button>
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
		
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script>

	var i_sucursal=0, 
	i_pendientes=1, 
	i_fechas=2;

	var CARGAR_BOTONES_TABLA = "0";

	window.onload = function()
	{
	}

	$("#btnAplicar").on("click", function(){
		var idsucursal = $("#cmbSucursal").val();
		var fecha = $("#txtFecha").val();

		var now = new Date();
		//var dateStringWithTime = moment(now).format('DD/MM/YYYY HH:mm:ss');
		var dateStringWithTime = moment(now).format('HH:mm:ss');

		$("#lblHora").text("Fecha: " + fecha + " " + dateStringWithTime);

		cargarRutas(idsucursal, fecha);
	});

	$("#btnCerrarTodo").on("click", function(){
		var res = confirm("¿Está seguro de finalizar el armado de todas las rutas de la sucursal: " + $("#cmbSucursal option:selected").text() + "?");

		var idsucursal = $("#cmbSucursal").val();

		if(res)
		{
			ActualizarEstatusRutaTodas(idsucursal);	
		}
	});

	$("#btnAbrirConfirmacionEntregas").on("click", function(){
		var res = confirm("¿Está seguro de abrir la confirmación de entregas en la sucursal: " + $("#cmbSucursal option:selected").text() + "?");

		var idsucursal = $("#cmbSucursal").val();
		var fecha = $("#txtFecha").val();

		if(res)
		{
			BorrarConfirmacionEntregas(idsucursal, fecha);	
		}
	});

	function ActualizarEstatus(pIdRuta, pStatus)
	{
		$.post("<?php echo LINKPROYECTO('Almacen/actualizarEstatusRuta') ?>", {idruta: pIdRuta, estatus: pStatus}, function(data)
		{
			
		}).always(function() {
			$("#btnAplicar").click();
		});		
	}

	function ActualizarEstatusRutaTodas(pIdSucursal)
	{
		$.post("<?php echo LINKPROYECTO('Almacen/actualizarEstatusRutaTodas') ?>", {idsucursal: pIdSucursal}, function(data)
		{
			
		}).always(function() {
			$("#btnAplicar").click();
		});		
	}

	function BorrarConfirmacionEntregas(pIdSucursal, pFecha)
	{
		$.post("<?php echo LINKPROYECTO('Almacen/borrarConfirmacionEntregas') ?>", {idsucursal: pIdSucursal, fecha: pFecha}, function(data)
		{
			
		}).always(function() {
			$("#btnAplicar").click();
		});		
	}

	function cargarRutas(pIdSucursal, pFecha)
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		var cadena = "";		

		$('#table_rutas').addClass('loadingtable');
		$('#table_rutas tbody').html("");
		$('#table_rutas tfoot').html("");

		$.get("<?php echo LINKPROYECTO('Almacen/getRutasEstatusJson/') ?>" + pIdSucursal + "/" + pFecha, function(data)
		{
			var datos = JSON.parse(data);
			
			if(datos.length > 0)
			{
				cadena = "";
				var estatus_confirmacion_entregas = "ABIERTO";
				for(var x in datos)
				{
					cadena = cadena + "<tr>";
					cadena = cadena + "<td>" + datos[x]["ruta"] + "</td>";
					cadena = cadena + "<td>" + datos[x]["estatus"] +"</td>";
					cadena = cadena + "<td>";
						if(datos[x]["estatus"] == "CERRADO")
						{
							<?php if(GETPERFILUSUARIO() == "SISTEMAS" || GETPERFILUSUARIO() == "ADMINISTRADOR" || GETPERFILUSUARIO() == "AUXILIAR OPERACIONES")  { ?>
								cadena = cadena + "<button onclick='ActualizarEstatus(" + datos[x]["id"] + ",0" + ")'><img src='<?php echo RUTAFOLDERASSETS('icons/autorize.png'); ?>'/></button>";
							<?php } ?>
						}
						else
						{
							cadena = cadena + "<button onclick='ActualizarEstatus(" + datos[x]["id"] + ",1" + ")'><img src='<?php echo RUTAFOLDERASSETS('icons/finish.png'); ?>'/></button>";
						}
					cadena = cadena + "</td>";
					cadena = cadena + "</tr>";					

					estatus_confirmacion_entregas = datos[x]["estatus_confirmacion_entregas"];
				}

				$("#lblEstatusConfirmacionEntregas").text(estatus_confirmacion_entregas);

				<?php if(GETPERFILUSUARIO() == "SISTEMAS" || GETPERFILUSUARIO() == "ADMINISTRADOR" || GETPERFILUSUARIO() == "AUXILIAR OPERACIONES") { ?>
					if(estatus_confirmacion_entregas == "CERRADO")
					{
						$("#btnAbrirConfirmacionEntregas").show();
					}
					else
					{
						$("#btnAbrirConfirmacionEntregas").hide();
					}
				<?php } ?>

				$("#table_rutas tbody").html(cadena);
			}
			else
			{
				
			}
		}).always(function() {
			$('#table_rutas').removeClass('loadingtable');
		});
	}	
	
</script>