<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pruebas extends CI_Controller {

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

	public function __construct()
    {
    	parent::__construct();

    	$this->load->model(array('AppModel'));
    	$this->load->helper(array('url','form'));
    }

	public function excel()
	{
		$this->load->library('excel');

		$objPHPExcel = PHPExcel_IOFactory::load('INVENTARIO.xlsx');
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

		$maxCell = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
		$data = $objPHPExcel->getActiveSheet()->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);

		/*echo "<pre>";
		print_r($data);
		echo "</pre>";
		die();*/

		$new_array = array();
		$contador = 0;

		$sucursal = "";

		foreach ($data as $index => $row)
		{
			$cambiosucursal = false;

			if($row[0]=="Errores de existencias o costos:"){
				break;
			}

			if($row[1]=='Nombre:')
			{
				$sucursal = $row[2];
				$cambiosucursal = true;
			}

			if($sucursal != "")
			{
				if(!$cambiosucursal)
				{
					if($row[2] != "")
					{
						if($row[4] != "Total:")
						{
							$new_array[$contador]["fecha"] = "FECHA";
							$new_array[$contador]["sucursal"] = $sucursal;
							$new_array[$contador]["codigobarra"] = "codigo barra";
							$new_array[$contador]["unidades"] = "unidades";
							$new_array[$contador]["valorinventario"] = "valorinventario";
							$new_array[$contador]["descripcion"] = $row[2];
							$contador++;
						}
					}
				}
			}
		}

		echo "<pre>";
		print_r($new_array);
		echo "</pre>";
		die();

	}
}
