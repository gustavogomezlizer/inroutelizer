<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configurar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','form', 'variables_helper', 'funcioneshtml'));
		$this->load->library(array('session', 'pagination'));
		$this->load->model(array('HomeModel','CatalogosModel','CatalogosAdapModel','ConfigurarModel'));
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->principal();
	}

	public function principal()
	{
		VERIFICARSESION();
		$data['datosConf'] = $this->ConfigurarModel->getConfiguracion();
		$data['listaPerfiles'] = $this->ConfigurarModel->getListaPerfiles();
		$this->load->view('Configurar/vPrincipal',$data);
	}

	public function datosEmpresas()
	{
		$data['datosConf'] = $this->ConfigurarModel->getConfiguracion();
		$this->load->view('Configurar/vDatosEmpresas', $data);
	}

	public function listaPerfiles()
	{
		$data['listaPerfiles'] = $this->ConfigurarModel->getListaPerfiles();
		$this->load->view('Configurar/vPerfiles', $data);
	}

	public function objetivosContrato()
	{
		$data['objetivos'] = $this->CatalogosModel->getValoresContrato();
		$this->load->view('Configurar/vObjetivosContrato', $data);
	}

	public function correosSellout()
	{
		$data['correos'] = $this->CatalogosModel->getCorreosSellout();
		$this->load->view('Configurar/vCorreosSellOut', $data);
	}

	public function nuevoPerfil()
	{
		VERIFICARSESION();
		$data['listaModulos']=$this->ConfigurarModel->getListaModulos();
		$this->load->view('Configurar/vNewPerfil',$data);
	}

	public function saveNuevoPerfil()
	{
		$datos=$this->input->post();
		$this->ConfigurarModel->saveNewPerfil($datos);
		?>
		<script>window.close();</script>
		<?php 
	}

	public function editarPerfil($id)
	{
		$data['listaModulos']=$this->ConfigurarModel->getListaModulos();
		$data['datosPerfil']=$this->ConfigurarModel->getDatosPerfil($id);
		$this->load->view('Configurar/vEditPerfil',$data);
	}

	public function saveEditarPerfil()
	{
		$datos=$this->input->post();
		$this->ConfigurarModel->saveEditPerfil($datos);
		?>
		<script>window.close();</script>
		<?php 
	}

	public function saveConfigurar()
	{
		$datos = $this->input->post();
		$datos["logo"] = "";
		$datos["utiliza_impresora"] = isset($datos["utiliza_impresora"]) ? "1" : "0";
		$datos["validacion_inventario"] = isset($datos["validacion_inventario"]) ? "1" : "0";

		//print_r($datos);die();

		$archivoimg = (isset($_FILES['logo'])) ? $_FILES['logo'] : null;

		if (($archivoimg) and (!empty($archivoimg['name'])))
		{
			$ruta_destino_archivo = "assets/images/logos/".GETEMPRESA()."_Logotipo.jpg";
			$archivo_ok = move_uploaded_file($archivoimg['tmp_name'], $ruta_destino_archivo);
			$datos["logo"] = base_url($ruta_destino_archivo);
		}

		$this->ConfigurarModel->saveNewConfigurar($datos);

		redirect(LINKPROYECTO("DatosEmpresas",'refresh'));
	}

	public function verPerfil($id)
	{
		$data['listaModulos'] = $this->ConfigurarModel->getListaModulos();
		$data['datosPerfil'] = $this->ConfigurarModel->getDatosPerfil($id);
		$this->load->view('Configurar/vVerPerfil',$data);
	}

	public function borrarPerfil($id,$perfil)
	{
		$this->ConfigurarModel->delPerfil($id,$perfil);
		redirect(CCONFIGURAR(),'refresh');
	}

	public function saveObjetivosContrato()
	{
		$datos = $this->input->post();

		$this->CatalogosModel->saveObjetivosContrato($datos);

		redirect(LINKPROYECTO("ObjetivosContrato",'refresh'));
	}

	public function saveCorreoSellout()
	{
		$datos = $this->input->post();

		$this->CatalogosModel->saveCorreoSellout($datos);

		redirect(LINKPROYECTO("CorreosSellout",'refresh'));
	}
}