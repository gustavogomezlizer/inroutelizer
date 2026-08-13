<?php
class EstadisticasModel extends CI_Model {

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

	public function getObjetivosAcumulados($data)
	{
		/*$consulta = "SELECT * FROM acumulados_categorias WHERE periodo='$periodo'";
		$resultado = $this->dbinfo->query($consulta);*/

		$this->dbinfo->select("acumulados_categorias.*, ((importe/objetivo) * 100) as alcance, ((importe/diasTranscurridos)*(diasMes)) AS venta2,
		( (((SELECT venta2)) / objetivo) * 100 ) AS alcance2, (objetivo - importe) AS gap, COALESCE( (SELECT gap) / (diasMes-diasTranscurridos), 0) AS objetivo_diario,
		CONCAT('$', FORMAT(objetivo, 2)) as objetivo_format,
		CONCAT('$', FORMAT(importe, 2)) as importe_format,
		CONCAT(TRUNCATE((SELECT alcance),2), '%') AS alcance_format, 
		CONCAT('$', FORMAT((SELECT venta2), 2)) as venta2_format,
		CONCAT(TRUNCATE((SELECT alcance2), 2), '%') AS alcance2_format,
		CONCAT('$', FORMAT((SELECT gap), 2)) as gap_format,
		CONCAT('$', FORMAT((SELECT objetivo_diario), 2)) as objetivo_diario_format,
		FORMAT((ventas/diasTranscurridos),0) AS promedio_ventas,
		'0' AS pago_pedidos,
		(SELECT sucursal FROM cat_rutas WHERE cat_rutas.`chofer` = idVendedor) AS idsucursal_vendedor,
		COALESCE((SELECT pago FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = acumulados_categorias.`categoria`), 0) AS tabulador_pedido,
		COALESCE((SELECT minimo FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = acumulados_categorias.`categoria`), 0) AS tabulador_pedido_minimo,
		COALESCE((SELECT maximo FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = acumulados_categorias.`categoria`), 0) AS tabulador_pedido_maximo,
		COALESCE( 
			IF( (SELECT alcance2) < (SELECT tabulador_pedido_minimo),
				0, 
				IF((SELECT alcance2) > (SELECT tabulador_pedido_maximo), 
					(SELECT tabulador_pedido_maximo), 
					(SELECT alcance2)
				)
			)
		,0) AS porcentaje_categoria_pago,
		COALESCE( CONCAT('$', FORMAT((SELECT tabulador_pedido)*( (SELECT porcentaje_categoria_pago) / 100 ),2))  ,0) AS pago_categoria
		");
		$this->dbinfo->from('acumulados_categorias');
		$this->dbinfo->where('acumulados_categorias.periodo', $data["periodo"]);
		if($data["categoria"]!="0") $this->dbinfo->where('acumulados_categorias.categoria', $data["categoria"]);

		$this->dbinfo->order_by("idVendedor,categoria");

		$query = $this->dbinfo->get()->result_array();
		//die($this->dbinfo->last_query());
		foreach ($query as $key => $value) {
			$datos_usuario = GETDATOSUSUARIO($value["idVendedor"], "*");
			$datos_sucursal = $this->CatalogosModel->getDatosSucursal($datos_usuario->sucursal)->row();
			$datos_ruta = $this->CatalogosModel->getDatosRuta($datos_usuario->ruta)->row();

			$query[$key]["usuario_nombre"] = $datos_usuario->nombre;
			$query[$key]["sucursal"] = isset($datos_sucursal) ? $datos_sucursal->sucursal : 0;
			$query[$key]["ruta"] = $datos_ruta->ruta;
		}

		return $query;
	}

	public function getProyeccionNomina($data)
	{
		/*$this->dbinfo->select("acumulados_categorias.*, ((importe/objetivo) * 100) as alcance, ((importe/diasTranscurridos)*(diasMes)) AS venta2,
		( (((SELECT venta2)) / objetivo) * 100 ) AS alcance2, (objetivo - importe) AS gap, COALESCE( (SELECT gap) / (diasMes-diasTranscurridos), 0) AS objetivo_diario,
		CONCAT('$', FORMAT(objetivo, 2)) as objetivo_format,
		CONCAT('$', FORMAT(importe, 2)) as importe_format,
		CONCAT(TRUNCATE((SELECT alcance),2), '%') AS alcance_format, 
		CONCAT('$', FORMAT((SELECT venta2), 2)) as venta2_format,
		CONCAT(TRUNCATE((SELECT alcance2), 2), '%') AS alcance2_format,
		CONCAT('$', FORMAT((SELECT gap), 2)) as gap_format,
		CONCAT('$', FORMAT((SELECT objetivo_diario), 2)) as objetivo_diario_format,
		FORMAT((ventas/diasTranscurridos),0) AS promedio_ventas,
		COALESCE((SELECT CONCAT('$', pago) FROM tabulador_pedidos WHERE numeropedidos <= (SELECT promedio_ventas) ORDER BY numeropedidos DESC LIMIT 1),0) AS pago_pedidos,
		(SELECT sucursal FROM cat_rutas WHERE cat_rutas.`chofer` = idVendedor) AS idsucursal_vendedor,
		COALESCE((SELECT pago FROM tabulador_categorias WHERE idsucursal = (SELECT idsucursal_vendedor) AND fnGetClasificacionById(idcategoria) = acumulados_categorias.`categoria`), 0) AS tabulador_pedido,
		COALESCE((SELECT minimo FROM tabulador_categorias WHERE idsucursal = (SELECT idsucursal_vendedor) AND fnGetClasificacionById(idcategoria) = acumulados_categorias.`categoria`), 0) AS tabulador_pedido_minimo,
		COALESCE((SELECT maximo FROM tabulador_categorias WHERE idsucursal = (SELECT idsucursal_vendedor) AND fnGetClasificacionById(idcategoria) = acumulados_categorias.`categoria`), 0) AS tabulador_pedido_maximo,
		COALESCE( 
			IF( (SELECT alcance2) < (SELECT tabulador_pedido_minimo),
				0, 
				IF((SELECT alcance2) > (SELECT tabulador_pedido_maximo), 
					(SELECT tabulador_pedido_maximo), 
					(SELECT alcance2)
				)
			)
		,0) AS porcentaje_categoria_pago,
		COALESCE( CONCAT('$', FORMAT((SELECT tabulador_pedido)*( (SELECT porcentaje_categoria_pago) / 100 ),2))  ,0) AS pago_categoria
		");*/
		$this->dbinfo->select("ac.*,
		(SELECT SUM(ac2.importe) FROM acumulados_categorias ac2 WHERE ac2.periodo = ac.periodo AND ac2.idvendedor = ac.idvendedor) AS venta_global,
		(SELECT SUM(tc.pago) FROM tabulador_categorias tc WHERE tc.idsucursal = 1) AS pago_total_categorias,
		((importe/objetivo) * 100) AS alcance, 
		((importe/diasTranscurridos)*(diasMes)) AS venta2, 
		( (((SELECT venta2)) / objetivo) * 100 ) AS alcance2,
		FORMAT((ventas/diasTranscurridos), 0) AS promedio_ventas, 
		'0' AS pago_pedidos, 
		(SELECT sucursal FROM cat_rutas WHERE cat_rutas.`chofer` = idVendedor) AS idsucursal_vendedor, 
		COALESCE((SELECT pago FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = ac.`categoria`), 0) AS tabulador_pedido, 
		COALESCE((SELECT minimo FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = ac.`categoria`), 0) AS tabulador_pedido_minimo, 
		COALESCE((SELECT maximo FROM tabulador_categorias WHERE idsucursal = 1 AND fnGetClasificacionById(idcategoria) = ac.`categoria`), 0) AS tabulador_pedido_maximo,
		COALESCE( 
			IF( (SELECT alcance2) < (SELECT tabulador_pedido_minimo),
				0, 
				IF((SELECT alcance2) > (SELECT tabulador_pedido_maximo), 
					(SELECT tabulador_pedido_maximo), 
					(SELECT alcance2)
				)
			)
		,0) AS porcentaje_categoria_pago,
		COALESCE( CONCAT('$', FORMAT((SELECT tabulador_pedido)*( (SELECT porcentaje_categoria_pago) / 100 ),2))  ,0) AS pago_categoria,
		COALESCE( ((SELECT tabulador_pedido)*( (SELECT porcentaje_categoria_pago) / 100 ))  ,0) AS pago_categoria2,
		TRUNCATE(((SELECT venta_global)/ac.`ventas`),0) AS dropsize
		");

		$this->dbinfo->from('acumulados_categorias ac');
		$this->dbinfo->where('ac.periodo', $data["periodo"]);
		$this->dbinfo->where('ac.idVendedor', $data["ruta"]);

		$this->dbinfo->order_by("categoria");
		
		$query = $this->dbinfo->get()->result_array();

		//die($this->dbinfo->last_query());

		foreach ($query as $key => $value)
		{
			$datos_usuario = GETDATOSUSUARIO($value["idVendedor"], "*");
			$datos_sucursal = $this->CatalogosModel->getDatosSucursal($datos_usuario->sucursal)->row();
			$datos_ruta = $this->CatalogosModel->getDatosRuta($datos_usuario->ruta)->row();

			$query[$key]["objetivo"] = "$".number_format($query[$key]["objetivo"],2,".",",");
			$query[$key]["importe"] = "$".number_format($query[$key]["importe"],2,".",",");
			$query[$key]["alcance"] = number_format($query[$key]["alcance"],2,".",",")."%";
			$query[$key]["venta2"] = "$".number_format($query[$key]["venta2"],2,".",",");
			$query[$key]["alcance2"] = number_format($query[$key]["alcance2"],2,".",",")."%";

			$query[$key]["usuario_nombre"] = $datos_usuario->nombre;
			$query[$key]["sucursal"] = $datos_sucursal->sucursal;
			$query[$key]["ruta"] = $datos_ruta->ruta;
			$query[$key]["incentivo_pedidos"] = $this->getIncentivoPedidos($value["dropsize"], $value["promedio_ventas"]);
		}

		return $query;
	}

	public function getProyeccionNomina2($data)
	{
		$periodo = $data["periodo"];
		$TIPO = "AGENTE";
		$FIN_MES = "SI";

		$mes = substr($periodo, 4, 6);

		if(date('m') == $mes);
		{
			$FIN_MES = "NO";
		}

		if($data["ruta"] == "0")
		{
			$TIPO = "SUPERVISOR";

			/*$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT ac.`idruta`) AS rutas
			FROM acumulados_categorias ac 
			WHERE ac.periodo = '$data[periodo]' AND (SELECT cr.sucursal FROM cat_rutas cr WHERE cr.id = ac.`idruta`) = '$data[sucursal]'");*/

			$empresa = GETEMPRESA();

			if (in_array($data["sucursal"], [11, 15, 18]))
			{
				$data["sucursal"] = "11,15,18";
			}

			$info_usuario = $this->db->query("SELECT * 
			FROM usuarios 
			WHERE empresa = '$empresa' AND puesto LIKE '%.supervisor de ventas%' AND FIND_IN_SET('$data[sucursal]', sucursal_asignadas)")->row();

			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT ac.`idruta`) AS rutas
			FROM acumulados_categorias ac 
			WHERE ac.periodo = '$data[periodo]' AND FIND_IN_SET((SELECT cr.sucursal FROM cat_rutas cr WHERE cr.id = ac.`idruta`), '$data[sucursal]')");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
			}			

			//die($data["ruta"]);
		}

		$query = $this->dbinfo->query("SELECT datos.*,
		ROUND(((datos.venta / datos.objetivo) * 100), 2) AS alcance,
		ROUND(IF((SELECT alcance) >= datos.restriccion, ((datos.pago_categoria * (SELECT alcance)) / 100), 0), 2) AS incentivo_categoria
		FROM(
		SELECT ac.`categoria`, ac.`restriccion`,
		('$FIN_MES') AS fin_mes,
		ROUND(IF((SELECT fin_mes) = 'SI', SUM(ac.`importe`), ((SUM(ac.`importe`) / ac.`diasTranscurridos`) * ac.`diasMes`) ), 2) AS venta,
		ROUND(SUM(ac.`objetivo`), 2) AS objetivo,
		('$TIPO') AS tipo_empleado,
		IF(ac.idcategoria = 6 AND (SELECT tipo_empleado) = 'SUPERVISOR', 3000, 
		IF(ac.idcategoria = 19 AND (SELECT tipo_empleado) = 'SUPERVISOR', 2000,
		tc.pago)) AS pago_categoria
		FROM acumulados_categorias ac
		INNER JOIN tabulador_categorias tc ON ac.`idcategoria` = tc.`idcategoria` AND tc.`idsucursal` = 1
		WHERE ac.`periodo` = '$data[periodo]' AND FIND_IN_SET(ac.`idruta`, '$data[ruta]')
		GROUP BY ac.`idcategoria`
		ORDER BY ac.categoria) AS datos")->result_array();

		return $query;
	}

	public function getProyeccionNominaCertificado($data)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$periodo = $data["periodo"];
		$TIPO = "AGENTE";
		$FIN_MES = "SI";

		$anio = substr($periodo, 0, 4);
		$mes = substr($periodo, 4, 6);

		$diaactual = date('d');
		if(strlen($diaactual) == 1)
		{
			$diaactual = "0$diaactual";
		}

		if( floatval(date('m')) != floatval($mes) )
		{
			$diaactual = "31";
		}

		if(date('m') == $mes);
		{
			$FIN_MES = "NO";
		}

		$fechaDe = $anio.'-'.$mes.'-01';
		$fechaA = $anio.'-'.$mes.'-'.$diaactual;

		if($data["ruta"] == "0")
		{
			$TIPO = "SUPERVISOR";

			/*$rutas = $db->query("SELECT GROUP_CONCAT(DISTINCT ac.`idruta`) AS rutas
			FROM acumulados_categorias ac 
			WHERE ac.periodo = '$data[periodo]' AND (SELECT cr.sucursal FROM cat_rutas cr WHERE cr.id = ac.`idruta`) = '$data[sucursal]'");*/

			$empresa = GETEMPRESA();

			if (in_array($data["sucursal"], [11, 15, 18]))
			{
				$data["sucursal"] = "11,15,18";
			}

			$info_usuario = $this->db->query("SELECT * 
			FROM usuarios 
			WHERE empresa = '$empresa' AND puesto LIKE '%.supervisor de ventas%' AND FIND_IN_SET('$data[sucursal]', sucursal_asignadas)")->row();

			//$data["sucursal"] = $info_usuario->sucursal_asignadas;

			$rutas = $db->query("SELECT GROUP_CONCAT(DISTINCT ac.`idruta`) AS rutas
			FROM acumulados_categorias ac 
			WHERE ac.periodo = '$data[periodo]' AND FIND_IN_SET((SELECT cr.sucursal FROM cat_rutas cr WHERE cr.id = ac.`idruta`), '$data[sucursal]')");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
			}
		}

		$data["fechaDe"] = $fechaDe;
		$data["fechaA"] = $fechaA;
		$data["tipo_empleado"] = $TIPO;
		$data["fin_mes"] = $FIN_MES;

		$query = $db->query($this->query_certificados($data));

		$datos = $query->row();

		$datos->incentivo_pedidos_da = $this->getIncentivoPedidos($datos->dropsize_da, $datos->promedio_ventas_da);
		$datos->incentivo_pedidos_impulso = $this->getIncentivoPedidosImpulso($datos->dropsize_impulso, $datos->promedio_ventas_impulso);
		$datos->incentivo_pedidos_rtd = 0;

		//$datos->porcentaje_cumplimiento_agenda = ($datos->visitado / $datos->visitas) * 100;

		$incentivo_cumplimiento_agenda = 0;

		if($TIPO == "SUPERVISOR")
		{
			if($datos->porcentaje_cumplimiento_agenda >= 95 && $datos->promedio_clientes_programados >= 45)
			{
				if($datos->rutas_laboradas_periodo >= 6 && $datos->rutas_laboradas_periodo <= 7)
				{
					$incentivo_cumplimiento_agenda = 1200;
				}
				else if($datos->rutas_laboradas_periodo >= 8 && $datos->rutas_laboradas_periodo <= 9)
				{
					$incentivo_cumplimiento_agenda = 1600;
				}
				else if($datos->rutas_laboradas_periodo >= 10)
				{
					$incentivo_cumplimiento_agenda = 2000;
				}
			}
		}

		$datos->incentivo_cumplimiento_agenda = $incentivo_cumplimiento_agenda;

		if($TIPO == "AGENTE")
		{
			$datos->incentivo_rutas_laboradas = 0;
			//$datos->puntos_clientes_programados = 0;
			//$datos->puntos_cumplimiento_agenda = 0;
		}		

		$datos->sum_total_puntos = $datos->puntos_rutas_laboradas + $datos->puntos_cobertura_sallout_da + $datos->puntos_dropsize_da +
									$datos->puntos_promedio_ventas_da + $datos->puntos_efectividad_ventas_da + $datos->puntos_cobertura_sallout_impulso +
									$datos->puntos_dropsize_impulso + $datos->puntos_promedio_ventas_impulso + $datos->puntos_efectividad_ventas_impulso +
									$datos->puntos_cobertura_categorias + $datos->puntos_cumplimiento_agenda + $datos->puntos_clientes_programados + $datos->puntos_porcentaje_efectividad_rutas;

		/*if($TIPO == "SUPERVISOR")
		{
			$datos->sum_total_puntos = $datos->sum_total_puntos + $datos->puntos_clientes_programados + $datos->puntos_cumplimiento_agenda;
		}*/

		$datos->incentivo_certificacion = 0;
		$datos->incentivo_certificacion_localidad = 0;

		$datos->certifica = 0;

		if($datos->sum_total_puntos >= 80)
		{
			$datos->certifica_texto = "Certifica";
		}
		else
		{
			$datos->certifica_texto = "No certifica";
		}

		if($TIPO == "AGENTE")
		{
			if($datos->sum_total_puntos >= 80)
			{
				$datos->certifica = 1;
				$datos->incentivo_certificacion = 2000;
			}
		}
		else
		{
			if($datos->sum_total_puntos >= 80)
			{
				$datos->incentivo_certificacion_localidad = 200 * $datos->rutas_laboradas_periodo;
			}

			/*$rutas = $db->query("SELECT GROUP_CONCAT(DISTINCT ac.`idruta`) AS rutas
			FROM acumulados_categorias ac 
			WHERE ac.periodo = '$data[periodo]' AND (SELECT cr.sucursal FROM cat_rutas cr WHERE cr.id = ac.`idruta`) = '$data[sucursal]'");*/

			$rutas = $db->query("SELECT GROUP_CONCAT(DISTINCT ac.`idruta`) AS rutas
			FROM acumulados_categorias ac 
			WHERE ac.periodo = '$data[periodo]' AND FIND_IN_SET((SELECT cr.sucursal FROM cat_rutas cr WHERE cr.id = ac.`idruta`), '$data[sucursal]')");

			if($rutas->num_rows() > 0)
			{
				$rutas_certificadas = 0;

				$ids_rutas = explode(',', $rutas->row()->rutas);

				foreach($ids_rutas as $item)
				{
					$data["ruta"] = $item;
					$query = $db->query($this->query_certificados($data))->row();

					$sum_puntos =  $query->puntos_rutas_laboradas + $query->puntos_cobertura_sallout_da + $query->puntos_dropsize_da +
									$query->puntos_promedio_ventas_da + $query->puntos_efectividad_ventas_da + $query->puntos_cobertura_sallout_impulso +
									$query->puntos_dropsize_impulso + $query->puntos_promedio_ventas_impulso + $query->puntos_efectividad_ventas_impulso +
									$query->puntos_cobertura_categorias + $query->puntos_cumplimiento_agenda + $query->puntos_clientes_programados + $datos->puntos_porcentaje_efectividad_rutas;;

					if($sum_puntos >= 80)
					{
						$rutas_certificadas = $rutas_certificadas + 1;
					}
				}

				$datos->certifica = $rutas_certificadas;
				$datos->incentivo_certificacion = $rutas_certificadas * 200;
			}
		}

		$datos->sum_total_incentivo = $datos->acumulado_incentivo_da + $datos->incentivo_pedidos_da + $datos->acumulado_incentivo_impulso + $datos->incentivo_pedidos_impulso +
										$datos->incentivo_rutas_laboradas + $datos->incentivo_certificacion + $datos->incentivo_certificacion_localidad + $datos->incentivo_cumplimiento_agenda;


		if($TIPO == "AGENTE")
		{
			$info_ruta = $db->query("SELECT * FROM cat_rutas WHERE id = '$data[ruta]'")->row();
			$info_usuario = $this->db->query("SELECT * FROM usuarios WHERE id = '$info_ruta->chofer'")->row();
			$info_sucursal = $db->query("SELECT * FROM cat_sucursales WHERE id = '$info_ruta->sucursal'")->row();

			$datos->tipo_empleado = "Agente de ventas";
			$datos->nombre_ruta = $info_ruta->ruta;
			$datos->nombre_empleado = $info_usuario->nombre;
			$datos->nombre_sucursal = $info_sucursal->sucursal;
		}
		else
		{
			$info_sucursal = $db->query("SELECT GROUP_CONCAT(sucursal) AS sucursal FROM cat_sucursales WHERE FIND_IN_SET(id, '$data[sucursal]')")->row();

			$datos->tipo_empleado = "Supervisor de ventas";
			$datos->nombre_ruta = "TODAS";
			$datos->nombre_empleado = $info_usuario->nombre ?? "SUPERVISOR";
			$datos->nombre_sucursal = $info_sucursal->sucursal;
		}

		return $datos;
	}

	public function query_certificados($data)
	{
		$SQL = "SELECT ac.periodo, ac.diasTranscurridos,
		fnGetCantidadVisitaClientesActivosHastaHoy('$data[ruta]', '$data[fechaDe]', ac.fecha) AS visitas,
		fnGetCantidadVisitasHastaHoy('$data[ruta]', '$data[fechaDe]', ac.fecha) AS visitado,
		fnGetCantidadRutasLaboradas('$data[ruta]', '$data[fechaDe]', ac.fecha) AS rutas_laboradas,
		fnGetCantidadDiasLaboradasTodasRutasSucursal('$data[ruta]', '$data[periodo]', '$data[sucursal]') AS dias_laborables,
		fnGetCantidadRutasLaboradasPeriodo('$data[ruta]', '$data[periodo]', '$data[sucursal]') AS rutas_laboradas_periodo,
		((((SELECT rutas_laboradas) / (SELECT dias_laborables))) * 100) AS porcentaje_rutas_laboradas,
		((SELECT visitado) / (SELECT visitas) * 100) AS porcentaje_cumplimiento_agenda,
		fnGetPuntosCertificado('rutas laboradas', (SELECT porcentaje_rutas_laboradas)) AS puntos_rutas_laboradas,
		(SELECT COUNT(*) FROM cat_clasificacionproductos cp WHERE cp.`status` = 1 AND cp.id NOT IN(19)) AS categorias_activas,
		fnGetClientesProgramados('$data[ruta]') AS clientes_programados,
		TRUNCATE(((SELECT clientes_programados) / 6) / (SELECT rutas_laboradas_periodo), 0) AS promedio_clientes_programados,

		fnGetCantidadVentasHastaHoy('$data[ruta]', '$data[fechaDe]', ac.fecha, 'DA') AS cantidad_ventas_da,		
		ROUND(fnGetImporteAcumuladoCategorias('$data[ruta]', '$data[periodo]', 'DA', '$data[fin_mes]'), 2) AS acumulado_importe_da,
		ROUND(fnGetImporteAcumuladoCategorias('$data[ruta]', '$data[periodo]', 'DA', 'SI'), 2) AS acumulado_importe_da_real,
		ROUND(fnGetObjetivoAcumuladoCategorias('$data[ruta]', '$data[periodo]', 'DA'), 2) AS acumulado_objetivo_da,
		(((SELECT acumulado_importe_da) / (SELECT acumulado_objetivo_da)) * 100) AS porcentaje_acumulado_alcance_da,
		ROUND(fnGetAcumuladoPagoCategoria('$data[ruta]', '$data[periodo]', 'DA', '$data[tipo_empleado]', '$data[fin_mes]'), 2) AS acumulado_incentivo_da,
		fnGetPuntosCertificado('cobertura sell out da', (SELECT porcentaje_acumulado_alcance_da)) AS puntos_cobertura_sallout_da,
		TRUNCATE((SELECT acumulado_importe_da_real) / (SELECT cantidad_ventas_da), 2) AS dropsize_da,
		ROUND(((SELECT cantidad_ventas_da) / (SELECT visitado) * 100), 2) AS efectividad_ventas_da,
		fnGetPuntosCertificado('drop size da', (SELECT dropsize_da)) AS puntos_dropsize_da,
		TRUNCATE((((SELECT cantidad_ventas_da)/diasTranscurridos) / (SELECT rutas_laboradas_periodo)), 2) AS promedio_ventas_da,
		fnGetPuntosCertificado('pedidos promedio da', (SELECT promedio_ventas_da)) AS puntos_promedio_ventas_da,
		fnGetPuntosCertificado('efectividad venta da', (SELECT efectividad_ventas_da)) AS puntos_efectividad_ventas_da,

		fnGetCantidadVentasHastaHoy('$data[ruta]', '$data[fechaDe]', ac.fecha, 'IMPULSO') AS cantidad_ventas_impulso,
		ROUND(fnGetImporteAcumuladoCategorias('$data[ruta]', '$data[periodo]', 'IMPULSO', '$data[fin_mes]'), 2) AS acumulado_importe_impulso,
		ROUND(fnGetImporteAcumuladoCategorias('$data[ruta]', '$data[periodo]', 'IMPULSO', 'SI'), 2) AS acumulado_importe_impulso_real,
		ROUND(fnGetObjetivoAcumuladoCategorias('$data[ruta]', '$data[periodo]', 'IMPULSO'), 2) AS acumulado_objetivo_impulso,
		(((SELECT acumulado_importe_impulso) / (SELECT acumulado_objetivo_impulso)) * 100) AS porcentaje_acumulado_alcance_impulso,
		COALESCE(ROUND(fnGetAcumuladoPagoCategoria('$data[ruta]', '$data[periodo]', 'IMPULSO', '$data[tipo_empleado]', '$data[fin_mes]'), 2), 0) AS acumulado_incentivo_impulso,
		fnGetPuntosCertificado('cobertura sell out impulso', (SELECT porcentaje_acumulado_alcance_impulso)) AS puntos_cobertura_sallout_impulso,
		TRUNCATE((SELECT acumulado_importe_impulso_real) / (SELECT cantidad_ventas_impulso), 2) AS dropsize_impulso,
		ROUND(((SELECT cantidad_ventas_impulso) / (SELECT visitado) * 100), 2) AS efectividad_ventas_impulso,
		fnGetPuntosCertificado('drop size impulso', (SELECT dropsize_impulso)) AS puntos_dropsize_impulso,
		TRUNCATE((((SELECT cantidad_ventas_impulso)/diasTranscurridos) / (SELECT rutas_laboradas_periodo)), 2) AS promedio_ventas_impulso,
		fnGetPuntosCertificado('pedidos promedio impulso', (SELECT promedio_ventas_impulso)) AS puntos_promedio_ventas_impulso,
		fnGetPuntosCertificado('efectividad venta impulso', (SELECT efectividad_ventas_impulso)) AS puntos_efectividad_ventas_impulso,

		fnGetCantidadVentasHastaHoy('$data[ruta]', '$data[fechaDe]', ac.fecha, 'RTD') AS cantidad_ventas_rtd,
		ROUND(fnGetImporteAcumuladoCategorias('$data[ruta]', '$data[periodo]', 'RTD', 'SI'), 2) AS acumulado_importe_rtd_real,
		TRUNCATE((SELECT acumulado_importe_rtd_real) / (SELECT cantidad_ventas_rtd), 2) AS dropsize_rtd,
		ROUND(((SELECT cantidad_ventas_rtd) / (SELECT visitado) * 100), 2) AS efectividad_ventas_rtd,
		TRUNCATE((((SELECT cantidad_ventas_rtd)/diasTranscurridos) / (SELECT rutas_laboradas_periodo)), 2) AS promedio_ventas_rtd,
		ROUND(fnGetImporteAcumuladoCategorias('$data[ruta]', '$data[periodo]', 'RTD', '$data[fin_mes]'), 2) AS acumulado_importe_rtd,
		ROUND(fnGetObjetivoAcumuladoCategorias('$data[ruta]', '$data[periodo]', 'RTD'), 2) AS acumulado_objetivo_rtd,
		(((SELECT acumulado_importe_rtd) / (SELECT acumulado_objetivo_rtd)) * 100) AS porcentaje_acumulado_alcance_rtd,
		COALESCE(ROUND(fnGetAcumuladoPagoCategoria('$data[ruta]', '$data[periodo]', 'RTD', '$data[tipo_empleado]', '$data[fin_mes]'), 2), 0) AS acumulado_incentivo_rtd,

		fnGetCoberturaCategorias('$data[ruta]', '$data[periodo]', '$data[fin_mes]') AS cobertura_categorias,
		(((SELECT cobertura_categorias) / (SELECT categorias_activas)) * 100) AS porcentaje_cobertura_categorias,
		fnGetPuntosCertificado('cobertura categorias', (SELECT cobertura_categorias)) AS puntos_cobertura_categorias,
		fnGetPuntosCertificado('cumplimiento agenda', (SELECT porcentaje_cumplimiento_agenda)) AS puntos_cumplimiento_agenda,
		fnGetPuntosCertificado('clientes programados', (SELECT promedio_clientes_programados)) AS puntos_clientes_programados,

		fn_porcentaje_efectividad_reparto('$data[ruta]', '$data[sucursal]', '$data[fechaDe]', ac.fecha) AS porcentaje_efectividad_rutas,
		fnGetPuntosCertificado('% Efectividad Reparto', (SELECT porcentaje_efectividad_rutas)) AS puntos_porcentaje_efectividad_rutas,

		((SELECT dias_laborables) - (SELECT rutas_laboradas)) AS num_faltas,
		(SELECT (200*trl.pago) * (SELECT rutas_laboradas_periodo)  FROM tabulador_rutas_laboradas trl WHERE trl.`faltas` <= (SELECT num_faltas) ORDER BY trl.`faltas` DESC LIMIT 1) AS incentivo_rutas_laboradas 

		FROM acumulados_categorias ac
		WHERE ac.periodo = '$data[periodo]' LIMIT 1";

		//die($SQL);
		return $SQL;
	}

	public function getIncentivoPedidos($pDropsize, $pPropedidos)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$pPropedidos = intval($pPropedidos);
		$pDropsize = intval($pDropsize);

		$vRes = 0;
		if($pPropedidos < 25)
		{
			//return 0;
			$pPropedidos = 25;
		}
		
		if($pPropedidos > 40)
		{
			$pPropedidos = 40;
		}
		
		if($pDropsize < 0)
		{
			$pDropsize = 0;
		}
		else if($pDropsize > 460)
		{
			$pDropsize = 460;
		}
		else
		{
			$info_tabulador = $db->query("SELECT * FROM tabulador_pedidos WHERE dropsize <= $pDropsize ORDER BY dropsize DESC LIMIT 1")->row();
			$pDropsize = $info_tabulador->dropsize;
		}

		$info_tabulador = $db->query("SELECT tabulador_pedidos.`$pPropedidos` AS vRes FROM tabulador_pedidos WHERE dropsize = $pDropsize")->row();
		return $info_tabulador->vRes;
	}

	public function getIncentivoPedidosImpulso($pDropsize, $pPropedidos)
	{
		$db = $this->getDBEmpresa($this->config_app);

		$pPropedidos = intval($pPropedidos);
		$pDropsize = intval($pDropsize);

		$vRes = 0;
		
		if($pPropedidos < 8)
		{
			$pPropedidos = 8;
			//return 0;
		}
		
		if($pPropedidos > 23)
		{
			$pPropedidos = 23;
		}
		
		if($pDropsize < 0)
		{
			$pDropsize = 0;
		}
		else if($pDropsize > 300)
		{
			$pDropsize = 300;
		}
		else
		{
			$info_tabulador = $db->query("SELECT * FROM tabulador_pedidos_impulso WHERE dropsize <= $pDropsize ORDER BY dropsize DESC LIMIT 1")->row();
			$pDropsize = $info_tabulador->dropsize;
		}

		$info_tabulador = $db->query("SELECT tabulador_pedidos_impulso.`$pPropedidos` AS vRes FROM tabulador_pedidos_impulso WHERE dropsize = $pDropsize")->row();
		return $info_tabulador->vRes;
	}

	public function getCategorias()
	{
		$consulta = "SELECT nombre FROM cat_clasificacionproductos WHERE status = 1";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getDatosObjetivos($periodo)
	{
		$consulta = "SELECT diasTranscurridos, diasMes FROM acumulados_categorias WHERE periodo='$periodo' LIMIT 1";
		$query = $this->dbinfo->query($consulta);
		return $query;
	}

	public function getReporteSellInOut($data)
	{
		$periodo = $data["periodo"];
		$TIPO = $data["tipo"];
		$FIN_MES = "SI";

		$mes = substr($periodo, 4, 6);

		if(date('m') == $mes);
		{
			$FIN_MES = "NO";
		}

		if($TIPO == "GLOBAL")
		{
			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT r.`id`) AS rutas FROM cat_rutas r WHERE r.`status` = 1");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
			}
		}
		else if($TIPO == "PACIFICO")
		{
			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT r.`id`) AS rutas 
			FROM cat_rutas r 
			INNER JOIN cat_sucursales s ON r.`sucursal` = s.`id` AND s.`zona` = 1
			WHERE r.`status` = 1");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
			}
		}
		else if($TIPO == "NORTE")
		{
			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT r.`id`) AS rutas 
			FROM cat_rutas r 
			INNER JOIN cat_sucursales s ON r.`sucursal` = s.`id` AND s.`zona` = 2
			WHERE r.`status` = 1");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
			}
		}
		else if($TIPO == "SUCURSAL")
		{
			$empresa = GETEMPRESA();

			$info_usuario = $this->db->query("SELECT * 
			FROM usuarios 
			WHERE empresa = '$empresa' AND puesto LIKE '%.supervisor de ventas%' AND FIND_IN_SET('$data[sucursal]', sucursal_asignadas)")->row();

			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT ac.`idruta`) AS rutas
			FROM acumulados_categorias ac 
			WHERE ac.periodo = '$data[periodo]' AND FIND_IN_SET((SELECT cr.sucursal FROM cat_rutas cr WHERE cr.id = ac.`idruta`), '$info_usuario->sucursal_asignadas')");

			//$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT r.`id`) AS rutas FROM cat_rutas r WHERE r.`status` = 1 AND r.sucursal = '$data[sucursal]'");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
			}
		}

		$query = $this->dbinfo->query("SELECT datos.*,
		TRUNCATE(((datos.venta / datos.objetivo) * 100), 0) AS alcance
		FROM(
		SELECT ac.`categoria`,
		('$FIN_MES') AS fin_mes,
		ROUND(IF((SELECT fin_mes) = 'SI', SUM(ac.`importe`), ((SUM(ac.`importe`) / ac.`diasTranscurridos`) * ac.`diasMes`) ), 2) AS venta,
		ROUND(SUM(ac.`objetivo`), 2) AS objetivo
		FROM acumulados_categorias ac
		WHERE ac.`periodo` = '$data[periodo]' AND FIND_IN_SET(ac.`idruta`, '$data[ruta]')
		GROUP BY ac.`idcategoria`
		ORDER BY ac.categoria) AS datos")->result_array();

		return $query;
	}

	public function getResumenSellInOut($data)
	{
		$periodo = $data["periodo"];
		$TIPO = $data["tipo"];
		$FIN_MES = "SI";

		$anio = substr($periodo, 0, 4);
		$mes = substr($periodo, 4, 6);

		if(date('m') == $mes);
		{
			$FIN_MES = "NO";
		}

		$data["fin_mes"] = $FIN_MES;
		$data["fechaDe"] = $anio.'-'.$mes.'-01';

		if($TIPO == "GLOBAL")
		{
			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT r.`id`) AS rutas, GROUP_CONCAT(DISTINCT s.`id`) AS sucursales
			FROM cat_rutas r
			INNER JOIN cat_sucursales s ON r.`sucursal` = s.`id`
			WHERE r.`status` = 1");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
				$data["sucursal"] = $rutas->row()->sucursales;
			}
		}
		else if($TIPO == "PACIFICO")
		{
			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT r.`id`) AS rutas, GROUP_CONCAT(DISTINCT s.`id`) AS sucursales
			FROM cat_rutas r 
			INNER JOIN cat_sucursales s ON r.`sucursal` = s.`id` AND s.`zona` = 1
			WHERE r.`status` = 1");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
				$data["sucursal"] = $rutas->row()->sucursales;
			}
		}
		else if($TIPO == "NORTE")
		{
			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT r.`id`) AS rutas, GROUP_CONCAT(DISTINCT s.`id`) AS sucursales
			FROM cat_rutas r 
			INNER JOIN cat_sucursales s ON r.`sucursal` = s.`id` AND s.`zona` = 2
			WHERE r.`status` = 1");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
				$data["sucursal"] = $rutas->row()->sucursales;
			}
		}
		else if($TIPO == "SUCURSAL")
		{
			$empresa = GETEMPRESA();

			$info_usuario = $this->db->query("SELECT * 
			FROM usuarios 
			WHERE empresa = '$empresa' AND puesto LIKE '%.supervisor de ventas%' AND FIND_IN_SET('$data[sucursal]', sucursal_asignadas)")->row();

			$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT ac.`idruta`) AS rutas
			FROM acumulados_categorias ac 
			WHERE ac.periodo = '$data[periodo]' AND FIND_IN_SET((SELECT cr.sucursal FROM cat_rutas cr WHERE cr.id = ac.`idruta`), '$info_usuario->sucursal_asignadas')");

			//$rutas = $this->dbinfo->query("SELECT GROUP_CONCAT(DISTINCT r.`id`) AS rutas FROM cat_rutas r WHERE r.`status` = 1 AND r.sucursal = '$data[sucursal]'");

			if($rutas->num_rows() > 0)
			{
				$data["ruta"] = $rutas->row()->rutas;
				$data["sucursal"] = $info_usuario->sucursal_asignadas;
			}
		}

		$query = $this->dbinfo->query("SELECT
		ROUND(fnGetImporteAcumuladoCategorias('$data[ruta]', '$data[periodo]', '$data[tipo_resumen]', '$data[fin_mes]'), 2) AS acumulado_importe_da_real,
		ROUND(fnGetImporteAcumuladoCategorias('$data[ruta]', '$data[periodo]', '$data[tipo_resumen]', 'SI'), 2) AS venta_real,
		ROUND(fnGetObjetivoAcumuladoCategorias('$data[ruta]', '$data[periodo]', '$data[tipo_resumen]'), 2) AS acumulado_objetivo_da,
		fnGetCantidadVentasHastaHoy('$data[ruta]', '$data[fechaDe]', ac.fecha, '$data[tipo_resumen]') AS cantidad_ventas_da,		
		fnGetCantidadRutasLaboradasPeriodo('$data[ruta]', '$data[periodo]', '$data[sucursal]') AS rutas_laboradas_periodo,
		TRUNCATE((SELECT venta_real) / (SELECT cantidad_ventas_da), 0) AS dropsize_da,
		TRUNCATE((((SELECT cantidad_ventas_da)/diasTranscurridos) / (SELECT rutas_laboradas_periodo)), 0) AS promedio_ventas_da,
		ac.fecha
		FROM acumulados_categorias ac WHERE ac.periodo = '$data[periodo]' LIMIT 1")
		->row();

		//die($this->dbinfo->last_query());

		return $query;
	}

	public function getLastPeriodo()
	{
		$query = $this->dbinfo->query("SELECT * FROM acumulados_categorias ORDER BY fecha DESC LIMIT 1")->row();
		return $query;
	}

	public function getSucursalesZonas($pZona)
	{
		$query = $this->dbinfo->query("SELECT * FROM cat_sucursales s WHERE s.`status` = 1 AND s.`zona` = '$pZona' ORDER BY s.`zona`,s.`sucursal`")->result();
		return $query;
	}

	public function getObjetivosZonas($pZona)
	{
		$query = $this->dbinfo->query("SELECT * FROM objetivos_proyeccion WHERE zona = '$pZona'")->row();
		return $query;
	}

public function getPruebaAgregarPedido(){
		//$consulta="CALL agregarAcumulados('2018-11-06',".$idVendedor.",'BEBIDAS DE COCOA',3200.43)";
		$cadena=$_POST['cadena'];
    return "OK";
}
public function getRutaName($idVendedor){
	$consulta="SELECT cat_rutas.ruta FROM cat_rutas INNER JOIN usuarios ON usuarios.ruta=cat_rutas.id WHERE usuarios.id=$idVendedor";
	$query=$this->db->query($consulta);
	return $query;
}
public function getSucursalName($idVendedor){
	$consulta="SELECT cat_sucursales.sucursal FROM cat_sucursales INNER JOIN usuarios ON usuarios.sucursal=cat_sucursales.id WHERE usuarios.id=$idVendedor";
	$query=$this->db->query($consulta);
	return $query;
}
public function doCorte($fecha){
	$fecha2=$fecha." 23:59:59";
	$consulta="CALL eliminaPedidos('".$fecha."','".$fecha2."')";
	$resultado=$this->db->query($consulta);
}


public function getSacarDias($fecha){
	$mesC=explode("-", $fecha);
	$mes=$mesC[0]."-".$mesC[1]."01";
	
	$consulta="SELECT COUNT(id) AS cuantos FROM pedidos WHERE fecha BETWEEN '$mes' AND '$fecha'";
	$query=$this->db->query($consulta);
	return $query;

}

public function getAgregarAcumulados(){
	$data = file_get_contents("assets/acumulados/jSonAcumulados.json");
		$products = json_decode($data, true);
			$fecha=$products['Fecha'];
			$tipoArchivo=$products['TipoArchivo'];
			$cFecha=explode("-",$fecha);
			$mes=$cFecha[1];
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
public function getCuantasVisitas($fIni,$fFin,$valor){
	$consulta="SELECT COUNT(id) AS cuanto FROM visitas WHERE resultado='$valor' AND fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
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
public function getPedidosVisitas($idUsuario,$fIni,$fFin){
	$consulta="SELECT DISTINCT(visitas.idcliente),visitas.fecha,visitas.latitud,visitas.longitud,clientes.codigo,clientes.nombre,clientes.calle,clientes.numero,clientes.colonia,clientes.ciudad,clientes.estado FROM visitas INNER JOIN clientes ON clientes.id=visitas.idcliente WHERE visitas.idusuario=$idUsuario AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	$contador=0;
	$cadena="";
	//$cadena2=$query->num_rows();
					foreach ($query->result() as $kCoord) {
						
						$consultaPed="SELECT tipo,total FROM pedidos WHERE idcliente=$kCoord->idcliente AND pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
						//$cadena2.=" - ".$consultaPed;
						//echo "<br>".$consultaPed;
						$queryPed=$this->db->query($consultaPed);
						if($queryPed->num_rows()!=0){
							$total=FORMATO_DINERO($queryPed->row()->total);
							$tipo=$queryPed->row()->tipo;
							if($contador==0){
								$cadena.=$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
							}
							else{
								$cadena.="%".$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
							}	 
						}
						else{
							$total=0;
							$tipo="VISITA";
							if($contador==0){
								$cadena.=$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
							}
							else{
								$cadena.="%".$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
							}
						}
						
						$contador=$contador+1;
				}
	//return $cadena2;
	return $contador."&".$cadena;
}
public function getProductosJ(){
	$consulta="SELECT * FROM cat_productos";
	$query=$this->db->query($consulta);
	return $query;
}
public function getClientesJ(){
	$consulta="SELECT * FROM clientes";
	$query=$this->db->query($consulta);
	return $query;
}
public function getUsuariosJ(){
	$consulta="SELECT * FROM usuarios";
	$query=$this->db->query($consulta);
	return $query;
}
public function getZonasJ($id){
	$consulta="SELECT * FROM cat_zonas WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
public function getCategoriasProductosJ($id){
	$consulta="SELECT * FROM cat_clasificacionproductos WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
public function getSucursales(){
		$MS=VERIFICAMULTISUCURSAL();
		$sucursal=GETSUCURSAL();
		if($MS==1){
			$consulta="SELECT * FROM cat_sucursales ORDER BY sucursal ASC";
		}
		else{
			$consulta="SELECT * FROM cat_sucursales WHERE id=$sucursal";		
		}
	$query=$this->db->query($consulta);
	return $query;
}
public function banderaImpreso($idpedido){
	$consulta="UPDATE pedidos SET impreso=1 WHERE id=$idpedido";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidos($fIni,$fFin){
	$consulta="SELECT pedidos.id,pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total, pedidos.fechacreacion, pedidos.impreso, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, cat_sucursales.sucursal AS sucursal, pedidos.status, cat_rutas.ruta FROM pedidos  INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente INNER JOIN cat_sucursales ON cat_sucursales.id=clientes.sucursal INNER JOIN cat_rutas ON cat_rutas.chofer=pedidos.idusuario WHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosCuantos($fIni,$fFin){
	$consulta="SELECT COUNT(pedidos.id) AS cuantospedidos, SUM(pedidos.total) AS totalpedidos FROM pedidos WHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
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
public function getVisitas($fIni,$fFin){
	$consulta="SELECT visitas.id,visitas.codigocliente, visitas.idcliente, visitas.cliente, usuarios.nombre, visitas.resultado, visitas.fecha, visitas.inicio, visitas.fin, cat_sucursales.sucursal, cat_rutas.ruta FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id INNER JOIN cat_rutas ON cat_rutas.chofer=usuarios.id WHERE visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;

}
public function getEfectividad($fIni,$fFin){
	/*$consulta="SELECT usuarios.nombre, usuarios.id AS idUsuario, COUNT(visitas.idusuario) AS numeroVisitas, cat_sucursales.sucursal,MIN(visitas.fechacreacion) AS primera, MAX(visitas.fechacreacion) AS ultima FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";*/
	$consulta="SELECT DISTINCT (visitas.idusuario),usuarios.nombre, usuarios.id AS idUsuario, cat_sucursales.sucursal, cat_rutas.ruta FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id INNER JOIN cat_rutas ON cat_rutas.chofer=visitas.idusuario WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;

}
public function getEfectividadAgenda($fIni,$fFin){
	/*$consulta="SELECT usuarios.nombre, usuarios.id AS idUsuario, COUNT(visitas.idusuario) AS numeroVisitas, cat_sucursales.sucursal,MIN(visitas.fechacreacion) AS primera, MAX(visitas.fechacreacion) AS ultima FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";*/
	$consulta="SELECT DISTINCT (visitas.idusuario),usuarios.nombre, usuarios.id AS idUsuario, cat_sucursales.sucursal, cat_rutas.ruta, visitas.ruta AS idRuta FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id INNER JOIN cat_rutas ON cat_rutas.chofer=visitas.idusuario WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
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
public function getRutas(){
	$MS=VERIFICAMULTISUCURSAL();
		$sucursal=GETSUCURSAL();
		if($MS==1){
			$consulta="SELECT * FROM cat_rutas WHERE status=1";
		}
		else {
			$consulta="SELECT * FROM cat_rutas WHERE sucursal=$sucursal AND status=1";
		}
	$query=$this->db->query($consulta);
	return $query;
}
public function deletePedidos($id){
	$consulta="UPDATE pedidos SET status=0 WHERE id=$id";
	$this->db->query($consulta);
}
public function getPedidosDetalle($idpedido){
	$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, cat_proveedor.nombre AS nombreProveedor FROM pedidos INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente INNER JOIN cat_proveedor ON pedidos_detalle.idproveedor=cat_proveedor.id WHERE pedidos.id=$idpedido";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosDetalladosId($idpedido){
	$consulta="SELECT codigoproducto, producto, cantidad, precio, importe FROM pedidos_detalle WHERE idpedido=$idpedido";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosDetalleId($idpedido){
	$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.credito, pedidos.fechacreacion, pedidos.total, usuarios.nombre AS nombreUsuario,clientes.ciudad AS clienteCiudad, clientes.estado AS clienteEstado, clientes.nombre AS nombreCliente, CONCAT(clientes.calle,' ',clientes.numero) AS domicilio, clientes.colonia AS colonia, CONCAT(clientes.ciudad,' ',clientes.estado) AS ciudad, clientes.cp AS cp, clientes.telefono AS telefono FROM pedidos INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente WHERE pedidos.id=$idpedido";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getListaPedidos($fIni,$fFin,$tipo,$usuario,$sucursal){
	$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, clientes.codigo AS codigoCliente, cat_proveedor.nombre AS nombreProveedor, pedidos.idusuario, cat_rutas.ruta FROM pedidos INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente INNER JOIN cat_proveedor ON pedidos_detalle.idproveedor=cat_proveedor.id INNER JOIN cat_rutas ON usuarios.id=cat_rutas.chofer WHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidoIndividual($fIni,$fFin){
	$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente FROM pedidos INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idclienteWHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosJ($fIni,$fFin){
	$consulta="SELECT * FROM pedidos WHERE status=1 AND fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedido($id){
	$consulta="SELECT pedidos.id,pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, cat_sucursales.sucursal AS sucursal, pedidos.idcliente, pedidos.latitud, pedidos.longitud FROM pedidos INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente INNER JOIN cat_sucursales ON cat_sucursales.id=clientes.sucursal WHERE pedidos.id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
public function getDatosCliente($id){
	$consulta="SELECT * FROM clientes WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
public function getItemsJ($idpedido){
	$consulta="SELECT * FROM pedidos_detalle WHERE idpedido=$idpedido";
	$query=$this->db->query($consulta);
	return $query;
}
public function getCreadoporJ($idusuario){
	$consulta="SELECT * FROM usuarios WHERE id=$idusuario";
	$query=$this->db->query($consulta);
	return $query;
}
public function getClienteJ($id){
	$consulta="SELECT * FROM clientes WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
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
	public function delPerfil($id,$perfil){
		$consultaDEL="DELETE FROM perfiles WHERE id=$id";
		$this->db->query($consultaDEL);
		$consulta="SELECT id,perfiles FROM funciones";
		$query=$this->db->query($consulta);
		
		foreach ($query->result() as $kLista) {
			$id=$kLista->id;
			$perfiles=$kLista->perfiles;
			$perfiles=str_replace($perfil.",","", $perfiles);
			$consulta2="UPDATE funciones SET perfiles='$perfiles' WHERE id=$id";	
				$query=$this->db->query($consulta2);
		}
	}

}

?>