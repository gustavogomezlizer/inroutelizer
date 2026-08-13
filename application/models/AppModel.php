<?php
class AppModel extends CI_Model {

	private $dbinfo;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();		
	}

	public function getusuario2($user,$pass,$company)
	{
		//$consulta="SELECT * FROM usuarios WHERE STATUS = 1 AND vendedor = 1 AND usuario = '$user' AND clave = '$pass' AND empresa = '$company' LIMIT 1";
		$consulta="SELECT usuarios.*,empresas.`nombrecorto`, empresas.`logo` AS logo, empresas.distanciacliente, empresas.ws, empresas.utiliza_impresora, empresas.validacion_inventario
		FROM usuarios
		INNER JOIN empresas ON usuarios.`empresa` = empresas.`idCliente`
		WHERE usuarios.STATUS = 1 AND usuarios.vendedor = 1 AND empresas.status = 1 AND usuarios.usuario = '$user' AND usuarios.clave = '$pass' AND usuarios.empresa = '$company' 
		LIMIT 1";
		$query = $this->db->query($consulta);
		return $query->result();
	}

	public function getusuarioreparto($user,$pass,$company)
	{
		$consulta="SELECT usuarios.*,empresas.`nombrecorto`, empresas.`logo` AS logo, empresas.distanciacliente, empresas.ws, empresas.utiliza_impresora,
		'800' AS limite_depositar
		FROM usuarios
		INNER JOIN empresas ON usuarios.`empresa` = empresas.`idCliente`
		WHERE usuarios.status = 1 AND usuarios.perfil = 5 AND usuarios.usuario = '$user' AND usuarios.clave = '$pass' AND usuarios.empresa = '$company'
		LIMIT 1";
		$query = $this->db->query($consulta);
		return $query->result();
	}

	public function UpdateCelular($id,$celular)
	{
		$this->db->where('id', $id);
		$this->db->update("usuarios", array("celular"=>$celular));
		/*if ($this->db->affected_rows() > 0) 
		{
		}*/
	}

	public function FreeCellphone($usuario)
	{
		$this->db->where('id', $usuario);
		$this->db->update("usuarios", array("celular"=>"0"));
		if ($this->db->affected_rows() > 0) 
		{
			return "si";
		}

		return "no";
	}	

	public function GetRuta($usuario,$empresa)
	{
		/*$consulta="SELECT cat_rutas.*
		FROM cat_rutas WHERE id = (SELECT ruta FROM usuarios WHERE id = $usuario) ";*/

		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT cat_rutas.*
		FROM cat_rutas WHERE id = '$usuario' ";
		$query = $this->dbinfo->query($consulta);
		return $query->row();
	}

	public function GetSucursal($idsucursal,$empresa)
	{
		/*$consulta="SELECT cat_rutas.*
		FROM cat_rutas WHERE id = (SELECT ruta FROM usuarios WHERE id = $usuario) ";*/

		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT cat_sucursales.*
		FROM cat_sucursales WHERE id = '$idsucursal' ";
		$query = $this->dbinfo->query($consulta);
		return $query->row();
	}

	public function GetZonas($rutas,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		//$consulta="SELECT * FROM cat_zonas";
		$consulta = "SELECT cat_zonas.*,
		IF(asi_ruta_zona.`ultima_actualizacion` > cat_zonas.`ultima_actualizacion`,asi_ruta_zona.`ultima_actualizacion`,cat_zonas.`ultima_actualizacion`) AS ultima_fecha,
		IF(asi_ruta_zona.`status`=1 AND cat_zonas.`status`=1,1,0) AS match_status
		FROM cat_zonas
		INNER JOIN asi_ruta_zona ON cat_zonas.`id` = asi_ruta_zona.`zona`
		WHERE asi_ruta_zona.`ruta` IN ($rutas)";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetClientes($zonas, $proveedores, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT DISTINCT clientes.`id` AS iddis, clientes.*,
		clientes.`ultima_actualizacion` AS ultima_fecha,
		clientes.`status` AS match_status
		FROM clientes
		WHERE clientes.`zona` IN ($zonas)";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetClienteById($pIdCliente, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT DISTINCT clientes.`id` AS iddis, clientes.*,
		clientes.`ultima_actualizacion` AS ultima_fecha,
		clientes.`status` AS match_status,
		(SELECT clave FROM cat_sucursales cs WHERE cs.`id` = clientes.sucursal) as codigosucursal,
		IFNULL((SELECT clasificacion FROM cat_clasificacion_cliente cs WHERE cs.`id` = clientes.clasificacion), 'NA') as clasificacioncliente
		FROM clientes
		WHERE clientes.`id` = '$pIdCliente'";
		$query = $this->dbinfo->query($consulta);
		return $query->row();
	}

	public function GetProveedores($rutas,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		//$consulta="SELECT * FROM cat_proveedor";
		$consulta = "SELECT cat_proveedor.*, 
		IF(asi_proveedor_ruta.`ultima_actualizacion` > cat_proveedor.`ultima_actualizacion`,asi_proveedor_ruta.`ultima_actualizacion`,cat_proveedor.`ultima_actualizacion`) AS ultima_fecha,
		IF(asi_proveedor_ruta.`status`=1 AND cat_proveedor.`status`=1,1,0) AS match_status
		FROM cat_proveedor
		INNER JOIN asi_proveedor_ruta ON cat_proveedor.`id` = asi_proveedor_ruta.`proveedor`
		AND asi_proveedor_ruta.`ruta` IN($rutas)";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetProductos($proveedores, $idsucursal, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		//$consulta="SELECT * FROM cat_productos";
		/*$consulta = "SELECT cat_productos.* 
		FROM cat_productos
		WHERE cat_productos.`proveedor` IN ($proveedores) AND cat_productos.status=1";*/
		/*$consulta = "SELECT cat_productos.* 
		FROM cat_productos
		WHERE tipo2 = 'PRODUCTO' AND cat_productos.`proveedor` IN ($proveedores) AND cat_productos.status=1
		UNION
		SELECT cat_productos.* 
		FROM cat_productos
		WHERE tipo2 = 'PAQUETE' AND FIND_IN_SET('$idsucursal', cat_productos.`sucursales`) AND cat_productos.status=1";*/

		$consulta = "SELECT cat_productos.*,
		0 AS vendidos,
		0 AS presupuesto,
		'TODOS' AS audiencia_clientes
		FROM cat_productos
		WHERE tipo2 = 'PRODUCTO' AND cat_productos.`proveedor` IN ($proveedores) AND cat_productos.status=1
		UNION
		SELECT p.*, 
		fnGetPaquetesVendidosBySucursal('$idsucursal', p.`id`) AS vendidos,
		fnGetPaquetesPresupuestoBySucursal('$idsucursal', p.`id`) AS presupuesto,
		IFNULL(IF(p.audiencia = 0, 'TODOS', (SELECT sub.`clientes` FROM paquetes_audiencia sub WHERE sub.`idpaquete` = p.id)), '') AS audiencia_clientes
		FROM cat_productos p
		WHERE p.tipo2 = 'PAQUETE' AND FIND_IN_SET('$idsucursal', p.sucursales) AND p.status=1 AND CURDATE() BETWEEN p.`fechainicio` AND p.fechafinal
		HAVING (SELECT presupuesto) > (SELECT vendidos);";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetClientesPaquetes($idruta, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT p.`id`, c.`codigo` AS codigocliente, c.`nombre`, pd.`codigoproducto`, pd.`producto` ,
		SUM(pd.`cantidad_entregado` - pd.`cantidad_rechazado`) AS cantidad_vendida,
		(SELECT sub.tipo2 FROM cat_productos sub WHERE sub.id = pd.`iditem`) AS tipo2
		FROM clientes c 
		INNER JOIN pedidos p ON c.id = p.`idcliente`
		INNER JOIN pedidos_detalle pd ON p.`id` = pd.`idpedido`
		WHERE p.ruta = '$idruta' AND c.`status` = 1 AND p.`status` = 1 AND p.`fecha` BETWEEN DATE_FORMAT(CURDATE(),'%Y-%m-01') AND DATE_FORMAT(LAST_DAY(CURDATE()),'%Y-%m-%d')
		GROUP BY p.`idcliente`, pd.`iditem`
		HAVING tipo2 = 'PAQUETE' AND cantidad_vendida > 0
		ORDER BY c.`nombre` , pd.`producto`;";
		
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetComponentesPaquete($empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$query = $this->dbinfo->query("SELECT * FROM componentes_paquete");
		return $query->result();
	}

	public function GetClasificacionProductos($empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT * FROM cat_clasificacionproductos";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetClasificacionClientes($empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT * FROM cat_clasificacion_cliente";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetProductosUltimos($empresa, $idruta)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT p.`ruta`, p.`idsucursal`, p.`idcliente`, p.`cliente`, cp.`id` AS idproducto, cp.`codigo`, cp.`nombre` AS producto, cp.`sucursales`
		FROM pedidos p
		INNER JOIN pedidos_detalle pd ON p.`id` = pd.`idpedido`
		INNER JOIN cat_productos cp ON pd.`iditem` = cp.`id` AND cp.`status` = 1
		WHERE 
		p.`fecha` BETWEEN DATE_ADD(CURDATE(), INTERVAL -2 MONTH) AND CURDATE() AND 
		(cp.`sucursales` = 0 OR FIND_IN_SET(p.`idsucursal`, cp.`sucursales`)) AND
		p.`idcliente` 
		IN
		(
			SELECT c.id FROM clientes c WHERE c.zona IN
			(
				SELECT zona FROM asi_ruta_zona arz WHERE arz.`ruta` = $idruta
			) 
			AND c.`status` = 1
		)
		GROUP BY p.`idcliente`, pd.`iditem`
		ORDER BY p.`idcliente`, pd.`producto`";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetOrdersToday($idusuario, $empresa){

		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT * FROM pedidos WHERE DATE(fechacreacion) = CURDATE() AND idusuario = '$idusuario' AND status=1";		
		$query = $this->dbinfo->query($consulta);
		$pedidos = $query->result_array();

		foreach($pedidos as $key => $item){

			$query = $this->dbinfo->query("SELECT * FROM pedidos_detalle WHERE idpedido = '$item[id]'");
			$detalle = $query->result();

			$pedidos[$key]["detalle"] = $detalle;
		}

		return $pedidos;
	}

	public function GetVisitsToday($idusuario,$empresa){

		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT * FROM visitas WHERE DATE(fechacreacion) = CURDATE() AND idusuario = '$idusuario' AND status=1";
		$query = $this->dbinfo->query($consulta);
		return $query->result();	
	}

	public function GetPrinter($usuario,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT * FROM printers WHERE idusuario = '$usuario' ";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	/*public function GetRutas($usuario)
	{
		$consulta="SELECT cat_rutas.*,asi_usuario_ruta.`status` AS match_status
		FROM cat_rutas
		INNER JOIN asi_usuario_ruta ON cat_rutas.`id` = asi_usuario_ruta.`ruta`
		WHERE asi_usuario_ruta.`usuario` = '$usuario'";
		$query = $this->db->query($consulta);
		return $query->result();
	}*/

	public function GetOrdersById($idorder,$empresa){
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT * FROM pedidos WHERE id = '$idorder'";
		$query = $this->dbinfo->query($consulta);
		$pedidos = $query->result_array();

		foreach($pedidos as $key => $item){

			$query = $this->dbinfo->query("SELECT * FROM pedidos_detalle WHERE idpedido = '$item[id]'");
			$detalle = $query->result();

			$pedidos[$key]["detalle"] = $detalle;
		}

		return $pedidos;
	}

	/*public function getusuario($user,$pass)
	{
		$consulta="SELECT * FROM usuarios WHERE STATUS = 1 AND vendedor = 1 AND usuario = '$user' AND clave = '$pass' LIMIT 1";
		$query = $this->db->query($consulta);
		return $query->result();
	}*/

	public function GetTimeline($date,$user,$empresa){
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT id AS idservidor,tipo,codigocliente,cliente,total,TIME_FORMAT(fechacreacion,'%H:%i') AS hora,fechacreacion,folio
		FROM pedidos
		WHERE idusuario = '$user' AND DATE(fechacreacion) = '$date' AND status=1
		UNION
		SELECT id AS idservidor,'VISITA' AS tipo,codigocliente,cliente,resultado AS total,
		CONCAT(TIME_FORMAT(inicio,'%H:%i'),' - ',TIME_FORMAT(fin,'%H:%i'), ' hrs (',TIMESTAMPDIFF(MINUTE, inicio, fin),' minutos)') AS hora,
		fechacreacion,'' AS folio
		FROM visitas
		WHERE idusuario = '$user' AND DATE(fechacreacion) = '$date' AND status=1
		ORDER BY fechacreacion DESC";
		$query = $this->dbinfo->query($consulta);
		return $query->result();	
	}

	public function GetPromociones($idsucursal,$user,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT promocion.`id`, promocion.`codigo`, promocion.`tipo`, promocion_detalle.`codigoproducto`, promocion_detalle.`idproducto`, promocion_detalle.`condicion`, promocion_detalle.`promocion`, promocion.`status`, promocion.`ultima_actualizacion`
		FROM promocion 
		INNER JOIN promocion_detalle ON promocion.`id` = promocion_detalle.`idpromocion`
		WHERE promocion.`status` = 1 AND FIND_IN_SET($idsucursal, promocion.`sucursales`)";

		$query = $this->dbinfo->query($consulta);
		return $query->result();	
	}

	public function CountRows($SQL, $empresa){
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		return $this->dbinfo->query($SQL)->row()->registros;
	}	

	public function InsertClient($cliente,$usuario,$proveedor,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$res = array("res"=>"no","codigo"=>"","idservidor"=>"0");

		$cliente = json_decode($cliente, true);

		$SQL_CHECK = "SELECT COUNT(*) AS registros FROM clientes WHERE nombre = '".addcslashes($cliente["nombre"],"'")."' AND ultima_actualizacion = '$cliente[ultima_actualizacion]'";
		if($this->CountRows($SQL_CHECK, $empresa)>0)
		{
			$infocliente = $this->dbinfo->query("SELECT * FROM clientes WHERE nombre = '".addcslashes($cliente["nombre"],"'")."' AND ultima_actualizacion = '$cliente[ultima_actualizacion]'");

			if($infocliente->num_rows() > 0)
			{
				$res["res"] = "si";
				$res["idservidor"] = $infocliente->row()->id;
				$res["codigo"] = $infocliente->row()->codigo;
			}
			
			return $res;
		}

		$idcliente = $cliente["idservidor"];

		unset($cliente["id"]);
		unset($cliente["subido"]);		
		unset($cliente["idservidor"]);
		unset($cliente["visitado"]);
		unset($cliente["actualizadopor"]);
		unset($cliente["creadopor"]);

		if($cliente["diasvisita"] != "")
		{
			$diasvisita = explode(",", $cliente["diasvisita"]);

			$diasentrega = "";
			foreach($diasvisita as $item)
			{
				if($item == "7")
				{
					$diasentrega = $diasentrega."2,";
				}
				else
				{
					$diasentrega = $diasentrega .(((int)$item) + 1) . ",";
				}
			}

			$diasentrega = rtrim($diasentrega, ",");
			$cliente["diasentrega"] = $diasentrega;
		}

		$cliente["subidobees"] = "0";
		$cliente["idusuariocrea"] = $usuario;		

		if($idcliente == "0")
		{
			$cliente["codigo"] = $this->getClaveNewCliente($cliente["sucursal"],$empresa);			

			$this->dbinfo->insert("clientes", $cliente);
			if ($this->dbinfo->affected_rows() > 0) 
			{
				$id = $this->dbinfo->insert_id();

				$res["res"] = "si";
				$res["idservidor"] = $id;
				$res["codigo"] = $cliente["codigo"];

				$proveedoresA=explode(",", $proveedor);
				$cuantosProv=count($proveedoresA);
				
				for ($i=0; $i < $cuantosProv; $i++)
				{
					$this->dbinfo->insert('asi_cliente_proveedor', array('cliente'=>$id, 'proveedor'=>$proveedoresA[$i], 'status'=>1, 'creadopor'=>$usuario, 'ultima_actualizacion'=>$cliente["ultima_actualizacion"]));
				}

				return $res;
			}			
		}
		else
		{
			$cliente["idusuarioactualiza"] = $usuario;
			
			$this->dbinfo->where('id', $idcliente);
			$this->dbinfo->update("clientes", $cliente);
			if ($this->dbinfo->affected_rows() > 0) 
			{				
				$res["res"] = "si";
				$res["idservidor"] = $idcliente;
				$res["codigo"] = $cliente["codigo"];

				return $res;
			}
		}

		return $res;
	}	

	public function InsertVisits($visitas, $empresa){
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

	 	$res = array("res"=>"no","idservidor"=>"0");

		$visitas = json_decode($visitas,true);

		$SQL_CHECK = "SELECT COUNT(*) AS registros FROM visitas WHERE codigocliente = '$visitas[codigocliente]' AND fechacreacion = '$visitas[fechacreacion]'";
		if($this->CountRows($SQL_CHECK, $empresa)>0){
			return $res;
		}
		
		$idservidor = $visitas["idservidor"];

		unset($visitas["id"]);
		unset($visitas["idclientelocal"]);		
		unset($visitas["idservidor"]);		
		unset($visitas["status"]);
		unset($visitas["subido"]);

		$visitas["ruta"] = $this->GetRutaUsuario($visitas["idusuario"], $empresa);
		$visitas["idsucursal"] = $this->GetSucursalUsuario($visitas["idusuario"]);

		if($idservidor == "0"){			
			$this->dbinfo->insert("visitas", $visitas);
			if($this->dbinfo->affected_rows() > 0) 
			{
				$id = $this->dbinfo->insert_id();

				$res["res"] = "si";
				$res["idservidor"] = $id;

				$this->dbinfo->query("UPDATE visitas SET diasvisitas = (SELECT clientes.`diasvisita` FROM clientes WHERE id = visitas.`idcliente`) WHERE ISNULL(diasvisitas)", false);

				return $res;
			}
		}
		else
		{
			$this->dbinfo->where('id', $idservidor);
			$this->dbinfo->update("visitas", $visitas);
			if ($this->dbinfo->affected_rows() > 0) 
			{				
				$res["res"] = "si";
				$res["idservidor"] = $idservidor;

				return $res;
			}
		}

		return $res;
	}

	public function GetFolioPedido($usuario,$idsucursal,$empresa){
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		//$query = $this->dbinfo->query("SELECT * FROM cat_sucursales WHERE id = $idsucursal");
		$sucursal = "TODAS";//$query->row()->clave;

		$folio = array("sucursal"=>$sucursal,"usuario"=>$usuario,"idsucursal"=>$idsucursal);
		$this->dbinfo->set('fecha', 'CURRENT_DATE()', FALSE);
		$this->dbinfo->insert("pedidos_folios", $folio);

		$id = $this->dbinfo->insert_id();

		//$query = $this->dbinfo->query("SELECT *,CONCAT(sucursal,DATE_FORMAT(fecha, '%y%m%d'),id) AS consecutivo FROM pedidos_folios WHERE id = '$id' AND fecha=CURDATE() AND sucursal='$sucursal' ");
		$query = $this->dbinfo->query("SELECT *,CONCAT(DATE_FORMAT(fecha, '%y%m%d'),id) AS consecutivo FROM pedidos_folios WHERE id = '$id' AND fecha=CURDATE() AND sucursal='$sucursal' ");
		return $query->row()->consecutivo;
	}

	public function InsertOrders($pedido,$pedido_detalle,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$res = array("res"=>"no","folio"=>"","idservidor"=>"0");

		$pedido = json_decode($pedido,true);

		if($pedido["tipo"] == "0")
		{
			$pedido["tipo"] = "PREVENTA";
		}

		//$info_sucursal = $this->dbinfo->query("SELECT * FROM cat_sucursales WHERE id = '$pedido[idsucursal]'");
		$info_cierre = $this->dbinfo->query("SELECT * FROM cat_rutas WHERE agente = '$pedido[idusuario]'");
		if($info_cierre->num_rows() > 0)
		{
			$fecha_corte = $info_cierre->row()->fecha_cierre_armado_ruta;
			if(is_null($fecha_corte) || $fecha_corte == "")
			{

			}
			else
			{
				$dt = new DateTime($pedido["fechacreacion"]);
				$fechacreacion = $dt->format('Y-m-d');

				if($pedido["idsucursal"] == "16")
				{
					$info_dia_mxl = $this->dbinfo->query("SELECT fnGetDiaProcesoMxl('$fechacreacion') AS dia_mxl;")->row();
					$fechacreacion = $info_dia_mxl->dia_mxl;
				}

				if($fecha_corte == $fechacreacion)
				{
					return $res;
				}
			}
		}
		
		$idservidor = $pedido["idservidor"];

		unset($pedido["id"]);
		unset($pedido["clientelocal"]);		
		unset($pedido["idservidor"]);
		//unset($pedido["status"]);
		unset($pedido["subido"]);

		if(isset($pedido["info_facturar_subir"]))
		{
			unset($pedido["info_facturar_subir"]);
			/*unset($pedido["corte_factura"]);
			unset($pedido["corte_metodopago"]);
			unset($pedido["corte_condicionpago"]);*/
		}

		$pedido["ruta"] = $this->GetRutaUsuario($pedido["idusuario"], $empresa);

		$infocliente = $this->dbinfo->query("SELECT * FROM clientes WHERE id = '$pedido[idcliente]';");

		if($infocliente->num_rows() > 0)
		{
			$infocliente = $infocliente->row();

			$pedido["corte_factura"] = $infocliente->codigo_adminpaq=="" ? 0 : 1;
			$pedido["corte_metodopago"] = $infocliente->metodopago;
			$pedido["corte_condicionpago"] = $infocliente->condicionpago;
		}
		else
		{
			$pedido["corte_factura"] = 0;
			$pedido["corte_metodopago"] = "";
			$pedido["corte_condicionpago"] = "";
		}

		
		$pedido["foliobees"] = "0";
		$pedido["canal"] = "NON-BEES";
		$pedido["origen"] = "INROUTE";
		$pedido["estatusbees"] = "DELIVERED";

		if($idservidor == "0")
		{
			$pedido["folio"] = GETFOLIOPEDIDO($pedido["usuario"],$pedido["idsucursal"],$empresa);			

			if($pedido["idsucursal"] == "16")
			{
				$this->dbinfo->set('fecha', 'fnGetDiaProcesoMxl(CURRENT_DATE())', FALSE);
			}
			else
			{
				$this->dbinfo->set('fecha', 'CURRENT_DATE()', FALSE);
			}

			$this->dbinfo->insert("pedidos", $pedido);
			if ($this->dbinfo->affected_rows() > 0) 
			{
				$id = $this->dbinfo->insert_id();

				$res["res"] = "si";
				$res["idservidor"] = $id;
				$res["folio"] = $pedido["folio"];

				$pedido_detalle = json_decode($pedido_detalle, TRUE);

				$idsucursal = $pedido["idsucursal"];
	 
				foreach ($pedido_detalle as $key => $subArr)
				{
					$info_producto = $this->dbinfo->query("SELECT * FROM cat_productos WHERE id = '$subArr[idproductoservidor]'");

					$idproducto = $subArr["idproductoservidor"];
					$cantidad = $subArr["cantidad"];

			 		$subArr["idpedido"] = $id;
			 		$subArr["iditem"] = $subArr["idproductoservidor"];
					$subArr["idproveedor"] = $subArr["idproveedorservidor"];

					$subArr["cantidadoriginal"] = $cantidad;

					if($info_producto->row()->tipo2 == "PAQUETE")
					{
						$subArr["cantidad_entregado"] = $this->dbinfo->query("SELECT fnGetPaquetesRealVender('$idsucursal', '$idproducto', $cantidad) AS cantidad;")->row()->cantidad;
						$subArr["cantidad"] = $subArr["cantidad_entregado"];
					}
					else
					{
						$subArr["cantidad_entregado"] = $cantidad;
					}

					$subArr["cantidad_rechazado"] = "0";

					if($info_producto->num_rows() > 0)
					{
						$costo = $info_producto->row()->costo;
						$iva = $info_producto->row()->iva;
						$ieps = $info_producto->row()->ieps;

						$subArr["costo"] = $costo;
						$subArr["iva"] = $iva;
						$subArr["ieps"] = $ieps;
					}

				    unset($subArr["id"]);
				    unset($subArr["idorderlocal"]);
				    unset($subArr["status"]);
				    unset($subArr["subido"]);
				    unset($subArr["idproductoservidor"]);
				    unset($subArr["idproveedorservidor"]);

				    $pedido_detalle[$key] = $subArr;  
				}

				$this->dbinfo->insert_batch('pedidos_detalle', $pedido_detalle);

				return $res;
			}			
		}
		else
		{
			$this->dbinfo->where('id', $idservidor);
			$this->dbinfo->update("pedidos", $pedido);
			if ($this->dbinfo->affected_rows() > 0) 
			{				
				$res["res"] = "si";
				$res["idservidor"] = $idservidor;
				$res["folio"] = $pedido["folio"];

				return $res;
			}
		}

		return $res;
	}

	public	function UpdateOrderCambioFacturar($datos)
	{
		$config_app = switch_db_dinamico($datos["empresa"], 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$res = "no";

		$this->dbinfo->where('id', $datos["idpedido"]);
		$this->dbinfo->update("pedidos", 
			array(
				"corte_factura" => $datos["corte_factura"], 
				"corte_metodopago" => $datos["corte_metodopago"], 
				"corte_condicionpago" => $datos["corte_condicionpago"] 
			) 
		);

		if($this->dbinfo->affected_rows() > 0)
		{
			$res = "si";
		}

		return $res;
	}

	public	function InsertPrint($print,$idusuario,$usuario,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$res = "no";
		$print = json_decode($print,true);

		$SQL_CHECK = "SELECT COUNT(*) AS registros FROM printers WHERE idusuario = '$idusuario' ";
		if($this->CountRows($SQL_CHECK,$empresa)==0){
			$this->dbinfo->insert("printers", array("nombre"=>$print["nombre"], "mac"=>$print["mac"], "modelo"=>$print["modelo"], "idusuario"=>$idusuario, "usuario"=>$usuario ) );

			if ($this->dbinfo->affected_rows() > 0)
				$res = "si";
		}else{
			$this->dbinfo->where('idusuario', $idusuario);
			$this->dbinfo->update("printers", array("nombre"=>$print["nombre"], "mac"=>$print["mac"], "modelo"=>$print["modelo"], "idusuario"=>$idusuario, "usuario"=>$usuario ) );

			if($this->dbinfo->affected_rows() > 0)
				$res = "si";
		}

		return $res;
	}

	public function GetRutaUsuario($usuario,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta="SELECT * FROM cat_rutas WHERE chofer = '$usuario' LIMIT 1";
		$query = $this->dbinfo->query($consulta);
		if($query->num_rows()>0)
			return $query->row()->id;
		else
			return 0;
	}

	public function GetSucursalUsuario($usuario)
	{
		$consulta="SELECT * FROM usuarios WHERE id = '$usuario' LIMIT 1";
		$query = $this->db->query($consulta);
		if($query->num_rows()>0)
			return $query->row()->sucursal;
		else
			return 0;
	}

	public function GetReporteMensualCategorias($month,$iduser,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		if($empresa == '01220601')
		{
			$query = $this->dbinfo->query("SELECT ac.*, ((importe/objetivo) * 100) AS alcance,
			(SELECT SUM(ac2.importe) FROM acumulados_categorias ac2 WHERE ac2.periodo = ac.periodo AND ac2.idvendedor = ac.idvendedor) AS venta_global,
			(SELECT SUM(tc.pago) FROM tabulador_categorias tc WHERE tc.idsucursal = 1) AS pago_total_categorias,
			((importe/diasTranscurridos)*(diasMes)) AS venta2, ( (((SELECT venta2)) / objetivo) * 100 ) AS alcance2, 
			(objetivo - importe) AS gap, COALESCE( (SELECT gap) / (diasMes-diasTranscurridos), 0) AS objetivo_diario, 
			CONCAT('$', FORMAT(objetivo, 2)) AS objetivo_format, CONCAT('$', FORMAT(importe, 2)) AS importe_format, 
			CONCAT(TRUNCATE((SELECT alcance), 2), '%') AS alcance_format, CONCAT('$', FORMAT((SELECT venta2), 2)) AS venta2_format, 
			CONCAT(TRUNCATE((SELECT alcance2), 2), '%') AS alcance2_format, CONCAT('$', FORMAT((SELECT gap), 2)) AS gap_format, 
			CONCAT('$', FORMAT((SELECT objetivo_diario), 2)) AS objetivo_diario_format, FORMAT((ventas/diasTranscurridos), 0) AS promedio_ventas, 
			'0' AS pago_pedidos, 
			(SELECT sucursal FROM cat_rutas WHERE cat_rutas.`chofer` = idVendedor) AS idsucursal_vendedor, 
			COALESCE((SELECT pago FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = ac.`categoria`), 0) AS tabulador_pedido, 
			COALESCE((SELECT minimo FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = ac.`categoria`), 0) AS tabulador_pedido_minimo, 
			COALESCE((SELECT maximo FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = ac.`categoria`), 0) AS tabulador_pedido_maximo,  
			ROUND(IF((SELECT alcance2) >= ac.restriccion, (((SELECT tabulador_pedido) * (SELECT alcance2)) / 100), 0), 2) AS pago_categoria,
			TRUNCATE(((SELECT venta_global)/ac.`ventas`),0) AS dropsize
			FROM `acumulados_categorias` ac
			WHERE periodo = CONCAT(YEAR(CURDATE()), '$month') AND idVendedor = '$iduser'
			ORDER BY `categoria`");

			$query = $query->result();
		}
		else
		{
			$query = $this->dbinfo->query("SELECT ac.*, ((importe/objetivo) * 100) AS alcance,
			(SELECT SUM(ac2.importe) FROM acumulados_categorias ac2 WHERE ac2.periodo = ac.periodo AND ac2.idvendedor = ac.idvendedor) AS venta_global,
			(SELECT SUM(tc.pago) FROM tabulador_categorias tc WHERE tc.idsucursal = 1) AS pago_total_categorias,
			((importe/diasTranscurridos)*(diasMes)) AS venta2, ( (((SELECT venta2)) / objetivo) * 100 ) AS alcance2, 
			(objetivo - importe) AS gap, COALESCE( (SELECT gap) / (diasMes-diasTranscurridos), 0) AS objetivo_diario, 
			CONCAT('$', FORMAT(objetivo, 2)) AS objetivo_format, CONCAT('$', FORMAT(importe, 2)) AS importe_format, 
			CONCAT(TRUNCATE((SELECT alcance), 2), '%') AS alcance_format, CONCAT('$', FORMAT((SELECT venta2), 2)) AS venta2_format, 
			CONCAT(TRUNCATE((SELECT alcance2), 2), '%') AS alcance2_format, CONCAT('$', FORMAT((SELECT gap), 2)) AS gap_format, 
			CONCAT('$', FORMAT((SELECT objetivo_diario), 2)) AS objetivo_diario_format, FORMAT((ventas/diasTranscurridos), 0) AS promedio_ventas, 
			'0' AS pago_pedidos, 
			(SELECT sucursal FROM cat_rutas WHERE cat_rutas.`chofer` = idVendedor) AS idsucursal_vendedor, 
			COALESCE((SELECT pago FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = ac.`categoria`), 0) AS tabulador_pedido, 
			COALESCE((SELECT minimo FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = ac.`categoria`), 0) AS tabulador_pedido_minimo, 
			COALESCE((SELECT maximo FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = ac.`categoria`), 0) AS tabulador_pedido_maximo,  
			COALESCE( 
			IF( (SELECT alcance2) < (SELECT tabulador_pedido_minimo), 0, IF((SELECT alcance2) > (SELECT tabulador_pedido_maximo), (SELECT tabulador_pedido_maximo), (SELECT alcance2)
			)
			), 0) AS porcentaje_categoria_pago, 
			COALESCE( CONCAT('$', FORMAT((SELECT tabulador_pedido)*( (SELECT porcentaje_categoria_pago) / 100 ), 2)), 0) AS pago_categoria,
			TRUNCATE(((SELECT venta_global)/ac.`ventas`),0) AS dropsize
			FROM `acumulados_categorias` ac
			WHERE periodo = CONCAT(YEAR(CURDATE()), '$month') AND idVendedor = '$iduser'
			ORDER BY `categoria`");

			$query = $query->result();

			foreach($query as $key => $value)
			{
				$query[$key]->incentivo_pedidos = $this->getIncentivoPedidos($value->dropsize, $value->promedio_ventas, $empresa);
			}
		}		

		//die($this->dbinfo->last_query());		

		return $query;
	}

	public function getIncentivoPedidos($pDropsize, $pPropedidos, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$vRes = 0;
		if($pPropedidos < 29)
		{
			return 0;
		}
		
		if($pPropedidos > 44)
		{
			$pPropedidos = 44;
		}
		
		if($pDropsize < 0)
		{
			$pDropsize = 0;
		}
		else if($pDropsize > 310)
		{
			$pDropsize = 310;
		}
		else
		{
			$info_tabulador = $this->dbinfo->query("SELECT * FROM tabulador_pedidos WHERE dropsize <= $pDropsize ORDER BY dropsize DESC LIMIT 1")->row();
			$pDropsize = $info_tabulador->dropsize;
		}

		$info_tabulador = $this->dbinfo->query("SELECT tabulador_pedidos.$pPropedidos AS vRes FROM tabulador_pedidos WHERE dropsize = $pDropsize")->row();
		return $info_tabulador->vRes;
	}

	//esta funcion es paara sacar el codigo del cliente es la misma que se utilza en CatalogosModel, cualquier correcion en 
	//una de ellos se debe hacer en las dos
	public function getClaveNewCliente($idSucursal,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);		

		date_default_timezone_set('America/Mazatlan');
		$fecha1 = date('Y-m-d');
		$fecha2 = date('y-m-d');

		$consulta="SELECT cat_sucursales.clave, conf_consecutivos.consecutivo, conf_consecutivos.dia, conf_consecutivos.id AS idConf 
		FROM cat_sucursales 
		INNER JOIN conf_consecutivos ON cat_sucursales.id=conf_consecutivos.sucursal WHERE cat_sucursales.id = '$idSucursal'";

		$query = $this->dbinfo->query($consulta)->row();
		$idConf = $query->idConf;
		$fecha = $query->dia;

		if($fecha!=$fecha1)
		{
			$actualiza="UPDATE conf_consecutivos SET consecutivo=2, dia='$fecha1' WHERE id = '$idConf'";
			$this->dbinfo->query($actualiza);
			$consecutivo=1;
		}
		else
		{
			$consecutivo=$query->consecutivo;
			$actualiza = "UPDATE conf_consecutivos SET consecutivo=$consecutivo+1, dia='$fecha1' WHERE id = '$idConf'";
			$this->dbinfo->query($actualiza);
		}

		$cadena = str_pad($consecutivo, 4, '0', STR_PAD_LEFT);
			
		$sucursal = $query->clave;
		$fechaT = str_replace("-", "", $fecha2);
		return $sucursal.$fechaT.$cadena;
	}

	//##################### REPARTO ###################################################

	public function InsertDescargaPedidos($datos, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$rutas_asignadas = $this->GetRutasAsignadas($empresa, $datos["idusuario"]);

		$query = $this->dbinfo->query("SELECT * FROM reparto_descarga_pedidos WHERE idusuario = '$datos[idusuario]' AND fecha = '$datos[fecha_descarga]'");

		if($query->num_rows() > 0)
		{
			$id = $query->row()->id;
			$num_pedidos = $query->row()->pedidos;

			if($datos["pedidos"] > $num_pedidos)
			{
				$this->dbinfo->where("id", $id);
				$this->dbinfo->update("reparto_descarga_pedidos", 
					array(
						"idusuario" => $datos["idusuario"],
						"rutas" => $rutas_asignadas,
						"idsucursal" => $datos["idsucursal"], 
						"pedidos" => $datos["pedidos"],
						"fecha" => $datos["fecha_descarga"], 
						"hora" => $datos["hora"]
					)
				);
			}
		}
		else
		{
			$this->dbinfo->insert("reparto_descarga_pedidos",
				array(
					"idusuario" => $datos["idusuario"],
					"rutas" => $rutas_asignadas,
					"idsucursal" => $datos["idsucursal"],
					"pedidos" => $datos["pedidos"],
					"fecha" => $datos["fecha_descarga"], 
					"hora" => $datos["hora"]
				)
			);

			$id = $this->dbinfo->insert_id();
		}

		return $id;
	}

	public function GetRutasAsignadas($empresa, $idusuario)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$query = $this->dbinfo->query("SELECT COALESCE(GROUP_CONCAT(arr.`idruta`), '') AS rutas FROM asi_reparto_rutas arr WHERE arr.`status` = 1 AND arr.`idusuario` = '$idusuario'");

		return $query->row()->rutas;
	}

	public function GetPedidosEntregaUsuario($empresa, $datos)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$rutas_asignadas = $this->GetRutasAsignadas($empresa, $datos["idusuario"]);

		$consulta = "SELECT *,
		(SELECT c.telefono FROM clientes c WHERE c.id = p.`idcliente`) AS telefono_cliente,
		(SELECT CONCAT(c.calle, ' ', c.numero, ', ', c.colonia, ', ', c.ciudad, ',', c.estado, ', C.P. ', c.cp) FROM clientes c WHERE c.id = p.`idcliente`) AS direccion_cliente,
		(SELECT SUM((pd.cantidad_entregado - pd.cantidad_rechazado) * pd.precio) FROM pedidos_detalle pd WHERE p.`id` = pd.idpedido AND pd.status = 1) AS total_real
		FROM pedidos p 
		WHERE ISNULL(p.entregado_estatus) AND p.status = 1 AND p.fecha = '$datos[fecha_descarga]' AND FIND_IN_SET(p.ruta, '$rutas_asignadas')";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetPedidosDetalleEntregaUsuario($empresa, $datos)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$rutas_asignadas = $this->GetRutasAsignadas($empresa, $datos["idusuario"]);

		$consulta = "SELECT p.`folio`, pd.*,
		(pd.cantidad_entregado - pd.cantidad_rechazado) AS cantidad_real,
		((SELECT cantidad_real) * pd.precio) AS importe_real
		FROM pedidos p
		INNER JOIN pedidos_detalle pd ON p.`id` = pd.`idpedido`
		WHERE ISNULL(p.entregado_estatus) AND p.`status` = 1 AND pd.`status` = 1 AND p.fecha = '$datos[fecha_descarga]' AND FIND_IN_SET(p.ruta, '$rutas_asignadas')";

		$query = $this->dbinfo->query($consulta);
		return $query->result();	
	}

	public function GetEstatusReparto($empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$query = $this->dbinfo->query("SELECT * FROM cat_estatus_reparto WHERE STATUS = 1 ORDER BY descripcion");
		return $query->result();
	}

	public function UploadEntrega($datos)
	{
		$config_app = switch_db_dinamico($datos["empresa"], 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$res = array("res"=>"no");

		unset($datos["empresa"]);

		$this->dbinfo->insert("reparto_entregas_historial", $datos);

		$this->dbinfo->where('id', $datos["idpedido"]);
		$this->dbinfo->update("pedidos", array("entregado_estatus" => $datos["idestatus"], "entregado_usuario" => $datos["idusuario"], "entregado_fecha" => $datos["fecha"].' '.$datos["hora"]));
		if ($this->dbinfo->affected_rows() > 0) 
		{				
			$res["res"] = "si";

			return $res;
		}

		return $res;
	}

	public function UploadPedidosIdDescargaReparto($datos)
	{
		$config_app = switch_db_dinamico($datos["empresa"], 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$this->dbinfo->where_in('id', explode(',', $datos["ids_pedidos"]));
		$this->dbinfo->update("pedidos", array("id_reparto_descarga" => $datos["id_reparto_descarga"]));
	}

	public function UploadRechazos($empresa, $idusuario, $pedidos_detalle)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$res = array("res"=>"no", "mensaje" => "Ocurrio un error");

		$fecha = GETFECHA();
		$rutas_asignadas = $this->GetRutasAsignadas($empresa, $idusuario);

		$cortes = $this->dbinfo->query("SELECT * FROM cortes c WHERE fecha = '$fecha' AND FIND_IN_SET(c.`idruta`, '$rutas_asignadas')");

		if($cortes->num_rows() > 0)
		{
			$res["res"] = "no";
			$res["mensaje"] = "No se realizó la sincronización. Ya se realizó el corte";

			return $res;	
		}

		$pedidos_detalle = json_decode($pedidos_detalle, TRUE);
	 
		foreach ($pedidos_detalle as $key => $subArr)
		{
			$this->dbinfo->where('id', $subArr["idservidor"]);
			$this->dbinfo->update("pedidos_detalle", array("cantidad_rechazado" => $subArr["cantidad_rechazada"]));
		}

		$res["res"] = "si";
		$res["mensaje"] = "Sincrionización realizada con exito";

		return $res;
	}

	public function UploadDepositoReparto($datos)
	{
		$config_app = switch_db_dinamico($datos["empresa"], 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$res = array("res"=>"no");

		unset($datos["empresa"]);

		$this->dbinfo->insert("reparto_depositos", $datos);

		$id = $this->dbinfo->insert_id();

		if ($id > 0) 
		{				
			$res["res"] = "si";

			return $res;
		}

		return $res;
	}

	public function GetInventarioReal($empresa, $idsucursal)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$query = $this->dbinfo->query("SELECT '$idsucursal' AS idsucursal, cp.id AS idproducto, cp.`codigo` COLLATE utf8_unicode_ci AS codigo, cp.`nombre` COLLATE utf8_unicode_ci AS nombre, 0.0 AS cantidad, fnGetPaquetesDisponibles(cp.id, '$idsucursal') AS cantidaddisponible
		FROM cat_productos cp
		WHERE cp.tipo2 = 'PAQUETE' AND cp.status = 1 AND FIND_IN_SET('$idsucursal', cp.sucursales)
		UNION
		SELECT idsucursal, idproducto, codigo, nombre, IF(cantidad < 0, 0 , cantidad) AS cantidad, cantidaddisponible 
		FROM inventario_real 
		WHERE idsucursal = '$idsucursal'");
		return $query->result();
	}
}
?>