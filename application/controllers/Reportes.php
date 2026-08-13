<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','form', 'variables_helper', 'funcioneshtml'));
		$this->load->library(array('session', 'pagination'));
		$this->load->model(array('HomeModel','CatalogosModel','ConfigurarModel','ReportesModel'));		
	}
			
	public function index()
	{
		$this->principal();
	}

	public function listadoPedidos()
	{
		VERIFICARSESION();
		//$data["lista"] = $this->ReportesModel->getPedidos($fIni,$fFin);
		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$this->load->view('Reportes/vListaReporteVentas', $data);
	}

	public function listadoPedidosJson()
	{
		$data = $this->input->post();
		
		echo json_encode($this->ReportesModel->getPedidos($data)->result());
	}

	public function verPedido($id)
	{
		VERIFICARSESION();
		$data["datos"] = $this->ReportesModel->getVisita($id);
		$data["datos_pedido"] = $this->ReportesModel->getPedido($id);
		$data["poligonoDatos"] = "";

		//if($data["datos"]->num_rows() > 0)
		if(1==1)
		{
			$data["idcliente"] = $data["datos_pedido"]->row()->idcliente;
			$data["datosCliente"] = $this->CatalogosModel->getCoordenadasCliente($data["idcliente"]);
			$data["latitud"] = $data["datos_pedido"]->row()->latitud;
			$data["longitud"] = $data["datos_pedido"]->row()->longitud;
			$poligono = $this->CatalogosModel->getPoligonoZona($data["datosCliente"]->row()->zona);
			$data["poligonoDatos"] = $this->CatalogosModel->getPoligono($poligono);
			$data["datosPedido"] = $this->ReportesModel->getPedidosDetalle($id);
			$this->load->view('Reportes/vVerPedido', $data);
		}
	}

	public function verVisita($id)
	{
		VERIFICARSESION();
		$data["datos"] = $this->ReportesModel->getVisita($id);
		$data["datos_pedido"] = $this->ReportesModel->getPedido($data["datos"]->row()->idpedido);
		$data["poligonoDatos"] = "";

		if($data["datos"]->num_rows() > 0)
		{
			$data["idcliente"] = $data["datos"]->row()->idcliente;
			$data["datosCliente"] = $this->CatalogosModel->getCoordenadasCliente($data["idcliente"]);
			$data["latitud"] = $data["datos"]->row()->latitud;
			$data["longitud"] = $data["datos"]->row()->longitud;
			$poligono = $this->CatalogosModel->getPoligonoZona($data["datosCliente"]->row()->zona);
			$data["poligonoDatos"] = $this->CatalogosModel->getPoligono($poligono);
			$data["datosPedido"] = $this->ReportesModel->getPedidosDetalle($data["datos"]->row()->idpedido);
			$this->load->view('Reportes/vVerVisita', $data);
		}
	}

	public function imprimirPedido($idpedido)
	{
		//die("En reparacion...");
		VERIFICARSESION();

		ini_set('allow_url_fopen', '1');
		ob_start();
		$data['idpedido']=$idpedido;		
		$this->ReportesModel->banderaImpreso($idpedido);
		$pedidodetalle=$this->ReportesModel->getPedido($idpedido);		
		
		$data['folio']=$pedidodetalle->row()->folio;
		$data['tipo']=$pedidodetalle->row()->tipo;
		$fechacreacion=$pedidodetalle->row()->fechacreacion;
		$fc=explode(" ", $fechacreacion);
		$data['fecha']=$fc[0];
		$data['hora']=$fc[1];
		$data['total']=$pedidodetalle->row()->total;
		$data['nombreUsuario'] = GETUSUARIOBYID($pedidodetalle->row()->idusuario)->nombre;
		$data['nombreCliente']=$pedidodetalle->row()->cliente;

		$datos_cliente = $this->CatalogosModel->getCoordenadasCliente($pedidodetalle->row()->idcliente)->row();

		$data["info_pedido"] = $pedidodetalle->row();
		$data["info_cliente"] = $datos_cliente;
		$data['clienteCiudad'] = $datos_cliente->ciudad;
		$data['clienteEstado'] = $datos_cliente->estado;
		$data["pedidodetallado"] = $this->ReportesModel->getPedidosDetalladosId($idpedido);
		$data["info_empresa"] = GETDATOSEMPRESA();

		$html = $this->load->view("Reportes/imprimir/venta_view", $data, TRUE);
		$html = preg_replace('/>\s+</', '><', $html);

		$this->load->library('Pdf');
		$pdf = new Dompdf\DOMPDF();
		$pdf->load_html($html, 'UTF-8');
		$pdf->set_option('isRemoteEnabled', TRUE);
		$pdf->setPaper('A4', 'portrait');
		$pdf->render();
		ob_end_clean();
		$pdf->stream("$idpedido.pdf", array("Attachment" => 0));

		//$this->load->view('Reportes/imprimir/venta0.php',$data);
	}

	public function listadoVisitas()
	{
		VERIFICARSESION();
		$data["listaSucursales"] = $this->ReportesModel->getSucursales();

		$this->load->view('Reportes/vListaReporteVisitas', $data);
	}

	public function visitasEnMapa()
	{
		VERIFICARSESION();

		$this->load->view('Reportes/vVisitasMapa');
	}

	public function ubicacionRutasMapa()
	{
		VERIFICARSESION();

		$this->load->view('Reportes/vUbicacionRutasMapa');
	}

	public function listadoVisitasJson()
	{
		ob_start();
		$data = $this->input->post();
		/*$data = array(
			"fechade" => "2018-11-01",
			"fechaa" => "2019-01-31",
			"tipo" => "0",
			"sucursal" => "1",
			"ruta" => "0",
			"usuario" => "0",
		);*/
		echo json_encode($this->ReportesModel->getVisitas($data)->result());
		ob_end_flush();
	}

	public function listadoUbicacionRutasJson()
	{
		ob_start();
		$data = $this->input->post();
		echo json_encode($this->ReportesModel->getUbicacionRutas($data)->result());
		ob_end_flush();
	}

	public function listaCumplimientoAgenda()
	{
		VERIFICARSESION();
		$data["listaSucursales"]=$this->ReportesModel->getSucursales();
		$this->load->view('Reportes/vListaReporteAgenda', $data);
	}

	public function listadoCumplimientoagendaJson()
	{
		$data = $this->input->post();
		/*$data = array(			
			"fecha" => "2019-01-02",			
			"sucursal" => "1",
			"ruta" => "0",
			"usuario" => "0",
		);*/
		echo json_encode($this->ReportesModel->getEfectividadAgenda($data));
	}

	public function verAcciones($idUsuario,$fIni,$fFin)
	{
		VERIFICARSESION();
		$data["idUsuario"] = $idUsuario;
		$data["fIni"] = $fIni;
		$data["fFin"] = $fFin;
		$this->load->view('Reportes/vVerVisitas',$data);
	}

	public function getAcciones()
	{
		$idUsuario = $_POST['idUsuario'];
		$fIni = $_POST['fIni'];
		$fFin = $_POST['fFin'];
		$acciones = $this->ReportesModel->getPedidosVisitas($idUsuario,$fIni,$fFin);
		echo $acciones;
	}

	public function listaEfectividad()
	{
		VERIFICARSESION();

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$this->load->view('Reportes/vListaReporteEfectividad', $data);
	}

	public function listadoEfectividadVisitasJson()
	{
		$data = $this->input->post();
		/*$data = array(			
			"fechade" => "2019-01-02",
			"fechaa" => "2019-01-04",
			"sucursal" => "1",
			"ruta" => "0",
			"usuario" => "0",
		);*/
		echo json_encode($this->ReportesModel->getEfectividad($data));
	}

	public function verPedidos($fIni="1900-01-01",$fFin="1900-01-01",$tipo="0",$usuario="0",$sucursal="0",$ruta="0")
	{
		$data["lista"] = $this->ReportesModel->getListaPedidos($fIni,$fFin,$tipo,$usuario,$sucursal,$ruta);
		$this->load->view('Reportes/vVerPedidos', $data);
	}

	public function verPedidosLiquidado($fIni="1900-01-01",$fFin="1900-01-01",$tipo="0",$usuario="0",$sucursal="0",$ruta="0")
	{
		$data["lista"] = $this->ReportesModel->getListaPedidos($fIni,$fFin,$tipo,$usuario,$sucursal,$ruta);
		$this->load->view('Reportes/vVerPedidosLiquidado', $data);
	}

	public function ListadoSellout()
	{
		$data["listado"] = $this->ReportesModel->listado_sellout()->result();
		$this->load->view('Reportes/excel_lista', $data);
	}

	public function getSellout()
	{
		echo json_encode($this->ReportesModel->getSellout($_POST["id"])->row(), JSON_UNESCAPED_UNICODE);
	}

	public function Sellout()
	{
		/*$excel = new Spreadsheet_Excel_Reader();
		$excel->read('assets/Libro1.xls');
		$data['data_excell']=$excel->sheets[0]['cells'];
		$this->load->view('Reportes/excel', $data);*/
		$data["tipodocumento"] = $this->CatalogosModel->getTipoDocumento()->result();
		$this->load->view('Reportes/excel', $data);
	}

	public function Sellout2()
	{
		$data["tipodocumento"] = $this->CatalogosModel->getTipoDocumento()->result();
		$this->load->view('Reportes/excel_2', $data);
	}

	public function guardar_excel()
	{
		$data = $this->input->post();
		$data["hora"] = GETHORA();
		echo $this->ReportesModel->guardar_excel($data);
	}

	public function convertir_excel_inventarios()
	{
		date_default_timezone_set('America/Mazatlan');
		$error = false;
		$message = "Todo bien";
		/*$header = array();
		$arr_data = array();*/

		$this->load->library('excel');

		$path_parts = pathinfo($_FILES["archivo"]["name"]);
		$extension = $path_parts['extension'];

		$fecha = str_replace('-','', $_POST["fecha"]);
		//$fecha = substr($fecha, 2);

		if (strpos($extension, 'xls') !== false)
		{
			$objPHPExcel = PHPExcel_IOFactory::load($_FILES['archivo']['tmp_name']);
			//$objPHPExcel = PHPExcel_IOFactory::load('INVENTARIO.xlsx');

			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			$maxCell = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
			$data = $objPHPExcel->getActiveSheet()->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);
		}
		else
		{
			$error = true;
			$message = "El archivo subido no es valido. <b>Los archivos validos son: extencion (.xls, .xlsx)</b>";
		}

		if($error==false)
		{
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
						if($row[1] != "Almacén:")
						{
							if($row[2] != "")
							{
								if($row[4] != "Total:")
								{
									$idclasificacion_producto = 6;
									$codigobarras = "SIN CB";
									$info_producto = $this->CatalogosModel->getProductoByCodigo($row[1]);
									if($info_producto->num_rows() > 0){
										$idclasificacion_producto = $info_producto->row()->clasificacion;
										if( !is_null($info_producto->row()->codigobarras) ){
											if( $info_producto->row()->codigobarras != ""){
												$codigobarras = $info_producto->row()->codigobarras;
											}										
										}									
									}

									//if( $idclasificacion_producto != 6 )
									if(1==1)
									{
										$unidades = (int)trim(preg_replace('/\s+/', ' ', $row[8]));

										$valorinventario = trim(preg_replace('/\s+/', ' ', $row[14]));
										$valorinventario = str_replace(',','',$valorinventario);

										$new_array[$contador]["fecha"] = $fecha;
										$new_array[$contador]["sucursal"] = $sucursal;
										$new_array[$contador]["codigobarra"] = $codigobarras;
										$new_array[$contador]["unidades"] = $unidades;
										$new_array[$contador]["valorinventario"] = number_format( (float)$valorinventario, 2, '.', ',');
										$new_array[$contador]["descripcion"] = trim(preg_replace('/\s+/', ' ', $row[2]));
										$contador++;
									}									
								}
							}
						}
					}
				}
			}

			/*echo "<pre>";
			print_r($new_array);
			echo "</pre>";
			die();*/
		}

		$datos = array(
			"data" => $new_array,
			"error" => $error,
			"message" => $message,
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function convertir_excel_ventas()
	{
		ini_set('memory_limit', '2048M');
		date_default_timezone_set('America/Mazatlan');
		$error = false;
		$message = "Todo bien";		

		$this->load->library('excel');
		$FILE_NEW = $_FILES["archivo"]["tmp_name"];//"excel/INVENTARIO_NEW.xlsx";

		/*if(move_uploaded_file($_FILES["archivo"]["tmp_name"], "hola.xlsx"))
		{*/
			//$path_parts = pathinfo($_FILES["archivo"]["name"]);
			$path_parts = pathinfo($_FILES["archivo"]["name"]);
			$extension = $path_parts['extension'];

			//$fecha = str_replace('-','', $_POST["fecha"]);
			if (strpos($extension, 'xls') !== false)
			{
				//$objPHPExcel = PHPExcel_IOFactory::load($_FILES['archivo']['tmp_name']);			
				//$objPHPExcel = PHPExcel_IOFactory::load($FILE_NEW);

				$inputFileType = PHPExcel_IOFactory::identify($FILE_NEW);
				/**  Create a new Reader of the type that has been identified  **/
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objReader->setLoadSheetsOnly("LIQUIDADO");
				/** Set read type to read cell data onl **/
				$objReader->setReadDataOnly(true);
				/**  Load $inputFileName to a PHPExcel Object  **/
				$objPHPExcel = $objReader->load($FILE_NEW);

				$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
				$maxCell = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
				$data = $objPHPExcel->getActiveSheet()->rangeToArray('A2:' . $maxCell['column'] . $maxCell['row']);
			}
			else
			{
				$error = true;
				$message = "El archivo subido no es valido. <b>Los archivos validos son: extencion (.xls, .xlsx)</b>";
			}

			if($error==false)
			{
				$new_array = array();
				$contador = 0;

				$sucursal = "";
				//echo count($data);die("aqui");
				$FILE = fopen('excel/data.txt', 'w');
				foreach ($data as $index => $row)
				{
					$producto = $this->CatalogosModel->getProductoByCodigo($row[6]);
					if($producto->num_rows()>0)
					{
						//if( $producto->row()->clasificacion != 6 )
						if( 1==1 )
						{
							$vendedor = $this->CatalogosModel->getDatosUsuario($row[2]);
					
							//$fecha_format = date("Ymd", strtotime($row[11]));
							$fecha_format = DateTime::createFromFormat('d/m/Y', $row[11]);
							if ($fecha_format !== false){
								$fecha_format = $fecha_format->format('Ymd');
							}else{
								$fecha_format = "00000000";
							}

							$nopedido = trim(preg_replace('/\s+/', ' ', $row[0]));
							$nofactura = trim(preg_replace('/\s+/', ' ', $row[0]));
							$fecha = $fecha_format;
							$nosucursal = trim(preg_replace('/\s+/', ' ', $row[19]));
							$nombrevendedor = ($vendedor->num_rows()>0) ? $this->CatalogosModel->getDatosUsuario($row[2])->row()->usuario : "no encontrado";//obtener la ruta
							$nocliente = trim(preg_replace('/\s+/', ' ', $row[4]));
							$cb = $producto->row()->codigobarras;
							$nounidades = trim(preg_replace('/\s+/', ' ', $row[8]));
							$ventapesos = trim(preg_replace('/\s+/', ' ', $row[10]));
							$producto = trim(preg_replace('/\s+/', ' ', $row[7]));

							$new_array[$contador]["nopedido"] = $nopedido;
							$new_array[$contador]["nofactura"] = $nofactura;
							$new_array[$contador]["fecha"] = $fecha;
							$new_array[$contador]["nosucursal"] = $nosucursal;
							$new_array[$contador]["vendedor"] = $nombrevendedor;
							$new_array[$contador]["nocliente"] = $nocliente;
							$new_array[$contador]["cb"] = $cb;
							$new_array[$contador]["nounidades"] = $nounidades;
							$new_array[$contador]["ventapesos"] = $ventapesos;
							$new_array[$contador]["producto"] = $producto;

							$linea = $nopedido.'|'.$nofactura.'|'.$fecha.'|'.$nosucursal.'|'.$nombrevendedor.'|'.$nocliente.'|'.$cb.'|'.$nounidades.'|'.$ventapesos.'|'.$producto."\n";

							fwrite($FILE, $linea);

							$contador++;
						}					
					}				
				}
				
				fclose($FILE);
				//die("aqui");

				/*echo "<pre>";
				print_r($new_array);
				echo "</pre>";
				die();*/
			}

			$datos = array(
				"data" => $new_array,
				"error" => $error,
				"message" => $message,
			);

			//die("hasta qui");
			echo json_encode($datos, JSON_UNESCAPED_UNICODE);
		/*}
		else
		{
			echo "error";
		}*/
	}

	public function convertir_excel_ventas_2()
	{
		ini_set('memory_limit', '2048M');
		date_default_timezone_set('America/Mazatlan');
		$error = false;
		$message = "Todo bien";

		$data = $_POST["archivo"];
		print_r($data);die();

			if($error==false)
			{
				$new_array = array();
				$contador = 0;

				$sucursal = "";				
				foreach ($data as $index => $row)
				{
					$producto = $this->CatalogosModel->getProductoByCodigo($row[6]);
					if($producto->num_rows()>0)
					{
						//if( $producto->row()->clasificacion != 6 )
						if( 1==1 )
						{
							$vendedor = $this->CatalogosModel->getDatosUsuario($row[2]);
					
							//$fecha_format = date("Ymd", strtotime($row[11]));
							$fecha_format = DateTime::createFromFormat('d/m/Y', $row[11]);
							if ($fecha_format !== false){
								$fecha_format = $fecha_format->format('Ymd');
							}else{
								$fecha_format = "00000000";
							}

							$nopedido = trim(preg_replace('/\s+/', ' ', $row[0]));
							$nofactura = trim(preg_replace('/\s+/', ' ', $row[0]));
							$fecha = $fecha_format;
							$nosucursal = trim(preg_replace('/\s+/', ' ', $row[19]));
							$nombrevendedor = ($vendedor->num_rows()>0) ? $this->CatalogosModel->getDatosUsuario($row[2])->row()->usuario : "no encontrado";//obtener la ruta
							$nocliente = trim(preg_replace('/\s+/', ' ', $row[4]));
							$cb = $producto->row()->codigobarras;
							$nounidades = trim(preg_replace('/\s+/', ' ', $row[8]));
							$ventapesos = trim(preg_replace('/\s+/', ' ', $row[10]));
							$producto = trim(preg_replace('/\s+/', ' ', $row[7]));

							$new_array[$contador]["nopedido"] = $nopedido;
							$new_array[$contador]["nofactura"] = $nofactura;
							$new_array[$contador]["fecha"] = $fecha;
							$new_array[$contador]["nosucursal"] = $nosucursal;
							$new_array[$contador]["vendedor"] = $nombrevendedor;
							$new_array[$contador]["nocliente"] = $nocliente;
							$new_array[$contador]["cb"] = $cb;
							$new_array[$contador]["nounidades"] = $nounidades;
							$new_array[$contador]["ventapesos"] = $ventapesos;
							$new_array[$contador]["producto"] = $producto;

							$linea = $nopedido.'|'.$nofactura.'|'.$fecha.'|'.$nosucursal.'|'.$nombrevendedor.'|'.$nocliente.'|'.$cb.'|'.$nounidades.'|'.$ventapesos.'|'.$producto."\n";

							$contador++;
						}					
					}				
				}

				/*echo "<pre>";
				print_r($new_array);
				echo "</pre>";
				die();*/
			}

			$datos = array(
				"data" => $new_array,
				"error" => $error,
				"message" => $message,
			);

			echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function convertir_excel()
	{
		date_default_timezone_set('America/Mazatlan');
		$error = false;
		$message = "Todo bien";
		$header = array();
		$arr_data = array();

		$this->load->library('excel');

		$path_parts = pathinfo($_FILES["archivo"]["name"]);
		$extension = $path_parts['extension'];

		$tipodocumento = $_POST["tipodocumento"];
		$infodocumento = $this->CatalogosModel->getTipoDocumentoById($tipodocumento)->row();

		if (strpos($extension, 'xls') !== false) {
			$objPHPExcel = PHPExcel_IOFactory::load($_FILES['archivo']['tmp_name']);
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

			/*$maxCell = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
			$data = $objPHPExcel->getActiveSheet()->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);
			print_r($data);die();*/

			foreach ($cell_collection as $cell)
			{
				$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
				$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
				$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

				$type = $objPHPExcel->getActiveSheet()->getCell($cell)->getDataType();
				if ($row == 1) {
					$header[$row][$column] = $data_value;
				}
				else 
				{
					if(PHPExcel_Shared_Date::isDateTime( $objPHPExcel->getActiveSheet()->getCell($cell) )){
						
						$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($data_value);
						$arr_data[$row][$column] = $fecha->format("Ymd");
						
						
					}else{
						
						$arr_data[$row][$column] = trim(preg_replace('/\s+/', ' ', $data_value));

					}					
				}
			}			
		}
		else
		{
			$error = true;
			$message = "El archivo subido no es valido. <b>Los archivos validos son: extencion (.xls, .xlsx)</b>";
		}//print_r($arr_data);die();

		if($error==false)
		{
			//valido columnas que se necesitan
			$columnas_borrar = "";
			foreach ($header as $key => $value)
			{
				foreach ($value as $numero => $columna)
				{
					if (in_array($columna, explode(',', $infodocumento->estructura))) {
					}else{
						$columnas_borrar = $columnas_borrar.$numero.",";
						unset($header[$key][$numero]);
					}
				}
			}
			
			if( count($header[1]) > 0)
			{
				$columnas_excel = "";				

				foreach ($header as $key => $value)
				{
					foreach ($value as $numero => $columna)
					{						
						$columnas_excel = $columnas_excel.$columna.",";						
					}
				}				

				$columnas_faltantes = "";

				$columnas_excel = substr($columnas_excel, 0, -1);
				$array_originales = explode(",", $infodocumento->estructura);
				foreach($array_originales as $item){
					if (strpos($columnas_excel, $item) !== false){
					}else{
						$columnas_faltantes = $columnas_faltantes.$item.",";
						$error = true;
					}
				}

				if($error)
				{
					$columnas_faltantes = substr($columnas_faltantes, 0, -1);
					$error = true;
					$message = "El archivo excel no tiene el formato correcto. Faltan la(s) siguiente(s) columnas: $columnas_faltantes</b>";
				}
			}
			else
			{
				$columnas_faltantes = $infodocumento->estructura;
				$error = true;
				$message = "El archivo excel no tiene el formato correcto. Faltan la(s) siguiente(s) columnas: $columnas_faltantes</b>";
			}

			//CAMBIO DE INDICES AL ARRAY DE DATOS Y ELIMINACION DE DATOS QUE NO SE OCUPAN
			$abecedario = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K");
			foreach ($arr_data as $key => $value)
			{
				$col = 0;
				foreach ($value as $numero => $columna)
				{
					if (strpos($columnas_borrar, $numero) !== false) {
						unset($arr_data[$key][$numero]);
					}else{
						unset($arr_data[$key][$numero]);
						//$arr_data[$key][$abecedario[$col]." ".$header[1][$numero]] = $columna;
						$arr_data[$key][$header[1][$numero]] = $columna;
						$col++;
					}					
				}
			}

			//ORDEN DE VALORES
			foreach ($arr_data as $key => $value)
			{
				$col = 0;
				foreach($array_originales as $item){
					foreach ($value as $numero => $columna)
					{
						if($item==$numero){
							unset($arr_data[$key][$numero]);
							$arr_data[$key][$item] = $columna;
						}				
					}
				}				
			}

			$index_original="";
			foreach ($header as $key => $value){
				$col = 0;
				foreach ($value as $numero => $columna){
					$index_original = $index_original.$numero.",";
					$header[$key][$numero] = $array_originales[$col];
					$col++;
				}
			}
			$index_original = substr($index_original, 0, -1);
			//die($array_originales[0]);

			//CAMBIO DE DATOS
			$columnas_cambiar = "ID Vendedor,otro";
			foreach ($header as $key => $value)
			{
				foreach ($value as $numero => $columna)
				{
					if($columna=="ID Vendedor"){
						$header[$key][$numero] = "Vendedor";
						//$columnas_cambiar = $numero.",";
					}
				}
			}
			foreach ($arr_data as $key => $value)
			{
				foreach ($value as $numero => $columna)
				{
					if( in_array($numero, explode(',', $columnas_cambiar) )){
						$vendedor = $this->CatalogosModel->getDatosUsuario($columna);
						$arr_data[$key][$numero] = ($vendedor->num_rows()>0) ? $this->CatalogosModel->getDatosUsuario($columna)->row()->usuario : "MZ000$columna";
					}
				}
			}
		}

		$datos = array(
			"header" => $header,
			"values" => $arr_data,
			"error" => $error,
			"message" => $message,
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function SendEmail()
	{
		$documento = $this->ReportesModel->getSellout($_POST["id"])->row();
		//$documento = $this->ReportesModel->getSellout(1)->row();
		$file = 'sellout.txt';
		$data = $documento->sellout;
		file_put_contents($file, $data);

		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'mail.inroute.mx',
			'smtp_port' => 465,
			'smtp_user' => 'ggomez@inroute.mx', // change it to yours
			'smtp_pass' => 'gustavoinroute10', // change it to yours
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);
		$this->load->library('email');

		$this->email->from('ejemplo@inroute.mx', 'InRoute');
		$this->email->to($_POST["correo"]);

		$this->email->subject('Sellout: '.$documento->descripcion);
		$this->email->message($_POST["mensaje"]);
		$this->email->attach($file);

		if($this->email->send())
		{
			echo '1';
		}
		else
		{
			echo '0';
			//show_error($this->email->print_debugger());
		}
	}

	/*public function leerAcumuladosJson()
	{
		$cadena=$_POST['cadena'];
		//$cadena='{"TipoArchivo":"Acumulados","Fecha":"2018-12-11","DiasHabiles":"26","DiasTrascurridos":"25","Acumulados":[{"IdVendedor":"120","AcumCat":[{"Categoria":"ALIMENTOS INFANTILES","Importe":"2844.40"},{"Categoria":"BEBIDAS COCOA","Importe":"316.20"}]},{"IdVendedor":"14","AcumCat":[{"Categoria":"ALIMENTOS INFANTILES","Importe":"10866.80"},{"Categoria":"BEBIDAS COCOA","Importe":"3576.20"}]},{"IdVendedor":"77","AcumCat":[{"Categoria":"LACTEOS INFANTILES","Importe":"56.80"},{"Categoria":"LACTEOS POLVO","Importe":"2933.90"}]}]}';
		//$cadena="Hola";
		/*$data = file_get_contents("assets/acumulados/jSonAcumulados.json");
		$products = json_decode($data, true);
			echo "<br>".$products['TipoArchivo']."<br>".$products['Fecha']."<br>";
		    //print_r($products['Acumulados']);
		    foreach ($products['Acumulados'] as $lVend) {
		    	echo "<br>IdVendedor: ".$lVend['IdVendedor'];
		    	foreach ($lVend['AcumCat'] as $lAcum) {
		    		echo "<br>--- Categoria: ".$lAcum['Categoria']." Importe".$lAcum['Importe']."<br>";
		    	}
		    }*/
		//$cadena='{"TipoArchivo":"Acumulados","Fecha":"2018-11-29","DiasHabiles":"26","DiasTrascurridos":"25","Acumulados":[{"IdVendedor":"120","AcumCat":[{"Categoria":"BEBIDAS COCOA","Importe":"316.20"}]},{"IdVendedor":"14","AcumCat":[{"Categoria":"BEBIDAS COCOA","Importe":"3576.20"}]},{"IdVendedor":"77","AcumCat":[{"Categoria":"LACTEOS POLVO","Importe":"2933.90"}]}]}';
		/*$this->ReportesModel->getAgregarAcumulados($cadena);
		echo "listo";
	}*/

	public function inicio($fIni="1900-01-01",$fFin="1900-01-01",$usuario="TODOS",$ruta="TODOS",$sucursal="TODOS")
	{
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');

		if(($fIni=="1900-01-01") OR ($fFin=="1900-01-01"))
		{
			$fIni=$fecha1;
			$fFin=$fecha1;
		}
		
		$data["fIni"]=$fIni;
		$data["fFin"]=$fFin;
		
		$data["ruta"]=$ruta;
		$data["usuario"]=$usuario;
		$data["sucursal"]=$sucursal;
		
		$data["visitadosinventa"]=$this->ReportesModel->getCuantasVisitas($fIni,$fFin,"visitado sin venta")->row()->cuanto;
		$data["ventaregistrada"]=$this->ReportesModel->getCuantasVisitas($fIni,$fFin,"venta registrada")->row()->cuanto;
		$data["yateniaproducto"]=$this->ReportesModel->getCuantasVisitas($fIni,$fFin,"ya tenia producto")->row()->cuanto;
		$data["contactonoencontrado"]=$this->ReportesModel->getCuantasVisitas($fIni,$fFin,"contacto no encontrado")->row()->cuanto;
		$data["tiendacerrada"]=$this->ReportesModel->getCuantasVisitas($fIni,$fFin,"tienda cerrada")->row()->cuanto;
		$data["noteniadinero"]=$this->ReportesModel->getCuantasVisitas($fIni,$fFin,"no tenia dinero")->row()->cuanto;
		$data["nopedidos"]=$data["visitadosinventa"]+$data["yateniaproducto"]+$data["contactonoencontrado"]+$data["tiendacerrada"]+$data["noteniadinero"];
		$data["visitas"]=$data["nopedidos"]+$data["ventaregistrada"];
		$data["clientes"]=$this->CatalogosModel->cuantosClientes()->row()->cuantosClientes;
		$data["rutas"]=$this->CatalogosModel->cuantasRutas()->row()->cuantasRutas;
		$data["datosPedidos"]=$this->ReportesModel->getPedidosCuantos($fIni,$fFin);		

		$this->load->view('Home/principal',$data);
	}

	public function crearReporte(){
		$this->load->view('Reportes/vDescargaReporte');
	}
	public function enviarpost(){
		$this->load->view('pruebapost');
	}
	
	public function agregarAcumulados($idVendedor){
		$res=$this->ReportesModel->getPruebaAgregarPedido($idVendedor);
		echo $res;
	}
	public function elCorte(){
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');
		$data['fecha']=$fecha1;
		$this->load->view('Reportes/vCorteH',$data);
	}
	public function hacerCorte(){
		$fecha=$_POST['fecha'];
		$this->ReportesModel->doCorte($fecha);	
	}
	public function listado($fIni="1900-01-01",$fFin="1900-01-01"){

		$fecha1=date('Y-m-d');	
		if(($fIni=="1900-01-01") OR ($fFin=="1900-01-01")){
			$fIni=$fecha1;
			$fFin=$fecha1;
		}
		$data["lista"]=$this->ReportesModel->getPedidos($fIni,$fFin);
		//print_r($data["lista"]->result());
	}
	
	public function postPorcentajeObtener(){
		$porcentaje=$_POST['porcentaje'];
		echo FORMATO_PORCENTAJEDEC($porcentaje);
	}
	

/*public function getUsuariosJson($usuario="",$clave=""){
		
		$perfil=$this->HomeModel->inicioSesionLiq($usuario,$clave);
		$validacion=GETACCESOX("Reportes","getLiquidacion",$perfil);
		$principal=array();
		if($validacion!=0){
			
			$principal['error']=false;
			$principal['message']="";
			$usuarios=$this->ReportesModel->getUsuariosJ();
			$usuariosA=array();
			$i=0;
			foreach ($usuarios->result() as $k) {
				$usuariosA[$i]['id']=$k->id;
				$usuariosA[$i]['name']=$k->nombre;
				$usuariosA[$i]['username']=$k->usuario;
				$usuariosA[$i]['role']=$k->perfil;
				$to=$k->fechacreacion;
				//echo $to;
				list($part1,$part2) = explode(' ', $to);
				list($year, $month, $day) = explode('-', $part1);
				list($hours,$minutes,$seconds) = explode(':', $part2);
				/*$usuariosA[$i]['date_created'] =  $to;
				$usuariosA[$i]['date_last_login'] =  $to;
				$usuariosA[$i]['date_last_logout'] =  $to;*/
				/*$usuariosA[$i]['date_created'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				$usuariosA[$i]['date_last_login'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				$usuariosA[$i]['date_last_logout'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				$usuariosA[$i]['phone_number'] = $k->telefono;
				if($k->status==1){
					$usuariosA[$i]['enabled']=true;
				}
				else{
					$usuariosA[$i]['enabled']=false;
				}
				$usuariosA[$i]['extras']="";
				$usuariosA[$i]['tags']=[];
				$i++;
			}
			$principal['users']=$usuariosA;
		}
		else{
			$principal['error']=true;
			$principal['message']="Error de Autentificacion";
			$principal['users']=null;
		}
		$resultado=json_encode($principal);
		echo $resultado;
	}*/

/*public function getProductosJson($usuario="",$clave=""){
			
		$perfil=$this->HomeModel->inicioSesionLiq($usuario,$clave);
		$validacion=GETACCESOX("Reportes","getLiquidacion",$perfil);
		$principal=array();
		if($validacion!=0){
			
			$principal['error']=false;
			$principal['message']="";
			$usuarios=$this->ReportesModel->getProductosJ();
			$usuariosA=array();
			$i=0;
			foreach ($usuarios->result() as $k) {
				$usuariosA[$i]['id']=$k->id;
				$usuariosA[$i]['code']=$k->codigo;
				$usuariosA[$i]['description']=$k->nombre;
				$categoriaA=array();
				$categoria=$this->ReportesModel->getCategoriasProductosJ($k->clasificacion);
				//print_r($categoria->result());
				foreach ($categoria->result() as $k3) {
					$categoriaA['id']=$k3->id;
					$categoriaA['description']=$k3->nombre;
					if($k3->status==1){
						$categoriaA['enabled']=true;
					}
					else{
						$categoriaA['enabled']=false;
					}
				}
				$usuariosA[$i]['family']=$categoriaA;

				if($k->status==1){
					$usuariosA[$i]['enabled']=true;
				}
				else{
					$usuariosA[$i]['enabled']=false;
				}
				$usuariosA[$i]['price']=$k->precio;
				$usuariosA[$i]['pictures']=[];
				$usuariosA[$i]['barcode']=null;
				$usuariosA[$i]['apply_discounts']=false;
				$usuariosA[$i]['details']=null;


				$to=$k->fechacreacion;
				list($part1,$part2) = explode(' ', $to);
				list($year, $month, $day) = explode('-', $part1);
				list($hours,$minutes,$seconds) = explode(':', $part2);
				$usuariosA[$i]['date_created'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				//$usuariosA[$i]['date_created'] =  $to;
				$to=$k->ultima_actualizacion;
				list($part1,$part2) = explode(' ', $to);
				list($year, $month, $day) = explode('-', $part1);
				list($hours,$minutes,$seconds) = explode(':', $part2);
				$usuariosA[$i]['last_updated'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				//$usuariosA[$i]['last_updated'] =$to;
				$usuariosA[$i]['quantity'] = 0;
				$i++;
			}
			$principal['products']=$usuariosA;
		}
		else{
			$principal['error']=true;
			$principal['message']="Error de Autentificacion";
			$principal['products']=null;
		}
		$resultado=json_encode($principal);
		echo $resultado;
}*/

	/*public function getClientesJson($usuario="",$clave=""){
			
		$perfil=$this->HomeModel->inicioSesionLiq($usuario,$clave);
		$validacion=GETACCESOX("Reportes","getLiquidacion",$perfil);
		$principal=array();
		if($validacion!=0){
			
			$principal['error']=false;
			$principal['message']="";
			$usuarios=$this->ReportesModel->getClientesJ();
			$usuariosA=array();
			$i=0;
			foreach ($usuarios->result() as $k) {
				$usuariosA[$i]['id']=$k->id;
				$usuariosA[$i]['code']=$k->codigo;
				$usuariosA[$i]['description']=$k->nombre;
				if($k->status==1){
					$usuariosA[$i]['enabled']=true;
				}
				else{
					$usuariosA[$i]['enabled']=false;
				}
				$usuariosA[$i]['latitud']=$k->latitud;
				$usuariosA[$i]['longitud']=$k->longitud;
				$usuariosA[$i]['accuracy']=0;
				$usuariosA[$i]['zone_id']=$k->zona;
			
				$zona=$this->ReportesModel->getZonasJ($k->zona);
				//print_r($categoria->result());
				foreach ($zona->result() as $k3) {
					$usuariosA[$i]['zone_description']=$k3->zona;
					
				}
				
				
				$usuariosA[$i]['address']=$k->calle." ".$k->numero." ".$k->colonia;
				$usuariosA[$i]['city']=$k->ciudad;
				$usuariosA[$i]['postal_code']=$k->cp;
				$usuariosA[$i]['owner']=$k->encargado;
				$usuariosA[$i]['phone_number']=$k->telefono;
				$usuariosA[$i]['comments']=$k->comentarios;


				$to=$k->fechacreacion;
				list($part1,$part2) = explode(' ', $to);
				list($year, $month, $day) = explode('-', $part1);
				list($hours,$minutes,$seconds) = explode(':', $part2);
				$usuariosA[$i]['date_created'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				$to=$k->ultima_actualizacion;
				list($part1,$part2) = explode(' ', $to);
				list($year, $month, $day) = explode('-', $part1);
				list($hours,$minutes,$seconds) = explode(':', $part2);
				$usuariosA[$i]['last_updated'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				$usuariosA[$i]['is_prospect'] = false;
				$usuariosA[$i]['mobile'] = true;
				$usuariosA[$i]['email'] = $k->email;
				$usuariosA[$i]['priceList'] = null;
				$usuariosA[$i]['discount'] = 0;
				$usuariosA[$i]['created_by'] = $k->creadopor;
				$usuariosA[$i]['balance'] = 0;
				$usuariosA[$i]['credit'] = 0;
				$usuariosA[$i]['customerFather'] = 0;
				$usuariosA[$i]['tags'] = [];
				$usuariosA[$i]['pictures'] = null;
				$i++;
			}
			$principal['customers']=$usuariosA;
		}
		else{
			$principal['error']=true;
			$principal['message']="Error de Autentificacion";
			$principal['customers']=null;
		}
		$resultado=json_encode($principal);
		echo $resultado;
	}*/

	/*public function getLiquidacion($usuario="",$clave="",$empresa="",$fIni="1900-01-01",$fFin="1900-01-01")
	{
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');	
		if(($fIni=="1900-01-01") OR ($fFin=="1900-01-01"))
		{
			$fIni=$fecha1;
			$fFin=$fecha1;
		}

		$perfil = $this->HomeModel->inicioSesionLiq($usuario,$clave,$empresa);
		$validacion = GETACCESOX("Reportes","getLiquidacion",$perfil);
		$principal = array();

		if($validacion!=0)
		{
			$principal['error']=false;
			$principal['message']="";

			$pedidos=$this->ReportesModel->getPedidosJ($fIni,$fFin);
			$pedidosA=array();
			$i=0;

			foreach ($pedidos->result() as $k)
			{
				$pedidosA[$i]['id']=$k->folio;
				$itemsA=array();
				$items=$this->ReportesModel->getItemsJ($k->id);
				$cItems=0;

				foreach ($items->result() as $kItems)
				{
					$itemsA[$cItems]['id']=$kItems->id;
					$itemsA[$cItems]['product_code']=$kItems->codigoproducto;
					$itemsA[$cItems]['product_id']=$kItems->iditem;
					/*if((is_null($kItems->codigoproducto)AND($kItems->codigoproducto!=""))){
						
						$itemsA[$cItems]['product_code']=$kItems->codigoproducto;
						$itemsA[$cItems]['product_id']=$this->ReportesModel->getIdProductoJ($kItems->codigoproducto)->row()->id; //preguntar a gustavo
					}
					else{
						
						$itemsA[$cItems]['product_code']=$kItems->codigoproducto;
						$itemsA[$cItems]['product_id']=null; //preguntar a gustavo
					}*/
					/*$itemsA[$cItems]['product_description']=$kItems->producto;
					$itemsA[$cItems]['price']=(double)$kItems->precio;
					$itemsA[$cItems]['quantity']=(double)$kItems->cantidad;
					$itemsA[$cItems]['total']=(double)$kItems->importe;
					$itemsA[$cItems]['comments']=null;
					$cItems++;
				}

				$pedidosA[$i]['items'] = $itemsA;
				$pedidosA[$i]['customer_code']=$k->codigocliente;
				$pedidosA[$i]['customer_id']=(integer)$k->idcliente;

				$cliente = $this->ReportesModel->getClienteJ($k->idcliente);
				$clienteA = array();

				$pedidosA[$i]['customer_description']=$cliente->row()->nombre;
				$pedidosA[$i]['customer_email']=$cliente->row()->email;
				$pedidosA[$i]['type']=$k->tipo;
				$pedidosA[$i]['delivery_schedule_date']=null;

				$creadoporA=array();
				$creadopor=$this->ReportesModel->getCreadoporJ($k->idusuario);				
				$creadoporA['id']=$creadopor->row()->id;
				$creadoporA['username']=$creadopor->row()->usuario;				
				$creadoporA['name']=$creadopor->row()->nombre;

				$pedidosA[$i]['created_by']=$creadoporA;
				$pedidosA[$i]['comment']=null;

				$to=$k->fechacreacion;
				list($part1,$part2) = explode(' ', $to);
				list($year, $month, $day) = explode('-', $part1);
				list($hours, $minutes,$seconds) = explode(':', $part2);

				$pedidosA[$i]['date_created'] =  (integer)(mktime($hours, $minutes, $seconds, $month, $day, $year)."000");
				$pedidosA[$i]['total']=(double)$k->total;
				$pedidosA[$i]['price_list']=null;
				$pedidosA[$i]['latitude']=(double)$k->latitud;
				$pedidosA[$i]['longitude']=(double)$k->longitud;
				$pedidosA[$i]['accuracy']=0;
				$pedidosA[$i]['deleted']=false;
				$i++;
			}

			$principal['salesOrders'] = $pedidosA;
		}
		else
		{
			$principal['error']=true;
			$principal['message']="Error de Autentificacion";
			$principal['salesOrders']=null;
		}

		$resultado = json_encode($principal);
		echo $resultado;
	}*/

	public function getLiquidacion2($usuario="",$clave="",$empresa="",$fIni="1900-01-01",$fFin="1900-01-01")
	{
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');

		if(($fIni=="1900-01-01") OR ($fFin=="1900-01-01"))
		{
			$fIni=$fecha1;
			$fFin=$fecha1;
		}

		$perfil = $this->HomeModel->inicioSesionLiq($usuario,$clave,$empresa);
		$validacion = GETACCESOX("Reportes","getLiquidacion",$perfil);
		$principal = array();

		if($validacion!=0)
		{
			$principal['error']=false;
			$principal['message']="";

			$pedidos = $this->ReportesModel->getPedidosJ2($fIni,$fFin)->result_array();
			$i=0;
			
			foreach ($pedidos as $key => $value)
			{
				$pedidos[$key]["items"] = $this->ReportesModel->getItemsJ($value["idpedido"])->result();
				$pedidos[$key]["created_by"] = $this->ReportesModel->getCreadoporJ($value["idusuario"])->row();
			}

			$principal['salesOrders'] = $pedidos;
		}
		else
		{
			$principal['error']=true;
			$principal['message']="Error de Autentificacion";
			$principal['salesOrders']=null;
		}

		$resultado = json_encode($principal);
		echo $resultado;
	}

	public function eliminarPedido()
	{
		$id = $_POST['id'];
		$consulta = $this->ReportesModel->deletePedidos($id);		
		//echo $id;
	}
	public function postTotalesPedidos(){
		$usuario=$_POST['usuario'];
		$sucursal=$_POST['sucursal'];
		$tipo=$_POST['tipo'];
		$ruta=$_POST['ruta'];
		$fechaI=$_POST['fechaI'];
		$fechaF=$_POST['fechaF'];
		$pedidos=$this->ReportesModel->getDatosPedidos($usuario,$sucursal,$tipo,$ruta,$fechaI,$fechaF);
		//echo $pedidos;
		//print_r($pedidos->row());
		if($tipo=="DEVOLUCION"){
			echo "0-$0.00";
		}
		else{
			echo $pedidos->row()->cuantos."-".FORMATO_DINERO($pedidos->row()->total);
		}
	}
	public function postVisitasProgramadas(){
		$usuario=$_POST['usuario'];
		$sucursal=$_POST['sucursal'];
		
		$ruta=$_POST['ruta'];
		$fechaI=$_POST['fechaI'];
		$fechaF=$_POST['fechaF'];
		//$usuario="Benjamin Gonzalez C.";
		/*$sucursal="TODOS";
		
		$ruta="TODOS";
		$fechaI='2018-09-21';
		$fechaF='2018-09-21';*/
		$programadas=$this->ReportesModel->getDatosVisitasProgramadas($usuario,$sucursal,$ruta,$fechaI,$fechaF);
		$programadasSi=$this->ReportesModel->getDatosVisitasProgramadasSi($usuario,$sucursal,$ruta,$fechaI,$fechaF);
		//echo $pedidos;
		//print_r($pedidos->row());
		//echo $programadas." - ".$programadasSi;
		$porc=0;
		if($programadas->row()->cuantos>0){
			$porc=$programadas->row()->cuantos/100;
			if($programadasSi->row()->cuantos>0){
				$porc=$programadasSi->row()->cuantos/$porc;
			}
		}
		$porc2=FORMATO_PORCENTAJEDEC($porc);
		//echo $programadas."-1-1";
		//echo $programadas->row()->cuantos."-".$programadasSi->row()->cuantos."-".$porc2;
		//echo $ruta;
		//echo $programadas;
		echo $programadas->row()->cuantos."-".$programadasSi->row()->cuantos."-".$porc2;
	}
	public function postTotalesEfectividad(){
		/*$usuario="Carlos H. Patiño C.,J. Ascencion Osuna A.";
		$sucursal="TODOS";
		$fechaI="2018-08-13";
		$fechaF="2018-08-13";*/
		$usuario=$_POST['usuario'];
		$sucursal=$_POST['sucursal'];
		
		$ruta=$_POST['ruta'];
		$fechaI=$_POST['fechaI'];
		$fechaF=$_POST['fechaF'];
		$pedidos=$this->ReportesModel->getDatosEfectividadTotal($usuario,$sucursal,$ruta,$fechaI,$fechaF);
		//echo $pedidos;
		//print_r($pedidos->row());
		echo $pedidos->row()->cuantos;
	}
	public function postTotalesVisitas(){
		/*$usuario="Carlos H. Patiño C.,J. Ascencion Osuna A.";
		$sucursal="TODOS";
		$fechaI="2018-08-13";
		$fechaF="2018-08-13";*/
		$usuario=$_POST['usuario'];
		$sucursal=$_POST['sucursal'];
		$tipo=$_POST['tipo'];
		//$ruta=$_POST['ruta'];
		$fechaI=$_POST['fechaI'];
		$fechaF=$_POST['fechaF'];
		$pedidos=$this->ReportesModel->getDatosEfectividadTotal($usuario,$sucursal,$tipo,$fechaI,$fechaF);
		
		//echo $pedidos;
		//print_r($pedidos->row());
		echo $pedidos->row()->cuantos;
	}
	
	public function listadoVisitasH($fIni="1900-01-01",$fFin="1900-01-01",$ruta="TODOS",$usuario="TODOS",$sucursal="TODOS"){
		VERIFICARSESION();
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');	
		if(($fIni=="1900-01-01") OR ($fFin=="1900-01-01")){
			$fIni=$fecha1;
			$fFin=$fecha1;
		}
		$data["fIni"]=$fIni;
		$data["fFin"]=$fFin;
		$data["ruta"]=$ruta;
		$data["usuario"]=$usuario;
		$data["sucursal"]=$sucursal;
		$MS=VERIFICAMULTISUCURSAL();

		$data["sucursal"]=$sucursal;
		if($MS==0){
			$data["sucursal"]=GETSUCURSALNAME(GETSUCURSAL());
		}
		$data["lista"]=$this->ReportesModel->getVisitasH($fIni,$fFin);
		$data["listaUsuarios"]=$this->ReportesModel->getUsuarios();
		$data["listaSucursales"]=$this->ReportesModel->getSucursales();
		$data["listaRutas"]=$this->ReportesModel->getRutas();
		$this->load->view('Reportes/vListaReporteVisitasH',$data);

	}
	
	public function listaCumplimientoAgendaH($fIni="1900-01-01",$fFin="1900-01-01",$usuario="TODOS",$ruta="TODOS",$sucursal="TODOS"){
		VERIFICARSESION();
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');	
		if(($fIni=="1900-01-01") OR ($fFin=="1900-01-01")){
			$fIni=$fecha1;
			$fFin=$fecha1;
		}
		$data["fIni"]=$fIni;
		$data["fFin"]=$fFin;
		//$data["tipo"]=$tipo;
		$data["ruta"]=$ruta;
		//$data["usuario"]=str_replace("%20"," ",$usuario);
		$data["usuario"]=GETCARACTERESESPECIALES($usuario);
		$data["sucursal"]=$sucursal;
		$MS=VERIFICAMULTISUCURSAL();

		$data["sucursal"]=$sucursal;
		if($MS==0){
			$data["sucursal"]=GETSUCURSALNAME(GETSUCURSAL());
		}
		$data["lista"]=$this->ReportesModel->getEfectividadAgendaH($fIni,$fFin);
		$data["listaUsuarios"]=$this->ReportesModel->getUsuarios();
		$data["listaSucursales"]=$this->ReportesModel->getSucursales();
		$data["listaRutas"]=$this->ReportesModel->getRutas();
		$this->load->view('Reportes/vListaReporteAgendaH',$data);

	}
	
	public function listaEfectividadH($fIni="1900-01-01",$fFin="1900-01-01",$usuario="TODOS",$ruta="TODOS",$sucursal="TODOS"){
		VERIFICARSESION();
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');	
		if(($fIni=="1900-01-01") OR ($fFin=="1900-01-01")){
			$fIni=$fecha1;
			$fFin=$fecha1;
		}
		$data["fIni"]=$fIni;
		$data["fFin"]=$fFin;
		//$data["tipo"]=$tipo;
		$data["ruta"]=$ruta;
		$data["usuario"]=$usuario;
		$data["sucursal"]=$sucursal;
		$MS=VERIFICAMULTISUCURSAL();

		$data["sucursal"]=$sucursal;
		if($MS==0){
			$data["sucursal"]=GETSUCURSALNAME(GETSUCURSAL());
		}
		$data["lista"]=$this->ReportesModel->getEfectividadH($fIni,$fFin);
		$data["listaUsuarios"]=$this->ReportesModel->getUsuarios();
		$data["listaSucursales"]=$this->ReportesModel->getSucursales();
		$data["listaRutas"]=$this->ReportesModel->getRutas();
		$this->load->view('Reportes/vListaReporteEfectividadH',$data);
	}

	public function imprimirPedido2()
	{
		VERIFICARSESION();
		$this->load->view('Reportes/imprimir/venta.php', $this->input->post());
	}
	
public function listadoPedidosH($fIni="1900-01-01",$fFin="1900-01-01",$tipo="TODOS",$usuario="TODOS",$sucursal="TODOS",$ruta="TODOS"){
		VERIFICARSESION();
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');	
		if(($fIni=="1900-01-01") OR ($fFin=="1900-01-01")){
			$fIni=$fecha1;
			$fFin=$fecha1;
		}
		$data["fIni"]=$fIni;
		$data["fFin"]=$fFin;
		$data["tipo"]=$tipo;
		$data["usuario"]=$usuario;
		$MS=VERIFICAMULTISUCURSAL();

		$data["sucursal"]=$sucursal;
		if($MS==0){
			$data["sucursal"]=GETSUCURSALNAME(GETSUCURSAL());
		}
		$data["ruta"]=$ruta;
		$data["lista"]=$this->ReportesModel->getPedidosH($fIni,$fFin);
		$data["listaUsuarios"]=$this->ReportesModel->getUsuarios();
		$data["listaSucursales"]=$this->ReportesModel->getSucursales();
		$data["listaRutas"]=$this->ReportesModel->getRutasPedidos();
		//echo $data["lista"];
		$this->load->view('Reportes/vListaReporteVentasH',$data);
		
	}

	public function principal(){
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');
		$pedidos=$this->ReportesModel->getPedidosJ($fecha1,$fecha1);
		$pedidosA=array();
		$i=0;
		foreach ($pedidos->result() as $k) {
			
			$pedidosA[$i]['id']=$k->folio;
			$itemsA=array();
			$items=$this->ReportesModel->getItemsJ($k->id);
			$cItems=0;
			foreach ($items->result() as $kItems) {
				$itemsA[$cItems]['id']=$kItems->id;
				if((is_null($kItems->codigoproducto)AND($kItems->codigoproducto!=""))){
					
					$itemsA[$cItems]['product_code']=$kItems->codigoproducto;
					$itemsA[$cItems]['product_id']=$this->ReportesModel->getIdProductoJ($kItems->codigoproducto)->row()->id; //preguntar a gustavo
				}
				else{
					
					$itemsA[$cItems]['product_code']=$kItems->codigoproducto;
					$itemsA[$cItems]['product_id']=null; //preguntar a gustavo
				}
				$itemsA[$cItems]['product_description']=$kItems->producto;
				$itemsA[$cItems]['price']=$kItems->precio;
				$itemsA[$cItems]['quantity']=$kItems->cantidad;
				$itemsA[$cItems]['total']=$kItems->importe;
				$itemsA[$cItems]['comments']=null;
				$cItems++;
			}
			$pedidosA[$i]['items']=$itemsA;
			$pedidosA[$i]['customer_code']=$k->codigocliente;
			$pedidosA[$i]['customer_id']=$k->cliente;
			$cliente=$this->ReportesModel->getClienteJ($k->cliente);
			$clienteA=array();
			$pedidosA[$i]['customer_description']=$cliente->row()->nombre;
			$pedidosA[$i]['customer_email']=$cliente->row()->email;
			$pedidosA[$i]['type']=$k->tipo;
			$pedidosA[$i]['delivery_schedule_date']=null;
			$creadoporA=array();
			$creadopor=$this->ReportesModel->getCreadoporJ($k->idusuario);
			$creadoporA['id']=$creadopor->row()->id;
			$creadoporA['username']=$creadopor->row()->usuario;
			$creadoporA['name']=$creadopor->row()->nombre;
			$pedidosA[$i]['created_by']=$creadoporA;
			$pedidosA[$i]['comment']=null;
			//$pedidosA[$i]['data_created']=$k->fechacreacion;
			$to=$k->fechacreacion;
			list($part1,$part2) = explode(' ', $to);
			list($year, $month, $day) = explode('-', $part1);
			list($hours, $minutes,$seconds) = explode(':', $part2);
			$pedidosA[$i]['data_created'] =  mktime($hours, $minutes, $seconds, $month, $day, $year);
			//echo $timeto;
			$pedidosA[$i]['total']=$k->total;
			$pedidosA[$i]['price_list']=null;
			$pedidosA[$i]['latitude']=$k->latitud;
			$pedidosA[$i]['longitude']=$k->longitud;
			$pedidosA[$i]['accuracy']=0;
			$pedidosA[$i]['deleted']=false;
			//$pedidosA[$i]['']=$k->;
			$i++;
		}
		$resultado=json_encode($pedidosA);
		echo $resultado;
	}
	public function nuevoPerfil(){
		VERIFICARSESION();
		$data['listaModulos']=$this->ConfigurarModel->getListaModulos();
		$this->load->view('Configurar/vNewPerfil',$data);
	}
	public function saveNuevoPerfil(){
		$datos=$this->input->post();
		$this->ConfigurarModel->saveNewPerfil($datos);
		?>
			<script>window.close();</script>
			<?php 
	}
	
	public function borrarPerfil($id,$perfil){
		$this->ConfigurarModel->delPerfil($id,$perfil);
		redirect(CCONFIGURAR(),'refresh');
	}

	function getDatesFromRange($start, $end, $format = 'Y-m-d')
	{
		$array = array();
		$interval = new DateInterval('P1D');
	
		$realEnd = new DateTime($end);
		$realEnd->add($interval);
	
		$period = new DatePeriod(new DateTime($start), $interval, $realEnd);
	
		foreach($period as $date) { 
			$array[] = $date->format($format); 
		}
	
		return $array;
	}

	public function reporteDistribucion()
	{
		$data['clasificaciones'] = $this->CatalogosModel->getListaClasProd();
		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();
		
		$this->load->view('Reportes/vReporteDistribucion', $data);
	}

	public function reporteDistribucionJson()
	{
		$datos = $this->input->post();
		
		if($datos["clasificacion"]!="")
		{
			$idsclasificacion = "";
			foreach($datos["clasificacion"] as $item)
			{
				$idsclasificacion = $idsclasificacion.$item.',';
			}

			$datos["clasificacion"] = substr($idsclasificacion, 0, -1);
		}

		$resultado = $this->ReportesModel->reporteDistribucionJson($datos);

		foreach($resultado as $key => $value)
		{
			$visitasprogramadas = $resultado[$key]["visitasprogramadas"];
			$visitasrealizadas = $resultado[$key]["visitasrealizadas"];

			$resultado[$key]["nombre_ruta"] = (isset($resultado[$key]["ruta_nombre"]) && $resultado[$key]["ruta_nombre"] != "") ? $resultado[$key]["ruta_nombre"] : "";

			$cumplimientoagenda = 0;

			if($visitasprogramadas != 0)
			{
				$cumplimientoagenda = ($visitasrealizadas / $visitasprogramadas) * 100;
			}

			$resultado[$key]["visitasprogramadas"] = $visitasprogramadas;
			$resultado[$key]["visitasrealizadas"] = $visitasrealizadas;
			$resultado[$key]["cumplimientoagenda"] = number_format($cumplimientoagenda, 2, '.', '').'%';
			$resultado[$key]["efectividad"] = ($visitasrealizadas==0) ? "0%" : number_format(($resultado[$key]["numpedidos"] / $visitasrealizadas) * 100, 2, '.', '').'%';
			$resultado[$key]["dropsize"] = number_format(($resultado[$key]["venta"] / $resultado[$key]["numpedidos"]), 2, '.', '');
			$resultado[$key]["venta"] = '$'.number_format($resultado[$key]["venta"], 2, '.', ',');
		}

		echo json_encode($resultado);
	}

	public function viewReporteRepartoEntregas()
	{
		VERIFICARSESION();

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$this->load->view('Reportes/vReporteRepartoEntregas', $data);
	}

	public function listaReporteRepartoEntregasJson()
	{
		$data = $this->input->post();
		echo json_encode($this->ReportesModel->listaReporteRepartoEntregasJson($data));
	}

	public function listaReporteRepartoDepositosJson()
	{
		$data = $this->input->post();
		echo json_encode($this->ReportesModel->listaReporteRepartoDepositosJson($data));
	}

	public function listaReporteRepartoEntregasUsuarioJson()
	{
		$data = $this->input->post();
		echo json_encode($this->ReportesModel->listaReporteRepartoEntregasUsuarioJson($data));
	}

	public function viewReporteUtilidad()
	{
		VERIFICARSESION();

		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();

		$this->load->view('Reportes/vReporteUtilidad', $data);
	}

	public function viewReportePresupuestos()
	{
		VERIFICARSESION();

		$fecha1 = date('Y-m-d');
		
		$periodoC = explode("-",$fecha1);
		$periodo = $periodoC[0].$periodoC[1];

		$data["periodo"] = $periodo;
		$data["listaSucursales"] = $this->ReportesModel->getSucursales();
		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();

		$this->load->view('Reportes/vReportePresupuestos', $data);
	}

	public function listaReporteUtilidadJson()
	{
		$data = $this->input->post();
		echo json_encode($this->ReportesModel->listaReporteUtilidadJson($data));
	}

	public function listaReportePresupuestosJson()
	{
		$data = $this->input->post();
		echo json_encode($this->ReportesModel->listaReportePresupuestosJson($data));
	}

	public function viewReporteCortes()
	{
		VERIFICARSESION();

		$this->load->view('Reportes/vReporteCortes');
	}

	public function listaCortesPendientesJson()
	{
		echo json_encode($this->ReportesModel->listaCortesPendientesJson());
	}

	public function viewReporteMesaControl()
	{
		VERIFICARSESION();

		$data["proveedores"] = $this->CatalogosModel->getListaProveedoresAll()->result();
		$this->load->view('Reportes/vReporteMesaControl', $data);
	}

	public function getReporteMesaControlJson($fecha, $idsucursal)
	{
		$rutas = $this->CatalogosModel->getRutasBySucursal($idsucursal);
		$columnas = $this->ReportesModel->getMesaControlVisitasHoraColumnas($fecha, $idsucursal);
		$valores = $this->ReportesModel->getMesaControlVisitasHoraValores($fecha, $idsucursal, $columnas, $rutas);

		$datos = array(
			"columnas" => $columnas,
			"valores" => $valores
		);

		echo json_encode($datos);
	}

	public function getReporteDistribucionNegocioJson($fecha, $idproveedor, $idsucursal)
	{
		$datos = $this->ReportesModel->getReporteDistribucionNegocioJson($fecha, $idproveedor, $idsucursal);

		echo json_encode($datos);
	}

	public function getReporteVentaCategoriaJson($fecha, $idruta, $tipo)
	{
		$info_ruta = $this->CatalogosModel->getRutaByRuta($idruta)->row();

		$datos = $this->ReportesModel->getReporteVentaCategoriaJson($fecha, $info_ruta->id, $tipo);

		echo json_encode($datos);
	}

	public function getReporteVentaClienteJson($fecha, $idruta, $tipo, $idcategoria)
	{
		$datos = $this->ReportesModel->getReporteVentaClienteJson($fecha, $idruta, $tipo, $idcategoria);

		echo json_encode($datos);
	}

	public function generarExcelPedidos($pFecha1, $pFecha2, $pSucursal)
	{
		ini_set('memory_limit', '2G');
		$this->load->library('excel');
		$namefile = "Reporte_Pedidos";
		$objPHPExcel = $this->excel;

		$result = $this->ReportesModel->generarExcelPedidos($pFecha1, $pFecha2, $pSucursal);

		$alphas = range('A', 'Z');
		$index = 1;
		$letra = 0;

		$rango1 = 'A';
		$rango2 = 'A';		

		$HOJA1 = $objPHPExcel->setActiveSheetIndex(0);
		foreach ($result->row() as $key => $val)
		{
			$colum = strtoupper(str_replace("_", " ", $key));
			$HOJA1->setCellValue("$alphas[$letra]$index", $colum);
			$letra++;
		}

		$rango2 = $alphas[$letra-1];

        $HOJA1->getStyle($rango1."1:".$rango2."1")->getFont()->setBold(true);
	    foreach(range($rango1,$rango2) as $columnID) {
	    	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
	        ->setAutoSize(true);
		}
		
		$HOJA2 = $objPHPExcel->setActiveSheetIndex(0);
		foreach ($result->result_array() as $row) {

			$letra = 0;
			$index++;
			foreach($row as $item){				
				$HOJA2->setCellValue("$alphas[$letra]$index", $item);
				$letra++;
			}
		}

        //ob_end_clean();        
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$namefile.'.xls"');
		header('Cache-Control: max-age=0');		
		header('Cache-Control: max-age=1');		
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
}