<?php
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
	2=> 'direccion'
);

// getting total number records without any search
$sql = "SELECT codigo, nombre, direccion";
$sql.=" FROM clientes";
$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get clientes");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	$sql = "SELECT codigo, nombre, direccion";
	$sql.=" FROM clientes";
	$sql.=" WHERE codigo LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR nombre LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR direccion LIKE '".$requestData['search']['value']."%' ";
	$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get clientes");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get clientes"); // again run query with limit
	
} else {	

	$sql = "SELECT codigo, nombre, direccion";
	$sql.=" FROM clientes";
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get clientes");
	
}

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row["codigo"];
	$nestedData[] = $row["nombre"];
	$nestedData[] = $row["direccion"];
	
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
