<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModuloLiquidacion extends CI_Controller {

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

    	$this->load->model(array('ModuloLiquidacionModel', 'HomeModel'));
    	$this->load->helper(array('url','form'));
	}
	
	public function getLiquidacion($usuario="",$clave="",$empresa="",$fIni="1900-01-01",$fFin="1900-01-01")
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

			$pedidos=$this->ModuloLiquidacionModel->getPedidosJ($fIni,$fFin,$empresa);
			$pedidosA=array();
			$i=0;

			foreach ($pedidos->result() as $k)
			{
				$pedidosA[$i]['id']=$k->folio;
				$itemsA=array();
				$items=$this->ModuloLiquidacionModel->getItemsJ($k->id,$empresa);
				$cItems=0;

				foreach ($items->result() as $kItems)
				{
					$categoria_nombre = "";
					$info_categoria = $this->ModuloLiquidacionModel->getCategoriasProductosJ($kItems->idclasificacion,$empresa);

					if($info_categoria->num_rows() > 0)
					{
						$categoria_nombre = $info_categoria->row()->nombre;
					}

					$itemsA[$cItems]['id'] = $kItems->id;
					$itemsA[$cItems]['clasificacion_id'] = $kItems->idclasificacion;
					$itemsA[$cItems]['clasificacion_nombre'] = $categoria_nombre;
					$itemsA[$cItems]['product_code'] = $kItems->codigoproducto;
					$itemsA[$cItems]['product_id'] = $kItems->iditem;
					$itemsA[$cItems]['product_description'] = $kItems->producto;
					$itemsA[$cItems]['price'] = number_format((double)$kItems->precio, 2, '.', '');
					$itemsA[$cItems]['quantity'] = (double)$kItems->cantidad;
					$itemsA[$cItems]['total'] = number_format((double)$kItems->importe, 2, '.', '');
					$itemsA[$cItems]['comments'] = null;
					$cItems++;
				}

				$pedidosA[$i]['items'] = $itemsA;
				$pedidosA[$i]['customer_code'] = $k->codigocliente;
				$pedidosA[$i]['customer_id'] = (integer)$k->idcliente;
				$pedidosA[$i]['customer_name'] = $k->cliente;

				$cliente = $this->ModuloLiquidacionModel->getClienteJ($k->idcliente,$empresa);
				$clienteA = array();

				$pedidosA[$i]['customer_description'] = ($cliente->num_rows()>0) ? $cliente->row()->nombre : "";
				$pedidosA[$i]['customer_email'] = ($cliente->num_rows()>0) ? $cliente->row()->email : "";
				$pedidosA[$i]['type']=$k->tipo;
				$pedidosA[$i]['delivery_schedule_date']=null;

				$creadoporA=array();
				$creadopor=$this->ModuloLiquidacionModel->getCreadoporJ($k->idusuario);				
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
				$pedidosA[$i]['total']=number_format((double)$k->total, 2, '.', '');
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

		$resultado = json_encode($principal, JSON_UNESCAPED_UNICODE);
		echo $resultado;
	}

	public function getProductosJson($usuario="",$clave="",$empresa)
	{
			
		$perfil=$this->HomeModel->inicioSesionLiq($usuario,$clave,$empresa);
		$validacion=GETACCESOX("Reportes","getLiquidacion",$perfil);
		$principal=array();
		if($validacion!=0){
			
			$principal['error']=false;
			$principal['message']="";
			$usuarios=$this->ModuloLiquidacionModel->getProductosJ($empresa);
			$usuariosA=array();
			$i=0;
			foreach ($usuarios->result() as $k) {
				$usuariosA[$i]['id']=$k->id;
				$usuariosA[$i]['code']=$k->codigo;
				$usuariosA[$i]['description']=$k->nombre;
				$categoriaA=array();
				$categoria = $this->ModuloLiquidacionModel->getCategoriasProductosJ($k->clasificacion,$empresa);
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
				$usuariosA[$i]['barcode']= is_null($k->codigobarras) ? "00000" : $k->codigobarras;
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
	}

	public function getClientesJson($usuario="",$clave="",$empresa){
			
		$perfil=$this->HomeModel->inicioSesionLiq($usuario,$clave,$empresa);
		$validacion=GETACCESOX("Reportes","getLiquidacion",$perfil);
		$principal=array();
		if($validacion!=0){
			
			$principal['error']=false;
			$principal['message']="";
			$usuarios=$this->ModuloLiquidacionModel->getClientesJ($empresa);
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
			
				$zona=$this->ModuloLiquidacionModel->getZonasJ($k->zona,$empresa);
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
	}

	public function getUsuariosJson($usuario="",$clave="",$empresa)
	{
		$perfil=$this->HomeModel->inicioSesionLiq($usuario,$clave,$empresa);
		$validacion=GETACCESOX("Reportes","getLiquidacion",$perfil);
		$principal=array();
		if($validacion!=0){
			
			$principal['error'] = false;
			$principal['message'] = "";
			$usuarios = $this->ModuloLiquidacionModel->getUsuariosJ($empresa);
			$usuariosA = array();
			$i = 0;
			foreach ($usuarios->result() as $k) {
				$usuariosA[$i]['id']=$k->id;
				$usuariosA[$i]['name']=$k->nombre;
				$usuariosA[$i]['username']=$k->usuario;
				$usuariosA[$i]['role']=$k->perfil;
				$usuariosA[$i]['idsucursal']=$k->sucursal;
				$usuariosA[$i]['sucursal_nombre'] = $this->ModuloLiquidacionModel->getNombreSucursal($k->sucursal, "sucursal", $empresa);
				$usuariosA[$i]['sucursal_clave'] = $this->ModuloLiquidacionModel->getNombreSucursal($k->sucursal, "clave", $empresa);
				$to=$k->fechacreacion;
				//echo $to;
				list($part1,$part2) = explode(' ', $to);
				list($year, $month, $day) = explode('-', $part1);
				list($hours,$minutes,$seconds) = explode(':', $part2);
				/*$usuariosA[$i]['date_created'] =  $to;
				$usuariosA[$i]['date_last_login'] =  $to;
				$usuariosA[$i]['date_last_logout'] =  $to;*/
				$usuariosA[$i]['date_created'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				$usuariosA[$i]['date_last_login'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				$usuariosA[$i]['date_last_logout'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
				$usuariosA[$i]['phone_number'] = $k->telefono;

				$info_ruta = $this->ModuloLiquidacionModel->getRutaUsuario($k->id, $empresa);
				if($info_ruta->num_rows() > 0){
					$usuariosA[$i]['ruta'] = $info_ruta->row()->ruta;
				}else{
					$usuariosA[$i]['ruta'] = "SIN RUTA";
				}
				
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
	}

	public function leerAcumuladosJson()
	{
		$cadena=$_POST['cadena'];
		$empresa=$_POST['empresa'];
		$this->ModuloLiquidacionModel->getAgregarAcumulados($cadena,$empresa);
		echo "listo";
	}

	public function leerObjetivosVentas()
	{		
		$cadena=$_POST['cadena'];
		$empresa=$_POST['empresa'];
		$res=$this->ModuloLiquidacionModel->getAgregarObjetivos($cadena,$empresa);
		echo "listo";
	}

	public function listadoPedidosJson($empresa)
	{
		$data = $this->input->post();
		$data = array(
			"fechade" => "2018-11-01",
			"fechaa" => "2019-01-31",
			"tipo" => "0",
			"sucursal" => "1",
			"ruta" => "0",
			"usuario" => "0",
		);
		echo json_encode($this->ModuloLiquidacionModel->getPedidos($data,$empresa)->result());
	}

	public function getClientesByFecha($fecha, $empresa)
	{
		$datos = array(
			"clientes" => $this->ModuloLiquidacionModel->getClientesByFecha($fecha, $empresa)->result(),
		);
		
		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function getAcumuladosObjetivos($empresa, $periodo)
	{
		/*
		ACTUALIZA IDCATEGORIA
		UPDATE acumulados_categorias SET idcategoria = (SELECT ccp.id FROM cat_clasificacionproductos ccp WHERE acumulados_categorias.`categoria` = ccp.nombre);
		*/

		$datos = $this->ModuloLiquidacionModel->getAcumuladosObjetivos($empresa, $periodo);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);		
	}

	public function saveCategoriasObjetivosAcumulado()
	{
		$datos = $this->input->post();
		$empresa = $datos["empresa"];
		$json = $datos["json"];

		echo $this->ModuloLiquidacionModel->saveCategoriasObjetivosAcumulado($empresa, $json);
	}

	public function getProductosCategorias($empresa)
	{
		echo json_encode($this->ModuloLiquidacionModel->getProductosJ($empresa)->result(), JSON_UNESCAPED_UNICODE);
	}

	public function getCategorias($empresa)
	{
		echo json_encode($this->ModuloLiquidacionModel->getCategorias($empresa)->result(), JSON_UNESCAPED_UNICODE);
	}

	public function saveCategoriasValidas()
	{
		$datos = $this->input->post();
		$empresa = $datos["empresa"];
		$json = $datos["json"];

		echo $this->ModuloLiquidacionModel->saveCategoriasValidas($empresa, $json);
	}

	public function getInfoEmpresa($empresa)
	{
		$datos = array(
			"empresa" => $this->ModuloLiquidacionModel->getInfoEmpresa($empresa)->result()
		);
		
		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function postLogin()
	{
		$datos = array(
			"success" => true,
			"mensaje" => "todo bien"
		);

		$post = $this->input->post();

		$info_usuario = $this->ModuloLiquidacionModel->postLogin($post);

		if($info_usuario->num_rows() > 0)
		{
			$datos["usuario"] = $info_usuario->row();
		}
		else
		{
			$datos["success"] = false;
			$datos["mensaje"] = "El usuario ingresado no se encuentra registrado. Favor de verificarlo";
		}

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function postConfirmarPedidos()
	{
		$post = $this->input->post();

		$pedidos = $this->ModuloLiquidacionModel->postConfirmarPedidos($post);

		$datos = array(
			"pedidos" => $pedidos,
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function saveFinalizarArmadoRuta()
	{
		$post = $this->input->post();

		echo $this->ModuloLiquidacionModel->saveFinalizarArmadoRuta($post);
	}

	public function listaPedidosByFecha()
	{
		$post = $this->input->post();

		$pedidos = $this->ModuloLiquidacionModel->listaPedidosByFecha($post, "PRINCIPAL");
		$pedidos_detalle = $this->ModuloLiquidacionModel->listaPedidosByFecha($post, "DETALLE");

		$datos = array(
			"pedidos" => $pedidos,
			"pedidos_detalle" => $pedidos_detalle,
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function getReporteSellInOut4($empresa)
	{
		$arraydata = array(
			'movil' => "1",
			'empresa' => $empresa
		);
		
		$this->session->set_userdata($arraydata);

		$this->load->model('EstadisticasModel', 'EstadisticasModel');

		$fecha_envio = date("d").' de '.date("M").' del '.date("Y");
		$pPeriodo = $this->EstadisticasModel->getLastPeriodo()->periodo;
		$ultima_fecha = $this->EstadisticasModel->getLastPeriodo()->fecha;

		$data = array(
			"periodo" => $pPeriodo
		);		

		$this->load->library('excel');

		//$objPHPExcel = $this->excel;

		copy('excel/reporte_proyeccion_template.xls', 'excel/Reporte.xls');

		$fileType = 'Excel5';
		$fileName = 'Reporte.xls';

		// Read the file
		$objReader = PHPExcel_IOFactory::createReader($fileType);
		$objPHPExcel = $objReader->load("excel/$fileName");

		$data["tipo"] = "GLOBAL";
		$datos_global = $this->EstadisticasModel->getReporteSellInOut($data);

		$data["tipo_resumen"] = "DA";
		$resumen_global_da = $this->EstadisticasModel->getResumenSellInOut($data);

		$data["tipo_resumen"] = "IMPULSO";
		$resumen_global_impulso = $this->EstadisticasModel->getResumenSellInOut($data);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "Periodo: $pPeriodo");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "Hasta el dia: $ultima_fecha");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "Fecha de Envio: $fecha_envio");
		$objPHPExcel->getActiveSheet()->getStyle("A3:A5")->getFont()->setBold(true);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C38",  8257742);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C43",  1043161);
		
		//####### INICIO VALORES GLOBAL ######################################################
		$categorias_cumplidas = 0;
		$index = 10;
		foreach ($datos_global as $item) 
		{
			if($item["categoria"] != "CHOCOLATE IMPULSO")
			{
				$objetivo = $item["objetivo"];//number_format($item["objetivo"], 0, '.', ',');
				$venta = $item["venta"];//number_format($item["venta"], 0, '.', ',');
				$alcance = $item["alcance"];//number_format($item["alcance"], 0)."%";
				
				$index++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$index", $objetivo);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$index", $venta);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$index", "$alcance%");

				$this->parametrosColorCell($objPHPExcel, "E$index", $alcance);
			}

			if(number_format($item["alcance"], 0) >= 100)
			{
				$categorias_cumplidas++;
			}
		}

		//#####################################################################################################################################
		$porcentaje_total_fb = ($resumen_global_da->acumulado_importe_da_real / $resumen_global_da->acumulado_objetivo_da) * 100;
		$porcentaje_total_fb = floor($porcentaje_total_fb);

		$porcentaje_dropsize_fb = ($resumen_global_da->dropsize_da / 300) * 100;
		$porcentaje_dropsize_fb = floor($porcentaje_dropsize_fb);

		$porcentaje_pedidos_fb = ($resumen_global_da->promedio_ventas_da / 35) * 100;
		$porcentaje_pedidos_fb = floor($porcentaje_pedidos_fb);

		$this->parametrosColorCell($objPHPExcel, "E24", $porcentaje_total_fb);
		$this->parametrosColorCell($objPHPExcel, "E25", $porcentaje_dropsize_fb);
		$this->parametrosColorCell($objPHPExcel, "E26", $porcentaje_pedidos_fb);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E24", "$porcentaje_total_fb%");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E25", "$porcentaje_dropsize_fb%");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E26", "$porcentaje_pedidos_fb%");
		//#####################################################################################################################################

		//#####################################################################################################################################
		$porcentaje_total_impulso = ($resumen_global_impulso->acumulado_importe_da_real / $resumen_global_impulso->acumulado_objetivo_da) * 100;
		$porcentaje_total_impulso = floor($porcentaje_total_impulso);

		$porcentaje_dropsize_impulso = ($resumen_global_impulso->dropsize_da / 150) * 100;
		$porcentaje_dropsize_impulso = floor($porcentaje_dropsize_impulso);

		$porcentaje_pedidos_impulso = ($resumen_global_impulso->promedio_ventas_da / 15) * 100;
		$porcentaje_pedidos_impulso = floor($porcentaje_pedidos_impulso);

		$this->parametrosColorCell($objPHPExcel, "E28", $porcentaje_total_impulso);
		$this->parametrosColorCell($objPHPExcel, "E29", $porcentaje_dropsize_impulso);
		$this->parametrosColorCell($objPHPExcel, "E30", $porcentaje_pedidos_impulso);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E28", "$porcentaje_total_impulso%");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E29", "$porcentaje_dropsize_impulso%");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E30", "$porcentaje_pedidos_impulso%");
		//#####################################################################################################################################		

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D25", $resumen_global_da->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D26", $resumen_global_da->promedio_ventas_da);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C28", $resumen_global_impulso->acumulado_objetivo_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D28", $resumen_global_impulso->acumulado_importe_da_real);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D29", $resumen_global_impulso->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D30", $resumen_global_impulso->promedio_ventas_da);

		$porcentaje_categorias = ($categorias_cumplidas / 8) * 100;
		$porcentaje_categorias = floor($porcentaje_categorias);
		$this->parametrosColorCell($objPHPExcel, "E32", $porcentaje_categorias);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D32", $categorias_cumplidas);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E32", "$porcentaje_categorias%");
		//####### FIN VALORES GLOBAL ######################################################

		//####### INICIO VALORES PACIFICO ######################################################
		$data["tipo"] = "PACIFICO";
		$datos_pacifico = $this->EstadisticasModel->getReporteSellInOut($data);

		$data["tipo_resumen"] = "DA";
		$resumen_pacifico_da = $this->EstadisticasModel->getResumenSellInOut($data);

		$data["tipo_resumen"] = "IMPULSO";
		$resumen_pacifico_impulso = $this->EstadisticasModel->getResumenSellInOut($data);


		$categorias_cumplidas = 0;
		$index = 50;
		foreach ($datos_pacifico as $item) 
		{
			if($item["categoria"] != "CHOCOLATE IMPULSO")
			{
				$objetivo = $item["objetivo"];//number_format($item["objetivo"], 0, '.', ',');
				$venta = $item["venta"];//number_format($item["venta"], 0, '.', ',');
				$alcance = $item["alcance"];//number_format($item["alcance"], 0)."%";
				
				$index++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$index", $objetivo);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$index", $venta);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$index", "$alcance%");

				$this->parametrosColorCell($objPHPExcel, "E$index", $alcance);
			}

			if(number_format($item["alcance"], 0) >= 100)
			{
				$categorias_cumplidas++;
			}
		}

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D65", $resumen_pacifico_da->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D66", $resumen_pacifico_da->promedio_ventas_da);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C68", $resumen_pacifico_impulso->acumulado_objetivo_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D68", $resumen_pacifico_impulso->acumulado_importe_da_real);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D69", $resumen_pacifico_impulso->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D70", $resumen_pacifico_impulso->promedio_ventas_da);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D72", $categorias_cumplidas);
		//####### FIN VALORES PACIFICO ######################################################

		//####### INICIO VALORES PACIFICO SUCURSALES ######################################################
		$sucursales = $this->EstadisticasModel->getSucursalesZonas("1");

		foreach($sucursales as $sucursal)
		{
			$data["tipo"] = "SUCURSAL";
			$data["sucursal"] = $sucursal->id;
			$datos_sucursal = $this->EstadisticasModel->getReporteSellInOut($data);

			$data["tipo_resumen"] = "DA";
			$resumen_sucursal_da = $this->EstadisticasModel->getResumenSellInOut($data);

			$data["tipo_resumen"] = "IMPULSO";
			$resumen_sucursal_impulso = $this->EstadisticasModel->getResumenSellInOut($data);

			$categorias_cumplidas = 0;
			$index = 50;
			foreach ($datos_sucursal as $item) 
			{
				if($item["categoria"] != "CHOCOLATE IMPULSO")
				{
					$objetivo = $item["objetivo"];
					$venta = $item["venta"];
					$alcance = $item["alcance"];
					
					$index++;

					if($sucursal->id == "1")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$index", $venta);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$index", "$alcance%");

						$this->parametrosColorCell($objPHPExcel, "I$index", $alcance);

					}
					else if($sucursal->id == "9")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$index", $venta);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$index", "$alcance%");

						$this->parametrosColorCell($objPHPExcel, "M$index", $alcance);
					}
					else if($sucursal->id == "12")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P$index", $venta);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q$index", "$alcance%");

						$this->parametrosColorCell($objPHPExcel, "Q$index", $alcance);
					}
					else if($sucursal->id == "13")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T$index", $venta);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("U$index", "$alcance%");

						$this->parametrosColorCell($objPHPExcel, "U$index", $alcance);
					}
				}

				if(number_format($item["alcance"], 0) >= 100)
				{
					$categorias_cumplidas++;
				}
			}

			if($sucursal->id == "1")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H65", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H66", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G68", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H68", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H69", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H70", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H72", $categorias_cumplidas);
			}
			else if($sucursal->id == "9")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L65", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L66", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K68", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L68", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L69", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L70", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L72", $categorias_cumplidas);
			}
			else if($sucursal->id == "12")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P65", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P66", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O68", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P68", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P69", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P70", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P72", $categorias_cumplidas);
			}
			else if($sucursal->id == "13")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T65", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T66", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S68", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T68", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T69", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T70", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T72", $categorias_cumplidas);
			}
		}
		//####### FIN VALORES PACIFICO SUCURSALES ######################################################

		//####### INICIO VALORES NORTE ######################################################
		$data["tipo"] = "NORTE";
		$datos_norte = $this->EstadisticasModel->getReporteSellInOut($data);

		$data["tipo_resumen"] = "DA";
		$resumen_norte_da = $this->EstadisticasModel->getResumenSellInOut($data);

		$data["tipo_resumen"] = "IMPULSO";
		$resumen_norte_impulso = $this->EstadisticasModel->getResumenSellInOut($data);


		$categorias_cumplidas = 0;
		$index = 85;
		foreach ($datos_norte as $item) 
		{
			if($item["categoria"] != "CHOCOLATE IMPULSO")
			{
				$objetivo = $item["objetivo"];//number_format($item["objetivo"], 0, '.', ',');
				$venta = $item["venta"];//number_format($item["venta"], 0, '.', ',');
				$alcance = $item["alcance"];//number_format($item["alcance"], 0)."%";
				
				$index++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$index", $objetivo);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$index", $venta);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$index", "$alcance%");

				$this->parametrosColorCell($objPHPExcel, "E$index", $alcance);
			}

			if(number_format($item["alcance"], 0) >= 100)
			{
				$categorias_cumplidas++;
			}
		}

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D100", $resumen_norte_da->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D101", $resumen_norte_da->promedio_ventas_da);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C103", $resumen_norte_impulso->acumulado_objetivo_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D103", $resumen_norte_impulso->acumulado_importe_da_real);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D104", $resumen_norte_impulso->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D105", $resumen_norte_impulso->promedio_ventas_da);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D107", $categorias_cumplidas);
		//####### FIN VALORES NORTE ######################################################

		//####### INICIO VALORES NORTE SUCURSALES ######################################################
		$sucursales = $this->EstadisticasModel->getSucursalesZonas("2");

		foreach($sucursales as $sucursal)
		{
			$data["tipo"] = "SUCURSAL";
			$data["sucursal"] = $sucursal->id;
			$datos_sucursal = $this->EstadisticasModel->getReporteSellInOut($data);

			$data["tipo_resumen"] = "DA";
			$resumen_sucursal_da = $this->EstadisticasModel->getResumenSellInOut($data);

			$data["tipo_resumen"] = "IMPULSO";
			$resumen_sucursal_impulso = $this->EstadisticasModel->getResumenSellInOut($data);

			$categorias_cumplidas = 0;
			$index = 85;
			foreach ($datos_sucursal as $item) 
			{
				if($item["categoria"] != "CHOCOLATE IMPULSO")
				{
					$objetivo = $item["objetivo"];
					$venta = $item["venta"];
					$alcance = $item["alcance"];
					
					$index++;

					if($sucursal->id == "10")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$index", $venta);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$index", "$alcance%");

						$this->parametrosColorCell($objPHPExcel, "I$index", $alcance);
					}
					else if($sucursal->id == "11")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$index", $venta);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$index", "$alcance%");

						$this->parametrosColorCell($objPHPExcel, "M$index", $alcance);
					}
					else if($sucursal->id == "14")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P$index", $venta);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q$index", "$alcance%");

						$this->parametrosColorCell($objPHPExcel, "Q$index", $alcance);
					}
				}

				if(number_format($item["alcance"], 0) >= 100)
				{
					$categorias_cumplidas++;
				}
			}

			if($sucursal->id == "10")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H100", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H101", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G103", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H103", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H104", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H105", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H107", $categorias_cumplidas);
			}
			else if($sucursal->id == "11")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L100", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L101", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K103", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L103", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L104", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L105", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L107", $categorias_cumplidas);
			}
			else if($sucursal->id == "14")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P100", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P101", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O103", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P103", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P104", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P105", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P107", $categorias_cumplidas);
			}
		}
		//####### FIN VALORES NORTE SUCURSALES ######################################################		

		// Write the file
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
		$objWriter->save("excel/$fileName");

		$objReader = PHPExcel_IOFactory::createReader($fileType);
		$objPHPExcel = $objReader->load("excel/$fileName");

		$this->parametrosColorCell3($objPHPExcel, "E36");
		$this->parametrosColorCell3($objPHPExcel, "E38");
		$this->parametrosColorCell3($objPHPExcel, "E41");
		$this->parametrosColorCell3($objPHPExcel, "E43");
		$this->parametrosColorCell3($objPHPExcel, "E64");
		$this->parametrosColorCell3($objPHPExcel, "E65");
		$this->parametrosColorCell3($objPHPExcel, "E66");
		$this->parametrosColorCell3($objPHPExcel, "E68");
		$this->parametrosColorCell3($objPHPExcel, "E69");
		$this->parametrosColorCell3($objPHPExcel, "E70");
		$this->parametrosColorCell3($objPHPExcel, "E72");
		$this->parametrosColorCell3($objPHPExcel, "E76");
		//$this->parametrosColorCell3($objPHPExcel, "E78");
		//$this->parametrosColorCell3($objPHPExcel, "E79");
		//$this->parametrosColorCell3($objPHPExcel, "E83");
		$this->parametrosColorCell3($objPHPExcel, "E99");
		$this->parametrosColorCell3($objPHPExcel, "E100");
		$this->parametrosColorCell3($objPHPExcel, "E101");
		$this->parametrosColorCell3($objPHPExcel, "E103");
		$this->parametrosColorCell3($objPHPExcel, "E104");
		$this->parametrosColorCell3($objPHPExcel, "E105");
		$this->parametrosColorCell3($objPHPExcel, "E107");
		$this->parametrosColorCell3($objPHPExcel, "E111");
		$this->parametrosColorCell3($objPHPExcel, "E113");
		$this->parametrosColorCell3($objPHPExcel, "E116");
		$this->parametrosColorCell3($objPHPExcel, "E118");

		$this->parametrosColorCell3($objPHPExcel, "I60");
		$this->parametrosColorCell3($objPHPExcel, "I61");
		$this->parametrosColorCell3($objPHPExcel, "I62");
		$this->parametrosColorCell3($objPHPExcel, "I63");
		$this->parametrosColorCell3($objPHPExcel, "I64");
		$this->parametrosColorCell3($objPHPExcel, "I66");
		$this->parametrosColorCell3($objPHPExcel, "I88");
		$this->parametrosColorCell3($objPHPExcel, "I74");
		$this->parametrosColorCell3($objPHPExcel, "I78");
		$this->parametrosColorCell3($objPHPExcel, "I77");
		$this->parametrosColorCell3($objPHPExcel, "I83");
		$this->parametrosColorCell3($objPHPExcel, "I99");
		$this->parametrosColorCell3($objPHPExcel, "I101");
		$this->parametrosColorCell3($objPHPExcel, "I104");
		$this->parametrosColorCell3($objPHPExcel, "I103");
		$this->parametrosColorCell3($objPHPExcel, "I104");
		$this->parametrosColorCell3($objPHPExcel, "I105");
		$this->parametrosColorCell3($objPHPExcel, "I117");
		$this->parametrosColorCell3($objPHPExcel, "I111");
		$this->parametrosColorCell3($objPHPExcel, "I117");
		$this->parametrosColorCell3($objPHPExcel, "I116");
		$this->parametrosColorCell3($objPHPExcel, "I122");

		$this->parametrosColorCell3($objPHPExcel, "M64");
		$this->parametrosColorCell3($objPHPExcel, "M65");
		$this->parametrosColorCell3($objPHPExcel, "M66");
		$this->parametrosColorCell3($objPHPExcel, "M68");
		$this->parametrosColorCell3($objPHPExcel, "M69");
		$this->parametrosColorCell3($objPHPExcel, "M70");
		$this->parametrosColorCell3($objPHPExcel, "M72");
		$this->parametrosColorCell3($objPHPExcel, "M76");
		$this->parametrosColorCell3($objPHPExcel, "M78");
		$this->parametrosColorCell3($objPHPExcel, "M79");
		$this->parametrosColorCell3($objPHPExcel, "M83");
		$this->parametrosColorCell3($objPHPExcel, "M99");
		$this->parametrosColorCell3($objPHPExcel, "M100");
		$this->parametrosColorCell3($objPHPExcel, "M101");
		$this->parametrosColorCell3($objPHPExcel, "M103");
		$this->parametrosColorCell3($objPHPExcel, "M104");
		$this->parametrosColorCell3($objPHPExcel, "M105");
		$this->parametrosColorCell3($objPHPExcel, "M107");
		$this->parametrosColorCell3($objPHPExcel, "M111");
		$this->parametrosColorCell3($objPHPExcel, "M117");
		$this->parametrosColorCell3($objPHPExcel, "M116");
		$this->parametrosColorCell3($objPHPExcel, "M122");

		$this->parametrosColorCell3($objPHPExcel, "Q64");
		$this->parametrosColorCell3($objPHPExcel, "Q65");
		$this->parametrosColorCell3($objPHPExcel, "Q66");
		$this->parametrosColorCell3($objPHPExcel, "Q68");
		$this->parametrosColorCell3($objPHPExcel, "Q69");
		$this->parametrosColorCell3($objPHPExcel, "Q70");
		$this->parametrosColorCell3($objPHPExcel, "Q72");
		$this->parametrosColorCell3($objPHPExcel, "Q76");
		$this->parametrosColorCell3($objPHPExcel, "Q78");
		$this->parametrosColorCell3($objPHPExcel, "Q79");
		$this->parametrosColorCell3($objPHPExcel, "Q83");
		$this->parametrosColorCell3($objPHPExcel, "Q99");
		$this->parametrosColorCell3($objPHPExcel, "Q100");
		$this->parametrosColorCell3($objPHPExcel, "Q101");
		$this->parametrosColorCell3($objPHPExcel, "Q103");
		$this->parametrosColorCell3($objPHPExcel, "Q104");
		$this->parametrosColorCell3($objPHPExcel, "Q105");
		$this->parametrosColorCell3($objPHPExcel, "Q107");
		$this->parametrosColorCell3($objPHPExcel, "Q111");
		$this->parametrosColorCell3($objPHPExcel, "Q113");
		$this->parametrosColorCell3($objPHPExcel, "Q116");
		$this->parametrosColorCell3($objPHPExcel, "Q118");

		$this->parametrosColorCell3($objPHPExcel, "U64");
		$this->parametrosColorCell3($objPHPExcel, "U65");
		$this->parametrosColorCell3($objPHPExcel, "U66");
		$this->parametrosColorCell3($objPHPExcel, "U68");
		$this->parametrosColorCell3($objPHPExcel, "U69");
		$this->parametrosColorCell3($objPHPExcel, "U70");
		$this->parametrosColorCell3($objPHPExcel, "U72");
		$this->parametrosColorCell3($objPHPExcel, "U76");
		//$this->parametrosColorCell3($objPHPExcel, "U78");
		$this->parametrosColorCell3($objPHPExcel, "U79");
		//$this->parametrosColorCell3($objPHPExcel, "U83");

		$this->parametrosColorCell3($objPHPExcel, "Y64");
		$this->parametrosColorCell3($objPHPExcel, "Y65");
		$this->parametrosColorCell3($objPHPExcel, "Y66");
		$this->parametrosColorCell3($objPHPExcel, "Y68");
		$this->parametrosColorCell3($objPHPExcel, "Y69");
		$this->parametrosColorCell3($objPHPExcel, "Y70");
		$this->parametrosColorCell3($objPHPExcel, "Y72");
		$this->parametrosColorCell3($objPHPExcel, "Y76");
		//$this->parametrosColorCell3($objPHPExcel, "Y78");
		$this->parametrosColorCell3($objPHPExcel, "Y79");
		//$this->parametrosColorCell3($objPHPExcel, "Y83");

		foreach(range('A','ZZ') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		//$objPHPExcel->getActiveSheet()->removeRow(78, 1);
		//$objPHPExcel->getActiveSheet()->removeRow(79, 1);

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
		$objWriter->save("excel/$fileName");

		/*header('Content-Type: application/ms-excel');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');*/

		$objWriter->save('php://output');

		//$this->SendEmail("excel/$fileName", $pPeriodo, $ultima_fecha, $empresa);
	}

	public function formatearCeldas($pExcel, $pLetra, $inicio, $final)
	{
		for($x=$inicio; $x<=$final; $x++)
		{
			if(is_numeric($pExcel->getActiveSheet()->getCell($pLetra.$x)->getCalculatedValue()))
			{
				$valor = (int)($pExcel->getActiveSheet()->getCell($pLetra.$x)->getCalculatedValue()*100);

				if($valor < 95)
				{
					$pExcel->getActiveSheet()->getStyle($pLetra.$x)->getFont()->getColor()->setRGB('FF0808');
				}
				else if($valor >= 95)
				{
					$pExcel->getActiveSheet()->getStyle($pLetra.$x)->getFont()->getColor()->setRGB('000000');
					$pExcel->getActiveSheet()->getStyle($pLetra.$x)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('A9D08E');
				}
			}
		}
	}

	public function getReporteSellInOut3($empresa)
	{
		$arraydata = array(
			'movil' => "1",
			'empresa' => $empresa
		);
		
		$this->session->set_userdata($arraydata);

		$this->load->model('EstadisticasModel', 'EstadisticasModel');
		$this->load->model('CatalogosModel', 'CatalogosModel');

		$info_objetivo = $this->CatalogosModel->getValoresContrato();

		$fecha_envio = date("d").' de '.date("M").' del '.date("Y");
		$pPeriodo = $this->EstadisticasModel->getLastPeriodo()->periodo;
		$ultima_fecha = $this->EstadisticasModel->getLastPeriodo()->fecha;

		$data = array(
			"periodo" => $pPeriodo
		);		

		$this->load->library('excel');

		copy('excel/reporte_proyeccion_template_2.xls', 'excel/Reporte.xls');

		$fileType = 'Excel5';
		$fileName = 'Reporte.xls';

		// Read the file
		$objReader = PHPExcel_IOFactory::createReader($fileType);
		$objPHPExcel = $objReader->load("excel/$fileName");

		$data["tipo"] = "GLOBAL";
		$datos_global = $this->EstadisticasModel->getReporteSellInOut($data);

		$data["tipo_resumen"] = "DA";
		$resumen_global_da = $this->EstadisticasModel->getResumenSellInOut($data);

		$data["tipo_resumen"] = "IMPULSO";
		$resumen_global_impulso = $this->EstadisticasModel->getResumenSellInOut($data);

		$data["tipo_resumen"] = "RTD";
		$resumen_global_rtd = $this->EstadisticasModel->getResumenSellInOut($data);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C42', $info_objetivo->row()->objetivo_fb);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C47', $info_objetivo->row()->objetivo_impulso);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C52', $info_objetivo->row()->objetivo_rtd);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "Periodo: $pPeriodo");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "Hasta el dia: $ultima_fecha");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "Fecha de Envio: $fecha_envio");
		
		//####### INICIO VALORES GLOBAL ######################################################
		$categorias_cumplidas = 0;
		$index = 10;
		foreach ($datos_global as $item) 
		{
			if($item["categoria"] != "CHOCOLATE IMPULSO" && $item["categoria"] != "RTD")
			{
				$objetivo = $item["objetivo"];
				$venta = $item["venta"];
				$alcance = $item["alcance"];
				
				$index++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$index", $objetivo);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$index", $venta);
				//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$index", "$alcance%");
			}

			if((float)$item["alcance"] >= 100)
			{
				$categorias_cumplidas++;
			}
		}

		//GLOBAL FB
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D25", $resumen_global_da->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D26", $resumen_global_da->promedio_ventas_da);

		//GLOBAL IMPULSO
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C28", $resumen_global_impulso->acumulado_objetivo_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D28", $resumen_global_impulso->acumulado_importe_da_real);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D29", $resumen_global_impulso->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D30", $resumen_global_impulso->promedio_ventas_da);

		//GLOBAL RTD
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C32", $resumen_global_rtd->acumulado_objetivo_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D32", $resumen_global_rtd->acumulado_importe_da_real);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D33", $resumen_global_rtd->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D34", $resumen_global_rtd->promedio_ventas_da);

		//GLOBAL CATEGORIAS CUMPLIDAS
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D36", $categorias_cumplidas);

		//####### FIN VALORES GLOBAL ######################################################

		//####### INICIO VALORES ZONA 1 ######################################################
		$data["tipo"] = "PACIFICO";
		$datos_pacifico = $this->EstadisticasModel->getReporteSellInOut($data);

		$data["tipo_resumen"] = "DA";
		$resumen_pacifico_da = $this->EstadisticasModel->getResumenSellInOut($data);

		$data["tipo_resumen"] = "IMPULSO";
		$resumen_pacifico_impulso = $this->EstadisticasModel->getResumenSellInOut($data);

		$data["tipo_resumen"] = "RTD";
		$resumen_pacifico_rtd = $this->EstadisticasModel->getResumenSellInOut($data);


		$categorias_cumplidas = 0;
		$index = 59;
		foreach ($datos_pacifico as $item) 
		{
			if($item["categoria"] != "CHOCOLATE IMPULSO" && $item["categoria"] != "RTD")
			{
				$objetivo = $item["objetivo"];
				$venta = $item["venta"];
				$alcance = $item["alcance"];
				
				$index++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$index", $objetivo);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$index", $venta);
				//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$index", "$alcance%");
			}

			if((float)$item["alcance"] >= 100)
			{
				$categorias_cumplidas++;
			}
		}

		//ZONA 1 FB
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D74", $resumen_pacifico_da->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D75", $resumen_pacifico_da->promedio_ventas_da);

		//ZONA 1 IMPULSO
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C77", $resumen_pacifico_impulso->acumulado_objetivo_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D77", $resumen_pacifico_impulso->acumulado_importe_da_real);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D78", $resumen_pacifico_impulso->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D79", $resumen_pacifico_impulso->promedio_ventas_da);

		//ZONA 1 RTD
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C81", $resumen_pacifico_rtd->acumulado_objetivo_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D81", $resumen_pacifico_rtd->acumulado_importe_da_real);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D82", $resumen_pacifico_rtd->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D83", $resumen_pacifico_rtd->promedio_ventas_da);

		//ZONA 1 CATEGORIAS CUMPLIDAS
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D85", $categorias_cumplidas);

		//####### FIN VALORES ZONA 1 ######################################################

		//####### INICIO VALORES ZONA 1 SUCURSALES ######################################################
		$sucursales = $this->EstadisticasModel->getSucursalesZonas("1");

		foreach($sucursales as $sucursal)
		{
			$data["tipo"] = "SUCURSAL";
			$data["sucursal"] = $sucursal->id;
			$datos_sucursal = $this->EstadisticasModel->getReporteSellInOut($data);

			$data["tipo_resumen"] = "DA";
			$resumen_sucursal_da = $this->EstadisticasModel->getResumenSellInOut($data);

			$data["tipo_resumen"] = "IMPULSO";
			$resumen_sucursal_impulso = $this->EstadisticasModel->getResumenSellInOut($data);

			$data["tipo_resumen"] = "RTD";
			$resumen_sucursal_rtd = $this->EstadisticasModel->getResumenSellInOut($data);

			$categorias_cumplidas = 0;
			$index = 59;
			foreach ($datos_sucursal as $item) 
			{
				if($item["categoria"] != "CHOCOLATE IMPULSO" && $item["categoria"] != "RTD")
				{
					$objetivo = $item["objetivo"];
					$venta = $item["venta"];
					$alcance = $item["alcance"];
					
					$index++;

					if($sucursal->id == "1")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$index", $venta);
						//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$index", "$alcance%");
					}
					else if($sucursal->id == "9")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$index", $venta);
						//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$index", "$alcance%");
					}
					else if($sucursal->id == "12")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P$index", $venta);
						//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q$index", "$alcance%");
					}
					else if($sucursal->id == "13")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T$index", $venta);
						//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("U$index", "$alcance%");
					}
				}

				if((float)$item["alcance"] >= 100)
				{
					$categorias_cumplidas++;
				}
			}

			if($sucursal->id == "1")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H74", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H75", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G77", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H77", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H78", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H79", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G81", $resumen_sucursal_rtd->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H81", $resumen_sucursal_rtd->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H82", $resumen_sucursal_rtd->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H83", $resumen_sucursal_rtd->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H85", $categorias_cumplidas);
			}
			else if($sucursal->id == "9")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L74", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L75", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K77", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L77", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L78", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L79", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K81", $resumen_sucursal_rtd->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L81", $resumen_sucursal_rtd->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L82", $resumen_sucursal_rtd->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L83", $resumen_sucursal_rtd->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L85", $categorias_cumplidas);
			}
			else if($sucursal->id == "12")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P74", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P75", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O77", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P77", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P78", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P79", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O81", $resumen_sucursal_rtd->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P81", $resumen_sucursal_rtd->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P82", $resumen_sucursal_rtd->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P83", $resumen_sucursal_rtd->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P85", $categorias_cumplidas);
			}
			else if($sucursal->id == "13")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T74", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T75", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S77", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T77", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T78", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T79", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S81", $resumen_sucursal_rtd->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T81", $resumen_sucursal_rtd->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T82", $resumen_sucursal_rtd->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T83", $resumen_sucursal_rtd->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T85", $categorias_cumplidas);
			}
		}
		//####### FIN VALORES ZONA 1 SUCURSALES ######################################################

		//####### INICIO VALORES ZONA 2 ######################################################
		$data["tipo"] = "PACIFICO";
		$datos_pacifico = $this->EstadisticasModel->getReporteSellInOut($data);

		$data["tipo_resumen"] = "DA";
		$resumen_pacifico_da = $this->EstadisticasModel->getResumenSellInOut($data);

		$data["tipo_resumen"] = "IMPULSO";
		$resumen_pacifico_impulso = $this->EstadisticasModel->getResumenSellInOut($data);

		$data["tipo_resumen"] = "RTD";
		$resumen_pacifico_rtd = $this->EstadisticasModel->getResumenSellInOut($data);


		$categorias_cumplidas = 0;
		$index = 102;
		foreach ($datos_pacifico as $item) 
		{
			if($item["categoria"] != "CHOCOLATE IMPULSO" && $item["categoria"] != "RTD")
			{
				$objetivo = $item["objetivo"];
				$venta = $item["venta"];
				$alcance = $item["alcance"];
				
				$index++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$index", $objetivo);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$index", $venta);
				//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$index", "$alcance%");
			}

			if((float)$item["alcance"] >= 100)
			{
				$categorias_cumplidas++;
			}
		}

		//ZONA 2 FB
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D117", $resumen_pacifico_da->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D118", $resumen_pacifico_da->promedio_ventas_da);

		//ZONA 2 IMPULSO
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C120", $resumen_pacifico_impulso->acumulado_objetivo_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D120", $resumen_pacifico_impulso->acumulado_importe_da_real);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D121", $resumen_pacifico_impulso->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D122", $resumen_pacifico_impulso->promedio_ventas_da);

		//ZONA 2 RTD
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C124", $resumen_pacifico_rtd->acumulado_objetivo_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D124", $resumen_pacifico_rtd->acumulado_importe_da_real);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D125", $resumen_pacifico_rtd->dropsize_da);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D126", $resumen_pacifico_rtd->promedio_ventas_da);

		//ZONA 2 CATEGORIAS CUMPLIDAS
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D128", $categorias_cumplidas);

		//####### FIN VALORES ZONA 1 ######################################################

		//####### INICIO VALORES ZONA 2 SUCURSALES ######################################################
		$sucursales = $this->EstadisticasModel->getSucursalesZonas("2");

		foreach($sucursales as $sucursal)
		{
			$data["tipo"] = "SUCURSAL";
			$data["sucursal"] = $sucursal->id;
			$datos_sucursal = $this->EstadisticasModel->getReporteSellInOut($data);

			$data["tipo_resumen"] = "DA";
			$resumen_sucursal_da = $this->EstadisticasModel->getResumenSellInOut($data);

			$data["tipo_resumen"] = "IMPULSO";
			$resumen_sucursal_impulso = $this->EstadisticasModel->getResumenSellInOut($data);

			$data["tipo_resumen"] = "RTD";
			$resumen_sucursal_rtd = $this->EstadisticasModel->getResumenSellInOut($data);

			$categorias_cumplidas = 0;
			$index = 102;
			foreach ($datos_sucursal as $item) 
			{
				if($item["categoria"] != "CHOCOLATE IMPULSO" && $item["categoria"] != "RTD")
				{
					$objetivo = $item["objetivo"];
					$venta = $item["venta"];
					$alcance = $item["alcance"];
					
					$index++;

					if($sucursal->id == "10")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$index", $venta);
						//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$index", "$alcance%");
					}
					else if($sucursal->id == "11")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$index", $venta);
						//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$index", "$alcance%");
					}
					else if($sucursal->id == "14")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P$index", $venta);
						//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q$index", "$alcance%");
					}
					else if($sucursal->id == "15")
					{
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S$index", $objetivo);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T$index", $venta);
						//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("U$index", "$alcance%");
					}
				}

				if((float)$item["alcance"] >= 100)
				{
					$categorias_cumplidas++;
				}
			}

			if($sucursal->id == "10")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H117", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H118", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G120", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H120", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H121", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H122", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G124", $resumen_sucursal_rtd->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H124", $resumen_sucursal_rtd->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H125", $resumen_sucursal_rtd->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H126", $resumen_sucursal_rtd->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H128", $categorias_cumplidas);
			}
			else if($sucursal->id == "11")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L117", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L118", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K120", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L120", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L121", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L122", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K124", $resumen_sucursal_rtd->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L124", $resumen_sucursal_rtd->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L125", $resumen_sucursal_rtd->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L126", $resumen_sucursal_rtd->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L128", $categorias_cumplidas);
			}
			else if($sucursal->id == "14")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P117", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P118", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O120", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P120", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P121", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P122", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O124", $resumen_sucursal_rtd->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P124", $resumen_sucursal_rtd->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P125", $resumen_sucursal_rtd->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P126", $resumen_sucursal_rtd->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P128", $categorias_cumplidas);
			}
			else if($sucursal->id == "15")
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T117", $resumen_sucursal_da->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T118", $resumen_sucursal_da->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S120", $resumen_sucursal_impulso->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T120", $resumen_sucursal_impulso->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T121", $resumen_sucursal_impulso->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T122", $resumen_sucursal_impulso->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S124", $resumen_sucursal_rtd->acumulado_objetivo_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T124", $resumen_sucursal_rtd->acumulado_importe_da_real);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T125", $resumen_sucursal_rtd->dropsize_da);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T126", $resumen_sucursal_rtd->promedio_ventas_da);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T128", $categorias_cumplidas);
			}
		}
		//####### FIN VALORES ZONA 1 SUCURSALES ######################################################

		// Write the file
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
		$objWriter->save("excel/$fileName");

		$objReader = PHPExcel_IOFactory::createReader($fileType);
		$objPHPExcel = $objReader->load("excel/$fileName");

		foreach(range('A','ZZ') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		//GLOBAL AGREGAR FORMATOS
		$this->formatearCeldas($objPHPExcel, "E", 11, 138);
		$this->formatearCeldas($objPHPExcel, "I", 11, 138);
		$this->formatearCeldas($objPHPExcel, "M", 11, 138);
		$this->formatearCeldas($objPHPExcel, "Q", 11, 138);
		$this->formatearCeldas($objPHPExcel, "U", 11, 138);
		$this->formatearCeldas($objPHPExcel, "Y", 11, 138);

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
		$objWriter->save("excel/$fileName");		

		/*header('Content-Type: application/ms-excel');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');*/

		$objWriter->save('php://output');

		//$this->SendEmail("excel/$fileName", $pPeriodo, $ultima_fecha, $empresa);
	}

	public function SendEmail($pFile, $pPeriodo, $pFecha, $pEmpresa)
	{
		setlocale(LC_TIME,"es_MX");

		$actualizacion = date("d").' de '.date("M").' del '.date("Y");
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'mail.lizer.com.mx',
			'smtp_port' => 465,
			'smtp_user' => 'gustavo.gomez@lizer.com.mx', // change it to yours
			'smtp_pass' => 'gustavolizer10', // change it to yours
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);
		$this->load->library('email');

		/*$correos = array(
			"ernesto.acuna@lizer.com.mx",
			"cesar.cuevas@lizer.com.mx",
			"isidro.lizarraga@lizer.com.mx",
			"jonathan.reyes@lizer.com.mx",
			"luis.urias@lizer.com.mx",
			"alejandro.solano@lizer.com.mx",
			"hugo.arellano@lizer.com.mx",
			"victor.salas@lizer.com.mx",
			"jose.roldan@lizer.com.mx",
			"jose.martinez@lizer.com.mx"
		);*/

		$usuarios = $this->HomeModel->getAllUsuariosSendEmail($pEmpresa);

		foreach($usuarios as $item) 
		{
			$correos[] = $item->correo;
	  	}

		$this->email->from('sistemas@lizer.com.mx', 'Inroute');
		//$this->email->to("gustavo.gomez@lizer.com.mx");
		$this->email->to("eric.lizarraga@lizer.com.mx");
		$this->email->cc($correos);
		$this->email->bcc("gustavo.gomez@lizer.com.mx");

		$this->email->subject("Inroute - Proyección Sell Out & Sell In ($pFecha)");
		$this->email->message(
			"Proyección Sell Out & Sell In Enviado desde Inroute\nPeriodo: $pPeriodo\nHasta el dia: $pFecha\nFecha Envio: $actualizacion"
		);
		$this->email->attach($pFile);

		if($this->email->send())
		{
			echo '1';
		}
		else
		{
			echo '0';
		}
	}

	function cellColor($objPHPExcel, $cells, $color)
	{
		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' => array(
				 'rgb' => $color
			)
		));
	}
	function fontColor($objPHPExcel, $cells, $color)
	{
		$objPHPExcel->getActiveSheet()
		->getStyle($cells)
		->getFont()
		->getColor()
		->setRGB ($color)  ;
	}
	function cellColor2($objPHPExcel, $column, $row, $color)
	{
		$objPHPExcel->getActiveSheet()
		->getCellByColumnAndRow($column, $row)
		->getStyle()
		->getFill()->applyFromArray(array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' => array(
				 'rgb' => $color
			)
		));
	}
	function fontColor2($objPHPExcel, $column, $row, $color)
	{
		$objPHPExcel->getActiveSheet()
		->getCellByColumnAndRow($column, $row)
		->getStyle()
		->getFont()
		->getColor()
		->setRGB ($color)  ;
	}
	function border($objPHPExcel, $cells)
	{
		$objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray(
			array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000')
					)
				)
			)
		);
	}
	function border2($objPHPExcel, $column, $row)
	{
		$objPHPExcel->getActiveSheet()
		->getCellByColumnAndRow($column, $row)
		->getStyle()->applyFromArray(
			array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000')
					)
				)
			)
		);
	}
	function parametrosColorCell($objPHPExcel, $cells, $valor)
	{
		if($valor >= 100){
			$this->cellColor($objPHPExcel, $cells, 'a9d08e');
		}else if($valor < 100){
			$this->fontColor($objPHPExcel, $cells, 'E71818');
		}
	}
	function parametrosColorCell2($objPHPExcel, $column, $row, $valor)
	{
		if($valor >= 100){
			$this->cellColor2($objPHPExcel, $column, $row, 'a9d08e');
		}else if($valor < 100){
			$this->fontColor2($objPHPExcel, $column, $row, 'E71818');
		}
	}
	function alinearCelda($objPHPExcel, $cells, $tipo)
	{
		if($tipo == "CENTER"){
			$objPHPExcel->getActiveSheet()->getStyle($cells)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}else if($tipo == "RIGHT"){
			$objPHPExcel->getActiveSheet()->getStyle($cells)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		}
	}
	function alinearCelda2($objPHPExcel, $column, $row, $tipo)
	{
		if($tipo == "CENTER"){
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($column, $row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}else if($tipo == "RIGHT"){
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($column, $row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		}
	}
	function parametrosColorCell3($objPHPExcel, $cells)
	{
		if(!is_numeric($objPHPExcel->getActiveSheet()->getCell($cells)->getCalculatedValue()))
		{
			return;
		}

		$valor = $objPHPExcel->getActiveSheet()->getCell($cells)->getCalculatedValue() * 100;
		$valor = floor($valor);

		if($valor >= 100){
			$this->cellColor($objPHPExcel, $cells, 'a9d08e');
		}else if($valor < 100){
			$this->fontColor($objPHPExcel, $cells, 'E71818');
			$this->cellColor($objPHPExcel, $cells, 'ffffff');
		}

		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFont()->setUnderline(false);
	}
}