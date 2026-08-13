<?php
class AdministracionModel extends CI_Model {

	private $dbinfo;
	 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		if($this->session->has_userdata('movil'))
		{
			$config_app = switch_db_dinamico(GETEMPRESA(), 1);
		}
		else
		{
			$config_app = switch_db_dinamico(GETEMPRESA());
		}
		
		$this->dbinfo = $this->load->database($config_app, TRUE);
	}

	public function getListadoGastosSucursal($data)
	{
		$query = $this->dbinfo->query("SELECT ccg.id AS gasto_id, ccg.`descripcion`, gs.*,
		(SELECT cs.sucursal FROM cat_sucursales cs WHERE cs.id = '$data[sucursal]') AS sucursal,
		'$data[sucursal]' AS sucursal_id,
		'$data[negocio]' AS negocio_id,
		'$data[periodo]' AS periodo_seleccionado
		FROM cat_conceptos_gastos ccg
		LEFT JOIN gastos_sucursal gs ON ccg.`id` = gs.`idgasto` AND gs.`idsucursal` = '$data[sucursal]' AND gs.`periodo` = '$data[periodo]' AND gs.idnegocio = '$data[negocio]'
		ORDER BY ccg.`descripcion`")->result();

		foreach ($query as $key => $value)
		{
			$query[$key]->importe = is_null($value->importe) ? 0 : $value->importe;
		}

		return $query;
	}

	public function saveGastoSucursal($data)
	{
		foreach($data["items"] as $item)
		{
			$idgasto = $item["gasto_id"];
			$gasto = $item["descripcion"];
			$idsucursal = $item["sucursal_id"];
			$idnegocio = $item["negocio_id"];
			$periodo = $item["periodo_seleccionado"];
			$importe = $item["importe"];

			$row = $this->dbinfo->query("SELECT * FROM gastos_sucursal WHERE idgasto = '$idgasto' AND periodo = '$periodo' AND idsucursal = '$idsucursal' AND idnegocio = '$idnegocio'");

			if($row->num_rows() > 0)
			{
				$id = $row->row()->id;

				$this->dbinfo->where("id", $id);
				$this->dbinfo->update('gastos_sucursal',
					array(
						"idgasto" => $idgasto, 
						"gasto" => $gasto,
						"idsucursal" => $idsucursal,
						"idnegocio" => $idnegocio,
						"periodo" => $periodo,
						"importe" => $importe,
						"fechahora_registro" => GETFECHAHORA(),
						"idusuario_registro" => GETIDUSUARIO()
					)
				);
			}
			else
			{
				$cantidadrutas = $this->dbinfo->query("SELECT COUNT(*) AS rutas FROM cat_rutas WHERE STATUS = 1 AND sucursal = '$idsucursal'")->row();

				$this->dbinfo->insert('gastos_sucursal',
					array(
						"idgasto" => $idgasto, 
						"gasto" => $gasto,
						"idsucursal" => $idsucursal,
						"idnegocio" => $idnegocio,
						"periodo" => $periodo,
						"importe" => $importe,
						"rutaslaboradas" => $cantidadrutas->rutas,
						"fechahora_registro" => GETFECHAHORA(),
						"idusuario_registro" => GETIDUSUARIO()
					)
				);
			}
		}

		return "1";
	}

	public function getListadoOtrosIngresos($data)
	{
		$query = $this->dbinfo->query("SELECT ccg.id AS gasto_id, ccg.`descripcion`, gs.*,
		(SELECT cs.sucursal FROM cat_sucursales cs WHERE cs.id = '$data[sucursal]') AS sucursal,
		'$data[sucursal]' AS sucursal_id,
		'$data[negocio]' AS negocio_id,
		'$data[periodo]' AS periodo_seleccionado
		FROM cat_conceptos_ingresos ccg
		LEFT JOIN ingresos_sucursal gs ON ccg.`id` = gs.`idgasto` AND gs.`idsucursal` = '$data[sucursal]' AND gs.`periodo` = '$data[periodo]' AND gs.idnegocio = '$data[negocio]'
		ORDER BY ccg.`descripcion`")->result();

		foreach ($query as $key => $value)
		{
			$query[$key]->importe = is_null($value->importe) ? 0 : $value->importe;
		}

		return $query;
	}

	public function saveIngresoSucursal($data)
	{
		foreach($data["items"] as $item)
		{
			$idgasto = $item["gasto_id"];
			$gasto = $item["descripcion"];
			$idsucursal = $item["sucursal_id"];
			$idnegocio = $item["negocio_id"];
			$periodo = $item["periodo_seleccionado"];
			$importe = $item["importe"];

			$row = $this->dbinfo->query("SELECT * FROM ingresos_sucursal WHERE idgasto = '$idgasto' AND periodo = '$periodo' AND idsucursal = '$idsucursal' AND idnegocio = '$idnegocio'");

			if($row->num_rows() > 0)
			{
				$id = $row->row()->id;

				$this->dbinfo->where("id", $id);
				$this->dbinfo->update('ingresos_sucursal',
					array(
						"idgasto" => $idgasto, 
						"gasto" => $gasto,
						"idsucursal" => $idsucursal,
						"idnegocio" => $idnegocio,
						"periodo" => $periodo,
						"importe" => $importe,
						"fechahora_registro" => GETFECHAHORA(),
						"idusuario_registro" => GETIDUSUARIO()
					)
				);
			}
			else
			{
				$cantidadrutas = $this->dbinfo->query("SELECT COUNT(*) AS rutas FROM cat_rutas WHERE STATUS = 1 AND sucursal = '$idsucursal'")->row();

				$this->dbinfo->insert('ingresos_sucursal',
					array(
						"idgasto" => $idgasto, 
						"gasto" => $gasto,
						"idsucursal" => $idsucursal,
						"idnegocio" => $idnegocio,
						"periodo" => $periodo,
						"importe" => $importe,
						"rutaslaboradas" => $cantidadrutas->rutas,
						"fechahora_registro" => GETFECHAHORA(),
						"idusuario_registro" => GETIDUSUARIO()
					)
				);
			}
		}

		return "1";
	}

	public function getListadoPresupuesto($data)
	{
		$query = $this->dbinfo->query("SELECT ccg.id AS presupuesto_id, ccg.`mes` AS presupuesto_mes, ccg.`periodo` AS mes_numero, gs.*,
		(SELECT cs.sucursal FROM cat_sucursales cs WHERE cs.id = '$data[sucursal]') AS sucursal,
		'$data[sucursal]' AS sucursal_id,
		'$data[negocio]' AS negocio_id,
		'$data[anio]' AS periodo_seleccionado
		FROM cat_periodos ccg
		LEFT JOIN presupuesto_periodo gs ON ccg.`id` = gs.`idperiodo` AND gs.`idsucursal` = '$data[sucursal]' AND gs.`anio` = '$data[anio]' AND gs.idnegocio = '$data[negocio]'")->result();

		//die($this->dbinfo->last_query());

		foreach ($query as $key => $value)
		{
			$presupuesto_ventas = is_null($value->presupuesto_ventas) ? 0 : $value->presupuesto_ventas;
			$presupuesto_costos = is_null($value->presupuesto_costos) ? 0 : $value->presupuesto_costos;
			$presupuesto_gastos = is_null($value->presupuesto_gastos) ? 0 : $value->presupuesto_gastos;
			$presupuesto_otrosingresos = is_null($value->presupuesto_otrosingresos) ? 0 : $value->presupuesto_otrosingresos;
			$presupuesto_utilidadoperativa = ($presupuesto_ventas + $presupuesto_otrosingresos) - ($presupuesto_costos + $presupuesto_gastos);

			$query[$key]->presupuesto_ventas = $presupuesto_ventas;
			$query[$key]->presupuesto_costos = $presupuesto_costos;
			$query[$key]->presupuesto_gastos = $presupuesto_gastos;
			$query[$key]->presupuesto_otrosingresos = $presupuesto_otrosingresos;
			$query[$key]->presupuesto_utilidadoperativa = $presupuesto_utilidadoperativa;
		}

		return $query;
	}

	public function savePresupuestos($data)
	{
		foreach($data["items"] as $item)
		{
			$idperiodo = $item["presupuesto_id"];
			$anio = $item["periodo_seleccionado"];
			$mes = $item["mes_numero"];
			$idsucursal = $item["sucursal_id"];
			$idnegocio = $item["negocio_id"];
			$presupuestoventas = $item["presupuesto_ventas"];
			$presupuestocostos = $item["presupuesto_costos"];
			$presupuestogastos = $item["presupuesto_gastos"];
			$presupuestootrosingresos = $item["presupuesto_otrosingresos"];

			$row = $this->dbinfo->query("SELECT * FROM presupuesto_periodo WHERE idperiodo = '$idperiodo' AND anio = '$anio' AND mes = '$mes' AND idsucursal = '$idsucursal' AND idnegocio = '$idnegocio'");

			if($row->num_rows() > 0)
			{
				$id = $row->row()->id;

				$this->dbinfo->where("id", $id);
				$this->dbinfo->update('presupuesto_periodo',
					array(
						"idperiodo" => $idperiodo, 
						"anio" => $anio,
						"mes" => $mes, 
						"idsucursal" => $idsucursal,
						"idnegocio" => $idnegocio,
						"presupuesto_ventas" => $presupuestoventas,
						"presupuesto_costos" => $presupuestocostos,
						"presupuesto_gastos" => $presupuestogastos,
						"presupuesto_otrosingresos" => $presupuestootrosingresos,
						"fechahora_registro" => GETFECHAHORA(),
						"idusuario_registro" => GETIDUSUARIO()
					)
				);
			}
			else
			{
				//$cantidadrutas = $this->dbinfo->query("SELECT COUNT(*) AS rutas FROM cat_rutas WHERE STATUS = 1 AND sucursal = '$idsucursal'")->row();

				$this->dbinfo->insert('presupuesto_periodo',
					array(
						"idperiodo" => $idperiodo, 
						"anio" => $anio,
						"mes" => $mes, 
						"idsucursal" => $idsucursal,
						"idnegocio" => $idnegocio,
						"presupuesto_ventas" => $presupuestoventas,
						"presupuesto_costos" => $presupuestocostos,
						"presupuesto_gastos" => $presupuestogastos,
						"presupuesto_otrosingresos" => $presupuestootrosingresos,
						"fechahora_registro" => GETFECHAHORA(),
						"idusuario_registro" => GETIDUSUARIO()
					)
				);
			}
		}

		return "1";
	}

	public function getListadoNc($data)
	{
		setlocale(LC_MONETARY, 'es_MX');

		$query = $this->dbinfo->query("SELECT *,
		(SELECT cs.sucursal FROM cat_sucursales cs WHERE cs.id = nc.`idsucursal`) AS sucursal,
		(SELECT cs.nombre FROM cat_proveedor cs WHERE cs.id = nc.`negocio`) AS negocio_nombre
		FROM nc
		WHERE nc.`fecha_recepcion` BETWEEN '$data[fecha_inicio]' AND '$data[fecha_final]' AND nc.`idsucursal` IN($data[sucursal]) AND FIND_IN_SET(tipo, '$data[tipo]') AND FIND_IN_SET(negocio, '$data[negocio]')")->result();

		foreach ($query as $key => $value)
		{
			$query[$key]->importe_factura = money_format_2($value->importe_factura);
			$query[$key]->importe_nc = money_format_2($value->importe_nc);
			$query[$key]->importe_total =  money_format_2($value->importe_total);
		}

		return $query;
	}

	public function getNcById($id)
	{
		$query = $this->dbinfo->query("SELECT * FROM nc WHERE id = '$id'")->row();

		return $query;
	}

	public function saveNc($data)
	{
		$id = $data["id"];
		$fecha_recepcion = $data["fecharecepcion"];
		$fecha_pago = $data["fechapago"];
		$negocio = $data["negocio"];
		$tipo = $data["cmbTipo"];
		$idsucursal = $data["cmbSucursal"];
		$factura = $data["factura"];
		$importe_factura = $data["importefactura"];
		$importenc = $data["importenc"];
		$importe_total = $data["importetotal"];
		$numero_nc = $data["numeronc"];
		$idusuario_registro = GETIDUSUARIO();
		$fechahora_registro = GETFECHAHORA();

		//$row = $this->dbinfo->query("SELECT * FROM nc WHERE numero_nc = '$numero_nc' AND factura = '$factura'");

		if($id > 0)
		{
			$this->dbinfo->where("id", $id);
			$this->dbinfo->update('nc',
				array(
					"fecha_recepcion" => $fecha_recepcion, 
					"fecha_pago" => $fecha_pago,
					"negocio" => $negocio,
					"tipo" => $tipo,
					"idsucursal" => $idsucursal, 
					"factura" => $factura,
					"importe_factura" => $importe_factura,
					"importe_nc" => $importenc,
					"importe_total" => $importe_total,
					"numero_nc" => $numero_nc,
					"fechahora_registro" => $fechahora_registro,
					"idusuario_registro" => $idusuario_registro
				)
			);
		}
		else
		{
			$this->dbinfo->insert('nc',
				array(
					"fecha_recepcion" => $fecha_recepcion, 
					"fecha_pago" => $fecha_pago,
					"negocio" => $negocio,
					"tipo" => $tipo,
					"idsucursal" => $idsucursal, 
					"factura" => $factura,
					"importe_factura" => $importe_factura,
					"importe_nc" => $importenc,
					"importe_total" => $importe_total,
					"numero_nc" => $numero_nc,
					"fechahora_registro" => $fechahora_registro,
					"idusuario_registro" => $idusuario_registro
				)
			);

			$id = $this->dbinfo->insert_id();
		}

		return $id;
	}
}
?>