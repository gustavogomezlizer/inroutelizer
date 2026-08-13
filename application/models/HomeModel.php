<?php
class HomeModel extends CI_Model {
 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();				
	}

	public function VerificacionUsuario($data)
	{
		$usuario = $data['usuario'];
		$clave = $data['clave'];

		if( isset($data['empresa']) ){
			$empresa = $data['empresa'];
		}else{
			$empresa = "";
		}		

		$consulta = "SELECT * FROM usuarios WHERE usuario=BINARY ? and clave=BINARY ? and empresa = ? and status = 1 ";
		$query = $this->db->query($consulta, [$usuario, $clave, $empresa]);	
		return $query;
	}

	public function getAccesosVer($perfil,$controlador,$funcion)
	{
		$consulta = "SELECT * FROM funciones WHERE controlador = ? AND funcion = ? AND perfiles LIKE ? AND status = 1";		
		$query = $this->db->query($consulta, [$controlador, $funcion, "%$perfil%"]);
		return $query->num_rows();
	}

	public function getModulos()
	{
		$query = $this->db->query("SELECT * FROM modulos WHERE STATUS = 1 ORDER BY orden", false);
		return $query->result();
	}

	public function getAllUsuariosSendEmail($pEmpresa)
	{
		$query = $this->db->query("SELECT correo FROM usuarios WHERE STATUS = 1 AND correo != '' AND perfil IN(2,18,3) AND empresa = ?", [$pEmpresa]);
		return $query->result();
	}

	public function getSubModulos($pIdModulo)
	{
		$query = $this->db->query("SELECT * FROM modulos_sub WHERE STATUS = 1 AND idModulo = ? ORDER BY orden", [$pIdModulo]);
		return $query;
	}

	public function getConexionEmpresa($empresa)
	{
		$consulta="SELECT * FROM empresas WHERE idCliente = ?";
		$query = $this->db->query($consulta, [$empresa]);
		return $query->row();
	}
	
	public function getVerificarPerfilPermiso($controlador,$funcion)
	{
		$consulta="SELECT * FROM funciones WHERE controlador = ? AND funcion = ?";
		$query=$this->db->query($consulta, [$controlador, $funcion]);
		return $query;
	}

	public function inicioSesionX($id)
	{
		$consulta="SELECT * FROM usuarios WHERE id = ?";
		$query = $this->db->query($consulta, [$id]);	
		return $query;
	}

	public function changeClave($user,$clave1)
	{
		$consulta="UPDATE usuarios SET clave = ?, nuevo = 0 WHERE usuario = ?";
		$this->db->query($consulta, [$clave1, $user]);
	}

	public function inicioSesionLiq($usuario,$clave,$empresa)
	{
		$bandera=0;
		$consulta="SELECT * FROM usuarios WHERE usuario = ? AND clave = ? AND empresa = ? AND status = 1 ";
		$query= $this->db->query($consulta, [$usuario, $clave, $empresa]);
		if($query->num_rows()!=0){
			$perfil=$this->getNamePerfil($query->row()->perfil);
		}
		else{
			$perfil="ERROR SESION";
		}
		return $perfil;
	}

	public function getDatosUsuario($id, $dato)
	{
		$consulta = "SELECT $dato FROM usuarios WHERE id = ?";
		$query = $this->db->query($consulta, [$id])->row();
		return $query;
	}

	public function getNamePerfil($id)
	{
		$consulta="SELECT * FROM perfiles WHERE id = ?";
		$query=$this->db->query($consulta, [$id]);
		foreach ($query->result() as $k) {
			$nombre=$k->perfil;
		}
		return $nombre;
	}
}

?>