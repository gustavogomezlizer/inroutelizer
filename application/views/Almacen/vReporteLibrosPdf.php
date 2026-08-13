<!DOCTYPE HTML>
<html5 lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<title>Reporte de OT Reparto</title>

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

			body{font-size:12px; font-family: Arial, Helvetica, sans-serif; }

			*{margin:5px 2px 2px 2px;padding:0;}

			.table   { display: table; border: 0.1px solid #000;}
			.tablerow  { display: table-row; border: 0.1px solid #000; width:100%;}
			.tablecell { display: table-cell; border: 0.1px solid #000; width:100%;}

			.zebra tbody tr:nth-child(odd) {
				background-color: #ccc;
			}

			.table_encabezado {
				background-color: #b0afaf;
			}

			.table_totales {
				background-color: #9E9E9E;
			}

			.fondo_light_gray {
				background-color: #c4c4c4;
			}

			.ispaquete {
				background-color: #bdb2b2;
			}

			td, th{
				padding: 2px;
			}
			
		</style>
	</head>

	<body>

		<?php 
			function formatMoney($value)
			{
				//return '$'.number_format($value, 2, '.', ',');
				return number_format($value, 2, '.', ',');
			}
			function formatPorcentaje($value)
			{
				return number_format($value, 2, '.', ',').'%';
			}

			$items_rutas = array();

			foreach($info_pedidos as $pedido)
			{
				if(!in_array($pedido["ruta"], $items_rutas))
				{
					array_push($items_rutas, $pedido["ruta"]);
				}
			}

			/*foreach($items_rutas as $ruta) 
			{
				echo "<pre>";
				echo "rutaactual:".$ruta;
				$detalle = array_filter($info_pedidos, function ($var) use($ruta) {
					return ($var['ruta'] == $ruta);
				});
				$detalle = array_values(array_filter($detalle));
				print_r($detalle);
				echo "</pre>";
			}

			die();*/
		?>		

		<?php foreach($items_rutas as $ruta) { ?>

			<div class="principales">

				<?php
				$detalle = array_filter($info_pedidos, function ($var) use($ruta) {
					return ($var['ruta'] == $ruta);
				});

				$detalle = array_values(array_filter($detalle));

				$items_folios = array();

				foreach($detalle as $pedido)
				{
					if(!in_array($pedido["folio"], $items_folios))
					{
						array_push($items_folios, $pedido["folio"]);
					}
				}
				?>

				<header>

					<table width="100%">
						<tr>
							<td width="30%" align="left"><img src="<?php echo $informacion_empresa->logo ?>" width="180" height="80" /></td>
							<td width="60%" align="center">
								<h3><?php echo $informacion_empresa->nombrecorto ?></h3>
								<h3>Reporte de Libro de Ruta</h3>
							</td>
							<td width="30%">&nbsp;</td>
						</tr>
					</table>

				</header>

				<main>

					<table width="30%" border=1 cellspacing=0>
						<tr>
							<td class="fondo_light_gray">Ruta</td>
							<td><?php echo $detalle[0]["ruta_nombre"]; ?></td>
						</tr>
						<tr>
							<td class="fondo_light_gray">Fecha</td>
							<td><?php echo $detalle[0]["fecha"]; ?></td>
						</tr>
					</table>
			
					<table width="100%" border=1 cellspacing=0>
						<thead>
							<tr class="table_encabezado">
								<th width="3%">ID Pedido</th>
								<th width="14%">Cliente</th>
								<th width="9%">Tipo</th>
								<th width="2%" align="center">ID Item</th>
								<th width="7%">Cod. Producto</th>
								<th width="30%">Producto</th>
								<th width="5%" align="right">Precio</th>
								<th width="5%" align="center">Suma Unis</th>
								<th width="15%" align="right">Suma Total</th>
							</tr>
						</thead>

						<tbody>

							<?php 

							$total_general_importe_pedido = 0;
							$total_general_cantidad_pedido = 0;

							foreach($items_folios as $folio) 
							{ 
								$detalle_pedido = array_filter($detalle, function ($var) use($folio) {
									return ($var['folio'] == $folio);
								});
				
								$detalle_pedido = array_values(array_filter($detalle_pedido));

								/*array_multisort(
									array_column($detalle_pedido, 'categoria_nombre'),  SORT_ASC,
									array_column($detalle_pedido, 'producto'), SORT_ASC,
									$detalle_pedido
								);*/
							?>

								<?php $index = 1; $total_importe_pedido = 0; $total_cantidad_pedido = 0; ?>

								<?php foreach($detalle_pedido as $pedido) { ?>

									<?php 
										$total = floatval($pedido["precio"]) * floatval($pedido["cantidad_real_entregado"]);

										if ($pedido["tipo"] == "DEVOLUCION")
										{
											$total = $total * -1;
										}

										$colorpaquete = "a";

										if (strtolower($pedido["tipo_producto"]) == "paquete")
										{
											$colorpaquete = "ispaquete";
										}

										$total_importe_pedido = $total_importe_pedido + ($total);
										$total_cantidad_pedido = $total_cantidad_pedido + floatval($pedido["cantidad_real_entregado"]);
									?>

										<tr>
											<td class="<?php //echo $colorpaquete; ?>"><?php echo $index > 1 ? "" : $pedido["folio"]; ?></td>
											<td class="<?php //echo $colorpaquete; ?>"><?php echo $index > 1 ? "" : $pedido["cliente"]; ?></td>
											<td class="<?php //echo $colorpaquete; ?>"><?php echo $index > 1 ? "" : $pedido["tipo"]; ?></td>
											<td align="center" class="<?php echo $colorpaquete; ?>"><?php echo $index; ?></td>
											<td class="<?php echo $colorpaquete; ?>"><?php echo $pedido["codigoproducto"]; ?></td>
											<td class="<?php echo $colorpaquete; ?>"><?php echo $pedido["producto"]; ?></td>
											<td align="right" class="<?php echo $colorpaquete; ?>"><?php echo formatMoney($pedido["precio"]); ?></td>
											<td align="center" class="<?php echo $colorpaquete; ?>"><?php echo $pedido["cantidad_real_entregado"]; ?></td>
											<td align="right" class="<?php echo $colorpaquete; ?>"><?php echo formatMoney($total); ?></td>
										</tr>

										<?php 
											if (strtolower($pedido["tipo_producto"]) == "paquete")
											{
												$iditem = $pedido["iditem"];

												$componentes = array_filter($componentes_paquete, function ($var) use($iditem) {
													return ($var['idpaquete'] == $iditem);
												});
								
												$componentes = array_values(array_filter($componentes));
											
										?>

											<?php foreach($componentes as $comp) { $cantidadreal = floatval($pedido["cantidad_real_entregado"]) * floatval($comp["cantidad"]); ?>

												<tr>
													<td><?php echo ""; ?></td>
													<td><?php echo ""; ?></td>
													<td><?php echo ""; ?></td>
													<td><?php echo ""; ?></td>
													<td><?php echo $comp["codigo"]; ?></td>
													<td><?php echo $comp["nombre"]; ?></td>
													<td align="right"><?php echo ""; ?></td>
													<td align="center"><?php echo $cantidadreal; ?></td>
													<td align="right"><?php echo ""; ?></td>
												</tr>

											<?php } 
										
											} ?>

										<?php $index++; ?>

								<?php } ?>

								<tr class="table_totales">
									<td><?php echo ""; ?></td>
									<td><?php echo ""; ?></td>
									<td><?php echo ""; ?></td>
									<td><?php echo ""; ?></td>
									<td><?php echo ""; ?></td>
									<td align="right"><?php echo "Total"; ?></td>
									<td align="right"><?php echo ""; ?></td>
									<td align="center"><?php echo $total_cantidad_pedido; ?></td>
									<td align="right"><?php echo formatMoney($total_importe_pedido); ?></td>
								</tr>

								<?php 
									$total_general_importe_pedido = $total_general_importe_pedido + $total_importe_pedido;
									$total_general_cantidad_pedido = $total_general_cantidad_pedido + $total_cantidad_pedido; 
								?>

							<?php } ?>

							<tr class="table_totales">
								<td><?php echo ""; ?></td>
								<td><?php echo ""; ?></td>
								<td><?php echo ""; ?></td>
								<td><?php echo ""; ?></td>
								<td><?php echo ""; ?></td>
								<td align="right"><?php echo "Total General"; ?></td>
								<td align="right"><?php echo ""; ?></td>
								<td align="center"><?php echo $total_general_cantidad_pedido; ?></td>
								<td align="right"><?php echo formatMoney($total_general_importe_pedido); ?></td>
							</tr>

						</tbody>

						<tfoot>

						</tfoot>

					</table><br/>

				</main>

				<!--<?php /*if($lineas >= 30) { ?>
					<div style="page-break-after: always;"></div>
				<?php }*/ ?>-->

				<footer>

				</footer>
			</div>

			<div style="page-break-after: always;"></div>
		<?php } ?>		
	</body>
</html>