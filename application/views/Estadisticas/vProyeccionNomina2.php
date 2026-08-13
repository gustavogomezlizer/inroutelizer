<?php 
$data['title']="Proyección Nomina";
$this->load->view("vHead", $data); ?>
<?php $this->load->view("vMenu");?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Reportes / Proyección Nomina
								</small>
							</h1>
							
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-md-12">

									<div class="col-md-2">
										<label for="txtPeriodo">Mes</label>
										<input id="txtPeriodo" type="input" class="form-control" value="<?php echo $periodo; ?>" />
									</div>
									
									<div class="col-md-2">
										<label for="">Sucursal</label>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<option value="0" >TODAS</option>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value="<?php echo $item->id; ?>" ><?php echo $item->sucursal; ?></option>
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

									<div class="col-md-2">
										<label for="">Ruta</label>							
										<select name="cmbRuta" id="cmbRuta">
										</select>
									</div>

									<div class="col-md-4" align="right"><br/>
										<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
										<button class="btn btn-success btnActualizar">Actualizar</button>
									</div>

									<div class="col-md-12"><hr></div>
									
									<div class="row">
									
									<div class="row">

										<div class="row">										

											<div class="col-md-12">
												
												<?php

												$datosObj = $this->EstadisticasModel->getDatosObjetivos($periodo);
												//if($datosObj->num_rows()!=0){
												if(1==2){
													$diasMes = $datosObj->row()->diasMes;
													$diasTranscurridos = $datosObj->row()->diasTranscurridos;
													$promediopedidos = 1;
												}
												else{
													$diasMes = 0;
													$diasTranscurridos = 0;
													$promediopedidos = 0;
													}	
												?>
												

												<div class="clearfix col-md-12" align="right">
													<div class="pull-right tableTools-container"></div>
												</div>

											</div>
											</div>
											</div>

										<div id="div_informacion" style="display:none;">	
											<h4 id="lblTipoEmpleado" align="center"></h4>

											<div class="col-md-12">
												<div class="col-md-4">
														<table>
															<tr>
																<th width="40%">Nombre:</th>
																<td id="lblNombreEmpleado"></td>
															</tr>
															<tr>
																<th>Ruta:</th>
																<td id="lblRuta"></td>
															</tr>
															<tr>
																<th>Sucursal:</th>
																<td id="lblSucursal"></td>
															</tr>
														</table>
												</div>
												<div class="col-md-4">
														<table>
															<tr>
																<th width="50%">Periodo:</th>
																<td id="lblPeriodo"></td>
															</tr>
															<tr>
																<th>Dias transcurridos:</th>
																<td id="lblDiasTranscurridos"></td>
															</tr>
														</table>
												</div>
												<div class="col-md-4">
														<table>
															<tr>
																<th width="70%">Incentivo total:</th>
																<td id="lblIncentivoTotal"></td>
															</tr>
															<tr>
																<th>Puntos:</th>
																<td id="lblPuntos"></td>
															</tr>
														</table>
												</div>

											</div>

											<div class="row col-md-12"><br/>
												<button id="btnImprimir" class="btn btn-light">
													<span class="glyphicon glyphicon-print pull-left"></span>
													Imprimir
												</button>
											</div>

										</div>

										<div class="row col-md-12">
											<br/>
											<div class="table-responsive">
												<table id="table_categorias" width="100%" class="table table-striped table-bordered table-hover tablex">
													<thead>
														<tr>
															<th>Categoria</th>
															<th>Restriccion</th>
															<th>Objetivo</th>
															<th>Venta</th>
															<th>% Alcance</th>
															<th>Incentivo Categoria</th>
														</tr>
													</thead>

													<tbody>
													</tbody>
													
													<tfoot>
													</tfoot>

												</table>
											</div>
										</div>

										<div class="row col-md-12">

											<div class="table-responsive">
												<table id="table_certificacion" width="100%" class="table table-striped table-bordered table-hover tablex">

													<thead>
														<tr>
															<th>Concepto</th>
															<th>Resultado</th>
															<th>KPIS</th>
															<th>Incentivo Total</th>
															<th>Puntos Certificacion</th>
														</tr>
													</thead>

													<tbody>
													</tbody>
													
													<tfoot>
													</tfoot>

												</table>
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

</div><!-- /.main-container -->

<?php $this->load->view("vFooter"); ?>

	</body>
</html>
		
<script>

	var i_sucursal=0, i_ruta=1, i_categoria=2, i_objetivo=3, i_venta1=4, i_alcance1=5, i_venta2=6, i_alcance2=7,i_pago_categoria=8,i_total=9  /*, i_alcancegap=8, i_objetivodiario=9*/;
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
	}

	$("#cmbSucursal").on("change", function(){
		if($(this).val()==null){			
			$("#cmbRuta").html("");			
			return;
		}

		var idSucursal = $(this).val().toString();

		$.post("<?php echo CCATALOGOS('createComboRutasUsuariosRuta');?>", {sucursal: idSucursal},function(data){
			$("#cmbRuta").html(data);
		});
	});

	$("#btnAplicar").on("click", function(){
		/*if($("#cmbRuta").val()==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una ruta", "alert alert-warning");
		}
		else
		{
			cargarTablaProductos($("#txtPeriodo").val(), $("#cmbSucursal").val(), $("#cmbRuta").val());
		}*/

		if($("#cmbSucursal").val()==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una sucursal", "alert alert-warning");
		}
		else
		{
			cargarTablaProductos($("#txtPeriodo").val(), $("#cmbSucursal").val(), $("#cmbRuta").val());
		}
	});

	$("#btnImprimir").on("click", function(){
		var periodo = $("#txtPeriodo").val();
		var sucursal = $("#cmbSucursal").val();
		var ruta = $("#cmbRuta").val();

		window.open("<?php echo LINKPROYECTO('Estadisticas/proyeccionNominaPreventasPdf/') ?>" + ruta + "/" + periodo + "/" + sucursal, '_blank').focus();
	});

	function cargarTablaProductos(pPeriodo, pSucursal, pRuta)
	{
		$('#table_categorias').addClass('loadingtable');		

		$("#div_informacion").hide();
		$("#lblTipoEmpleado").text("");
		$("#lblNombreEmpleado").text("");
		$("#lblRuta").text("");
		$("#lblSucursal").text("");
		$("#lblPeriodo").text("");
		$("#lblDiasTranscurridos").text("");
		$("#lblIncentivoTotal").text("");
		$("#lblPuntos").text("");

		$("#table_certificacion tbody").html("");
		$('#table_categorias tbody').html("");
		$('#table_categorias tfoot').html("");

		if(pSucursal==null) pSucursal = "0";
		if(pRuta==null) pRuta = "0";

		$.post("<?php echo LINKPROYECTO('Estadisticas/getListaProyeccionNominaJson2') ?>", {periodo:pPeriodo, sucursal:pSucursal, ruta:pRuta}, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				var table = "";
				var foot = "";
				var sum_objetivo_da = 0, sum_ventas_da = 0, sum_alcance_da = 0, sum_incentivos_da = 0;

				for(var x in datos)
				{
					if(datos[x].categoria != "CHOCOLATE IMPULSO" && datos[x].categoria != "RTD")
					{
						table = table + "<tr>";
						table = table + "<td>" + datos[x].categoria + "</td>";
						table = table + "<td>" + datos[x].restriccion + "%</td>";
						table = table + "<td align = 'right'>$" + formatMoney(datos[x].objetivo) + "</td>";
						table = table + "<td align = 'right'>$" + formatMoney(datos[x].venta) + "</td>";
						table = table + "<td align = 'right'>" + formatMoney(datos[x].alcance) + "%</td>";
						table = table + "<td align = 'right'>$" + formatMoney(datos[x].incentivo_categoria) + "</td>";
						table = table + "</tr>";

						sum_objetivo_da = sum_objetivo_da + parseFloat(datos[x].objetivo);
						sum_ventas_da = sum_ventas_da + parseFloat(datos[x].venta);
						sum_incentivos_da = sum_incentivos_da + parseFloat(datos[x].incentivo_categoria);
					}
				}

				sum_alcance_da = (sum_ventas_da / sum_objetivo_da) * 100;

				foot = "<tr>";
				foot = foot + "<th>" + "Co. Sell Out FB" + "</th>";
				foot = foot + "<th>" + "" + "</th>";
				foot = foot + "<th class='text-right'>$" + formatMoney(sum_objetivo_da) + "</th>";
				foot = foot + "<th class='text-right'>$" + formatMoney(sum_ventas_da) + "</th>";
				foot = foot + "<th class='text-right'>" + formatMoney(sum_alcance_da) + "%</th>";
				foot = foot + "<th class='text-right'>$" + formatMoney(sum_incentivos_da) + "</th>";
				foot = foot + "</tr>";

				$('#table_categorias tbody').html(table);

				for(var x in datos)
				{
					if(datos[x].categoria == "CHOCOLATE IMPULSO")
					{
						foot = foot + "<tr>";
						foot = foot + "<th>" + "Co. Sell Out Impulso" + "</th>";
						foot = foot + "<th>" + datos[x].restriccion + "%</th>";
						foot = foot + "<th class='text-right'>$" + formatMoney(datos[x].objetivo) + "</th>";
						foot = foot + "<th class='text-right'>$" + formatMoney(datos[x].venta) + "</th>";
						foot = foot + "<th class='text-right'>" + formatMoney(datos[x].alcance) + "%</th>";
						foot = foot + "<th class='text-right'>$" + formatMoney(datos[x].incentivo_categoria) + "</th>";
						foot = foot + "</tr>";
					}
				}

				for(var x in datos)
				{
					if(datos[x].categoria == "RTD")
					{
						foot = foot + "<tr>";
						foot = foot + "<th>" + "Co. Sell Out RTD" + "</th>";
						foot = foot + "<th>" + datos[x].restriccion + "%</th>";
						foot = foot + "<th class='text-right'>$" + formatMoney(datos[x].objetivo) + "</th>";
						foot = foot + "<th class='text-right'>$" + formatMoney(datos[x].venta) + "</th>";
						foot = foot + "<th class='text-right'>" + formatMoney(datos[x].alcance) + "%</th>";
						foot = foot + "<th class='text-right'>$" + formatMoney(datos[x].incentivo_categoria) + "</th>";
						foot = foot + "</tr>";
					}
				}

				$('#table_categorias tfoot').html(foot);
			}
			else
			{
				//myTable.clear().draw();
			}

			$("#div_informacion").show();
			cargarTablaCertificacion(pPeriodo, pSucursal, pRuta);

		}).always(function() {
			$('#table_categorias').removeClass('loadingtable');
		});
	}

	function cargarTablaCertificacion(pPeriodo, pSucursal, pRuta)
	{
		$('#table_certificacion').addClass('loadingtable');
		$('#div_informacion').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('Estadisticas/getProyeccionNominaCertificado') ?>", {periodo:pPeriodo, sucursal:pSucursal, ruta:pRuta}, function(data)
		{
			var datos = JSON.parse(data);

			if(datos != null)
			{
				//var porcentaje_cumplimiento_agenda = (datos.visitado / datos.visitas) * 100;
				//var porcentaje_rutas_laboradas = (datos.rutas_laboradas / datos.diasTranscurridos) * 100;
				var table = "";

				$("#lblTipoEmpleado").text(datos.tipo_empleado);
				$("#lblNombreEmpleado").text(datos.nombre_empleado);
				$("#lblRuta").text(datos.nombre_ruta);
				$("#lblSucursal").text(datos.nombre_sucursal);
				$("#lblPeriodo").text(datos.periodo);
				$("#lblDiasTranscurridos").text(datos.diasTranscurridos);
				$("#lblIncentivoTotal").text("$" + formatMoney(datos.sum_total_incentivo));
				$("#lblPuntos").text(datos.sum_total_puntos + " (" + datos.certifica_texto + ")");

				//################## INICIO GENERAL ################################################
				table = "<tr>";
				table = table + "<th width='40%'>" + "Clientes Programados" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.clientes_programados + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.promedio_clientes_programados + "</td>";
				table = table + "<td width='15%' class='text-right' style='vertical-align: middle' rowspan='2'>$" + formatMoney(datos.incentivo_cumplimiento_agenda) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_clientes_programados + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Cumpliento de Agenda" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.visitado + "</td>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.porcentaje_cumplimiento_agenda) + "%</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_cumplimiento_agenda + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Rutas Laboradas" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.rutas_laboradas + "</td>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.porcentaje_rutas_laboradas) + "%</td>";
				table = table + "<td width='15%' class='text-right'>$" + formatMoney(datos.incentivo_rutas_laboradas) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_rutas_laboradas + "</td>";
				table = table + "</tr>";
				//################## FIN GENERAL ################################################

				//################## INICIO DA ################################################
				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Co. Sell Out FB" + "</th>";
				table = table + "<td width='15%' class='text-right'>$" + formatMoney(datos.acumulado_importe_da) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.porcentaje_acumulado_alcance_da) + "%</td>";
				table = table + "<td width='15%' class='text-right'>$" + formatMoney(datos.acumulado_incentivo_da) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_cobertura_sallout_da + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Drop Size FB" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.dropsize_da + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.dropsize_da + "</td>";
				table = table + "<td width='15%' class='text-right' style='vertical-align: middle' rowspan='2'>$" + formatMoney(datos.incentivo_pedidos_da) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_dropsize_da + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "PP FB" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.promedio_ventas_da + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.promedio_ventas_da + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_promedio_ventas_da + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Efectividad Venta FB" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.cantidad_ventas_da) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.efectividad_ventas_da) + "%</td>";
				table = table + "<td width='15%' class='text-right'>" + "-" + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_efectividad_ventas_da + "</td>";
				table = table + "</tr>";
				//################## FIN DA ################################################

				//################## INICIO IMPULSO ################################################
				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Co. Sell Out Impulso" + "</th>";
				table = table + "<td width='15%' class='text-right'>$" + formatMoney(datos.acumulado_importe_impulso) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.porcentaje_acumulado_alcance_impulso) + "%</td>";
				table = table + "<td width='15%' class='text-right'>$" + formatMoney(datos.acumulado_incentivo_impulso) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_cobertura_sallout_impulso + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Drop Size Impulso" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.dropsize_impulso + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.dropsize_impulso + "</td>";
				table = table + "<td width='15%' class='text-right' style='vertical-align: middle' rowspan='2'>$" + formatMoney(datos.incentivo_pedidos_impulso) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_dropsize_impulso + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "PP Impulso" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.promedio_ventas_impulso + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.promedio_ventas_impulso + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_promedio_ventas_impulso + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Efectividad Venta Impulso" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.cantidad_ventas_impulso) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.efectividad_ventas_impulso) + "%</td>";
				table = table + "<td width='15%' class='text-right'>" + "-" + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_efectividad_ventas_impulso + "</td>";
				table = table + "</tr>";
				//################## FIN IMPULSO ################################################

				//################## INICIO RTD ################################################
				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Co. Sell Out RTD" + "</th>";
				table = table + "<td width='15%' class='text-right'>$" + formatMoney(datos.acumulado_importe_rtd) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.porcentaje_acumulado_alcance_rtd) + "%</td>";
				table = table + "<td width='15%' class='text-right'>$" + formatMoney(datos.acumulado_incentivo_rtd) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Drop Size RTD" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.dropsize_rtd + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.dropsize_rtd + "</td>";
				table = table + "<td width='15%' class='text-right' style='vertical-align: middle' rowspan='2'>$" + formatMoney(datos.incentivo_pedidos_rtd) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "PP RTD" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.promedio_ventas_rtd + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.promedio_ventas_rtd + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Efectividad Venta RTD" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.cantidad_ventas_rtd) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.efectividad_ventas_rtd) + "%</td>";
				table = table + "<td width='15%' class='text-right'>" + "-" + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "</tr>";
				//################## FIN RTD ################################################

				//################## INICIO GENERAL ################################################
				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Co. Categorias" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.cobertura_categorias + "</td>";
				table = table + "<td width='15%' class='text-right'>" + formatMoney(datos.porcentaje_cobertura_categorias) + "%</td>";
				table = table + "<td width='15%' class='text-right'>" + "-" + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_cobertura_categorias + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "No. Ruta Certificadas" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.certifica + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "<td width='15%' class='text-right'>$" + formatMoney(datos.incentivo_certificacion) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Certificación de la localidad" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "<td width='15%' class='text-right'>$" + formatMoney(datos.incentivo_certificacion_localidad) + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "% Efectividad de reparto" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + datos.porcentaje_efectividad_rutas + "%" + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "-" + "</td>";
				table = table + "<td width='15%' class='text-right'>" + datos.puntos_porcentaje_efectividad_rutas + "</td>";
				table = table + "</tr>";

				table = table + "<tr>";
				table = table + "<th width='40%'>" + "Totales" + "</th>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "<td width='15%' class='text-right'>" + "" + "</td>";
				table = table + "<th width='15%' class='text-right'>$" + formatMoney(datos.sum_total_incentivo) + "</th>";
				table = table + "<th width='15%' class='text-right'>" + datos.sum_total_puntos + "</th>";
				table = table + "</tr>";
				//################## FIN GENERAL ################################################

				$('#table_certificacion tbody').html(table);
			}
		}).always(function() {
			$('#table_certificacion').removeClass('loadingtable');
			$('#div_informacion').removeClass('loadingtable');
		});		
	}
</script>