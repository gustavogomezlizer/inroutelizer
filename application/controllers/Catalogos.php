<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogos  extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','form', 'variables_helper', 'funcioneshtml'));
		$this->load->library(array('session', 'pagination'));
		//$this->load->model(array('CatalogosModel', 'BeesModel'));
		$this->load->model(array('CatalogosModel'));		
		/*if(!$this->session->userdata('logged_in'))		
		{
			redirect(CWELCOME("login"), 'refresh'); 	
		}*/
	}

	public function index()
	{
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('y-m-d');
		echo GETNEWCLIENTENAME(2);
	}

	//########## CATALOGOUSUARIOS ##################

	public function listaUsuarios()
	{
		VERIFICARSESION();

		$data["vista"] = $this->uri->segment(1, 0);

		$this->load->view('Catalogos/Usuarios/vListaUsuarios', $data);
	}

	public function getListaUsuariosJson()
	{
		$idsucursal = $this->input->post("idsucursal");
		$post = $this->input->post();

		echo json_encode($this->CatalogosModel->getListaUsuarios($post));
	}

	public function nuevoUsuario()
	{		
		VERIFICARSESION();
		$data["opcion"] = "nuevo";
		$data["listaPerfiles"] = $this->CatalogosModel->getListaPerfilesX();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$data["listaAvatares"] = $this->CatalogosModel->getListaAvatares();		
		
		$this->load->view('Catalogos/Usuarios/vNewUsuario', $data);
	}

	public function editarUsuario($id)
	{
		VERIFICARSESION();
		$data["opcion"] = "editar";
		$data["usuario"] = $this->CatalogosModel->getDatosUsuario($id)->row();
		$data["listaPerfiles"] = $this->CatalogosModel->getListaPerfilesX();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$data["listaAvatares"] = $this->CatalogosModel->getListaAvatares();
		$this->load->view('Catalogos/Usuarios/vNewUsuario', $data);
	}

	public function verUsuario($id)
	{
		VERIFICARSESION();
		$data["opcion"] = "ver";
		$data["usuario"] = $this->CatalogosModel->getDatosUsuario($id)->row();
		$data["listaPerfiles"] = $this->CatalogosModel->getListaPerfilesX();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$data["listaAvatares"] = $this->CatalogosModel->getListaAvatares();
		$this->load->view('Catalogos/Usuarios/vNewUsuario', $data);
	}

	public function saveNuevoUsuario()
	{
		$datos = $this->input->post();		
		echo $this->CatalogosModel->saveNewUsuario($datos);
	}

	public function saveEditarUsuario()
	{
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveEditUsuario($datos);		
	}

	//########## CATALOGOCLIENTES ##################

	public function listaClientes()
	{
		VERIFICARSESION();		
		$this->load->view('Catalogos/Clientes/vListaClientes');
	}

	public function getListaClientesJson()
	{
		$idsucursal = $this->input->post("idsucursal");
		echo json_encode($this->CatalogosModel->getListaClientes($idsucursal)->result());
	}

	public function nuevoCliente()
	{		
		VERIFICARSESION();
		$data["opcion"] = "nuevo";
		$data["listaZonas"] = $this->CatalogosModel->getListaZonasAll();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$data["listaClasificacionCliente"] = $this->CatalogosModel->getListadoClasificacionClientes();

		$this->load->view('Catalogos/Clientes/vNewCliente', $data);
	}

	public function verCliente($id)
	{
		VERIFICARSESION();
		$data["opcion"] = "ver";
		//$poligono=$this->CatalogosModel->getPoligonoZona($lista->row()->zona);
		//$data["poligonoDatos"]=$this->CatalogosModel->getPoligono($poligono);

		$data["cliente"] = $this->CatalogosModel->getCoordenadasCliente($id)->row();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$data["listaClasificacionCliente"] = $this->CatalogosModel->getListadoClasificacionClientes();

		/*$data["listaZonas"] = $this->CatalogosModel->getListaZonas2($data["cliente"]->sucursal);
		$data["listaProveedores"] = $this->CatalogosModel->getListaProveedores($data["sucursal"]);*/

		$this->load->view('Catalogos/Clientes/vNewCliente', $data);		
	}

	public function editarCliente($id)
	{		
		VERIFICARSESION();
		$data["opcion"] = "editar";
		//$poligono=$this->CatalogosModel->getPoligonoZona($lista->row()->zona);
		//$data["poligonoDatos"]=$this->CatalogosModel->getPoligono($poligono);

		$data["cliente"] = $this->CatalogosModel->getCoordenadasCliente($id)->row();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$data["listaClasificacionCliente"] = $this->CatalogosModel->getListadoClasificacionClientes();

		/*$data["listaZonas"] = $this->CatalogosModel->getListaZonas2($data["cliente"]->sucursal);
		$data["listaProveedores"] = $this->CatalogosModel->getListaProveedores($data["sucursal"]);*/

		$this->load->view('Catalogos/Clientes/vNewCliente', $data);
	}	

	public function saveNuevoCliente()
	{		
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveNewClient($datos);
	}

	public function saveEditarCliente()
	{		
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveEditClient($datos);	
	}

	public function editClienteDiaZona()
	{		
		$datos = $this->input->post();

		$codigos = $datos["codigos"];
		$idsucursal = $datos["idsucursal"];

		$codigos = explode("|", $codigos);

		foreach($codigos as $codigo)
		{
			$post = array();

			$post["codigo"] = $codigo;
			$post["zona"] = $datos["idzona"];
			$post["diasvisita"] = $datos["dias"];
			$post["sucursal"] = $idsucursal;

			echo $this->CatalogosModel->editClienteDiaZona($post);
		}
	}

	public function clientesMapa()
	{
		VERIFICARSESION();		
		$this->load->view('Catalogos/Clientes/vClientesMapa');
	}

	public function getClienteByCodigo($pCodigo)
	{
		echo json_encode($this->CatalogosModel->getClienteByCodigo($pCodigo));
	}

	public function getListaClientesJsonByZonaDia()
	{
		$zona = $this->input->post("zona");
		$diavisita = $this->input->post("diavisita");

		echo json_encode($this->CatalogosModel->getListaClientesJsonByZonaDia($zona, $diavisita)->result());
	}

	//########## CATALOGOPRODUCTOS ##################

	public function listaProductos()
	{
		VERIFICARSESION();		
		$this->load->view('Catalogos/Productos/vListaProductos');
	}

	public function getListaProductosJson($tipo)
	{
		echo json_encode($this->CatalogosModel->getListaProductos($tipo)->result());
	}

	public function getListaProductosJsonByStatus()
	{
		$post = $this->input->post();
		echo json_encode($this->CatalogosModel->getListaProductosByStatus($post["status"])->result());
	}

	public function getListaProductosInventarioJson($idsucursal)
	{
		echo json_encode($this->CatalogosModel->getListaProductosInventarioJson($idsucursal));
	}

	public function nuevoProducto()
	{
		VERIFICARSESION();
		$data['opcion'] = "nuevo";
		$data['listaProveedores'] = $this->CatalogosModel->getListaProveedoresAll();
		$data['listaClasificacionesProductos'] = $this->CatalogosModel->getListaClasProd();
		$data['listatipopaquetes'] = $this->CatalogosModel->getTipoPaquetes();
		$data['listamarcas'] = $this->CatalogosModel->getMarcas();
		$data['listaunidadesmedida'] = $this->CatalogosModel->getUnidadesMedida();

		$this->load->view('Catalogos/Productos/vNewProducto',$data);
	}

	public function saveNuevoProducto()
	{
		$datos = $this->input->post();
		$id = $this->CatalogosModel->saveNewProducto($datos);

		/*if(!isset($datos["tipo2"]))
		{
			if(is_numeric($id))
			{
				$this->BeesModel->postItem($id, GETEMPRESA());
			}
		}*/

		echo $id;
	}

	public function saveComponentesPaquete()
	{
		$datos = $this->input->post();
		
		$filas = count($datos["componentes"][0]) / 5;
		$componentes = [];
		$y=0;
		for($x=1; $x<=$filas; $x++)
		{
			$componente = array();
			$componente["idpaquete"] = $datos["id"];
			$componente["idproducto"] = $datos["componentes"][0][$y];
			$y=$y+1;
			$componente["codigo"] = $datos["componentes"][0][$y];
			$y=$y+2;
			$componente["cantidad"] = $datos["componentes"][0][$y];
			$y=$y+2;

			array_push($componentes, $componente);
		}

		//print_r($componentes); die();

		$filas = count($datos["componentesbees"][0]) / 6;
		$componentesbees = [];
		$y=0;
		for($x=1; $x<=$filas; $x++)
		{
			$componente = array();
			$componente["idpaquete"] = $datos["id"];
			$componente["idproducto"] = $datos["componentesbees"][0][$y];
			$y=$y+1;
			$componente["codigo"] = $datos["componentesbees"][0][$y];
			$y=$y+2;
			$componente["tipo"] = $datos["componentesbees"][0][$y];
			$y=$y+1;
			$componente["cantidad"] = $datos["componentesbees"][0][$y];
			$y=$y+2;

			array_push($componentesbees, $componente);
		}

		//print_r($componentesbees); die();

		echo $this->CatalogosModel->saveComponentesPaquete($componentes, $componentesbees);
	}

	public function savePaquetesSucursal()
	{
		$datos = $this->input->post();

		echo $this->CatalogosModel->savePaquetesSucursal($datos["items_sucursal"]);
	}

	public function savePaquetesAudiencia()
	{
		$datos = $this->input->post();

		echo $this->CatalogosModel->savePaquetesAudiencia($datos);
	}

	public function editarProducto($id)
	{
		VERIFICARSESION();
		$producto = $this->CatalogosModel->getProducto($id)->row();
		$data['opcion'] = "editar";
		$data['producto'] = $producto;
		$data['listaProveedores'] = $this->CatalogosModel->getListaProveedoresAll();
		$data['listaClasificacionesProductos'] = $this->CatalogosModel->getListaClasProd();
		$data['listatipopaquetes'] = $this->CatalogosModel->getTipoPaquetes();
		$data['listamarcas'] = $this->CatalogosModel->getMarcas();
		$data['listaunidadesmedida'] = $this->CatalogosModel->getUnidadesMedida();

		$this->load->view('Catalogos/Productos/vNewProducto', $data);
	}

	public function verProducto($id)
	{
		VERIFICARSESION();
		$producto = $this->CatalogosModel->getProducto($id)->row();
		$data['opcion'] = "ver";
		$data['producto'] = $producto;
		$data['listaProveedores'] = $this->CatalogosModel->getListaProveedoresAll();
		$data['listaClasificacionesProductos'] = $this->CatalogosModel->getListaClasProd();
		$data['listatipopaquetes'] = $this->CatalogosModel->getTipoPaquetes();
		$data['listamarcas'] = $this->CatalogosModel->getMarcas();
		$data['listaunidadesmedida'] = $this->CatalogosModel->getUnidadesMedida();
		$this->load->view('Catalogos/Productos/vNewProducto', $data);
	}

	//########## CATALOGOPAQUETES ##################

	public function listaPaquetes()
	{
		VERIFICARSESION();		
		$this->load->view('Catalogos/Paquetes/vListaPaquetes');
	}

	public function nuevoPaquete()
	{
		VERIFICARSESION();
		$data['opcion'] = "nuevo";
		$data['listaProveedores'] = $this->CatalogosModel->getListaProveedoresAll();
		$data['listaClasificacionesProductos'] = $this->CatalogosModel->getListaClasProd();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$data["listaProductos"] = $this->CatalogosModel->getListaProductos();
		$data['listamarcas'] = $this->CatalogosModel->getMarcas();
		$this->load->view('Catalogos/Paquetes/vNewPaquete',$data);
	}

	public function editarPaquete($id)
	{
		VERIFICARSESION();
		$producto = $this->CatalogosModel->getProducto($id)->row();
		$data['opcion'] = "editar";
		$data['producto'] = $producto;
		$data['componentes'] = $this->CatalogosModel->getComponentesPaquete($id);
		$data['componentesbees'] = $this->CatalogosModel->getComponentesPaqueteBees($id);
		$data['sucursales'] = $this->CatalogosModel->getSucursalesPaquetes($id);
		$data['listaProveedores'] = $this->CatalogosModel->getListaProveedoresAll();
		$data['listaClasificacionesProductos'] = $this->CatalogosModel->getListaClasProd();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$data["listaProductos"] = $this->CatalogosModel->getListaProductos();
		$data['listamarcas'] = $this->CatalogosModel->getMarcas();
		$this->load->view('Catalogos/Paquetes/vNewPaquete', $data);
	}

	public function verPaquete($id)
	{
		VERIFICARSESION();
		$producto = $this->CatalogosModel->getProducto($id)->row();
		$data['opcion'] = "ver";
		$data['producto'] = $producto;
		$data['componentes'] = $this->CatalogosModel->getComponentesPaquete($id);
		$data['componentesbees'] = $this->CatalogosModel->getComponentesPaqueteBees($id);
		$data['sucursales'] = $this->CatalogosModel->getSucursalesPaquetes($id);
		$data['listaProveedores'] = $this->CatalogosModel->getListaProveedoresAll();
		$data['listaClasificacionesProductos'] = $this->CatalogosModel->getListaClasProd();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$data["listaProductos"] = $this->CatalogosModel->getListaProductos();
		$data['listamarcas'] = $this->CatalogosModel->getMarcas();
		$this->load->view('Catalogos/Paquetes/vNewPaquete', $data);
	}

	public function deshabilitarPaquetes()
	{
		echo $this->CatalogosModel->deshabilitarPaquetes();
	}

	//########## CATALOGOPROVEEDORES ##################

	public function listaProveedor()
	{
		VERIFICARSESION();		
		$this->load->view('Catalogos/Proveedores/vListaProveedores');
	}

	public function getListaProveedoresJson()
	{
		echo json_encode($this->CatalogosModel->getListadoProveedores()->result());
	}

	public function nuevoProveedor()
	{
		VERIFICARSESION();
		$data['opcion'] = "nuevo";
		$this->load->view('Catalogos/Proveedores/vNewProveedor', $data);
	}

	public function saveNuevoProveedor()
	{
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveNewProveedor($datos);
	}

	public function editarProveedor($id)
	{
		VERIFICARSESION();
		$proveedor = $this->CatalogosModel->getProveedorById($id)->row();
		$data['opcion'] = "editar";
		$data['proveedor'] = $proveedor;
		$this->load->view('Catalogos/Proveedores/vNewProveedor',$data);
	}

	public function verProveedor($id)
	{
		VERIFICARSESION();
		$proveedor = $this->CatalogosModel->getProveedorById($id)->row();
		$data['opcion'] = "ver";
		$data['proveedor'] = $proveedor;
		$this->load->view('Catalogos/Proveedores/vNewProveedor',$data);
	}

	//########## CATALOGOCATEGORIAS ##################

	public function listaCategorias()
	{
		VERIFICARSESION();		
		$this->load->view('Catalogos/Categorias/vListaCategorias');
	}

	public function getListaCategoriasJson()
	{
		echo json_encode($this->CatalogosModel->getListadoCategorias()->result());
	}

	public function nuevaCategoria()
	{
		VERIFICARSESION();
		$data['opcion'] = "nuevo";
		$data['listaProveedores']=$this->CatalogosModel->getListaProveedoresAll();
		$this->load->view('Catalogos/Categorias/vNewCategoria', $data);
	}

	public function saveNuevaCategoria()
	{
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveNewCategoria($datos);
	}

	public function editarCategoria($id)
	{
		VERIFICARSESION();
		$data['opcion'] = "editar";
		$categoria = $this->CatalogosModel->getCategoriaById($id)->row();
		$data['listaProveedores'] = $this->CatalogosModel->getListaProveedoresAll();		
		$data["categoria"] = $categoria;
		$this->load->view('Catalogos/Categorias/vNewCategoria', $data);
	}

	public function verCategoria($id)
	{
		VERIFICARSESION();
		$data['opcion'] = "ver";
		$categoria = $this->CatalogosModel->getCategoriaById($id)->row();
		$data['listaProveedores'] = $this->CatalogosModel->getListaProveedoresAll();
		$data["categoria"] = $categoria;
		$this->load->view('Catalogos/Categorias/vNewCategoria', $data);
	}

	//########## CATALOGOMARCAS ##################

	public function listaMarcas()
	{
		VERIFICARSESION();		
		$this->load->view('Catalogos/Marcas/vListaMarcas');
	}

	public function getListaMarcasJson()
	{
		echo json_encode($this->CatalogosModel->getListadoMarcas()->result());
	}

	public function nuevaMarca()
	{
		VERIFICARSESION();
		$data['opcion'] = "nuevo";
		$this->load->view('Catalogos/Marcas/vNewMarca', $data);
	}

	public function saveNuevaMarca()
	{
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveNewMarca($datos);
	}

	public function editarMarca($id)
	{
		VERIFICARSESION();
		$data['opcion'] = "editar";
		$data["categoria"] = $this->CatalogosModel->getMarcaById($id)->row();
		$this->load->view('Catalogos/Marcas/vNewMarca', $data);
	}

	public function verMarca($id)
	{
		VERIFICARSESION();
		$data['opcion'] = "ver";
		$data["categoria"] = $this->CatalogosModel->getMarcaById($id)->row();
		$this->load->view('Catalogos/Marcas/vNewMarca', $data);
	}

	//########## CATALOGOCLASIFICACIONCLIENTES ##################

	public function listaClasificacionClientes()
	{
		VERIFICARSESION();		
		$this->load->view('Catalogos/ClasificacionClientes/vListaCategorias');
	}

	public function getListaClasificacionClientesJson()
	{
		echo json_encode($this->CatalogosModel->getListadoClasificacionClientes()->result());
	}

	public function nuevaClasificacionCliente()
	{
		VERIFICARSESION();
		$data['opcion'] = "nuevo";
		$this->load->view('Catalogos/ClasificacionClientes/vNewCategoria', $data);
	}

	public function saveNuevaClasificacionCliente()
	{
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveNewClasificacionCliente($datos);
	}

	public function editarClasificacionCliente($id)
	{
		VERIFICARSESION();
		$data['opcion'] = "editar";
		$clasificacion_cliente = $this->CatalogosModel->getClasificacionClienteById($id)->row();
		$data["clasificacion_cliente"] = $clasificacion_cliente;
		$this->load->view('Catalogos/ClasificacionClientes/vNewCategoria', $data);
	}

	public function verClasificacionCliente($id)
	{
		VERIFICARSESION();
		$data['opcion'] = "ver";
		$clasificacion_cliente = $this->CatalogosModel->getClasificacionClienteById($id)->row();
		$data["clasificacion_cliente"] = $clasificacion_cliente;
		$this->load->view('Catalogos/ClasificacionClientes/vNewCategoria', $data);
	}

	//########## CATALOGOSUCURSALES ##################

	public function listaSucursales()
	{
		VERIFICARSESION();
		$this->load->view('Catalogos/Sucursales/vListaSucursales');
	}

	public function getListaSucursalesJson()
	{
		echo json_encode($this->CatalogosModel->getListaSucursalesAll()->result());
	}

	public function nuevaSucursal()
	{
		VERIFICARSESION();
		$data['opcion'] = "nuevo";
		$data['listaProveedores'] = $this->CatalogosModel->getListaProveedoresAll();
		$this->load->view('Catalogos/Sucursales/vNewSucursal', $data);
	}

	public function saveNuevaSucursal()
	{
		$datos=$this->input->post();
		echo $this->CatalogosModel->saveNewSucursal($datos);			
	}

	public function editarSucursal($id)
	{
		VERIFICARSESION();		
		$data['opcion'] = "editar";
		$data["sucursal"] = $this->CatalogosModel->getDatosSucursal($id)->row();
		$data['listaProveedores']=$this->CatalogosModel->getListaProveedoresAll();
		$this->load->view('Catalogos/Sucursales/vNewSucursal', $data);
	}

	public function verSucursal($id)
	{
		VERIFICARSESION();
		$data['opcion'] = "ver";
		$data["sucursal"] = $this->CatalogosModel->getDatosSucursal($id)->row();
		$data['listaProveedores']=$this->CatalogosModel->getListaProveedoresAll();
		$this->load->view('Catalogos/Sucursales/vNewSucursal', $data);
	}

	//########## CATALOGORUTAS ##################

	public function listaRutas()
	{
		VERIFICARSESION();
		$this->load->view('Catalogos/Rutas/vListaRutas');
	}

	public function getListaRutasJson()
	{
		$idsucursal = $this->input->post("idsucursal");
		echo json_encode($this->CatalogosModel->getListaRutas($idsucursal));
	}

	public function getListaRutasBySucursalJson($idsucursal)
	{
		echo json_encode($this->CatalogosModel->getRutasBySucursal($idsucursal)->result_array());
	}

	public function nuevaRuta()
	{
		VERIFICARSESION();
		$data["opcion"] = "nuevo";
		$data["listaZonas"] = $this->CatalogosModel->getListaZonasAll();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$this->load->view('Catalogos/Rutas/vNewRuta', $data);
	}

	public function saveNuevaRuta()
	{
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveNewRuta($datos);		
	}

	public function editarRuta($id)
	{
		VERIFICARSESION();
		$data["opcion"] = "editar";
		$data["listaSucursales"]=$this->CatalogosModel->getListaSucursales();
		
		/*$datosRutas=$this->CatalogosModel->getDatosRuta($id);
		$datosZonas=$this->CatalogosModel->getZonasRuta($id);*/

		$data["ruta"] = $this->CatalogosModel->getDatosRuta($id)->row();
		$this->load->view('Catalogos/Rutas/vNewRuta', $data);
	}

	public function verRuta($id)
	{
		$data["opcion"] = "ver";
		$data["listaSucursales"]=$this->CatalogosModel->getListaSucursales();
		
		/*$datosRutas=$this->CatalogosModel->getDatosRuta($id);
		$datosZonas=$this->CatalogosModel->getZonasRuta($id);*/

		$data["ruta"] = $this->CatalogosModel->getDatosRuta($id)->row();
		$this->load->view('Catalogos/Rutas/vNewRuta', $data);
	}

	//########## CATALOGOZONAS ##################

	public function listaZonas()
	{
		VERIFICARSESION();
		$this->load->view('Catalogos/Zonas/vListaZonas');
	}

	public function getListaZonasJson()
	{
		$idsucursal = $this->input->post("idsucursal");
		echo json_encode($this->CatalogosModel->getZonaCatalogo($idsucursal)->result());
	}

	public function nuevaZona()
	{
		VERIFICARSESION();
		$data["opcion"] = "nuevo";
		$data["listaSucursales"]=$this->CatalogosModel->getListaSucursales();
		//$data["listaPoligonos"]=$this->CatalogosModel->getListaPoligonos($data["datosZonas"]->row()->poligono);
		$this->load->view('Catalogos/Zonas/vNewZona', $data);
	}

	public function saveNuevaZona()
	{
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveNewZona($datos);
	}

	public function editarZona($id)
	{
		VERIFICARSESION();
		$data["opcion"] = "editar";
		$data["zona"] = $this->CatalogosModel->getDatosZonas($id)->row();
		$data["listaSucursales"]=$this->CatalogosModel->getListaSucursales();
		//$data["listaPoligonos"]=$this->CatalogosModel->getListaPoligonos($data["datosZonas"]->row()->poligono);		

		$this->load->view('Catalogos/Zonas/vNewZona', $data);
	}

	public function verZona($id)
	{
		VERIFICARSESION();
		$data["opcion"] = "ver";
		$data["zona"] = $this->CatalogosModel->getDatosZonas($id)->row();
		$data["listaSucursales"]=$this->CatalogosModel->getListaSucursales();
		//$data["listaPoligonos"]=$this->CatalogosModel->getListaPoligonos($data["datosZonas"]->row()->poligono);		

		$this->load->view('Catalogos/Zonas/vNewZona', $data);
	}

	//########## CATALOGOPROMOCIONES ##################

	public function listaPromociones()
	{
		VERIFICARSESION();		
		$this->load->view('Catalogos/Promociones/vListaPromociones');
	}

	public function getListaPromocionesJson()
	{
		echo json_encode($this->CatalogosModel->getListaPromociones()->result());
	}

	public function nuevaPromocion()
	{
		VERIFICARSESION();
		$data["opcion"] = "nuevo";
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$this->load->view('Catalogos/Promociones/vNewPromocion', $data);
	}

	public function saveNuevaPromocion()
	{
		$datos = $this->input->post();
		echo $this->CatalogosModel->saveNewPromocion($datos);		
	}

	public function editarPromocion($id)
	{
		VERIFICARSESION();
		$data['opcion'] = "editar";
		$data['promocion'] = $this->CatalogosModel->getPromocion($id)->row();
		$data['detalle'] = $this->CatalogosModel->getPromocionDetalle($id)->result();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$this->load->view('Catalogos/Promociones/vNewPromocion', $data);
	}

	public function verPromocion($id)
	{
		VERIFICARSESION();
		$data['opcion'] = "ver";
		$data['promocion'] = $this->CatalogosModel->getPromocion($id)->row();
		$data['detalle'] = $this->CatalogosModel->getPromocionDetalle($id)->result();
		$data["listaSucursales"] = $this->CatalogosModel->getListaSucursales();
		$this->load->view('Catalogos/Promociones/vNewPromocion', $data);
	}

	//########## CATALOGOREPARTO_RUTAS ##################

	public function listaRepartoRutas()
	{
		VERIFICARSESION();
		$this->load->view('Catalogos/RepartoRutas/vListaRepartoRutas');
	}

	public function getListaRepartoRutasJson($idsucursal)
	{
		echo json_encode($this->CatalogosModel->getListaRepartoRutasJson($idsucursal));
	}

	public function getListaRepartoRutasAsignadasJson($idusuario)
	{
		echo json_encode($this->CatalogosModel->getListaRepartoRutasAsignadasJson($idusuario)->result());
	}

	public function getListaRepartoRutasDisponiblesJson($idsucursal)
	{
		echo json_encode($this->CatalogosModel->getListaRepartoRutasDisponiblesJson($idsucursal)->result());
	}

	public function asignacionRepartoRutas()
	{
		$datos = $this->input->post();

		echo $this->CatalogosModel->asignacionRepartoRutas($datos);
	}

	//########## CATALOGOINVENTARIO ##################

	public function listaInventario()
	{
		VERIFICARSESION();

		$data["vista"] = $this->uri->segment(1, 0);

		$this->load->view('Catalogos/Inventario/vListaInventario', $data);
	}

	public function getListaInventarioJson()
	{
		$idsucursal = $this->input->post("idsucursal");
		$post = $this->input->post();

		echo json_encode($this->CatalogosModel->getListaInventario($post));
	}

/*INICIA SECCION DE CATEGORIAS*/
	
	
	
	
public function saveEditarCategoria(){
			$datos = $this->input->post();
			$this->CatalogosModel->saveEditCategoria($datos);
		
			//$link=CCATALOGOS('listaCategorias');
			redirect(CCATALOGOS('listaCategorias'),'refresh');
}
/*TERMINA SECCION DE CATEGORIAS*/


/*Inicia seccion de proveedores*/





public function saveEditarProveedor(){
			$datos = $this->input->post();
			$this->CatalogosModel->saveEditProveedor($datos);
			redirect(CCATALOGOS('listaProveedor'),'refresh');
}

public function saveEditarProducto(){
			$datos = $this->input->post();
			$this->CatalogosModel->saveEditProducto($datos);
			redirect(CCATALOGOS('listaProductos'),'refresh');
}

/*Termina seccion de productos*/

			/*INICIA SECCION DE CLIENTES*/

	public function actualizarAsSucCli(){
		$this->CatalogosModel->getActualizarAsSucCli();
	}
	public function actualizarFechaClie(){
		$this->CatalogosModel->getActualizarFecha();
	}
	
	public function listaClientesTest(){
		VERIFICARSESION();
		$data['titulo']="hola";
		$data['cantidad']=$this->CatalogosModel->getListaClientes()->num_rows();
		$this->load->view('Catalogos/vListaClientesTest',$data);
	}
	public function jsonListaClientes(){
		$lista=$this->CatalogosModel->getJsonListaClientes();
		$resultado=json_encode($lista);
		echo $resultado;
	}
	public function verMapaCliente($id){
		//$sesion=VERIFICARSESION();
		VERIFICARSESION();
		$lista=$this->CatalogosModel->getCoordenadasCliente($id);
		foreach ($lista->result() as $kL) {
			$data["codigo"]=$kL->codigo;
			$data["descripcion"]=$kL->nombre;
			$data["latitud"]=$kL->latitud;
			$data["longitud"]=$kL->longitud;
			$data["domicilio"]=$kL->direccion;
			$data["ciudad"]=$kL->ciudad;
		}
		$this->load->view('Catalogos/vListaClientesMapa',$data);
	}

		
	public function obtenerPoligono()
	{
		$idZ=$_POST['zona'];
		$poligono=$this->CatalogosModel->getPoligonoZona($idZ);
			$poligonoDatos=$this->CatalogosModel->getPoligono($poligono);
			foreach ($poligonoDatos->result() as $k) {
				# code...
				$color=$k->color;
				$coordenadas=$k->coordenadas;
			}
			echo $color."/".$coordenadas;
	}
		
	public function createComboZona(){
		$cadena='<option value=0 disabled selected>(Selecciona)</option>';
		$idSucursal=$_POST['sucursal'];
		//$idSucursal=1;
		$listaZonas=$this->CatalogosModel->getListaZonas2($idSucursal);
		foreach ($listaZonas->result() as $k) {
			$cadena.="<option value=".$k->id.">".$k->zona."</option>";
		}
		echo $cadena;
	}
	public function createComboProveedores(){
		$cadena='';
		$idSucursal=$_POST['sucursal'];
		$listaProveedores=$this->CatalogosModel->getListaProveedores($idSucursal);
		foreach ($listaProveedores->result() as $k) {
			$cadena.="<option value=".$k->id.">".$k->nombre."</option>";
		}
		echo $cadena;
	}
	public function createComboProveedoresX(){
		$cadena='';
		$idSucursal=$_POST['sucursal'];
		$proveedor=$_POST['proveedor'];
		$listaProveedores=$this->CatalogosModel->getListaProveedores($idSucursal);
		foreach ($listaProveedores->result() as $k) {
			if($proveedor==$k->id){
				$cadena.="<option value=".$k->id." selected>".$k->nombre."</option>";
			}
			else{
				$cadena.="<option value=".$k->id.">".$k->nombre."</option>";
			}
			
		}
		echo $cadena;
	}
	public function probarNuevoCliente(){

		GUARDAR_CLIENTEPROVEDOR_NUEVO(9999,9999,1,"2018-07-23 15:38:11");
	}
		
	public function nuevoClienteX(){
		//$sesion=VERIFICARSESION();
			VERIFICARSESION();
		
			$data["listaZonas"]=$this->CatalogosModel->getListaZonasAll();
			$data["listaSucursales"]=$this->CatalogosModel->getListaSucursales();
			//$poligono=$this->CatalogosModel->getPoligonoZona($kL->zona);
			//$data["poligonoDatos"]=$this->CatalogosModel->getPoligono($poligono);
			/*
			$data["codigo"]=$kL->codigo;
			$data["descripcion"]=$kL->descripcion;
			$data["latitud"]=$kL->latitud;
			$data["longitud"]=$kL->longitud;
			$data["domicilio"]=$kL->direccion;
			$data["ciudad"]=$kL->ciudad;
			$data["cp"]=$kL->cp;
			$data["zona"]=$kL->zona;
			$data["ciudad"]=$kL->ciudad;
			$data["telefono"]=$kL->telefono;
			$data["email"]=$kL->email;
			$data["encargado"]=$kL->encargado;
			$data["esmovil"]=$kL->esclientemovil;
			$data["esprospecto"]=$kL->esprospecto;
			$data["comentarios"]=$kL->comentarios;*/

		
		$this->load->view('Catalogos/vNewClienteX',$data);
	}
	
		
		public function saveNuevoClienteX(){
			//print_r($_POST);
			$datos = $this->input->post();
			$this->CatalogosModel->saveNewClient($datos);
			redirect(CHOME(),'refresh');
			
		}



					/* TERMINA SECCION DE CLIENTES*/



					/*INICIA SECCION DE RUTAS*/
					
		

		
		public function nuevaRutaX(){
			VERIFICARSESION();
			$data["listaZonas"]=$this->CatalogosModel->getListaZonasAll();
			$data["listaSucursales"]=$this->CatalogosModel->getListaSucursales();
			$this->load->view('Catalogos/Rutas/vNewRutaX',$data);

		}
		
		public function saveNuevaRutaX(){
			$datos=$this->input->post();
			$this->CatalogosModel->saveNewRuta($datos);
			
			redirect(CCATALOGOS("listaRutas"),'refresh');
		}
		
		public function saveEditarRuta(){
			//print_r($_POST);
			$datos = $this->input->post();
			$this->CatalogosModel->saveEditRuta($datos);
			redirect(CCATALOGOS('listaRutas'),'refresh');	
		}
		

					/*TERMINA SECCION DE RUTAS*/
					/*EMPIEZA SECCION DE USUARIOS*/
	public function liberaEquipo(){
		$id=$_POST['id'];
		$cons=$this->CatalogosModel->liberarEquipo($id);
		echo $cons;
	}

					/*TERMINA SECCION DE USUARIOS*/

					/*COMIENZA SECCION DE SUCURSALES*/

	
	
	
	public function saveEditarSucursal(){
			$datos=$this->input->post();
			$this->CatalogosModel->saveEditSucursal($datos);
			redirect(CCATALOGOS('listaSucursales'),'refresh');
		}	

					/*TERMINA SECCION DE SUCURSALES*/
					/*INICIA SECCION ZONA*/	

					/*TERMINA SECCION ZONA*/
					/*AJAX*/

	public function createComboZona2(){
		$cadena='';
		$idSucursal=$_POST['sucursal'];
		//$idSucursal=1;
		$listaZonas=$this->CatalogosModel->getListaZonas2($idSucursal);
		foreach ($listaZonas->result() as $k) {
			$cadena.="<option value=".$k->id.">".$k->zona."</option>";
		}
		echo $cadena;
	}
	public function createComboZona2X(){
		$cadena='';
		$idSucursal=$_POST['sucursal'];
		$zona=$_POST['zona'];

		//$idSucursal=1;
		$listaZonas=$this->CatalogosModel->getListaZonas2($idSucursal);
		foreach ($listaZonas->result() as $k) {
			if($zona==$k->id){
				$cadena.="<option value=".$k->id." selected>".$k->zona."</option>";
			}
			else{
				$cadena.="<option value=".$k->id.">".$k->zona."</option>";
			}
			
		}
		echo $cadena;
	}

	public function obtenerPoligono2()
	{
		$zonas=$_POST['zona'];
		$arrayZonas=explode(",", $zonas);
		$cantidadZonas=count($arrayZonas);
		$cadena="";

		for ($i=0; $i < $cantidadZonas; $i++) { 

			$poligono = $this->CatalogosModel->getPoligonoZona($arrayZonas[$i]);
			if($poligono != "0")
			{
				$poligonoDatos = $this->CatalogosModel->getPoligono($poligono);
				foreach ($poligonoDatos->result() as $k)
				{
					$color=$k->color;
					$coordenadas=$k->coordenadas;
					if($i!=0){
						$cadena.="&".$color."/".$coordenadas."/".$arrayZonas[$i];
					}
					else{
						$cadena.=$color."/".$coordenadas."/".$arrayZonas[$i];
					}
				}
			}
		}
		
		echo $cadena;
	}

	public function obtenerPoligono3(){
		$zonas=$_POST['zona'];
		
		$cadena="";
		
		

			$poligono=$this->CatalogosModel->getPoligonoZona($zonas);
			$poligonoDatos=$this->CatalogosModel->getPoligono($poligono);
			foreach ($poligonoDatos->result() as $k) {
				# code...
				$color=$k->color;
				$coordenadas=$k->coordenadas;
				
					$cadena.=$color."/".$coordenadas."/".$zonas;
				
			}

		
		
			echo $cadena;
	}
	public function obtenerPoligonoEspecifico(){
		$poligono=$_POST['poligono'];
		$cadena="";
		$poligonoDatos=$this->CatalogosModel->getPoligono($poligono);
		foreach ($poligonoDatos->result() as $k) {
			# code...
			$color=$k->color;
				$coordenadas=$k->coordenadas;
				
					$cadena.=$color."/".$coordenadas;
		}
		echo $cadena;
	}
	public function obtenerMarcadoresZonaCiclo(){
		$zona=$_POST['zona'];
		$proveedor=$_POST['proveedor'];
		/*$zona="7";
		$proveedor="1,2";*/

		$cadena="";
		$cantidad=0;
		$zonas=explode(",", $zona);
		$contador=0;
		$nZonas=count($zonas);
		for ($i=0; $i < $nZonas; $i++) { 
			# code...
			if($proveedor!=""){
				$listaCoordenadas=$this->CatalogosModel->getCoordenadasClientes2($zonas[$i],$proveedor);
				//echo $listaCoordenadas;
			}
			else{
				$listaCoordenadas=$this->CatalogosModel->getCoordenadasClientes($zonas[$i]);
			}
			
			$cantidad=$cantidad+$listaCoordenadas->num_rows();
			if($listaCoordenadas->num_rows()==0){
				if($i==0){
					$cadena.="0/0/0/0";
				}
				else{
					$cadena.="%0/0/0/0";
				}
				
			}
			else{
				//$cantidad+=$listaCoordenadas->num_rows();
				
				foreach ($listaCoordenadas->result() as $kCoord) {
						if($contador==0){

							$cadena.=$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud;
						}
						else{
							$cadena.="%".$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud;
						}
						$contador=$contador+1;
										
				}
			}
		}
		//print_r($zonas);
		//echo $listaCoordenadas;
		echo $cantidad."&".$cadena;
	}
		public function obtenerMarcadoresZona2(){
		$zona=$_POST['zona'];
		
		/*$zona="7";
		$proveedor="1,2";*/

		$cadena="";
		$cantidad=0;
		$zonas=explode(",", $zona);
		$contador=0;
		$nZonas=count($zonas);
		
			# code...
		
				$listaCoordenadas=$this->CatalogosModel->getCoordenadasClientes($zona);
				//print_r($listaCoordenadas->result());
		
			$cantidad=$cantidad+$listaCoordenadas->num_rows();
			if($listaCoordenadas->num_rows()==0){
				if($i==0){
					$cadena.="0/0/0/0";
				}
				else{
					$cadena.="%0/0/0/0";
				}
				
			}
			else{
				//$cantidad+=$listaCoordenadas->num_rows();

				//print_r($listaCoordenadas->result());
				foreach ($listaCoordenadas->result() as $kCoord) {
						if($contador==0){

							$cadena.=$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad;
						}
						else{
							$cadena.="%".$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad;
						}
						$contador=$contador+1;
										
				}
			}
		
		//print_r($zonas);
		//echo $listaCoordenadas;
		echo $cantidad."&".$cadena;
	}
	public function obtenerMarcadoresZona(){
		$zona=$_POST['zona'];
		$proveedor=$_POST['proveedor'];
		/*$zona="7";
		$proveedor="1,2";*/

		$cadena="";
		$cantidad=0;
		$zonas=explode(",", $zona);
		$contador=0;
		$nZonas=count($zonas);
		
			# code...
			/*if($proveedor!=""){
				$listaCoordenadas=$this->CatalogosModel->getCoordenadasClientes2($zona,$proveedor);
				//echo $listaCoordenadas;
			}
			else{
				for ($i=0; $i < $nZonas; $i++)
				{
					$listaCoordenadas=$this->CatalogosModel->getCoordenadasClientes($zonas[$i]);
				}				
			}*/

			$listaCoordenadas=$this->CatalogosModel->getCoordenadasClientes($zona);
			
			$cantidad=$cantidad+$listaCoordenadas->num_rows();
			if($listaCoordenadas->num_rows()==0){
				if($cantidad==0){
					$cadena.="0/0/0/0";
				}
				else{
					$cadena.="%0/0/0/0";
				}
				
			}
			else{
				//$cantidad+=$listaCoordenadas->num_rows();
				
				foreach ($listaCoordenadas->result() as $kCoord) {
						if($contador==0){

							$cadena.=$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad;
						}
						else{
							$cadena.="%".$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad;
						}
						$contador=$contador+1;
										
				}
			}
		
		//print_r($zonas);
		//echo $listaCoordenadas;
		echo $cantidad."&".$cadena;
	}

	public function createComboAgente()
	{
		$cadena='<option value=0 selected>(Selecciona Agente)</option>';
		$idSucursal = $_POST['sucursal'];
		$empresa = GETEMPRESA();
		$listaAgentes=$this->CatalogosModel->getListaEmpleados($idSucursal, $empresa);
		foreach ($listaAgentes->result() as $k) {
			$cadena.="<option value=".$k->id.">".$k->nombre."</option>";
		}
		echo $cadena;
	}

	public function createComboReparto()
	{
		$cadena='<option value=0 selected>(Selecciona Reparto)</option>';
		$idSucursal = $_POST['sucursal'];
		$empresa = GETEMPRESA();
		$listaAgentes=$this->CatalogosModel->getListaRepartidores($idSucursal, $empresa);
		foreach ($listaAgentes->result() as $k) {
			$cadena.="<option value=".$k->id.">".$k->nombre."</option>";
		}
		echo $cadena;
	}

	public function createComboRutas()
	{
		$cadena='<option value=0 selected>(Selecciona una ruta)</option>';
		$idSucursal = $_POST['sucursal'];
		$empresa = GETEMPRESA();
		$listaAgentes = $this->CatalogosModel->getListaRutas($idSucursal);
		foreach ($listaAgentes as $k) {
			echo $k['sucursal'];
			if($k['sucursal']==$idSucursal)
				$cadena.="<option value=".$k['id'].">".$k['ruta']."</option>";
		}
		echo $cadena;
	}

	public function createComboZonas()
	{
		$cadena='<option value=0 selected disabled>SELECCIONE UNA ZONA</option>';
		$idSucursal = $_POST['sucursal'];
		$empresa = GETEMPRESA();
		$listaAgentes = $this->CatalogosModel->getListaZonas2($idSucursal)->result_array();

		foreach ($listaAgentes as $k) 
		{
			$cadena.="<option value=".$k['id'].">".$k['zona']."</option>";
		}

		echo $cadena;
	}

	public function createComboRutasUsuarios()
	{
		$cadena='<option value=0 selected>(Selecciona una ruta)</option>';
		$idSucursal = $_POST['sucursal'];
		$empresa = GETEMPRESA();
		$listaAgentes = $this->CatalogosModel->getListaRutas($idSucursal);
		foreach ($listaAgentes as $k) {
			echo $k['sucursal'];
			if($k['sucursal']==$idSucursal)
				$cadena.="<option value=".$k['chofer'].">".$k['ruta'].' - '.$k['nombre_chofer']."</option>";
		}
		echo $cadena;
	}

	public function createComboRutasUsuariosRuta()
	{
		$cadena='<option value=0 selected>(Selecciona una ruta)</option>';
		$idSucursal = $_POST['sucursal'];
		$empresa = GETEMPRESA();
		$listaAgentes = $this->CatalogosModel->getListaRutas($idSucursal);
		foreach ($listaAgentes as $k) {
			echo $k['sucursal'];
			if($k['sucursal']==$idSucursal)
				$cadena.="<option value=".$k['id'].">".$k['ruta'].' - '.$k['nombre_chofer']."</option>";
		}
		echo $cadena;
	}

	public function createComboAgenteX(){
			$cadena='<option value=0 selected>(Selecciona Agente)</option>';
			$idSucursal=$_POST['sucursal'];
			$empresa = GETEMPRESA();
			$agente=$_POST['agente'];			
			$listaAgentes=$this->CatalogosModel->getListaEmpleados($idSucursal, $empresa);
			foreach ($listaAgentes->result() as $k) {
				if($agente==$k->id){
					$cadena.="<option value=".$k->id." selected>".$k->nombre."</option>";
				}
				else{
					$cadena.="<option value=".$k->id.">".$k->nombre."</option>";
				}
				
			}
			echo $cadena;
	}

	public function verificarCliente()
	{
		$idCliente=$_POST['cliente'];
		$proveedores=$_POST['proveedores'];
		$resultado=$this->CatalogosModel->getVerificaCliente($idCliente);
		return $resultado;
	}

	public function ImportarJson()
	{
		$this->load->view('Catalogos/Productos/importar_json');
	}

	public function sincronizarProductos()
	{
		$json = $this->input->post('json');

		$productos = json_decode($json, true);

		if (json_last_error() !== JSON_ERROR_NONE)
		{
			echo json_encode([
				"status" => false,
				"mensaje" => "JSON inválido"
			]);
			return;
		}

		//CLIENTES
		$resultado = $this->CatalogosModel->sincronizarClientes($productos);

		//PRODUCTOS
		//$resultado = $this->CatalogosModel->sincronizarProductos($productos);

		//ACUMULADOS
		//$resultado = $this->CatalogosModel->importarAcumulados($productos);

		//PEDIDOS
		//$resultado = $this->CatalogosModel->importarVentas($productos);

		//PEDIDOS DETALLE
		//$resultado = $this->CatalogosModel->importarVentasDetalle($productos);

		echo json_encode([
			"status" => true,
			"resultado" => $resultado
		]);
	}
}