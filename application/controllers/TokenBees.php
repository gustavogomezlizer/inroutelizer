<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TokenBees extends CI_Controller {

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

		//echo "<pre>";print_r($this->uri->segments[3]);echo "</pre>";die();

    	//$this->load->helper(array('url','form'));

		//$this->load->model(array('BeesModel'));
    }

	public function getEmpresa($pEmpresa)
	{
		$empresa = GETEMPRESA();
		
		if($empresa == "")
		{
			$arraydata = array(
				'empresa'=>$pEmpresa
			);
			
	        $this->session->set_userdata($arraydata);
		}

		$this->load->model(array('BeesModel'));
	}

	public function Apis($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$datos["info"] = $this->BeesModel->GetInfoCatalogosPendientes(GETEMPRESA());
		$this->load->view('Bees/vApiBees', $datos);
	}

	public function postAccount()
	{
		$this->BeesModel->postAccount("105609", "01220601");
	}

	public function postMasivoAccount($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->postMasivoAccount();
	}

	public function postMasivoItem($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->postMasivoItem();
	}

	public function postAssortment($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->postAssortment();
	}

	public function postInventory($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->postInventory();
	}

	public function postPrice($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->postPrice();
	}

	public function postComboAccount($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->postComboAccount();
	}

	public function deleteComboAccount($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->deleteComboAccount();
	}

	public function postOrder($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->postOrder();
	}

	public function patchOrder($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->patchOrder("", "4469");
	}

	public function postUcc($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->postUcc();
		$this->BeesModel->postAssortment();
		$this->BeesModel->postPrice();
	}

	public function postVisits($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		
		$this->BeesModel->postVisits();
	}

	public function postInvoice($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->postInvoice();
	}

	public function getBeesOrder($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->getBeesOrder();
	}

	public function syncBees($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->syncBees();
	}

	public function denied_pedido_manual($pEmpresa = "")
	{
		$this->getEmpresa($pEmpresa);
		$this->BeesModel->denied_pedido_manual();
	}

	public function postLogin()
	{
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			http_response_code(200);
			exit();
		}

		$jsonData = '{
		"success": true,
		"message": "Login correcto",
		"token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9",
		"user": {
			"id": 1,
			"name": "Administrador",
			"username": "admin",
			"email": "admin@empresa.com",
			"role": "admin"
		},
		"permissions": [
			"dashboard.view",
			"users.view",
			"users.create",
			"users.edit",
			"users.delete"
		],
		"menu": [
			{
			"title": "Dashboard",
			"icon": "dashboard",
			"route": "/dashboard",
			"permission": "dashboard.view"
			},
			{
			"title": "Usuarios",
			"icon": "people",
			"route": "/users",
			"permission": "users.view",
			"children": [
				{
				"title": "Listado",
				"route": "/users",
				"permission": "users.view"
				}
			]
			}
		]
		}';
		echo json_encode(json_decode($jsonData, true));
		//$this->getEmpresa($pEmpresa);
		//$this->BeesModel->postMasivoAccount();
	}

	public function apiListaUsuarios()
	{
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			http_response_code(200);
			exit();
		}
		
		$jsonData = '[
			{
			"id": 1,
			"name": "Juan Pérez",
			"email": "juan@mail.com",
			"role": "Admin",
			"active": 1
			},
			{
			"id": 2,
			"name": "Ana López",
			"email": "ana@mail.com",
			"role": "User",
			"active": 0
			},
			{
			"id": 3,
			"name": "Pedro Pancho Lopez",
			"email": "pedro@mail.com",
			"role": "User",
			"active": 0
			}
		]';

		echo json_encode(json_decode($jsonData, true));
	}

	public function CargarVisitasView()
	{
		$this->load->view('Bees/vBeesCargarVisitas');
	}

	public function LeerVisitasJson()
	{
		header('Content-Type: application/json');

		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		if ($this->input->method() === 'options') {
			http_response_code(200);
			exit();
		}

		$this->getEmpresa('20240617');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		// Verificar CSRF manualmente si viene en el body JSON
		/*$csrf_name  = $this->security->get_csrf_token_name();
		if (isset($data[$csrf_name])) {
			$csrf_hash = $data[$csrf_name];
			unset($data[$csrf_name]);
			if ($csrf_hash !== $this->security->get_csrf_hash()) {
				echo json_encode(['success' => false, 'message' => 'CSRF inválido.']);
				return;
			}
		}
		*/

		if (!$data || !isset($data['data'])) {
			echo json_encode(['success' => false, 'message' => 'No se recibieron datos.']);
			return;
		}

		$excelData = $data['data'];
		$filename  = $data['filename'] ?? 'desconocido.xlsx';

		$clientes = $this->BeesModel->GetAllClientes();
		$usuarios = $this->BeesModel->GetAllUsuarios();
		$rutas = $this->BeesModel->GetAllRutas();

		$datosprocedados = 0;

		foreach ($excelData as $row) 
		{
			if($row['Visit status'] !== 'Closed') {
				continue; // Saltar registros que no estén completados
			}

			$ruta = substr($row['BDR'], strpos($row['BDR'], "-") + 1);
			$codigocliente = $row['POC'];

			$infocliente = array_filter($clientes, fn($c) => $c->codigo == $codigocliente);
			$infocliente = reset($infocliente) ?: null;

			$idcliente = $infocliente ? $infocliente->id : 0;
			$nombrecliente = $infocliente ? $infocliente->nombre : 'Cliente no encontrado';
			$diasvisita = $infocliente ? $infocliente->diasvisita : 'No definido';

			$inforuta = array_filter($rutas, fn($r) => $r->ruta == $ruta);
			$inforuta = reset($inforuta) ?: null;

			$idusuario = $inforuta ? $inforuta->chofer : 0;
			$idruta = $inforuta ? $inforuta->id : 0;
			$idsucursal = $inforuta ? $inforuta->sucursal : 0;

			$infousuario = array_filter($usuarios, fn($u) => $u->id == $idusuario);
			$infousuario = reset($infousuario) ?: null;

			parse_str(parse_url($row['Start location'], PHP_URL_QUERY), $qinicio);
			[$latitudinicio, $longitudinicio] = array_map('floatval', explode(',', $qinicio['q']));

			parse_str(parse_url($row['Finish location'], PHP_URL_QUERY), $qfin);
			[$latitudfin, $longitudfin] = array_map('floatval', explode(',', $qfin['q']));

			$usuario = $infousuario ? $infousuario->usuario : 'Usuario no encontrado';
			
			$fecha = date('Y-m-d');
			$horainicio = date('H:i:s', strtotime($row['Visit start time']));
			$horafin = date('H:i:s', strtotime($row['Visit end time']));
			$fechacreacion = date('Y-m-d H:i:s');
			$fecharegistro = date('Y-m-d H:i:s');

			$infopedido = $this->BeesModel->getPedidoByVisita($fecha, $idcliente, $idruta);
			$resultado = $infopedido ? 'Venta registrada' : 'Visitado sin venta';

			$visita = array(
				'idcliente' => $idcliente,
				'codigocliente' => $codigocliente,
				'cliente' => $nombrecliente,
				'idusuario' => $idusuario,
				'usuario' => $usuario,
				'resultado' => $resultado,
				'fecha' => $fecha,
				'inicio' => $horainicio,
				'fin' => $horafin,
				'latitud' => $latitudinicio,
				'longitud' => $longitudinicio,
				'distancia' => 0,
				'latitudfin' => $latitudfin,
				'longitudfin' => $longitudfin,
				'ruta' => $idruta,
				'idsucursal' => $idsucursal,
				'comentarios' => '',
				'fechacreacion' => $fechacreacion,
				'fecharegistro' => $fecharegistro,
				'diasvisitas' => $diasvisita,
				'origen' => 'BEES',
			);

			$this->BeesModel->guardarVisitaBees($visita);

			$datosprocedados++;
		}

		echo json_encode([
			'success' => true,
			'message' => 'Datos recibidos correctamente. ' . $datosprocedados . ' registros procesados.',
			'total'   => $datosprocedados
		]);
	}
}