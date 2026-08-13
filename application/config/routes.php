<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/*if($_SERVER['HTTP_HOST'] == "pedidosfb.lizer.com.mx")
{
	$route['default_controller'] = 'PortalCliente';
}
else
{
	$route['default_controller'] = 'Home';
}*/

$route['default_controller'] = 'Home';

$route['404_override'] = 'notfound';
$route['translate_uri_dashes'] = FALSE;

$route['Login'] = 'Home/elLogin';
$route['Verificacion'] = 'Home/index';
$route['VerificacionUsuario'] = 'Home/inicioLogin';
$route['Principal'] = 'Reportes/inicio';
$route['Salir'] = 'Home/cerrarSesion';

$route['Usuarios'] = 'Catalogos/listaUsuarios';
$route['Vendedores'] = 'Catalogos/listaUsuarios';
$route['Repartidores'] = 'Catalogos/listaUsuarios';
$route['ListadoUsuariosJson'] = 'Catalogos/getListaUsuariosJson';
$route['NuevoUsuario'] = 'Catalogos/nuevoUsuario';
$route['EditarUsuario/(:any)'] = 'Catalogos/editarUsuario/$1';
$route['VerUsuario/(:any)'] = 'Catalogos/verUsuario/$1';
$route['GuardarUsuario'] = 'Catalogos/saveNuevoUsuario';

$route['Clientes'] = 'Catalogos/listaClientes';
$route['ListadoClientesJson'] = 'Catalogos/getListaClientesJson';
$route['VerCliente/(:any)'] = 'Catalogos/verCliente/$1';
$route['EditarCliente/(:any)'] = 'Catalogos/editarCliente/$1';
$route['NuevoCliente'] = 'Catalogos/nuevoCliente';
$route['GuardarCliente'] = 'Catalogos/saveNuevoCliente';
$route['ClientesMapa'] = 'Catalogos/clientesMapa';
$route['GetClienteByCodigo/(:any)'] = 'Catalogos/getClienteByCodigo/$1';

$route['Productos'] = 'Catalogos/listaProductos';
$route['ListadoProductosJson/(:any)'] = 'Catalogos/getListaProductosJson/$1';
$route['ListadoProductosJsonByStatatus'] = 'Catalogos/getListaProductosJsonByStatus';
$route['ListadoProductosInventarioJson/(:any)'] = 'Catalogos/getListaProductosInventarioJson/$1';
$route['NuevoProducto'] = 'Catalogos/nuevoProducto';
$route['GuardarProducto'] = 'Catalogos/saveNuevoProducto';
$route['EditarProducto/(:any)'] = 'Catalogos/editarProducto/$1';
$route['VerProducto/(:any)'] = 'Catalogos/verProducto/$1';

$route['Paquetes'] = 'Catalogos/listaPaquetes';
$route['NuevoPaquete'] = 'Catalogos/nuevoPaquete';
$route['EditarPaquete/(:any)'] = 'Catalogos/editarPaquete/$1';
$route['VerPaquete/(:any)'] = 'Catalogos/verPaquete/$1';

$route['Promociones'] = 'Catalogos/listaPromociones';
$route['ListadoPromocionesJson'] = 'Catalogos/getListaPromocionesJson';
$route['NuevaPromocion'] = 'Catalogos/nuevaPromocion';
$route['GuardarPromocion'] = 'Catalogos/saveNuevaPromocion';
$route['EditarPromocion/(:any)'] = 'Catalogos/editarPromocion/$1';
$route['VerPromocion/(:any)'] = 'Catalogos/verPromocion/$1';

$route['Proveedores'] = 'Catalogos/listaProveedor';
$route['ListadoProveedoresJson'] = 'Catalogos/getListaProveedoresJson';
$route['NuevoProveedor'] = 'Catalogos/nuevoProveedor';
$route['GuardarProveedor'] = 'Catalogos/saveNuevoProveedor';
$route['EditarProveedor/(:any)'] = 'Catalogos/editarProveedor/$1';
$route['VerProveedor/(:any)'] = 'Catalogos/verProducto/$1';

$route['Categorias'] = 'Catalogos/listaCategorias';
$route['ListadoCatergoriasJson'] = 'Catalogos/getListaCategoriasJson';
$route['NuevoCategoria'] = 'Catalogos/nuevaCategoria';
$route['GuardarCategoria'] = 'Catalogos/saveNuevaCategoria';
$route['EditarCategoria/(:any)'] = 'Catalogos/editarCategoria/$1';
$route['VerCategoria/(:any)'] = 'Catalogos/verCategoria/$1';

$route['Marcas'] = 'Catalogos/listaMarcas';
$route['ListadoMarcasJson'] = 'Catalogos/getListaMarcasJson';
$route['NuevoMarca'] = 'Catalogos/nuevaMarca';
$route['GuardarMarca'] = 'Catalogos/saveNuevaMarca';
$route['EditarMarca/(:any)'] = 'Catalogos/editarMarca/$1';
$route['VerMarca/(:any)'] = 'Catalogos/verMarca/$1';

$route['ClasificacionClientes'] = 'Catalogos/listaClasificacionClientes';
$route['ListadoClasificacionClientesJson'] = 'Catalogos/getListaClasificacionClientesJson';
$route['NuevoClasificacionCliente'] = 'Catalogos/nuevaClasificacionCliente';
$route['GuardarClasificacionCliente'] = 'Catalogos/saveNuevaClasificacionCliente';
$route['EditarClasificacionCliente/(:any)'] = 'Catalogos/editarClasificacionCliente/$1';
$route['VerClasificacionCliente/(:any)'] = 'Catalogos/verClasificacionCliente/$1';

$route['Sucursales'] = 'Catalogos/listaSucursales';
$route['ListadoSucursalesJson'] = 'Catalogos/getListaSucursalesJson';
$route['NuevoSucursal'] = 'Catalogos/nuevaSucursal';
$route['GuardarSucursal'] = 'Catalogos/saveNuevaSucursal';
$route['EditarSucursal/(:any)'] = 'Catalogos/editarSucursal/$1';
$route['VerSucursal/(:any)'] = 'Catalogos/verSucursal/$1';

$route['Rutas'] = 'Catalogos/listaRutas';
$route['ListadoRutasJson'] = 'Catalogos/getListaRutasJson';
$route['NuevoRuta'] = 'Catalogos/nuevaRuta';
$route['GuardarRuta'] = 'Catalogos/saveNuevaRuta';
$route['EditarRuta/(:any)'] = 'Catalogos/editarRuta/$1';
$route['VerRuta/(:any)'] = 'Catalogos/verRuta/$1';

$route['Zonas'] = 'Catalogos/listaZonas';
$route['ListadoZonasJson'] = 'Catalogos/getListaZonasJson';
$route['NuevoZona'] = 'Catalogos/nuevaZona';
$route['GuardarZona'] = 'Catalogos/saveNuevaZona';
$route['EditarZona/(:any)'] = 'Catalogos/editarZona/$1';
$route['VerZona/(:any)'] = 'Catalogos/verZona/$1';

$route["ReporteVentas"] = 'Reportes/listadoPedidos';
$route["ReporteVentasDetalle/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)"] = 'Reportes/verPedidos/$1/$2/$3/$4/$5/$6';
$route["ReporteVentasDetalleLiquidado/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)"] = 'Reportes/verPedidosLiquidado/$1/$2/$3/$4/$5/$6';
$route["PedidosJson"] = 'Reportes/listadoPedidosJson';
$route["VerPedido/(:any)"] = 'Reportes/verPedido/$1';
$route["ImprimirPedido/(:any)"] = 'Reportes/imprimirPedido/$1';
$route["ListadoSellout"] = 'Reportes/ListadoSellout';
$route["ReporteDistribucion"] = 'Reportes/reporteDistribucion';
$route["ReporteDistribucionJson"] = 'Reportes/reporteDistribucionJson';
$route["ReporteRepartoEntregas"] = 'Reportes/viewReporteRepartoEntregas';
$route["ReporteUtilidad"] = 'Reportes/viewReporteUtilidad';
$route["ReportePresupuestos"] = 'Reportes/viewReportePresupuestos';
$route["ReporteCortes"] = 'Reportes/viewReporteCortes';
$route["ReporteMesaControl"] = 'Reportes/viewReporteMesaControl';

$route["Visitas"] = 'Reportes/listadoVisitas';
$route["VisitasJson"] = 'Reportes/listadoVisitasJson';
$route["VerVisita/(:any)"] = 'Reportes/verVisita/$1';
$route["VisitasEnMapa"] = 'Reportes/visitasEnMapa';
$route["UbicacionRutasMapa"] = 'Reportes/ubicacionRutasMapa';

$route["CumplimientoAgenda"] = 'Reportes/listaCumplimientoAgenda';
$route["CumplimientoAgendaJson"] = 'Reportes/listadoCumplimientoagendaJson';
$route["VerVisitas/(:any)/(:any)/(:any)"] = 'Reportes/verAcciones/$1/$2/$3';

$route["EfectividadVisitas"] = 'Reportes/listaEfectividad';
$route["EfectividadVisitasJson"] = 'Reportes/listadoEfectividadVisitasJson';

$route["ObjetivosAcumulados"] = 'Estadisticas/ObjetivosAcumulados';
$route["ObjetivosAcumuladosJson"] = 'Estadisticas/getListaAcumuladosJson';
$route["ProyeccionNomina"] = 'Estadisticas/ProyeccionNomina';
$route["ProyeccionNomina2"] = 'Estadisticas/ProyeccionNomina2';
$route["ProyeccionNominaJson"] = 'Estadisticas/getListaProyeccionNominaJson';

$route["PedidosJson2"] = 'ModuloLiquidacion/listadoPedidosJson/01100480';

$route["DatosEmpresas"] = 'Configurar/datosEmpresas';
$route["ListaPerfiles"] = 'Configurar/listaPerfiles';
$route["ObjetivosContrato"] = 'Configurar/objetivosContrato';
$route["CorreosSellout"] = 'Configurar/correosSellout';

$route["RepartoRutas"] = 'Catalogos/listaRepartoRutas';
$route["ListadoRepartoRutasJson/(:any)"] = 'Catalogos/getListaRepartoRutasJson/$1';

$route['Inventario'] = 'Catalogos/listaInventario';
$route['ListadoInventarioJson'] = 'Catalogos/getListaInventarioJson';

$route['PortalCliente'] = 'PortalCliente/index';
$route['PortalClienteVistaPrincipal'] = 'PortalCliente/vista_principal';
$route['PortalClienteNuevoPedido'] = 'PortalCliente/vista_nuevo_pedido';

$route['Gastos'] = 'Administracion/control_gastos';
$route['OtrosIngresos'] = 'Administracion/otros_ingresos';
$route['Presupuesto'] = 'Administracion/presupuesto';
$route['ReporteNc'] = 'Administracion/reportenc';
$route['CapturaNc'] = 'Administracion/capturanc';
$route["EditarNc/(:any)"] = 'Administracion/editarnc/$1';

$route['ArmadoRuta'] = 'Almacen/ArmadoRuta';
$route['ReportePedidos'] = 'Almacen/ReportePedidos';

$route['ImportarExcel'] = 'Catalogos/ImportarJson';
$route['APIBEES'] = 'TokenBees/Apis';
$route['CargarVisitas'] = 'TokenBees/CargarVisitasView';