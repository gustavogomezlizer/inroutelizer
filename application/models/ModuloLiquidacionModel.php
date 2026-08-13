<?php
class ModuloLiquidacionModel extends CI_Model {

	private $dbinfo;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();		
	}

	public function getPedidosJ($fIni,$fFin,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT * FROM pedidos WHERE status=1 AND fecha BETWEEN '$fIni' AND '$fFin'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getItemsJ($idpedido,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		/*$consulta = "SELECT id, codigoproducto AS product_code, iditem AS product_id, producto AS product_description, precio AS price,
		cantidad AS quantity, importe AS total, NULL AS comments 
		FROM pedidos_detalle 
		WHERE idpedido=$idpedido";*/
		$consulta = "SELECT pedidos_detalle.* 
		FROM pedidos_detalle 
		WHERE idpedido=$idpedido";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getClienteJ($id,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT * FROM clientes WHERE id=$id";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getCreadoporJ($idusuario)
	{
		$consulta="SELECT * FROM usuarios WHERE id=$idusuario";
		$query=$this->db->query($consulta);
		return $query;
	}

	public function getProductosJ($empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		//$consulta = "SELECT * FROM cat_productos WHERE status=1";
		//$consulta = "SELECT * FROM cat_productos";
		$consulta = "SELECT *,
		(SELECT cat_clasificacionproductos.`id` FROM cat_clasificacionproductos WHERE cat_clasificacionproductos.id = cat_productos.`clasificacion`) AS idcategoria,
		(SELECT cat_clasificacionproductos.`nombre` FROM cat_clasificacionproductos WHERE cat_clasificacionproductos.id = cat_productos.`clasificacion`) AS categoria
		FROM cat_productos";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getCategoriasProductosJ($id,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT * FROM cat_clasificacionproductos WHERE id=$id";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getClientesJ($empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		//$consulta = "SELECT *, ' ' as creadopor FROM clientes";
		$consulta = "SELECT clientes.*, ' ' AS creadopor
		FROM clientes
		INNER JOIN pedidos ON clientes.`id` = pedidos.`idcliente` AND pedidos.`fecha` BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND CURDATE()
		GROUP BY pedidos.`idcliente`";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getZonasJ($id,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT * FROM cat_zonas WHERE id = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getRutaUsuario($id,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT * FROM cat_rutas WHERE chofer = '$id'";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getUsuariosJ($empresa)
	{
		$query = $this->db->query("SELECT * FROM usuarios WHERE empresa='$empresa'");
		return $query;
	}

	public function getNombreSucursal($idsucursal, $dato, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT $dato as dato FROM cat_sucursales WHERE id = '$idsucursal'";
		$query = $this->dbinfo->query($consulta);
		if($query->num_rows() > 0){
			return $query->row()->dato;
		}else{
			return "SIN DATO";
		}		
	}

	public function getAgregarAcumulados($cadena,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

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

		/*$consulta2x="UPDATE prupru SET cadena='$xxx' WHERE id=1";
		$this->dbinfo->query($consulta2x);*/
		
		foreach ($products['Acumulados'] as $lVend)
		{
			//echo "<br>IdVendedor: ".$lVend['IdVendedor'];
			
			$idVendedor=$lVend['IdVendedor'];
		
			foreach ($lVend['AcumCat'] as $lAcum)
			{
				$categoria = $lAcum['Categoria'];
				$acumulado = $lAcum['Importe'];
				$ventas = isset($lAcum['Ventas']) ? $lAcum['Ventas'] : 0;

				//echo "<br>--- Categoria: ".$lAcum['Categoria']." Importe".$lAcum['Importe']."<br>";
				$consulta="SELECT id AS cuantos FROM acumulados_categorias WHERE idVendedor=$idVendedor AND periodo='$periodo' AND categoria='$categoria'";
			
				
				//echo $consulta;
				$res=$this->dbinfo->query($consulta);
				//print_r($res);
				if($res->num_rows()!=0)
				{
					$this->dbinfo->where(array(
						"idVendedor" => $idVendedor,
						"periodo" => $periodo,
						"categoria" => $categoria,
					));
					$this->dbinfo->update('acumulados_categorias', array(
						"importe" => $acumulado,
						"fecha" => $fecha,
						"periodo" => $periodo,
						"diasMes" => $diasMes,
						"diasTranscurridos" => $diasTranscurridos,
						"ventas" => $ventas,
					));

					//$consulta2="CALL actualizarAcumulados('".$fecha."','".$idVendedor."','".$categoria."','".$acumulado."','".$mes."','".$periodo."','".$diasMes."','".$diasTranscurridos."','".$ventas."')";
				}
				else
				{
					$this->dbinfo->insert('acumulados_categorias', array(
						"fecha" => $fecha,
						"idVendedor" => $idVendedor,
						"categoria" => $categoria,
						"importe" => $acumulado,
						"mes" => $mes,
						"periodo" => $periodo,
						"diasMes" => $diasMes,
						"diasTranscurridos" => $diasTranscurridos,
						"ventas" => $ventas,
					));
					//$consulta2="CALL agregarAcumulados('".$fecha."','".$idVendedor."','".$categoria."','".$acumulado."','".$mes."','".$periodo."','".$diasMes."','".$diasTranscurridos."','".$ventas."')";
				}

				//$this->dbinfo->query($consulta2);

				//$consulta3="CALL actualizaPeriodo('".$periodo."','".$fecha."',".$diasMes.",".$diasTranscurridos.")";
				//$this->dbinfo->query($consulta3);
			}
		}
	}

	public function getAgregarObjetivos($cadena,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);
		
		$objetivosT = json_decode($cadena,true);

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
					$consulta2="CALL actualizarObjetivos('".$idVendedor."','".$categoria."','".$importe."','".$mes."','".$periodo."','".$fecha."')";
				}
				else
				{
					$consulta2="CALL agregarObjetivos('".$idVendedor."','".$categoria."','".$importe."','".$mes."','".$periodo."','".$fecha."')";
				}

			   	$this->dbinfo->query($consulta2);
			}
		}
	}	

	public function getPedidos($data,$empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$this->dbinfo->select("*");
		$this->dbinfo->from('viewGetPedidos');		
		if($data['fechade']!=""){
			$this->dbinfo->where('fecha >=', $data['fechade']);
			$this->dbinfo->where('fecha <=', $data['fechaa']);
		}
		if($data["tipo"]!="0") $this->dbinfo->where('tipo', $data["tipo"]);
		if($data["sucursal"]!="0") $this->dbinfo->where('idsucursal', $data["sucursal"]);
		if($data["ruta"]!="0") $this->dbinfo->where('ruta', $data["ruta"]);
		if($data["usuario"]!="0") $this->dbinfo->where('idusuario', $data["usuario"]);
		
		$query = $this->dbinfo->get();
		//die($this->dbinfo->last_query());
		return $query;
	}

	public function getClientesByFecha($fecha, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$query = $this->dbinfo->query("SELECT c.*
		FROM pedidos p
		INNER JOIN clientes c ON p.`idcliente` = c.`id`
		WHERE p.`fecha` = '$fecha'");
		return $query;
	}

	public function getAcumuladosObjetivos($empresa, $periodo)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$usuarios = $this->db->query("SELECT u.id, u.`usuario`, u.`nombre`, u.`ruta` 
		FROM usuarios u
		WHERE STATUS = 1 AND vendedor = 1 AND perfil = 4 AND empresa = '$empresa'")->result_array();

		foreach($usuarios as $key => $value)
		{
			$idusuario = $usuarios[$key]["id"];

			$acumulado = $this->dbinfo->query("SELECT ccp.`id` AS categoria_id, ccp.`nombre` AS categoria_nombre, ac.*
			FROM cat_clasificacionproductos ccp 
			LEFT JOIN acumulados_categorias ac ON ccp.`id` = ac.`idcategoria` AND ac.`periodo` = '$periodo' AND ac.`idVendedor` = '$idusuario'
			WHERE ccp.`status` = 1 ORDER BY ccp.nombre")->result_array();

			$info_ruta = $this->dbinfo->query("SELECT * FROM cat_rutas WHERE chofer = '$idusuario'");

			$ruta_id = "0";
			$ruta_nombre = "";

			if($info_ruta->num_rows() > 0)
			{
				$ruta_id = $info_ruta->row()->id;
				$ruta_nombre = $info_ruta->row()->ruta;
			}

			/*foreach($acumulado as $key_acumulado => $value_acumulado)
			{
				$acumulado[$key_acumulado]["usuario_id"] = $idusuario;
				$acumulado[$key_acumulado]["usuario"] = $usuarios[$key]["usuario"];
				$acumulado[$key_acumulado]["usuario_nombre"] = $usuarios[$key]["nombre"];
				$acumulado[$key_acumulado]["ruta_id"] = $ruta_id;
				$acumulado[$key_acumulado]["ruta_nombre"] = $ruta_nombre;
			}*/

			$usuarios[$key]["ruta_id"] = $ruta_id;
			$usuarios[$key]["ruta_nombre"] = $ruta_nombre;
			$usuarios[$key]["acumulado"] = $acumulado;
		}

		return $usuarios;
	}

	public function saveCategoriasObjetivosAcumulado($empresa, $json)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$acumulado = json_decode($json, true);
		
		foreach($acumulado as $item)
		{
			$datos = array(
				"fecha" => $item["fecha"],
				"idruta" => $item["idruta"],
				"idVendedor" => $item["idVendedor"],
				"idcategoria" => $item["idcategoria"],
				"categoria" => $item["categoria"],
				"importe" => $item["importe"],
				"mes" => $item["mes"],
				"periodo" => $item["periodo"],
				"objetivo" => $item["objetivo"],
				"ventas" => $item["ventas"],
				"diasMes" => $item["diasMes"],
				"diasTranscurridos" => $item["diasTranscurridos"],
			);

			$acumulado_existe = $this->dbinfo->query("SELECT * FROM acumulados_categorias WHERE idVendedor = '$datos[idVendedor]' AND periodo = '$datos[periodo]' AND idcategoria = '$datos[idcategoria]'");

			if($acumulado_existe->num_rows() > 0)
			{
				$id = $acumulado_existe->row()->id;
				$this->dbinfo->where("id", $id);
				$this->dbinfo->update("acumulados_categorias", $datos);
			}
			else
			{
				$id = $this->dbinfo->insert("acumulados_categorias", $datos);
			}
		}

		return $id;
	}

	public function getCategorias($empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$query = $this->dbinfo->query("SELECT ccp.*, tc.`pago`, tc.`minimo`, tc.`maximo`
		FROM cat_clasificacionproductos ccp
		INNER JOIN tabulador_categorias tc ON ccp.`id` = tc.`idcategoria` AND tc.`idsucursal` = 1
		WHERE ccp.`status` = 1
		ORDER BY ccp.`nombre`");
		return $query;
	}

	public function saveCategoriasValidas($empresa, $json)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$categorias = json_decode($json, true);
		
		foreach($categorias as $item)
		{
			$datos = array(
				"id" => $item["idcategoria"],
				"valida_acumulado" => $item["valida"],
			);

			$this->dbinfo->where("id", $datos["id"]);
			$this->dbinfo->update("cat_clasificacionproductos", $datos);

			$datos_tabulador = array(
				"pago" => $item["pago"],
				"minimo" => $item["minimo"],
				"maximo" => $item["maximo"],
			);

			$this->dbinfo->where("idcategoria", $item["idcategoria"]);
			$this->dbinfo->where("idsucursal", "1");
			$this->dbinfo->update("tabulador_categorias", $datos_tabulador);
		}

		return "1";
	}

	public function getInfoEmpresa($empresa)
	{
		$consulta = "SELECT * FROM empresas WHERE idCliente = '$empresa'";
		$query = $this->db->query($consulta);
		return $query;
	}

	public function postLogin($post)
	{
		$query = $this->db->query("SELECT * FROM usuarios WHERE empresa = '$post[idcliente]' AND usuario = '$post[usuario]' AND clave = '$post[password]'");
		return $query;
	}

	public function saveFinalizarArmadoRuta($post)
	{
		$config_app = switch_db_dinamico($post["empresa"], 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$this->dbinfo->where("clavemodulo", $post["clavemodulo"]);
		$this->dbinfo->update("cat_sucursales", array("fecha_corte" => $post["fecha_corte"], "usuario_corte" => $post["usuario_corte"]));

		return "1";
	}

	public function listaPedidosByFecha($post, $pTipo)
	{
		$config_app = switch_db_dinamico($post["empresa"], 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		if($pTipo == "PRINCIPAL")
		{
			$query = $this->dbinfo->query("SELECT p.*,
			(SELECT cs.sucursal FROM cat_sucursales cs WHERE cs.id = p.`idsucursal`) AS sucursal_nombre,
			(SELECT cr.ruta FROM cat_rutas cr WHERE cr.id = p.`ruta`) AS ruta_nombre
			FROM pedidos p
			WHERE p.`fecha` = '$post[fecha]' AND p.`status` = 1");

			$pedidos = $query->result();

			foreach($pedidos as $key => $value)
			{
				$vendedor = $this->db->query("SELECT * FROM usuarios WHERE id = '$value->idusuario'")->row()->nombre;
				$pedidos[$key]->vendedor = $vendedor;
			}

			return $pedidos;
		}
		else if($pTipo == "DETALLE")
		{
			$query = $this->dbinfo->query("SELECT pd.*, p.folio
			FROM pedidos p
			INNER JOIN pedidos_detalle pd ON p.`id` = pd.`idpedido`
			WHERE p.`fecha` = '$post[fecha]' AND p.`status` = 1");

			return $query->result();
		}		
	}
}
?>