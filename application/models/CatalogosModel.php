<?php
class CatalogosModel extends CI_Model {
	
	private $dbinfo;
	private $config_app;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		if($this->session->has_userdata('movil'))
		{
			$this->config_app = switch_db_dinamico(GETEMPRESA(), 1);
		}
		else
		{
			$this->config_app = switch_db_dinamico(GETEMPRESA());
		}
		
		$this->dbinfo = $this->load->database($this->config_app, TRUE);
	}

	protected function getDBEmpresa($empresa)
	{
		if ($this->dbinfo === null) {
			$this->dbinfo = $this->load->database($empresa, TRUE);
		}
		return $this->dbinfo;
	}

	public function cuantosClientes()
	{
		$db = $this->getDBEmpresa($this->config_app);

		$consulta = "SELECT COUNT(id) AS cuantosClientes FROM clientes WHERE status=1";
		$query = $db->query($consulta);
		return $query;
	}

	public function cuantasRutas()
	{
		$db = $this->getDBEmpresa($this->config_app);

		$consulta = "SELECT COUNT(id) AS cuantasRutas FROM cat_rutas WHERE status=1";
		$query = $db->query($consulta);
		return $query;
	}

	public function getListaUsuarios($post)
	{
		$empresa = GETEMPRESA();

		if($post["idsucursal"]==0) $wheresucursal = "";
		else $wheresucursal = "AND sucursal = '$post[idsucursal]'";

		$wherein = "";

		if($post["vista"] == "Usuarios")
		{
			$wherein = '1,2,3,4,7,8,9,10,11,12,13,14,15,16,17';
		}
		else if($post["vista"] == "Vendedores")
		{
			$wherein = '5';
		}
		else if($post["vista"] == "Repartidores")
		{
			$wherein = '6';
		}

		$consulta = "SELECT *,
		fnGetPerfilById(usuarios.perfil) as perfil_nombre,
		IF(vendedor=1,'SI', 'NO') as ventas,
		IF( (celular='0' or isnull(celular)),'NO', 'SI') as celular_activo,
		IF(status=1,'SI', 'NO') as status2
		FROM usuarios
		WHERE empresa = '$empresa' AND perfil IN($wherein) ".$wheresucursal;

		$query = $this->db->query($consulta)->result_array();

		foreach ($query as $key => $value) 
		{
			$sucursal = "";
			$datossucursal = $this->getDatosSucursal($value["sucursal"]);

			if( $datossucursal->num_rows() > 0 )
			{
				$sucursal = $datossucursal->row()->sucursal;
			}

			$query[$key]["sucursal_nombre"] = $sucursal;
		}

		return $query;
	}

	public function getNombreSucursal($id)
	{
		$consulta = "SELECT sucursal FROM cat_sucursales WHERE id=$id";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListaSucursales()
	{
		$consulta = "SELECT id,sucursal FROM cat_sucursales WHERE status=1 ORDER BY sucursal ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListaAvatares()
	{
		$consulta = "SELECT id,avatar FROM avatar WHERE status=1";
		$query = $this->db->query($consulta);
		return $query;
	}

	public function saveNewUsuario($datos)
	{
		$id = $datos["id"];
		unset($datos["id"]);

		$existe = $this->getUsuarioByUsuario($datos["usuario"], GETEMPRESA())->row();
		if( isset($existe) ){
			if($id==0){
				return "existe";
			}else{
				if($existe->id!=$id){
					return "existe";
				}
			}
		}

		$fecha = GETFECHAHORA();

		$idusuariocrea = $this->session->userdata("userId");
		$empresa = GETEMPRESA();

		$datos["status"] = isset($datos['status']) ? "1" : "0";
		$datos["nuevo"] = isset($datos['nuevo']) ? "1" : "0";
		$datos["vendedor"] = isset($datos['vendedor']) ? "1" : "0";
		$datos["multisucursal"] = isset($datos['multisucursal']) ? "1" : "0";
		$datos["empresa"] = $empresa;

		if($id==0)
		{
			$datos["usuariocrea"] = $idusuariocrea;
			$datos["fechacreacion"] = $fecha;
			$datos["ultima_actualizacion"] = $fecha;
			$datos["celular"] = "0";

			$this->db->insert('usuarios', $datos);
			$id = $this->db->insert_id();
		}
		else
		{
			$datos["usuarioactualiza"] = $idusuariocrea;
			$datos["ultima_actualizacion"] = $fecha;

			$this->db->where("id", $id);
			$this->db->update('usuarios', $datos);
		}

		return $id;
	}

	public function getUsuarioByUsuario($usuario, $empresa)
	{
		$query = $this->db->query("SELECT * FROM usuarios WHERE usuario = '$usuario' AND empresa = '$empresa' ");
		return $query;
	}

	public function getUsuarioById($idusuario)
	{
		$query = $this->db->query("SELECT * FROM usuarios WHERE id = '$idusuario'");
		return $query;
	}

	public function saveEditUsuario($datos)
	{
		$fecha = GETFECHAHORA();

		$id = $datos['idusuario'];

		unset($datos['idusuario']);

		//$datos["usuariocrea"] = $this->session->userdata("userId");
		$datos["usuarioactualiza"] = $this->session->userdata("userId");
		//$datos["empresa"] = $this->session->userdata("empresa");
		//$datos["fechacreacion"] = $fecha;
		$datos["ultima_actualizacion"] = $fecha;
		//$datos["celular"] = "0";
		$datos["status"] = isset($datos['status']) ? "1" : "0";
		$datos["nuevo"] = isset($datos['nuevo']) ? "1" : "0";
		$datos["vendedor"] = isset($datos['vendedor']) ? "1" : "0";
		$datos["multisucursal"] = isset($datos['multisucursal']) ? "1" : "0";

		//print_r($datos);die();

		$this->db->where('id', $id);
		$this->db->update('usuarios', $datos);

		return $id;
	}

	public function getListaClientes($idsucursal)
	{
		$MS = VERIFICAMULTISUCURSAL();
		$sucursal = GETSUCURSAL();

		if($idsucursal==0) $wheresucursal = "";
		else $wheresucursal = " WHERE clientes.sucursal = '$idsucursal'";

		//if($MS==1){
			/*$consulta="SELECT clientes.id,clientes.codigo,clientes.nombre,clientes.calle,clientes.numero,
			clientes.colonia,clientes.ciudad,clientes.zona as zonaId,cat_sucursales.sucursal,clientes.status,cat_zonas.zona,
			fnGetProveedorCliente(clientes.id) AS proveedores, clientes.latitud, clientes.longitud, clientes.diasvisita,
			CONCAT_WS(' ', clientes.calle, clientes.numero, clientes.colonia, clientes.ciudad) AS domicilio, IF(clientes.status=1,'SI', 'NO') AS status2,
			clientes.telefono,
			(SELECT ccc.clasificacion FROM cat_clasificacion_cliente ccc WHERE ccc.id = clientes.`clasificacion`) AS clasificacion_cliente
			FROM clientes 
			INNER JOIN cat_zonas ON clientes.zona=cat_zonas.id 
			INNER JOIN cat_sucursales ON clientes.sucursal=cat_sucursales.id".$wheresucursal;*/

			/*$consulta="SELECT clientes.*,
			clientes.zona AS zonaId,
			fnGetProveedorCliente(clientes.id) AS proveedores, 
			CONCAT_WS(' ', clientes.calle, clientes.numero, clientes.colonia, clientes.ciudad) AS domicilio,
			IF(clientes.status=1,'SI', 'NO') AS status2,
			(SELECT ccc.clasificacion FROM cat_clasificacion_cliente ccc WHERE ccc.id = clientes.`clasificacion`) AS clasificacion_cliente,
			cat_sucursales.sucursal, 
			cat_zonas.zona,
			IFNULL((SELECT 'SI' FROM pedidos sub WHERE sub.idcliente = clientes.`id` AND sub.canal = 'B2B_APP' LIMIT 1), 'NO') AS cliente_digitalizado
			FROM clientes 
			INNER JOIN cat_zonas ON clientes.zona=cat_zonas.id 
			INNER JOIN cat_sucursales ON clientes.sucursal=cat_sucursales.id".$wheresucursal;*/

			$consulta="SELECT clientes.*,
			clientes.zona AS zonaId,
			GROUP_CONCAT(DISTINCT cat_proveedor.nombre ORDER BY cat_proveedor.nombre SEPARATOR ',') AS proveedores,
			CONCAT_WS(' ', clientes.calle, clientes.numero, clientes.colonia, clientes.ciudad) AS domicilio,
			IF(clientes.status=1,'SI', 'NO') AS status2,
			ccc.`clasificacion` AS clasificacion_cliente,
			cat_sucursales.sucursal, 
			cat_zonas.zona,
			IF(pedidos_b2b.idcliente IS NULL, 'NO', 'SI') AS cliente_digitalizado
			FROM clientes 
			INNER JOIN cat_zonas ON clientes.zona=cat_zonas.id 
			INNER JOIN cat_sucursales ON clientes.sucursal=cat_sucursales.id
			LEFT JOIN cat_clasificacion_cliente ccc ON clientes.`clasificacion` = ccc.`id`
			LEFT JOIN asi_cliente_proveedor
				ON asi_cliente_proveedor.cliente = clientes.id
				AND asi_cliente_proveedor.status = 1

			LEFT JOIN cat_proveedor
				ON cat_proveedor.id = asi_cliente_proveedor.proveedor
			LEFT JOIN (
				SELECT DISTINCT idcliente
				FROM pedidos
				WHERE canal = 'B2B_APP'
			) pedidos_b2b
				ON pedidos_b2b.idcliente = clientes.id".$wheresucursal . " GROUP BY clientes.id";
		/*}
		else{
			$consulta="SELECT clientes.id,clientes.codigo,clientes.nombre,clientes.calle,clientes.numero,
			clientes.colonia,clientes.ciudad,clientes.zona,cat_sucursales.sucursal,clientes.status,cat_zonas.zona,
			fnGetProveedorCliente(clientes.id) AS proveedores,
			CONCAT_WS(' ', clientes.calle, clientes.numero, clientes.colonia, clientes.ciudad) AS domicilio, IF(clientes.status=1,'SI', 'NO') AS status2
			FROM clientes 
			INNER JOIN cat_zonas ON clientes.zona=cat_zonas.id 
			INNER JOIN cat_sucursales ON clientes.sucursal=cat_sucursales.id 
			WHERE clientes.sucursal=$sucursal";
		}*/

		$query = $this->dbinfo->query($consulta);		
		return $query;
	}

	public function getListaClientesJsonByZonaDia($zona, $diavisita)
	{
		$consulta = "SELECT *, 
		fnGetZonaById(c.zona) AS zona_nombre,
		IFNULL((SELECT 'SI' FROM pedidos sub WHERE sub.idcliente = c.`id` AND sub.canal = 'B2B_APP' LIMIT 1), 'NO') AS cliente_digitalizado
		FROM clientes c 
		WHERE c.`status` = 1 AND FIND_IN_SET(c.`zona`, '$zona') AND FIND_IN_SET(c.`diasvisita`, '$diavisita')";

		$query = $this->dbinfo->query($consulta);		
		return $query;
	}

	public function getProveedoresCliente($id)
	{
		$consulta = "SELECT cat_proveedor.nombre FROM cat_proveedor INNER JOIN asi_cliente_proveedor ON asi_cliente_proveedor.proveedor=cat_proveedor.id WHERE asi_cliente_proveedor.cliente=$id AND asi_cliente_proveedor.status=1";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getCoordenadasCliente($id)
	{
		$consulta = "SELECT *, fnGetProveedorCliente(clientes.id) AS proveedores FROM clientes WHERE id=$id";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getPoligonoZona($id)
	{
		//if($id=="") $id=1;
		$consulta = "SELECT poligono FROM cat_zonas WHERE id=$id";
		$query = $this->dbinfo->query($consulta);
		foreach ($query->result() as $k) {
			$poligono = $k->poligono;
		}
		return isset($poligono) ? $poligono : "0";
	}

	public function getPoligono($id)
	{
		$consulta = "SELECT coordenadas,color FROM poligonos WHERE id=$id";
		$query = $this->dbinfo->query($consulta);		
		return $query;
	}

	public function getListaZonas2($id)
	{
		$consulta = "SELECT id,zona FROM cat_zonas WHERE status=1 AND idSucursal=$id ORDER BY zona ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;		
	}

	public function getListaProveedores($id)
	{
		$consulta = "SELECT cat_proveedor.id,cat_proveedor.nombre 
		FROM asi_sucursal_proveedor 
		INNER JOIN cat_sucursales ON cat_sucursales.id=asi_sucursal_proveedor.sucursal 
		INNER JOIN cat_proveedor ON cat_proveedor.id=asi_sucursal_proveedor.proveedor 
		WHERE cat_proveedor.status=1 AND cat_sucursales.status=1 AND cat_sucursales.id=$id";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function validarProveedorCliente($id,$cliente)
	{
		$consulta = "SELECT id FROM asi_cliente_proveedor WHERE proveedor=$id AND cliente=$cliente AND status=1";		
		$query = $this->dbinfo->query($consulta);
		if($query->num_rows()!=0){
			return "SI";
		}
		else{
			return "NO";
		}
	}

	public function getListaZonasAll()
	{		
		$consulta = "SELECT id,zona FROM cat_zonas WHERE status=1 ORDER BY zona ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveNewClient($datos)
	{
		$id = $datos["id"];
		unset($datos["id"]);

		$usuarioId = $this->session->userdata('userId');
		$usuarioname = $this->session->userdata('user');

		$proveedoresA = explode(",", $datos['proveedor']);
		$cuantosProv = count($proveedoresA);
		unset($datos["proveedor"]);

		$fechahora = GETFECHAHORA();

		$datos["direccion"] = $datos["calle"];
		$datos["status"] =  isset($datos["status"]) ? "1" : "0";

		$datos["isChuponera"] =  isset($datos["isChuponera"]) ? "1" : "0";
		$datos["isMundoCafe"] =  isset($datos["isMundoCafe"]) ? "1" : "0";
		$datos["isEnfriador"] =  isset($datos["isEnfriador"]) ? "1" : "0";

		if($id==0)
		{
			$datos["fechacreacion"] = $fechahora;
			$datos["ultima_actualizacion"] = $fechahora;
			$datos["codigo"] = GETNEWCLIENTENAME($datos['sucursal']);
			$datos["idusuariocrea"] = $usuarioId;
			$datos["esclientemovil"] = "NO";			

			$this->dbinfo->insert("clientes", $datos);
			$id = $this->dbinfo->insert_id();
			
			for ($i=0; $i < $cuantosProv; $i++) 
			{
				$this->dbinfo->insert('asi_cliente_proveedor',array('cliente'=>$id, 'proveedor'=>$proveedoresA[$i], 'status'=>1, 'creadopor'=>$usuarioname, 'ultima_actualizacion'=>$fechahora, 'creadopor'=>$usuarioId ));
			}
		}
		else
		{
			$datos["ultima_actualizacion"] = $fechahora;
			$datos["idusuarioactualiza"] = $usuarioId;

			unset($datos["proveedor"]);

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("clientes", $datos);
			$idultimo = $this->dbinfo->insert_id();
			
			$conCliProv="UPDATE asi_cliente_proveedor SET status=0, creadopor=$usuarioId, ultima_actualizacion='$fechahora' WHERE cliente=$id";		
			$this->dbinfo->query($conCliProv);		

			if($cuantosProv!=0)
			{
				for ($i=0; $i < $cuantosProv; $i++)
				{
					$consultaVer = "SELECT id FROM asi_cliente_proveedor WHERE cliente=$id AND proveedor=$proveedoresA[$i]";			
					$queryVer = $this->dbinfo->query($consultaVer);				
					if($queryVer->num_rows()==0)
					{
						$this->dbinfo->insert('asi_cliente_proveedor',array('cliente'=>$id, 'proveedor'=>$proveedoresA[$i], 'status'=>1, 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$fechahora));				
					}
					else
					{
						$conCliProv2="UPDATE asi_cliente_proveedor SET status=1, creadopor=$usuarioId, ultima_actualizacion='$fechahora' WHERE cliente=$id AND proveedor=$proveedoresA[$i]";				
						$this->dbinfo->query($conCliProv2);
					}
				}
			}
		}

		return $id;
	}

	public function editClienteDiaZona($datos)
	{
		$usuarioId = $this->session->userdata('userId');
		$usuarioname = $this->session->userdata('user');
		$fechahora = GETFECHAHORA();

		$datos["ultima_actualizacion"] = $fechahora;
		$datos["idusuarioactualiza"] = $usuarioId;
		$datos["subidobees"] = "0";

		//print_r($datos);die();

		$this->dbinfo->where("codigo", $datos["codigo"]);
		$this->dbinfo->update("clientes", $datos);

		return 1;
	}

	public function getClaveNewCliente($idSucursal)
	{
		date_default_timezone_set('America/Mazatlan');
		$fecha1 = date('Y-m-d');
		$fecha2 = date('y-m-d');

		$consulta="SELECT cat_sucursales.clave, conf_consecutivos.consecutivo, conf_consecutivos.dia, conf_consecutivos.id AS idConf FROM cat_sucursales INNER JOIN conf_consecutivos ON cat_sucursales.id=conf_consecutivos.sucursal WHERE cat_sucursales.id=$idSucursal";
		$query=$this->dbinfo->query($consulta);
		$idConf=$query->row()->idConf;
		$fecha=$query->row()->dia;
		if($fecha!=$fecha1){
			$actualiza="UPDATE conf_consecutivos SET consecutivo=2, dia='$fecha1' WHERE id=$idConf";
			$this->dbinfo->query($actualiza);
			$consecutivo=1;
		}
		else{
			$consecutivo=$query->row()->consecutivo;
			$actualiza="UPDATE conf_consecutivos SET consecutivo=$consecutivo+1, dia='$fecha1' WHERE id=$idConf";
			$this->dbinfo->query($actualiza);
	
		}
			if($consecutivo<10){
				$cadena="000".$consecutivo;
			}
			else{
				if($consecutivo<100){
					$cadena="00".$consecutivo;
				}
				else{
					if($consecutivo<999){
						$cadena="0".$consecutivo;
					}
					else{
						$cadena=$consecutivo;
					}
				}
			}
			
			$sucursal=$query->row()->clave;
			$fechaT=str_replace("-", "", $fecha2);
			return $sucursal.$fechaT.$cadena;
		
	}

	public function getClienteByCodigo($pCodigo)
	{
		$query = $this->dbinfo->query("SELECT * FROM clientes WHERE status = 1 AND codigo = '$pCodigo'");
		return $query->row();
	}

	public function getListaProductos($tipo = 'PRODUCTO')
	{
		$consulta = "SELECT cat_productos.*,
		fnGetProveedorById(proveedor) as nombre_proveedor,
		fnGetClasificacionById(clasificacion) as nombre_clasificacion,
		CONCAT('$', FORMAT(precio, 2)) as precio_format,
		CONCAT(ieps, '%') as ieps_format,
		CONCAT('$', FORMAT(costo, 2)) as costo_format,
		IF(status=1,'SI', 'NO') as status2,
		(SELECT GROUP_CONCAT(cat_sucursales.`sucursal`) FROM cat_sucursales WHERE FIND_IN_SET(cat_sucursales.id, cat_productos.`sucursales`) ) AS sucursales2
		FROM cat_productos
		WHERE tipo2 = '$tipo'
		ORDER BY nombre ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListaProductosByStatus($status)
	{
		$consulta = "SELECT cat_productos.*,
		fnGetProveedorById(proveedor) as nombre_proveedor,
		fnGetClasificacionById(clasificacion) as nombre_clasificacion,
		CONCAT('$', FORMAT(precio, 2)) as precio_format,
		CONCAT(ieps, '%') as ieps_format,
		IF(status=1,'SI', 'NO') as status2
		FROM cat_productos
		WHERE status IN($status)
		ORDER BY nombre ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListaProductosInventarioJson($idsucursal)
	{
		$query = $this->dbinfo->query("SELECT *,
		COALESCE((SELECT ir.cantidaddisponible FROM inventario_real ir WHERE ir.idproducto = cp.`id` AND ir.idsucursal = '$idsucursal'), 0) AS cantidaddisponible
		FROM cat_productos cp
		WHERE cp.`status` = 1 AND cp.`tipo2` = 'PRODUCTO'
		ORDER BY cp.`nombre`");

		return $query->result();
	}

	public function getListaProveedoresAll()
	{
		$consulta = "SELECT id,nombre FROM cat_proveedor ORDER BY nombre ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListaClasProd()
	{
		$consulta = "SELECT id,nombre FROM cat_clasificacionproductos WHERE status=1 ORDER BY nombre ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveNewProducto($datos)
	{
		$id = $datos["id"];
		$idmarca = $datos["idmarca"];		

		unset($datos["id"]);
		unset($datos["idmarca"]);

		$datos["idmarcasistema"] = explode("-", $idmarca)[0];
		$datos["idmarca"] = explode("-", $idmarca)[1];
		$datos["marca"] = explode("-", $idmarca)[2];

		$existe = $this->getProductoByCodigo($datos["codigo"])->row();
		if( isset($existe) ){
			if($id==0){
				return "existe";
			}else{
				if($existe->id!=$id){
					return "existe";
				}
			}
		}

		$datos["status"] = isset($datos["status"]) ? "1" : "0";
		$datos["audiencia"] = isset($datos["audiencia"]) ? "1" : "0";
		$datos["subidobees"] = "0";

		if($id == 0)
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuariocrea"] = $this->session->userdata('userId');

			$this->dbinfo->insert("cat_productos", $datos);
			$id = $this->dbinfo->insert_id();
		}
		else
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuarioactualiza"] = $this->session->userdata('userId');

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("cat_productos", $datos);
		}
		
		return $id;
	}

	public function saveComponentesPaquete($componentes, $componentesbees)
	{
		$idpaquete = $componentes[0]["idpaquete"];
		$this->dbinfo->query("DELETE FROM componentes_paquete WHERE idpaquete = '$idpaquete'");

		foreach($componentes as $componente)
		{
			$this->dbinfo->insert("componentes_paquete", $componente);
		}

		$idpaquete = $componentesbees[0]["idpaquete"];
		$this->dbinfo->query("DELETE FROM componentes_paquete_bees WHERE idpaquete = '$idpaquete'");

		foreach($componentesbees as $componente)
		{
			$this->dbinfo->insert("componentes_paquete_bees", $componente);
		}

		return "1";
	}

	public function savePaquetesSucursal($sucursales)
	{
		foreach($sucursales as $sucursal)
		{
			unset($sucursal["sucursal"]);
			unset($sucursal["cantidadvendidos"]);

			$idpaquete = $sucursal["idpaquete"];
			$idsucursal = $sucursal["idsucursal"];

			$infosucursal = $this->dbinfo->query("SELECT * FROM paquetes_sucursal WHERE idpaquete = '$idpaquete' AND idsucursal = '$idsucursal'")->result();

			if(count($infosucursal) > 0)
			{
				$this->dbinfo->where(array("idpaquete" => $idpaquete, "idsucursal" => $idsucursal));
				$this->dbinfo->update("paquetes_sucursal", $sucursal);
			}
			else
			{
				$this->dbinfo->insert("paquetes_sucursal", $sucursal);
			}
		}

		return "1";
	}

	public function savePaquetesAudiencia($pDatos)
	{
		$idpaquete = $pDatos["idpaquete"];
		$codigopaquete = $pDatos["codigo"];
		$audiencia = $pDatos["items_audiencia"];

		$this->dbinfo->query("DELETE FROM paquetes_audiencia WHERE idpaquete = '$idpaquete'");

		if(count($audiencia)==0)
		{
			return "1";
		}

		$result = [];

		// Transponer el array
		foreach ($audiencia as $row)
		{
			foreach ($row as $key => $value) 
			{
				$result[$key][] = $value;
			}
		}

		// Quitar valores repetidos por cada columna
		/*foreach ($result as $key => $values) 
		{
			$result[$key] = array_unique($values);
		}*/

		$this->dbinfo->insert("paquetes_audiencia", 
			array
			(
				"idpaquete" => $idpaquete,
				"codigopaquete" => $codigopaquete,
				"sucursal" => implode("|", $result["sucursal"]),
				"clientes" => implode("|", $result["codigocliente"])
			)
		);

		return "1";
	}

	public function deshabilitarPaquetes()
	{
		echo $this->dbinfo->query("UPDATE cat_productos SET status = 0 WHERE tipo2 = 'PAQUETE'");
	}

	public function getProducto($id)
	{
		$consulta = "SELECT p.*, 
		(SELECT GROUP_CONCAT(cat_sucursales.`sucursal`) FROM cat_sucursales WHERE FIND_IN_SET(cat_sucursales.id, p.`sucursales`) ) AS sucursales2,
		(SELECT sub.clientes FROM paquetes_audiencia sub WHERE sub.idpaquete = p.`id`) AS audiencia_clientes,
		(SELECT sub.sucursal FROM paquetes_audiencia sub WHERE sub.idpaquete = p.`id`) AS audencia_sucursales
		FROM cat_productos p
		WHERE p.id = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getComponentesPaquete($id)
	{
		$query = $this->dbinfo->query("SELECT *,
		(SELECT p.nombre FROM cat_productos p WHERE p.id = cp.`idproducto`) AS nombre
		FROM componentes_paquete cp
		WHERE cp.`idpaquete` = '$id'")->result();

		return $query;
	}

	public function getComponentesPaqueteBees($id)
	{
		$query = $this->dbinfo->query("SELECT *,
		(SELECT p.nombre FROM cat_productos p WHERE p.id = cp.`idproducto`) AS nombre
		FROM componentes_paquete_bees cp
		WHERE cp.`idpaquete` = '$id'")->result();

		return $query;
	}

	public function getSucursalesPaquetes($id)
	{
		$query = $this->dbinfo->query("SELECT *,
		fnGetSucursalById(cp.idsucursal) AS sucursal,
		fnGetPaquetesVendidosBySucursal(cp.`idsucursal`, cp.`idpaquete`) AS paquetesvendidos
		FROM paquetes_sucursal cp
		WHERE cp.`idpaquete` = '$id'")->result();

		return $query;
	}

	public function getProductoByCodigo($codigo)
	{
		$consulta = "SELECT * FROM cat_productos WHERE codigo = '$codigo'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListadoProveedores()
	{
		$consulta = "SELECT cat_proveedor.*,
		IF(status=1,'SI', 'NO') as status2,
		fnGetNumProductosProveedor(cat_proveedor.id) as num_productos
		FROM cat_proveedor
		ORDER BY cat_proveedor.nombre ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveNewProveedor($datos)
	{
		$id = $datos["id"];
		unset($datos["id"]);		

		$datos["status"] =  isset($datos["status"]) ? "1" : "0";

		if($id == 0)
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuariocrea"] = $this->session->userdata('userId');

			$this->dbinfo->insert("cat_proveedor", $datos);
			$id = $this->dbinfo->insert_id();
		}
		else
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuarioactualiza"] = $this->session->userdata('userId');

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("cat_proveedor", $datos);
		}
		
		return $id;		
	}

	public function getProveedorById($id)
	{
		$consulta = "SELECT * FROM cat_proveedor WHERE id = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListadoCategorias()
	{
		$consulta = "SELECT cat_clasificacionproductos.*,
		fnGetProveedorById(clientePro) as nombre_proveedor,
		IF(status=1,'SI', 'NO') as status2
		FROM cat_clasificacionproductos
		ORDER BY nombre ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveNewCategoria($datos)
	{
		$id = $datos["id"];
		unset($datos["id"]);		

		$datos["status"] =  isset($datos["status"]) ? "1" : "0";

		if($id == 0)
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuariocrea"] = $this->session->userdata('userId');

			$this->dbinfo->insert("cat_clasificacionproductos", $datos);
			$id = $this->dbinfo->insert_id();
		}
		else
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuarioactualiza"] = $this->session->userdata('userId');

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("cat_clasificacionproductos", $datos);
		}
		
		return $id;
	}

	public function getCategoriaById($id)
	{
		$consulta=  "SELECT id,nombre,status,clientePro FROM cat_clasificacionproductos WHERE id=$id";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListadoMarcas()
	{
		$consulta = "SELECT cat.*,
		IF(cat.estatus=1,'SI', 'NO') as status2
		FROM cat_marca cat
		ORDER BY nombre ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveNewMarca($datos)
	{
		$id = $datos["id"];
		unset($datos["id"]);		

		$datos["estatus"] =  isset($datos["estatus"]) ? "1" : "0";

		if($id == 0)
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuariocrea"] = $this->session->userdata('userId');

			$this->dbinfo->insert("cat_marca", $datos);
			$id = $this->dbinfo->insert_id();
		}
		else
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuarioactualiza"] = $this->session->userdata('userId');

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("cat_marca", $datos);
		}
		
		return $id;
	}

	public function getMarcaById($id)
	{
		$consulta = "SELECT * FROM cat_marca WHERE id = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListadoClasificacionClientes()
	{
		$consulta = "SELECT cat_clasificacion_cliente.*,
		IF(status=1,'SI', 'NO') as status2
		FROM cat_clasificacion_cliente
		ORDER BY clasificacion ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveNewClasificacionCliente($datos)
	{
		$id = $datos["id"];
		unset($datos["id"]);		

		$datos["status"] =  isset($datos["status"]) ? "1" : "0";

		if($id == 0)
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuariocrea"] = $this->session->userdata('userId');

			$this->dbinfo->insert("cat_clasificacion_cliente", $datos);
			$id = $this->dbinfo->insert_id();
		}
		else
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuarioactualiza"] = $this->session->userdata('userId');

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("cat_clasificacion_cliente", $datos);
		}
		
		return $id;
	}

	public function getClasificacionClienteById($id)
	{
		$consulta=  "SELECT * FROM cat_clasificacion_cliente WHERE id = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListaSucursalesAll()
	{
		$consulta = "SELECT *, 
		fnGetProveedorSucursal(cat_sucursales.id) as proveedores,
		IF(status=1,'SI', 'NO') as status2
		FROM cat_sucursales";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveNewSucursal($datos)
	{
		$isnuevo = true;

		$id = $datos["id"];
		unset($datos["id"]);

		$proveedores = $datos["proveedor"];
		unset($datos["proveedor"]);

		$existe = $this->getSucursalByClave($datos["clave"])->row();
		if( isset($existe) ){
			if($id==0){
				return "existe";
			}else{
				if($existe->id!=$id){
					return "existe";
				}
			}
		}

		$datos["status"] =  isset($datos["status"]) ? "1" : "0";
		$datos["autoventa"] =  isset($datos["autoventa"]) ? "1" : "0";

		$idusuariocrea = $this->session->userdata('userId');

		if($id == 0)
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuariocrea"] = $idusuariocrea;

			$this->dbinfo->insert("cat_sucursales", $datos);
			$id = $this->dbinfo->insert_id();

			$this->dbinfo->insert("conf_consecutivos", array( "sucursal"=>$id, "dia"=>GETFECHA() ));
		}
		else
		{
			$isnuevo = false;
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuarioactualiza"] = $idusuariocrea;

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("cat_sucursales", $datos);
		}
		
		$proveedoresA = explode(",", $proveedores);
		$cuantosProv = count($proveedoresA);

		if($isnuevo)
		{
			for ($i=0; $i < $cuantosProv; $i++) 
			{
				$this->dbinfo->insert('asi_sucursal_proveedor',array('sucursal'=>$id, 'proveedor'=>$proveedoresA[$i], 'status'=>1, 'creadopor'=>$idusuariocrea, 'ultima_actualizacion'=>GETFECHAHORA() ));
			}
		}
		else
		{
			$this->dbinfo->where("sucursal", $id);						
			$this->dbinfo->update('asi_sucursal_proveedor',array('status'=>0, 'creadopor'=>$idusuariocrea, 'ultima_actualizacion'=>GETFECHAHORA() ));			

			if($proveedores!="")
			{
				for ($i=0; $i < $cuantosProv; $i++)
				{
					$consultaR="SELECT id FROM asi_sucursal_proveedor WHERE sucursal='$id' AND proveedor='$proveedoresA[$i]'";
					$queryR=$this->dbinfo->query($consultaR);

					if($queryR->num_rows()!=0)
					{
						$idRZ=$queryR->row()->id;
						$this->dbinfo->where("id", $idRZ);						
						$this->dbinfo->update('asi_sucursal_proveedor',array('sucursal'=>$id, 'proveedor'=>$proveedoresA[$i], 'status'=>1, 'creadopor'=>$idusuariocrea, 'ultima_actualizacion'=>GETFECHAHORA() ));
					}
					else
					{
						$this->dbinfo->insert('asi_sucursal_proveedor',array('sucursal'=>$id, 'proveedor'=>$proveedoresA[$i], 'status'=>1, 'creadopor'=>$idusuariocrea, 'ultima_actualizacion'=>GETFECHAHORA() ));
					}
				}
			}
		}

		return $id;
	}

	public function getSucursalByClave($clave)
	{
		$consulta = "SELECT * FROM cat_sucursales WHERE clave = '$clave'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getDatosSucursal($id)
	{
		$consulta = "SELECT *,fnGetProveedorSucursal(cat_sucursales.id) AS proveedores FROM cat_sucursales WHERE id = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListaRutas($idsucursal)
	{
		$MS = VERIFICAMULTISUCURSAL();
		$sucursal = GETSUCURSAL();

		if($idsucursal==0) $wheresucursal = "";
		else $wheresucursal = " WHERE cat_rutas.sucursal = '$idsucursal'";

		/*if($MS==1)
		{*/
			$consulta="SELECT cat_rutas.*, 
			fnGetSucursalById(cat_rutas.sucursal) as sucursal_nombre,
			fnGetRutaZonas(cat_rutas.id, 'nombre') as zonas,
			fnGetRutaZonas(cat_rutas.id, 'numero') as num_clientes,
			fnGetProveedorRuta(cat_rutas.id) as proveedores,
			IF(status=1,'SI', 'NO') as status2
			FROM cat_rutas".$wheresucursal;
		/*}
		else
		{
			$consulta="SELECT cat_rutas.*, 
			fnGetSucursalById(cat_rutas.sucursal) as sucursal,
			IF(status=1,'SI', 'NO') as status2
			FROM cat_rutas 
			WHERE cat_rutas.sucursal=$sucursal";
		}*/

		//die($consulta);

		$query = $this->dbinfo->query($consulta)->result_array();

		foreach ($query as $key => $value)
		{
			if ($value["chofer"] == "0")
			{
				$query[$key]["nombre_chofer"] = "NO ASIGNADO";
			}
			else
			{
				$usuario = GETDATOSUSUARIO($value["chofer"], "nombre");

				$query[$key]["nombre_chofer"] = is_object($usuario)
					? $usuario->nombre
					: "NO ENCONTRADO";
			}
		}
		
		return $query;
	}

	public function saveNewRuta($datos)
	{
		$id = $datos["id"];
		unset($datos["id"]);

		$zonas = $datos["zona"];
		unset($datos["zona"]);

		$proveedor = $datos["proveedor"];
		unset($datos["proveedor"]);

		$arregloProveedor = explode(",", $proveedor);
		$cuantosProveedor = count($arregloProveedor);

		$arregloZona = explode(",", $zonas);
		$cuantasZona = count($arregloZona);

		$fecha = GETFECHAHORA();

		$datos["status"] =  isset($datos["status"]) ? "1" : "0";
		$datos["chofer"] = $datos["agente"];

		$usuarioId = $this->session->userdata('userId');

		$existe = $this->getRutaByRuta($datos["ruta"])->row();
		if( isset($existe) ){
			if($id==0){
				return "existe";
			}else{
				if($existe->id!=$id){
					return "existe";
				}
			}
		}

		if($id == 0)
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuariocrea"] = $usuarioId;

			$this->dbinfo->insert("cat_rutas", $datos);
			$id = $this->dbinfo->insert_id();

			for ($i=0; $i < $cuantasZona; $i++) 
			{
				$this->dbinfo->insert('asi_ruta_zona',array('ruta'=>$id, 'zona'=>$arregloZona[$i], 'status'=>1, 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$fecha));
			}

			for ($i=0; $i < $cuantosProveedor; $i++) {
				$this->dbinfo->insert('asi_proveedor_ruta',array('ruta'=>$id, 'proveedor'=>$arregloProveedor[$i], 'status'=>1, 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$fecha));	
			}
		}
		else
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuarioactualiza"] = $usuarioId;

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("cat_rutas", $datos);

			$this->dbinfo->where("ruta", $id);
			$this->dbinfo->update("asi_proveedor_ruta", array('status'=>'0', 'ultima_actualizacion'=>$fecha, 'creadopor'=>$usuarioId));

			if($proveedor!="")
			{
				for ($i=0; $i < $cuantosProveedor; $i++)
				{
					$consultaR="SELECT id FROM asi_proveedor_ruta WHERE ruta='$id' AND proveedor='$arregloProveedor[$i]'";						
					$queryR=$this->dbinfo->query($consultaR);
					if($queryR->num_rows()>0)
					{
						$idRZ=$queryR->row()->id;
						$this->dbinfo->where("id", $idRZ);
						$this->dbinfo->update("asi_proveedor_ruta", array('ruta'=>$id, 'proveedor'=>$arregloProveedor[$i], 'status'=>'1', 'ultima_actualizacion'=>$fecha, 'creadopor'=>$usuarioId));
					}
					else
					{
						$this->dbinfo->insert('asi_proveedor_ruta',array('ruta'=>$id, 'proveedor'=>$arregloProveedor[$i], 'status'=>'1', 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$fecha));
					}
				}
			}

			$this->dbinfo->where("ruta", $id);
			$this->dbinfo->update("asi_ruta_zona", array('status'=>'0', 'ultima_actualizacion'=>$fecha, 'creadopor'=>$usuarioId));

			if($zonas!="")
			{
				for ($i=0; $i < $cuantasZona; $i++)
				{
					$consultaR="SELECT id FROM asi_ruta_zona WHERE ruta='$id' AND zona='$arregloZona[$i]'";
					$queryR=$this->dbinfo->query($consultaR);

					if($queryR->num_rows()>0){
						$idRZ=$queryR->row()->id;
						$this->dbinfo->where("id", $idRZ);
						$this->dbinfo->update("asi_ruta_zona", array('ruta'=>$id, 'zona'=>$arregloZona[$i], 'status'=>'1', 'ultima_actualizacion'=>$fecha, 'creadopor'=>$usuarioId));
					}
					else
					{
						$this->dbinfo->insert('asi_ruta_zona',array('ruta'=>$id, 'zona'=>$arregloZona[$i], 'status'=>'1', 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$fecha));
					}
				}
			}
		}

		//ACTUALIZAR EL IDRUTA en la tabla de usuarios
		$this->db->where('id', $datos["chofer"]);
		$this->db->update("usuarios", array('ruta'=>$id));

		return $id;
	}

	public function getRutaByRuta($ruta)
	{
		$consulta = "SELECT * FROM cat_rutas WHERE ruta = '$ruta'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getDatosRuta($id)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$consulta = "SELECT *,
		fnGetProveedorRuta(cat_rutas.id) as proveedores,
		fnGetRutaZonas(cat_rutas.id, 'nombre') as zonas
		FROM cat_rutas 
		WHERE id=$id";

		$query = $db->query($consulta);

		return $query;
	}

	public function getRutasBySucursal($idsucursal)
	{
		$consulta = "SELECT *, ruta as Ruta FROM cat_rutas WHERE status = 1 AND sucursal = '$idsucursal' ORDER BY ruta";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getZonaCatalogo($idsucursal)
	{
		if($idsucursal==0) $wheresucursal = "";
		else $wheresucursal = " WHERE cat_zonas.idSucursal = '$idsucursal'";

		$consulta = "SELECT cat_zonas.*,
		fnGetSucursalById(cat_zonas.idSucursal) as sucursal_nombre,
		fnGetZonaRutas(cat_zonas.id) as rutas,
		fnGetNumClientesZona(cat_zonas.id) as num_clientes,
		IF(status=1,'SI', 'NO') as status2
		FROM cat_zonas".$wheresucursal;
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveNewZona($datos)
	{
		$id = $datos["id"];
		unset($datos["id"]);

		$existe = $this->getZonaByZona($datos["zona"])->row();
		if( isset($existe) ){
			if($id==0){
				return "existe";
			}else{
				if($existe->id!=$id){
					return "existe";
				}
			}
		}

		$datos["status"] =  isset($datos["status"]) ? "1" : "0";

		if($id == 0)
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuariocrea"] = $this->session->userdata('userId');

			$this->dbinfo->insert("cat_zonas", $datos);
			$id = $this->dbinfo->insert_id();
		}
		else
		{
			$datos["fechacreacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["idusuarioactualiza"] = $this->session->userdata('userId');

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("cat_zonas", $datos);
		}
		
		return $id;
	}

	public function getZonaByZona($zona)
	{
		$consulta = "SELECT * FROM cat_zonas WHERE zona = '$zona'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getDatosZonas($id)
	{
		$consulta = "SELECT *,
		fnGetNumClientesZona(cat_zonas.id) as num_clientes
		FROM cat_zonas 
		WHERE id=$id";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getTipoDocumento()
	{
		return $this->dbinfo->query("SELECT * FROM cat_tipo_documento WHERE status = 1");
	}

	public function getTipoDocumentoById($tipodocumento)
	{
		return $this->dbinfo->query("SELECT * FROM cat_tipo_documento WHERE id = '$tipodocumento'");
	}

	public function getListaPromociones()
	{
		$consulta = "SELECT promocion.*,
		UPPER(tipo) as tipo2,
		IF(status=1,'SI', 'NO') as status2,
		(SELECT GROUP_CONCAT(cat_sucursales.`sucursal`) FROM cat_sucursales WHERE FIND_IN_SET(cat_sucursales.id, promocion.`sucursales`) ) AS sucursales2
		FROM promocion
		ORDER BY id ASC";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveNewPromocion($datos)
	{
		$id = $datos["id"];
		unset($datos["id"]);

		$detalle = json_decode($datos["detalle"], true);
		unset($datos["detalle"]);

		$existe = $this->getPromocionByCodigo($datos["codigo"])->row();
		if( isset($existe) ){
			if($id==0){
				return "existe";
			}else{
				if($existe->id!=$id){
					return "existe";
				}
			}
		}

		//$datos["status"] =  isset($datos["status"]) ? "1" : "0";

		if($id == 0)
		{
			$datos["fecha_creacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["usuariocrea"] = $this->session->userdata('userId');

			$this->dbinfo->insert("promocion", $datos);
			$id = $this->dbinfo->insert_id();
		}
		else
		{
			$datos["fecha_creacion"] = GETFECHAHORA();
			$datos["ultima_actualizacion"] = GETFECHAHORA();
			$datos["usuarioactualiza"] = $this->session->userdata('userId');

			$this->dbinfo->where("id", $id);
			$this->dbinfo->update("promocion", $datos);
		}

		if( count($detalle)>0 )
		{
			foreach($detalle as $item)
			{
				$existe = $this->dbinfo->query("SELECT * FROM promocion_detalle WHERE idpromocion = '$id' AND idproducto = '".$item["4"]."'");
				if($existe->num_rows()>0)
				{
					$iddetalle = $existe->row()->id;
					$datos = array( "condicion"=>$item["5"], "promocion"=>$item["6"] );

					$this->dbinfo->where("id", $iddetalle);
					$this->dbinfo->update("promocion_detalle", $datos);
				}
				else
				{
					$datos = array( "idpromocion"=>$id, "idproducto"=>$item["4"], "codigoproducto"=>$item["0"], "condicion"=>$item["5"], "promocion"=>$item["6"] );
					$this->dbinfo->insert("promocion_detalle", $datos);
				}
			}
		}
		
		return $id;
	}

	public function getPromocionByCodigo($codigo)
	{
		$consulta = "SELECT * FROM promocion WHERE codigo = '$codigo'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getPromocion($id)
	{
		$consulta = "SELECT *, (SELECT GROUP_CONCAT(cat_sucursales.`sucursal`) FROM cat_sucursales WHERE FIND_IN_SET(cat_sucursales.id, promocion.`sucursales`) ) AS sucursales2 FROM promocion WHERE id = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getPromocionDetalle($id)
	{
		$consulta = "SELECT * FROM promocion_detalle WHERE idpromocion = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getListaRepartoRutasJson($idsucursal)
	{
		$query = $this->db->query("SELECT *
		FROM usuarios u 
		WHERE u.perfil = 6 AND u.status = 1 AND sucursal = ? AND empresa = ?", [$idsucursal, $this->session->userdata('empresa')])->result();

		if(in_array($idsucursal, array(13, 17)))
		{
			$query = $this->db->query("SELECT *
			FROM usuarios u 
			WHERE u.perfil = 6 AND u.status = 1 AND sucursal IN(13,17) AND empresa = ?", [$this->session->userdata('empresa')])->result();
		}

		foreach($query as $key => $usuario)
		{

			$query[$key]->sucursal_nombre = "";

			$datos_sucursal = $this->getDatosSucursal($usuario->sucursal);

			if($datos_sucursal->num_rows() > 0)
			{
				$query[$key]->sucursal_nombre = $datos_sucursal->row()->sucursal;
			}

			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(cr.`ruta`) AS rutas
			FROM asi_reparto_rutas arr
			INNER JOIN cat_rutas cr ON arr.`idruta` = cr.`id`
			WHERE arr.`status` = 1 AND cr.`status` = 1 AND arr.idusuario = '$usuario->id'
			ORDER BY cr.ruta");

			if($rutas->num_rows() > 0)
			{
				$query[$key]->rutas_asignadas = $rutas->row()->rutas;
			}
			else
			{
				$query[$key]->rutas_asignadas = "";
			}
		}

		return $query;
	}

	public function getListaRepartoRutasAsignadasJson($idusuario)
	{
		$query = $this->dbinfo->query("SELECT cr.*
		FROM asi_reparto_rutas arr
		INNER JOIN cat_rutas cr ON arr.`idruta` = cr.`id`
		WHERE arr.`status` = 1 AND cr.`status` = 1 AND arr.idusuario = '$idusuario'
		ORDER BY cr.ruta");

		return $query;
	}

	public function getListaRepartoRutasDisponiblesJson($idsucursal)
	{
		$query = $this->dbinfo->query("SELECT *
		FROM cat_rutas cr 
		WHERE cr.`status` = 1 AND cr.sucursal = '$idsucursal' AND cr.id NOT IN(SELECT arr.idruta FROM asi_reparto_rutas arr 
		WHERE arr.`status` = 1)
		ORDER BY cr.ruta");

		if(in_array($idsucursal, array(13,17)))
		{
			$query = $this->dbinfo->query("SELECT *
			FROM cat_rutas cr 
			WHERE cr.`status` = 1 AND cr.sucursal IN(13,17) AND cr.id NOT IN(SELECT arr.idruta FROM asi_reparto_rutas arr 
			WHERE arr.`status` = 1)
			ORDER BY cr.ruta");
		}

		return $query;
	}

	public function asignacionRepartoRutas($datos)
	{
		$ids = explode(',', $datos["rutas"]);

		$fechahora = GETFECHAHORA();
		$idusuario = $this->session->userdata('userId');

		$this->dbinfo->where(array("idusuario" => $datos["idusuario"]));
		$this->dbinfo->update("asi_reparto_rutas", array("status" => "0", "ultima_actualizacion" => $fechahora, "idusuario_actualiza" => $idusuario));

		if($datos["rutas"] == "")
		{
			return "no";
		}

		foreach($ids as $idruta)
		{
			$existe = $this->dbinfo->query("SELECT * FROM asi_reparto_rutas WHERE idruta = '$idruta' AND idusuario = '$datos[idusuario]'");

			if($existe->num_rows() > 0)
			{
				$idregistro = $existe->row()->id;

				$this->dbinfo->where("id", $idregistro);
				$this->dbinfo->update("asi_reparto_rutas", array("status" => "1", "ultima_actualizacion" => $fechahora, "idusuario_actualiza" => $idusuario));
			}
			else
			{
				$nueva_ruta = array(
					"idruta" => $idruta,
					"idusuario" => $datos["idusuario"],
					"status" => "1",
					"ultima_actualizacion" => $fechahora,
					"idusuario_crea" => $idusuario,
					"idusuario_actualiza" => $idusuario
				);

				$this->dbinfo->insert("asi_reparto_rutas", $nueva_ruta);
			}
		}

		return "si";
	}

	public function getListaInventario($data)
	{
		if($data["idsucursal"] == 0)
		{
			$query = $this->dbinfo->query("SELECT *,
			(SELECT cs.sucursal FROM cat_sucursales cs WHERE cs.id = ir.`idsucursal`) AS sucursal
			FROM inventario_real ir
			ORDER BY ir.nombre")->result();
		}
		else
		{
			$query = $this->dbinfo->query("SELECT *,
			(SELECT cs.sucursal FROM cat_sucursales cs WHERE cs.id = ir.`idsucursal`) AS sucursal
			FROM inventario_real ir
			WHERE ir.idsucursal = '$data[idsucursal]'
			ORDER BY ir.nombre")->result();
		}

		return $query;
	}

/*	public function makeCmbCaracteristicas($numVehiculo)
	{
		
		$consulta="SELECT caracteristicas.id, caracteristicas.caracteristica, caracteristicas.valor, cat_categorias.categoria FROM caracteristicas, cat_categorias WHERE caracteristicas.categoria=cat_categorias.id and caracteristicas.num_vehiculo='$numVehiculo' ORDER BY cat_categorias.id";
		$query= $this->db->query($consulta);

		return $query;
		
	}*/
/*SECCION DE HOME*/
	
	
/*TERMINA SECCION DE HOME*/
	/*INICIA SECCION DE PROVEEDOR*/
	
	
	

public function saveEditCategoria(){
	date_default_timezone_set('America/Mazatlan');
		$hoy=date('Y-m-d');

		$hora=date('H:i:s',time());
		$fecha=$hoy." ".$hora;
		$id=$_POST['txtId'];
	$nombre=strtoupper($_POST['txtNombre']);
	
	
	if(isset($_POST['checkActivo'])){
			$activo=1;
		}
		else{
			$activo=0;
		}
	$proveedor=$_POST['cmbProveedor'];
	$consulta="UPDATE cat_clasificacionproductos SET nombre='$nombre', status=$activo, ultima_actualizacion='$fecha', clientePro=$proveedor WHERE id=$id";
	$this->db->query($consulta);
	}
	/*TERMINA SECCION DE PROVEEDOR*/


/*INICIA SECCION DE PROVEEDOR*/
	
	public function getNumProductos($id){
		$consulta="SELECT COUNT(id) AS cantidad FROM cat_productos WHERE proveedor=$id";
		$query=$this->db->query($consulta);
		return $query->row()->cantidad;
	}

public function saveEditProveedor($datos){
	date_default_timezone_set('America/Mazatlan');
	$hoy=date('Y-m-d');

		$hora=date('H:i:s',time());
		$fecha=$hoy." ".$hora;
	$id=$_POST['txtId'];
	$nombre=$_POST['txtNombre'];
	$domicilio=$_POST['txtDomicilio'];
	$comentario=$_POST['txtComentario'];
	$telefono=$_POST['txtTelefono'];
	
	if(isset($_POST['checkActivo'])){
			$activo=1;
		}
		else{
			$activo=0;
		}
	//$proveedor=$_POST['cmbProveedor'];
	$consulta="UPDATE cat_proveedor SET nombre='$nombre', domicilio='$domicilio', comentario='$comentario', telefono='$telefono', status=$activo, ultima_actualizacion='$fecha' WHERE id=$id";
	$query=$this->db->query($consulta);
	

}


/*TERMINA SECCION DE PROVEEDOR*/
/*INICIA SECCION DE PRODUCTOS*/




public function saveEditProducto(){
	$id=$_POST['txtId'];
	$codigo=$_POST['txtCodigo'];
	$nombre=$_POST['txtNombre'];
	$precio=$_POST['txtPrecio'];
	$iva=$_POST['txtIva'];
	$ieps=$_POST['txtIEPS'];
	if($ieps==""){
		$ieps=(double)"0";
	}
	$clasificacion=$_POST['cmbClasificacion'];
	$tipo=$_POST['txtTipo'];
	$clavesat=$_POST['txtClaveSAT'];
	if(isset($_POST['checkActivo'])){
			$activo=1;
		}
		else{
			$activo=0;
		}
	$proveedor=$_POST['cmbProveedor'];
	date_default_timezone_set('America/Mazatlan');
	$hoy=date('Y-m-d');

		$hora=date('H:i:s',time());
		$fecha=$hoy." ".$hora;
	$proveedor=$_POST['cmbProveedor'];
	$consulta="UPDATE cat_productos SET codigo='$codigo',nombre='$nombre',precio=$precio,iva=$iva,ieps=$ieps,clasificacion=$clasificacion,tipo='$tipo',clavesat='$clavesat',status=$activo,proveedor=$proveedor, ultima_actualizacion='$fecha' WHERE id=$id";
	//echo $consulta;
	$this->db->query($consulta);
}

/*TERMINA SECCION DE PRODUCTOS*/

	/*INICIA SECCION DE CLIENTES*/
public function getJsonListaClientes(){
	
	$usuarios=array();
	$usuariosDatos=array();
	$usuariosDatos['codigo']="Codigo";
	$usuariosDatos['cliente']="AQL";
	$usuariosDatos['domicilio']="Pradera Dorada";
	$usuarios[]=$usuariosDatos;
	$usuariosDatos=array();
	$usuariosDatos['codigo']="Codigo2";
	$usuariosDatos['cliente']="MGAJ";
	$usuariosDatos['domicilio']="Pradera Dorada 2";
	$usuarios[]=$usuariosDatos;
	return $usuarios;
}

public function validarProveedorRuta($id,$ruta){
	$consulta="SELECT id FROM asi_proveedor_ruta WHERE proveedor=$id AND ruta=$ruta AND status=1";
	//echo $consulta;
	$query=$this->db->query($consulta);
	if($query->num_rows()!=0){
		return "SI";
	}
	else{
		return "NO";
	}
}
public function validarProveedorSucursal($id,$sucursal){
	$consulta="SELECT id FROM asi_sucursal_proveedor WHERE proveedor=$id AND sucursal=$sucursal AND status=1";
	//echo $consulta;
	$query=$this->db->query($consulta);
	if($query->num_rows()!=0){
		return "SI";
	}
	else{
		return "NO";
	}
}
public function validarZonaRuta($id,$ruta){
	$consulta="SELECT id FROM asi_ruta_zona WHERE zona=$id AND ruta=$ruta AND status=1";
	//echo $consulta;
	$query=$this->db->query($consulta);
	if($query->num_rows()!=0){
		return "SI";
	}
	else{
		return "NO";
	}
}
public function getActualizarAsSucCli(){
	date_default_timezone_set('America/Mazatlan');
		$hoy=date('Y-m-d');

		$hora=date('H:i:s',time());
		$fechaact=$hoy." ".$hora;
	$consulta="SELECT id,zona FROM clientes";
	$query=$this->db->query($consulta);
	foreach ($query->result() as $k) {
		# code...
		$id=$k->id;
		$zona=$k->zona;
		$consultaX="SELECT id FROM cat_zonas WHERE zona='$zona'";
		$query2=$this->db->query($consultaX);
		foreach ($query2->result() as $k2) {
			# code...
			$idZona=$k2->id;
		}
		//$this->db->insert('asi_cliente_zona',array('cliente'=>$id, 'zona'=>$idZona, 'usuario'=>1, 'fecha'=>$hoy, 'hora'=>$hora));
		$consultaY="UPDATE clientes SET idZona=$idZona, ultima_actualizacion='$fechaact' WHERE id=$id";
		$this->db->query($consultaY);
	}		
}

public function getListaZonas($ciudad){
	date_default_timezone_set('America/Mazatlan');
		$hoy=date('Y-m-d');

		$hora=date('H:i:s',time());
	$consulta="SELECT id,zona FROM cat_zonas WHERE status=1 AND ciudad='$ciudad' ORDER BY zona ASC";
	$query=$this->db->query($consulta);
	return $query;
	
}




public function getZonaName($id){
	$consulta="SELECT zona FROM cat_zonas";
	$query=$this->db->query($consulta);
	foreach ($query->result() as $k) {
		# code...
		$zona=$k->zona;
	}
	return $zona;
}
public function getListaAgentes($id){
	$consulta="SELECT id,nombre FROM usuarios WHERE status=1 AND sucursal=$id";
	$query=$this->db->query($consulta);
	return $query;
}



public function guardarClienteProveedorNuevo($cliente,$proveedores,$usuarioId,$lafecha)
{
	date_default_timezone_set('America/Mazatlan');
	
	$proveedoresA=explode(",", $proveedores);
	$cuantosProv=count($proveedoresA);
	
	for ($i=0; $i < $cuantosProv; $i++) { 
		# code...
			//echo $proveedoresA[$i];
			$this->db->insert('asi_cliente_proveedor',array('cliente'=>$cliente, 'proveedor'=>$proveedoresA[$i], 'status'=>1, 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$lafecha));
	}
}

public function guardarClienteProveedorEditar($cliente,$proveedores,$usuarioId,$lafecha){
		
		$proveedoresA=explode(",", $proveedores);
		$cuantosProv=count($proveedoresA);
	
		$conCliProv="UPDATE asi_cliente_proveedor SET status=0, creadopor=$usuarioId, ultima_actualizacion='$lafecha' WHERE cliente=$cliente";
		//echo "<br> colocar en status 0 asignaciones".$conCliProv;
		$this->db->query($conCliProv);
		if($cuantosProv!=0){
		for ($i=0; $i < $cuantosProv; $i++) { 
			# code...
			 $consultaVer="SELECT id FROM asi_cliente_proveedor WHERE cliente=$cliente AND proveedor=$proveedoresA[$i]";
			// echo "<br>".$consultaVer;
			 $queryVer=$this->db->query($consultaVer);
			 //echo "<br> Rows: ".$queryVer->num_rows();
			 if($queryVer->num_rows()==0){
			 	$this->db->insert('asi_cliente_proveedor',array('cliente'=>$cliente, 'proveedor'=>$proveedoresA[$i], 'status'=>1, 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$lafecha));
			 	//echo "<br>Sera nuevo";
			 }
			 else{
			 	$conCliProv2="UPDATE asi_cliente_proveedor SET status=1, creadopor=$usuarioId, ultima_actualizacion='$lafecha' WHERE cliente=$cliente AND proveedor=$proveedoresA[$i]";
			 	//echo "<br>".$conCliProv2;
			 	$this->db->query($conCliProv2);
			 }
		}
		}

}


public function getActualizarFecha(){
	date_default_timezone_set('America/Mazatlan');
		
	$consulta="SELECT * FROM clientes";
	$query=$this->db->query($consulta);
	foreach ($query->result() as $k) {
		# code...
		$fecha=$k->fechacreacion." ".$k->horacreacion;
		$fecha2=$k->fechaactualizacion." ".$k->horaactualizacion;
		$id=$k->id;
		
		
		$consultaY="UPDATE clientes SET ultima_actualizacion='$fecha2', creado='$fecha' WHERE id=$id";
		$this->db->query($consultaY);
		
	}		
}
public function getActualizarFecha2(){
	date_default_timezone_set('America/Mazatlan');
		
	$consulta="SELECT id,fecha,hora FROM asi_cliente_zona";
	$query=$this->db->query($consulta);
	foreach ($query->result() as $k) {
		# code...
		$fecha=$k->fecha." ".$k->hora;
		$id=$k->id;
		
		
		$consultaY="UPDATE asi_cliente_zona SET ultima_actualizacion='$fecha' WHERE id=$id";
		$this->db->query($consultaY);
	}		
}
	
	
	/*TERMINA SECCION DE CLIENTES*/

		/*INICIA SECCION DE RUTAS*/
	public function getNameChofer($id){
		$consulta="SELECT nombre FROM usuarios WHERE id=$id";
		$query=$this->db->query($consulta);
		if($query->num_rows()!=0){
			return $query->row()->nombre;
		}
		else{
			return "No Asignado";
		}
	}
	
	public function getZonasRuta($id){
		$consulta="SELECT cat_zonas.zona,cat_zonas.id FROM cat_zonas INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=cat_zonas.id WHERE asi_ruta_zona.ruta=$id AND asi_ruta_zona.status=1 ORDER BY cat_zonas.zona ASC";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function getProveedoresRuta($id){
		$consulta="SELECT cat_proveedor.nombre FROM cat_proveedor INNER JOIN asi_proveedor_ruta ON asi_proveedor_ruta.proveedor=cat_proveedor.id WHERE asi_proveedor_ruta.ruta=$id AND asi_proveedor_ruta.status=1 ORDER BY cat_proveedor.nombre ASC";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function getNumeroClientes($id){
		$contador=0;
		$consultaX="SELECT proveedor FROM asi_proveedor_ruta WHERE ruta=$id AND status=1";
		$queryX=$this->db->query($consultaX);
		foreach ($queryX->result() as $ke) {
			# code...
			$consulta="SELECT COUNT(clientes.id) AS clientes FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona INNER JOIN asi_cliente_proveedor ON asi_cliente_proveedor.cliente=clientes.id WHERE asi_ruta_zona.ruta=$id AND asi_cliente_proveedor.proveedor=$ke->proveedor AND asi_ruta_zona.status=1";
				$query=$this->db->query($consulta);
				$contador=$contador+$query->row()->clientes;
		}
		return $contador;
		
	}
	public function getNumeroClientesZona($id){
		$consulta="SELECT COUNT(zona) AS clientes FROM clientes WHERE zona=$id AND status=1";
		$query=$this->db->query($consulta);
		return $query->row()->clientes;
	}

	public function getCoordenadasClientesXXX($zona,$proveedor){
		$cadena=" ";
		if($proveedor==""){
			$consulta="SELECT nombre,latitud,longitud FROM clientes WHERE zona=$zona";
		}
		else{
			$cadena=" ";
			$proveedores=explode(",", $proveedor);
			$nProveedores=count($proveedores);
			for ($i=0; $i < $nProveedores; $i++) { 
				# code...
				if($i==0){
					$cadena.=" AND (asi_cliente_proveedor.proveedor=".$proveedores[$i];
				}
				else{
					$cadena.=" OR asi_cliente_proveedor.proveedor=".$proveedores[$i];
				}
				
			}
			$cadena.=")";
			$consulta="SELECT DISTINCT(clientes.nombre), clientes.latitud, clientes.longitud FROM clientes INNER JOIN asi_cliente_proveedor ON asi_cliente_proveedor.cliente=clientes.id WHERE zona=$zona".$cadena." ORDER BY clientes.nombre ASC";
		}
		$query=$this->db->query($consulta);
		return $query;
		//return $consulta;
	}

	public function getCoordenadasClientes($zona)
	{
		$consulta = "SELECT * FROM clientes WHERE zona=$zona";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getCoordenadasClientes3($zona){
			$arrayProveedor=explode(",", $proveedor);
			$numProveedor=count($arrayProveedor);
			$cadena="";
			for ($i=0; $i < $numProveedor; $i++) { 
				# code...
				if($i==0){
					$cadena="asi_cliente_proveedor.proveedor=".$arrayProveedor[$i];
				}
				else{
					$cadena.=" OR asi_cliente_proveedor.proveedor=".$arrayProveedor[$i];
				}

			}
			$arrayZona=explode(",", $zona);
			$numZona=count($arrayZona);
			$cadenaZ="";
			for ($i=0; $i < $numZona; $i++) { 
				# code...
				if($i==0){
					$cadenaZ="clientes.zona=".$arrayZona[$i];
				}
				else{
					$cadenaZ.=" OR clientes.zona=".$arrayZona[$i];
				}

			}

			if($cadena!=""){				
				$consulta="SELECT DISTINCT(clientes.nombre),clientes.latitud,clientes.longitud,clientes.codigo,clientes.calle,clientes.numero,clientes.colonia,clientes.ciudad FROM clientes INNER JOIN asi_cliente_proveedor ON clientes.id=asi_cliente_proveedor.cliente WHERE (".$cadenaZ.") AND (".$cadena.")";
			}
			else{
				$consulta="SELECT clientes.nombre,clientes.latitud,clientes.longitud,clientes.codigo,clientes.calle,clientes.numero,clientes.colonia,clientes.ciudad FROM clientes INNER JOIN asi_cliente_proveedor ON clientes.id=asi_cliente_proveedor.cliente WHERE clientes.zona=$zona";
			}
			
		
		$query=$this->db->query($consulta);
		return $query;
	}

	public function getCoordenadasClientes2($zona,$proveedor)
	{
		$arrayProveedor=explode(",", $proveedor);
		$numProveedor=count($arrayProveedor);
		$cadena="";
		/*for ($i=0; $i < $numProveedor; $i++) { 
			# code...
			if($i==0){
				$cadena="asi_cliente_proveedor.proveedor=".$arrayProveedor[$i];
			}
			else{
				$cadena.=" OR asi_cliente_proveedor.proveedor=".$arrayProveedor[$i];
			}

		}*/
		$arrayZona=explode(",", $zona);
		$numZona=count($arrayZona);
		$cadenaZ="";
		for ($i=0; $i < $numZona; $i++) {
			if($i==0){
				$cadenaZ="clientes.zona=".$arrayZona[$i];
			}
			else{
				$cadenaZ.=" OR clientes.zona=".$arrayZona[$i];
			}

		}
		if($cadena!=""){
			
			$consulta="SELECT DISTINCT(clientes.nombre),clientes.latitud,clientes.longitud,clientes.codigo,clientes.calle,clientes.numero,clientes.colonia,clientes.ciudad FROM clientes INNER JOIN asi_cliente_proveedor ON clientes.id=asi_cliente_proveedor.cliente WHERE (".$cadenaZ.") AND (".$cadena.")";
		}
		else{
			$consulta="SELECT clientes.nombre,clientes.latitud,clientes.longitud,clientes.codigo,clientes.calle,clientes.numero,clientes.colonia,clientes.ciudad FROM clientes INNER JOIN asi_cliente_proveedor ON clientes.id=asi_cliente_proveedor.cliente WHERE clientes.zona=$zona";
		}
			
		
		$query=$this->dbinfo->query($consulta);
		return $query;
	}

	public function getListaEmpleados($sucursal, $empresa)
	{
		$consulta = "SELECT id,nombre FROM usuarios WHERE vendedor = 1 AND sucursal IN($sucursal) and empresa = '$empresa' ORDER BY nombre ASC";
		$query = $this->db->query($consulta);
		return $query;
	}

	public function getListaRepartidores($sucursal, $empresa)
	{
		$consulta = "SELECT id,nombre FROM usuarios WHERE perfil = 5 AND sucursal IN($sucursal) and empresa = '$empresa' ORDER BY nombre ASC";
		$query = $this->db->query($consulta);
		return $query;
	}

	public function getValoresContrato()
	{
		$consulta = "SELECT * FROM valores_objetivos_contrato";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveObjetivosContrato($datos)
	{
		$this->dbinfo->update("valores_objetivos_contrato", $datos);
	}

	public function getCorreosSellout()
	{
		$consulta = "SELECT * FROM correos_sellout WHERE estatus = 1";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function saveCorreoSellout($datos)
	{
		$correo = $datos["correo"];

		$info_correo = $this->dbinfo->query("SELECT * FROM correos_sellout WHERE correo = '$correo'");

		if($info_correo->num_rows() > 0)
		{
			$estatus = $info_correo->row()->estatus == 1 ? 0 : 1;
			$this->dbinfo->where("correo", $correo);
			$this->dbinfo->update("correos_sellout", array("correo" => $correo, "estatus" => $estatus));
		}
		else
		{
			$this->dbinfo->insert("correos_sellout", array("correo" => $correo));
		}
	}

	public function getTipoPaquetes()
	{
		$consulta = "SELECT * FROM cat_tipo_paquete WHERE estatus = 1";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getMarcas()
	{
		$consulta = "SELECT * FROM cat_marca WHERE estatus = 1";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getUnidadesMedida()
	{
		$consulta = "SELECT * FROM cat_unidad_medida WHERE estatus = 1";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}
	
	public function saveEditRuta($datos){
		date_default_timezone_set('America/Mazatlan');
		$fecha=date('Y-m-d')." ".date('H:i:s',time());
		//$hora=date('H:i:s',time());
		$id=$_POST['txtId'];
		$ruta=$_POST['txtRuta'];
		$chofer=$_POST['cmbAgente'];
		if(isset($_POST['checkActivo'])){
			$activo=1;
		}
		else{
			$activo=0;
		}
		$descripcion=$_POST['txtComentarios'];
		$sucursal=$_POST['cmbSucursal'];
		$usuarioId=$this->session->userdata("userIdLIZER");
		$zonas=$_POST['txtZona'];
		$proveedor=$_POST['txtProveedor'];
		$verificarChofer="SELECT id FROM cat_rutas WHERE chofer=$chofer";
		$queryVerificaChofer=$this->db->query($verificarChofer);
		if($queryVerificaChofer->num_rows()!=0){
			$idRC=$queryVerificaChofer->row()->id;
			$eliminaChofer="UPDATE cat_rutas SET chofer=0, ultima_actualizacion='$fecha' WHERE id=$idRC";
			$this->db->query($eliminaChofer);
			$eliminaChofer2="UPDATE usuarios SET ruta=0, ultima_actualizacion='$fecha' WHERE ruta=$id";
			$this->db->query($eliminaChofer2);
			$eliminaChofer3="UPDATE usuarios SET ruta=0, ultima_actualizacion='$fecha' WHERE id=$chofer";
			$this->dbG->query($eliminaChofer3);
		}
		$consulta="UPDATE cat_rutas SET ruta='$ruta', chofer='$chofer', status=$activo, sucursal=$sucursal, descripcion='$descripcion', creadopor=$usuarioId, ultima_actualizacion='$fecha' WHERE id=$id";
		$this->db->query($consulta);
		$consulta2="UPDATE usuarios SET ruta='$id', ultima_actualizacion='$fecha' WHERE id=$chofer";
		//echo $consulta2;
		$this->db->query($consulta2);
		$consulta2="UPDATE usuarios SET ruta='$id', ultima_actualizacion='$fecha' WHERE id=$chofer";
		//echo $consulta2;
		$this->dbG->query($consulta2);
		
		 $idRuta=$id;
		$this->db->query("UPDATE asi_proveedor_ruta SET status=0, ultima_actualizacion='$fecha', creadopor=$usuarioId WHERE ruta=$id");
		if($proveedor!=""){
	 		$arregloProveedor=explode(",", $proveedor);
			 $cuantosProveedor=count($arregloProveedor);
			//echo $cuantosProveedor;
			 for ($i=0; $i < $cuantosProveedor; $i++) { 
			 		//echo "<br>No. ".$i;
			 		$consultaR="SELECT id FROM asi_proveedor_ruta WHERE ruta='$id' AND proveedor='$arregloProveedor[$i]'";
			 		//echo "<br>".$consultaR;
			 		$queryR=$this->db->query($consultaR);
			 		if($queryR->num_rows()!=0){
			 			$idRZ=$queryR->row()->id;
			 			$consultaUR="UPDATE asi_proveedor_ruta SET ruta=$idRuta, proveedor=$arregloProveedor[$i], status=1, creadopor=$usuarioId, ultima_actualizacion='$fecha' WHERE id=$idRZ";
			 			$this->db->query($consultaUR);
			 		}
			 		else{
			 			$this->db->insert('asi_proveedor_ruta',array('ruta'=>$idRuta, 'proveedor'=>$arregloProveedor[$i], 'status'=>1, 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$fecha));		
			 		}
					
			 	
			 }
		}
		 $this->db->query("UPDATE asi_ruta_zona SET status=0, ultima_actualizacion='$fecha', creadopor=$usuarioId WHERE ruta=$id");
		  if($zonas!=""){
		  $arregloZona=explode(",", $zonas);
		 $cuantasZona=count($arregloZona);
		  for ($i=0; $i < $cuantasZona; $i++) { 
		 		$consultaR="SELECT id FROM asi_ruta_zona WHERE ruta='$id' AND zona='$arregloZona[$i]'";
		 		//echo $consultaR;
		 		$queryR=$this->db->query($consultaR);
		 		if($queryR->num_rows()!=0){
		 			$idRZ=$queryR->row()->id;
		 			$consultaUR="UPDATE asi_ruta_zona SET ruta=$idRuta, zona=$arregloZona[$i], status=1, creadopor=$usuarioId, ultima_actualizacion='$fecha' WHERE id=$idRZ";
		 			$this->db->query($consultaUR);
		 		}
		 		else{
		 			$this->db->insert('asi_ruta_zona',array('ruta'=>$idRuta, 'zona'=>$arregloZona[$i], 'status'=>1, 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$fecha));		
		 		}
				
		 	
		 }
		}
		
	}
	
	public function getVerificaCliente($idC,$proveedores){
		$bandera=false;
		$array=explode($proveedores);
		$narray=count($array);
		for ($i=0; $i < $narray; $i++) { 
			# code...
			$idP=$arra[$i];
			$consulta="SELECT id FROM asi_cliente_proveedor WHERE cliente=$idC AND proveedor=$idP";
			$query=$this->db->query($consulta);
			if($query->num_rows()!=0){
				$bandera=true;
			}
		}
		
		
		return $bandera;
	}


	/*TERMINA SECCION DE RUTAS*/
	/*Inicia seccion de usuarios*/
	
		public function getNombrePerfil($id){
		$consulta="SELECT perfil FROM perfiles WHERE id=$id";
		$query=$this->db->query($consulta);
		return $query;
	}

	
	public function liberarEquipo($id)
	{				
		$consulta = "UPDATE usuarios SET celular=0, ultima_actualizacion='CURRENT_TIMESTAMP()' WHERE id=$id";
		$this->db->query($consulta);		
		return "1";
	}
	
	public function getDatosUsuario($id){
		$consulta="SELECT * FROM usuarios WHERE id = '$id'";
		$query=$this->db->query($consulta);
		return $query;
	}
	/*Termina seccion de usuarios*/
	/*Inicia seccion de zonas*/
	
	
	public function getRutasZona($id){
		$consulta="SELECT cat_rutas.ruta FROM cat_rutas INNER JOIN asi_ruta_zona ON asi_ruta_zona.ruta=cat_rutas.id WHERE asi_ruta_zona.zona=$id";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function getListaPoligonos($id){
		$consulta="SELECT * FROM poligonos WHERE status=1";
		$query=$this->db->query($consulta);
		return $query;
	}
	/*Termina seccion de usuarios*/
 	/*inicia seccion de sucursales*/
 	public function getNombreSucursal2($id){
 		$consulta="SELECT sucursal FROM cat_sucursales WHERE id=$id";
 		$query=$this->db->query($consulta);
 		return $query->row()->sucursal;
 	}
 	
 	
 	
 	public function saveEditSucursal(){
		$usuarioId=$this->session->userdata('userIdLIZER');
		date_default_timezone_set('America/Mazatlan');
		$hoy=date('Y-m-d');

		$hora=date('H:i:s',time());
		$fecha=$hoy." ".$hora;
		$id=$_POST['txtId'];
	$clave=$_POST['txtClave'];
	$sucursal=$_POST['txtSucursal'];
	$direccion=$_POST['txtDireccion'];
	$ciudad=$_POST['txtCiudad'];
	$descripcion=$_POST['txtDescripcion'];
	$proveedor=$_POST['txtProveedor'];
	if(isset($_POST['checkActivo'])){
			$activo=1;
		}
		else{
			$activo=0;
		}
	
	$consulta="UPDATE cat_sucursales SET clave='$clave', status=$activo,  sucursal='$sucursal', direccion='$direccion', ciudad='$ciudad', descripcion='$descripcion' WHERE id=$id";
	$this->db->query($consulta);
	
				$this->db->query("UPDATE asi_sucursal_proveedor SET status=0, creadopor=$usuarioId, ultima_actualizacion='$fecha' WHERE sucursal=$id");
		if($proveedor!=""){
			$usuarioId=$this->session->userdata('userIdLIZER');
	 		$arregloProveedor=explode(",", $proveedor);
			 $cuantosProveedor=count($arregloProveedor);
			//echo $cuantosProveedor;
			 for ($i=0; $i < $cuantosProveedor; $i++) { 
			 		//echo "<br>No. ".$i;
			 		$consultaR="SELECT id FROM asi_sucursal_proveedor WHERE sucursal='$id' AND proveedor='$arregloProveedor[$i]'";
			 		//echo "<br>".$consultaR;
			 		$queryR=$this->db->query($consultaR);
			 		if($queryR->num_rows()!=0){
			 			$idRZ=$queryR->row()->id;
			 			$consultaUR="UPDATE asi_sucursal_proveedor SET sucursal=$id, proveedor=$arregloProveedor[$i], status=1, creadopor=$usuarioId, ultima_actualizacion='$fecha' WHERE id=$idRZ";
			 			$this->db->query($consultaUR);
			 		}
			 		else{
			 			$this->db->insert('asi_proveedor_ruta',array('ruta'=>$idRuta, 'proveedor'=>$arregloProveedor[$i], 'status'=>1, 'creadopor'=>$usuarioId, 'ultima_actualizacion'=>$fecha));		
			 		}
					
			 	
				 }
			}
	}

 	
 	/*termina seccion de sucursales*/
	/*Inician funciones generales*/
	public function getListaPerfiles(){
		$consulta="SELECT perfil FROM perfiles WHERE id=$id ORDER BY perfil ASC";
		$query=$this->db->query($consulta);
		return $query;
	}	

	public function getListaPerfilesX()
	{
		$consulta="SELECT id,perfil FROM perfiles WHERE status=1 ORDER BY perfil ASC";
		$query=$this->db->query($consulta);
		return $query;
	}

	public function sincronizarClientes($productos)
	{
		$insertados = 0;
		$actualizados = 0;

		foreach ($productos as $producto)
		{
			// Campo que identifica al producto
			$codigo = $producto["Codigo"];

			// Buscar si existe
			$sql = "SELECT id FROM clientes WHERE codigo = ?";
			$query = $this->dbinfo->query($sql, [$codigo]);

			$dias = [
				'domingo'   => 1,
				'lunes'     => 2,
				'martes'    => 3,
				'miercoles' => 4,
				'miércoles' => 4,
				'jueves'    => 5,
				'viernes'   => 6,
				'sabado'    => 7,
				'sábado'    => 7
			];
			

			if($producto["clasificacion"] == "")
			{
				$producto["clasificacion"] = 0;
			}
			else
			{
				$producto["clasificacion"] = $this->dbinfo->query("select * from cat_clasificacion_cliente where clasificacion = '".$producto["clasificacion"]."'")->row()->id;
			}

			$producto["zona"] = $this->getZonaByZona($producto["Zona"])->row()->id;
			$producto["sucursal"] = $this->dbinfo->query("select * from cat_sucursales where sucursal = '".$producto["Sucursal"]."'")->row()->id;
			//$producto["proveedor"] = $this->dbinfo->query("select * from cat_proveedor where nombre = '".$producto["proveedor"]."'")->row()->id;

			$listaDias = array_map('trim', explode(',', strtolower($producto['diasvisita'])));
			$numeros = array_map(function($dia) use ($dias) {
				return isset($dias[$dia]) ? $dias[$dia] : 0;
			}, $listaDias);
			$producto['diasvisita'] = implode(',', $numeros);

			//$producto["fechacreacion"] = date("Y-m-d") . " " . date("H:i:s");
			$producto["ultima_actualizacion"] = date("Y-m-d") . " " . date("H:i:s");
			$producto["direccion"] = $producto["Domicilio"];
			$producto["ciudad"] = $producto["localidad"];
			$producto["codigo"] = $producto["Codigo"];
			$producto["nombre"] = $producto["Cliente"];
			$producto["subidobees"] = "0";
			$producto["status"] = "1";

			unset($producto["Sucursal"]);
			unset($producto["Zona"]);
			unset($producto["Domicilio"]);
			unset($producto["localidad"]);
			unset($producto["Activo"]);
			unset($producto["clientedigitalizado"]);
			unset($producto["Codigo"]);
			unset($producto["Cliente"]);
			unset($producto["Proveedores"]);

			/*echo "<pre>";
			print_r($producto);	
			echo "</pre>";
			die();*/

			if ($query->num_rows() > 0)
			{
				// UPDATE
				$this->dbinfo->where("codigo", $codigo);
				$this->dbinfo->update("clientes", $producto);

				$actualizados++;
			}
			else
			{
				// INSERT
				$this->dbinfo->insert("clientes", $producto);

				$insertados++;
			}
		}

		return [
			"insertados" => $insertados,
			"actualizados" => $actualizados
		];
	}

	public function sincronizarProductos($productos)
	{
		$insertados = 0;
		$actualizados = 0;

		foreach ($productos as $producto)
		{
			// Campo que identifica al producto
			$codigo = $producto["codigo"];

			// Buscar si existe
			$sql = "SELECT id FROM cat_productos WHERE codigo = ?";
			$query = $this->dbinfo->query($sql, [$codigo]);

			/*$dias = [
				'domingo'   => 1,
				'lunes'     => 2,
				'martes'    => 3,
				'miercoles' => 4,
				'miércoles' => 4,
				'jueves'    => 5,
				'viernes'   => 6,
				'sabado'    => 7,
				'sábado'    => 7
			];			

			

			if($producto["clasificacion"] == "")
			{
				$producto["clasificacion"] = 0;
			}
			else
			{
				$producto["clasificacion"] = $this->dbinfo->query("select * from cat_clasificacion_cliente where clasificacion = '".$producto["clasificacion"]."'")->row()->id;
			}

			$producto["zona"] = $this->getZonaByZona($producto["Zona"])->row()->id;
			$producto["sucursal"] = $this->dbinfo->query("select * from cat_sucursales where sucursal = '".$producto["Sucursal"]."'")->row()->id;
			//$producto["proveedor"] = $this->dbinfo->query("select * from cat_proveedor where nombre = '".$producto["proveedor"]."'")->row()->id;			
			$producto["diasvisita"] = isset($dias[strtolower(trim($producto["diasvisita"]))])? $dias[strtolower(trim($producto["diasvisita"]))]: 0;

			$producto["fechacreacion"] = date("Y-m-d") . " " . date("H:i:s");
			$producto["ultima_actualizacion"] = date("Y-m-d") . " " . date("H:i:s");

			unset($producto["Sucursal"]);
			unset($producto["Zona"]);

			/*echo "<pre>";
			print_r($producto);	
			echo "</pre>";
			die();*/

			if ($query->num_rows() > 0)
			{
				// UPDATE
				$this->dbinfo->where("codigo", $codigo);
				$this->dbinfo->update("cat_productos", $producto);

				$actualizados++;
			}
			else
			{
				// INSERT
				$this->dbinfo->insert("cat_productos", $producto);

				$insertados++;
			}
		}

		return [
			"insertados" => $insertados,
			"actualizados" => $actualizados
		];
	}

	public function importarAcumulados($productos)
	{
		$insertados = 0;
		$actualizados = 0;

		foreach ($productos as $producto)
		{
			// Campo que identifica al producto
			$rutanombre = $producto["ruta"];
			$categorianombre = $producto["categoria"];
			$periodo = $producto["periodo"];

			$inforuta = $this->dbinfo->query("SELECT * FROM cat_rutas WHERE ruta = '$rutanombre'")->row();
			$infocategoria = $this->dbinfo->query("SELECT * FROM cat_clasificacionproductos WHERE nombre = '$categorianombre'")->row();

			$producto["idruta"] = $inforuta->id;
			$producto["idcategoria"] = $infocategoria->id;
			$producto["idVendedor"] = $inforuta->chofer;

			// Buscar si existe
			$sql = "SELECT t.id FROM acumulados_categorias t WHERE t.`idruta` = ? AND t.`idcategoria` = ? AND t.`periodo` = ?";
			$query = $this->dbinfo->query($sql, [$producto["idruta"], $producto["idcategoria"], $producto["periodo"]]);

			$producto["mes"] = str_pad($producto["mes"], 2, '0', STR_PAD_LEFT);
			$producto["fecha"] = DateTime::createFromFormat('d/m/Y', $producto["fecha"])->format('Y-m-d');
			$producto["idusuario_registro"] = GETIDUSUARIO();
			$producto["fechahora_registro"] = date("Y-m-d") . " " . date("H:i:s");

			unset($producto["ruta"]);

			/*echo "<pre>";
			print_r($producto);
			echo "</pre>";*/

			if ($query->num_rows() > 0)
			{
				// UPDATE
				$this->dbinfo->where("idruta", $producto["idruta"]);
				$this->dbinfo->where("idcategoria", $producto["idcategoria"]);
				$this->dbinfo->where("periodo", $producto["periodo"]);
				$this->dbinfo->update("acumulados_categorias", $producto);

				$actualizados++;
			}
			else
			{
				// INSERT
				$this->dbinfo->insert("acumulados_categorias", $producto);

				$insertados++;
			}
		}

		//return "todo bien";

		return [
			"insertados" => $insertados,
			"actualizados" => $actualizados
		];
	}

	public function importarVentas($productos)
	{
		$insertados = 0;
		$actualizados = 0;

		$contador = 0;

		foreach ($productos as $producto)
		{
			// Campo que identifica al producto
			$rutanombre = $producto["ruta"];
			//$categorianombre = $producto["categoria"];
			//$periodo = $producto["periodo"];

			
			$inforuta = $this->dbinfo->query("SELECT * FROM cat_rutas WHERE ruta = '$rutanombre'")->row();
			//$infocategoria = $this->dbinfo->query("SELECT * FROM cat_clasificacionproductos WHERE nombre = '$categorianombre'")->row();
			$infousuario = $this->db->query("SELECT * FROM usuarios WHERE id = '".$inforuta->chofer."'")->row();
			$infocliente = $this->dbinfo->query("SELECT * FROM clientes WHERE codigo = '".$producto["codigocliente"]."'")->row();
			$infosucursal = $this->dbinfo->query("SELECT * FROM cat_sucursales WHERE sucursal = '".$producto["sucursal"]."'")->row();

			$producto["idusuario"] = $infousuario->id;
			$producto["usuario"] = $infousuario->usuario;
			//$producto["idcategoria"] = $infocategoria->id;
			//$producto["idVendedor"] = $inforuta->chofer;
			$producto["idcliente"] = $infocliente->id;
			$producto["cliente"] = $infocliente->nombre;

			$producto["fecha"] = DateTime::createFromFormat('d/m/Y', $producto["fecha"])->format('Y-m-d');
			$producto["tipo"] = strtoupper($producto["tipo"]);
			$producto["facturado"] = "0";
			$producto["credito"] = "0";
			$producto["ruta"] = $inforuta->id;
			$producto["idsucursal"] = $infosucursal->id;
			$producto["fechacreacion"] = date('Y-m-d H:i:s', strtotime($producto["fecha"] . " 00:00:00 +{$contador} seconds"));
			$producto["fecharegistro"] = $producto["fecha"] . " " . date("H:i:s");
			$producto["canal"] = "NON-BEES";
			$producto["estatusbees"] = 'DELIVERED';

			unset($producto["sucursal"]);

			// Buscar si existe
			$sql = "SELECT t.id FROM pedidos t WHERE t.folio = ?";
			$query = $this->dbinfo->query($sql, [$producto["folio"]]);

			/*echo "<pre>";
			print_r($producto);
			echo "</pre>";*/

			if ($query->num_rows() > 0)
			{
				// UPDATE

				$this->dbinfo->where("folio", $producto["folio"]);
				$this->dbinfo->update("pedidos", $producto);

				$actualizados++;
			}
			else
			{
				// INSERT
				
				$this->dbinfo->insert("pedidos", $producto);

				$insertados++;
			}

			$contador++;
		}

		//return "todo bien";

		return [
			"insertados" => $insertados,
			"actualizados" => $actualizados
		];
	}

	public function importarVentasDetalle($productos)
	{
		$insertados = 0;
		$actualizados = 0;

		foreach ($productos as $producto)
		{
			$categorianombre = $producto["clasificacion"];
			$codigoproducto = $producto["codigoproducto"];

			$infocategoria = $this->dbinfo->query("SELECT * FROM cat_clasificacionproductos WHERE nombre = '$categorianombre'")->row();
			$infoproducto = $this->dbinfo->query("SELECT * FROM cat_productos WHERE codigo = '$codigoproducto'")->row();
			$infopedido = $this->dbinfo->query("SELECT * FROM pedidos WHERE folio = '".$producto["folio"]."'")->row();

			$producto["idpedido"] = $infopedido->id;
			$producto["iditem"] = $infoproducto->id;
			$producto["idclasificacion"] = $infocategoria->id;
			$producto["idproveedor"] = $infoproducto->proveedor;
			$producto["producto"] = $infoproducto->nombre;
			$producto["cantidadoriginal"] = $producto["cantidad"];
			$producto["cantidad_entregado"] = $producto["cantidad"];
			$producto["cantidad_rechazado"] = "0";
			$producto["preciooriginal"] = "0";
			$producto["costo"] = $infoproducto->costo;
			$producto["iva"] = $infoproducto->iva;
			$producto["ieps"] = $infoproducto->ieps;
			
			$producto["fechacreacion"] = $infopedido->fechacreacion;
			$producto["fecha_registro"] = $infopedido->fecharegistro;

			unset($producto["clasificacion"]);
			unset($producto["folio"]);

			// Buscar si existe
			$sql = "SELECT t.id FROM pedidos_detalle t WHERE t.idpedido = ? AND t.iditem = ?";
			$query = $this->dbinfo->query($sql, [$infopedido->id, $producto["iditem"]]);

			/*echo "<pre>";
			print_r($producto);
			echo "</pre>";*/

			if ($query->num_rows() > 0)
			{
				// UPDATE
				$this->dbinfo->where("idpedido", $infopedido->id);
				$this->dbinfo->where("iditem", $producto["iditem"]);
				$this->dbinfo->update("pedidos_detalle", $producto);

				$actualizados++;
			}
			else
			{
				// INSERT
				$this->dbinfo->insert("pedidos_detalle", $producto);

				$insertados++;
			}
		}

		//return "todo bien";

		return [
			"insertados" => $insertados,
			"actualizados" => $actualizados
		];
	}
	
	/*Terminan funciones generales*/ 
}
?>