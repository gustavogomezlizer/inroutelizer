<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define("INDEX","index.php/");

function LINKPROYECTO($cadena="")
{
    return base_url().INDEX.$cadena;
}

function VERIFICARSESION(){
    $CI = get_instance();    
    $CI->load->library('session');

    $inicio=0;

    if(!$CI->session->has_userdata('user'))		
    {
        redirect(LINKPROYECTO("Verificacion"), 'refresh');
    }
        
    return $inicio;
}

function VERIFICARPERFILFUNCION($controlador,$funcion,$perfil){
    //$idPerfil=$this->session->userdata('idperfilLIZER');

    //$perfil=$this->session->userdata('perfilLIZER');
    $CI = get_instance();    
    $CI->load->model('HomeModel');
    $bandera=0;
    $nombre=$CI->HomeModel->getVerificarPerfilPermiso($controlador,$funcion);
    if($nombre->num_rows()!=0){
        $perfiles=$nombre->row()->perfiles;
        $cadPerfiles=explode(",", $perfiles);
        $cantPerfiles=count($cadPerfiles);
        for ($i=0; $i < $cantPerfiles; $i++) { 
            if($cadPerfiles[$i]==$perfil){
                $bandera=1;
            }
        }
    }
    return $bandera;
}
function GETPERFILNAME($id){
    $CI = get_instance();    
    $CI->load->model('HomeModel');
    $nombre=$CI->HomeModel->getNamePerfil($id);
    return $nombre;
}
function GETACCESO($controlador,$funcion){
    $CI = get_instance();    
    $CI->load->model('HomeModel');
    $CI->load->library('session');    
    $data=$CI->HomeModel->getAccesosVer($CI->session->userdata("perfil"),$controlador,$funcion);
    if($data==0){
        $bandera=0;
    }
    else {
        $bandera=1;
    }
    return $bandera;
}
function GETACCESOX($controlador,$funcion,$perfil){
    $CI = get_instance();    
    $CI->load->model('HomeModel');
    $CI->load->library('session');    
    $data=$CI->HomeModel->getAccesosVer($perfil,$controlador,$funcion);
    if($data==0){
        $bandera=0;
    }
    else {
        $bandera=1;
    }
    return $bandera;
}
function GETNEWCLIENTENAME($idSucursal){
    $CI=get_instance();
    $CI->load->model('CatalogosModel');
    $CI->load->library('session');    
    $data=$CI->CatalogosModel->getClaveNewCliente($idSucursal);
   
    return $data;
}
function GETELIMINARACENTO($cadena){
    $cadena=str_replace("á", "a", $cadena);
    $cadena=str_replace("é", "e", $cadena);
    $cadena=str_replace("í", "i", $cadena);
    $cadena=str_replace("ó", "o", $cadena);
    $cadena=str_replace("ú", "u", $cadena);
    $cadena=str_replace("Á", "A", $cadena);
    $cadena=str_replace("É", "E", $cadena);
    $cadena=str_replace("Í", "I", $cadena);
    $cadena=str_replace("Ó", "O", $cadena);
    $cadena=str_replace("Ú", "U", $cadena);
    return $cadena;
}
function GETCARACTERESESPECIALES($cadena){
    $cadena=str_replace("%20"," ",$cadena);
    $cadena=str_replace("%C3%A1","á",$cadena);
    $cadena=str_replace("%C3%A9","é",$cadena);
    $cadena=str_replace("%C3%AD","í",$cadena);
    $cadena=str_replace("%C3%B3","ó",$cadena);
    $cadena=str_replace("%C3%BA","ú",$cadena);
    $cadena=str_replace("%C3%A1","ñ",$cadena);
    $cadena=str_replace("%C3%81","Á",$cadena);
    $cadena=str_replace("%C3%89","É",$cadena);
    $cadena=str_replace("%C3%8D","Í",$cadena);
    $cadena=str_replace("%C3%93","Ó",$cadena);
    $cadena=str_replace("%C3%9A","Ú",$cadena);
    $cadena=str_replace("%C3%91","Ñ",$cadena);
    return $cadena;
}
function CCATALOGOS($cadena="")
{
    return base_url().INDEX."Catalogos/".$cadena;
}

function CHOME($cadena="")
{
    return base_url().INDEX."Home/".$cadena;
}

function CCONFIGURAR($cadena="")
{
	return base_url().INDEX."Configurar/".$cadena;
}
function CREPORTES($cadena="")
{
    return base_url().INDEX."Reportes/".$cadena;
}
function CESTADISTICAS($cadena=""){
    return base_url().INDEX."Estadisticas/".$cadena;
}
function RUTAFOLDERASSETS($cadena="")
{
	return base_url()."assets/".$cadena;
}

function LOGOPRINCIPAL()
{
	return RUTAFOLDERASSETS("images/logos/logo_principal.png");
}

function SYSTEM_NAME()
{
	return "IN ROUTE";
}

function switch_db_dinamico($empresa,$movil=0)
{
    if($movil==0){
        $CI_SESSION = get_instance();    
        $CI_SESSION->load->library('session');

        if(!$CI_SESSION->session->has_userdata('user'))		
        {
            redirect(LINKPROYECTO("Login"), 'refresh');
        }
    }
    
    $CI = get_instance();    
    $CI->load->model('HomeModel');
    $conexion = $CI->HomeModel->getConexionEmpresa($empresa);

    //print_r($conexion);die();
    
    $config_app['hostname'] = $conexion->db_domain;
    $config_app['username'] = $conexion->db_user;
    $config_app['password'] = $conexion->db_password;
    $config_app['database'] = $conexion->db_database;
    $config_app['dbdriver'] = 'mysqli';
    $config_app['dbprefix'] = '';
    $config_app['pconnect'] = FALSE;
    $config_app['db_debug'] = TRUE;
    /*$config_app['cache_on'] = FALSE;
	$config_app['cachedir'] = '';
	$config_app['char_set'] = 'utf8';
	$config_app['dbcollat'] = 'utf8_general_ci';
	$config_app['swap_pre'] = '';
	$config_app['encrypt'] = FALSE;
	$config_app['compress'] = FALSE;
	$config_app['stricton'] = FALSE;
	$config_app['failover'] = array();
	$config_app['save_queries'] = TRUE;*/
    return $config_app;
}

function EDITMASK($cadena=""){
    $valor=str_replace(",", "", $cadena);
    return $valor;
}
function FORMATO_DINERO($numero=0){
    $valor="$".number_format($numero, 2, '.', ',');
    return $valor;
}
function FORMATO_ENTERO($numero=0){
    $valor=number_format($numero, 0, '.', ',');
    return $valor;
}
function FORMATO_DECIMAL1($numero=0){
    $valor=number_format($numero, 1, '.', ',');
    return $valor;
}
function FORMATO_DECIMAL2($numero=0){
    if($numero!=0){ 
         $valor=number_format($numero, 2, '.', ',');
    }
    else{
         $valor="0.00";
    }
    return $valor;
}
function FORMATO_DECIMAL3($numero=0){
    $valor=number_format($numero, 3, '.', ',');
    return $valor;
}
function FORMATO_DECIMAL4($numero=0){
    $valor=number_format($numero, 4, '.', ',');
    return $valor;
}
function FORMATO_PORCENTAJEDEC($numero=0){
   $valor=number_format($numero, 2, '.', ',')."%";
    return $valor;
}
function FORMATO_PORCENTAJE($numero=0){
    $valor=number_format($numero, 0, '.', ',')."%";
    return $valor;
}
function FORMATO_FECHA($valor){
    $fecha=new DateTime($valor);
    return $fecha->format('d/m/Y');
}
function FORMATO_FECHA2($valor){
	

    $fecha=new DateTime($valor, new DateTimeZone('America/Mazatlan'));
    $lafecha=$fecha->format('d-m-y');
    $cadenaFecha=explode("-", $lafecha);

    if($valor=="0000-00-00")
    	return "";

    $fechaFormateada="";
    if($cadenaFecha[1]=='01'){
        $fechaFormateada=$cadenaFecha[0]."-Ene-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='02'){
        $fechaFormateada=$cadenaFecha[0]."-Feb-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='03'){
        $fechaFormateada=$cadenaFecha[0]."-Mar-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='04'){
        $fechaFormateada=$cadenaFecha[0]."-Abr-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='05'){
        $fechaFormateada=$cadenaFecha[0]."-May-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='06'){
        $fechaFormateada=$cadenaFecha[0]."-Jun-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='07'){
        $fechaFormateada=$cadenaFecha[0]."-Jul-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='08'){
        $fechaFormateada=$cadenaFecha[0]."-Ago-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='09'){
        $fechaFormateada=$cadenaFecha[0]."-Sep-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='10'){
        $fechaFormateada=$cadenaFecha[0]."-Oct-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='11'){
        $fechaFormateada=$cadenaFecha[0]."-Nov-".$cadenaFecha[2];
    }
    if($cadenaFecha[1]=='12'){
        $fechaFormateada=$cadenaFecha[0]."-Dic-".$cadenaFecha[2];
    }
    return $fechaFormateada;

}
function FORMATO_HORA($valor){
    $hora=new DateTime($valor);
    return $hora->format('H:i');
}

function FGETMODULOS()
{
	// Get a reference to the controller object
    $CI = get_instance();
    // You may need to load the model if it hasn't been pre-loaded
    $CI->load->model('HomeModel');
    // Call a function of the model
    return $CI->HomeModel->getModulos();
}

function FGETSUBMODULOS($pIdModulo)
{	
    $CI = get_instance();    
    $CI->load->model('HomeModel');    
    return $CI->HomeModel->getSubModulos($pIdModulo);
}

function GETSYSTEMNAME()
{
    echo "IN ROUTE Software de Venta";
}

function FGETSUBMODULOSPRINCIPAL()
{    
    $CI = get_instance();    
    $CI->load->model('CatalogosAdapModel');    
    return $CI->CatalogosAdapModel->getSubModulosPrincipal();
}

function GETAUTORIZA($autorizacion,$perfil,$aviso,$tipo)
{    
   // echo "<br> Autorizacion: ".$autorizacion." - Aviso: ".$aviso." - Perfil: ".$perfil." - Tipo:".$tipo;
    $banderin=false;
    $cadena=0;
    if($tipo=="AVISO"){
         $cadena=explode(',', $aviso);
        // echo "Hola";
    }
    else{
        $cadena=explode(',', $autorizacion);
    }
   
   
    $long=count($cadena);
    for ($i=0; $i < $long; $i++) { 
        if($cadena[$i]==$perfil){
            $banderin=true;
        }
    }
  
/*    if ($banderin){
        echo "<br>Si pasa";
    }
    else {
        echo "<br>No pasa";
    }*/
    
    return $banderin;

}
function GETAUTORIZAX($nivelDifusion,$nivelUsuarioX,$nivelUsuario)
{    
    $banderin=false;
    
    if($nivelDifusion==1){
        $banderin=true;
    }
    if($nivelDifusion==2){
        if($nivelUsuario<=$nivelUsuarioX){
            $banderin=true;
        }
    }
    if($nivelDifusion==3){
        if($nivelUsuario<$nivelUsuarioX){
            $banderin=true;
        }
    }
    return $banderin;

}
function GETVERIFICADOR($numero)
{    
    $contador=0;
    if($numero>9){
      $contador=3;
    }
    if($numero>99){
      $contador=2;
    }
    if($numero>999){
      $contador=1;
    }
    /*if($numero>9999){
      $contador=3;
    }
    if($numero>99999){
     $contador=2;
    }
    if($numero>999999){
     $contador=1;
    }*/
$numeroX="";
$numeroX=$numeroX.(string)$numero;

$numeroY="";
    for ($i=0; $i <$contador ; $i++) { 
      $random=rand(0,9);
      //echo $random;
      $numeroY=$numeroY.$random;
    }

$numeroX=$numeroX.$numeroY;

    return $numeroX;

}

function GETACCESO2($usuario,$controlador,$funcion){
    $CI = get_instance();    
    $CI->load->model('SessionModel');    
    $data=$CI->SessionModel->getPerfilSubMod($usuario,$controlador,$funcion);
    if($data==0){
        $bandera=0;
    }
    else {
        $bandera=1;
    }
    return $bandera;
}
function GETACCESO1($usuario,$grupo){
    $CI = get_instance();    
    $CI->load->model('SessionModel');    
    $data=$CI->SessionModel->getPerfilMod($usuario,$grupo);
    return $data;
}
function GET_TIPO_WORK_FLOW($tipousuario,$controlador,$funcion){
    $CI = get_instance();    
    $CI->load->model('SessionModel');    
    $data=$CI->SessionModel->getPerfilDatos($tipousuario,$controlador,$funcion,1);
    
    return $data;
}

function GETNAMEUNIDADCOMPLETA($pUnidad)
{    
    $CI = get_instance();    
    $CI->load->model('MenuModel');    
    return $CI->MenuModel->getNameUnidadCompleto($pUnidad);
}
function GETNAMEUNIDADCOMPLETA2($pUnidad)
{    
    $CI = get_instance();    
    $CI->load->model('MenuModel');    
    return $CI->MenuModel->getNameUnidadCompleto2($pUnidad);
}
function GETNAMEUSUARIOCOMPLETO($pUsuario)
{    
    $CI = get_instance();    
    $CI->load->model('CatalogosAdapModel');

    $valor = "";

    $datos = $CI->CatalogosAdapModel->getNameUsuarioCompleto($pUsuario);
    if( count($datos)>0 )
        $valor = strtoupper($datos->nombre);

    return $valor;
}

function GETNAMEPROVEEDORCOMPLETA($pUnidad)
{    
    $CI = get_instance();    
    $CI->load->model('MenuModel');    
    return $CI->MenuModel->getNameProveedorCompleto($pUnidad);
}
function GETNAMEPROVEEDOR($pUnidad)
{    
    $CI = get_instance();    
    $CI->load->model('MenuModel');    
    return $CI->MenuModel->getNameProveedor($pUnidad);
}
function GETNAMERUTACOMPLETA($pUnidad)
{    
    $CI = get_instance();    
    $CI->load->model('MenuModel');    
    return $CI->MenuModel->getNameRutaCompleto($pUnidad);
}
function GETNAMEUSUARIO($pClave)
{    
    $CI = get_instance();    
    $CI->load->model('MenuModel');    
    return $CI->MenuModel->getNameUsuario($pClave);
}
function ADDTODIFUSION($origenControlador, $origenFuncion, $perfil, $hoy, $hora, $usuario, $origenId, $status, $descripcion, $detalles, $numero, $laUnidad, $oficina, $autorizacion, $aviso, $comentarios="")
{    
    $CI = get_instance();    
    $CI->load->model('MenuModel');    
    $CI->MenuModel->addDifusion($origenControlador, $origenFuncion, $perfil, $hoy, $hora, $usuario, $origenId, $status, $descripcion, $detalles, $numero, $laUnidad, $oficina, $autorizacion, $aviso, $comentarios);
}
/*function GETADDAVISO($pUnidad)
{    
    $CI = get_instance();    
    $CI->load->model('MenuModel');    
    $CI->MenuModel->getValidarAutorizacion($controlador, $funcion, $perfil);
}*/
function GETDIFUSION($controlador, $funcion, $perfil, $tipo)
{    
    $CI = get_instance();    
    $CI->load->model('MenuModel');    
    return $CI->MenuModel->getValidarDifusion($controlador, $funcion, $perfil, $tipo);

}
function CONVERTIR_COD_APO($cadena){
    $cadena=str_replace(".apo.", "'", $cadena);
    return $cadena;
}
function CONVERTIR_APO_COD($cadena){
    $cadena=str_replace("'", ".apo.", $cadena);
    return $cadena;
}
function GUARDAR_CLIENTEPROVEDOR_NUEVO($cliente,$proveedores,$idUsuario,$fechahora){
    $CI=get_instance();
    $CI->load->model('CatalogosModel');
    $CI->CatalogosModel->guardarClienteProveedorNuevo($cliente,$proveedores,$idUsuario,$fechahora);
}
function GUARDAR_CLIENTEPROVEDOR_EDITAR($cliente,$proveedores,$idUsuario,$fechahora){
    $CI=get_instance();
    $CI->load->model('CatalogosModel');
    $CI->CatalogosModel->guardarClienteProveedorNuevo($cliente,$proveedores,$idUsuario,$fechahora);
}

function GETFOLIOPEDIDO($usuario,$idsucursal,$empresa){
    $CI=get_instance();
    $CI->load->model('AppModel');
    return $CI->AppModel->GetFolioPedido($usuario,$idsucursal,$empresa);   
}
function VERIFICAMULTISUCURSAL(){
    $CI = get_instance();    
    $CI->load->library('session');
    $LS=$CI->session->userdata("limiteSucursal");
    return $LS;   
}
function ISMULTISUCURSAL(){
    $CI = get_instance();    
    $CI->load->library('session');
    $LS=$CI->session->userdata("limiteSucursal");

    if($LS==1){
        return true;
    }

    return false;
}
function GETSUCURSAL(){
   $CI=get_instance();
   $CI->load->library('session');
   $LS=$CI->session->userdata("sucursal");
   return $LS;
}
function GETEMPRESA(){
    $CI = get_instance();
    $CI->load->library('session');
    $LS = $CI->session->userdata("empresa");
    return $LS;
 }
function GETSUCURSALNAME($id){
    $CI=get_instance();
    $CI->load->model('CatalogosModel');
    return $CI->CatalogosModel->getNombreSucursal2($id);
}
function GETUSUARIO(){
   $CI=get_instance();
   $CI->load->library('session');
   $LS=$CI->session->userdata("user");
   return $LS;
}
function GETNOMBREUSUARIO(){
   $CI=get_instance();
   $CI->load->library('session');
   $LS=$CI->session->userdata("nombreLIZER");
   return $LS;
}
function GETPUESTOUSUARIO(){
   $CI=get_instance();
   $CI->load->library('session');
   $LS=$CI->session->userdata("puestoLIZER");
   return $LS;
}
function GETIDPERFILUSUARIO(){
   $CI=get_instance();
   $CI->load->library('session');
   $LS=$CI->session->userdata("idperfilLIZER");
   return $LS;
}
function GETPERFILUSUARIO(){
   $CI=get_instance();
   $CI->load->library('session');
   $LS=$CI->session->userdata("perfil");
   return $LS;
}
function GETIDUSUARIO(){
   $CI=get_instance();
   $CI->load->library('session');
   $LS=$CI->session->userdata("userId");
   return $LS;
}


function AGREGARDIAS($dias,$fecha){
    $cdias='+'.$dias.' day';
    $nuevafecha = strtotime ( $cdias , strtotime ( $fecha ) ) ;
    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
    return $nuevafecha;
}

function GETFECHAHORA()
{
    date_default_timezone_set('America/Mazatlan');
    $fecha = date('Y-m-d')." ".date('H:i:s',time());
    return $fecha;
}

function GETFECHA()
{
    date_default_timezone_set('America/Mazatlan');
    $fecha = date('Y-m-d');
    return $fecha;
}

function GETHORA()
{
    date_default_timezone_set('America/Mazatlan');
    $fecha = date('H:i:s',time());
    return $fecha;
}

function GETDATOSUSUARIO($id,$dato)
{
    $CI=get_instance();
    $CI->load->model('HomeModel');
    return $CI->HomeModel->getDatosUsuario($id,$dato);
}

function GETDATOSEMPRESA()
{
    $CI = get_instance();    
    $CI->load->model('HomeModel');
    return $CI->HomeModel->getConexionEmpresa(GETEMPRESA());
}

function LOGOCLIENTE()
{
	//return RUTAFOLDERASSETS("images/logos/").GETDATOSEMPRESA()->logo;
    return GETDATOSEMPRESA()->logo;
}

function GETLISTASUCURSALES()
{
    $CI=get_instance();
    $CI->load->model('ReportesModel');
    return $CI->ReportesModel->getSucursales()->result();
}

function GETUSUARIOBYID($idusuario)
{
    $CI=get_instance();
    $CI->load->model('CatalogosModel');
    return $CI->CatalogosModel->getUsuarioById($idusuario)->row();
}

function DATE_DIFFERENCE($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    
    $interval = date_diff($datetime1, $datetime2);
    
    return $interval->format($differenceFormat);
}

function money_format_2($floatcurr)
{
    //return NumberFormatter::create( 'es_MX', NumberFormatter::DECIMAL )->format($floatcurr);
    return "$".number_format($floatcurr, 2, '.', ',');
}

function GETBEESDATOS()
{
    $CI=get_instance();
    $CI->load->model('BeesModel');
    return $CI->BeesModel->GetBeesDatos();
}