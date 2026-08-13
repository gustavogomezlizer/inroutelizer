<?php
class ReportesModel extends CI_Model {

	private $dbinfo;
	private $config_app;
	 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		$this->config_app = switch_db_dinamico(GETEMPRESA());

		$this->dbinfo = $this->load->database($this->config_app, TRUE);
	}

	protected function getDBEmpresa($empresa)
	{
		if ($this->dbinfo === null) {
			$this->dbinfo = $this->load->database($empresa, TRUE);
		}
		return $this->dbinfo;
	}

	function getDatesFromRange($start, $end, $format = 'Y-m-d')
	{
		$array = array();
		$interval = new DateInterval('P1M');
	
		$realEnd = new DateTime($end);
		$realEnd->add($interval);
	
		$period = new DatePeriod(new DateTime($start), $interval, $realEnd);
	
		foreach($period as $date) { 
			$array[] = $date->format($format); 
		}
	
		return $array;
	}

	public function getPedidosCuantos($fIni,$fFin)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$consulta = "SELECT COUNT(pedidos.id) AS cuantospedidos, SUM(pedidos.total) AS totalpedidos 
		FROM pedidos 
		WHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";

		$query = $db->query($consulta);

		return $query;
	}

	public function getCuantasVisitas($fIni,$fFin,$valor)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$consulta = "SELECT COUNT(id) AS cuanto FROM visitas WHERE resultado='$valor' AND fecha BETWEEN '$fIni' AND '$fFin'";

		$query = $db->query($consulta);
		
		return $query;
	}	

	public function getSucursales()
	{
		$MS = VERIFICAMULTISUCURSAL();
		$sucursal = GETSUCURSAL();
		
		if($MS==1)
		{
			$consulta = "SELECT * FROM cat_sucursales WHERE status = 1 ORDER BY sucursal ASC";
			$query = $this->dbinfo->query($consulta);
		}
		else
		{
			$sucursalesusuario = $this->db->query("SELECT sucursal_asignadas FROM usuarios WHERE id = ?", [GETIDUSUARIO()])->row()->sucursal_asignadas;


			$consulta = "SELECT * FROM cat_sucursales WHERE status = 1 AND FIND_IN_SET(id, ?)";
			$query = $this->dbinfo->query($consulta, [$sucursalesusuario]);
		}

		/*if(GETIDUSUARIO() == 124)
		{
			$consulta = "SELECT * FROM cat_sucursales WHERE status = 1 AND id IN (13,17) ORDER BY sucursal ASC";
			$query = $this->dbinfo->query($consulta);
		}*/
		
		return $query;
	}

	public function getPedidos($data)
	{
		//$this->dbinfo->select("pedidos.*, CONCAT('$', FORMAT(pedidos.total, 2)) as total_format, cat_sucursales.sucursal AS sucursal_nombre, cat_rutas.ruta AS ruta_nombre");
		$this->dbinfo->select("pedidos.*, 
		CONCAT('$', FORMAT(SUM((pedidos_detalle.`cantidad`) * pedidos_detalle.`precio`), 2)) as total_format, 
		SUM((pedidos_detalle.`cantidad`) * pedidos_detalle.`precio`) as total2, 
		cat_sucursales.sucursal AS sucursal_nombre, cat_rutas.ruta AS ruta_nombre");
		$this->dbinfo->from('pedidos');
		$this->dbinfo->join('pedidos_detalle', 'pedidos.id = pedidos_detalle.idpedido');
		$this->dbinfo->join('cat_sucursales', 'pedidos.idsucursal = cat_sucursales.id');
		$this->dbinfo->join('cat_rutas', 'pedidos.ruta = cat_rutas.id');
		if($data['fechade']!=""){
			$this->dbinfo->where('pedidos.fecha >=', $data['fechade']);
			$this->dbinfo->where('pedidos.fecha <=', $data['fechaa']);
		}
		if($data["tipo"]!="0") $this->dbinfo->where('pedidos.tipo', $data["tipo"]);
		if($data["sucursal"]!="0") $this->dbinfo->where('pedidos.idsucursal', $data["sucursal"]);
		if($data["ruta"]!="0") $this->dbinfo->where('pedidos.ruta', $data["ruta"]);
		if($data["usuario"]!="0") $this->dbinfo->where('pedidos.idusuario', $data["usuario"]);

		$this->dbinfo->group_by('pedidos_detalle.idpedido');
		
		$query = $this->dbinfo->get();
		//die($this->dbinfo->last_query());
		return $query;
	}

	public function getRutas()
	{
		$MS=VERIFICAMULTISUCURSAL();
		$sucursal=GETSUCURSAL();		
		$consulta = "SELECT * FROM cat_rutas WHERE status=1";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getPedido($id)
	{
		$consulta = "SELECT pedidos.*,		
		fnGetSucursalById(pedidos.idSucursal) AS sucursal		
		FROM pedidos
		WHERE pedidos.id = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getVisita($id)
	{
		$consulta = "SELECT visitas.*, (SELECT id FROM pedidos WHERE idcliente=visitas.idcliente AND fecha=visitas.fecha limit 1) as idpedido,		
		fnGetSucursalById(visitas.idSucursal) AS sucursal		
		FROM visitas
		WHERE visitas.id=$id";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	/*public function getDatosCliente($id)
	{
		$consulta = "SELECT * FROM clientes WHERE id=$id";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}*/

	public function getPedidosDetalle($idpedido)
	{
		$consulta = "SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe,
		fnGetProveedorById(pedidos_detalle.idproveedor) AS nombreProveedor,
		(pedidos_detalle.`cantidad`) AS cantidad_real,
		((SELECT cantidad_real) * pedidos_detalle.`precio`) AS importe_real
		FROM pedidos 
		INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id
		WHERE pedidos.id='$idpedido'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function banderaImpreso($idpedido)
	{
		$consulta = "UPDATE pedidos SET impreso=1 WHERE id=$idpedido";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	/*public function getPedidosDetalleId($idpedido)
	{
		$consulta = "SELECT pedidos.folio, pedidos.tipo, pedidos.credito, pedidos.fechacreacion, pedidos.total, usuarios.nombre AS nombreUsuario,clientes.ciudad AS clienteCiudad, clientes.estado AS clienteEstado, clientes.nombre AS nombreCliente, CONCAT(clientes.calle,' ',clientes.numero) AS domicilio, clientes.colonia AS colonia, CONCAT(clientes.ciudad,' ',clientes.estado) AS ciudad, clientes.cp AS cp, clientes.telefono AS telefono 
		FROM pedidos 
		INNER JOIN usuarios ON usuarios.id=pedidos.idusuario 
		INNER JOIN clientes ON clientes.id=pedidos.idcliente 
		WHERE pedidos.id=$idpedido";		
		$query = $this->dbinfo->query($consulta);
		return $query;
	}*/

	public function getPedidosDetalladosId($idpedido)
	{
		$consulta = "SELECT pedidos_detalle.* FROM pedidos_detalle WHERE idpedido=$idpedido";		
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getVisitas($data)
	{
		//$this->dbinfo->select("visitas.*, cat_sucursales.sucursal AS sucursal_nombre, cat_rutas.ruta AS ruta_nombre, (SELECT id FROM pedidos WHERE idcliente=visitas.idcliente AND fecha=visitas.fecha limit 1) as idpedido");
		$this->dbinfo->select("visitas.*, cat_sucursales.sucursal AS sucursal_nombre, cat_rutas.ruta AS ruta_nombre, 
		(SELECT id FROM pedidos WHERE idcliente=visitas.idcliente AND fecha=visitas.fecha limit 1) as idpedido,
		(SELECT CONCAT_WS(',', cli.calle, cli.numero, cli.colonia, cli.ciudad, cli.estado) FROM clientes cli WHERE cli.id = visitas.`idcliente`) AS domicilio");
		$this->dbinfo->from('visitas');
		$this->dbinfo->join('cat_sucursales', 'visitas.idsucursal = cat_sucursales.id');
		$this->dbinfo->join('cat_rutas', 'visitas.ruta = cat_rutas.id');
		if($data['fechade']!=""){
			$this->dbinfo->where('visitas.fecha >=', $data['fechade']);
			$this->dbinfo->where('visitas.fecha <=', $data['fechaa']);
		}
		if($data["sucursal"]!="0") $this->dbinfo->where('visitas.idsucursal', $data["sucursal"]);
		if($data["ruta"]!="0") $this->dbinfo->where('visitas.ruta', $data["ruta"]);
		if($data["usuario"]!="0") $this->dbinfo->where('visitas.idusuario', $data["usuario"]);
		
		$query = $this->dbinfo->get();
		//die($this->dbinfo->last_query());

		return $query;
	}

	public function getEfectividadAgenda($data)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$db->select("cat_rutas.*, '$data[fecha]' AS fecha_agenda, cat_sucursales.sucursal as sucursal_nombre, fnGetClientesVisitaRutaByDia(cat_rutas.`id`, '$data[fecha]', '$data[fecha]', '$data[fecha]') AS clientes, fnGetClientesVisitoRutaByDia(cat_rutas.`id`, '$data[fecha]') AS visito");
		$db->from('cat_rutas');
		$db->join('cat_sucursales', 'cat_rutas.sucursal = cat_sucursales.id');
		$db->where('cat_rutas.status', '1');
		if($data["sucursal"]!="0") $db->where('cat_rutas.sucursal', $data["sucursal"]);
		if($data["ruta"]!="0") $db->where('cat_rutas.id', $data["ruta"]);
		if($data["usuario"]!="0") $db->where('cat_rutas.chofer', $data["usuario"]);		
				
		//$query = $db->get();

		$query = $db->get()->result_array();
		foreach ($query as $key => $value) {
			$query[$key]["usuario_nombre"] = (($value["chofer"]=="0") ? "NO ASIGNADO" : GETDATOSUSUARIO($value["chofer"], "nombre")->nombre);
		}

		return $query;
	
	}

	public function getPedidosVisitas($idUsuario,$fIni,$fFin)
	{
		$consulta = "SELECT DISTINCT(visitas.idcliente),visitas.fecha,visitas.latitud,visitas.longitud,clientes.codigo,clientes.nombre,clientes.calle,clientes.numero,clientes.colonia,clientes.ciudad,clientes.estado 
		FROM visitas 
		INNER JOIN clientes ON clientes.id=visitas.idcliente
		WHERE visitas.idusuario = '$idUsuario' AND visitas.fecha = '$fIni'";
		//WHERE visitas.idusuario=$idUsuario AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
		$query = $this->dbinfo->query($consulta);
		$contador = 0;
		$cadena = "";

		foreach ($query->result() as $kCoord)
		{
			//$consultaPed = "SELECT tipo,total FROM pedidos WHERE idcliente=$kCoord->idcliente AND pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
			$consultaPed = "SELECT tipo,total FROM pedidos WHERE idcliente=$kCoord->idcliente AND pedidos.fecha = '$fIni'";
			$queryPed = $this->dbinfo->query($consultaPed);
			if($queryPed->num_rows()!=0)
			{
				$total=FORMATO_DINERO($queryPed->row()->total);
				$tipo=$queryPed->row()->tipo;
				if($contador==0)
				{
					$cadena.=$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
				}
				else
				{
					$cadena.="%".$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
				}	 
			}
			else
			{
				$total=0;
				$tipo="VISITA";
				if($contador==0)
				{
					$cadena.=$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
				}
				else
				{
					$cadena.="%".$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
				}
			}
			
			$contador=$contador+1;
		}
		return $contador."&".$cadena;
	}

	public function getEfectividad($data)
	{
		$where = "";
		$ponerand = false;		

		if($data["sucursal"]!="0"){
			$where = $where." AND visitas.idsucursal = '$data[sucursal]' ";
			$ponerand = true;
		}

		if($data["ruta"]!="0"){
			if($ponerand){
				$where = $where." AND visitas.ruta = '$data[ruta]' ";
			}else{
				$where = $where." visitas.ruta = '$data[ruta]' ";
			}

			$ponerand = true;
		}

		if($data["usuario"]!="0"){
			if($ponerand){
				$where = $where." AND visitas.idusuario = '$data[usuario]' ";
			}else{
				$where = $where." visitas.idusuario = '$data[usuario]' ";
			}

			$ponerand = true;
		}

		$consulta = "SELECT datos.*,
		fnGetVisitasDatosByFechas(datos.idusuario, '$data[fechade]', '$data[fechaa]') AS datos_visita, 
		fnGetPedidosDatosByFechas(datos.idusuario, '$data[fechade]', '$data[fechaa]') AS datos_pedidos 
		FROM 
		(SELECT DISTINCT (visitas.idusuario), `cat_sucursales`.`sucursal` AS `sucursal_nombre`, `cat_rutas`.`ruta` AS `ruta_nombre`
		FROM `visitas` 
		JOIN `cat_sucursales` ON `visitas`.`idsucursal` = `cat_sucursales`.`id` 
		JOIN `cat_rutas` ON `visitas`.`ruta` = `cat_rutas`.`id` 
		WHERE `visitas`.`fecha` BETWEEN '$data[fechade]' AND '$data[fechaa]'".$where.") AS datos";

		$query = $this->dbinfo->query($consulta)->result_array();
		foreach ($query as $key => $value) {
			$query[$key]["usuario_nombre"] = GETDATOSUSUARIO($value["idusuario"], "nombre")->nombre;
		}

		/*$this->dbinfo->select("DISTINCT (visitas.idusuario), cat_sucursales.sucursal as sucursal_nombre, cat_rutas.ruta as ruta_nombre, fnGetVisitasDatosByFechas(visitas.idusuario, '$data[fechade]', '$data[fechaa]') as datos_visita, fnGetPedidosDatosByFechas(visitas.idusuario, '$data[fechade]', '$data[fechaa]') as datos_pedidos");
		$this->dbinfo->from('visitas');
		$this->dbinfo->join('cat_sucursales', 'visitas.idsucursal = cat_sucursales.id');
		$this->dbinfo->join('cat_rutas', 'visitas.ruta = cat_rutas.id');
		if($data['fechade']!=""){
			$this->dbinfo->where('visitas.fecha >=', $data['fechade']);
			$this->dbinfo->where('visitas.fecha <=', $data['fechaa']);
		}
		if($data["sucursal"]!="0") $this->dbinfo->where('visitas.idsucursal', $data["sucursal"]);
		if($data["ruta"]!="0") $this->dbinfo->where('visitas.ruta', $data["ruta"]);
		if($data["usuario"]!="0") $this->dbinfo->where('visitas.idusuario', $data["usuario"]);
		
		$query = $this->dbinfo->get();*/
		//die($this->dbinfo->last_query());

		return $query;
	}

	public function getListaPedidos($fIni,$fFin,$tipo,$usuario,$sucursal,$ruta)
	{
		//$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, clientes.codigo AS codigoCliente, cat_proveedor.nombre AS nombreProveedor, pedidos.idusuario, cat_rutas.ruta, cat_clasificacionproductos.nombre AS clasificacionproducto FROM pedidos INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente INNER JOIN cat_proveedor ON pedidos_detalle.idproveedor=cat_proveedor.id INNER JOIN cat_rutas ON usuarios.id=cat_rutas.chofer INNER JOIN cat_productos ON cat_productos.id=pedidos_detalle.iditem INNER JOIN cat_clasificacionproductos ON cat_clasificacionproductos.id=cat_productos.clasificacion WHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";		
		/*$consulta = "SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, 
		'dss' AS nombreUsuario, 
		pedidos.`cliente` AS nombreCliente, 
		pedidos.`codigocliente` AS codigoCliente, 
		cat_proveedor.nombre AS nombreProveedor, 
		pedidos.idusuario, cat_rutas.ruta, 
		cat_clasificacionproductos.nombre AS clasificacionproducto 
		FROM pedidos 
		INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id 
		INNER JOIN cat_proveedor ON pedidos_detalle.idproveedor=cat_proveedor.id 
		INNER JOIN cat_rutas ON pedidos.`ruta`=cat_rutas.id 
		INNER JOIN cat_productos ON cat_productos.id=pedidos_detalle.iditem 
		INNER JOIN cat_clasificacionproductos ON cat_clasificacionproductos.id=cat_productos.clasificacion 
		WHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";*/

		$this->dbinfo->select("pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, 
		'dss' AS nombreUsuario, 
		pedidos_detalle.cantidad_entregado, pedidos_detalle.cantidad_rechazado,
		pedidos.`cliente` AS nombreCliente, 
		pedidos.`codigocliente` AS codigoCliente, 
		cat_proveedor.nombre AS nombreProveedor, 
		pedidos.idusuario, cat_rutas.ruta, 
		cat_clasificacionproductos.nombre AS clasificacionproducto ");
		$this->dbinfo->from('pedidos');
		$this->dbinfo->join('pedidos_detalle', 'pedidos_detalle.idpedido = pedidos.id');
		$this->dbinfo->join('cat_proveedor', 'pedidos_detalle.idproveedor = cat_proveedor.id');
		$this->dbinfo->join('cat_rutas', 'pedidos.ruta = cat_rutas.id');
		$this->dbinfo->join('cat_clasificacionproductos', 'pedidos_detalle.idclasificacion = cat_clasificacionproductos.id');		
		if($fIni!=""){
			$this->dbinfo->where('pedidos.fecha >=', $fIni);
			$this->dbinfo->where('pedidos.fecha <=', $fFin);
		}
		if($tipo!="0") $this->dbinfo->where('pedidos.tipo', $tipo);
		if($sucursal!="0") $this->dbinfo->where('pedidos.idsucursal', $sucursal);
		if($ruta!="0") $this->dbinfo->where('pedidos.ruta', $ruta);
		if($usuario!="0") $this->dbinfo->where('pedidos.idusuario', $usuario);

		$this->dbinfo->where('pedidos.status', "1");
		$this->dbinfo->where('pedidos_detalle.status', "1");

		//$query = $this->dbinfo->query($consulta);
		$query = $this->dbinfo->get();
		return $query;
	}

	/*public function getAgregarObjetivos($cadena)
	{
		$objetivosT=json_decode($cadena,true);
		$periodo=$objetivosT['Periodo'];
		$mes=substr($periodo, -2);
		$year=substr($periodo,0,-2);
		$tipoArchivo=$objetivosT['TipoArchivo'];
		$objetivos=$objetivosT['Objetivos'];
		//echo $mes."-".$year;
		$fecha=$year."-".$mes."-01";
		foreach ($objetivosT['Objetivos'] as $lObj)
		{
			# code...
			$idVendedor=$lObj['IdVendedor'];
	
			foreach ($lObj['ObjetivoCat'] as $ObjCat) 
			{
				# code...
				$categoria=$ObjCat['Categoria'];
				$importe=$ObjCat['Importe'];
				 
				$consulta="SELECT id AS cuantos FROM acumulados_categorias WHERE idVendedor=$idVendedor AND periodo='$periodo' AND categoria='$categoria'";
				$res=$this->dbinfo->query($consulta);
				//echo $consulta." ".$idVendedor." ".$categoria." ".$importe." ".$mes." ;
				if($res->num_rows()!=0)
				{
					$consulta2="CALL actualizarObjetivos(".$idVendedor.",'".$categoria."',".$importe.",'".$mes."','".$periodo."','".$fecha."')";
				}
				else
				{
					$consulta2="CALL agregarObjetivos(".$idVendedor.",'".$categoria."',".$importe.",'".$mes."','".$periodo."','".$fecha."')";
				}

			   	$this->dbinfo->query($consulta2);
			}
		}
	}*/

	public function getAgregarAcumulados($cadena)
	{
		/*$data = file_get_contents("assets/acumulados/jSonAcumulados.json");*/	
		$products = json_decode($cadena, true);
		$fecha=$products['Fecha'];
		$diasMes=$products['DiasHabiles'];
		$diasTranscurridos=$products['DiasTrascurridos'];
		$tipoArchivo=$products['TipoArchivo'];
		$cFecha=explode("-",$fecha);
		$mes=$cFecha[1];
		$periodo=$cFecha[0].$mes;
		$xxx=$diasMes."-".$diasTranscurridos;
		$consulta2x="UPDATE prupru SET cadena='$xxx' WHERE id=1";
					$this->dbinfo->query($consulta2x);
		//echo "<br>".$products['TipoArchivo']."<br>".$products['Fecha']."<br>";
		//print_r($products['Acumulados']);
		foreach ($products['Acumulados'] as $lVend)
		{
			//echo "<br>IdVendedor: ".$lVend['IdVendedor'];
			
			$idVendedor=$lVend['IdVendedor'];
		
			foreach ($lVend['AcumCat'] as $lAcum)
			{
				$categoria=$lAcum['Categoria'];
				$acumulado=$lAcum['Importe'];

				//echo "<br>--- Categoria: ".$lAcum['Categoria']." Importe".$lAcum['Importe']."<br>";
				$consulta="SELECT id AS cuantos FROM acumulados_categorias WHERE idVendedor=$idVendedor AND periodo='$periodo' AND categoria='$categoria'";
			
				
				//echo $consulta;
				$res=$this->dbinfo->query($consulta);
				//print_r($res);
				if($res->num_rows()!=0)
				{
					$consulta2="CALL actualizarAcumulados('".$fecha."',".$idVendedor.",'".$categoria."',".$acumulado.",'".$mes."','".$periodo."',".$diasMes.",".$diasTranscurridos.")";
				}
				else
				{
					$consulta2="CALL agregarAcumulados('".$fecha."',".$idVendedor.",'".$categoria."',".$acumulado.",'".$mes."','".$periodo."',".$diasMes.",".$diasTranscurridos.")";
				}

				$this->dbinfo->query($consulta2);
				$consulta3="CALL actualizaPeriodo('".$periodo."','".$fecha."',".$diasMes.",".$diasTranscurridos.")";
				$this->dbinfo->query($consulta3);
			}
		}
	}

	public function listado_sellout()
	{
		return $this->dbinfo->query("SELECT *, (SELECT UPPER(tipodocumento) FROM cat_tipo_documento WHERE id = sellout.`tipo` LIMIT 1)  AS tipo FROM sellout WHERE DATE(fecha_registro) = CURDATE()");
	}

	public function getSellout($id)
	{
		return $this->dbinfo->query("SELECT * FROM sellout WHERE id = '$id'");
	}

	public function guardar_excel($data)
	{
		$this->dbinfo->insert("sellout", $data);
		$insert_id = $this->dbinfo->insert_id();

		if($insert_id>0){
			return 1;
		}else{
			return 0;
		}
	}

public function getPruebaAgregarPedido($idVendedor){
		//$consulta="CALL agregarAcumulados('2018-11-06',".$idVendedor.",'BEBIDAS DE COCOA',3200.43)";
		$fecha='2018-11-06';
		$idVendedor=14;
		$categoria='BEBIDAS DE COCOA';
		$data = file_get_contents("assets/acumulados/jSonAcumulados.json");
		$products = json_decode($data, true);
			echo "<br>".$products['TipoArchivo']."<br>".$products['Fecha']."<br>";
		    //print_r($products['Acumulados']);
		    foreach ($products['Acumulados'] as $lVend) {
		    	echo "<br>IdVendedor: ".$lVend['IdVendedor'];
		    	foreach ($lVend['AcumCat'] as $lAcum) {
		    		echo "<br>--- Categoria: ".$lAcum['Categoria']." Importe".$lAcum['Importe']."<br>";
		    		$consulta="CALL validaAcumuladoCat('".$fecha."',".$idVendedor.",'".$categoria."')";
		    		$resultado=$this->db->query($consulta);
		    		mysql_free_result($resultado);
		    	}
		    }
		/*$consulta="CALL validaAcumuladoCat('".$fecha."',".$idVendedor.",'".$categoria."')";
        if($this->db->query($consulta))
        {
            $res='listo';
        }else{
            $res=show_error('Error!');
        }
    return $res;*/
    return "OK";
}
public function doCorte($fecha){
	$fecha2=$fecha." 23:59:59";
	$consulta="CALL eliminaPedidos('".$fecha."','".$fecha2."')";
	$resultado=$this->db->query($consulta);
}
	

public function getAgregarAcumuladosx($cadena){
	/*$data = file_get_contents("assets/acumulados/jSonAcumulados.json");*/
		$consulta2="UPDATE prupru SET cadena='$cadena' WHERE id=1";
		    $this->db->query($consulta2);
				       
		    		
			       
		    

}

public function getLeerPrueba(){
	$consulta="SELECT * FROM prupru WHERE id=1";
	$query=$this->db->query($consulta);
	return $query->row()->cadena;
}
public function getAgregarAcumuladosRes(){
	$data = file_get_contents("assets/acumulados/jSonAcumulados.json");
		$products = json_decode($data, true);
			$fecha=$products['Fecha'];
			$tipoArchivo=$products['TipoArchivo'];
			$cFecha=explode("-",$fecha);
			$mes=$cFecha[1];
			$periodo=$cFecha[0];

			//echo "<br>".$products['TipoArchivo']."<br>".$products['Fecha']."<br>";
		    //print_r($products['Acumulados']);
		    foreach ($products['Acumulados'] as $lVend) {
		    	//echo "<br>IdVendedor: ".$lVend['IdVendedor'];
		    	$idVendedor=$lVend['IdVendedor'];
		    	foreach ($lVend['AcumCat'] as $lAcum) {
		    		$categoria=$lAcum['Categoria'];
		    		$acumulado=$lAcum['Importe'];
		    		//echo "<br>--- Categoria: ".$lAcum['Categoria']." Importe".$lAcum['Importe']."<br>";
		    		$consulta="SELECT id AS cuantos FROM acumulados_categorias WHERE idVendedor=$idVendedor AND mes='$mes' AND categoria='$categoria'";
		    		//echo $consulta;
		    		$res=$this->db->query($consulta);
		    		//print_r($res);
		    if($res->num_rows()!=0){

		    	 $consulta2="CALL actualizarAcumulados('".$fecha."',".$idVendedor.",'".$categoria."',".$acumulado.",'".$mes."')";
		    }
		    else{
		    		$consulta2="CALL agregarAcumulados('".$fecha."',".$idVendedor.",'".$categoria."',".$acumulado.",'".$mes."')";
		    }
		    $this->db->query($consulta2);
				       
		    		
			       
		    }
}
}
public function getDatosEmpresa(){
	$idempresa=GETIDEMPRESA();
	$consulta="SELECT * FROM empresas WHERE idCliente='$idempresa'";
	$query=$this->dbL->query($consulta);
	return $query;
}

public function getDatosPedidos($usuario,$sucursal,$tipo,$ruta,$fIni,$fFin){
	$consulta="SELECT count(id) AS cuantos, sum(total) as total FROM pedidos";
	$contador=0;
	if($usuario!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		$consulta.="(";
		$usuarioCadena=explode(",", $usuario);
		$usuarioNum=count($usuarioCadena);
		for ($i=0; $i < $usuarioNum; $i++) { 
			$consultaUsuario="SELECT id FROM usuarios WHERE nombre='$usuarioCadena[$i]'";
			$queryU=$this->db->query($consultaUsuario);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="idusuario=".$id;
				}
			else{
				$consulta.=" OR idusuario=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
	
	if($sucursal!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		else{
			$consulta.=" AND ";
		}
		$consulta.="(";
		$sucursalCadena=explode(",", $sucursal);
		$sucursalNum=count($sucursalCadena);
		for ($i=0; $i < $sucursalNum; $i++) { 
			$consultaSucursal="SELECT id FROM cat_sucursales WHERE sucursal='$sucursalCadena[$i]'";
			$queryU=$this->db->query($consultaSucursal);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="idsucursal=".$id;
				}
			else{
				$consulta.=" OR idsucursal=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
			if($ruta!="TODOS"){
				if($contador==0){
					$consulta.=" WHERE ";
				}
				else{
					$consulta.=" AND ";
				}
				$consulta.="(";
				$rutaCadena=explode(",", $ruta);
				$rutaNum=count($rutaCadena);
				for ($i=0; $i < $rutaNum; $i++) { 
					$consultaRuta="SELECT id FROM cat_rutas WHERE ruta='$rutaCadena[$i]'";
					$queryU=$this->db->query($consultaRuta);
					$id=$queryU->row()->id;
					if($i==0){
						$consulta.="ruta=".$id;
						}
					else{
						$consulta.=" OR ruta=".$id;
					}

				}
				$consulta.=")";
				$contador=$contador+1;

			}

	if($contador==0){
		$consulta.=" tipo='PREVENTA' AND status=1 AND fecha BETWEEN '$fIni' AND '$fFin'";
	}
	else{
		$consulta.=" AND tipo='PREVENTA' AND status=1 AND fecha BETWEEN '$fIni' AND '$fFin'";
	}
	
	$query=$this->db->query($consulta);
	return $query;
}
public function getDatosVisitasProgramadas($usuario,$sucursal,$ruta,$fIni,$fFin){
	$fecha=$fIni;
	$contadorD=0;
	$cadenaD="";
	$consulta="SELECT count(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona INNER JOIN cat_rutas ON cat_rutas.id=asi_ruta_zona.zona INNER JOIN usuarios ON usuarios.ruta=cat_rutas.id INNER JOIN cat_sucursales ON cat_sucursales.id=cat_rutas.sucursal";
	if($fecha==$fFin){
		$dia=date('w', strtotime($fecha));
		$dia=$dia+1;
		if($contadorD==0){
			$cadenaD.="clientes.diasvisita like '%".$dia."%'";
		}
		else{
			$cadenaD.="OR clientes.diasvisita like '%".$dia."%'";
		}
	}
	else{
		while($fecha!=$fFin){
			$dia=date('w', strtotime($fecha));
			$dia=$dia+1;
			if($contadorD==0){
				$cadenaD.="clientes.diasvisita like '%".$dia."%'";
			}
			else{
				$cadenaD.="OR clientes.diasvisita like '%".$dia."%'";
			}
			$fecha=AGREGARDIAS(1,$fecha);
		}
	}
	//$consulta="SELECT count(visitas.id) AS cuantos FROM usuarios WHERE sucursal=$sucursal AND (".$cadena.")";
	$contador=0;
	if($usuario!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		$consulta.="(";
		$usuarioCadena=explode(",", $usuario);
		$usuarioNum=count($usuarioCadena);
		for ($i=0; $i < $usuarioNum; $i++) { 
			$consultaUsuario="SELECT id FROM usuarios WHERE nombre='$usuarioCadena[$i]'";
			$queryU=$this->db->query($consultaUsuario);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="usuarios.id=".$id;
				}
			else{
				$consulta.=" OR usuarios.id=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
	
	if($sucursal!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		else{
			$consulta.=" AND ";
		}
		$consulta.="(";
		$sucursalCadena=explode(",", $sucursal);
		$sucursalNum=count($sucursalCadena);
		for ($i=0; $i < $sucursalNum; $i++) { 
			$consultaSucursal="SELECT id FROM cat_sucursales WHERE sucursal='$sucursalCadena[$i]'";
			$queryU=$this->db->query($consultaSucursal);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="usuarios.idsucursal=".$id;
				}
			else{
				$consulta.=" OR usuarios.idsucursal=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
		if($ruta!="TODOS"){

				if($contador==0){
					$consulta.=" WHERE ";
				}
				else{
					$consulta.=" AND ";
				}
				$consulta.="(";
				$rutaCadena=explode(",", $ruta);
				$rutaNum=count($rutaCadena);
				for ($i=0; $i < $rutaNum; $i++) { 
					$consultaRuta="SELECT id FROM cat_rutas WHERE ruta='$rutaCadena[$i]'";
					$queryU=$this->db->query($consultaRuta);
					$id=$queryU->row()->id;
					if($i==0){
						$consulta.="ruta.id=".$id;
						}
					else{
						$consulta.=" OR ruta.id=".$id;
					}

				}
				$consulta.=")";
				$contador=$contador+1;

			}
	if($contador!=0){
		$consulta.=" AND (".$cadenaD.")";
	}
	else{
		$consulta.=" WHERE (".$cadenaD.")";
	}
	$query=$this->db->query($consulta);
	return $query;
	//return $consulta;
}
public function getDatosVisitasProgramadasSi($usuario,$sucursal,$ruta,$fIni,$fFin){
	$fecha=$fIni;
	$contadorD=0;
	$cadena="";
	$consulta="SELECT count(visitas.id) AS cuantos FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON cat_sucursales.id=usuarios.sucursal INNER JOIN cat_rutas ON cat_rutas.id=visitas.ruta";
	if($fecha==$fFin){
		$dia=date('w', strtotime($fecha));
		$dia=$dia+1;
		if($contadorD==0){
			$cadena.="visitas.diasvisitas like '%".$dia."%'";
		}
		else{
			$cadena.="OR visitas.diasvisitas like '%".$dia."%'";
		}
	}
	else{
		while($fecha!=$fFin){
			$dia=date('w', strtotime($fecha));
			$dia=$dia-1;
			if($contadorD==0){
				$cadena.="visitas.diasvisitas like '%".$dia."%'";
			}
			else{
				$cadena.="OR visitas.diasvisitas like '%".$dia."%'";
			}
			$fecha=AGREGARDIAS(1,$fecha);
		}
	}
	//$consulta="SELECT count(visitas.id) AS cuantos FROM usuarios WHERE sucursal=$sucursal AND (".$cadena.")";
	$contador=0;
	if($usuario!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		$consulta.="(";
		$usuarioCadena=explode(",", $usuario);
		$usuarioNum=count($usuarioCadena);
		for ($i=0; $i < $usuarioNum; $i++) { 
			$consultaUsuario="SELECT id FROM usuarios WHERE nombre='$usuarioCadena[$i]'";
			$queryU=$this->db->query($consultaUsuario);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="visitas.idusuario=".$id;
				}
			else{
				$consulta.=" OR visitas.idusuario=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
	
	if($sucursal!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		else{
			$consulta.=" AND ";
		}
		$consulta.="(";
		$sucursalCadena=explode(",", $sucursal);
		$sucursalNum=count($sucursalCadena);
		for ($i=0; $i < $sucursalNum; $i++) { 
			$consultaSucursal="SELECT id FROM cat_sucursales WHERE sucursal='$sucursalCadena[$i]'";
			$queryU=$this->db->query($consultaSucursal);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="visitas.idsucursal=".$id;
				}
			else{
				$consulta.=" OR visitas.idsucursal=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
		if($ruta!="TODOS"){
				if($contador==0){
					$consulta.=" WHERE ";
				}
				else{
					$consulta.=" AND ";
				}
				$consulta.="(";
				$rutaCadena=explode(",", $ruta);
				$rutaNum=count($rutaCadena);
				for ($i=0; $i < $rutaNum; $i++) { 
					$consultaRuta="SELECT id FROM cat_rutas WHERE ruta='$rutaCadena[$i]'";
					$queryU=$this->db->query($consultaRuta);
					$id=$queryU->row()->id;
					if($i==0){
						$consulta.="cat_rutas.id=".$id;
						}
					else{
						$consulta.=" OR cat_rutas.id=".$id;
					}

				}
				$consulta.=")";
				$contador=$contador+1;

			}
	if($contador==0){
		$consulta.=" WHERE visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	}
	else{
		$consulta.=" AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	}
	if($contador!=0){
		$consulta.=" AND (".$cadena.")";
	}
	else{
		$consulta.=" AND (".$cadena.")";
	}
	$query=$this->db->query($consulta);
	return $query;
	//return $consulta;
}
public function getDatosEfectividadTotal($usuario,$sucursal,$ruta,$fIni,$fFin){
	$consulta="SELECT count(visitas.id) AS cuantos FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON cat_sucursales.id=usuarios.sucursal INNER JOIN cat_rutas ON cat_rutas.id=visitas.ruta";
	$contador=0;
	if($usuario!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		$consulta.="(";
		$usuarioCadena=explode(",", $usuario);
		$usuarioNum=count($usuarioCadena);
		for ($i=0; $i < $usuarioNum; $i++) { 
			$consultaUsuario="SELECT id FROM usuarios WHERE nombre='$usuarioCadena[$i]'";
			$queryU=$this->db->query($consultaUsuario);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="visitas.idusuario=".$id;
				}
			else{
				$consulta.=" OR visitas.idusuario=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
	
	if($sucursal!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		else{
			$consulta.=" AND ";
		}
		$consulta.="(";
		$sucursalCadena=explode(",", $sucursal);
		$sucursalNum=count($sucursalCadena);
		for ($i=0; $i < $sucursalNum; $i++) { 
			$consultaSucursal="SELECT id FROM cat_sucursales WHERE sucursal='$sucursalCadena[$i]'";
			$queryU=$this->db->query($consultaSucursal);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="visitas.idsucursal=".$id;
				}
			else{
				$consulta.=" OR visitas.idsucursal=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
		if($ruta!="TODOS"){
				if($contador==0){
					$consulta.=" WHERE ";
				}
				else{
					$consulta.=" AND ";
				}
				$consulta.="(";
				$rutaCadena=explode(",", $ruta);
				$rutaNum=count($rutaCadena);
				for ($i=0; $i < $rutaNum; $i++) { 
					$consultaRuta="SELECT id FROM cat_rutas WHERE ruta='$rutaCadena[$i]'";
					$queryU=$this->db->query($consultaRuta);
					$id=$queryU->row()->id;
					if($i==0){
						$consulta.="cat_rutas.id=".$id;
						}
					else{
						$consulta.=" OR cat_rutas.id=".$id;
					}

				}
				$consulta.=")";
				$contador=$contador+1;

			}
	if($contador==0){
		$consulta.=" visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	}
	else{
		$consulta.=" AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	}
	
	$query=$this->db->query($consulta);
	return $query;
}

public function getRutasPedidos(){
	$MS=VERIFICAMULTISUCURSAL();
		$sucursal=GETSUCURSAL();
		if($MS==1){
			$consulta="SELECT * FROM cat_rutas ORDER BY ruta ASC";
		}
		else{
			$consulta="SELECT * FROM cat_rutas WHERE sucursal=$sucursal ORDER BY ruta ASC";
		}
	$query=$this->db->query($consulta);
	return $query;
}
public function getIdPedido($cliente,$fecha){
	$id=0;
	$consulta="SELECT id FROM pedidos WHERE idcliente=$cliente AND fecha='$fecha'";
	$query=$this->db->query($consulta);
	foreach ($query->result() as $k) {
		$id=$k->id;
	}
	return $id;
}
public function getusuarios(){
		$MS=VERIFICAMULTISUCURSAL();
		$sucursal=GETSUCURSAL();
		if($MS==1){
			$consulta="SELECT * FROM usuarios WHERE vendedor=1 ORDER BY nombre ASC";
		}
		else{
			$consulta="SELECT * FROM usuarios WHERE vendedor=1 AND sucursal=$sucursal ORDER BY nombre ASC";
		}
	$query=$this->db->query($consulta);
	return $query;
}

/*public function getProductosJ(){
	$consulta="SELECT * FROM cat_productos";
	$query=$this->db->query($consulta);
	return $query;
}*/
/*public function getClientesJ(){
	$consulta="SELECT * FROM clientes";
	$query=$this->db->query($consulta);
	return $query;
}*/
/*public function getUsuariosJ(){
	$consulta="SELECT * FROM usuarios";
	$query=$this->db->query($consulta);
	return $query;
}*/
/*public function getZonasJ($id){
	$consulta="SELECT * FROM cat_zonas WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}*/
/*public function getCategoriasProductosJ($id){
	$consulta="SELECT * FROM cat_clasificacionproductos WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}*/

public function getPedidosH($fIni,$fFin){
	$consulta="SELECT historico_pedidos.id,historico_pedidos.folio, historico_pedidos.tipo, historico_pedidos.fecha, historico_pedidos.total, historico_pedidos.fechacreacion, historico_pedidos.impreso, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, cat_sucursales.sucursal AS sucursal, historico_pedidos.status, cat_rutas.ruta FROM historico_pedidos  INNER JOIN usuarios ON usuarios.id=historico_pedidos.idusuario INNER JOIN clientes ON clientes.id=historico_pedidos.idcliente INNER JOIN cat_sucursales ON cat_sucursales.id=clientes.sucursal INNER JOIN cat_rutas ON cat_rutas.chofer=historico_pedidos.idusuario WHERE historico_pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}

public function getPedidosDatos($fIni,$fFin,$idUsuario){
	$consulta="SELECT COUNT(total) AS numeroVentas,SUM(total) AS totalVentas FROM pedidos WHERE idusuario=$idUsuario AND tipo='PREVENTA' AND fecha BETWEEN '$fIni' AND '$fFin'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}

public function getVisitasH($fIni,$fFin){
	$consulta="SELECT historico_visitas.id,historico_visitas.codigocliente, historico_visitas.idcliente, historico_visitas.cliente, usuarios.nombre, historico_visitas.resultado, historico_visitas.fecha, historico_visitas.inicio, historico_visitas.fin, cat_sucursales.sucursal, cat_rutas.ruta FROM historico_visitas INNER JOIN usuarios ON usuarios.id=historico_visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id INNER JOIN cat_rutas ON cat_rutas.chofer=usuarios.id WHERE historico_visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;

}

public function getEfectividadH($fIni,$fFin){
	/*$consulta="SELECT usuarios.nombre, usuarios.id AS idUsuario, COUNT(visitas.idusuario) AS numeroVisitas, cat_sucursales.sucursal,MIN(visitas.fechacreacion) AS primera, MAX(visitas.fechacreacion) AS ultima FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";*/
	$consulta="SELECT DISTINCT (historicos_visitas.idusuario),usuarios.nombre, usuarios.id AS idUsuario, cat_sucursales.sucursal, cat_rutas.ruta FROM historicos_visitas INNER JOIN usuarios ON usuarios.id=historicos_visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id INNER JOIN cat_rutas ON cat_rutas.chofer=historicos_visitas.idusuario WHERE usuarios.status=1 AND historicos_visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;

}

public function getEfectividadAgendaH($fIni,$fFin){
	/*$consulta="SELECT usuarios.nombre, usuarios.id AS idUsuario, COUNT(visitas.idusuario) AS numeroVisitas, cat_sucursales.sucursal,MIN(visitas.fechacreacion) AS primera, MAX(visitas.fechacreacion) AS ultima FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";*/
	$consulta="SELECT DISTINCT (historico_visitas.idusuario),usuarios.nombre, usuarios.id AS idUsuario, cat_sucursales.sucursal, cat_rutas.ruta, historico_visitas.ruta AS idRuta FROM historico_visitas INNER JOIN usuarios ON usuarios.id=historico_visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id INNER JOIN cat_rutas ON cat_rutas.chofer=historico_visitas.idusuario WHERE usuarios.status=1 AND historico_visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;

}

public function getEfectividadEstVisitas($fIni,$fFin,$usuarioid){
	$consulta="SELECT COUNT(visitas.id) AS numeroVisitas, MIN(visitas.fechacreacion) AS primera, MAX(visitas.fechacreacion) AS ultima FROM visitas WHERE visitas.idusuario=$usuarioid AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;
}
public function getDatosAgenda($fIni,$fFin,$ruta){
	$fecha=$fIni;
	$visitasProgramadas=0;
	$visitasHechas=0;
	$visitasNoHechas=0;

	if($fecha==$fFin){
		$dia=date('w', strtotime($fecha));
		$dia=$dia+1;
		$consulta="SELECT COUNT(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona WHERE asi_ruta_zona.ruta=$ruta AND clientes.diasvisita LIKE '%$dia%'";
		$query=$this->db->query($consulta);
		$visitasProgramadas+=$query->row()->cuantos;
		/*if($query->num_rows()!=0){
			foreach($query->result() as $q2){
				$consulta2="SELECT id FROM visitas WHERE idcliente=$q2->id AND ruta=$ruta AND fecha='$fecha'";
				$query2=$this->db->query($consulta2);
				$visitasHechas+=$query->num_rows();
			}
		}*/
		$consulta2="SELECT COUNT(id) AS cuantossi FROM visitas WHERE ruta=$ruta AND fecha='$fecha' AND diasvisitas LIKE '%$dia%'";
				$query2=$this->db->query($consulta2);
				$visitasHechas+=$query2->row()->cuantossi;
	}
	else{
		while($fecha!=$fFin){
			$dia=date('w', strtotime($fecha));
			$dia=$dia-1;
				$consulta="SELECT COUNT(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona WHERE asi_ruta_zona.ruta=$ruta AND clientes.diasvisita LIKE '%$dia%'";
				$query=$this->db->query($consulta);
				$visitasProgramadas+=$query->row()->cuantos;
				/*if($query->num_rows()!=0){
					foreach($query->result() as $q2){
						$consulta2="SELECT id FROM visitas WHERE idcliente=$q2->id AND ruta=$ruta AND fecha='$fecha'";
						$query2=$this->db->query($consulta2);
						$visitasHechas+=$query->num_rows();
					}
				}*/
				$consulta2="SELECT COUNT(id) AS cuantossi FROM visitas WHERE ruta=$ruta AND fecha='$fecha' AND diasvisitas LIKE '%$dia%'";
						$query2=$this->db->query($consulta2);
						$visitasHechas+=$query2->row()->cuantossi;
			$fecha=AGREGARDIAS(1,$fecha);
		}
	}
	return $visitasProgramadas."-".$visitasHechas;
}
public function getDatosAgendaTodas($fIni,$fFin,$ruta){
	$fecha=$fIni;
	$visitasProgramadas=0;
	$visitasHechas=0;
	$visitasNoHechas=0;

	if($fecha==$fFin){
		$dia=date('w', strtotime($fecha));
		$dia=$dia-1;
		$consulta="SELECT COUNT(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona WHERE asi_ruta_zona.ruta=$ruta AND clientes.diasvisita LIKE '%$dia%'";
		$query=$this->db->query($consulta);
		$visitasProgramadas+=$query->row()->cuantos;
		/*if($query->num_rows()!=0){
			foreach($query->result() as $q2){
				$consulta2="SELECT id FROM visitas WHERE idcliente=$q2->id AND ruta=$ruta AND fecha='$fecha'";
				$query2=$this->db->query($consulta2);
				$visitasHechas+=$query->num_rows();
			}
		}*/
		$consulta2="SELECT COUNT(id) AS cuantossi FROM visitas WHERE ruta=$ruta AND fecha='$fecha' AND diasvisitas LIKE '%$dia%'";
				$query2=$this->db->query($consulta2);
				$visitasHechas+=$query2->row()->cuantossi;
	}
	else{
		while($fecha!=$fFin){
			$dia=date('w', strtotime($fecha));
			$dia=$dia-1;
				$consulta="SELECT COUNT(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona WHERE asi_ruta_zona.ruta=$ruta AND clientes.diasvisita LIKE '%$dia%'";
				$query=$this->db->query($consulta);
				$visitasProgramadas+=$query->row()->cuantos;
				/*if($query->num_rows()!=0){
					foreach($query->result() as $q2){
						$consulta2="SELECT id FROM visitas WHERE idcliente=$q2->id AND ruta=$ruta AND fecha='$fecha'";
						$query2=$this->db->query($consulta2);
						$visitasHechas+=$query->num_rows();
					}
				}*/
				$consulta2="SELECT COUNT(id) AS cuantossi FROM visitas WHERE ruta=$ruta AND fecha='$fecha' AND diasvisitas LIKE '%$dia%'";
						$query2=$this->db->query($consulta2);
						$visitasHechas+=$query2->row()->cuantossi;
			$fecha=AGREGARDIAS(1,$fecha);
		}
	}
	return $visitasProgramadas."-".$visitasHechas;
	//return $visitasProgramadas."-".$visitasHechas;
}

public function deletePedidos($id)
{
	$consulta = "UPDATE pedidos SET status = 0 WHERE id = '$id'";
	//die($consulta);
	$this->dbinfo->query($consulta);
}

public function getPedidoIndividual($fIni,$fFin){
	$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente FROM pedidos INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idclienteWHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;
}

/*public function getPedidosJ($fIni,$fFin)
{
	$consulta = "SELECT * FROM pedidos WHERE status=1 AND fecha BETWEEN '$fIni' AND '$fFin'";
	$query = $this->dbinfo->query($consulta);
	return $query;
}

public function getPedidosJ2($fIni,$fFin)
{
	$consulta = "SELECT pedidos.`idusuario`, pedidos.id as idpedido, folio AS id, codigocliente AS customer_code, idcliente AS customer_id, clientes.`nombre` AS customer_description, clientes.`email` AS customer_email,
	pedidos.`tipo` AS type, NULL AS delivery_schedule_date, NULL AS comment, (UNIX_TIMESTAMP(pedidos.`fechacreacion`)*1000) AS date_created,
	pedidos.`total` , NULL AS price_list, pedidos.`latitud` AS latitude , pedidos.`longitud` AS longitude, 0 AS accuracy, false AS deleted 
	FROM pedidos
	INNER JOIN clientes ON pedidos.`idcliente` = clientes.`id`
	WHERE pedidos.status=1 AND fecha BETWEEN '$fIni' AND '$fFin'";
	$query = $this->dbinfo->query($consulta);
	return $query;
}*/

/*public function getItemsJ($idpedido)
{
	$consulta = "SELECT id, codigoproducto AS product_code, iditem AS product_id, producto AS product_description, precio AS price,
	cantidad AS quantity, importe AS total, NULL AS comments 
	FROM pedidos_detalle 
	WHERE idpedido=$idpedido";
	$query = $this->dbinfo->query($consulta);
	return $query;
}*/

/*public function getClienteJ($id)
{
	$consulta = "SELECT * FROM clientes WHERE id=$id";
	$query = $this->dbinfo->query($consulta);
	return $query;
}*/

/*public function getCreadoporJ($idusuario){
	$consulta="SELECT id, usuario as username, nombre as name FROM usuarios WHERE id=$idusuario";
	$query=$this->db->query($consulta);
	return $query;
}*/

public function getIdProductoJ($codigo){
	$consulta="SELECT * FROM cat_productos WHERE codigo='$codigo'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}

public function getModulos()
	{
		$query = $this->db->query("SELECT * FROM modulos WHERE STATUS = 1 ORDER BY orden", false);
		return $query->result();
	}

	public function getSubModulos($pIdModulo)
	{
		$query = $this->db->query("SELECT * FROM modulos_sub WHERE STATUS = 1 AND idModulo = $pIdModulo ORDER BY orden", false);
		return $query;
	}
	public function getListaPerfiles(){
		$consulta="SELECT * FROM perfiles ORDER BY perfil ASC";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function getListaModulos(){
		$consulta="SELECT DISTINCT modulo, icono, color FROM funciones ORDER BY modulo ASC";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function getListaSubModulos($modulo){
		$consulta="SELECT id,submodulo,descripcion,perfiles,controlador,funcion FROM funciones WHERE modulo='$modulo' ORDER BY submodulo ASC";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function saveNewPerfil($datos){
		//print_r($_POST);
		$perfil=$_POST['txtPerfil'];
		$descripcion=$_POST['txtDescripcion'];
		if(isset($_POST['checkActivo'])){
			$activo=1;
		}
		else{
			$activo=0;
		}
		//ajustes,mas,redes moviles, operadores de red, manual, telcel 3g o 4g o telcel,
		$usuarioId=$this->session->userdata('userIdLIZER');
		$this->db->insert('perfiles',array('perfil'=>$perfil, 'descripcion'=>$descripcion, 'status'=>$activo, 'usuariocrea'=>$usuarioId));
		$consulta="SELECT id,perfiles FROM funciones";
		$query=$this->db->query($consulta);
		
		foreach ($query->result() as $kLista) {
			$id=$kLista->id;
			$perfiles=$kLista->perfiles;
			if(isset($_POST['Check'.$id])){
				$perfiles=$perfiles.$perfil.",";
				$consulta2="UPDATE funciones SET perfiles='$perfiles' WHERE id=$id";
				//echo "<br>".$consulta2;
				$query=$this->db->query($consulta2);
			}
		}
	}
	public function getDatosPerfil($id){
		$consulta="SELECT * FROM perfiles WHERE id=$id";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function saveEditPerfil($datos){
		print_r($_POST);
		$id=$_POST['txtId'];
		$perfilold=$_POST['txtPerfilOld'];
		$perfil=$_POST['txtPerfil'];
		$descripcion=$_POST['txtDescripcion'];
		if(isset($_POST['checkActivo'])){
			$activo=1;
		}
		else{
			$activo=0;
		}
		//ajustes,mas,redes moviles, operadores de red, manual, telcel 3g o 4g o telcel,
		$usuarioId=$this->session->userdata('userIdLIZER');
		$consultaUP="UPDATE perfiles SET perfil='$perfil', descripcion='$descripcion', status=$activo WHERE id=$id";
		$this->db->query($consultaUP);
		
		$consulta="SELECT id,perfiles FROM funciones";
		$query=$this->db->query($consulta);
		
		foreach ($query->result() as $kLista) {
			$id=$kLista->id;
			$perfiles=$kLista->perfiles;
			$perfiles=str_replace($perfilold.",","", $perfiles);
			if(isset($_POST['Check'.$id])){
				$perfiles=$perfiles.$perfil.",";
				
			}
			$consulta2="UPDATE funciones SET perfiles='$perfiles' WHERE id=$id";
				
				$query=$this->db->query($consulta2);
		}
	}

	public function delPerfil($id,$perfil)
	{
		$consultaDEL="DELETE FROM perfiles WHERE id=$id";
		$this->db->query($consultaDEL);
		$consulta="SELECT id,perfiles FROM funciones";
		$query=$this->db->query($consulta);
		
		foreach ($query->result() as $kLista)
		{
			$id=$kLista->id;
			$perfiles=$kLista->perfiles;
			$perfiles=str_replace($perfil.",","", $perfiles);
			$consulta2="UPDATE funciones SET perfiles='$perfiles' WHERE id=$id";	
				$query=$this->db->query($consulta2);
		}
	}

	public function reporteDistribucionJson($datos)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$fechaDe = $datos["fechaDe"];
		$fechaA = $datos["fechaA"];
		$sucursal = $datos["sucursal"];
		$clasificacion = $datos["clasificacion"];
		$proveedor = $datos["proveedor"];

		//$WHERE = "WHERE p.fecha BETWEEN '$fechaDe' AND '$fechaA' AND p.`status` = 1 AND tipo = 'PREVENTA'";
		$WHERE = "WHERE p.fecha BETWEEN '$fechaDe' AND '$fechaA' AND p.`status_principal` = 1 AND p.`status_detalle` = 1 AND p.tipo = 'PREVENTA'";
		
		if($sucursal != "0")
		{
			$WHERE = $WHERE." AND p.`idsucursal` = '$sucursal' ";
		}

		if($clasificacion != "")
		{
			$WHERE = $WHERE." AND p.`idclasificacion` IN ($clasificacion) ";
		}

		if($proveedor != "0")
		{
			$WHERE = $WHERE." AND p.`idproveedor` = '$proveedor' ";
		}

		$visitasWhere = "status = 1 AND fecha BETWEEN '$fechaDe' AND '$fechaA'";

		$consulta = "SELECT p.`ruta_nombre`, 
		COUNT(DISTINCT p.`idpedido`) AS numpedidos,
		SUM(((p.`cantidad_entregado` - p.`cantidad_rechazado`) * p.`precio`)) AS venta,
		p.`ruta`,
		COALESCE(v.visitasrealizadas, 0) AS visitasrealizadas,
		COALESCE(vp.visitasprogramadas, 0) AS visitasprogramadas
		FROM vwInformacionGeneralPedidos p
		LEFT JOIN (
			SELECT ruta, COUNT(DISTINCT idcliente, fecha) AS visitasrealizadas
			FROM visitas
			WHERE $visitasWhere
			GROUP BY ruta
		) v ON v.ruta = p.ruta
		LEFT JOIN (
			SELECT rz.ruta, COALESCE(SUM(num.cnt), 0) AS visitasprogramadas
			FROM clientes c
			INNER JOIN asi_ruta_zona rz
				ON rz.zona = c.zona
				AND rz.status = 1
			INNER JOIN (
				SELECT w,
				FLOOR((DATEDIFF('$fechaA', '$fechaDe') - ((w - DAYOFWEEK('$fechaDe') + 7) % 7) + 7) / 7) AS cnt
				FROM (SELECT 1 AS w UNION SELECT 2 AS w UNION SELECT 3 AS w UNION SELECT 4 AS w UNION SELECT 5 AS w UNION SELECT 6 AS w UNION SELECT 7 AS w) w
			) num ON FIND_IN_SET(num.w, c.diasvisita)
			WHERE c.status = 1
			GROUP BY rz.ruta
		) vp ON vp.ruta = p.ruta
		$WHERE
		GROUP BY p.`ruta`";

		//die($consulta);
		$query = $db->query($consulta);

		return $query->result_array();
	}

	public function infoVisitas($ruta, $fecha, $fechaDe, $fechaA)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$consulta = "SELECT fnGetClientesVisitaRutaByDia('$ruta', '$fecha', '$fechaDe', '$fechaA') as visitasprogramadas, fnGetClientesVisitoRutaByDia('$ruta', '$fecha') as visitasrealizadas ";

		$query = $db->query($consulta);

		return $query->row();
	}

	public function listaReporteRepartoEntregasJson($data)
	{
		$this->dbinfo->select("*,
		DATE(rdp.fecha_registro) AS fecha_descarga,
		`fnGetSucursalById`(rdp.idsucursal) AS sucursal_nombre,
		(SELECT TIME(entregado_fecha) FROM pedidos p WHERE p.id_reparto_descarga = rdp.`id` ORDER BY fechacreacion LIMIT 1) AS hora_inicio,
		(SELECT TIME(entregado_fecha) FROM pedidos p WHERE p.id_reparto_descarga = rdp.`id` ORDER BY fechacreacion DESC LIMIT 1) AS hora_final,
		(SELECT COUNT(*) FROM pedidos p WHERE p.entregado_estatus IN(1,3) AND p.id_reparto_descarga = rdp.`id`) AS entregado,
		CONCAT((SELECT entregado), '/', rdp.`pedidos`) AS efectividad");
		$this->dbinfo->from('reparto_descarga_pedidos rdp');
		$this->dbinfo->where('DATE(rdp.fecha_registro) >=', $data["fecha_inicio"]);
		$this->dbinfo->where('DATE(rdp.fecha_registro) <=', $data["fecha_final"]);

		if($data["idsucursal"]!="0") $this->dbinfo->where('rdp.idsucursal', $data["idsucursal"]);
		if($data["idreparto"]!="0") $this->dbinfo->where('rdp.idusuario', $data["idreparto"]);
		if($data["ruta"]!="0") $this->dbinfo->like('rdp.rutas', $data["ruta"], 'both');

		$query = $this->dbinfo->get()->result_array();

		foreach ($query as $key => $value)
		{
			$query[$key]["usuario_nombre"] = (($value["idusuario"]=="0") ? "NO ASIGNADO" : GETDATOSUSUARIO($value["idusuario"], "nombre")->nombre);
		}

		return $query;
	}

	public function listaReporteRepartoDepositosJson($data)
	{
		$query = $this->dbinfo->query("SELECT *, DATE(fechahora_registro) AS fecha, TIME(fechahora_registro) AS hora 
		FROM reparto_depositos 
		WHERE DATE(fechahora_registro) = '$data[fecha]' AND idusuario = '$data[idusuario]'
		ORDER BY fechahora_registro")->result();

		foreach($query as $key => $value)
		{
			$query[$key]->importe_total = "$".number_format($query[$key]->importe_total, 2, '.', ',');
			$query[$key]->importe_real = "$".number_format($query[$key]->importe_real, 2, '.', ',');
			$query[$key]->importe_deposito = "$".number_format($query[$key]->importe_deposito, 2, '.', ',');
			$query[$key]->importe_disponible = "$".number_format($query[$key]->importe_disponible, 2, '.', ',');
		}

		return $query;
	}

	public function listaReporteRepartoEntregasUsuarioJson($data)
	{
		$query = $this->dbinfo->query("SELECT *, DATE(entregado_fecha) AS fecha_entrega, TIME(entregado_fecha) AS hora_entrega 
		FROM pedidos 
		WHERE id_reparto_descarga = '$data[id_reparto]'
		ORDER BY entregado_fecha")->result();

		$ultima_fecha = "";

		foreach ($query as $key => $value)
		{
			$estatus = "";
			$duracion = "";

			if($ultima_fecha != "")
			{
				$duracion = DATE_DIFFERENCE($value->entregado_fecha, $ultima_fecha, "%h:%i");
			}

			$ultima_fecha = $value->entregado_fecha;

			if($value->entregado_estatus == "0")
			{
				$estatus = "PENDIENTE";
			}
			else if($value->entregado_estatus == "1")
			{
				$estatus = "ENTREGADO";
			}
			else if($value->entregado_estatus == "2")
			{
				$estatus = "RECHAZADO";
			}
			else if($value->entregado_estatus == "3")
			{
				$estatus = "PARCIAL";
			}

			$query[$key]->estatus = $estatus;
			$query[$key]->duracion = $duracion;
		}

		return $query;
	}

	public function listaReporteUtilidadJson($data)
	{
		setlocale(LC_MONETARY, 'es_MX');

		$time = strtotime($data["fecha_inicio"]);
		$periodo = date('Ym', $time);

		$impuesto = "p.ieps";

		if(GETEMPRESA() == "02271106")
		{
			$impuesto = "p.iva";
		}

		if($data["sucursal"] == "0")
		{
			$query = $this->dbinfo->query("SELECT datos.nombre_sucursal,
			SUM(venta_cimpuesto) AS venta_cimpuesto, SUM(costo_cimpuesto) AS costo_cimpuesto,
			SUM(venta_simpuesto) AS venta_simpuesto, SUM(costo_simpuesto) AS costo_simpuesto,
			(SELECT SUM(gs.`importe`) FROM gastos_sucursal gs WHERE gs.`periodo` = '$periodo' AND gs.`idsucursal` = datos.idsucursal AND FIND_IN_SET(gs.idnegocio, '$data[negocio]')) AS gastos,
			(SELECT SUM(gs.`importe`) FROM ingresos_sucursal gs WHERE gs.`periodo` = '$periodo' AND gs.`idsucursal` = datos.idsucursal AND FIND_IN_SET(gs.idnegocio, '$data[negocio]')) AS otros_ingresos,
			(SELECT rutaslaboradas FROM gastos_sucursal gs WHERE gs.`periodo` = '$periodo' AND gs.`idsucursal` = datos.idsucursal LIMIT 1) AS rutaslaboradas,
			(SELECT cs.orden FROM cat_sucursales cs WHERE cs.id = datos.idsucursal) AS orden
			FROM(SELECT datos.*,
			SUM(datos.cantidad_real * datos.precio) AS venta_cimpuesto,
			SUM(datos.cantidad_real * datos.costo) AS costo_cimpuesto,
			SUM(datos.cantidad_real * datos.precio_simpuesto) AS venta_simpuesto,
			SUM(datos.cantidad_real * datos.costouni_simpuesto) AS costo_simpuesto
			FROM(
			SELECT p.nombre_sucursal, p.idsucursal, p.iditem, p.tipo, IF(tipo='preventa', (cantidad_entregado - cantidad_rechazado), (cantidad_entregado - cantidad_rechazado) * -1) AS cantidad_real, IF($impuesto = 0, 0, (($impuesto/100)+1)) AS ieps2, precio, IF($impuesto = 0, precio, (precio / (SELECT ieps2))) AS precio_simpuesto, costo, IF($impuesto = 0, costo, (costo / (SELECT ieps2))) AS costouni_simpuesto,
			(SELECT cp.proveedor FROM cat_productos cp WHERE cp.id = p.iditem) AS proveedor
			FROM vwInformacionGeneralPedidos p
			WHERE p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha BETWEEN '$data[fecha_inicio]' AND '$data[fecha_final]' AND FIND_IN_SET(p.tipo, '$data[tipo]'))
			AS datos
			WHERE FIND_IN_SET(datos.proveedor, '$data[negocio]')
			GROUP BY datos.idsucursal, datos.tipo) AS datos
			GROUP BY datos.idsucursal ORDER BY orden")->result();
		}
		else
		{
			$query = $this->dbinfo->query("SELECT datos.ruta_nombre,
			SUM(venta_cimpuesto) AS venta_cimpuesto, SUM(costo_cimpuesto) AS costo_cimpuesto,
			SUM(venta_simpuesto) AS venta_simpuesto, SUM(costo_simpuesto) AS costo_simpuesto,
			(SELECT SUM(gs.`importe`) FROM gastos_sucursal gs WHERE gs.`periodo` = '$periodo' AND gs.`idsucursal` = datos.idsucursal AND FIND_IN_SET(gs.idnegocio, '$data[negocio]')) AS gastos,
			(SELECT SUM(gs.`importe`) FROM ingresos_sucursal gs WHERE gs.`periodo` = '$periodo' AND gs.`idsucursal` = datos.idsucursal AND FIND_IN_SET(gs.idnegocio, '$data[negocio]')) AS otros_ingresos,
			(SELECT rutaslaboradas FROM gastos_sucursal gs WHERE gs.`periodo` = '$periodo' AND gs.`idsucursal` = datos.idsucursal LIMIT 1) AS rutaslaboradas
			FROM(SELECT datos.*,
			SUM(datos.cantidad_real * datos.precio) AS venta_cimpuesto,
			SUM(datos.cantidad_real * datos.costo) AS costo_cimpuesto,
			SUM(datos.cantidad_real * datos.precio_simpuesto) AS venta_simpuesto,
			SUM(datos.cantidad_real * datos.costouni_simpuesto) AS costo_simpuesto
			FROM(
			SELECT p.nombre_sucursal, p.idsucursal, p.iditem, p.ruta, p.ruta_nombre, p.tipo, IF(tipo='preventa', (cantidad_entregado - cantidad_rechazado), (cantidad_entregado - cantidad_rechazado) * -1) AS cantidad_real, IF($impuesto = 0, 0, (($impuesto/100)+1)) AS ieps2, precio, IF($impuesto = 0, precio, (precio / (SELECT ieps2))) AS precio_simpuesto, costo, IF($impuesto = 0, costo, (costo / (SELECT ieps2))) AS costouni_simpuesto,
			(SELECT cp.proveedor FROM cat_productos cp WHERE cp.id = p.iditem) AS proveedor
			FROM vwInformacionGeneralPedidos p
			WHERE p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha BETWEEN '$data[fecha_inicio]' AND '$data[fecha_final]' AND FIND_IN_SET(p.tipo, '$data[tipo]'))
			AS datos
			WHERE FIND_IN_SET(datos.proveedor, '$data[negocio]') AND datos.idsucursal = '$data[sucursal]'
			GROUP BY datos.ruta, datos.tipo) AS datos
			GROUP BY datos.ruta")->result();
		}

		//die($this->dbinfo->last_query());
		$totalventa = 0;
		//$totalotrosingresos = 0;
		foreach ($query as $key => $value)
		{
			$gastos = $value->gastos;
			$otrosingresos = $value->otros_ingresos;
			$rutaslaboradas = $value->rutaslaboradas;

			if($data["sucursal"] != "0")
			{
				$query[$key]->nombre_sucursal = $value->ruta_nombre;

				//$gastos = 0;
				//$otrosingresos = 0;

				if($rutaslaboradas > 0)
				{
					$gastos = $gastos / $rutaslaboradas;
					$otrosingresos = $otrosingresos / $rutaslaboradas;
				}
			}

			if($data["impuestos"] == "1")
			{
				$venta = $value->venta_cimpuesto;
				$costo = $value->costo_cimpuesto;

				$totalingresos = $otrosingresos + $venta;
				$utilidadbruta = $totalingresos - $costo;
				$utilidadneta = $utilidadbruta - $gastos;
				$importemargen = $venta - $costo;
				$porcentajemargen = $venta == 0 ? 0 : ($importemargen / $venta) * 100;
				$porcentajegastos = $venta == 0 ? 0 : ($gastos / $venta) * 100;

				$query[$key]->venta = str_replace('$', '', money_format_2($venta));
				$query[$key]->otrosingresos = str_replace('$', '', money_format_2($otrosingresos));
				$query[$key]->totalingresos = str_replace('$', '', money_format_2($totalingresos));
				$query[$key]->costo = str_replace('$', '', money_format_2($costo));
				$query[$key]->utilidad_bruta = str_replace('$', '', money_format_2($utilidadbruta));
				$query[$key]->gastos = str_replace('$', '', money_format_2($gastos));
				$query[$key]->porcentaje_gastos = str_replace('$', '', money_format_2($porcentajegastos)).'%';
				$query[$key]->importe_margen = str_replace('$', '', money_format_2($importemargen));
				$query[$key]->porcentaje_margen = str_replace('$', '', money_format_2($porcentajemargen)).'%';
				$query[$key]->utilidad_neta = str_replace('$', '', money_format_2($utilidadneta));

				$totalventa = $totalventa + $venta;
				//$totalotrosingresos = $totalotrosingresos + $otrosingresos;
			}
			else
			{
				$venta = $value->venta_simpuesto;
				$costo = $value->costo_simpuesto;

				$totalingresos = $otrosingresos + $venta;
				$utilidadbruta = $totalingresos - $costo;
				$utilidadneta = $utilidadbruta - $gastos;
				$importemargen = $venta - $costo;
				$porcentajemargen = $venta == 0 ? 0 : ($importemargen / $venta) * 100;
				$porcentajegastos = $venta == 0 ? 0 : ($gastos / $venta) * 100;

				$query[$key]->venta = str_replace('$', '', money_format_2($venta));
				$query[$key]->otrosingresos = str_replace('$', '', money_format_2($otrosingresos));
				$query[$key]->totalingresos = str_replace('$', '', money_format_2($totalingresos));
				$query[$key]->costo = str_replace('$', '', money_format_2($costo));
				$query[$key]->utilidad_bruta = str_replace('$', '', money_format_2($utilidadbruta));
				$query[$key]->gastos = str_replace('$', '', money_format_2($gastos));
				$query[$key]->porcentaje_gastos = str_replace('$', '', money_format_2($porcentajegastos)).'%';
				$query[$key]->importe_margen = str_replace('$', '', money_format_2($importemargen));
				$query[$key]->porcentaje_margen = str_replace('$', '', money_format_2($porcentajemargen)).'%';
				$query[$key]->utilidad_neta = str_replace('$', '', money_format_2($utilidadneta));

				$totalventa = $totalventa + $venta;
			}
		}

		if($data["sucursal"] == "0")
		{
			foreach ($query as $key => $value)
			{
				$venta = str_replace(',', '', $query[$key]->venta);

				$porcentajeparticipacion = $venta <= 0 ? 0 : ($venta / $totalventa) * 100;
				$query[$key]->porcentaje_participacion = str_replace('$', '', money_format_2($porcentajeparticipacion)).'%';
			}
		}
		else
		{
			foreach ($query as $key => $value)
			{
				$venta = str_replace(',', '', $query[$key]->venta);
				$costo = str_replace(',', '', $query[$key]->costo);
				$gastos = str_replace(',', '', $query[$key]->gastos);

				$otrosingresos = $venta <= 0 ? 0 : ($venta / $totalventa) * $value->otros_ingresos;
				$porcentajeparticipacion = $venta <= 0 ? 0 : ($venta / $totalventa) * 100;

				$totalingresos = $otrosingresos + $venta;
				$utilidadbruta = $totalingresos - $costo;
				$utilidadneta = $utilidadbruta - $gastos;

				$query[$key]->otrosingresos = str_replace('$', '', money_format_2($otrosingresos));
				$query[$key]->totalingresos = str_replace('$', '', money_format_2($totalingresos));
				$query[$key]->utilidad_bruta = str_replace('$', '', money_format_2($utilidadbruta));
				$query[$key]->utilidad_neta = str_replace('$', '', money_format_2($utilidadneta));
				$query[$key]->porcentaje_participacion = str_replace('$', '', money_format_2($porcentajeparticipacion)).'%';
			}
		}

		return $query;
	}

	public function listaReportePresupuestosJson($data)
	{
		setlocale(LC_MONETARY, 'es_MX');

		$impuesto = "p.ieps";

		if(GETEMPRESA() == "02271106")
		{
			$impuesto = "p.iva";
		}

		$data["fecha_inicio"] = "$data[mes1]-01";
		$data["fecha_final"] = "$data[mes2]-31";

		$rangoperiodos = "";

		$start    = (new DateTime($data["mes1"]))->modify('first day of this month');
		$end      = (new DateTime($data["mes2"]))->modify('first day of next month');
		$interval = DateInterval::createFromDateString('1 month');
		$period   = new DatePeriod($start, $interval, $end);

		foreach ($period as $dt) {
			$rangoperiodos = $rangoperiodos.$dt->format("Ym").",";
		}

		if($rangoperiodos != "")
		{
			$rangoperiodos = substr($rangoperiodos, 0, -1);
		}

		$query = $this->dbinfo->query("SELECT * 
		FROM cat_sucursales 
		ORDER BY orden")->result();

		foreach ($query as $key => $value)
		{
			$presupuestado = 0;
			$real = 0;

			$valor = "";
			$valorreal = "";

			if($data["presupuesto"] == "v")
			{
				$valor = "presupuesto_ventas";
				$valorreal = "datos.venta_cimpuesto";
			}
			else if($data["presupuesto"] == "cv")
			{
				$valor = "presupuesto_costos";
				$valorreal = "datos.costo_cimpuesto";
			}
			else if($data["presupuesto"] == "g")
			{
				$valor = "presupuesto_gastos";
				$valorreal = "datos.gastos";
			}
			else if($data["presupuesto"] == "oi")
			{
				$valor = "presupuesto_otrosingresos";
				$valorreal = "datos.otros_ingresos";
			}
			else if($data["presupuesto"] == "uo")
			{
				$valor = "IFNULL(presupuesto_ventas, 0) + IFNULL(presupuesto_otrosingresos, 0) - IFNULL(presupuesto_costos, 0) - IFNULL(presupuesto_gastos, 0)";
				$valorreal = "IFNULL(datos.venta_cimpuesto, 0) + IFNULL(datos.otros_ingresos, 0) - IFNULL(datos.costo_cimpuesto, 0) - IFNULL(datos.gastos, 0)";
			}

			$rowreal = $this->dbinfo->query("SELECT $valorreal AS dato FROM (SELECT datos.nombre_sucursal,
			SUM(venta_cimpuesto) AS venta_cimpuesto, SUM(costo_cimpuesto) AS costo_cimpuesto,
			SUM(venta_simpuesto) AS venta_simpuesto, SUM(costo_simpuesto) AS costo_simpuesto,
			(SELECT SUM(gs.`importe`) FROM gastos_sucursal gs WHERE FIND_IN_SET(gs.`periodo`, '$rangoperiodos') AND gs.`idsucursal` = datos.idsucursal AND FIND_IN_SET(gs.idnegocio, '$data[negocio]')) AS gastos,
			(SELECT SUM(gs.`importe`) FROM ingresos_sucursal gs WHERE FIND_IN_SET(gs.`periodo`, '$rangoperiodos') AND gs.`idsucursal` = datos.idsucursal AND FIND_IN_SET(gs.idnegocio, '$data[negocio]')) AS otros_ingresos,
			(SELECT rutaslaboradas FROM gastos_sucursal gs WHERE FIND_IN_SET(gs.`periodo`, '$rangoperiodos') AND gs.`idsucursal` = datos.idsucursal LIMIT 1) AS rutaslaboradas
			FROM(SELECT datos.*,
			SUM(datos.cantidad_real * datos.precio) AS venta_cimpuesto,
			SUM(datos.cantidad_real * datos.costo) AS costo_cimpuesto,
			SUM(datos.cantidad_real * datos.precio_simpuesto) AS venta_simpuesto,
			SUM(datos.cantidad_real * datos.costouni_simpuesto) AS costo_simpuesto
			FROM(
			SELECT p.nombre_sucursal, p.idsucursal, p.iditem, p.tipo, IF(tipo='preventa', (cantidad_entregado - cantidad_rechazado), (cantidad_entregado - cantidad_rechazado) * -1) AS cantidad_real, IF($impuesto = 0, 0, (($impuesto/100)+1)) AS ieps2, precio, IF($impuesto = 0, precio, (precio / (SELECT ieps2))) AS precio_simpuesto, costo, IF($impuesto = 0, costo, (costo / (SELECT ieps2))) AS costouni_simpuesto,
			(SELECT cp.proveedor FROM cat_productos cp WHERE cp.id = p.iditem) AS proveedor
			FROM vwInformacionGeneralPedidos p
			WHERE p.idsucursal = '$value->id' AND p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha BETWEEN '$data[fecha_inicio]' AND '$data[fecha_final]' AND FIND_IN_SET(p.tipo, 'preventa'))
			AS datos
			WHERE FIND_IN_SET(datos.proveedor, '$data[negocio]')
			) AS datos) AS datos");

			//die($this->dbinfo->last_query());

			$rowpresupuestado = $this->dbinfo->query("SELECT SUM($valor) AS presupuesto 
			FROM presupuesto_periodo 
			WHERE FIND_IN_SET(CONCAT(anio, mes), '$rangoperiodos') AND FIND_IN_SET(idnegocio, '$data[negocio]') AND idsucursal = '$value->id'");

			//die($this->dbinfo->last_query());

			if($rowpresupuestado->num_rows() > 0)
			{
				$presupuestado = $rowpresupuestado->row()->presupuesto;
			}

			if($rowreal->num_rows() > 0)
			{
				$real = $rowreal->row()->dato;
			}

			$comparativo = $real - $presupuestado;

			if($presupuestado == 0)
			{
				$porcentaje = 0;
			}
			else
			{
				$porcentaje = ($real / $presupuestado) * 100;
			}

			$query[$key]->presupuestado = str_replace('$', '', money_format_2($presupuestado));
			$query[$key]->real = str_replace('$', '', money_format_2($real));
			$query[$key]->comparativo = str_replace('$', '', money_format_2($comparativo));
			$query[$key]->porcentaje = str_replace('$', '', money_format_2($porcentaje));
		}

		return $query;
	}

	public function listaCortesPendientesJson()	
	{
		$query = $this->dbinfo->query("SELECT idsucursal, GROUP_CONCAT(DISTINCT fecha SEPARATOR ' | ') AS fechas,
		COUNT(*) AS pendientes,
		fnGetFechasCortesPorHacer(cortes.idsucursal, 'fechas') AS pendientes_hacer,
		fnGetFechasCortesPorHacer(cortes.idsucursal, 'numero') AS pendientes_hacer_numero,
		fnGetSucursalById(idsucursal) AS sucursal
		FROM cortes 
		WHERE STATUS = 1		
		GROUP BY idsucursal		
		HAVING (pendientes > 0 OR pendientes_hacer_numero > 0)")->result();

		return $query;
	}

	public function generarExcelPedidos($pFecha1, $pFecha2, $pSucursal)	
	{
		$query = $this->dbinfo->query("SELECT fecha AS Fecha, tipo AS Tipo, folio AS 'ID Pedido', foliobees AS 'FOLIO BEES', iditem AS 'ID Item', ruta AS 'ID Ruta', ruta_nombre AS Ruta,
		codigocliente AS 'Cod. Cliente', cliente AS Cliente, 
		(SELECT ccc.`clasificacion` FROM cat_clasificacion_cliente ccc WHERE ccc.id = (SELECT c.`clasificacion` FROM clientes c WHERE c.id = p.`idcliente` LIMIT 1) LIMIT 1) AS 'Cla. Cliente',
		codigoproducto AS 'Cod. Producto', producto AS Producto, categoria_nombre AS Categoria,(cantidad_entregado - cantidad_rechazado) AS Cantidad, precio AS Precio,
		((SELECT Cantidad) * precio) AS Total,
		nombre_sucursal AS Sucursal,
		(SELECT cp.nombre FROM cat_proveedor cp WHERE cp.id = p.`idproveedor`) AS Negocio,
		((SELECT Cantidad) * precio) AS Surtido,
		p.canal
		FROM vwInformacionGeneralPedidos p
		WHERE p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha BETWEEN '$pFecha1' AND '$pFecha2'
		ORDER BY p.folio");

		return $query;
	}

	public function getUbicacionRutas($data)
	{
		$query = $this->dbinfo->query("SELECT datos.* FROM(
			SELECT p.`ruta`, p.`fechacreacion`, p.`cliente`, fnGetRutaById(p.`ruta`) AS ruta_nombre, 'PEDIDO' AS tipo, p.`latitud`, p.`longitud`
			FROM pedidos p
			WHERE p.fecha = CURDATE() AND p.`idsucursal` = '$data[idsucursal]'
			UNION
			SELECT v.ruta, v.fechacreacion, v.cliente, fnGetRutaById(v.`ruta`) AS ruta_nombre, 'VISITA' AS tipo, v.`latitud`, v.`longitud`
			FROM visitas v
			WHERE v.fecha = CURDATE() AND v.`idsucursal` = '$data[idsucursal]'
			ORDER BY fechacreacion DESC) AS datos
			GROUP BY datos.ruta
			ORDER BY datos.ruta_nombre");

		return $query;
	}

	public function getMesaControlVisitasHoraColumnas($fecha, $idsucursal)
	{
		$query = $this->dbinfo->query("SELECT ruta, DATE_FORMAT( inicio, '%H' ) AS hora
		FROM visitas
		WHERE fecha = '$fecha' AND idsucursal = '$idsucursal'
		GROUP BY hora
		ORDER BY hora"
		)->result();

		$datos = array();
		$datos[] = "Ruta";

		foreach ($query as $key => $value)
		{
			$datos[] = $value->hora;
		}

		return $datos;
	}

	public function getMesaControlVisitasHoraValores($fecha, $idsucursal, $columnas, $rutas)
	{
		$rutas = $rutas->result_array();

		$rows = $this->dbinfo->query("SELECT fecha, ruta, usuario,  DATE_FORMAT( inicio, '%H' ) AS hora, COUNT(*) AS registros
		FROM visitas 
		WHERE fecha = '$fecha' AND idsucursal = '$idsucursal'
		GROUP BY ruta, hora
		ORDER BY hora, ruta
		")->result_array();

		foreach ($rutas as $key => $value)
		{
			foreach($columnas as $columna)
			{
				$idruta = $value["id"];
				$hora = $columna;

				if($hora == "Ruta")
				{
					
				}
				else
				{
					$cantidad = "0";
					foreach($rows as $row)
					{
						if($row["ruta"] == $value["id"] and $row["hora"] == $columna)
						{
							$cantidad = $row["registros"];
							break;
						}
					}
			
					$rutas[$key][$columna] = $cantidad;
				}
			}
		}

		return $rutas;
	}

	public function getReporteDistribucionNegocioJson($fecha, $idproveedor, $idsucursal)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$query = $db->query("SELECT fnGetProveedorById(p.idproveedor) AS negocio, p.ruta, p.`ruta_nombre`,
		FORMAT(SUM(((p.`cantidad_entregado` - p.`cantidad_rechazado`) * p.`precio`)), 2) AS grantotal,
		fnGetClientesVisitaRutaByDia(p.`ruta`, p.`fecha`, '', '') AS visitas_programadas,
		fnGetClientesVisitoRutaByDia(p.`ruta`, p.`fecha`) AS visitas_hechas,
		TRUNCATE(((SELECT visitas_hechas) / (SELECT visitas_programadas)) * 100, 1) AS cumplimiento_agenda,
		COUNT(DISTINCT p.idpedido) AS pedidos,
		FORMAT((SUM(((p.`cantidad_entregado` - p.`cantidad_rechazado`) * p.`precio`)) / COUNT(DISTINCT p.idpedido)), 2) AS dropsize,
		TRUNCATE((COUNT(DISTINCT p.idpedido) / (SELECT visitas_programadas)) * 100, 1) AS efectividad,
		(SELECT inicio FROM visitas v WHERE v.fecha = p.fecha AND v.ruta = p.ruta ORDER BY v.inicio LIMIT 1) AS inicio_ruta,
		(SELECT inicio FROM visitas v WHERE v.fecha = p.fecha AND v.ruta = p.ruta ORDER BY v.inicio DESC LIMIT 1) AS fin_ruta,
		TIME_FORMAT(TIMEDIFF((SELECT fin_ruta), (SELECT inicio_ruta)), '%H:%i') AS tiempo_laborado
		FROM vwInformacionGeneralPedidos p
		WHERE p.`status_principal` = 1 AND p.`status_detalle` = 1 AND p.`tipo` = 'PREVENTA' AND
		p.fecha = '$fecha' AND p.`idproveedor` = '$idproveedor' AND p.idsucursal = '$idsucursal'
		GROUP BY p.`ruta`"
		)->result_array();

		//die($db->last_query());

		$ids = "";
		foreach($query as $item)
		{
			$ids.= $item["ruta"].',';
		}

		if($ids != "")
		{
			$ids = substr_replace($ids, "", -1);
		}

		$rutas = $db->query("SELECT 
		fnGetProveedorById($idproveedor) AS negocio,
		cr.id as ruta, cr.ruta as ruta_nombre, 0 as grantotal,
		fnGetClientesVisitaRutaByDia(cr.id, '$fecha', '', '') AS visitas_programadas,
		0 as visitas_hechas, 0 as cumplimiento_agenda, 0 as pedidos, 0 dropsize, 0 as efectividad, 
		'' as inicio_ruta, '' as fin_ruta, '' as tiempo_laborado
		FROM cat_rutas cr 
		WHERE cr.`status` = 1 AND 
		cr.`sucursal` = '$idsucursal' AND NOT FIND_IN_SET(cr.id, '$ids')"
		)->result_array();

		$output = array_unique(array_merge($query, $rutas), SORT_REGULAR);

		return $output;
	}

	public function getReporteVentaCategoriaJson($fecha, $idruta, $tipo)
	{
		$query = $this->dbinfo->query("SELECT p.ruta, p.ruta_nombre, p.`idclasificacion`, p.`categoria_nombre`,
		FORMAT(SUM(((p.`cantidad_entregado` - p.`cantidad_rechazado`) * p.`precio`)), 2) AS grantotal,
		COUNT(DISTINCT p.`idcliente`) AS clientes,
		COUNT(DISTINCT p.`iditem`) AS productos
		FROM vwInformacionGeneralPedidos p
		WHERE p.`status_principal` = 1 AND p.`status_detalle` = 1 AND
		p.`tipo` = '$tipo' AND p.fecha = '$fecha' AND p.`ruta` = '$idruta'
		GROUP BY p.`idclasificacion`"
		)->result_array();

		$ids = "";
		foreach($query as $item)
		{
			$ids.= $item["idclasificacion"].',';
		}

		if($ids != "")
		{
			$ids = substr_replace($ids, "", -1);
		}

		$rutas = $this->dbinfo->query("SELECT $idruta as ruta, 
		fnGetRutaById($idruta),
		ccp.id as idclasificacion, ccp.nombre as categoria_nombre, 0 as grantotal, 0 as clientes, 0 as productos
		FROM cat_clasificacionproductos ccp
		WHERE ccp.`status` = '1' AND
		NOT FIND_IN_SET(ccp.id, '$ids')"
		)->result_array();

		$output = array_unique(array_merge($query, $rutas), SORT_REGULAR);

		return $output;
	}

	public function getReporteVentaClienteJson($fecha, $idruta, $tipo, $idcategoria)
	{
		$query = $this->dbinfo->query("SELECT p.`folio`, p.`idcliente`, p.`cliente`, p.`idclasificacion`, p.`categoria_nombre`,
		FORMAT(SUM(((p.`cantidad_entregado` - p.`cantidad_rechazado`) * p.`precio`)), 2) AS grantotal,
		COUNT(DISTINCT p.`iditem`) AS productos
		FROM vwInformacionGeneralPedidos p
		WHERE p.`status_principal` = 1 AND p.`status_detalle` = 1 AND
		p.`tipo` = '$tipo' AND p.fecha = '$fecha' AND p.`ruta` = '$idruta' AND p.`idclasificacion` = '$idcategoria'
		GROUP BY p.`idcliente`"
		)->result_array();

		$idsclientes = "";
		foreach($query as $item)
		{
			$idsclientes.= $item["idcliente"].',';
		}		

		if($idsclientes != "")
		{
			$idsclientes = substr_replace($idsclientes, "", -1);
		}

		$clientes = $this->dbinfo->query("SELECT '' AS folio, cli.id as idcliente, cli.`nombre` AS cliente, 0 AS idclasificacion, '' AS categoria_nombre, 0 AS grantotal, 0 AS productos
		FROM clientes cli
		WHERE cli.`zona` IN (SELECT z.zona FROM asi_ruta_zona z WHERE z.ruta = '$idruta' AND z.STATUS = 1) 
		AND cli.`diasvisita` LIKE CONCAT('%', DAYOFWEEK('$fecha'), '%') AND NOT FIND_IN_SET(cli.`id`, '$idsclientes') AND cli.`status` = 1"
		)->result_array();

		$output = array_unique(array_merge($query, $clientes), SORT_REGULAR);

		/*echo "<pre>";
		print_r($clientes);
		echo "</pre>";
		die();*/

		//echo "sin venta: ".count($output)."<br/>";

		return $output;
	}
}

?>