<?php
class ConfigurarModel extends CI_Model {
	private $dbG;
	//private $dbinfo;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		/*$config_app = switch_db_dinamico(GETEMPRESA());
		$this->dbinfo = $this->load->database($config_app, TRUE);*/
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
	public function getConfiguracion(){
		$idempresa=GETEMPRESA();
		$consulta="SELECT * FROM empresas WHERE idCliente='$idempresa'";
		$query=$this->db->query($consulta);
		return $query;
	}

	public function saveNewConfigurar($datos)
	{
		$idempresa = GETEMPRESA();
		$nombre = $datos['txtRs'];
		$nombrecorto = $datos['txtNombreComercial'];
		$domicilio = $datos['txtDomicilio'];
		$telefono = $datos['txtTelefono'];
		$correo = $datos['txtCorreo'];
		$logo = $datos["logo"];
		$utiliza_impresora = $datos["utiliza_impresora"];
		$validacion_inventario = $datos["validacion_inventario"];

		if($logo == "")
		{
			$consulta = "UPDATE empresas SET 
			nombre='$nombre', nombrecorto='$nombrecorto', 
			domicilio='$domicilio', telefono='$telefono', 
			correo='$correo', utiliza_impresora='$utiliza_impresora', validacion_inventario='$validacion_inventario'
			WHERE idCliente='$idempresa'";
		}
		else
		{
			$consulta = "UPDATE empresas SET 
			nombre='$nombre', nombrecorto='$nombrecorto', 
			domicilio='$domicilio', telefono='$telefono', 
			correo='$correo', utiliza_impresora='$utiliza_impresora', validacion_inventario='$validacion_inventario', logo = '$logo'
			WHERE idCliente='$idempresa'";
		}		

		$this->db->query($consulta);
	}

}

?>