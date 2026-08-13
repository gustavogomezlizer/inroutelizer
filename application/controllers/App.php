<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

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

	public function index()
	{
		$this->load->view('welcome_message');
	}

	function is_multi_array( $arr ) 
	{
	    rsort( $arr );
	    return isset( $arr[0] ) && is_array( $arr[0] );
	}

	public function getusuario($user,$pass,$celular,$company)
	{
		$user = $this->AppModel->getusuario2($user,$pass,$company);
		if(count($user)>0){		

			if($user[0]->celular=="0" || $user[0]->celular==$celular){
				if($user[0]->ruta=="0"){
					echo "El usuario no tiene asignado una ruta";
				}else{

					$this->AppModel->UpdateCelular($user[0]->id,$celular);

					$user[0]->celular = $celular;

					$datos = array(
						"user" => $user,
					);

					echo json_encode($datos, JSON_UNESCAPED_UNICODE);
				}
			}else{
				echo "El usuario esta registrado en el celular: (IMEI:".$user[0]->celular.") con el usuario de (".$user[0]->nombre.") TU IMEI ES: (".$celular.")";
			}
		}
		else{
			echo "El usuario ingresado no se encontró en la base de datos, Favor de revisarlo";
		}
	}

	public function getusuarioreparto($user,$pass,$celular,$company)
	{
		$user = $this->AppModel->getusuarioreparto($user,$pass,$company);
		
		if(count($user)>0)
		{
			if($user[0]->celular=="0" || $user[0]->celular==$celular)
			{
				if($this->AppModel->GetRutasAsignadas($company, $user[0]->id) == "")
				{
					echo "No tienes asignado ninguna ruta para reparto";
					return;
				}

				$this->AppModel->UpdateCelular($user[0]->id,$celular);

				$user[0]->celular = $celular;

				$datos = array(
					"user" => $user,
				);

				echo json_encode($datos, JSON_UNESCAPED_UNICODE);
			}
			else
			{
				echo "El usuario esta registrado en el celular: (IMEI:".$user[0]->celular.") con el usuario de (".$user[0]->nombre.") TU IMEI ES: (".$celular.")";
			}
		}
		else
		{
			echo "El usuario ingresado no se encontró en la base de datos, Favor de revisarlo";
		}
	}

	public function FreeCellphone($usuario){
		echo $this->AppModel->FreeCellphone($usuario);
	}

	public function GetInfoCatalogos($usuario, $empresa)
	{
		//$usuario = 2;
		/*$rutas = $this->AppModel->GetRutas($usuario);
		$idsrutas = "";
		foreach($rutas as $item){		
			$idsrutas.= $item->id.",";			
		}
		$idsrutas = rtrim($idsrutas, ',');*/

		$rutas = $this->AppModel->GetRuta($usuario, $empresa);

		$idsrutas = $rutas->id;
		$idsucursal = $rutas->sucursal;

		$info_sucursal = $this->AppModel->GetSucursal($idsucursal, $empresa);

		$zonas = $this->AppModel->GetZonas($idsrutas, $empresa);

		$idszonas = "0,";
		foreach($zonas as $item){		
			$idszonas.= $item->id.",";			
		}
		$idszonas = rtrim($idszonas, ',');		

		$proveedores = $this->AppModel->GetProveedores($idsrutas, $empresa);

		$idsproveedores = "0,";
		foreach($proveedores as $item){		
			$idsproveedores.= $item->id.",";			
		}
		$idsproveedores = rtrim($idsproveedores, ',');
		$productos = $this->AppModel->GetProductos($idsproveedores, $idsucursal, $empresa);
		$componentes_paquete = $this->AppModel->GetComponentesPaquete($empresa);
		$productos_ultimos = $this->AppModel->GetProductosUltimos($empresa, $idsrutas);

		$clientes = $this->AppModel->GetClientes($idszonas, $idsproveedores, $empresa);

		$clasificacion_productos = $this->AppModel->GetClasificacionProductos($empresa);

		$clasificacion_clientes = $this->AppModel->GetClasificacionClientes($empresa);
		array_push($clasificacion_clientes, ['id' => "0", 'clasificacion' => "[NA]", 'idusuariocrea' => "0", 'idusuarioactualiza' => "0", 'fechacreacion' => "00000-00-00 00:00:00", 'ultima_actualizacion' => "00000-00-00 00:00:00", 'status' => "1"]);		

		$idusuario = $rutas->chofer;///cambio importante para agarrar el id de usuario
		$pedidos = $this->AppModel->GetOrdersToday($idusuario, $empresa);
		$visitas = $this->AppModel->GetVisitsToday($idusuario, $empresa);
		$printer = $this->AppModel->GetPrinter($idusuario, $empresa);
		$promociones = $this->AppModel->GetPromociones($idsucursal, $idusuario, $empresa);

		$datos = array(
			"zonas" => $zonas,
			"rutas" => $rutas,
			"sucursal" => $info_sucursal,
			"clasificacion_productos" => $clasificacion_productos,
			"clasificacion_clientes" => $clasificacion_clientes,
			"clientes" => $clientes,
			"proveedores" => $proveedores,
			"productos" => $productos,
			"pedidos" => $pedidos,
			"visitas" => $visitas,
			"printer" => $printer,
			"promociones" => $promociones,
			"componentes_paquete" => $componentes_paquete,
			"productos_ultimos" => $productos_ultimos,
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function GetCatalogoProductos($idsucursal, $empresa)
	{
		$productos = $this->AppModel->GetProductos('0', $idsucursal, $empresa);

		$datos = array(
			"productos" => $productos
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function GetPaquetes($idsucursal, $empresa)
	{
		$productos = $this->AppModel->GetProductos('0', $idsucursal, $empresa);

		$datos = array(
			"productos" => $productos
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function GetClientesPaquetes($idruta, $empresa)
	{
		$items = $this->AppModel->GetClientesPaquetes($idruta, $empresa);

		$datos = array(
			"clientes_paquetes" => $items
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function GetTimeline($date,$user,$empresa){
		$datos = array(
			"info" => $this->AppModel->GetTimeline($date,$user,$empresa),
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function GetOrdersToday(){
		$datos = array(
			"pedidos" => $this->AppModel->GetOrdersToday(),
			"visitas" => $this->AppModel->GetVisitsToday(),
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function GetOrdersById($idorder,$empresa){
		$datos = array(
			"info" => $this->AppModel->GetOrdersById($idorder,$empresa),			
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function InsertClient()
	{	
		$post = $this->input->post();
		$cliente = $post["cliente"];
		$usuario = $post["usuario"];
		$proveedor = (isset($post["proveedor"])) ? $post["proveedor"] : "0";
		$empresa = $post["empresa"];

		if($cliente!="0")
		{	
			$datos = array(
            	"info" => [$this->AppModel->InsertClient($cliente,$usuario,$proveedor,$empresa)],
        	);

			/*$idcliente = $datos["info"][0]["idservidor"];
			$this->BeesModel->postAccount($idcliente, $empresa);*/

        	echo json_encode($datos, JSON_UNESCAPED_UNICODE);
		}		
	}

	public function InsertVisits(){

		$res = "no";
		
		$post = $this->input->post();
		$visitas = $post["visitas"];
		$empresa = $post["empresa"];

		//print_r($visitas);die();

		//if($visitas!="[]"){
		if($visitas!="0"){
			
			/*$this->AppModel->InsertVisits($visitas);
			$res = "si";*/
			$datos = array(
            	"info" => [$this->AppModel->InsertVisits($visitas, $empresa)],
        	);

        	echo json_encode($datos, JSON_UNESCAPED_UNICODE);
		}

		//echo $res;
	}

	public function InsertOrders()
	{	
		$post = $this->input->post();
		$pedido = $post["pedido"];
		$pedido_detalle = $post["pedido_detalle"];
		$empresa = $post["empresa"];

		if($pedido!="0"){
			$datos = array(
            	"info" => [$this->AppModel->InsertOrders($pedido,$pedido_detalle,$empresa)],
        	);

        	echo json_encode($datos, JSON_UNESCAPED_UNICODE);
		}		
	}

	public function UpdateOrderCambioFacturar()
	{	
		$post = $this->input->post();

		echo $this->AppModel->UpdateOrderCambioFacturar($post);
	}

	public	function InsertPrint()
	{
		$post = $this->input->post();
		$print = $post["print"];
		$idusuario = $post["idusuario"];
		$usuario = $post["usuario"];
		$empresa = $post["empresa"];

		echo $this->AppModel->InsertPrint($print,$idusuario,$usuario,$empresa);
	}

	public function GetReporteMensualCategorias($month,$iduser,$empresa)
	{
		if( strlen($month)==1 )
		{
			$month = '0'.$month;
		}

		$datos = array(
			"info" => $this->AppModel->GetReporteMensualCategorias($month,$iduser,$empresa),
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function GetPedidosEntregaUsuario($empresa = null, $idusuario = 0, $fecha = "")
	{
		$datos = $this->input->post();		

		if(is_null($empresa))
		{
			$empresa = $datos["empresa"];			

			$pedidos = $this->AppModel->GetPedidosEntregaUsuario($empresa, $datos);
			$datos["pedidos"] = count($pedidos);

			$id_reparto_descarga = $this->AppModel->InsertDescargaPedidos($datos, $empresa);

			$ids_pedidos = "";

			foreach($pedidos as $item)
			{
				$ids_pedidos.= $item->id.',';
			}

			if($ids_pedidos != "")
			{
				$ids_pedidos = rtrim($ids_pedidos, ",");

				$datos_actualizar = array(
					"empresa" => $empresa,
					"id_reparto_descarga" => $id_reparto_descarga,
					"ids_pedidos" => $ids_pedidos
				);

				$this->AppModel->UploadPedidosIdDescargaReparto($datos_actualizar);
			}
		}
		else
		{
			$datos["idusuario"] = $idusuario;
			$datos["fecha_descarga"] = $fecha;
			$pedidos = $this->AppModel->GetPedidosEntregaUsuario($empresa, $datos);
		}

		$datos = array(
			"cat_estatus_reparto" => $this->AppModel->GetEstatusReparto($empresa),
			"pedidos" => $pedidos,
			"pedidos_detalle" => $this->AppModel->GetPedidosDetalleEntregaUsuario($empresa, $datos),
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function getProyeccionNominaCertificado($periodo, $sucursal, $ruta, $empresa)
	{
		$data = array(
			"empresa" => $empresa,
			"periodo" => $periodo,
			"sucursal" => $sucursal,
			"ruta" => $ruta
		);

		$arraydata = array(
			'movil' => "1",
			'empresa' => $empresa
		);
		
		$this->session->set_userdata($arraydata);

		$this->load->model('EstadisticasModel', 'blog');

		echo json_encode($this->blog->getProyeccionNominaCertificado($data));
	}

	public function UploadEntrega()
	{	
		$post = $this->input->post();

		$datos = array(
			"info" => [$this->AppModel->UploadEntrega($post)],
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);	
	}

	public function UploadRechazos()
	{	
		$post = $this->input->post();

		$pedido_detalle = $post["pedido_detalle"];
		$empresa = $post["empresa"];
		$idusuario = $post["idusuario"];

		$datos = array(
			"info" => [$this->AppModel->UploadRechazos($empresa, $idusuario, $pedido_detalle)],
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}

	public function UploadDepositoReparto()
	{	
		$post = $this->input->post();

		$datos = array(
			"info" => [$this->AppModel->UploadDepositoReparto($post)],
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);	
	}

	public function GetInventarioReal($empresa, $idsucursal)
	{
		$datos = array(
			"inventario" => $this->AppModel->GetInventarioReal($empresa, $idsucursal),
		);

		echo json_encode($datos, JSON_UNESCAPED_UNICODE);
	}
}