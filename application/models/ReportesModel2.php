<?php
class ReportesModel extends CI_Model {
 private $dbL;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->dbL= $this->load->database('dbGlobal', TRUE);
		//$this->dbLO= $this->load->database('accesosOrigen', TRUE);
		
	}
public function getPruebaAgregarPedido($idVendedor){
		//$consulta="CALL agregarAcumulados('2018-11-06',".$idVendedor.",'BEBIDAS DE COCOA',3200.43)";
		$fecha='2018-11-06';
		$idVendedor=14;
		$categoria='BEBIDAS DE COCOA';
		$data = file_get_contents("assets/acumulados/jSonAcumulados.json");
		$products = json_decode($data, true);
			echo "<br>".$products['TipoArchivo']."<br>".$products['Fecha']."<br>";
		    //print_r($products['Acumulados']);
		    foreach ($products['Acumulados'] as $lVend) {
		    	echo "<br>IdVendedor: ".$lVend['IdVendedor'];
		    	foreach ($lVend['AcumCat'] as $lAcum) {
		    		echo "<br>--- Categoria: ".$lAcum['Categoria']." Importe".$lAcum['Importe']."<br>";
		    		$consulta="CALL validaAcumuladoCat('".$fecha."',".$idVendedor.",'".$categoria."')";
		    		$resultado=$this->db->query($consulta);
		    		mysql_free_result($resultado);
		    	}
		    }
		/*$consulta="CALL validaAcumuladoCat('".$fecha."',".$idVendedor.",'".$categoria."')";
        if($this->db->query($consulta))
        {
            $res='listo';
        }else{
            $res=show_error('Error!');
        }
    return $res;*/
    return "OK";
}
public function doCorte($fecha){
	$fecha2=$fecha." 23:59:59";
	$consulta="CALL eliminaPedidos('".$fecha."','".$fecha2."')";
	$resultado=$this->db->query($consulta);
}
public function getAgregarObjetivos($cadena){
	$objetivosT=json_decode($cadena,true);
	$periodo=$objetivosT['Periodo'];
	$mes=substr($periodo, -2);
	$tipoArchivo=$objetivosT['TipoArchivo'];
	$objetivos=$objetivosT['Objetivos'];
	$fecha='2018-01-01';
	foreach ($objetivosT['Objetivos'] as $lObj) {
		# code...
		$idVendedor=$lObj['IdVendedor'];
		foreach ($lObj['ObjetivoCat'] as $ObjCat) {
			# code...
			$categoria=$ObjCat['Categoria'];
			$importe=$ObjCat['Importe'];
			 
			$consulta="SELECT id AS cuantos FROM acumulados_categorias WHERE idVendedor=$idVendedor AND periodo='$periodo' AND categoria='$categoria'";
			$res=$this->db->query($consulta);
			if($res->num_rows()!=0){

		    	 $consulta2="CALL actualizarObjetivos(".$idVendedor.",'".$categoria."',".$importe.",'".$mes."','".$periodo."')";
		    }
		    else{
		    	$consulta2="CALL agregarObjetivos(".$idVendedor.",'".$categoria."',".$importe.",'".$mes."','".$periodo."')";
		    }
		    $this->db->query($consulta2);
		}
	}
}	
public function getAgregarAcumulados($cadena){
	/*$data = file_get_contents("assets/acumulados/jSonAcumulados.json");*/
		$products = json_decode($cadena, true);
			$fecha=$products['Fecha'];
			$diasMes=$products['diasMes'];
			$diasTranscurridos=$products['diasTranscurridos'];
			$tipoArchivo=$products['TipoArchivo'];
			$cFecha=explode("-",$fecha);
			$mes=$cFecha[1];
			$periodo=$cFecha[0];

			//echo "<br>".$products['TipoArchivo']."<br>".$products['Fecha']."<br>";
		    //print_r($products['Acumulados']);
		    foreach ($products['Acumulados'] as $lVend) {
		    	//echo "<br>IdVendedor: ".$lVend['IdVendedor'];
		    	$idVendedor=$lVend['IdVendedor'];
		    	foreach ($lVend['AcumCat'] as $lAcum) {
		    		$categoria=$lAcum['Categoria'];
		    		$acumulado=$lAcum['Importe'];
		    		//echo "<br>--- Categoria: ".$lAcum['Categoria']." Importe".$lAcum['Importe']."<br>";
		    		$consulta="SELECT id AS cuantos FROM acumulados_categorias WHERE idVendedor=$idVendedor AND mes='$mes' AND categoria='$categoria'";
		    		//echo $consulta;
		    		$res=$this->db->query($consulta);
		    		//print_r($res);
		    if($res->num_rows()!=0){

		    	 $consulta2="CALL actualizarAcumulados('".$fecha."',".$idVendedor.",'".$categoria."',".$acumulado.",'".$mes."',".$diasMes.",".$diasTranscurridos.")";
		    }
		    else{
		    		$consulta2="CALL agregarAcumulados('".$fecha."',".$idVendedor.",'".$categoria."',".$acumulado.",'".$mes."',".$diasMes.",".$diasTranscurridos.")";
		    }
		    $this->db->query($consulta2);
				       
		    		
			       
		    }
}
}
public function getAgregarAcumuladosRes(){
	$data = file_get_contents("assets/acumulados/jSonAcumulados.json");
		$products = json_decode($data, true);
			$fecha=$products['Fecha'];
			$tipoArchivo=$products['TipoArchivo'];
			$cFecha=explode("-",$fecha);
			$mes=$cFecha[1];
			$periodo=$cFecha[0];

			//echo "<br>".$products['TipoArchivo']."<br>".$products['Fecha']."<br>";
		    //print_r($products['Acumulados']);
		    foreach ($products['Acumulados'] as $lVend) {
		    	//echo "<br>IdVendedor: ".$lVend['IdVendedor'];
		    	$idVendedor=$lVend['IdVendedor'];
		    	foreach ($lVend['AcumCat'] as $lAcum) {
		    		$categoria=$lAcum['Categoria'];
		    		$acumulado=$lAcum['Importe'];
		    		//echo "<br>--- Categoria: ".$lAcum['Categoria']." Importe".$lAcum['Importe']."<br>";
		    		$consulta="SELECT id AS cuantos FROM acumulados_categorias WHERE idVendedor=$idVendedor AND mes='$mes' AND categoria='$categoria'";
		    		//echo $consulta;
		    		$res=$this->db->query($consulta);
		    		//print_r($res);
		    if($res->num_rows()!=0){

		    	 $consulta2="CALL actualizarAcumulados('".$fecha."',".$idVendedor.",'".$categoria."',".$acumulado.",'".$mes."')";
		    }
		    else{
		    		$consulta2="CALL agregarAcumulados('".$fecha."',".$idVendedor.",'".$categoria."',".$acumulado.",'".$mes."')";
		    }
		    $this->db->query($consulta2);
				       
		    		
			       
		    }
}
}
public function getDatosEmpresa(){
	$idempresa=GETIDEMPRESA();
	$consulta="SELECT * FROM empresas WHERE idCliente='$idempresa'";
	$query=$this->dbL->query($consulta);
	return $query;
}
public function getCuantasVisitas($fIni,$fFin,$valor){
	$consulta="SELECT COUNT(id) AS cuanto FROM visitas WHERE resultado='$valor' AND fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;
}
public function getDatosPedidos($usuario,$sucursal,$tipo,$ruta,$fIni,$fFin){
	$consulta="SELECT count(id) AS cuantos, sum(total) as total FROM pedidos";
	$contador=0;
	if($usuario!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		$consulta.="(";
		$usuarioCadena=explode(",", $usuario);
		$usuarioNum=count($usuarioCadena);
		for ($i=0; $i < $usuarioNum; $i++) { 
			$consultaUsuario="SELECT id FROM usuarios WHERE nombre='$usuarioCadena[$i]'";
			$queryU=$this->db->query($consultaUsuario);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="idusuario=".$id;
				}
			else{
				$consulta.=" OR idusuario=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
	
	if($sucursal!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		else{
			$consulta.=" AND ";
		}
		$consulta.="(";
		$sucursalCadena=explode(",", $sucursal);
		$sucursalNum=count($sucursalCadena);
		for ($i=0; $i < $sucursalNum; $i++) { 
			$consultaSucursal="SELECT id FROM cat_sucursales WHERE sucursal='$sucursalCadena[$i]'";
			$queryU=$this->db->query($consultaSucursal);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="idsucursal=".$id;
				}
			else{
				$consulta.=" OR idsucursal=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
			if($ruta!="TODOS"){
				if($contador==0){
					$consulta.=" WHERE ";
				}
				else{
					$consulta.=" AND ";
				}
				$consulta.="(";
				$rutaCadena=explode(",", $ruta);
				$rutaNum=count($rutaCadena);
				for ($i=0; $i < $rutaNum; $i++) { 
					$consultaRuta="SELECT id FROM cat_rutas WHERE ruta='$rutaCadena[$i]'";
					$queryU=$this->db->query($consultaRuta);
					$id=$queryU->row()->id;
					if($i==0){
						$consulta.="ruta=".$id;
						}
					else{
						$consulta.=" OR ruta=".$id;
					}

				}
				$consulta.=")";
				$contador=$contador+1;

			}

	if($contador==0){
		$consulta.=" tipo='PREVENTA' AND status=1 AND fecha BETWEEN '$fIni' AND '$fFin'";
	}
	else{
		$consulta.=" AND tipo='PREVENTA' AND status=1 AND fecha BETWEEN '$fIni' AND '$fFin'";
	}
	
	$query=$this->db->query($consulta);
	return $query;
}
public function getDatosVisitasProgramadas($usuario,$sucursal,$ruta,$fIni,$fFin){
	$fecha=$fIni;
	$contadorD=0;
	$cadenaD="";
	$consulta="SELECT count(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona INNER JOIN cat_rutas ON cat_rutas.id=asi_ruta_zona.zona INNER JOIN usuarios ON usuarios.ruta=cat_rutas.id INNER JOIN cat_sucursales ON cat_sucursales.id=cat_rutas.sucursal";
	if($fecha==$fFin){
		$dia=date('w', strtotime($fecha));
		$dia=$dia+1;
		if($contadorD==0){
			$cadenaD.="clientes.diasvisita like '%".$dia."%'";
		}
		else{
			$cadenaD.="OR clientes.diasvisita like '%".$dia."%'";
		}
	}
	else{
		while($fecha!=$fFin){
			$dia=date('w', strtotime($fecha));
			$dia=$dia+1;
			if($contadorD==0){
				$cadenaD.="clientes.diasvisita like '%".$dia."%'";
			}
			else{
				$cadenaD.="OR clientes.diasvisita like '%".$dia."%'";
			}
			$fecha=AGREGARDIAS(1,$fecha);
		}
	}
	//$consulta="SELECT count(visitas.id) AS cuantos FROM usuarios WHERE sucursal=$sucursal AND (".$cadena.")";
	$contador=0;
	if($usuario!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		$consulta.="(";
		$usuarioCadena=explode(",", $usuario);
		$usuarioNum=count($usuarioCadena);
		for ($i=0; $i < $usuarioNum; $i++) { 
			$consultaUsuario="SELECT id FROM usuarios WHERE nombre='$usuarioCadena[$i]'";
			$queryU=$this->db->query($consultaUsuario);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="usuarios.id=".$id;
				}
			else{
				$consulta.=" OR usuarios.id=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
	
	if($sucursal!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		else{
			$consulta.=" AND ";
		}
		$consulta.="(";
		$sucursalCadena=explode(",", $sucursal);
		$sucursalNum=count($sucursalCadena);
		for ($i=0; $i < $sucursalNum; $i++) { 
			$consultaSucursal="SELECT id FROM cat_sucursales WHERE sucursal='$sucursalCadena[$i]'";
			$queryU=$this->db->query($consultaSucursal);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="usuarios.idsucursal=".$id;
				}
			else{
				$consulta.=" OR usuarios.idsucursal=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
		if($ruta!="TODOS"){

				if($contador==0){
					$consulta.=" WHERE ";
				}
				else{
					$consulta.=" AND ";
				}
				$consulta.="(";
				$rutaCadena=explode(",", $ruta);
				$rutaNum=count($rutaCadena);
				for ($i=0; $i < $rutaNum; $i++) { 
					$consultaRuta="SELECT id FROM cat_rutas WHERE ruta='$rutaCadena[$i]'";
					$queryU=$this->db->query($consultaRuta);
					$id=$queryU->row()->id;
					if($i==0){
						$consulta.="ruta.id=".$id;
						}
					else{
						$consulta.=" OR ruta.id=".$id;
					}

				}
				$consulta.=")";
				$contador=$contador+1;

			}
	if($contador!=0){
		$consulta.=" AND (".$cadenaD.")";
	}
	else{
		$consulta.=" WHERE (".$cadenaD.")";
	}
	$query=$this->db->query($consulta);
	return $query;
	//return $consulta;
}
public function getDatosVisitasProgramadasSi($usuario,$sucursal,$ruta,$fIni,$fFin){
	$fecha=$fIni;
	$contadorD=0;
	$cadena="";
	$consulta="SELECT count(visitas.id) AS cuantos FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON cat_sucursales.id=usuarios.sucursal INNER JOIN cat_rutas ON cat_rutas.id=visitas.ruta";
	if($fecha==$fFin){
		$dia=date('w', strtotime($fecha));
		$dia=$dia+1;
		if($contadorD==0){
			$cadena.="visitas.diasvisitas like '%".$dia."%'";
		}
		else{
			$cadena.="OR visitas.diasvisitas like '%".$dia."%'";
		}
	}
	else{
		while($fecha!=$fFin){
			$dia=date('w', strtotime($fecha));
			$dia=$dia-1;
			if($contadorD==0){
				$cadena.="visitas.diasvisitas like '%".$dia."%'";
			}
			else{
				$cadena.="OR visitas.diasvisitas like '%".$dia."%'";
			}
			$fecha=AGREGARDIAS(1,$fecha);
		}
	}
	//$consulta="SELECT count(visitas.id) AS cuantos FROM usuarios WHERE sucursal=$sucursal AND (".$cadena.")";
	$contador=0;
	if($usuario!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		$consulta.="(";
		$usuarioCadena=explode(",", $usuario);
		$usuarioNum=count($usuarioCadena);
		for ($i=0; $i < $usuarioNum; $i++) { 
			$consultaUsuario="SELECT id FROM usuarios WHERE nombre='$usuarioCadena[$i]'";
			$queryU=$this->db->query($consultaUsuario);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="visitas.idusuario=".$id;
				}
			else{
				$consulta.=" OR visitas.idusuario=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
	
	if($sucursal!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		else{
			$consulta.=" AND ";
		}
		$consulta.="(";
		$sucursalCadena=explode(",", $sucursal);
		$sucursalNum=count($sucursalCadena);
		for ($i=0; $i < $sucursalNum; $i++) { 
			$consultaSucursal="SELECT id FROM cat_sucursales WHERE sucursal='$sucursalCadena[$i]'";
			$queryU=$this->db->query($consultaSucursal);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="visitas.idsucursal=".$id;
				}
			else{
				$consulta.=" OR visitas.idsucursal=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
		if($ruta!="TODOS"){
				if($contador==0){
					$consulta.=" WHERE ";
				}
				else{
					$consulta.=" AND ";
				}
				$consulta.="(";
				$rutaCadena=explode(",", $ruta);
				$rutaNum=count($rutaCadena);
				for ($i=0; $i < $rutaNum; $i++) { 
					$consultaRuta="SELECT id FROM cat_rutas WHERE ruta='$rutaCadena[$i]'";
					$queryU=$this->db->query($consultaRuta);
					$id=$queryU->row()->id;
					if($i==0){
						$consulta.="cat_rutas.id=".$id;
						}
					else{
						$consulta.=" OR cat_rutas.id=".$id;
					}

				}
				$consulta.=")";
				$contador=$contador+1;

			}
	if($contador==0){
		$consulta.=" WHERE visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	}
	else{
		$consulta.=" AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	}
	if($contador!=0){
		$consulta.=" AND (".$cadena.")";
	}
	else{
		$consulta.=" AND (".$cadena.")";
	}
	$query=$this->db->query($consulta);
	return $query;
	//return $consulta;
}
public function getDatosEfectividadTotal($usuario,$sucursal,$ruta,$fIni,$fFin){
	$consulta="SELECT count(visitas.id) AS cuantos FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON cat_sucursales.id=usuarios.sucursal INNER JOIN cat_rutas ON cat_rutas.id=visitas.ruta";
	$contador=0;
	if($usuario!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		$consulta.="(";
		$usuarioCadena=explode(",", $usuario);
		$usuarioNum=count($usuarioCadena);
		for ($i=0; $i < $usuarioNum; $i++) { 
			$consultaUsuario="SELECT id FROM usuarios WHERE nombre='$usuarioCadena[$i]'";
			$queryU=$this->db->query($consultaUsuario);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="visitas.idusuario=".$id;
				}
			else{
				$consulta.=" OR visitas.idusuario=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
	
	if($sucursal!="TODOS"){
		if($contador==0){
			$consulta.=" WHERE ";
		}
		else{
			$consulta.=" AND ";
		}
		$consulta.="(";
		$sucursalCadena=explode(",", $sucursal);
		$sucursalNum=count($sucursalCadena);
		for ($i=0; $i < $sucursalNum; $i++) { 
			$consultaSucursal="SELECT id FROM cat_sucursales WHERE sucursal='$sucursalCadena[$i]'";
			$queryU=$this->db->query($consultaSucursal);
			$id=$queryU->row()->id;
			if($i==0){
				$consulta.="visitas.idsucursal=".$id;
				}
			else{
				$consulta.=" OR visitas.idsucursal=".$id;
			}

		}
		$consulta.=")";
		$contador=$contador+1;

	}
		if($ruta!="TODOS"){
				if($contador==0){
					$consulta.=" WHERE ";
				}
				else{
					$consulta.=" AND ";
				}
				$consulta.="(";
				$rutaCadena=explode(",", $ruta);
				$rutaNum=count($rutaCadena);
				for ($i=0; $i < $rutaNum; $i++) { 
					$consultaRuta="SELECT id FROM cat_rutas WHERE ruta='$rutaCadena[$i]'";
					$queryU=$this->db->query($consultaRuta);
					$id=$queryU->row()->id;
					if($i==0){
						$consulta.="cat_rutas.id=".$id;
						}
					else{
						$consulta.=" OR cat_rutas.id=".$id;
					}

				}
				$consulta.=")";
				$contador=$contador+1;

			}
	if($contador==0){
		$consulta.=" visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	}
	else{
		$consulta.=" AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	}
	
	$query=$this->db->query($consulta);
	return $query;
}

public function getRutasPedidos(){
	$MS=VERIFICAMULTISUCURSAL();
		$sucursal=GETSUCURSAL();
		if($MS==1){
			$consulta="SELECT * FROM cat_rutas ORDER BY ruta ASC";
		}
		else{
			$consulta="SELECT * FROM cat_rutas WHERE sucursal=$sucursal ORDER BY ruta ASC";
		}
	$query=$this->db->query($consulta);
	return $query;
}
public function getIdPedido($cliente,$fecha){
	$id=0;
	$consulta="SELECT id FROM pedidos WHERE idcliente=$cliente AND fecha='$fecha'";
	$query=$this->db->query($consulta);
	foreach ($query->result() as $k) {
		$id=$k->id;
	}
	return $id;
}
public function getusuarios(){
		$MS=VERIFICAMULTISUCURSAL();
		$sucursal=GETSUCURSAL();
		if($MS==1){
			$consulta="SELECT * FROM usuarios WHERE vendedor=1 ORDER BY nombre ASC";
		}
		else{
			$consulta="SELECT * FROM usuarios WHERE vendedor=1 AND sucursal=$sucursal ORDER BY nombre ASC";
		}
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosVisitas($idUsuario,$fIni,$fFin){
	$consulta="SELECT DISTINCT(visitas.idcliente),visitas.fecha,visitas.latitud,visitas.longitud,clientes.codigo,clientes.nombre,clientes.calle,clientes.numero,clientes.colonia,clientes.ciudad,clientes.estado FROM visitas INNER JOIN clientes ON clientes.id=visitas.idcliente WHERE visitas.idusuario=$idUsuario AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	$contador=0;
	$cadena="";
	//$cadena2=$query->num_rows();
					foreach ($query->result() as $kCoord) {
						
						$consultaPed="SELECT tipo,total FROM pedidos WHERE idcliente=$kCoord->idcliente AND pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
						//$cadena2.=" - ".$consultaPed;
						//echo "<br>".$consultaPed;
						$queryPed=$this->db->query($consultaPed);
						if($queryPed->num_rows()!=0){
							$total=FORMATO_DINERO($queryPed->row()->total);
							$tipo=$queryPed->row()->tipo;
							if($contador==0){
								$cadena.=$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
							}
							else{
								$cadena.="%".$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
							}	 
						}
						else{
							$total=0;
							$tipo="VISITA";
							if($contador==0){
								$cadena.=$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
							}
							else{
								$cadena.="%".$kCoord->nombre."/".$kCoord->latitud."/".$kCoord->longitud."/".$kCoord->codigo."/".$kCoord->calle." ".$kCoord->numero." ".$kCoord->colonia.", ".$kCoord->ciudad.", ".$kCoord->estado."/".$total."/".$tipo;
							}
						}
						
						$contador=$contador+1;
				}
	//return $cadena2;
	return $contador."&".$cadena;
}
public function getProductosJ(){
	$consulta="SELECT * FROM cat_productos";
	$query=$this->db->query($consulta);
	return $query;
}
public function getClientesJ(){
	$consulta="SELECT * FROM clientes";
	$query=$this->db->query($consulta);
	return $query;
}
public function getUsuariosJ(){
	$consulta="SELECT * FROM usuarios";
	$query=$this->db->query($consulta);
	return $query;
}
public function getZonasJ($id){
	$consulta="SELECT * FROM cat_zonas WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
public function getCategoriasProductosJ($id){
	$consulta="SELECT * FROM cat_clasificacionproductos WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
public function getSucursales(){
		$MS=VERIFICAMULTISUCURSAL();
		$sucursal=GETSUCURSAL();
		if($MS==1){
			$consulta="SELECT * FROM cat_sucursales ORDER BY sucursal ASC";
		}
		else{
			$consulta="SELECT * FROM cat_sucursales WHERE id=$sucursal";		
		}
	$query=$this->db->query($consulta);
	return $query;
}
public function banderaImpreso($idpedido){
	$consulta="UPDATE pedidos SET impreso=1 WHERE id=$idpedido";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidos($fIni,$fFin){
	$consulta="SELECT pedidos.id,pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total, pedidos.fechacreacion, pedidos.impreso, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, cat_sucursales.sucursal AS sucursal, pedidos.status, cat_rutas.ruta FROM pedidos  INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente INNER JOIN cat_sucursales ON cat_sucursales.id=clientes.sucursal INNER JOIN cat_rutas ON cat_rutas.chofer=pedidos.idusuario WHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosCuantos($fIni,$fFin){
	$consulta="SELECT COUNT(pedidos.id) AS cuantospedidos, SUM(pedidos.total) AS totalpedidos FROM pedidos WHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosDatos($fIni,$fFin,$idUsuario){
	$consulta="SELECT COUNT(total) AS numeroVentas,SUM(total) AS totalVentas FROM pedidos WHERE idusuario=$idUsuario AND tipo='PREVENTA' AND fecha BETWEEN '$fIni' AND '$fFin'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getVisitas($fIni,$fFin){
	$consulta="SELECT visitas.id,visitas.codigocliente, visitas.idcliente, visitas.cliente, usuarios.nombre, visitas.resultado, visitas.fecha, visitas.inicio, visitas.fin, cat_sucursales.sucursal, cat_rutas.ruta FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id INNER JOIN cat_rutas ON cat_rutas.chofer=usuarios.id WHERE visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;

}
public function getEfectividad($fIni,$fFin){
	/*$consulta="SELECT usuarios.nombre, usuarios.id AS idUsuario, COUNT(visitas.idusuario) AS numeroVisitas, cat_sucursales.sucursal,MIN(visitas.fechacreacion) AS primera, MAX(visitas.fechacreacion) AS ultima FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";*/
	$consulta="SELECT DISTINCT (visitas.idusuario),usuarios.nombre, usuarios.id AS idUsuario, cat_sucursales.sucursal, cat_rutas.ruta FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id INNER JOIN cat_rutas ON cat_rutas.chofer=visitas.idusuario WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;

}
public function getEfectividadAgenda($fIni,$fFin){
	/*$consulta="SELECT usuarios.nombre, usuarios.id AS idUsuario, COUNT(visitas.idusuario) AS numeroVisitas, cat_sucursales.sucursal,MIN(visitas.fechacreacion) AS primera, MAX(visitas.fechacreacion) AS ultima FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";*/
	$consulta="SELECT DISTINCT (visitas.idusuario),usuarios.nombre, usuarios.id AS idUsuario, cat_sucursales.sucursal, cat_rutas.ruta, visitas.ruta AS idRuta FROM visitas INNER JOIN usuarios ON usuarios.id=visitas.idusuario INNER JOIN cat_sucursales ON usuarios.sucursal=cat_sucursales.id INNER JOIN cat_rutas ON cat_rutas.chofer=visitas.idusuario WHERE usuarios.status=1 AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;

}

public function getEfectividadEstVisitas($fIni,$fFin,$usuarioid){
	$consulta="SELECT COUNT(visitas.id) AS numeroVisitas, MIN(visitas.fechacreacion) AS primera, MAX(visitas.fechacreacion) AS ultima FROM visitas WHERE visitas.idusuario=$usuarioid AND visitas.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;
}
public function getDatosAgenda($fIni,$fFin,$ruta){
	$fecha=$fIni;
	$visitasProgramadas=0;
	$visitasHechas=0;
	$visitasNoHechas=0;

	if($fecha==$fFin){
		$dia=date('w', strtotime($fecha));
		$dia=$dia+1;
		$consulta="SELECT COUNT(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona WHERE asi_ruta_zona.ruta=$ruta AND clientes.diasvisita LIKE '%$dia%'";
		$query=$this->db->query($consulta);
		$visitasProgramadas+=$query->row()->cuantos;
		/*if($query->num_rows()!=0){
			foreach($query->result() as $q2){
				$consulta2="SELECT id FROM visitas WHERE idcliente=$q2->id AND ruta=$ruta AND fecha='$fecha'";
				$query2=$this->db->query($consulta2);
				$visitasHechas+=$query->num_rows();
			}
		}*/
		$consulta2="SELECT COUNT(id) AS cuantossi FROM visitas WHERE ruta=$ruta AND fecha='$fecha' AND diasvisitas LIKE '%$dia%'";
				$query2=$this->db->query($consulta2);
				$visitasHechas+=$query2->row()->cuantossi;
	}
	else{
		while($fecha!=$fFin){
			$dia=date('w', strtotime($fecha));
			$dia=$dia-1;
				$consulta="SELECT COUNT(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona WHERE asi_ruta_zona.ruta=$ruta AND clientes.diasvisita LIKE '%$dia%'";
				$query=$this->db->query($consulta);
				$visitasProgramadas+=$query->row()->cuantos;
				/*if($query->num_rows()!=0){
					foreach($query->result() as $q2){
						$consulta2="SELECT id FROM visitas WHERE idcliente=$q2->id AND ruta=$ruta AND fecha='$fecha'";
						$query2=$this->db->query($consulta2);
						$visitasHechas+=$query->num_rows();
					}
				}*/
				$consulta2="SELECT COUNT(id) AS cuantossi FROM visitas WHERE ruta=$ruta AND fecha='$fecha' AND diasvisitas LIKE '%$dia%'";
						$query2=$this->db->query($consulta2);
						$visitasHechas+=$query2->row()->cuantossi;
			$fecha=AGREGARDIAS(1,$fecha);
		}
	}
	return $visitasProgramadas."-".$visitasHechas;
}
public function getDatosAgendaTodas($fIni,$fFin,$ruta){
	$fecha=$fIni;
	$visitasProgramadas=0;
	$visitasHechas=0;
	$visitasNoHechas=0;

	if($fecha==$fFin){
		$dia=date('w', strtotime($fecha));
		$dia=$dia-1;
		$consulta="SELECT COUNT(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona WHERE asi_ruta_zona.ruta=$ruta AND clientes.diasvisita LIKE '%$dia%'";
		$query=$this->db->query($consulta);
		$visitasProgramadas+=$query->row()->cuantos;
		/*if($query->num_rows()!=0){
			foreach($query->result() as $q2){
				$consulta2="SELECT id FROM visitas WHERE idcliente=$q2->id AND ruta=$ruta AND fecha='$fecha'";
				$query2=$this->db->query($consulta2);
				$visitasHechas+=$query->num_rows();
			}
		}*/
		$consulta2="SELECT COUNT(id) AS cuantossi FROM visitas WHERE ruta=$ruta AND fecha='$fecha' AND diasvisitas LIKE '%$dia%'";
				$query2=$this->db->query($consulta2);
				$visitasHechas+=$query2->row()->cuantossi;
	}
	else{
		while($fecha!=$fFin){
			$dia=date('w', strtotime($fecha));
			$dia=$dia-1;
				$consulta="SELECT COUNT(clientes.id) AS cuantos FROM clientes INNER JOIN asi_ruta_zona ON asi_ruta_zona.zona=clientes.zona WHERE asi_ruta_zona.ruta=$ruta AND clientes.diasvisita LIKE '%$dia%'";
				$query=$this->db->query($consulta);
				$visitasProgramadas+=$query->row()->cuantos;
				/*if($query->num_rows()!=0){
					foreach($query->result() as $q2){
						$consulta2="SELECT id FROM visitas WHERE idcliente=$q2->id AND ruta=$ruta AND fecha='$fecha'";
						$query2=$this->db->query($consulta2);
						$visitasHechas+=$query->num_rows();
					}
				}*/
				$consulta2="SELECT COUNT(id) AS cuantossi FROM visitas WHERE ruta=$ruta AND fecha='$fecha' AND diasvisitas LIKE '%$dia%'";
						$query2=$this->db->query($consulta2);
						$visitasHechas+=$query2->row()->cuantossi;
			$fecha=AGREGARDIAS(1,$fecha);
		}
	}
	return $visitasProgramadas."-".$visitasHechas;
	//return $visitasProgramadas."-".$visitasHechas;
}
public function getRutas(){
	$MS=VERIFICAMULTISUCURSAL();
		$sucursal=GETSUCURSAL();
		if($MS==1){
			$consulta="SELECT * FROM cat_rutas WHERE status=1";
		}
		else {
			$consulta="SELECT * FROM cat_rutas WHERE sucursal=$sucursal AND status=1";
		}
	$query=$this->db->query($consulta);
	return $query;
}
public function deletePedidos($id){
	$consulta="UPDATE pedidos SET status=0 WHERE id=$id";
	$this->db->query($consulta);
}
public function getPedidosDetalle($idpedido){
	$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, cat_proveedor.nombre AS nombreProveedor FROM pedidos INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente INNER JOIN cat_proveedor ON pedidos_detalle.idproveedor=cat_proveedor.id WHERE pedidos.id=$idpedido";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosDetalladosId($idpedido){
	$consulta="SELECT codigoproducto, producto, cantidad, precio, importe FROM pedidos_detalle WHERE idpedido=$idpedido";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosDetalleId($idpedido){
	$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.credito, pedidos.fechacreacion, pedidos.total, usuarios.nombre AS nombreUsuario,clientes.ciudad AS clienteCiudad, clientes.estado AS clienteEstado, clientes.nombre AS nombreCliente, CONCAT(clientes.calle,' ',clientes.numero) AS domicilio, clientes.colonia AS colonia, CONCAT(clientes.ciudad,' ',clientes.estado) AS ciudad, clientes.cp AS cp, clientes.telefono AS telefono FROM pedidos INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente WHERE pedidos.id=$idpedido";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getListaPedidos($fIni,$fFin,$tipo,$usuario,$sucursal){
	$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, clientes.codigo AS codigoCliente, cat_proveedor.nombre AS nombreProveedor, pedidos.idusuario, cat_rutas.ruta FROM pedidos INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente INNER JOIN cat_proveedor ON pedidos_detalle.idproveedor=cat_proveedor.id INNER JOIN cat_rutas ON usuarios.id=cat_rutas.chofer WHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidoIndividual($fIni,$fFin){
	$consulta="SELECT pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total,pedidos_detalle.cantidad,pedidos_detalle.codigoproducto,pedidos_detalle.producto,pedidos_detalle.precio,pedidos_detalle.importe, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente FROM pedidos INNER JOIN pedidos_detalle ON pedidos_detalle.idpedido=pedidos.id INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idclienteWHERE pedidos.fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedidosJ($fIni,$fFin){
	$consulta="SELECT * FROM pedidos WHERE status=1 AND fecha BETWEEN '$fIni' AND '$fFin'";
	$query=$this->db->query($consulta);
	return $query;
}
public function getPedido($id){
	$consulta="SELECT pedidos.id,pedidos.folio, pedidos.tipo, pedidos.fecha, pedidos.total, usuarios.nombre AS nombreUsuario, clientes.nombre AS nombreCliente, cat_sucursales.sucursal AS sucursal, pedidos.idcliente, pedidos.latitud, pedidos.longitud FROM pedidos INNER JOIN usuarios ON usuarios.id=pedidos.idusuario INNER JOIN clientes ON clientes.id=pedidos.idcliente INNER JOIN cat_sucursales ON cat_sucursales.id=clientes.sucursal WHERE pedidos.id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
public function getDatosCliente($id){
	$consulta="SELECT * FROM clientes WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
public function getItemsJ($idpedido){
	$consulta="SELECT * FROM pedidos_detalle WHERE idpedido=$idpedido";
	$query=$this->db->query($consulta);
	return $query;
}
public function getCreadoporJ($idusuario){
	$consulta="SELECT * FROM usuarios WHERE id=$idusuario";
	$query=$this->db->query($consulta);
	return $query;
}
public function getClienteJ($id){
	$consulta="SELECT * FROM clientes WHERE id=$id";
	$query=$this->db->query($consulta);
	return $query;
}
public function getIdProductoJ($codigo){
	$consulta="SELECT * FROM cat_productos WHERE codigo='$codigo'";
	//echo $consulta;
	$query=$this->db->query($consulta);
	return $query;
}

public function getModulos()
	{
		$query = $this->db->query("SELECT * FROM modulos WHERE STATUS = 1 ORDER BY orden", false);
		return $query->result();
	}

	public function getSubModulos($pIdModulo)
	{
		$query = $this->db->query("SELECT * FROM modulos_sub WHERE STATUS = 1 AND idModulo = $pIdModulo ORDER BY orden", false);
		return $query;
	}
	public function getListaPerfiles(){
		$consulta="SELECT * FROM perfiles ORDER BY perfil ASC";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function getListaModulos(){
		$consulta="SELECT DISTINCT modulo, icono, color FROM funciones ORDER BY modulo ASC";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function getListaSubModulos($modulo){
		$consulta="SELECT id,submodulo,descripcion,perfiles,controlador,funcion FROM funciones WHERE modulo='$modulo' ORDER BY submodulo ASC";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function saveNewPerfil($datos){
		//print_r($_POST);
		$perfil=$_POST['txtPerfil'];
		$descripcion=$_POST['txtDescripcion'];
		if(isset($_POST['checkActivo'])){
			$activo=1;
		}
		else{
			$activo=0;
		}
		//ajustes,mas,redes moviles, operadores de red, manual, telcel 3g o 4g o telcel,
		$usuarioId=$this->session->userdata('userIdLIZER');
		$this->db->insert('perfiles',array('perfil'=>$perfil, 'descripcion'=>$descripcion, 'status'=>$activo, 'usuariocrea'=>$usuarioId));
		$consulta="SELECT id,perfiles FROM funciones";
		$query=$this->db->query($consulta);
		
		foreach ($query->result() as $kLista) {
			$id=$kLista->id;
			$perfiles=$kLista->perfiles;
			if(isset($_POST['Check'.$id])){
				$perfiles=$perfiles.$perfil.",";
				$consulta2="UPDATE funciones SET perfiles='$perfiles' WHERE id=$id";
				//echo "<br>".$consulta2;
				$query=$this->db->query($consulta2);
			}
		}
	}
	public function getDatosPerfil($id){
		$consulta="SELECT * FROM perfiles WHERE id=$id";
		$query=$this->db->query($consulta);
		return $query;
	}
	public function saveEditPerfil($datos){
		print_r($_POST);
		$id=$_POST['txtId'];
		$perfilold=$_POST['txtPerfilOld'];
		$perfil=$_POST['txtPerfil'];
		$descripcion=$_POST['txtDescripcion'];
		if(isset($_POST['checkActivo'])){
			$activo=1;
		}
		else{
			$activo=0;
		}
		//ajustes,mas,redes moviles, operadores de red, manual, telcel 3g o 4g o telcel,
		$usuarioId=$this->session->userdata('userIdLIZER');
		$consultaUP="UPDATE perfiles SET perfil='$perfil', descripcion='$descripcion', status=$activo WHERE id=$id";
		$this->db->query($consultaUP);
		
		$consulta="SELECT id,perfiles FROM funciones";
		$query=$this->db->query($consulta);
		
		foreach ($query->result() as $kLista) {
			$id=$kLista->id;
			$perfiles=$kLista->perfiles;
			$perfiles=str_replace($perfilold.",","", $perfiles);
			if(isset($_POST['Check'.$id])){
				$perfiles=$perfiles.$perfil.",";
				
			}
			$consulta2="UPDATE funciones SET perfiles='$perfiles' WHERE id=$id";
				
				$query=$this->db->query($consulta2);
		}
	}
	public function delPerfil($id,$perfil){
		$consultaDEL="DELETE FROM perfiles WHERE id=$id";
		$this->db->query($consultaDEL);
		$consulta="SELECT id,perfiles FROM funciones";
		$query=$this->db->query($consulta);
		
		foreach ($query->result() as $kLista) {
			$id=$kLista->id;
			$perfiles=$kLista->perfiles;
			$perfiles=str_replace($perfil.",","", $perfiles);
			$consulta2="UPDATE funciones SET perfiles='$perfiles' WHERE id=$id";	
				$query=$this->db->query($consulta2);
		}
	}

}

?>