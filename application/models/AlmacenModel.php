<?php
class AlmacenModel extends CI_Model {

	private $dbinfo;
	 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		$config_app = switch_db_dinamico(GETEMPRESA());
		$this->dbinfo = $this->load->database($config_app, TRUE);
	}

	public function getRutasEstatusJson($idsucursal, $fecha)
	{
		//$fecha = GETFECHA();

		$consulta = "SELECT cr.`id`, cr.`ruta`,
		IF(ISNULL(cr.`fecha_cierre_armado_ruta`),'DISPONIBLE', IF(cr.`fecha_cierre_armado_ruta`='$fecha', 'CERRADO', 'DISPONIBLE')) AS estatus,
		(SELECT IF(COUNT(*) > 0, 'CERRADO', 'ABIERTO') FROM cierres_confirmacion_entregas cce WHERE cce.`idsucursal` = '$idsucursal' AND cce.`fecha` = '$fecha') as estatus_confirmacion_entregas
		FROM cat_rutas cr
		WHERE cr.`status` = 1 AND cr.`sucursal` = '$idsucursal'";

		$query = $this->dbinfo->query($consulta)->result_array();
		return $query;
	}

	public function actualizarEstatusRuta($idruta, $estatus)
	{
		if($estatus == 1)
			$fecha = GETFECHA();
		else
			$fecha = null;

		$this->dbinfo->where("id", $idruta);
		$this->dbinfo->update("cat_rutas", array("fecha_cierre_armado_ruta" => $fecha));

		return 1;
	}

	public function actualizarEstatusRutaTodas($idsucursal)
	{
		$this->dbinfo->where("sucursal", $idsucursal);
		$this->dbinfo->update("cat_rutas", array("fecha_cierre_armado_ruta" => GETFECHA()));

		return 1;
	}

	public function borrarConfirmacionEntregas($idsucursal, $fecha)
	{
		$this->dbinfo->where(array("idsucursal" => $idsucursal, "fecha" => $fecha));
		$this->dbinfo->delete("cierres_confirmacion_entregas");

		return 1;
	}

	public function getReportePedidosJson($fecha, $idsucursal)
	{
		$consulta = "SELECT *,
		COUNT(DISTINCT p.`folio`) AS cantidad_pedidos,
		COALESCE((SELECT SUM((glo.cantidad_entregado - glo.cantidad_rechazado) * glo.precio) FROM vwInformacionGeneralPedidos glo WHERE glo.status_principal = 1 AND glo.status_detalle = 1 AND glo.`tipo` = 'PREVENTA' AND glo.`fecha` = p.fecha AND glo.`ruta` = p.ruta),0) AS total_preventa,
		COALESCE((SELECT SUM((glo.cantidad_entregado - glo.cantidad_rechazado) * glo.precio) FROM vwInformacionGeneralPedidos glo WHERE glo.status_principal = 1 AND glo.status_detalle = 1 AND glo.`tipo` = 'DEVOLUCION' AND glo.`fecha` = p.fecha AND glo.`ruta` = p.ruta),0) AS total_devolucion,
		SUM((p.cantidad_entregado - p.cantidad_rechazado) * p.precio) AS total
		FROM vwInformacionGeneralPedidos p
		WHERE p.tipo = 'PREVENTA' AND p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha = '$fecha' AND p.idsucursal = '$idsucursal'
		GROUP BY p.ruta ORDER BY p.ruta_nombre";

		if(in_array($idsucursal, array(13,17)))
		{
			$consulta = "SELECT *,
			COUNT(DISTINCT p.`folio`) AS cantidad_pedidos,
			COALESCE((SELECT SUM((glo.cantidad_entregado - glo.cantidad_rechazado) * glo.precio) FROM vwInformacionGeneralPedidos glo WHERE glo.status_principal = 1 AND glo.status_detalle = 1 AND glo.`tipo` = 'PREVENTA' AND glo.`fecha` = p.fecha AND glo.`ruta` = p.ruta),0) AS total_preventa,
			COALESCE((SELECT SUM((glo.cantidad_entregado - glo.cantidad_rechazado) * glo.precio) FROM vwInformacionGeneralPedidos glo WHERE glo.status_principal = 1 AND glo.status_detalle = 1 AND glo.`tipo` = 'DEVOLUCION' AND glo.`fecha` = p.fecha AND glo.`ruta` = p.ruta),0) AS total_devolucion,
			SUM((p.cantidad_entregado - p.cantidad_rechazado) * p.precio) AS total
			FROM vwInformacionGeneralPedidos p
			WHERE p.tipo = 'PREVENTA' AND p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha = '$fecha' AND p.idsucursal in(13,17)
			GROUP BY p.ruta ORDER BY p.ruta_nombre";
		}

		$query = $this->dbinfo->query($consulta)->result_array();
		return $query;
	}

	public function getInfoReporteOtReparto($fecha, $idsucursal)
	{
		$consulta = "SELECT *,
		(SUM(cantidad_entregado) - SUM(cantidad_rechazado)) AS cantidad_real_entregado,
		COALESCE((SELECT`arr`.`idusuario` FROM `asi_reparto_rutas` `arr` WHERE `arr`.`status` = 1 AND `arr`.`idruta` = `p`.`ruta`), 0) AS `id_usuario_reparto`,
		COALESCE((SELECT GROUP_CONCAT(fnGetRutaById(`arr`.`idruta`)) FROM `asi_reparto_rutas` `arr` WHERE `arr`.`status` = 1 AND `arr`.`idusuario` = (SELECT id_usuario_reparto)), 0) AS rutas_nombres,
		(SELECT cp.tipo2 FROM cat_productos cp WHERE cp.id = p.`iditem`) AS tipo_producto
		FROM vwInformacionGeneralPedidos p
		WHERE p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha = '$fecha' AND p.idsucursal = '$idsucursal'
		GROUP BY id_usuario_reparto, p.tipo, p.iditem
		ORDER BY p.tipo, id_usuario_reparto, p.producto";

		if(in_array($idsucursal, array(13,17))) 
		{
			$consulta = "SELECT *,
			(SUM(cantidad_entregado) - SUM(cantidad_rechazado)) AS cantidad_real_entregado,
			COALESCE((SELECT`arr`.`idusuario` FROM `asi_reparto_rutas` `arr` WHERE `arr`.`status` = 1 AND `arr`.`idruta` = `p`.`ruta`), 0) AS `id_usuario_reparto`,
			COALESCE((SELECT GROUP_CONCAT(fnGetRutaById(`arr`.`idruta`)) FROM `asi_reparto_rutas` `arr` WHERE `arr`.`status` = 1 AND `arr`.`idusuario` = (SELECT id_usuario_reparto)), 0) AS rutas_nombres,
			(SELECT cp.tipo2 FROM cat_productos cp WHERE cp.id = p.`iditem`) AS tipo_producto
			FROM vwInformacionGeneralPedidos p
			WHERE p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha = '$fecha' AND p.idsucursal in(13,17)
			GROUP BY id_usuario_reparto, p.tipo, p.iditem
			ORDER BY p.tipo, id_usuario_reparto, p.producto";

		}

		$query = $this->dbinfo->query($consulta)->result_array();
		return $query;
	}

	public function getInfoReporteOts($fecha, $idsucursal)
	{
		$consulta = "SELECT *,
		(SUM(cantidad_entregado) - SUM(cantidad_rechazado)) AS cantidad_real_entregado,
		(SELECT cp.tipo2 FROM cat_productos cp WHERE cp.id = p.`iditem`) AS tipo_producto
		FROM vwInformacionGeneralPedidos p
		WHERE p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha = '$fecha' AND p.idsucursal = '$idsucursal'
		GROUP BY p.ruta, p.tipo, p.iditem
		ORDER BY p.ruta_nombre, p.tipo, p.`producto`";

		$query = $this->dbinfo->query($consulta)->result_array();
		return $query;
	}

	public function getInfoReporteLibros($fecha, $idsucursal)
	{
		$consulta = "SELECT *,
		(cantidad_entregado - cantidad_rechazado) AS cantidad_real_entregado,
		(SELECT cp.tipo2 FROM cat_productos cp WHERE cp.id = p.`iditem`) AS tipo_producto
		FROM vwInformacionGeneralPedidos p
		WHERE p.status_principal = 1 AND p.status_detalle = 1 AND p.fecha = '$fecha' AND p.idsucursal = '$idsucursal'
		GROUP BY p.id_detalle
		ORDER BY p.ruta_nombre, p.tipo, p.fechacreacion, p.`producto`";

		$query = $this->dbinfo->query($consulta)->result_array();
		return $query;
	}

	public function getComponentesPaquete()
	{
		$consulta = "SELECT *,
		(SELECT cp.nombre FROM cat_productos cp WHERE cp.id = componentes_paquete.`idproducto`) AS nombre
		FROM componentes_paquete";

		$query = $this->dbinfo->query($consulta)->result_array();
		return $query;
	}
}

?>