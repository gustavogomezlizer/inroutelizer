<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Almacen extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','form', 'variables_helper', 'funcioneshtml'));
		$this->load->library(array('session', 'pagination'));
		$this->load->model(array('HomeModel','CatalogosModel','ConfigurarModel','ReportesModel', 'AlmacenModel'));		
	}

	public function ArmadoRuta()
	{
		VERIFICARSESION();

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$this->load->view('Almacen/vArmadoRuta', $data);
	}

	public function ReportePedidos()
	{
		VERIFICARSESION();

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$this->load->view('Almacen/vReportePedidos', $data);
	}

	public function getRutasEstatusJson($idsucursal, $fecha)
	{
		echo json_encode($this->AlmacenModel->getRutasEstatusJson($idsucursal, $fecha));
	}

	public function actualizarEstatusRuta()
	{
		$idruta = $this->input->post("idruta");
		$estatus = $this->input->post("estatus");
		echo json_encode($this->AlmacenModel->actualizarEstatusRuta($idruta, $estatus));
	}

	public function actualizarEstatusRutaTodas()
	{
		$idsucursal = $this->input->post("idsucursal");
		echo json_encode($this->AlmacenModel->actualizarEstatusRutaTodas($idsucursal));
	}

	public function borrarConfirmacionEntregas()
	{
		$idsucursal = $this->input->post("idsucursal");
		$fecha = $this->input->post("fecha");

		echo json_encode($this->AlmacenModel->borrarConfirmacionEntregas($idsucursal, $fecha));
	}

	public function getReportePedidosJson($fecha, $idsucursal)
	{
		echo json_encode($this->AlmacenModel->getReportePedidosJson($fecha, $idsucursal));
	}

	public function reporteOtRepartoPdf($fecha, $idsucursal)
	{
		ini_set('allow_url_fopen', '1');
		ob_start();

		$data["info_pedidos"] = $this->AlmacenModel->getInfoReporteOtReparto($fecha, $idsucursal);
		$data["componentes_paquete"] = $this->AlmacenModel->getComponentesPaquete();
		$data["informacion_empresa"] = $this->ConfigurarModel->getConfiguracion()->row();

		$html = $this->load->view("Almacen/vReporteOtRepartoPdf", $data, TRUE);
		$html = preg_replace('/>\s+</', '><', $html);

		$this->load->library('Pdf');
		$pdf = new Dompdf\DOMPDF();
		$pdf->load_html($html, 'UTF-8');
		$pdf->set_option('isRemoteEnabled', TRUE);
		$pdf->setPaper('A4', 'portrait');
		$pdf->render();
		ob_end_clean();
		$pdf->stream("Nombre.pdf", array("Attachment" => 0));
	}

	public function reporteOtsPdf($fecha, $idsucursal)
	{
		ini_set('allow_url_fopen', '1');
		ob_start();

		$data["info_pedidos"] = $this->AlmacenModel->getInfoReporteOts($fecha, $idsucursal);
		$data["componentes_paquete"] = $this->AlmacenModel->getComponentesPaquete();
		$data["informacion_empresa"] = $this->ConfigurarModel->getConfiguracion()->row();

		$html = $this->load->view("Almacen/vReporteOtsPdf", $data, TRUE);
		$html = preg_replace('/>\s+</', '><', $html);

		$this->load->library('Pdf');
		$pdf = new Dompdf\DOMPDF();
		$pdf->load_html($html, 'UTF-8');
		$pdf->set_option('isRemoteEnabled', TRUE);
		$pdf->setPaper('A4', 'portrait');
		$pdf->render();
		ob_end_clean();
		$pdf->stream("Nombre.pdf", array("Attachment" => 0));
	}

	public function reporteLibrosPdf($fecha, $idsucursal)
	{
		ini_set('allow_url_fopen', '1');
		ob_start();

		$data["info_pedidos"] = $this->AlmacenModel->getInfoReporteLibros($fecha, $idsucursal);
		$data["componentes_paquete"] = $this->AlmacenModel->getComponentesPaquete();
		$data["informacion_empresa"] = $this->ConfigurarModel->getConfiguracion()->row();

		$html = $this->load->view("Almacen/vReporteLibrosPdf", $data, TRUE);
		$html = preg_replace('/>\s+</', '><', $html);

		$this->load->library('Pdf');
		$pdf = new Dompdf\DOMPDF();
		$pdf->load_html($html, 'UTF-8');
		$pdf->set_option('isRemoteEnabled', TRUE);
		$pdf->setPaper('A4', 'portrait');
		$pdf->render();
		ob_end_clean();
		$pdf->stream("Nombre.pdf", array("Attachment" => 0));
	}
}