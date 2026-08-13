<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Prueba post</title>
	<link rel="stylesheet" href="">
</head>
<body>
<?php 
	$valor='{"TipoArchivo":"Objetivos","Periodo":"201811","Objetivos":[{"IdVendedor":"14","ObjetivoCat":[{"Categoria":"Alimentos Infantiles","Importe":"27927.36"},{"Categoria":"Fórmulas Infantiles","Importe":"11081.00"},{"Categoria":"Lacteos Infantiles ","Importe":"14890.65"},{"Categoria":"Bebidas Cocoa ","Importe":"5021.31"},{"Categoria":"Bebidas Refrescantes","Importe":"6500.00"},{"Categoria":"Cafés","Importe":"103239.32"},{"Categoria":"Cereales ","Importe":"3900.00"},{"Categoria":"Chocolate Mesa","Importe":"7211.88"},{"Categoria":"Cremadores ","Importe":"17582.28"},{"Categoria":"Culinarios","Importe":"10804.48"},{"Categoria":"Lácteos Culinarios","Importe":"26447.79"},{"Categoria":"Lácteos Polvo","Importe":"9083.18"},{"Categoria":"Chocolate Impulso","Importe":"32500.00"}]},{"IdVendedor":"15","ObjetivoCat":[{"Categoria":"Alimentos Infantiles","Importe":"27927.36"},{"Categoria":"Fórmulas Infantiles","Importe":"11081.00"},{"Categoria":"Lacteos Infantiles ","Importe":"14890.65"},{"Categoria":"Bebidas Cocoa ","Importe":"5021.31"},{"Categoria":"Bebidas Refrescantes","Importe":"6500.00"},{"Categoria":"Cafés","Importe":"103239.32"},{"Categoria":"Cereales ","Importe":"3900.00"},{"Categoria":"Chocolate Mesa","Importe":"7211.88"},{"Categoria":"Cremadores ","Importe":"17582.28"},{"Categoria":"Culinarios","Importe":"10804.48"},{"Categoria":"Lácteos Culinarios","Importe":"26447.79"},{"Categoria":"Lácteos Polvo","Importe":"9083.18"},{"Categoria":"Chocolate Impulso","Importe":"32500.00"}]}]}';
$valorJson=json_encode($valor);
 ?>

	<!-- <form action="http://lizer.com.mx/lizerDemo/index.php/Estadisticas/leerObjetivosVentasPrueba" method="post"> -->
	<form action="http://lizer.com.mx/lizerDemo/index.php/Reportes/leerAcumuladosJson" method="post">
		<input type='hidden' name='Cadena' value='<?php echo $valor; ?>'>
		<button>Enviar</button>
	</form>
</body>
</html>