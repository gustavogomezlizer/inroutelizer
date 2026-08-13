<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
	public function __construct()
	    {
	        parent::__construct();
			$this->load->helper(array('url','form', 'variables_helper', 'funcioneshtml'));
			$this->load->library(array('session', 'pagination'));
			$this->load->model(array('HomeModel','CatalogosModel','CatalogosAdapModel','ConfigurarModel','ReportesModel'));		
			/*if(!$this->session->userdata('logged_in'))		
			{
				redirect(CWELCOME("login"), 'refresh'); 	
			}*/
			
	    }
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
	public function index()
	{
		$this->principal();
	}
	public function crearReporte(){
		$this->load->view('Reportes/vDescargaReporte');
	}
	public function leerAcumuladosJson(){
		$cadena=$_POST['cadena'];
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

		$this->ReportesModel->getAgregarAcumulados();
		echo "listo";
		
	}
	public function agregarAcumulados($idVendedor){
		$res=$this->ReportesModel->getPruebaAgregarPedido($idVendedor);
		echo $res;
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
	public function verPedido($id){
		VERIFICARSESION();
		$data["datos"]=$this->ReportesModel->getPedido($id);
		$data["poligonoDatos"]="";
		if($data["datos"]->num_rows()!=0){
			$data["idcliente"]=$data["datos"]->row()->idcliente;
			$data["datosCliente"]=$this->ReportesModel->getDatosCliente($data["idcliente"]);
			$data["latitud"]=$data["datos"]->row()->latitud;
			$data["longitud"]=$data["datos"]->row()->longitud;
			$poligono=$this->CatalogosModel->getPoligonoZona($data["datosCliente"]->row()->zona);
			$data["poligonoDatos"]=$this->CatalogosModel->getPoligono($poligono);
			$data["datosPedido"]=$this->ReportesModel->getPedidosDetalle($id);
			$this->load->view('Reportes/vVerPedido',$data);
		}
		else{
			?>
			<script>window.close();</script>
			<?php 
		}
	}
	public function postPorcentajeObtener(){
		$porcentaje=$_POST['porcentaje'];
		echo FORMATO_PORCENTAJEDEC($porcentaje);
	}
	public function verAcciones($idUsuario,$fIni,$fFin){
		VERIFICARSESION();
		//$data["acciones"]=$this->ReportesModel->getPedidosVisitas($idUsuario,$fIni,$fFin);
		$data["idUsuario"]=$idUsuario;
		$data["fIni"]=$fIni;
		$data["fFin"]=$fFin;
		$this->load->view('Reportes/vVerVisitas',$data);
		
		
	}
public function getAcciones(){
		
		$idUsuario=$_POST['idUsuario'];
		$fIni=$_POST['fIni'];
		$fFin=$_POST['fFin'];
		$acciones=$this->ReportesModel->getPedidosVisitas($idUsuario,$fIni,$fFin);
		echo $acciones;
			
		
		
	}
public function getUsuariosJson($usuario="",$clave=""){
		
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
				$usuariosA[$i]['date_created'] =  mktime($hours, $minutes, $seconds, $month, $day, $year)."000";
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
	}
public function getProductosJson($usuario="",$clave=""){
			
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
	}
	public function getClientesJson($usuario="",$clave=""){
			
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
	}
	public function getLiquidacion($usuario="",$clave="",$fIni="1900-01-01",$fFin="1900-01-01"){
		date_default_timezone_set('America/Mazatlan');
		$fecha1=date('Y-m-d');	
		if(($fIni=="1900-01-01") OR ($fFin=="1900-01-01")){
			$fIni=$fecha1;
			$fFin=$fecha1;
		}
		$perfil=$this->HomeModel->inicioSesionLiq($usuario,$clave);
		$validacion=GETACCESOX("Reportes","getLiquidacion",$perfil);
		$principal=array();
		if($validacion!=0){
			
			$principal['error']=false;
			$principal['message']="";

			$pedidos=$this->ReportesModel->getPedidosJ($fIni,$fFin);
			$pedidosA=array();
			$i=0;
			foreach ($pedidos->result() as $k) {
				
				$pedidosA[$i]['id']=$k->folio;
				$itemsA=array();
				$items=$this->ReportesModel->getItemsJ($k->id);
				$cItems=0;

				foreach ($items->result() as $kItems) {
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
					$itemsA[$cItems]['product_description']=$kItems->producto;
					$itemsA[$cItems]['price']=(double)$kItems->precio;
					$itemsA[$cItems]['quantity']=(double)$kItems->cantidad;
					$itemsA[$cItems]['total']=(double)$kItems->importe;
					$itemsA[$cItems]['comments']=null;
					$cItems++;
				}
				$pedidosA[$i]['items']=$itemsA;
				$pedidosA[$i]['customer_code']=$k->codigocliente;
				$pedidosA[$i]['customer_id']=(integer)$k->idcliente;
				$cliente=$this->ReportesModel->getClienteJ($k->idcliente);
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
				//$pedidosA[$i]['date_created']=$k->fechacreacion;
				$to=$k->fechacreacion;
				list($part1,$part2) = explode(' ', $to);
				list($year, $month, $day) = explode('-', $part1);
				list($hours, $minutes,$seconds) = explode(':', $part2);
				$pedidosA[$i]['date_created'] =  (integer)(mktime($hours, $minutes, $seconds, $month, $day, $year)."000");
				//echo $timeto;
				$pedidosA[$i]['total']=(double)$k->total;
				$pedidosA[$i]['price_list']=null;
				$pedidosA[$i]['latitude']=(double)$k->latitud;
				$pedidosA[$i]['longitude']=(double)$k->longitud;
				$pedidosA[$i]['accuracy']=0;
				$pedidosA[$i]['deleted']=false;
				//$pedidosA[$i]['']=$k->;
				$i++;
			}
			$principal['salesOrders']=$pedidosA;
		}
		else{
			$principal['error']=true;
			$principal['message']="Error de Autentificacion";
			$principal['salesOrders']=null;
		}
		$resultado=json_encode($principal);
		echo $resultado;
	}
	public function eliminarPedido(){
		$id=$_POST['id'];
		$consulta=$this->ReportesModel->deletePedidos($id);
		
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
	public function listadoVisitas($fIni="1900-01-01",$fFin="1900-01-01",$ruta="TODOS",$usuario="TODOS",$sucursal="TODOS"){
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
		$data["lista"]=$this->ReportesModel->getVisitas($fIni,$fFin);
		$data["listaUsuarios"]=$this->ReportesModel->getUsuarios();
		$data["listaSucursales"]=$this->ReportesModel->getSucursales();
		$data["listaRutas"]=$this->ReportesModel->getRutas();
		$this->load->view('Reportes/vListaReporteVisitas',$data);

	}
	public function listaCumplimientoAgenda($fIni="1900-01-01",$fFin="1900-01-01",$usuario="TODOS",$ruta="TODOS",$sucursal="TODOS"){
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
		$data["lista"]=$this->ReportesModel->getEfectividadAgenda($fIni,$fFin);
		$data["listaUsuarios"]=$this->ReportesModel->getUsuarios();
		$data["listaSucursales"]=$this->ReportesModel->getSucursales();
		$data["listaRutas"]=$this->ReportesModel->getRutas();
		$this->load->view('Reportes/vListaReporteAgenda',$data);

	}
	public function listaEfectividad($fIni="1900-01-01",$fFin="1900-01-01",$usuario="TODOS",$ruta="TODOS",$sucursal="TODOS"){
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
		$data["lista"]=$this->ReportesModel->getEfectividad($fIni,$fFin);
		$data["listaUsuarios"]=$this->ReportesModel->getUsuarios();
		$data["listaSucursales"]=$this->ReportesModel->getSucursales();
		$data["listaRutas"]=$this->ReportesModel->getRutas();
		$this->load->view('Reportes/vListaReporteEfectividad',$data);
	}
public function imprimirPedido($idpedido){
	VERIFICARSESION();
	$data['idpedido']=$idpedido;
	//echo $idpedido;
	$this->ReportesModel->banderaImpreso($idpedido);
	$pedidodetalle=$this->ReportesModel->getPedidosDetalleId($idpedido);
	//print_r($pedidodetalle->result());
	$data['folio']=$pedidodetalle->row()->folio;
	$data['tipo']=$pedidodetalle->row()->tipo;
	$fechacreacion=$pedidodetalle->row()->fechacreacion;
	$fc=explode(" ", $fechacreacion);
	$data['fecha']=$fc[0];
	$data['hora']=$fc[1];
	$data['total']=$pedidodetalle->row()->total;
	$data['nombreUsuario']=$pedidodetalle->row()->nombreUsuario;
	$data['nombreCliente']=$pedidodetalle->row()->nombreCliente;
	$data['domicilio']=$pedidodetalle->row()->domicilio;
	$data['clienteCiudad']=$pedidodetalle->row()->clienteCiudad;
	$data['clienteEstado']=$pedidodetalle->row()->clienteEstado;
	$pedidodetallado=$this->ReportesModel->getPedidosDetalladosId($idpedido);
	/*echo "<br>";
	print_r($pedidodetallado->result());*/
	$this->load->view('Reportes/imprimir/venta0.php',$data);
}
public function imprimirPedido2(){
	VERIFICARSESION();
	$this->load->view('Reportes/imprimir/venta.php',$this->input->post());
	
}
	public function listadoPedidos($fIni="1900-01-01",$fFin="1900-01-01",$tipo="TODOS",$usuario="TODOS",$sucursal="TODOS",$ruta="TODOS"){
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
		$data["lista"]=$this->ReportesModel->getPedidos($fIni,$fFin);
		$data["listaUsuarios"]=$this->ReportesModel->getUsuarios();
		$data["listaSucursales"]=$this->ReportesModel->getSucursales();
		$data["listaRutas"]=$this->ReportesModel->getRutasPedidos();
		//echo $data["lista"];
		$this->load->view('Reportes/vListaReporteVentas',$data);
		
	}

	public function verPedidos($fIni="1900-01-01",$fFin="1900-01-01",$tipo="TODOS",$usuario="TODOS",$sucursal="TODOS"){
		$data["lista"]=$this->ReportesModel->getListaPedidos($fIni,$fFin,$tipo,$usuario,$sucursal);
		//print_r($data["lista"]->result());
		$this->load->view('Reportes/vVerPedidos',$data);
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
}
