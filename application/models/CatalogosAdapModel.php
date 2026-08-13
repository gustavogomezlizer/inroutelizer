<?php class CatalogosAdapModel extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->TABLA_SEXO = "cat_sexo";
		$this->TABLA_TITULO = "cat_titulos";
		$this->TABLA_CODIGOSPOSTALES = "codigos_postales";
		$this->TABLA_TIPODOMICILIO = "cat_tipo_domicilio";
		$this->TABLA_TIPOCALLE = "cat_tipo_calle";
		$this->TABLA_TIPOMEDIOCOMUNICACION = "cat_tipo_medioscomunicacion";
		$this->TABLA_TIPOENVIO = "cat_tipo_envio";
		$this->TABLA_TIPOSERVICIO = "cat_tipo_servicio";
		$this->TABLA_TIPOENTREGA = "cat_tipo_entrega";
		$this->TABLA_MATERIALES = "cat_materiales";
		$this->TABLA_UNIDAD = "cat_unidad";
		$this->TABLA_MODULOS = "menu";
		$this->TABLA_SUBMODULOS = "submenu";
		$this->TABLA_SUBMODULOSPRINCIPAL = "modulos_sub_principal";
	}

	################## ESTAS FUNCIONES SIRVEN PARA LLENAR COMBOBOX Y LLEVAR DATOS ESPECIFICOS DE LAS DIFERENTE TABLAS ##################################################
	################## ESTAS FUNCIONES SE PUSIERON EN UN MISMO MODELO Y CONTROLADOR YA QUE ESTAS FUNCIONES SE UTILIZARAN ##################################################	
	################## EN MUCHAS VISTAS EL PROYECYO, AQUI SE AGREGARAN ESTE TIPO DE FUNCIONES ##################################################		

	public function getSexo()
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_SEXO." WHERE status = 1", false);
		return $query->result();
	}

	public function getInformacionByCp($pCp)
	{
		$query = $this->db->query("SELECT *, UPPER( CONCAT(SUBSTRING(tipoAsentamiento, 1,3), '. ', asentamiento) ) AS asentamiento2 FROM codigos_postales WHERE cp = $pCp", false);
		return $query->result();
	}

	public function getTitulos()
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_TITULO." WHERE status = 1", false);
		return $query->result();
	}

	public function getEstados()
	{
		$query = $this->db->query("SELECT idEstado, UPPER(estado) as estado FROM ".$this->TABLA_CODIGOSPOSTALES." GROUP BY idEstado ORDER BY estado", false);
		return $query->result();
	}

	public function getMunicipios($idEstado)
	{
		$query = $this->db->query("SELECT idMunicipio, UPPER(municipio) AS municipio FROM ".$this->TABLA_CODIGOSPOSTALES." WHERE idEstado = $idEstado GROUP BY idMunicipio ORDER BY municipio", false);
		return $query->result();
	}

	public function getCiudades($idEstado, $idMunicipio)
	{
		$query = $this->db->query("SELECT idCiudad, UPPER(ciudad) AS ciudad FROM ".$this->TABLA_CODIGOSPOSTALES." WHERE idEstado = $idEstado AND idMunicipio = $idMunicipio AND !ISNULL(idCiudad) GROUP BY idCiudad ORDER BY ciudad", false);
		return $query->result();
	}

	public function getColonias($idEstado, $idMunicipio, $idCiudad, $cp=0)
	{		
		if($cp == 0)
		{
			$SQL = "SELECT idAsentamiento, UPPER( CONCAT(SUBSTRING(tipoAsentamiento, 1,3), '. ', asentamiento) ) AS asentamiento FROM ".$this->TABLA_CODIGOSPOSTALES." WHERE idEstado = $idEstado AND idMunicipio = $idMunicipio AND idCiudad = $idCiudad GROUP BY idAsentamiento ORDER BY asentamiento ";
		}
		else
		{
			$SQL = "SELECT idAsentamiento, UPPER( CONCAT(SUBSTRING(tipoAsentamiento, 1,3), '. ', asentamiento) ) AS asentamiento FROM ".$this->TABLA_CODIGOSPOSTALES." WHERE idEstado = $idEstado AND idMunicipio = $idMunicipio AND idCiudad = $idCiudad GROUP BY idAsentamiento ORDER BY asentamiento ";
		}
		$query = $this->db->query($SQL, false);
		return $query->result();
	}

	public function getInfoCp($cp)
	{
		$query = $this->db->query("SELECT *, UPPER( CONCAT(SUBSTRING(tipoAsentamiento, 1,3), '. ', asentamiento) ) AS asentamiento2 FROM ".$this->TABLA_CODIGOSPOSTALES." WHERE cp = $cp", false);
		return $query->result();
	}

	public function getTipoDomicilio()
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_TIPODOMICILIO." WHERE STATUS = 1", false);
		return $query->result();
	}

	public function getTipoCalle()
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_TIPOCALLE." WHERE STATUS = 1", false);
		return $query->result();
	}

	public function getTipoMedioComunicacion()
	{ 
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_TIPOMEDIOCOMUNICACION." WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getTipoEnvio()
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_TIPOENVIO." WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getTipoServicio()
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_TIPOSERVICIO." WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getTipoEntrega()
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_TIPOENTREGA." WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getMateriales($pId=0)
	{
		if($pId == 0)
		{
			$query = $this->db->query("SELECT * FROM ".$this->TABLA_MATERIALES." WHERE STATUS = 1 ORDER BY descripcion", false);
			return $query->result();
		}
		else
		{
			$query = $this->db->query("SELECT * FROM ".$this->TABLA_MATERIALES." WHERE STATUS = 1 and id = $pId ORDER BY descripcion", false);
			return $query->row();	
		}	
	}

	public function getUnidades($pTipo)
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_UNIDAD." WHERE STATUS = 1 AND tipo = '$pTipo' ORDER BY descripcion", false);
		return $query->result();
	}

	public function getModulos()
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_MODULOS." WHERE STATUS = 1 ORDER BY orden", false);
		return $query->result();
	}

	public function getSubModulos($pIdModulo)
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_SUBMODULOS." WHERE STATUS = 1 AND idModulo = $pIdModulo ORDER BY orden", false);
		return $query->result();
	}

	public function getSubModulosPrincipal()
	{
		$query = $this->db->query("SELECT * FROM ".$this->TABLA_SUBMODULOSPRINCIPAL." WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getDocumentosAtributosRhSelect()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_atributos_rh WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getEstadoCivil()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_estadoCivil WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getTipoProveedor()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_proveedorTipo WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getPuestosProveedor()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_proveedorPuesto WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getTipoCasa()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_tipoCasa WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getRegimenesProveedores()
	{
		//$query = $this->db->query("SELECT id, descripcion FROM cat_regimen WHERE STATUS = 1 AND proveedores = 1 ORDER BY descripcion", false);
		$query = $this->db->query("SELECT id, descripcion FROM cat_regimen WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getRegimenesOficinas()
	{
		//$query = $this->db->query("SELECT id, descripcion FROM cat_regimen WHERE STATUS = 1 AND oficinas = 1 ORDER BY descripcion", false);
		$query = $this->db->query("SELECT id, descripcion FROM cat_regimen WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getTipoOficina()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_tipoOficina WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}
	
	public function getOficinas()
	{
		$query = $this->db->query("SELECT id, claveOficina as descripcion FROM cat_oficinas WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}
	
	public function getTiposRuta()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_rutasTipo WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}
	
	public function getOpciones()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_opciones WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}
	
	public function getParametros()
	{
		$query = $this->db->query("SELECT * FROM parametros", false);
		return $query->row();
	}

	public function getTipoSangre()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_tipoSangre WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getFamiliares()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_familiares WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getTipoOrden()
	{
		$query = $this->db->query("SELECT id, descripcion FROM cat_tipo_orden WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function getTipoReparacion()
	{
		$query = $this->db->query("SELECT * FROM cat_tipo_reparacion WHERE STATUS = 1 ORDER BY descripcion", false);
		return $query->result();
	}

	public function SaveConfiguracion($pDatos)
	{
		unset($pDatos["btnGuardar"]);
		$this->db->where("id", 1);
		$this->db->update("parametros",$pDatos);
		return 1;
	}
}

?>