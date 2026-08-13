<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataTables extends CI_Controller {
	public function __construct()
	    {
	        parent::__construct();
			$this->load->helper(array('url','form', 'variables_helper', 'funcioneshtml'));
			$this->load->library(array('session', 'pagination'));
			$this->load->model(array('HomeModel','CatalogosModel','CatalogosAdapModel','ReportesModel'));		
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

/*INICIA SECCION DE CATEGORIAS*/
	public function dataClientes(){
					/* Database connection start */
			$servername = "lizer.com.mx";
			$username = "lizer_programa";
			$password = "Sistem@s1";
			$dbname = "lizer_fb";

			$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

			/* Database connection end */


			// storing  request (ie, get/post) global array to a variable  
			$requestData= $_REQUEST;


			$columns = array( 
			// datatable column index  => database column name
				0 =>'codigo', 
				1 => 'nombre',
				2 => 'direccion',
				3 => 'zona',
				4 => 'proveedores',
				//4 => 'idCliente'
				5 => 'sucursal',
				6 => 'status',
				7 => 'acciones'
				
				
			);

			// getting total number records without any search
			$sql = "SELECT clientes.codigo, clientes.nombre, CONCAT(clientes.calle,' ',clientes.numero,' ',clientes.colonia) as direccion, cat_zonas.zona, cat_sucursales.sucursal, IF(clientes.status=1,'Si','No') as status, CONCAT('1') as acciones, clientes.id";
			$sql.=" FROM clientes INNER JOIN cat_zonas ON clientes.zona=cat_zonas.id INNER JOIN cat_sucursales ON clientes.sucursal=cat_sucursales.id";
			$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get clientes");
			$totalData = mysqli_num_rows($query);
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


			if( !empty($requestData['search']['value']) ) {
				// if there is a search parameter
				$sql = "SELECT clientes.codigo, clientes.nombre, CONCAT(clientes.calle,' ',clientes.numero,' ',clientes.colonia) as direccion, cat_zonas.zona, cat_sucursales.sucursal, IF(clientes.status=1,'Si','No') as status, CONCAT('1') as acciones, (SELECT GROUP_CONCAT(cat_proveedor.nombre) FROM asi_cliente_proveedor INNER JOIN cat_proveedor ON cat_proveedor.id=asi_cliente_proveedor.proveedor WHERE cliente=clientes.id) AS proveedores";
				$sql.=" FROM clientes INNER JOIN cat_zonas ON clientes.zona=cat_zonas.id INNER JOIN cat_sucursales ON clientes.sucursal=cat_sucursales.id";
				$sql.=" WHERE codigo LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
				$sql.=" OR nombre LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR direccion LIKE '".$requestData['search']['value']."%' ";
				$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get clientes");
				$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

				$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
				$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get clientes"); // again run query with limit
				
			} else {	

				$sql = "SELECT clientes.codigo, clientes.nombre, CONCAT(clientes.calle,' ',clientes.numero,' ',clientes.colonia) as direccion, cat_zonas.zona, cat_sucursales.sucursal, IF(clientes.status=1,'Si','No') as status, CONCAT('1') as acciones,(SELECT GROUP_CONCAT(cat_proveedor.nombre) FROM asi_cliente_proveedor INNER JOIN cat_proveedor ON cat_proveedor.id=asi_cliente_proveedor.proveedor WHERE cliente=clientes.id) AS proveedores";
				$sql.=" FROM clientes INNER JOIN cat_zonas ON clientes.zona=cat_zonas.id INNER JOIN cat_sucursales ON clientes.sucursal=cat_sucursales.id";
				$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
				$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get clientes");
				
			}

			$data = array();

			while( $row=mysqli_fetch_array($query) ) {  // preparing an array
				$cadenaAcciones='<div class="hidden-sm hidden-xs action-buttons">';
				$nestedData=array(); 
				$cadenaAcciones.='<a id="VER1'.$row["id"].'" class="blue verCliente1" href="http://lizer.com.mx/lizerFB/index.php/Catalogos/verCliente/'.$row["id"].'">';
				$cadenaAcciones.='<i class="ace-icon fa fa-eye bigger-130"></i>
																</a>';
				$cadenaAcciones.='<a id="EDIT1'.$row["id"].'" class="green editarCliente1" href="http://lizer.com.mx/lizerFB/index.php/Catalogos/editarCliente/'.$row["id"].'">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>';
				$cadenaAcciones.='</div>';
				$cadenaAcciones.='
															<div class="hidden-md hidden-lg">
																<div class="inline pos-rel">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>';
				$cadenaAcciones.='<a id="VER2'.$row["id"].'" href="http://lizer.com.mx/lizerFB/index.php/Catalogos/verCliente/'.$row["id"].'" class="tooltip-info verCliente1" data-rel="tooltip" title="Ver">
																				<span class="blue">
																					<i class="ace-icon fa fa-eye bigger-120"></i>
																				</span>
																			</a>
																		</li>';
				$cadenaAcciones.='<li>
																			<a id="EDIT2'.$row["id"].'" href="http://lizer.com.mx/lizerFB/index.php/Catalogos/editarCliente/'.$row["id"].'" class="tooltip-success editarCliente1" data-rel="tooltip" title="Editar">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li></ul>
																</div>
															</div>';
				$nestedData[] = $row["codigo"];
				$nestedData[] = $row["nombre"];
				$nestedData[] = $row["direccion"];
				$nestedData[] = $row["zona"];
				$nestedData[] = $row["proveedores"];
				/*$idC=$row["id"];
				$cadenaProveedores="";
				$cuentaProveedores=0;
				$sqlProv = "SELECT cat_proveedor.nombre FROM cat_proveedor INNER JOIN asi_cliente_proveedor ON asi_cliente_proveedor.proveedor=cat_proveedor.id WHERE asi_cliente_proveedor.cliente=$idC AND asi_cliente_proveedor.status=1";
				$queryProv=mysqli_query($conn, $sqlProv) or die("employee-grid-data.php: get clientes");
				while( $rowProv=mysqli_fetch_array($queryProv) ){
					if($cuentaProveedores==0){
								$cadenaProveedores.=$rowProv["nombre"];
								$cuentaProveedores=1;
					}
					else{
						$cadenaProveedores.=', '.$rowProv["nombre"];
					}
				}
				$nestedData[] = $cadenaProveedores;*/
				//$nestedData[] = $row["idCliente"];
				$nestedData[] = $row["sucursal"];
				if($row["status"]=='Si'){
					$nestedData[] = '<span class="label label-sm label-success">Si</span>';
				}
				else{
					$nestedData[] = '<span class="label label-sm label-danger">No</span>';
				}
				$nestedData[] =$cadenaAcciones;
				
				$data[] = $nestedData;
			}



			$json_data = array(
						"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
						"recordsTotal"    => intval( $totalData ),  // total number of records
						"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData

						"data"            => $data   // total data array
						);

			echo json_encode($json_data);  // send data as json format
	}
}
