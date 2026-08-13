<?php
class BeesModel extends CI_Model {

	private $dbinfo;
	private $isprod = true;

	//PRUEBA
	/*var $url_api_token = "https://eur-sdr-ext-pub.nestle.com/api/dv-exp-oauth-api/1/";
	var $url_api_entidades = "https://eur-sdr-ext-pub.nestle.com/api/dv-exp-bees-mx-sls-rtl-api/1/";
	var $client_id = "8e33ab651cb2489a86211fd9da52cb59";
	var $client_secret = "34a5aBCc17BC464C9D6E1486f6B1bCD8";
	var $client_name = "BEESMexicoLizerNonProd";

	var $correlation_id = "0a2ccc5f-1317-4076-9745-7918fdf7a8e8";
	var $trace_id = "LizerERP0001";
	var $source = "LizerERP";*/

	var $url_api_token = "";
	var $url_api_entidades = "";
	var $client_id = "";
	var $client_secret = "";
	var $client_name = "";
	var $correlation_id = "";
	var $trace_id = "";
	var $source = "";

	var $headers;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		$empresa = GETEMPRESA();
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		if($empresa == "02271106")
		{
			return 1;
		}

		$infoempresa = $this->db->query("SELECT * FROM empresas WHERE idCliente = '$empresa'")->row();

		$jsoninfoempresa = json_decode($infoempresa->datosbees);

		$this->url_api_token = $jsoninfoempresa->url_api_token;
		$this->url_api_entidades = $jsoninfoempresa->url_api_entidades;
		$this->client_id = $jsoninfoempresa->client_id;
		$this->client_secret = $jsoninfoempresa->client_secret;
		$this->client_name = $jsoninfoempresa->client_name;
		$this->correlation_id = $jsoninfoempresa->correlation_id;
		$this->trace_id = $jsoninfoempresa->trace_id;
		$this->source = $jsoninfoempresa->source;

		/*if($this->isprod)
		{
			//PRODUCCION
			$this->url_api_token = "https://eur-ext-pub.nestle.com/api/exp-oauth-api/1/";
			$this->url_api_entidades = "https://eur-ext-pub.nestle.com/api/exp-bees-mx-sls-rtl-api/1/";
			$this->client_id = "ba1d7a1e66fa4fb9b13422746ae9dd99";
			$this->client_secret = "B77b7bF04dBd4b9C9bEC68e0d7008230";
			$this->client_name = "ERP-Lizer-MX-PROD";
		}*/

		$token = $this->getTokenBees();

		$this->headers = array(
			"Authorization: $token",
			"x-correlation-id: $this->correlation_id",
			"x-trace-id: $this->trace_id",
			"x-source: $this->source",
			"Content-Type: application/json"
		);
	}

	public function api_bees_post($url, $headers, $data, $method, $return="text")
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		if($method == "POST")
		{
			curl_setopt($ch, CURLOPT_POST, true);
		}
		else if($method == "PUT")
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		}
		else if($method == "PATCH")
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
		}
		else if($method == "DELETE")
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		if($data != null)
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if($return == "text")
		{
			return $result;
		}
		else
		{
			return $httpcode;
		}
	}

	public function api_bees_get($parametros, $url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $parametros);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

	public function getTokenBees()
	{
		$url = $this->url_api_token."token";

		$headers = array(
			"client_id: $this->client_id",
			"client_secret: $this->client_secret",
			"client_name: $this->client_name",
			"Content-Type: application/json",
			"grant_type: CLIENT_CREDENTIALS"
		);

		//print_r($headers);die();

		$data = null;

		$result = json_decode($this->api_bees_post($url, $headers, $data, "POST"), true);

		//print_r($result);die();

		if(is_null($result))
		{
			return "Error al obtener el token de Bees";
		}

		if(!isset($result["token_type"]))
		{
			return "Error al obtener el token de Bees";
		}

		return $result["token_type"].' '.$result["access_token"];
	}

	public function validateToken()
	{
		$url = $this->url_api_token."validate";

		$headers = array(
			"Authorization: Bearer v1rUO4uu4Rt4seIwwyoPPSmROpT41ys0NTEmNR2ueyt2zlbEp9-552dwAvAsl3g8wlE5vN8PrChIClLstgCOtQ"
		);

		$result = json_decode($this->api_bees_get($headers, $url), true);

		print_r($result["expires_in"]);
	}

	public function postAccount($pIdCliente, $pEmpresa)
	{
		//METODO CALL ACCOUNTS

		$url = $this->url_api_entidades."accounts";

		$infocliente = $this->GetClienteById($pIdCliente, $pEmpresa);

		$infousuario = $this->GetUSuarioById($infocliente->idusuariocrea);

		$payload = array(
			array(
				"vendorAccountId" => $infocliente->codigo,
				"customerAccountId" => $infocliente->codigo,
				"taxId" => "_",
				"deliveryCenterId" => $infocliente->codigosucursal,
				"displayName" => ucwords($infocliente->nombre),
				"name" => ucwords($infocliente->nombre),
				//"isKeyAccount" => false,
				"legalName" => ucwords($infocliente->nombre),
				"segment" => $infocliente->clasificacioncliente,
				"seller" => $infousuario->usuario,
				"status" => $infocliente->status == 1 ? "ACTIVE" : "NO ACTIVE",
				"deliveryScheduleId" => $infocliente->codigo,
				//"priceListId" => "LIZER0001",
				"minimumOrder" => array(
					"value" => 150,
					"type" => "ORDER_TOTAL"
				),
				/*"maximumOrder" => array(
					"value" => 15000,
					"type" => "ORDER_TOTAL"
				),*/
				"billingAddress" => array(
					"address" => $infocliente->calle,
					"city" => $infocliente->ciudad,
					"state" => $infocliente->estado,
					"zipcode" => $infocliente->cp,
					"latitude" => $infocliente->latitud,
					"longitude" => $infocliente->longitud,
					"lines" => array(
						array(
							"type" => "NUMBER",
							"value" => 245
						)
					)
				),
				"deliveryAddress" => array(
					"address" => $infocliente->calle,
					"city" => $infocliente->ciudad,
					"state" => $infocliente->estado,
					"zipcode" => $infocliente->cp,
					"latitude" => $infocliente->latitud,
					"longitude" => $infocliente->longitud,
					"lines" => array(
						array(
							"type" => "NUMBER",
							"value" => 245
						)
					)
				),
				/*"contacts" => array(
					array(
						"type" => "PHONE",
						"value" => $infocliente->telefono
					)
				),*/
				"owner" => array(
					"email" => $infocliente->email,
					"firstName" => ucwords($infocliente->encargado),
					"lastName" => ucwords($infocliente->encargadoapellidos),
					"phone" => $infocliente->telefono
				),
				"salesRepresentative" => array(
					"email" => $infocliente->email,
					"name" => ucwords($infocliente->nombre),
					"phone" => $infocliente->telefono
				),
				"paymentMethods" => ["CASH"],
				"representatives" => array(
					array(
						"email" => $infocliente->email,
						"name" => ucwords($infocliente->nombre),
						"phone" => $infocliente->telefono,
						"primary" => true,
						"productTypes" => [],
						"role" => "SALES",
						"supervisor" => array(
							"name" => ucwords($infocliente->nombre),
							"phone" => $infocliente->telefono
						)
					)
				),
			)
		);

		//echo "<pre>";print_r($payload);echo "</pre>";die();

		$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

		//die($payload);
		
		$result = $this->api_bees_post($url, $this->headers, $payload, "POST");
	}

	public function postMasivoAccount()
	{
		//METODO CALL ACCOUNTS

		$url = $this->url_api_entidades."accounts";

		$rutas = $this->dbinfo->query("SELECT t2.*, t1.`zona`
		FROM asi_ruta_zona t1
		INNER JOIN cat_rutas t2 ON t1.`ruta` = t2.`id`")->result();

		$usuarios = $this->GetAllUsuarios();
		$clientes = $this->GetClientes();

		foreach($clientes as $infocliente)
		{
			### -> INICIO VALIDA LA INFORMACION DE LA RUTA ###
			$searchValue = $infocliente->zona;
			$searchKey = 'zona';

			$inforuta = array_filter($rutas, function ($object) use ($searchKey, $searchValue) {
				return $object->$searchKey === $searchValue;
			});

			$inforuta = reset($inforuta);

			/*if(!$inforuta)
			{
				$inforuta = null;
			}*/
			### FIN VALIDA LA INFORMACION DE LA RUTA <- ###

			$searchValue = $inforuta->agente;
			$searchKey = 'id';

			$infousuario = array_filter($usuarios, function ($object) use ($searchKey, $searchValue) {
				return $object->$searchKey === $searchValue;
			});

			$infousuario = reset($infousuario);

			$payload = array(
				array(
					"vendorAccountId" => $infocliente->codigo,
					"customerAccountId" => $infocliente->codigo,
					"taxId" => "_",
					"deliveryCenterId" => $infocliente->codigosucursal,
					"displayName" => ucwords($infocliente->nombre),
					"name" => ucwords($infocliente->nombre),
					"legalName" => ucwords($infocliente->nombre),
					"segment" => $infocliente->clasificacioncliente,
					"seller" => $infousuario->usuario,
					"status" => $infocliente->status == 1 ? "ACTIVE" : "INACTIVE",
					"deliveryScheduleId" => $infocliente->codigo,
					"minimumOrder" => array(
						"value" => 0,
						"type" => "ORDER_TOTAL"
					),
					/*"maximumOrder" => array(
						"value" => 15000,
						"type" => "ORDER_TOTAL"
					),*/
					"billingAddress" => array(
						"address" => $infocliente->calle,
						"city" => $infocliente->ciudad,
						"state" => $infocliente->estado,
						"zipcode" => $infocliente->cp,
						"latitude" => $infocliente->latitud,
						"longitude" => $infocliente->longitud,
						"lines" => array(
							array(
								"type" => "NUMBER",
								"value" => $infocliente->numero
							)
						)
					),
					"deliveryAddress" => array(
						"address" => $infocliente->calle,
						"city" => $infocliente->ciudad,
						"state" => $infocliente->estado,
						"zipcode" => $infocliente->cp,
						"latitude" => $infocliente->latitud,
						"longitude" => $infocliente->longitud,
						"lines" => array(
							array(
								"type" => "NUMBER",
								"value" => $infocliente->numero
							)
						)
					),
					"owner" => array(
						"email" => $infocliente->email2,
						"firstName" => $infocliente->encargado == "" ? ucwords($infocliente->nombre) : ucwords($infocliente->encargado),
						"lastName" => $infocliente->encargadoapellidos == "" ? ucwords($infocliente->nombre) : ucwords($infocliente->encargadoapellidos),
						"phone" => $infocliente->telefono == "" ? "66929801010" : $infocliente->telefono
					),
					"salesRepresentative" => array(
						"email" => $infousuario->correo == "" ? "ventas@lizer.com.mx" : $infousuario->correo,
						"name" => $infousuario->nombre == "" ? "Jose Perez Leon" : ucwords($infousuario->nombre),
						"phone" => $infousuario->telefono == "" ? "6699801010" : $infousuario->telefono
					),
					"paymentMethods" => ["CASH"],
					"representatives" => array(
						array(
							"email" => $infousuario->correo == "" ? "ventas@lizer.com.mx" : $infousuario->correo,
							"name" => $infousuario->nombre == "" ? "Jose Perez Leon" : ucwords($infousuario->nombre),
							"phone" => $infousuario->telefono == "" ? "6699801010" : $infousuario->telefono,
							"primary" => true,
							"productTypes" => [],
							"role" => "SALES",
							"supervisor" => array(
								"name" => $infousuario->nombre == "" ? "Jose Perez Leon" : ucwords($infousuario->nombre),
								"phone" => $infousuario->telefono == "" ? "6699801010" : $infousuario->telefono
							)
						)
					),
				)
			);

			//echo "<pre>";print_r($payload);echo "</pre>";die();

			$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

			//die($payload);
			
			$result = $this->api_bees_post($url, $this->headers, $payload, "POST", "number");

			if($result == 200)
			{
				$this->dbinfo->where(array("id" => $infocliente->id));
				$this->dbinfo->update('clientes', array("subidobees" => "1"));
			}
		}

		if(count($clientes) > 0)
		{
			//ACTUALIZA LA ASIGNACION DE RUTAS A CLIENTES
			//$this->postUcc();
		}
	}

	public function postItem($pIdProducto, $pEmpresa)
	{
		$url = $this->url_api_entidades."items";

		$infoitem = $this->GetProductoById($pIdProducto, $pEmpresa);

		$payload = array(
			array(
				"brandId" => $infoitem->idmarca,
				"brandName" => $infoitem->marca,
				"description" => $infoitem->descripcionlarga,
				"enabled" => ($infoitem->status == 0 ? false : true),
				//"image" => "http://englishversion",
				"isAlcoholic" => ($infoitem->esalcohol == 0 ? false : true),
				"name" => $infoitem->nombre,
				"sku" => $infoitem->codigo,
				"upc" => $infoitem->codigobarras,
				"salesRanking" => "1",
				"container" => array(
					"name" => $infoitem->contenedornombre,
					"returnable" => ($infoitem->contenedorretornable == 0 ? false : true),
					"size" => $infoitem->contenedortamano,
					"unitOfMeasurement" => $infoitem->contenedorunidadmedida,
				),
				"package" => array(
					"count" => $infoitem->paquetecantidad,
					"id" => $infoitem->paqueteid,
					"itemCount" => $infoitem->paquetecantidadproducto,
					"name" => $infoitem->paquetenombre
				),
				"sourceData" => array(
					"vendorItemId" => $infoitem->codigo
				)
			)
		);

		$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

		//die($payload);

		$result = $this->api_bees_post($url, $this->headers, $payload, "PUT");
	}

	public function postMasivoItem()
	{
		$url = $this->url_api_entidades."items";

		$productos = $this->GetProductos();

		foreach($productos as $infoitem)
		{
			$payload = array(
				array(
					"brandId" => $infoitem->idmarca,
					"brandName" => $infoitem->marca,
					"description" => $infoitem->descripcionlarga,
					"enabled" => ($infoitem->status == 0 ? false : true),
					"isAlcoholic" => ($infoitem->esalcohol == 0 ? false : true),
					"name" => $infoitem->nombre,
					"sku" => $infoitem->codigo,
					"upc" => $infoitem->codigobarras,
					"salesRanking" => "1",
					"container" => array(
						"name" => $infoitem->contenedornombre,
						"returnable" => ($infoitem->contenedorretornable == 0 ? false : true),
						"size" => $infoitem->contenedortamano,
						"unitOfMeasurement" => $infoitem->contenedorunidadmedida,
					),
					"package" => array(
						"count" => $infoitem->paquetecantidad,
						"id" => $infoitem->paqueteid,
						"itemCount" => $infoitem->paquetecantidadproducto,
						"name" => $infoitem->paquetenombre
					),
					"sourceData" => array(
						"vendorItemId" => $infoitem->codigo
					)
				)
			);

			$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

			//die($payload);

			$result = $this->api_bees_post($url, $this->headers, $payload, "PUT", "number");

			//print_r($result);die();

			if($result == 200)
			{
				$this->dbinfo->where(array("id" => $infoitem->id));
				$this->dbinfo->update('cat_productos', array("subidobees" => "1"));
			}
		}
	}

	public function postAssortment()
	{
		$url = $this->url_api_entidades."assortments";

		$sucursales = $this->BeesModel->GetSucursales();
		$productos = $this->BeesModel->GetPrecioProductos();

		$array_sucursales = array();
		$array_productos = array();
		
		foreach($sucursales as $item)
		{
			array_push($array_sucursales, $item->clave);
		}

		foreach($productos as $item)
		{
			$producto = array(
				"vendorItemId" => $item->codigo,
				"quantityMultiplier" => 1
			);

			array_push($array_productos, $producto);
		}

		$payload = array(
			"deliveryCenterIds" => $array_sucursales,
			"assortments" => $array_productos
		);

		$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

		//die($payload);

		$result = $this->api_bees_post($url, $this->headers, $payload, "POST");
	}

	public function postInventory()
	{
		$url = $this->url_api_entidades."inventories";

		$sucursales = $this->GetSucursales();
		
		foreach($sucursales as $item)
		{
			$payload = array(
				"deliveryCenterIds" => [$item->clave],
				"inventories" => $this->GetInventarioBySucursal($item->id)
			);

			$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

			//die($payload);

			$result = $this->api_bees_post($url, $this->headers, $payload, "POST");
		}
	}

	public function postPrice()
	{
		$url = $this->url_api_entidades."prices";

		$sucursales = $this->BeesModel->GetSucursales();
		$productos = $this->BeesModel->GetPrecioProductos();

		$array_sucursales = array();
		$array_productos = array();
		
		foreach($sucursales as $item)
		{
			array_push($array_sucursales, $item->clave);
		}

		foreach($productos as $item)
		{
			$producto = array(
				"type" => "PER_UNIT",
				"vendorItemId" => $item->codigo,
				"sku" => $item->codigo,
				"basePrice" => $item->precio,
				"measureUnit" => ($item->contenedorunidadmedida == 0 ? "PZ": $item->contenedorunidadmedida),
				"minimumPrice" => $item->precio,
				"suggestedRetailPrice" => $item->precio,
			);

			array_push($array_productos, $producto);
		}

		$payload = array(
			"type" => "DELIVERY_CENTER",
			"ids" => $array_sucursales,
			"prices" => $array_productos
		);

		$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

		//die($payload);

		$result = $this->api_bees_post($url, $this->headers, $payload, "PUT");
	}

	public function postComboAccount()
	{
		$url = $this->url_api_entidades."combos-accounts";
		
		$paquetes = $this->BeesModel->GetPaquetes();

		foreach($paquetes as $item)
		{
			$array_combos = array();
			$array_componentes = array();
			$array_freegoods = array();

			$componentespaquete = $this->GetPaquetesComponente($item->id);

			if($item->tipocombo == "D")
			{
				foreach($componentespaquete as $cp)
				{
					$c = array(
						"vendorItemId" => $cp->codigo,
						"vendorComboItemId" => $item->codigo,
						"quantity" => $cp->cantidad
					);

					array_push($array_componentes, $c);
				}
			}
			else if($item->tipocombo == "FG")
			{
				foreach($componentespaquete as $cp)
				{
					if($cp->tipo == "P")
					{
						$c = array(
							"vendorItemId" => $cp->codigo,
							"vendorComboItemId" => $item->codigo,
							"quantity" => $cp->cantidad
						);

						array_push($array_componentes, $c);
					}
					else if($cp->tipo == "G")
					{
						$c = array(
							"items" => array(
								array(
									"vendorItemId" => $cp->codigo,
									"vendorComboItemId" => $item->codigo
								)
							),
							"quantity" => $cp->cantidad
						);

						array_push($array_freegoods, $c);
					}
				}
			}			

			$startdate = str_replace("MDT", "T", date("Y-m-dTH:i:s", strtotime($item->fechainicio)))."Z";
			$enddate = str_replace("MDT", "T", date("Y-m-dTH:i:s", strtotime($item->fechafinal)))."Z";

			$combo = array(
				"vendorComboId" => $item->codigo,
				"upc" => $item->codigo,
				"title" => $item->nombre,
				"type" => $item->tipocombo,
				"description" => $item->descripcionlarga,
				"startDate" => $startdate,
				"endDate" => $enddate,
				"price" => $item->precio,
				"items" => $array_componentes,
				"freeGoods" => $array_freegoods,
				"limit" => array(
					"daily" => $item->limitemensual,
					"monthly" => $item->limitemensual
				)
			);

			if($item->tipocombo == "D")
			{
				unset($combo["freeGoods"]);
			}

			array_push($array_combos, $combo);

			if($item->status == 1)
			{
				$sucursales = $this->dbinfo->query("SELECT * FROM paquetes_sucursal q WHERE q.idpaquete = '$item->id' ORDER BY q.`activo` DESC")->result();

				foreach($sucursales as $sucursal)
				{
					$METODO = "POST";
					$array_clientes = array();

					if($item->audiencia == "1")
					{
						$clientes = $this->dbinfo->query("SELECT * FROM clientes c WHERE c.sucursal = '$sucursal->idsucursal' AND FIND_IN_SET(c.`codigo`, REPLACE('$item->audiencia_clientes','|', ',')) ORDER BY c.`sucursal`, c.`nombre`;")->result();
					}
					else
					{
						$clientes = $this->BeesModel->GetClientesBySucursal($sucursal->idsucursal);
					}

					foreach($clientes as $cliente)
					{
						array_push($array_clientes, $cliente->codigo);
					}

					if($sucursal->activo == 1)
					{
						$payload = array(
							"vendorAccountIds" => $array_clientes,
							"combos" => $array_combos
						);
					}
					else if($sucursal->activo == 0)
					{
						$METODO = "DELETE";

						$payload = array(
							"vendorAccountIds" => $array_clientes,
							"vendorComboIds" => array($item->codigo)
						);
					}

					$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

					//die($payload);

					$result = $this->api_bees_post($url, $this->headers, $payload, $METODO, "number");
				}
			}
			else if($item->status == 0)
			{
				$array_clientes = array();

				if($item->audiencia == "1")
				{
					$clientes = $this->dbinfo->query("SELECT * FROM clientes c WHERE FIND_IN_SET(c.`codigo`, REPLACE('$item->audiencia_clientes','|', ',')) ORDER BY c.`sucursal`, c.`nombre`;")->result();
				}
				else
				{
					$idssucursales = $this->dbinfo->query("SELECT GROUP_CONCAT(q.`idsucursal`) AS sucursales 
					FROM paquetes_sucursal q WHERE q.idpaquete = '$item->id'")->row();

					$clientes = $this->BeesModel->GetClientesBySucursal($idssucursales->sucursales);
				}
				
				foreach($clientes as $cliente)
				{
					array_push($array_clientes, $cliente->codigo);
				}

				$payload = array(
					"vendorAccountIds" => $array_clientes,
					"vendorComboIds" => array($item->codigo)
				);

				$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

				//die($payload);

				$result = $this->api_bees_post($url, $this->headers, $payload, "DELETE", "number");
			}

			//print_r($result);die();

			if($result == 200)
			{
				$this->dbinfo->where(array("id" => $item->id));
				$this->dbinfo->update('cat_productos', array("subidobees" => "1"));
			}
		}
	}

	public function deleteComboAccount()
	{
		$url = $this->url_api_entidades."combos-accounts";

		$paquetes = $this->dbinfo->query("SELECT *,
		DATE_ADD(fechacreacion, INTERVAL 1 YEAR) AS fechavencimiento
		FROM cat_productos cp 
		WHERE cp.status = 0 AND cp.tipo2 = 'PAQUETE' AND cp.subidobees = 0")->result();

		foreach($paquetes as $item)
		{
			$array_combos = array();
			$array_clientes = array();

			$clientes = $this->BeesModel->GetClientesBySucursal($item->sucursales);

			foreach($clientes as $cliente)
			{
				array_push($array_clientes, $cliente->codigo);
			}

			array_push($array_combos, $item->codigo);

			$payload = array(
				"vendorAccountIds" => $array_clientes,
				"vendorComboIds" => $array_combos
			);

			$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

			//die($payload);

			$result = $this->api_bees_post($url, $this->headers, $payload, "DELETE", "number");

			//print_r($result);die();

			if($result == 200)
			{
				$this->dbinfo->where(array("id" => $item->id));
				$this->dbinfo->update('cat_productos', array("subidobees" => "1"));
			}
		}
	}

	public function postOrder()
	{
		$url = $this->url_api_entidades."orders";

		$pedidos = $this->GetPedidosPendientes();

		$array_pedidos = array();
		$idspedidos = "";
		
		foreach($pedidos as $pedido)
		{
			$pedidodetalle = $this->GetPedidoDetalleById($pedido->id);

			$array_padre_combos = array();
			$array_padre_items = array();

			$grantotal = 0;

			foreach($pedidodetalle as $pd)
			{
				$cantidadentregado = $pd->cantidad_entregado - $pd->cantidad_rechazado;
				$importe = $pd->precio * $cantidadentregado;
				$importe = number_format((float)$importe, 2, '.', '');

				if($pd->tipoitem == "PAQUETE")
				{
					$array_freegoods = array();
					$array_componentes = array();

					$componentespaquete = $this->GetPaquetesComponente($pd->iditem);

					if($pd->tipocombo == "D")
					{
						foreach($componentespaquete as $cp)
						{
							$c = array(
								"tax" => 0,
								"discount" => 0,
								"key" => md5($cp->codigo),
								"name" => $cp->nombre,
								"quantity" => $cp->cantidad,
								"basePrice" => $pd->precio,
								"subtotal" => number_format((float)($pd->precio * $cp->cantidad), 2, '.', ''),
								"total" => number_format((float)($pd->precio * $cp->cantidad), 2, '.', ''),
								"vendorItemId" => $cp->codigo
							);

							array_push($array_componentes, $c);
						}
					}
					else if($pd->tipocombo == "FG")
					{
						foreach($componentespaquete as $cp)
						{
							if($cp->tipo == "P")
							{
								$c = array(
									"tax" => 0,
									"discount" => 0,
									"key" => md5($cp->codigo),
									"name" => $cp->nombre,
									"quantity" => $cp->cantidad,
									"basePrice" => $pd->precio,
									"subtotal" => number_format((float)($pd->precio * $cp->cantidad), 2, '.', ''),
									"total" => number_format((float)($pd->precio * $cp->cantidad), 2, '.', ''),
									"vendorItemId" => $cp->codigo
								);

								array_push($array_componentes, $c);
							}
							else if($cp->tipo == "G")
							{
								$c = array(
									"items" => array(
										array(
											"discount" => 0,
											"key" => md5($cp->codigo),
											"measureUnit" => $cp->unidadmedida,
											"basePrice" => 0,
											"total" => 0,
											"vendorItemId" => $cp->codigo
										)
									),
									"quantity" => $cp->cantidad
								);

								array_push($array_freegoods, $c);
							}
						}
					}

					$combo = array(
						"charges" => [],
						"description" => $pd->producto,
						"key" => md5($pd->codigoproducto),
						"title" => $pd->producto,
						"subtotal" => $importe,
						"quantity" => $cantidadentregado,
						"originalPrice" => $pd->precio,
						"price" => $pd->precio,
						"total" => $importe,
						"type" => $pd->tipocombo,
						"vendorComboId" => $pd->codigoproducto,
						"discountPercentage" => 0,
						"items" => $array_componentes,
						"freeGoods" => $array_freegoods
					);

					if($pd->tipocombo == "D")
					{
						unset($combo["freeGoods"]);
					}

					//$grantotal = $grantotal + $pd->importe;

					array_push($array_padre_combos, $combo);
				}
				else if($pd->tipoitem == "PRODUCTO")
				{
					$i = array(
						"key" => md5($pd->codigoproducto),
						"typeOfUnit" => $pd->unidadmedida,
						"vendorItemId" => $pd->codigoproducto,
						"type" => "REGULAR",
						"quantity" => $cantidadentregado,
						"name" => $pd->producto,
						"sku" => $pd->codigoproducto,
						"summaryItem" => array(
							"discount" => 0,
							"price" => $pd->precio,
							"subtotal" => $importe,
							"total" => $importe,
							"taxes" => [],
							"charges" => [],
							"discounts" => []
						)
					);

					array_push($array_padre_items, $i);
				}

				$grantotal = $grantotal + $importe;
			}

			$fecha = $pedido->fecha."T00:00:00Z";

			$p = array(
				"order" => array(
					"status" => $pedido->estatusbees,
					"channel" => $pedido->canal,
					"placementDate" => $fecha,
					"delivery" => array(
						"note" => "",
						"deliveryCenterId" => $pedido->codigosucursal,
						"date" => $fecha,
						"deliveredDate" => $fecha,
						"distributionCenters" => []
					),
					"combos" => $array_padre_combos,
					"items" => $array_padre_items,
					"payment" => array(
						"paymentMethod" => "CASH"
					),
					"summary" => array(
						"discount" => 0,
						"subtotal" => number_format((float)$grantotal, 2, '.', ''),
						"total" => number_format((float)$grantotal, 2, '.', '')
					),
					"vendor" => array(
						"accountId" => $pedido->codigocliente,
						"orderNumber" => $pedido->folio
					)
				)
			);

			$idspedidos = $idspedidos.$pedido->id.',';

			array_push($array_pedidos, $p);
		}

		//die($idspedidos);

		$payload = json_encode($array_pedidos, JSON_UNESCAPED_UNICODE);

		//die($payload);

		if(count($array_pedidos) == 0) return;

		$result = $this->api_bees_post($url, $this->headers, $payload, "POST", "number");

		if($result == 200)
		{
			if($idspedidos != "")
			{
				$idspedidos = substr($idspedidos, 0, -1);

				$this->dbinfo->query("UPDATE pedidos SET subidobees = 1 WHERE FIND_IN_SET(id, '$idspedidos');");
			}
		}

		//print_r($result);
	}

	public function denied_pedido_manual()
	{
		$url = $this->url_api_entidades."orders";
		$array_pedidos = array();

		$p = array(
			"order" => array(
				"status" => "DENIED",
				"orderNumber" => "583900129",
				"cancellation" => array("reason" => "error al guardar")
			)
		);

		array_push($array_pedidos, $p);

		$payload = json_encode($array_pedidos, JSON_UNESCAPED_UNICODE);

		$result = $this->api_bees_post($url, $this->headers, $payload, "PATCH", "number");

		echo $result;
	}

	public function patchOrder($pEstatus = "", $pIds)
	{
		$url = $this->url_api_entidades."orders";

		$pedidos = $this->GetPedidosActualizar($pIds);

		$array_pedidos = array();
		$idspedidos = "";
		
		foreach($pedidos as $pedido)
		{
			$pedidodetalle = $this->GetPedidoDetalleById($pedido->id);

			$array_padre_combos = array();
			$array_padre_items = array();

			$grantotal = 0;

			foreach($pedidodetalle as $pd)
			{
				$cantidadentregado = $pd->cantidad_entregado - $pd->cantidad_rechazado;
				$importe = $pd->precio * $cantidadentregado;
				$importe = number_format((float)$importe, 2, '.', '');

				if($pd->type == "REDEEMABLE")
				{
					$importe = number_format((float)0, 2, '.', '');
				}

				if($pd->tipoitem == "PAQUETE")
				{
					$json_items = json_decode($pd->items, true);

					$json_items[0]["subtotal"] = $json_items[0]["basePrice"] * $json_items[0]["quantity"] * $cantidadentregado;
					$json_items[0]["subtotal"] = number_format((float)$json_items[0]["subtotal"], 2, '.', '');
					$json_items[0]["basePrice"] = number_format((float)$json_items[0]["basePrice"], 2, '.', '');

					$combo = array(
						"key" => $pd->key,
						"price" => $pd->precio,
						"quantity" => $cantidadentregado,
						"subtotal" => $importe,
						"total" => $importe,
						"type" => $pd->type,
						"vendorComboId" => $pd->codigoproducto,
						"items" => $json_items,
					);

					if($pd->type == "FG")
					{
						$json_free = json_decode($pd->freegoods, true);
						$json_free[0]["items"][0]["discount"] = number_format((float)$json_free[0]["items"][0]["discount"], 2, '.', '');
						$json_free[0]["items"][0]["basePrice"] = number_format((float)$json_free[0]["items"][0]["basePrice"], 2, '.', '');
						$combo["freeGoods"] = $json_free;
					}

					array_push($array_padre_combos, $combo);
				}
				else if($pd->tipoitem == "PRODUCTO")
				{
					$i = array(
						"key" => $pd->key,
						"vendorItemId" => $pd->codigoproducto,
						"type" => $pd->type,
						"quantity" => $cantidadentregado,
						"summaryItem" => array(
							"price" => $pd->precio,
							"subtotal" => $importe,
							"total" => $importe,
						)
					);

					array_push($array_padre_items, $i);
				}

				$grantotal = $grantotal + $importe;
			}

			$fecha = $pedido->fecha."T00:00:00Z";
			$fechaentrega = str_replace("MDT", "T", date("Y-m-dTH:i:s", strtotime($pedido->fechaentrega2)))."Z";

			$p = array(
				"order" => array(
					"status" => $pedido->estatusbees,
					"orderNumber" => $pedido->foliobees,
					"combos" => $array_padre_combos,
					"items" => $array_padre_items,
					"summary" => array(
						"subtotal" => number_format((float)$grantotal, 2, '.', ''),
						"total" => number_format((float)$grantotal, 2, '.', '')
					),
					"vendor" => array(
						"accountId" => $pedido->codigocliente,
						"orderNumber" => $pedido->folio
					)
				)
			);

			if($pedido->estatusbees == "PLACED")
			{
				unset($p["order"]["combos"]);
				unset($p["order"]["items"]);
				unset($p["order"]["summary"]);
				unset($p["order"]["vendor"]);
			}
			else if($pedido->estatusbees == "CONFIRMED")
			{
				unset($p["order"]["combos"]);
				unset($p["order"]["items"]);
				unset($p["order"]["summary"]);
			}
			else if($pedido->estatusbees == "DELIVERED")
			{
				unset($p["order"]["combos"]);
				unset($p["order"]["items"]);
				unset($p["order"]["summary"]);

				$p["order"]["delivery"] = array(
					"deliveredDate" => $fechaentrega,
					"deliveryCenterId" => $pedido->codigosucursal
				);
			}
			else if($pedido->estatusbees == "PARTIAL_DELIVERY")
			{
				$p["order"]["delivery"] = array(
					"deliveredDate" => $fechaentrega,
					"deliveryCenterId" => $pedido->codigosucursal
				);
			}
			else if($pedido->estatusbees == "DENIED")
			{
				unset($p["order"]["combos"]);
				unset($p["order"]["items"]);
				unset($p["order"]["summary"]);

				$p["order"]["cancellation"] = array("reason" => $pedido->motivocancelacion);
			}

			$idspedidos = $idspedidos.$pedido->id.',';

			array_push($array_pedidos, $p);
		}

		$payload = json_encode($array_pedidos, JSON_UNESCAPED_UNICODE);

		//die($payload);

		if(count($array_pedidos) == 0) return;

		$result = $this->api_bees_post($url, $this->headers, $payload, "PATCH", "number");

		if($result == 200)
		{
			if($idspedidos != "")
			{
				$idspedidos = substr($idspedidos, 0, -1);

				if($pEstatus == "PLACED")
				{
					//$this->dbinfo->query("UPDATE pedidos SET subidobees = 1, comentarios = 'ACTUALIZADO 1', fecha = fnGetDiaProceso(fechaentrega) WHERE FIND_IN_SET(id, '$idspedidos');");
					$this->dbinfo->query("UPDATE pedidos SET subidobees = 1, comentarios = 'ACTUALIZADO 1', fecha = fnGetProximaFechaArmadoParaEntrega(idcliente) WHERE FIND_IN_SET(id, '$idspedidos');");
				}
				else
				{
					$this->dbinfo->query("UPDATE pedidos SET subidobees = 1, comentarios = 'ACTUALIZADO 2' WHERE FIND_IN_SET(id, '$idspedidos');");
				}
			}
		}

		//print_r($result);
	}

	public function postUcc()
	{
		$url = $this->url_api_entidades."ucc";

		$array_ucc = array();

		$sucursales = $this->GetSucursales();

		foreach($sucursales as $sucursal)
		{
			$rutas = $this->GetRutasBySucursal($sucursal->id);

			foreach($rutas as $ruta)
			{
				$clientes = $this->GetCodigoClienteByRuta($ruta->id);

				if(count($clientes) > 0)
				{
					$r = array(
						"bdrId" => "NMXLIZ-".$ruta->ruta,
						"distributionCenterId" => $ruta->clavesucursal,
						"sectorId" => $ruta->ruta,
						"isActive" => true,
						"accounts" => $clientes
					);

					array_push($array_ucc, $r);
				}
			}
		}

		$payload = json_encode($array_ucc, JSON_UNESCAPED_UNICODE);

		//die($payload);

		$result = $this->api_bees_post($url, $this->headers, $payload, "POST");

		print_r($result);
	}

	public function postVisits()
	{
		$url = $this->url_api_entidades."visits";

		$visitas = $this->GetClientesVisita();
		
		$payload = $visitas;

		$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);

		//die($payload);

		$result = $this->api_bees_post($url, $this->headers, $payload, "POST");

		print_r($result);
	}

	public function postInvoice()
	{
		$url = $this->url_api_entidades."invoices";

		$array_invoices = array();

		$invoices = $this->GetInvoicesPendientes();

		$idspedidos = "";
		
		foreach($invoices as $i)
		{
			$pedidodetalle = $this->GetPedidoDetalleById($i->id);
			
			$array_items = array();
			$grantotal = 0;
			$grantotaloriginal = 0;
			$items = 0;

			foreach($pedidodetalle as $pd)
			{
				$cantidadentregado = $pd->cantidad_entregado - $pd->cantidad_rechazado;
				$importe = $pd->precio * $cantidadentregado;
				$importe = number_format((float)$importe, 2, '.', '');

				$c = array(
					"vendorItemId" => $pd->codigoproducto,
					"quantity" => $cantidadentregado,
					"price" => $pd->precio,
					"subtotal" => $importe,
					"total" => $importe,
					"discount" => 0,
					"tax" => 0,
					"freeGood" => false,
				);

				if($pd->type == "REDEEMABLE")
				{
					$importe = number_format((float)0, 2, '.', '');

					$c["freeGood"] = true;
					$c["price"] = $importe;
					$c["subtotal"] = $importe;
					$c["total"] = $importe;

					$grantotal = $grantotal + $importe;
					$items++;

					array_push($array_items, $c);
				}
				else if($pd->type == "REGULAR")
				{
					$grantotal = $grantotal + $importe;
					$items++;

					array_push($array_items, $c);
				}
				else if($pd->type == "D")
				{
					$itemscombos = json_decode($pd->items, true);

					$cantidades = array_column($itemscombos, 'quantity');
					$totalcantidades = array_sum($cantidades);

					$cantidadentregado = $pd->cantidad_entregado - $pd->cantidad_rechazado;
					$totalcantidades = $totalcantidades * $cantidadentregado;

					$importe = $pd->precio * $cantidadentregado;
					$precio = $totalcantidades == 0 ? 0 : $importe / $totalcantidades;

					$importeoriginal = $pd->preciooriginal * $cantidadentregado;
					$preciooriginal = $totalcantidades == 0 ? 0 : $importeoriginal / $totalcantidades;

					$grantotaloriginal = $grantotaloriginal + ($importeoriginal - $importe);

					foreach($itemscombos as $ic)
					{
						$cantidadaentregar = $cantidadentregado * $ic["quantity"];
						$importe = $precio * $cantidadaentregar;
						$importe = number_format((float)$importe, 2, '.', '');
						$descuento = $preciooriginal - $precio;

						$c["vendorItemId"] = $ic["vendorItemId"];
						$c["quantity"] = $cantidadaentregar;
						$c["price"] = $precio;
						$c["subtotal"] = $importe;
						$c["total"] = $importe;
						$c["discount"] = number_format((float)$descuento, 2, '.', '');

						array_push($array_items, $c);
						
						$grantotal = $grantotal + $importe;
						$items++;
					}
				}
				else if($pd->type == "FG")
				{
					$itemscombos = json_decode($pd->items, true);

					$cantidades = array_column($itemscombos, 'quantity');
					$totalcantidades = array_sum($cantidades);

					$cantidadentregado = $pd->cantidad_entregado - $pd->cantidad_rechazado;
					$totalcantidades = $totalcantidades * $cantidadentregado;

					$importe = $pd->precio * $cantidadentregado;
					$precio = $totalcantidades == 0 ? 0 : $importe / $totalcantidades;

					$importeoriginal = $pd->preciooriginal * $cantidadentregado;
					$preciooriginal = $totalcantidades == 0 ? 0 : $importeoriginal / $totalcantidades;

					$grantotaloriginal = $grantotaloriginal + ($importeoriginal - $importe);

					foreach($itemscombos as $ic)
					{
						$cantidadaentregar = $cantidadentregado * $ic["quantity"];
						$importe = $precio * $cantidadaentregar;
						$importe = number_format((float)$importe, 2, '.', '');
						$descuento = $preciooriginal - $precio;

						$c["vendorItemId"] = $ic["vendorItemId"];
						$c["quantity"] = $cantidadaentregar;
						$c["price"] = $precio;
						$c["subtotal"] = $importe;
						$c["total"] = $importe;
						$c["discount"] = number_format((float)$descuento, 2, '.', '');

						array_push($array_items, $c);
						
						$grantotal = $grantotal + $importe;
						$items++;
					}

					$itemscombos = json_decode($pd->freegoods, true);

					foreach($itemscombos as $ic)
					{
						$cantidadentregado = $pd->cantidad_entregado - $pd->cantidad_rechazado;
						$cantidadentregado = $cantidadentregado * $ic["quantity"];
						$precio = 0;//$ic["subtotal"] / $cantidadentregado;
						$precio = number_format((float)$precio, 2, '.', '');
						$importe = 0;//$precio * $cantidadentregado;
						$importe = number_format((float)$importe, 2, '.', '');

						$c["freeGood"] = true;
						$c["vendorItemId"] = $ic["items"][0]["vendorItemId"];
						$c["quantity"] = $cantidadentregado;
						$c["price"] = $precio;
						$c["subtotal"] = $importe;
						$c["total"] = $importe;

						array_push($array_items, $c);

						$grantotal = $grantotal + $importe;
						$items++;
					}
				}
			}

			$fecha = $i->fecha."T00:00:00Z";
			//$grantotaloriginal = $grantotaloriginal - $grantotal;

			$p = array(
				"channel" => $i->canal,
				"customerInvoiceNumber" => "I_".$i->foliobees,
				"deliveryCenterId" => $i->codigosucursal,
				"orderId" => $i->foliobees,
				"status" => "CLOSED",
				"date" => $fecha,
				"invoicedDate" => $fecha,
				"orderDate" => $i->fechaentrega2,
				"items" => $array_items,
				"itemsQuantity" => $items,
				"paymentType" => $i->tipopedidobees == "REDEEM" ? "REWARDS_POINTS" : "CASH",
				"discount" => number_format((float)$grantotaloriginal, 2, '.', ''),
				"subtotal" => number_format((float)$grantotal, 2, '.', ''),
				"tax" => 0,
				"total" => number_format((float)$grantotal, 2, '.', ''),
				"vendor" => array(
					"accountId" => $i->codigocliente,
					"invoiceId" => "I_".$i->foliobees
				)
			);

			$idspedidos = $idspedidos.$i->id.',';

			array_push($array_invoices, $p);
		}

		$payload = json_encode($array_invoices, JSON_UNESCAPED_UNICODE);

		//echo "<pre>";print_r($array_invoices);"</pre>";die();

		if(count($array_invoices) == 0) return;

		$result = $this->api_bees_post($url, $this->headers, $payload, "POST", "number");

		if($result == 200)
		{
			if($idspedidos != "")
			{
				$idspedidos = substr($idspedidos, 0, -1);

				$itemsArray = explode(',', $idspedidos);

				foreach($itemsArray as $id)
				{
					$this->dbinfo->query("UPDATE pedidos SET estatusbees = 'INVOICE' WHERE id = '$id';");
				}
			}
		}

		print_r($result);
	}

	public function getBeesOrder()
	{
		$url = $this->url_api_entidades."orders?orderStatus=PENDING";

		$rutas = $this->dbinfo->query("SELECT t2.*, t1.`zona`
		FROM asi_ruta_zona t1
		INNER JOIN cat_rutas t2 ON t1.`ruta` = t2.`id`")->result();

		$productos = $this->GetTodosProductos();
		$clientes = $this->GetAllClientes();
		
		$result = $this->api_bees_post($url, $this->headers, null, "GET");
		//$result = '{"orders":[{"audit":{"createAt":"2025-06-27T20:51:01.275Z","updateAt":"2025-08-09T05:19:59.805Z"},"beesAccountId":"7229ae20-4ac9-40ca-b344-1cbf83c2ae54","channel":"B2B_APP","combos":[{"key":"97-4356-b567-86b6e22311_0ation782010","vendorComboId":"PQ111","originalPrice":233.00,"price":178.20,"quantity":1,"type":"FG","items":[{"dynamicAttributes":{"id":"42eb9078-35c8-3d9c-af50-9240dd427325","sku":"1000000002","vendorComboItemId":"comboNutriCarnation-1000000002"},"key":"897-4356-b567-86b6e22311_00000201040","vendorItemId":"1000000002","container":{"name":"Gramos","unitOfMeasurement":"g","itemSize":120,"returnable":false},"discount":19.80,"measureUnit":"g","quantity":4,"deliveryCenterItemDetail":[],"total":178.20,"subtotal":178.20,"name":"Nutri Rindes","charges":[],"basePrice":49.50,"taxes":[]}],"description":"Compra 4 Nutri Rindes 120g con 10% off y recibe 1 leche evaporada Carnation 496g gratis. ¡Haz rendir tus comidas con Nestlé!","title":"Compra 4 Nutri Rindes y lleva 1 Carnation gratis","freeGoods":[{"quantity":1,"items":[{"key":"897-4356-b567-86b6e22311_00000601000","vendorItemId":"1000000006","discount":35.00,"total":0,"basePrice":35.00,"name":"Leche Evaporada Carnation"}]}],"image":"https://cms-non-prod.global.ssl.fastly.net/media/06-27-2025-comboDiscFreeGood_final-1493d493.jpeg","charges":[],"total":178.20,"subtotal":178.20,"discountPercentage":23.52}],"delivery":{"date":"2025-07-30","windowId":"0801623370","type":"REGULAR","note":"","deliveryCenterId":"MX1001","distributionCenters":[],"multiOrigin":false},"empties":{"extraAmount":null,"discountForExtraEmpties":null,"hasEmpties":false,"minimumRequired":null,"totalAmount":null},"items":[],"orderGenericInfo":{"dynamicAttributes":{"SnowflakeStatus":"Processed"},"poNumber":""},"orderNumber":"63605906","payment":{"translations":[{"locale":"es-MX","description":"Efectivo","subDescription":""}],"dynamicAttributes":{"paymentMethodCode":"CASH","transactionReferenceId":""},"paymentMethod":"CASH","paymentMethodCode":"CASH"},"placementDate":"2025-06-27T20:51:01.274+00:00","previousStatus":"PENDING","status":"PENDING","summary":{"discount":54.80,"subtotal":178.20,"total":178.20,"taxes":[],"charges":[],"aggregateAmount":[],"dynamicValues":{"deposit":0.00},"browseSubtotal":178.20,"browseDiscountAmount":54.80},"vendor":{"image":"","id":"6de1e3f5-430c-4689-b26b-cc76a841d77d","accountId":"MEX0011223344"},"orderProperties":["REGULAR"],"deleted":false,"payments":{"dynamicAttributes":{"paymentMethodCode":"CASH","transactionReferenceId":""},"methods":[{"translations":[{"locale":"es-MX","description":"Efectivo","subDescription":""}],"paymentMethod":"CASH","paymentTerm":null,"paymentMethodCode":"CASH","monetaryAmount":178.20,"nonMonetaryAmount":null,"receiptUrl":null,"transactionReferenceId":null,"financialCategoryId":null,"installments":null}]},"purchaseId":1000023480122,"customerInfoPersonal":{"emailAddress":"andresr94+umxl@hotmail.com","familyName":"Demo Store","name":"Lizer Demo Store","givenName":"Lizer","locale":"es-MX"}},{"audit":{"createAt":"2025-06-27T20:38:51.891Z","updateAt":"2025-08-09T05:20:00.019Z"},"beesAccountId":"7229ae20-4ac9-40ca-b344-1cbf83c2ae54","channel":"B2B_APP","combos":[{"key":"a0-4ceb-9160-fe71484e06_0t-001592510","vendorComboId":"PQ122","originalPrice":68.10,"price":59.25,"quantity":1,"type":"D","items":[{"dynamicAttributes":{"id":"21632222-848a-3663-9880-2106183ff00a","sku":"1000000001","vendorComboItemId":"demoStore-ComboDiscount-001-1000000001"},"key":"4a0-4ceb-9160-fe71484e06_00000151030","vendorItemId":"1000000001","container":{"name":"Mililitros","unitOfMeasurement":"ml","itemSize":330,"returnable":false},"discount":8.85,"measureUnit":"ml","quantity":3,"deliveryCenterItemDetail":[],"total":59.25,"subtotal":59.25,"name":"Nescafé Latte RTD","charges":[],"basePrice":22.70,"taxes":[]}],"description":"Llévate 3 unidades de Nescafé Latte RTD con 13 % de descuento. Ideal para disfrutar en cualquier momento del día.","title":"Combo Nescafé Latte RTD 330 ml (3x)","freeGoods":[],"image":"https://cms-non-prod.global.ssl.fastly.net/media/06-27-2025-ComboDiscount_final-036b04d9.jpeg","charges":[],"total":59.25,"subtotal":59.25,"discountPercentage":13.00}],"delivery":{"date":"2025-07-30","windowId":"0801623370","type":"REGULAR","note":"","deliveryCenterId":"MX1001","distributionCenters":[],"multiOrigin":false},"empties":{"extraAmount":null,"discountForExtraEmpties":null,"hasEmpties":false,"minimumRequired":null,"totalAmount":null},"items":[],"orderGenericInfo":{"dynamicAttributes":{"SnowflakeStatus":"Processed"},"poNumber":""},"orderNumber":"63605904","payment":{"translations":[{"locale":"es-MX","description":"Efectivo","subDescription":""}],"dynamicAttributes":{"paymentMethodCode":"CASH","transactionReferenceId":""},"paymentMethod":"CASH","paymentMethodCode":"CASH"},"placementDate":"2025-06-27T20:38:51.890+00:00","previousStatus":"PENDING","status":"PENDING","summary":{"discount":8.85,"subtotal":59.25,"total":59.25,"taxes":[],"charges":[],"aggregateAmount":[],"dynamicValues":{"deposit":0.00},"browseSubtotal":59.25,"browseDiscountAmount":8.85},"vendor":{"image":"","id":"6de1e3f5-430c-4689-b26b-cc76a841d77d","accountId":"MEX0011223344"},"orderProperties":["REGULAR"],"deleted":false,"payments":{"dynamicAttributes":{"paymentMethodCode":"CASH","transactionReferenceId":""},"methods":[{"translations":[{"locale":"es-MX","description":"Efectivo","subDescription":""}],"paymentMethod":"CASH","paymentTerm":null,"paymentMethodCode":"CASH","monetaryAmount":59.25,"nonMonetaryAmount":null,"receiptUrl":null,"transactionReferenceId":null,"financialCategoryId":null,"installments":null}]},"purchaseId":1000023480082,"customerInfoPersonal":{"emailAddress":"andresr94+umxl@hotmail.com","familyName":"Demo Store","name":"Lizer Demo Store","givenName":"Lizer","locale":"es-MX"}},{"audit":{"createAt":"2025-06-27T20:45:35.894Z","updateAt":"2025-08-09T05:20:01.863Z"},"beesAccountId":"7229ae20-4ac9-40ca-b344-1cbf83c2ae54","channel":"B2B_APP","combos":[{"key":"a2-4dc7-a432-1616568cc1_0squik900010","vendorComboId":"PQ123","originalPrice":129.60,"price":90.00,"quantity":1,"type":"FG","items":[{"dynamicAttributes":{"id":"43b06712-c089-3a94-93d3-d897158676ea","sku":"1000000005","vendorComboItemId":"comboTrixGratisNesquik-1000000005"},"key":"6a2-4dc7-a432-1616568cc1_00000501020","vendorItemId":"1000000005","container":{"name":"Gramos","unitOfMeasurement":"g","itemSize":230,"returnable":false},"discount":0.00,"measureUnit":"g","quantity":2,"deliveryCenterItemDetail":[],"total":90.00,"subtotal":90.00,"name":"Cereal Trix","charges":[],"basePrice":45.00,"taxes":[]}],"description":"Compra 2 cereales Trix 230 g y llévate 1 cereal Nesquik 230 g gratis. ¡Empieza tus mañanas con diversión y sabor!","title":"Compra 2 Trix y lleva 1 Nesquik gratis","freeGoods":[{"quantity":1,"items":[{"key":"6a2-4dc7-a432-1616568cc1_00000401000","vendorItemId":"1000000004","discount":39.60,"total":0,"basePrice":39.60,"name":"Cereal Nesquik"}]}],"image":"https://cms-non-prod.global.ssl.fastly.net/media/06-27-2025-comboFreeGood_final-a3fd65e5.jpeg","charges":[],"total":90.00,"subtotal":90.00,"discountPercentage":30.56}],"delivery":{"date":"2025-07-30","windowId":"0801623370","type":"REGULAR","note":"","deliveryCenterId":"MX1001","distributionCenters":[],"multiOrigin":false},"empties":{"extraAmount":null,"discountForExtraEmpties":null,"hasEmpties":false,"minimumRequired":null,"totalAmount":null},"items":[],"orderGenericInfo":{"dynamicAttributes":{"SnowflakeStatus":"Processed"},"poNumber":""},"orderNumber":"63605905","payment":{"translations":[{"locale":"es-MX","description":"Efectivo","subDescription":""}],"dynamicAttributes":{"paymentMethodCode":"CASH","transactionReferenceId":""},"paymentMethod":"CASH","paymentMethodCode":"CASH"},"placementDate":"2025-06-27T20:45:35.893+00:00","previousStatus":"PENDING","status":"PENDING","summary":{"discount":39.60,"subtotal":90.00,"total":90.00,"taxes":[],"charges":[],"aggregateAmount":[],"dynamicValues":{"deposit":0.00},"browseSubtotal":90.00,"browseDiscountAmount":39.60},"vendor":{"image":"","id":"6de1e3f5-430c-4689-b26b-cc76a841d77d","accountId":"MEX0011223344"},"orderProperties":["REGULAR"],"deleted":false,"payments":{"dynamicAttributes":{"paymentMethodCode":"CASH","transactionReferenceId":""},"methods":[{"translations":[{"locale":"es-MX","description":"Efectivo","subDescription":""}],"paymentMethod":"CASH","paymentTerm":null,"paymentMethodCode":"CASH","monetaryAmount":90.00,"nonMonetaryAmount":null,"receiptUrl":null,"transactionReferenceId":null,"financialCategoryId":null,"installments":null}]},"purchaseId":1000023480108,"customerInfoPersonal":{"emailAddress":"andresr94+umxl@hotmail.com","familyName":"Demo Store","name":"Lizer Demo Store","givenName":"Lizer","locale":"es-MX"}}],"pagination":{"page":0,"hasNext":false}}';

		//die($result);

		$orders = json_decode($result);

		$array_p = array();

		$idspedidos = "";

		foreach($orders->orders as $order)
		{
			$o = $order;

			$array_pd = array();

			### -> INICIO VALIDA LA INFORMACION DEL CLIENTE ###
			$searchValue = $o->vendor->accountId;
			$searchKey = 'codigo';

			$infocliente = array_filter($clientes, function ($object) use ($searchKey, $searchValue) {
				return $object->$searchKey === $searchValue;
			});

			$infocliente = reset($infocliente);

			if(!$infocliente)
			{
				continue;
			}
			### FIN VALIDA LA INFORMACION DEL CLIENTE <- ###

			### -> INICIO VALIDA LA INFORMACION DE LA RUTA ###
			$searchValue = $infocliente->zona;
			$searchKey = 'zona';

			$inforuta = array_filter($rutas, function ($object) use ($searchKey, $searchValue) {
				return $object->$searchKey === $searchValue;
			});

			$inforuta = reset($inforuta);

			if(!$inforuta)
			{
				$inforuta = null;
			}
			### FIN VALIDA LA INFORMACION DE LA RUTA <- ###

			foreach($o->combos as $det)
			{
				### -> INICIO VALIDA LA INFORMACION DEl PRODUCTO ###
				$searchValue = $det->vendorComboId;
				$searchKey = 'codigo';

				$infoproducto = array_filter($productos, function ($object) use ($searchKey, $searchValue) {
					return $object->$searchKey === $searchValue;
				});

				$infoproducto = reset($infoproducto);

				if(!$infoproducto)
				{
					break;
				}
				### -> FIN VALIDA LA INFORMACION DEl PRODUCTO ###

				$pd = array(
					"idpedido" => "0",
					"iditem" => $infoproducto->id,
					"idmovimiento" => "0",
					"idclasificacion" => $infoproducto->clasificacion,
					"idproveedor" => $infoproducto->proveedor,
					"key" => $det->key,
					"codigoproducto" => $det->vendorComboId,
					"producto" => $det->title,
					"cantidadoriginal" => $det->quantity,
					"cantidad" => $det->quantity,
					"precio" => $det->price,
					"preciooriginal" => $det->originalPrice,
					"costo" => $infoproducto->costo,
					"ieps" => $infoproducto->ieps,
					"iva" => $infoproducto->iva,
					"importe" => $det->total,
					"cantidad_entregado" => $det->quantity,
					"cantidad_rechazado" => "0",
					"tipopromo" => "0",
					"valorpromo" => "0",
					"fechacreacion" => date("Y-m-d H:i:s", strtotime($o->audit->createAt)),
					"fecha_registro" => date("Y-m-d H:i:s"),
					"type" => $det->type,
					"items" => json_encode($det->items),
					"freegoods" => isset($det->freeGoods) ? json_encode($det->freeGoods) : "",
					"status" => "1"
				);

				array_push($array_pd, $pd);
			}

			foreach($o->items as $det)
			{
				$searchValue = $det->vendorItemId;
				$searchKey = 'codigo';

				$infoproducto = array_filter($productos, function ($object) use ($searchKey, $searchValue) {
					return $object->$searchKey === $searchValue;
				});

				$infoproducto = reset($infoproducto);

				if(!$infoproducto)
				{
					break;
				}
				//print_r($infoproducto);die();

				$pd = array(
					"idpedido" => "0",
					"iditem" => $infoproducto->id,
					"idmovimiento" => "0",
					"idclasificacion" => $infoproducto->clasificacion,
					"idproveedor" => $infoproducto->proveedor,
					"key" => $det->key,
					"codigoproducto" => $det->vendorItemId,
					"producto" => $det->name,
					"cantidadoriginal" => $det->quantity,
					"cantidad" => $det->quantity,
					"precio" => $det->type == "REGULAR" ? $det->summaryItem->price : $det->summaryItem->basePrice,
					"preciooriginal" => "0",
					"costo" => $infoproducto->costo,
					"ieps" => $infoproducto->ieps,
					"iva" => $infoproducto->iva,
					"importe" => $det->summaryItem->total,
					"cantidad_entregado" => $det->quantity,
					"cantidad_rechazado" => "0",
					"tipopromo" => "0",
					"valorpromo" => "0",
					"fechacreacion" => date("Y-m-d H:i:s", strtotime($o->audit->createAt)),
					"fecha_registro" => date("Y-m-d H:i:s"),
					"type" => $det->type,
					"items" => "",
					"freegoods" => "",
					"status" => "1"
				);

				array_push($array_pd, $pd);
			}

			$infopedido = $this->dbinfo->query("SELECT * FROM pedidos WHERE foliobees = '$o->orderNumber'")->result();

			$estatuspedido = "1";

			if($inforuta != null)
			{
				$info_cierre = $this->dbinfo->query("SELECT * FROM cat_rutas WHERE agente = '$inforuta->agente'");
				if($info_cierre->num_rows() > 0)
				{
					$fecha_corte = $info_cierre->row()->fecha_cierre_armado_ruta;
					if(is_null($fecha_corte) || $fecha_corte == "")
					{

					}
					else
					{
						//$infofechaarmado = $this->dbinfo->query("SELECT fnGetDiaProceso('".date("Y-m-d", strtotime($o->delivery->date))."') AS fechaarmado;")->row();
						$infofechaarmado = $this->dbinfo->query("SELECT fnGetProximaFechaArmadoParaEntrega('".$infocliente->id."') AS fechaarmado;")->row();
						
						$fechacreacion = $infofechaarmado->fechaarmado;

						if($fecha_corte == $fechacreacion)
						{
							$estatuspedido = "0";
						}
					}
				}
			}

			if(count($infopedido) > 0)
			{
				$idspedidos = $idspedidos.$infopedido[0]->id.',';
			}
			else
			{
				$p = array(
					"folio" => GETFOLIOPEDIDO("1", $infocliente->sucursal, GETEMPRESA()),
					"foliobees" => $o->orderNumber,
					"idusuario" => $inforuta==null ? 0 : $inforuta->agente,
					"usuario" => $inforuta==null ? 0 : $inforuta->ruta,
					"idcliente" => $infocliente->id,
					"codigocliente" => $infocliente->codigo,
					"cliente" => $infocliente->nombre,
					"fecha" => date("Y-m-d", strtotime($o->audit->createAt)),
					"fechaentrega" => date("Y-m-d", strtotime($o->delivery->date)),
					"tipo" => $o->orderProperties[0] == "REDEEM" ? "CLUB_B" : "PREVENTA",
					"facturado" => "0",
					"credito" => "0",
					"total" => $o->summary->total,
					"latitud" => "0",
					"longitud" => "0",
					"ruta" => $inforuta==null ? 0 : $inforuta->id,
					"fechacreacion" => date("Y-m-d H:i:s", strtotime($o->audit->createAt)),
					"idsucursal" => $inforuta==null ? 0 : $inforuta->sucursal,
					"fecharegistro" => date("Y-m-d H:i:s"),
					"fechaentrega2" => $o->placementDate,
					"status" => $estatuspedido,
					"subidobees" => "1",
					"corte_factura" => $infocliente->codigo_adminpaq=="" ? 0 : 1,
					"corte_metodopago" => $infocliente->metodopago,
					"corte_condicionpago" => $infocliente->condicionpago,
					"tipopedidobees" => $o->orderProperties[0],
					"estatusbees" => "PLACED",
					"canal" => $o->channel,
					"origen" => "BEES",
					"pedido_detalle" => $array_pd
				);

				array_push($array_p, $p);
			}

			//echo "<pre>";print_r($array_p);echo "</pre>";
		}

		foreach($array_p as $item)
		{
			$detalle = $item["pedido_detalle"];
			unset($item["pedido_detalle"]);

			if(count($detalle) == 0)
			{
				continue;
			}

			$this->dbinfo->insert("pedidos", $item);
			$insert_id = $this->dbinfo->insert_id();

			$idsucursal = $item["idsucursal"];

			foreach ($detalle as &$det)
			{
				$det["idpedido"] = $insert_id;

				$idproducto = $det["iditem"];
				$cantidad = $det["cantidad"];

				if($det["items"] != "")//este if quiere decir, si es item tipo PAQUETE
				{
					$det["cantidad_entregado"] = $this->dbinfo->query("SELECT fnGetPaquetesRealVender('$idsucursal', '$idproducto', $cantidad) AS cantidad;")->row()->cantidad;
					$det["cantidad"] = $det["cantidad_entregado"];
				}

				if($item["status"] == 0)
				{
					$det["cantidad_entregado"] = "0";
					//$det["cantidad"] = "0";
				}
			}
			unset($det);

			$this->dbinfo->insert_batch('pedidos_detalle', $detalle);

			$idspedidos = $idspedidos.$insert_id.',';
		}

		if($idspedidos != "")
		{
			$idspedidos = substr($idspedidos, 0, -1);

			$this->patchOrder("PLACED", $idspedidos);
		}
	}

	public function GetUsuarioById($pIdUsuario)
	{
		$consulta = "SELECT * FROM usuarios u WHERE u.id = '$pIdUsuario'";

		$query = $this->db->query($consulta);
		return $query->row();
	}

	public function GetAllUsuarios()
	{
		$consulta = "SELECT * FROM usuarios";

		$query = $this->db->query($consulta);
		return $query->result();
	}

	public function GetClienteById($pIdCliente, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT DISTINCT clientes.`id` AS iddis, clientes.*,
		clientes.`ultima_actualizacion` AS ultima_fecha,
		clientes.`status` AS match_status,
		(SELECT clave FROM cat_sucursales cs WHERE cs.`id` = clientes.sucursal) as codigosucursal,
		IFNULL((SELECT clasificacion FROM cat_clasificacion_cliente cs WHERE cs.`id` = clientes.clasificacion), 'NA') as clasificacioncliente
		FROM clientes
		WHERE clientes.`id` = '$pIdCliente'";

		$query = $this->dbinfo->query($consulta);
		return $query->row();
	}

	public function GetClientes()
	{
		$consulta = "SELECT DISTINCT c.`id` AS iddis, c.*,
		c.`ultima_actualizacion` AS ultima_fecha,
		IF(email = '', 'ventas@mitienda.com.mx', email) AS email2,
		c.`status` AS match_status,
		(SELECT clave FROM cat_sucursales cs WHERE cs.`id` = c.sucursal) AS codigosucursal,
		IFNULL((SELECT clasificacion FROM cat_clasificacion_cliente cs WHERE cs.`id` = c.clasificacion), 'NA') AS clasificacioncliente
		FROM clientes c
		WHERE c.`subidobees` = 0 LIMIT 300";
		
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetAllClientes()
	{
		//$config_app = switch_db_dinamico($empresa, 1);
		//$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT * FROM clientes";
		
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetCodigoClienteByRuta($idruta)
	{
		$consulta = "SELECT c.codigo as accountId 
		FROM clientes c 
		WHERE c.status = 1 AND FIND_IN_SET(c.`zona`, (SELECT GROUP_CONCAT(sub.`zona`) FROM asi_ruta_zona sub WHERE sub.`status` = 1 AND sub.`ruta` = '$idruta'))";
		
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetClientesVisita()
	{
		$consulta = "SELECT *, CONCAT_WS('_', datos.bdrId, datos.accountId, datos.visitDate) AS visitId FROM (
		SELECT c.`codigo` AS accountId, 
		CONCAT('NMXLIZ-',fnGetZonaRutas(c.`zona`)) AS bdrId,
		(SELECT sub.clave FROM cat_sucursales sub WHERE sub.id = c.`sucursal`) AS deliveryCenterId,
		ADDDATE(CURDATE(), 1) AS visitDate,
		'CREATE' AS operation
		FROM clientes c 
		WHERE c.`status` = 1 AND FIND_IN_SET(DAYOFWEEK(ADDDATE(CURDATE(), 1)), c.`diasvisita`)
		ORDER BY c.`sucursal`) AS datos;";
		
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetProductoById($pIdProducto, $empresa)
	{
		$config_app = switch_db_dinamico($empresa, 1);
		$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT *
		FROM cat_productos cp 
		WHERE cp.id = '$pIdProducto'";
		$query = $this->dbinfo->query($consulta);
		return $query->row();
	}

	public function GetProductos()
	{
		$consulta = "SELECT *
		FROM cat_productos cp 
		WHERE cp.tipo2 = 'PRODUCTO' AND cp.`subidobees` = 0";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetPrecioProductos()
	{
		$consulta = "SELECT *
		FROM cat_productos cp 
		WHERE cp.tipo2 = 'PRODUCTO'";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetTodosProductos()
	{
		//$config_app = switch_db_dinamico($empresa, 1);
		//$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT * FROM cat_productos cp WHERE cp.status = 1";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetPaquetes()
	{
		//$config_app = switch_db_dinamico($empresa, 1);
		//$this->dbinfo = $this->load->database($config_app, TRUE);

		/*$consulta = "SELECT *,
		DATE_ADD(fechacreacion, INTERVAL 1 YEAR) AS fechavencimiento
		FROM cat_productos cp 
		WHERE cp.status = 1 AND cp.tipo2 = 'PAQUETE' AND cp.subidobees = 0";*/

		$consulta = "SELECT *,
		DATE_ADD(fechacreacion, INTERVAL 1 YEAR) AS fechavencimiento,
		IFNULL(IF(cp.audiencia = 0, 'TODOS', (SELECT sub.`clientes` FROM paquetes_audiencia sub WHERE sub.`idpaquete` = cp.id)), '') AS audiencia_clientes
		FROM cat_productos cp 
		WHERE cp.tipo2 = 'PAQUETE' AND cp.subidobees = 0;";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetPaquetesComponente($idpaquete)
	{
		//$config_app = switch_db_dinamico($empresa, 1);
		//$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT *,
		(SELECT c.nombre FROM cat_productos c WHERE c.id = cpb.`idproducto`) AS nombre,
		(SELECT cum.nombre FROM cat_unidad_medida cum WHERE cum.id = (SELECT c.contenedorunidadmedida FROM cat_productos c WHERE c.id = cpb.`idproducto`)) AS unidadmedida
		FROM componentes_paquete_bees cpb
		WHERE cpb.idpaquete = '$idpaquete'";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetInventarioBySucursal($pIdSucursal)
	{
		$consulta = "SELECT ir.`codigo` AS vendorItemId, IF(ir.`cantidaddisponible` < 0, 0, ir.`cantidaddisponible`) AS quantity FROM inventario_real ir WHERE ir.idsucursal = '$pIdSucursal'";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetClientesBySucursal($pIdSucursal)
	{
		//$config_app = switch_db_dinamico($empresa, 1);
		//$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT * FROM clientes WHERE FIND_IN_SET(sucursal, '$pIdSucursal')";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetSucursales()
	{
		$consulta = "SELECT * FROM cat_sucursales cs WHERE cs.status = 1";
		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetPedidosPendientes()
	{
		$consulta = "SELECT *,
		(SELECT c.clave FROM cat_sucursales c WHERE c.id = p.`idsucursal`) AS codigosucursal
		FROM pedidos p 
		WHERE p.status = 1 AND p.`tipo` = 'PREVENTA' AND p.origen = 'INROUTE' AND p.subidobees = 0";

		/*$consulta = "SELECT *,
		'DELIVERED' as estatusbees,
		'NON-BEES' as canal,
		(SELECT c.clave FROM cat_sucursales c WHERE c.id = p.`idsucursal`) AS codigosucursal
		FROM pedidos p 
		WHERE p.status = 1 AND p.`tipo` = 'PREVENTA' AND p.`fecha` BETWEEN '2025-04-01' AND '2025-09-30'
		AND p.subidobees = 0 AND p.`idsucursal` IN (1, 11, 16) LIMIT 500";*/

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetPedidosActualizar($idspedidos)
	{
		$consulta = "SELECT *,
		(SELECT c.clave FROM cat_sucursales c WHERE c.id = p.`idsucursal`) AS codigosucursal,
		DATE_ADD(p.`fechacreacion`, INTERVAL 1 DAY) AS fechaentrega2
		FROM pedidos p 
		WHERE FIND_IN_SET(p.id, '$idspedidos');";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetInvoicesPendientes()
	{
		$consulta = "SELECT *,
		(SELECT c.clave FROM cat_sucursales c WHERE c.id = p.`idsucursal`) AS codigosucursal
		FROM pedidos p
		WHERE p.origen = 'BEES' AND p.`estatusbees` IN('DELIVERED', 'PARTIAL_DELIVERY') LIMIT 15;";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetPedidoDetalleById($idpedido)
	{
		//$config_app = switch_db_dinamico($empresa, 1);
		//$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT *,
		(SELECT cp.tipo2 FROM cat_productos cp WHERE cp.id = p.iditem) AS tipoitem,
		(SELECT cp.tipocombo FROM cat_productos cp WHERE cp.id = p.iditem) AS tipocombo,
		(SELECT cum.nombre FROM cat_unidad_medida cum WHERE cum.id = (SELECT c.contenedorunidadmedida FROM cat_productos c WHERE c.id = p.`iditem`)) AS unidadmedida
		FROM vwInformacionGeneralPedidos p 
		WHERE p.`status_principal` = 1 AND p.`status_detalle` = 1 AND p.`idpedido` = '$idpedido'";

		/*$consulta = "SELECT *,
		(SELECT cp.tipo2 FROM cat_productos cp WHERE cp.id = p.iditem) AS tipoitem,
		'PIEZA' AS unidadmedida
		FROM vwInformacionGeneralPedidos p 
		WHERE p.`status_principal` = 1 AND p.`status_detalle` = 1 AND p.`idpedido` = '$idpedido'
		HAVING tipoitem = 'PRODUCTO'";*/

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetRutasBySucursal($idsucursal)
	{
		$consulta = "SELECT *,
		(SELECT sub.clave FROM cat_sucursales sub WHERE sub.id = c.`sucursal`) AS clavesucursal
		FROM cat_rutas c 
		WHERE c.`status` = 1 AND c.sucursal = '$idsucursal'";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetAllRutas()
	{
		$consulta = "SELECT *,
		(SELECT sub.clave FROM cat_sucursales sub WHERE sub.id = c.`sucursal`) AS clavesucursal
		FROM cat_rutas c 
		WHERE c.`status` = 1";

		$query = $this->dbinfo->query($consulta);
		return $query->result();
	}

	public function GetInfoCatalogosPendientes($empresa)
	{
		//$config_app = switch_db_dinamico($empresa, 1);
		//$this->dbinfo = $this->load->database($config_app, TRUE);

		$consulta = "SELECT
		(SELECT COUNT(*) FROM clientes c WHERE c.`subidobees` = 0) AS clientespendientes,
		(SELECT COUNT(*) FROM cat_productos cp WHERE cp.tipo2 = 'PRODUCTO' AND cp.`subidobees` = 0) AS productospendientes,
		(SELECT COUNT(*) FROM cat_productos cp WHERE cp.status = 1 AND cp.tipo2 = 'PAQUETE' AND cp.subidobees = 0) AS combospendientes,
		(SELECT COUNT(*) FROM pedidos p WHERE p.status = 1 AND p.`tipo` = 'PREVENTA' AND p.subidobees = 0) AS pedidospendientes";

		$query = $this->dbinfo->query($consulta);
		return $query->row();
	}

	public function GetBeesDatos()
	{
		$consulta = "SELECT * FROM bees_datos";

		$query = $this->dbinfo->query($consulta);
		return $query->row();
	}

	public function syncBees()
	{
		//OBTIENE LOS PEDIDOS PENDIENTES DE BEES
		$this->getBeesOrder();		

		//ACTUALIZA ESTATUS PEDIDOS(MODIFIED O CONFIRMED) TOMA ENCUENTA SOLO LOS PEDIDOS QUE SE LES HIZO LA CONFIRMACION DE ENTREGAS
		$this->dbinfo->query("UPDATE pedidos AS p
		INNER JOIN 
		(
			SELECT p.`id` AS idpedido, 
			fnGetEstatusBees(p.id) AS estatus
			FROM pedidos p
			INNER JOIN pedidos_detalle pd ON p.id = pd.`idpedido` AND p.`origen` = 'BEES' AND p.`estatusbees`  = 'PLACED'
			INNER JOIN cierres_confirmacion_entregas cce ON cce.`subidobees` = 0 AND p.`fecha` = cce.`fecha` AND p.`idsucursal` = cce.`idsucursal`
			GROUP BY p.`id`
		) AS p2 ON p.`id` = p2.idpedido
		SET p.`estatusbees` = p2.estatus, p.subidobees = 0, motivocancelacion = IF(p2.estatus = 'DENIED', 'CLIENTE SOLICITÓ CANCELAR PEDIDO', '');");

		//ACTUALIZA EL CIERRE DE CONFIRMACION DE ENTREGAS COMO SUBIDO
		$this->dbinfo->query("UPDATE cierres_confirmacion_entregas SET subidobees = 1 WHERE subidobees = 0;");		

		//ACTUALIZA ESTATUS PEDIDOS(DELIVERED O PARTIAL_DELIVERY O DENIED) TOMA EN ENCUENTA PEDIDOS CON ESTATUS(MODIFIED O CONFIRMED) Y QUE TENGAN CORTE
		$this->dbinfo->query("UPDATE pedidos AS p
		INNER JOIN 
		(
			SELECT p.`id` AS idpedido, p.`folio`, p.`idsucursal`, 
			fnGetEstatusBees(p.id) AS estatus
			FROM pedidos p
			INNER JOIN pedidos_detalle pd ON p.id = pd.`idpedido` AND p.`origen` = 'BEES'
			WHERE p.`estatusbees` IN('CONFIRMED', 'MODIFIED') AND p.`corte` > 0
			GROUP BY p.`id`
		) AS p2 ON p.`id` = p2.idpedido
		SET p.`estatusbees` = p2.estatus, p.subidobees = 0, motivocancelacion = IF(p2.estatus = 'DENIED', 'CLIENTE RECHAZÓ PEDIDO', '');");		

		//OBTIENE LOS PEDIDOS QUE SERAN ENVIADOS PARA ACTUALIZACION A BEES Y MANDA ACTUALIZAR LOS PEDIDOS A BEES
		$pedidospatch = $this->dbinfo->query("SELECT IFNULL(GROUP_CONCAT(datos.`id`), '') AS idspedidos 
		FROM
		(
		SELECT *
		FROM pedidos p 
		WHERE p.origen = 'BEES' AND p.`subidobees` = 0 AND p.`estatusbees` IN ('CONFIRMED', 'MODIFIED', 'DENIED', 'PARTIAL_DELIVERY', 'DELIVERED') LIMIT 20
		) AS datos;"
		)->row();		
		
		$this->patchOrder('', $pedidospatch->idspedidos);

		//die("hola");

		//CALCULA LOS PAQUETES QUE YA ALCANZARON LAS CANTIDADES PRESUPUESTADAS
		$paquetes = $this->dbinfo->query("SELECT *,
		fnGetPaquetesVendidosBySucursal(ps.`idsucursal`, ps.`idpaquete`) AS paquetesvendidos
		FROM paquetes_sucursal ps
		WHERE ps.`idpaquete` IN
		(
			SELECT cp.`id` FROM cat_productos cp WHERE cp.tipo2 = 'PAQUETE' AND cp.status = 1
		) AND ps.`activo` = 1
		HAVING paquetesvendidos >= cantidad;"
		)->result();

		foreach($paquetes as $paquete)
		{
			$this->dbinfo->query("UPDATE paquetes_sucursal SET activo = 0 WHERE idpaquete = '$paquete->idpaquete' AND idsucursal = '$paquete->idsucursal';");
			$this->dbinfo->query("UPDATE cat_productos SET subidobees = 0 WHERE id = '$paquete->idpaquete';");
			/*if($paquete->paquetesvendidos >= $paquete->cantidad)
			{
				
			}*/
		}

		//MANDA LOS INVOICES DE PEDIDOS QUE YA ESTAN LISTOS
		$this->postInvoice();

		//ENVIA EL CATALOGO DE CLIENTES QUE ESTEN DISPONIBLES PARA ACTUALIZAR EN BEES
		$this->postMasivoAccount();

		//ENVIA EL CATALOGO DE PRODUCTOS QUE ESTEN DISPONIBLES PARA ACTUALIZAR EN BEES
		$this->postMasivoItem();

		//ENVIA EL CATALAGO DE COMBOS DE QUE ESTEN DISPONIBLES PARA ACTUALIZAR EN BEES
		$this->postComboAccount();

		//ENVIA EL ASSORTMENT
		//$this->postAssortment();
				
		//ENVIA EL INVENTARIO
		$this->postInventory();

		//ENVIA PRECIOS
		//$this->postPrice();

		//ENVIO PEDIDOS NON-BEES
		$this->postOrder();

		//ACTUALIZA HORA Y FECHA DE ACTUALIZACION
		$this->dbinfo->update("bees_datos", array("ultima_actualizacion" => date("Y-m-d H:i:s")));
	}

	public function getPedidoByVisita($fecha, $idcliente, $idruta)
	{
		$consulta = "SELECT * FROM pedidos p WHERE p.`fecha` = '$fecha' AND p.idcliente = '$idcliente' AND p.ruta = '$idruta' AND p.status = '1' AND p.tipo = 'PREVENTA' ;";

		$query = $this->dbinfo->query($consulta);
		return $query->row();
	}

	public function guardarVisitaBees($datos)
	{
		$infovisita = $this->dbinfo->query("SELECT * FROM visitas WHERE idcliente = '$datos[idcliente]' AND idusuario = '$datos[idusuario]' AND fecha = '$datos[fecha]'")->row();
		if ($infovisita)
		{
			$this->dbinfo->where("id", $infovisita->id);
			$this->dbinfo->update("visitas", $datos);
			return $infovisita->id;
		}

		$this->dbinfo->insert("visitas", $datos);

		return $this->dbinfo->insert_id();
	}
}
?>