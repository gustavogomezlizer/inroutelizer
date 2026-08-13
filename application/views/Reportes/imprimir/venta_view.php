
<?php
	//$datosempresa = GETDATOSEMPRESA();
	//$pedidodetallado = $this->ReportesModel->getPedidosDetalladosId($idpedido);
 ?>

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
			.normalvacio {
				width:150px;
				border-collapse: collapse;
				border-spacing:0;
			}
			.cBlank{
				width:3px;
				border-collapse: collapse;
				border-spacing:0;
			}
			.cSmall{
				width:75px;
				max-width: 75px;
				min-width: 75px;
				border-collapse: collapse;
				border-spacing:0;
			}
			.cMedium{
				width:75px;
				max-width: 75px;
				border-collapse: collapse;
				border-spacing:0;
			}
			.cBig{
				width:340px;
				max-width: 340px;
				border-collapse: collapse;
				border-spacing:0;
			}
			.cBigger{
				width:600px;
				max-width: 600px;
				border-collapse: collapse;
				border-spacing:0;
			}
			.cBiggerTotal{
				width:700px;
				max-width: 700px;
				border-collapse: collapse;
				border-spacing:0;
				text-align: justify;
				border-top: 1px solid black;
			}
			.cBiggerTotal2{
				width:700px;
				max-width: 700px;
				border-collapse: collapse;
				border-spacing:0;
				text-align: center;
				
			}
			.datos0 {
				width:50px;
				border-collapse: collapse;
				border-spacing:0;
			}
			.datos1 {
				width:350px;
				border-collapse: collapse;
				border-spacing:0;
			}
			.datos2 {
				width:350px;
				border-collapse: collapse;
				border-spacing:0;
			}
			.datos3 {
				width:550px;
				border-collapse: collapse;
				border-spacing:0;
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
				/*background-image: url(assets/images/html2pdf/noxfondo.jpg);
				background-repeat: no-repeat;
				background-attachment: fixed;
    			background-position: center; 
    			background-size: 30% 20%;*/
			}
			.letraPequeña{
				font-size: 9;
			}
			
			
		</style>
		
	

		

		<div class="content">
			<table border=0 cellspacing=0>

				<tr>
					<td class="datos0"></td>
					<td class="datos1">
						<?php echo $info_empresa->nombrecorto; ?>
					</td>
					<td class="normalvacio"></td>
					<td class="datos2"><?php echo $folio; ?></td>
				</tr>
				<tr>
					<td class="datos0"></td>
					<td class="datos1"><?php echo $info_empresa->domicilio; ?></td>
					<td class="normalvacio"></td>
					<td class="datos2"><?php echo $fecha." ".$hora; ?></td>
				</tr>
				<tr>
					<td class="datos0"></td>
					<td class="datos1"><?php echo $info_empresa->telefono; ?></td>
					<td class="normalvacio"></td>
					<td class="datos2"><?php echo $nombreUsuario; ?></td>
				</tr>
				
				<tr>
					<td class="datos0"></td>
					<td class="datos1"></td>
					<td class="normalvacio"></td>
					<td class="datos2"><?php 

						if($info_pedido->credito==0){
							$cadenacred=$tipo." CONTADO";
						}
						else{
							$cadenacred=$tipo." CREDITO";
						}
					echo $cadenacred; ?></td>
				</tr>
				
				
			</table>
			
		</div>
		<div class="content">
		<table>
			<tr>
					<td class="cSmall"></td>
					<td class="datos3"><strong>Cliente: </strong><?php echo $nombreCliente; ?></td>
					
				</tr>
				<tr>
					<td class="cSmall"></td>
					<td class="datos3"><strong>Domicilio: </strong><?php echo $info_cliente->direccion.' #'.$info_cliente->numero; ?></td>
					
				</tr>
				<tr>
					<td class="cSmall"></td>
					<td class="datos3"><strong>Colonia: </strong><?php echo $info_cliente->colonia; ?></td>
					
				</tr>
				<tr>
					<td class="cSmall"></td>
					<td class="datos3"><strong>Ciudad: </strong><?php echo $info_cliente->ciudad; ?></td>
					
				</tr>
				<tr>
					<td class="cSmall"></td>
					<td class="datos3"><strong>CP: </strong><?php echo $info_cliente->cp; ?> <strong>Telefono: </strong><?php echo $info_cliente->telefono; ?></td>
					
				</tr>
		</table>
		</div>

		<br/><br/>
		
		<div class="content">
			<table border=0 cellspacing=1>
			<thead>
				<tr>
					<th class="cMedium">Codigo</th>
					<!-- <th class="cBlank"></th>
					<th class="cMedium">Tipo</th> -->
					<th class="cBlank"></th>
					<th class="cSmall" style="text-align: left;">Cantidad</th>
					<th class="cBlank"></th>
					<th class="cBig">Concepto</th>
					<th class="cBlank"></th>
					<th class="cSmall">Precio</th>
					<th class="cBlank"></th>
					<th class="cSmall">Importe</th>
				</tr>
				</thead>
				<tbody>
				
				<?php 
				if($pedidodetallado->num_rows()!=0)
				{
					$cuantospedidos=$pedidodetallado->num_rows();
					
					$contador=0;
					$contadorT=0;
					foreach ($pedidodetallado->result() as $kPD) {
						# code...
						$codigoproducto=$kPD->codigoproducto;
						$producto=$kPD->producto;
						$cantidad=$kPD->cantidad;
						$precio=$kPD->precio;
						$importe=$kPD->importe;

						if($contador==35){
							$contador=0;
 					?>
					<tr>
						<td class="cMedium">&nbsp;</td>
						<!-- <td class="cBlank"></td>
						<td class="cMedium"></td> -->
						<td class="cBlank"></td>
						<td class="cSmall" style="text-align: left;"></td>
						<td class="cBlank"></td>
						<td class="cBig"></td>
						<td class="cBlank"></td>
						<td class="cSmall"></td>
						<td class="cBlank"></td>
						<td class="cSmall"></td>
					</tr>

					<?php
						}
						
						if($contadorT==$cuantospedidos){
							?>
					<tr>
						<td class="cMedium"></td>
						<!-- <td class="cBlank"></td>
						<td class="cMedium"></td> -->
						<td class="cBlank"></td>
						<td class="cSmall" style="text-align: left;"></td>
						<td class="cBlank"></td>
						<td class="cBig"></td>
						<td class="cBlank"></td>
						<td class="cSmall"><b>Total:</b> </td>
						<td class="cBlank"></td>
						<td class="cSmall"><b><?php echo FORMATO_DINERO($total); ?></b></td>
					</tr>

					<?php
						}
						else{
					
				 ?>
					<tr>
						<td class="cMedium"><?php echo $codigoproducto; ?></td>
						<!-- <td class="cBlank"></td>
						<td class="cMedium"><?php echo $tipo; ?></td> -->
						<td class="cBlank"></td>
						<td class="cSmall" style="text-align: left;"><?php echo $cantidad; ?></td>
						<td class="cBlank"></td>
						<td class="cBig"><?php echo $producto; ?></td>
						<td class="cBlank"></td>
						<td class="cSmall"><?php echo FORMATO_DINERO($precio); ?></td>
						<td class="cBlank"></td>
						<td class="cSmall"><?php echo FORMATO_DINERO($importe); ?></td>
					</tr>

					<?php 
					$contador=$contador+1;
					$contadorT=$contadorT+1;
				} } }
					for ($i=$contador; $i <35 ; $i++) { 
						?>
					<!--<tr>
						<td class="cMedium">&nbsp;</td>
						<td class="cBlank"></td>
						<td class="cSmall" style="text-align: left;"></td>
						<td class="cBlank"></td>
						<td class="cBig"></td>
						<td class="cBlank"></td>
						<td class="cSmall"></td>
						<td class="cBlank"></td>
						<td class="cSmall"></td>
					</tr>-->

					<?php
					}
					?>
					<tr>
						<td class="cMedium"></td>
						<!-- <td class="cBlank"></td>
						<td class="cMedium"></td> -->
						<td class="cBlank"></td>
						<td class="cSmall" style="text-align: left;"></td>
						<td class="cBlank"></td>
						<td class="cBig"></td>
						<td class="cBlank"></td>
						<td class="cSmall"><b>Total:</b> </td>
						<td class="cBlank"></td>
						<td class="cSmall"><b><?php echo FORMATO_DINERO($total); ?></b></td>
					</tr>
				</tbody>
			</table>

			<br/><br/>

			<table border=0 cellspacing=1>
				<tr>
					<td class="cBiggerTotal">
						PAGARE. <br><br>
						<small>Debe(mos) y pagare(mos) incondicionalmente por este pagare a la orden de <strong><?php echo $info_empresa->nombrecorto; ?></strong> en la ciudad de <?php echo $clienteCiudad.", ".$clienteEstado ?>. el <strong><?php echo FORMATO_FECHA($fecha); ?></strong> o en cualquier otra que se me requiera la cantidad de <strong><?php echo FORMATO_DINERO($total); ?></strong>, Valor recibido a mi (nuestra) entera satisfaccion. Se solventaran las obligaciones adquiridas en este pagare entregando su equivalente en moneda nacional a la fecha de pago. De no verificarse el pago de la cantidad que este pagare expresa el dia de su vencimiento causara intereses moratorios al tipo de 5% mensuales (cinco por ciento) pagadero en esta ciudad. Este pagare es mercantil y esta regido por la Ley General de Titulos y Operaciones de credito en su articulo 173 y articulos correlativos, por ser pagare domiciliado.</small>
						
					</td>
				</tr>
				<tr>
					<td class="cBiggerTotal2">
					<br/>
					__________________
					<br/>Nombre y Firma.
					</td>
				</tr>
			</table>
		</div>