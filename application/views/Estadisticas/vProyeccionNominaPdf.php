<!DOCTYPE HTML>
<html5 lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<title>PROYECCION DE NOMINA VENTAS</title>

		<style type="text/css">
			.normal1 {
			  width: 690px;
			 
			  border: 1px solid #000;
			  border-collapse: collapse;
			  border-spacing: 0px 0px;
			}
			.normal1 th, .normal1 td {
			  border: 1px solid #000;
			  border-collapse: collapse;
			  border-spacing : 0;
			}

			.normal2 {
			  
			 
			  width: 225px;
			  border: 1px solid #000;
			  border-collapse: collapse;
			  border-spacing : 0;
			}

			.encabezado {
			  text-align: center;
			  width: 660px;
			  border: 0px;
			  border-collapse: collapse;
			}
			.encabezado th, .encabezado td {
			  text-align: center;
			  border: 0px;
			}
			.datos {
			  text-align: left;
			  width: 341px;
			  border: 1px;
			  border-collapse: collapse;
			}
			.datos th, .datos td {
			  text-align: left;
			  border: 1px;
			}
			.detallesConciliacion {
			  text-align: left;
			  width: 169px;
			  border: 0px;
			  border-collapse: collapse;
			}
			.detallesConciliacion th, .detallesConciliacion td {
			  text-align: left;
			  border: 0px;
			}
			.headTabla {
			  text-align: center;
			  width: 100px;
			  border: 1px;
			  border-collapse: collapse;
			  font-size: 12;
			}
			.headTabla th, .headTabla td {
			  text-align: center;
			  border: 1px;
			}
			.pie {
			  width: 220px;
			  border: 1px solid #000;
			  border-collapse: collapse;
			}
			.pie th, .pie td {
			  border: 1px solid #000;
			}
			
					
			.subcelda2 {
			  width: 140px;
			  border: 0px;
			 
			}
			
			.subcelda2 th, .subcelda2 td {
			  border: 0px;
			}
			
			.principales{
				background-image: url(assets/images/html2pdf/noxfondo.jpg);
				background-repeat: no-repeat;
				background-attachment: fixed;
    			background-position: center; 
    			background-size: 30% 20%;
			}

			.letraPequeña{
				font-size: 8;
			}
			
			.border-outside-table{
				filter: alpha(opacity=40); opacity: 0.95;border:1px #000 solid;
			}

			body{font-size:14px; font-family: Arial, Helvetica, sans-serif; }

			*{margin:5px 2px 2px 2px;padding:0;}

			.table   { display: table; border: 0.1px solid #000;}
			.tablerow  { display: table-row; border: 0.1px solid #000; width:100%;}
			.tablecell { display: table-cell; border: 0.1px solid #000; width:100%;}

			.zebra tbody tr:nth-child(odd) {
				background-color: #ccc;
			}

			.table_encabezado {
				background-color: #777777;
			}

			.table_totales {
				background-color: #9E9E9E;
			}
			
		</style>
	</head>

	<body>

		<?php 
			function formatMoney($value)
			{
				return '$'.number_format($value, 2, '.', ',');
			}
			function formatPorcentaje($value)
			{
				return number_format($value, 2, '.', ',').'%';
			}
		?>
	
		<div class="principales">

			<header>

				<table width="100%">
					<tr>
						<td width="30%" align="left"><img src="<?php echo $informacion_empresa->logo ?>" width="180" height="80" /></td>
						<td width="70%" align="left"><h1>PROYECCION DE NOMINA VENTAS</h1></td>
						<td width="10%">&nbsp;</td>
					</tr>
				</table>				

			</header>

			<main>

				<h2 align="center"><?php echo $certificado->tipo_empleado; ?></h2>

				<table width="100%" cellspacing=0 align="center">
					<tr>
						<td>
							<table>
								<tr>
									<th>Nombre:</th>
									<td><?php echo $certificado->nombre_empleado; ?></td>
								</tr>
								<tr>
									<th>Ruta:</th>
									<td><?php echo $certificado->nombre_ruta; ?></td>
								</tr>
								<tr>
									<th>Sucursal:</th>
									<td><?php echo $certificado->nombre_sucursal; ?></td>
								</tr>
							</table>
						</td>

						<td>
							<table>
								<tr>
									<th>Periodo:</th>
									<td><?php echo $certificado->periodo; ?></td>
								</tr>
								<tr>
									<th>Dias transcurridos:</th>
									<td><?php echo $certificado->diasTranscurridos; ?></td>
								</tr>
							</table>
						</td>

						<td>
							<table>
								<tr>
									<th>Incentivo total:</th>
									<td><?php echo formatMoney($certificado->sum_total_incentivo); ?></td>
								</tr>
								<tr>
									<th>Puntos:</th>
									<td><?php echo $certificado->sum_total_puntos."($certificado->certifica_texto)"; ?></td>
								</tr>
							</table>
						</td>
					</tr>

				</table>
		
				<table width="100%" class="zebra" border=1 cellspacing=0 align="center">
					<thead>
						<tr class="table_encabezado">
							<th>Categoria</th>
							<th>Restriccion</th>
							<th>Objetivo</th>
							<th>Venta</th>
							<th>% Alcance</th>
							<th>Incentivo Categoria</th>
						</tr>
					</thead>

					<tbody>

						<?php 
							$sum_objetivo_da = 0;
							$sum_ventas_da = 0;
							$sum_alcance_da = 0;
							$sum_incentivos_da = 0;
						?>

						<?php foreach ($lista_categorias_proyeccion as $item) { ?>							
							
							<?php if($item["categoria"] != "CHOCOLATE IMPULSO") { ?>

								<tr>
									<td><?php echo $item["categoria"]; ?></td>
									<td><?php echo formatPorcentaje($item["restriccion"]); ?></td>
									<td align="right"><?php echo formatMoney($item["objetivo"]); ?></td>
									<td align="right"><?php echo formatMoney($item["venta"]); ?></td>
									<td align="right"><?php echo formatPorcentaje($item["alcance"]); ?></td>
									<td align="right"><?php echo formatMoney($item["incentivo_categoria"]); ?></td>
								</tr>

								<?php 
									$sum_objetivo_da = $sum_objetivo_da + floatval($item["objetivo"]);
									$sum_ventas_da = $sum_ventas_da + floatval($item["venta"]);
									$sum_incentivos_da = $sum_incentivos_da + floatval($item["incentivo_categoria"]);
								?>

							<?php } ?>

						<?php } ?>

						<?php
							$sum_alcance_da = ($sum_ventas_da / $sum_objetivo_da) * 100;
						?>

					</tbody>

					<tfoot>

						<tr class="table_totales">
							<th>Co. Sell Out DA</th>
							<th></th>
							<th align="right"><?php echo formatMoney($sum_objetivo_da); ?></th>
							<th align="right"><?php echo formatMoney($sum_ventas_da); ?></th>
							<th align="right"><?php echo formatPorcentaje($sum_alcance_da); ?></th>
							<th align="right"><?php echo formatMoney($sum_incentivos_da); ?></th>
						</tr>

						<?php foreach ($lista_categorias_proyeccion as $item) { ?>

							<?php if($item["categoria"] == "CHOCOLATE IMPULSO") { ?>

								<tr class="table_totales">
									<th>Co. Sell Out Impulso</th>
									<th><?php echo formatPorcentaje($item["restriccion"]); ?></th>
									<th align="right"><?php echo formatMoney($item["objetivo"]); ?></th>
									<th align="right"><?php echo formatMoney($item["venta"]); ?></th>
									<th align="right"><?php echo formatPorcentaje($item["alcance"]); ?></th>
									<th align="right"><?php echo formatMoney($item["incentivo_categoria"]); ?></th>
								</tr>

							<?php } ?>

						<?php } ?>

					</tfoot>

				</table><br/>

				<table width="100%" class="zebra" border=1 cellspacing=0 align="center">
					<thead>
						<tr class="table_encabezado">
							<th>Concepto</th>
							<th>Resultado</th>
							<th>KPIS</th>
							<th>Incentivo Total</th>
							<th>Puntos Certificacion</th>
						</tr>
					</thead>

					<tbody>

						<?php 
							$porcentaje_cumplimiento_agenda = ($certificado->visitado / $certificado->visitas) * 100;
						?>

						<tr>
							<th>Cumpliento de Agenda</th>
							<td align="right"><?php echo $certificado->visitado; ?></td>
							<td align="right"><?php echo formatPorcentaje($porcentaje_cumplimiento_agenda); ?></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>Rutas Laboradas</th>
							<td align="right"><?php echo $certificado->rutas_laboradas; ?></td>
							<td align="right"><?php echo formatPorcentaje($certificado->porcentaje_rutas_laboradas); ?></td>
							<td align="right"><?php echo formatMoney($certificado->incentivo_rutas_laboradas); ?></td>
							<td align="right"><?php echo $certificado->puntos_rutas_laboradas; ?></td>
						</tr>

						<tr>
							<th>Co. Sell Out DA</th>
							<td align="right"><?php echo formatMoney($certificado->acumulado_importe_da); ?></td>
							<td align="right"><?php echo formatPorcentaje($certificado->porcentaje_acumulado_alcance_da); ?></td>
							<td align="right"><?php echo formatMoney($certificado->acumulado_incentivo_da); ?></td>
							<td align="right"><?php echo $certificado->puntos_cobertura_sallout_da; ?></td>
						</tr>
						<tr>
							<th>Drop Size DA</th>
							<td align="right"><?php echo $certificado->dropsize_da; ?></td>
							<td align="right"><?php echo $certificado->dropsize_da; ?></td>
							<td align="right" style='vertical-align: middle' rowspan='2'><?php echo formatMoney($certificado->incentivo_pedidos_da); ?></td>
							<td align="right"><?php echo $certificado->puntos_dropsize_da; ?></td>
						</tr>
						<tr>
							<th>PP DA</th>
							<td align="right"><?php echo $certificado->promedio_ventas_da; ?></td>
							<td align="right"><?php echo $certificado->promedio_ventas_da; ?></td>
							<td align="right"><?php echo $certificado->puntos_promedio_ventas_da; ?></td>
						</tr>
						<tr>
							<th>Efectividad Venta DA</th>
							<td align="right"><?php echo $certificado->cantidad_ventas_da; ?></td>
							<td align="right"><?php echo formatPorcentaje($certificado->efectividad_ventas_da); ?></td>
							<td align="right">-</td>
							<td align="right"><?php echo $certificado->puntos_efectividad_ventas_da; ?></td>
						</tr>

						<tr>
							<th>Co. Sell Out Impulso</th>
							<td align="right"><?php echo formatMoney($certificado->acumulado_importe_impulso); ?></td>
							<td align="right"><?php echo formatPorcentaje($certificado->porcentaje_acumulado_alcance_impulso); ?></td>
							<td align="right"><?php echo formatMoney($certificado->acumulado_incentivo_impulso); ?></td>
							<td align="right"><?php echo $certificado->puntos_cobertura_sallout_impulso; ?></td>
						</tr>
						<tr>
							<th>Drop Size Impulso</th>
							<td align="right"><?php echo $certificado->dropsize_impulso; ?></td>
							<td align="right"><?php echo $certificado->dropsize_impulso; ?></td>
							<td align="right" style='vertical-align: middle' rowspan='2'><?php echo formatMoney($certificado->incentivo_pedidos_impulso); ?></td>
							<td align="right"><?php echo $certificado->puntos_dropsize_impulso; ?></td>
						</tr>
						<tr>
							<th>PP Impulso</th>
							<td align="right"><?php echo $certificado->promedio_ventas_impulso; ?></td>
							<td align="right"><?php echo $certificado->promedio_ventas_impulso; ?></td>
							<td align="right"><?php echo $certificado->puntos_promedio_ventas_impulso; ?></td>
						</tr>
						<tr>
							<th>Efectividad Venta Impulso</th>
							<td align="right"><?php echo $certificado->cantidad_ventas_impulso; ?></td>
							<td align="right"><?php echo formatPorcentaje($certificado->efectividad_ventas_impulso); ?></td>
							<td align="right">-</td>
							<td align="right"><?php echo $certificado->puntos_efectividad_ventas_impulso; ?></td>
						</tr>

						<tr>
							<th>Co. Categorias</th>
							<td align="right"><?php echo $certificado->cobertura_categorias; ?></td>
							<td align="right"><?php echo formatPorcentaje($certificado->porcentaje_cobertura_categorias); ?></td>
							<td align="right">-</td>
							<td align="right"><?php echo $certificado->puntos_cobertura_categorias; ?></td>
						</tr>
						<tr>
							<th>No. Ruta Certificadas</th>
							<td align="right"><?php echo $certificado->certifica; ?></td>
							<td align="right"></td>
							<td align="right"><?php echo formatMoney($certificado->incentivo_certificacion); ?></td>
							<td align="right"></td>
						</tr>						

					</tobdy>

					<tfoot>
						<tr class="table_totales">
							<th>Totales</th>
							<td align="right"></td>
							<td align="right"></td>
							<th align="right"><?php echo formatMoney($certificado->sum_total_incentivo); ?></th>
							<th align="right"><?php echo $certificado->sum_total_puntos; ?></th>
						</tr>
					</tfoot>

				</table>

			</main>

			<!--<?php /*if($lineas >= 30) { ?>
				<div style="page-break-after: always;"></div>
			<?php }*/ ?>-->

			<footer>

			</footer>
		</div>
	</body>
</html>