<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administracion extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','form', 'variables_helper', 'funcioneshtml'));
		$this->load->library(array('session', 'pagination'));
		$this->load->model(array('ReportesModel', 'AdministracionModel', 'CatalogosModel'));		
	}
			
	public function index()
	{
		$this->principal();
	}

	public function control_gastos()
	{
		VERIFICARSESION();

		$fecha1 = date('Y-m-d');
		
		$periodoC = explode("-",$fecha1);
		$periodo = $periodoC[0].$periodoC[1];

		$data["periodo"] = $periodo;

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();
		
		$this->load->view('Administracion/vControlGastos', $data);
	}

	public function getListadoGastosSucursal()
	{
		$data = $this->input->post();
		echo json_encode($this->AdministracionModel->getListadoGastosSucursal($data));
	}

	public function saveGastoSucursal()
	{
		$data = $this->input->post();
		
		echo $this->AdministracionModel->saveGastoSucursal($data);
	}

	public function otros_ingresos()
	{
		VERIFICARSESION();

		$fecha1 = date('Y-m-d');
		
		$periodoC = explode("-",$fecha1);
		$periodo = $periodoC[0].$periodoC[1];

		$data["periodo"] = $periodo;

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();

		$this->load->view('Administracion/vOtrosIngresos', $data);
	}

	public function getListadoOtrosIngresos()
	{
		$data = $this->input->post();
		echo json_encode($this->AdministracionModel->getListadoOtrosIngresos($data));
	}

	public function saveIngresoSucursal()
	{
		$data = $this->input->post();
		
		echo $this->AdministracionModel->saveIngresoSucursal($data);
	}

	public function presupuesto()
	{
		VERIFICARSESION();

		$fecha1 = date('Y-m-d');
		
		$periodoC = explode("-",$fecha1);
		$periodo = $periodoC[0];//.$periodoC[1];

		$data["periodo"] = $periodo;

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();

		$this->load->view('Administracion/vPresupuesto', $data);
	}

	public function getListadoPresupuesto()
	{
		$data = $this->input->post();
		echo json_encode($this->AdministracionModel->getListadoPresupuesto($data));
	}

	public function savePresupuestos()
	{
		$data = $this->input->post();
		
		echo $this->AdministracionModel->savePresupuestos($data);
	}

	public function capturanc()
	{
		VERIFICARSESION();

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();

		$this->load->view('Administracion/vCapturaNc', $data);
	}

	public function editarnc($id)
	{
		VERIFICARSESION();

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();
		$data["infonc"] = $this->AdministracionModel->getNcById($id);

		$this->load->view('Administracion/vCapturaNc', $data);
	}

	public function reportenc()
	{
		VERIFICARSESION();

		/*$fecha1 = date('Y-m-d');
		
		$periodoC = explode("-",$fecha1);
		$periodo = $periodoC[0];//.$periodoC[1];

		$data["periodo"] = $periodo;*/

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();

		$this->load->view('Administracion/vReporteNc', $data);
	}

	public function getListadoNc()
	{
		$data = $this->input->post();
		echo json_encode($this->AdministracionModel->getListadoNc($data));
	}

	public function saveNc()
	{
		$data = $this->input->post();
		echo $this->AdministracionModel->saveNc($data);
	}
}