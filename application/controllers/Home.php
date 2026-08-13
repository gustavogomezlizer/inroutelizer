<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	private $hoy;
	private $hora;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','form', 'variables_helper', 'funcioneshtml'));
		$this->load->library(array('session', 'pagination'));
		$this->load->model(array('HomeModel'));

		date_default_timezone_set('America/Mazatlan');
		$this->hoy = date('Y-m-d');
		$this->hora = date('H:i:s',time());
	}

	public function index()
	{
		/*//die("<h1 align='center'>Building Page</h1>");
		$data['title']="Sistema de Distribucion - LIZER";
		$data['subtitle']="Sistema de Distribucion";
		$data['vie_isi']="view_home";
			
		$bandera=0;
		$this->elLogin($bandera);*/

		if($_SERVER['HTTP_HOST'] == "pedidos.lizer.com.mx")
		{
			redirect(LINKPROYECTO("PortalCliente"), 'refresh');
		}
		else
		{
			if($this->session->has_userdata('user'))		
			{
				redirect(LINKPROYECTO("Principal"), 'refresh');
			}
			else
			{			
				$this->elLogin();
			}
		}
	}

	public function elLogin($bandera=0)
	{
		$this->load->view('Login/login');
	}

	
	public function cambiarClave(){
		//print_r($_POST);
		$user=$_POST['user'];
		$clave1=$_POST['clave1'];
		
		$this->HomeModel->changeClave($user,$clave1);
	}

	public function cerrarSesion()
	{
		$keys=array('user','clave','nombre','puesto','nuevo','perfil','idperfil','userId','foto','fecha','hora', 'sucursal', 'limiteSucursal', 'empresa');
		$this->session->unset_userdata($keys);
		$this->session->sess_destroy();
    	$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
    	$this->output->set_header("Pragma: no-cache");
		redirect(LINKPROYECTO('Verificacion'), 'refresh');
	}

	public function inicioLogin()
	{
		$data = $this->input->post();		
		$dataUser = $this->HomeModel->VerificacionUsuario($data);		

		if($dataUser->num_rows() > 0)
		{
			$user = $dataUser->row()->usuario;
			$clave = $dataUser->row()->clave;
			$nombre = $dataUser->row()->nombre;
			$puesto = $dataUser->row()->puesto;
			$idperfil = $dataUser->row()->perfil;
			$nuevo = $dataUser->row()->nuevo;
			$userId = $dataUser->row()->id;
			$foto = $dataUser->row()->foto;
			$sucursal = $dataUser->row()->sucursal;
			$ls = $dataUser->row()->multisucursal;
			$empresa = $dataUser->row()->empresa;
			$fecha = $this->hoy;
			$hora = $this->hora;

			$perfil = $this->HomeModel->getNamePerfil($idperfil);			

			$arraydata = array(
				'user' => $user,
				'clave' => $clave,
				'nombre'  => $nombre,
				'puesto' => $puesto,
				'idperfil'=>$idperfil,
				'perfil'=>$perfil,
				'userId'=>$userId,
				'foto'=>$foto,
				'hora'=>$hora,
				'fecha'=>$fecha,	                
				'sucursal'=>$sucursal,
				'limiteSucursal'=>$ls,
				'empresa'=>$empresa
			);
			
	        $this->session->set_userdata($arraydata);
			redirect(LINKPROYECTO("Principal"), 'refresh');
		}
		else
		{
			redirect(LINKPROYECTO('Verificacion'), 'refresh');
		}

	}
}
