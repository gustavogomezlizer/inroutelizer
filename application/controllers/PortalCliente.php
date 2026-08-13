<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/Mazatlan');

class PortalCliente  extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','form', 'variables_helper', 'funcioneshtml'));
		$this->load->library(array('session', 'pagination'));
		//$this->load->model(array('CatalogosModel','ReportesModel'));
	}

	public function index()
	{
		if($_SERVER['HTTP_HOST'] == "pedidosfb.lizer.com.mx")
		{
			$arraydata = array(
				'empresa' => "01100480",
				'movil' => "1"
			);
		}
		
        $this->session->set_userdata($arraydata);

		$this->load->view('PortalCliente/login');
	}

	public function validacion_cliente_login()
	{
		if(!array_key_exists("idcliente", $this->input->post()))
		{
			redirect(LINKPROYECTO('PortalCliente'), 'refresh');
		}

		$codigocliente = $this->input->post("idcliente");
		$this->session->set_userdata('codigocliente', $codigocliente);

		redirect(LINKPROYECTO('PortalClienteVistaPrincipal'), 'refresh');
	}
	
	public function vista_principal()
	{
		if(empty($this->session->userdata("codigocliente")))
		{
			redirect(LINKPROYECTO('PortalCliente'), 'refresh');
		}

		$this->load->view('PortalCliente/vListaPedidos');
	}

	public function vista_nuevo_pedido()
	{
		if(empty($this->session->userdata("codigocliente")))
		{
			redirect(LINKPROYECTO('PortalCliente'), 'refresh');
		}

		$this->load->view('PortalCliente/vNuevoPedido');
	}
}