<?php
include("connec.php");
define("PATH_SITE","http://localhost/github/obvii_web");
define("PATH_SITE_WEB","http://localhost/github/obvii_web/site");
define("PATH_SITE_ADMIN","http://localhost/github/obvii_web/admin");
//define("PATH_SITE","http://locate.chilemap.cl/obvii_v2");
//define("PATH_SITE_WEB","http://locate.chilemap.cl/obvii_v2/site");
//define("PATH_SITE_ADMIN","http://locate.chilemap.cl/obvii_v2/admin");

//define("PATH_WS_OBVII","http://www.totalaccess.cl/server/");
define("PATH_WS_OBVII","http://93.92.170.66:8080/server/");
define("WS_LOGIN","obvii_login.wsdl");
define("WS_LUGARES","obvii_registros_obvii_idti.wsdl");
define("WS_CONTRASENA","obvii_recuperar_pass.wsdl");
define("WS_REGISTROUSUARIO","obvii_registro.wsdl");
define("WS_MARCACION","obvii_eventos.wsdl");
define("ENCRIPTACION","semilla");
define("ENCRIPTACION2","manzana");
define("DIF_HORA","4");

function inicioSesion($mail,$id_user_obvii,$id_cliente,$tipo_cli)
{
	session_start();	
	//session_register('usuario');	
	$_SESSION["id_usuario"] = $mail;
	$_SESSION["id_cliente"] = $id_cliente;
	$_SESSION["id_usuario_obvii"] = $id_user_obvii;
	$_SESSION['fecha']=getFecha();
	$_SESSION["mail_log"]=$mail;
  $_SESSION["tipo_cli"]=$tipo_cli;
}
function cerrar_sesion()
{
	session_start();	
	unset($_SESSION["id_usuario"]); 
	unset($_SESSION['fecha']);
	unset($_SESSION['id_usuario_obvii']);
	unset($_SESSION['id_cliente']);	
	unset($_SESSION["tipo_cli"]);
	//session_destroy();
}
function estado_sesion()
{
	session_start();
	
	$estado=1;
	$hoy=date("Y-m-d H:i:s");
	
	$tiempo= segundos($_SESSION['fecha'],$hoy);
	
	if(isset($_SESSION['id_usuario']))	//7200
  {
  	$estado=0;
  }
  
  return $estado;
}

function segundos($hora_inicio,$hora_fin){
$hora_i=substr($hora_inicio,11,2);
$minutos_i=substr($hora_inicio,14,2);
$aÒo_i=substr($hora_inicio,0,4);
$mes_i=substr($hora_inicio,5,2);
$dia_i=substr($hora_inicio,8,2);
$hora_f=substr($hora_fin,11,2);
$minutos_f=substr($hora_fin,14,2);
$aÒo_f=substr($hora_fin,0,4);
$mes_f=substr($hora_fin,5,2);
$dia_f=substr($hora_fin,8,2);
$diferencia_seg=mktime($hora_f,$minutos_f,0,$mes_f,$dia_f,$aÒo_f) - mktime($hora_i,$minutos_i,0,$mes_i,$dia_i,$aÒo_i);
return $diferencia_seg;
}
function sendMail($para,$msg,$titulo)
{
	
	
	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	// Cabeceras adicionales
	$cabeceras .= 'From: contacto@architeq.cl' . "\r\n";
	$cabeceras .= 'Reply-To: contacto@architeq.cl' . "\r\n";
	
	
	if(mail($para, $titulo, $msg, $cabeceras))
	{
		$envio=true;
	}else
	{
		$envio=false;
	}
	return $envio;
}
function getDireccionGoogle($direccion)
{
	
	
	$delay = 0;
	//$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;
	$base_url="http://maps.googleapis.com/maps/api/geocode/xml?";
  $geocode_pending = true;
  while ($geocode_pending) {
    //$address = "pasaje u 2113 chile";
    $address=trim($direccion);
    $request_url = $base_url . "&address=" . urlencode($address)."!&oe=utf-8&sensor=false";
    //echo "<br>".$request_url;
    $xml = simplexml_load_file($request_url) or die("url not loading");    
    //print_r($xml);
    $status = $xml->status;
    $geocode_pending = false;
    if (strcmp($status, "OK") == 0) {
      // Successful geocode
      $geocode_pending = false;
      
      $total_r=$xml->result;
      
      $len_place=$xml;
      $i=1;
      foreach($len_place->result as $len)
      {
      	$direc = $len->formatted_address;
      	$tipo = $len->type;
      	//echo "total:".count($len->address_component);
      	for($i=0;$i<count($len->address_component);$i++)
      	{
      		$type=$len->address_component[$i]->type;
      		$type2=$len->address_component[$i]->type[0];
      		if(strtolower(trim($type))=="street_number")
      		{
      			$numero_municipal=$len->address_component[$i]->long_name;
      		}elseif(strtolower(trim($type))=="route")
      		{
      			$calle=$len->address_component[$i]->long_name;
      			$abrevia_calle=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="locality")
      		{
      			$ciudad=$len->address_component[$i]->long_name;
      			$abrevia_ciudad=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="administrative_area_level_3")
      		{
      			$comuna=$len->address_component[$i]->long_name;
      			$abrevia_comuna=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="administrative_area_level_1")
      		{
      			$region=$len->address_component[$i]->long_name;
      			$abrevia_region=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="country")
      		{
      			$pais=$len->address_component[$i]->long_name;
      			$abrevia_pais=$len->address_component[$i]->short_name;
      		}
      		
      		
      	}
      	//geometrias
      	$latitud=$len->geometry->location->lat;
      	$longitud=$len->geometry->location->lng;
      	$tipo_gis=$len->geometry->location_type;
      	
      	$dire=Array();
				$dire[]=$tipo;
				$dire[]=$direc;
				$dire[]=$numero_municipal;
				$dire[]=$calle;
				$dire[]=$comuna;
				$dire[]=$ciudad;
				$dire[]=$region;
				$dire[]=$latitud;
				$dire[]=$longitud;
				$dire[]=$tipo_gis;
      	$direccion_arr[]=$dire;
				$i++;
    	}      
    } 
    usleep($delay);
  }
 
	return $direccion_arr;
}
function buscarDireccionOSM($query)
{
	
	$delay = 0;
	
	$base_url="http://nominatim.openstreetmap.org/search?";
  $geocode_pending = true;
  while ($geocode_pending) {
    //$address = "pasaje u 2113 chile";
    $address=trim($direccion);
    $request_url = $base_url . "q=".urldecode($query)."&format=xml&polygon=1&addressdetails=1";
    //echo "<br>".$request_url;
    $xml = simplexml_load_file($request_url) or die("url not loading");    
   // print_r($xml);
    //$status = $xml->status;
    $geocode_pending=false;
    //echo count($xml->place);
    $lonlat_arr=array();
    
    foreach($xml->place as $place)
    {
    	$place=$xml->place;
    	
   		
    		$lonlat=Array();
    		$lonlat[]=$place['lon'];
    		$lonlat[]=$place['lat'];
    		$lonlat[]=$place->house_number;
    		$lonlat[]=$place->road;
    		$lonlat[]=$place->city;
    		$lonlat[]=$place->country;
    		$lonlat[]=$place->state;
				$lonlat[]=$place['lat'];
    		$lonlat[]=$place['lon'];
    		$lonlat_arr[]=$lonlat;
    	
    	//echo "<br>".$longitud;
    	//print_r($xml_result);
    }
  }
  return $lonlat_arr;
}


function addLugarObvii($data)
{
	$dbPg=pgSql_db();	
	$sql="INSERT INTO obvii_lugares(
             nombre, fecha_registro, estado, latitud, longitud, 
            calle, numero_municipal, comuna, geom, id_usuario, mail_post,comentario,marcacion,id_cliente,mail_lista)
    VALUES ('".$data[0]."', '".getFechaLibre(DIF_HORA)."', 0, '".$data[1]."' , '".$data[2]."', 
            '".$data[3]."', '".$data[4]."', '".$data[5]."', ST_GeomFromText('POINT(".$data[2]." ".$data[1].")',2276), '".$data[6]."', '".$data[7]."' , '".$data[8]."', '".$data[9]."','".$data[10]."','".$data[11]."');";            
  //echo "<br>".$sql;
  $rsCalle = pg_query($dbPg, $sql);	
}
function getLugares($qr)
{
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_lugar, nombre, fecha_registro, estado, latitud, longitud, 
       calle, numero_municipal, comuna, geom, mail_post, id_usuario, 
       comentario,marcacion,mail_lista FROM obvii_lugares where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  //echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
			$data[]=$row2[1];
			$data[]=$row2[2];
			$data[]=$row2[3];
			$data[]=$row2[4];
			$data[]=$row2[5];
			$data[]=$row2[6];
			$data[]=$row2[7];
			$data[]=$row2[8];
			$data[]=$row2[9];
			$data[]=$row2[10];
			$data[]=$row2[11];
			$data[]=$row2[12];
			$data[]=$row2[13];
			$data[]=$row2[14];
			$datos[]=$data;
		}
		pg_close($dbPg);
		
		return $datos;
}
function updateLugarObvii($qr,$id_lugar)
{
	$dbPg=pgSql_db();	
	$sql="update obvii_lugares set ".$qr." where id_lugar=".$id_lugar.";";            
  //echo "<br>".$sql;
  $rsCalle = pg_query($dbPg, $sql);	
}

function addMarcacion($data)
{
	$dbPg=pgSql_db();
  $sql="INSERT INTO obvii_marcacion(
             id_usuario, id_usuario_obvii, fecha_registro, tipo, 
            id_lugar, lat, lon, presicion,comentario,tipo_marcacion,nombre_lugar,id_cliente,direccion_libre)
    VALUES ('".$data[0]."', '".$data[1]."','".getFechaLibre(DIF_HORA)."' , '".$data[2]."', 
            '".$data[3]."', '".$data[4]."', '".$data[5]."', '".$data[6]."', '".$data[7]."', '".$data[8]."', '".$data[9]."', '".$data[10]."','".$data[11]."');";
   $rsCalle = pg_query($dbPg, $sql);
}

function getMarcaciones($qr)
{
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_marcacion, id_usuario, id_usuario_obvii, fecha_registro, tipo, 
       id_lugar, lat, lon, presicion, comentario, tipo_marcacion,nombre_lugar,direccion_libre 
  FROM obvii_marcacion where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
			$data[]=$row2[1];
			$data[]=$row2[2];
			$data[]=$row2[3];
			$data[]=$row2[4];
			$data[]=$row2[5];
			$data[]=$row2[6];
			$data[]=$row2[7];
			$data[]=$row2[8];
			$data[]=$row2[9];
			$data[]=$row2[10];
			$data[]=$row2[11];
			$data[]=$row2[12];

			$datos[]=$data;
		}
		pg_close($dbPg);
		
		return $datos;
}
function elimina_acentos($cadena)
{
	
	$tofind = "¿¡¬√ƒ≈‡·‚„‰Â“”‘’÷ÿÚÛÙıˆ¯»… ÀËÈÍÎ«ÁÃÕŒœÏÌÓÔŸ⁄€‹˘˙˚¸ˇ—Ò";
	$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	return(strtr($cadena,$tofind,$replac));
}
function getFecha()
{
	$fecha=date("Y-m-d H:i:s");
	$fecha_actual2 = strtotime ( '-4 hours ' , strtotime ( $fecha ) ) ;
	$fec = date ( 'Y-m-d H:i:s' , $fecha_actual2 );
	return $fec;
}
function getFechaLibre($horas)
{
	$fecha=date("Y-m-d H:i:s");
	$fecha_actual2 = strtotime ( '-'.$horas.' hours ' , strtotime ( $fecha ) ) ;
	$fec = date ( 'Y-m-d H:i:s' , $fecha_actual2 );
	return $fec;
}

function getUsuario($qr)
{
	
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_usuario,mail,fecha_registro,estado,id_cliente,tipo_usuario,clave,nombre from obvii_usuario where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  //echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
				$data[]=$row2[1];
				$data[]=$row2[2];
				$data[]=$row2[3];
				$data[]=$row2[4];
				$data[]=$row2[5];
				$data[]=$row2[6];
				$data[]=$row2[7];
		}
		return $data;
}
function getUsuarios($qr)
{
	
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_usuario,mail,fecha_registro,estado,id_cliente,tipo_usuario,clave,nombre from obvii_usuario where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  //echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
				$data[]=$row2[1];
				$data[]=$row2[2];
				$data[]=$row2[3];
				$data[]=$row2[4];
				$data[]=$row2[5];
				$data[]=$row2[6];
				$data[]=$row2[7];
				$datos[]=$data;
		}
		return $datos;
}
function addFavorito($data)
{
		$dbPg=pgSql_db();
	$sql="INSERT INTO obvii_favorito(
             id_usuario, id_usuario_obvii, id_lugar, fecha_registro, 
            estado)
    VALUES ('".$data[0]."', '".$data[1]."', '".$data[2]."', '".getFechaLibre(DIF_HORA)."', 
            0);";
            $rs2 = pg_query($dbPg, $sql);
            
            pg_close($dbPg);
}
function getFavoritos($qr)
{
	
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_favorito, id_usuario, id_usuario_obvii, id_lugar, fecha_registro, 
       estado FROM obvii_favorito where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
				$data[]=$row2[1];
				$data[]=$row2[2];
				$data[]=$row2[3];
				$data[]=$row2[4];
				$data[]=$row2[5];
				$datos[]=$data;
		}
		return $datos;
}
function upFavoritos($qr,$id)
{
	
	$dbPg=pgSql_db();
	
  $sql2 = "update obvii_favorito set ".$qr." where id_favorito=".$id."";		
  
  $rs2 = pg_query($dbPg, $sql2);

}

function inicioSesion_web($mail,$id_usuario,$cliente,$id_obvii,$pais,$tipo_cli)
{
	
	session_start();
	
	//session_register('usuario');
	$_SESSION["usuario_web"] = $mail;
	$_SESSION["id_usuario_web"] = $id_usuario;
	$_SESSION['fecha_web']=date("Y-m-d H:i:s");
	$_SESSION["id_usuario_web_obvii"]=$id_obvii;
	$_SESSION['id_cliente_web']=$cliente;
	$_SESSION['pais_web']=$pais;
	$_SESSION['tip_cli_web']=	$tipo_cli;
	
	
	
}
function cerrar_sesion_web()
{
	session_start();
	unset($_SESSION["usuario_web"]); 
	unset($_SESSION["fecha_web"]); 
	unset($_SESSION["id_usuario_web_obvii"]);
	unset($_SESSION["id_usuario_web"]); 
	unset($_SESSION['id_cliente_web']);
	unset($_SESSION['pais_web']);
	unset($_SESSION['tip_cli_web']);
	//session_destroy();
}
function estado_sesion_web()
{
	session_start();
	
	$estado=1;
	$hoy=date("Y-m-d H:i:s");
	
	$tiempo= segundos($_SESSION['fecha_web'],$hoy);
	
	if(isset($_SESSION['usuario_web']) and trim($_SESSION['usuario_web'])!="" and $tiempo < 7200)	//7200
  {
  	$estado=0;
  }
  
  return $estado;
}
function updateUsuario($qr,$id)
{
	$dbPg=pgSql_db();
	
 $sql2 = "update obvii_usuario set ".$qr." where id_usuario=".$id."";		
  
  $rs2 = pg_query($dbPg, $sql2);
}
function addUsuario($data)
{
	$dbPg=pgSql_db();
	
 $sql2 = "INSERT INTO obvii_usuario(
             mail, fecha_registro, estado, id_cliente, tipo_usuario, 
            clave,nombre)
    VALUES ('".$data[0]."', '".getFechaLibre(DIF_HORA)."', 0, '".$data[1]."', '".$data[2]."', 
            '".$data[3]."','".$data[4]."');";		
  
  $rs2 = pg_query($dbPg, $sql2);
}
function encrypt($string, $key) {
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   //$cadena_encriptada = encrypt("LA CADENA A ENCRIPTAR","LA CLAVE");
   return base64_encode($result);
}
function decrypt($string, $key) {
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   //$cadena_desencriptada = decrypt("LA CADENA ENCRIPTADA","LA CLAVE QUE SE US” PARA ENCRIPTARLA");
   return $result;
}
function getUsuarioAdmin($qr)
{
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_usuario_admin, nombre, mail, clave,estado FROM obvii_administrador where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
				$data[]=$row2[1];
				$data[]=$row2[2];
				$data[]=$row2[3];
				$data[]=$row2[4];
				
				$datos[]=$data;
		}
		return $datos;
}
function inicioSesion_admin($mail,$id_usuario)
{
	
	session_start();
	
	//session_register('usuario');
	$_SESSION["usuario_admin"] = $mail;
	$_SESSION["id_usuario_admin"] = $id_usuario;
	$_SESSION['fecha_admin']=date("Y-m-d H:i:s");
	
	
	
	
	
}
function cerrar_sesion_admin()
{
	session_start();
	unset($_SESSION["usuario_admin"]); 
	unset($_SESSION["fecha_admin"]); 
	
	unset($_SESSION["id_usuario_admin"]); 
	
	//session_destroy();
}
function estado_sesion_admin()
{
	session_start();
	
	$estado=1;
	$hoy=date("Y-m-d H:i:s");
	
	$tiempo= segundos($_SESSION['fecha_admin'],$hoy);
	
	if(isset($_SESSION['usuario_admin']) and trim($_SESSION['usuario_admin'])!="" and $tiempo < 7200)	//7200
  {
  	$estado=0;
  }
  
  return $estado;
}
function updateUsuarioadmin($qr,$id)
{
	$dbPg=pgSql_db();
	
 $sql2 = "update obvii_administrador set ".$qr." where id_usuario_admin=".$id."";		
  
  $rs2 = pg_query($dbPg, $sql2);
}
function addUsuarioAdmin($data)
{
	$dbPg=pgSql_db();
	
 $sql2 = "INSERT INTO obvii_administrador(
             mail, fecha_registro, estado,clave,nombre)
    VALUES ('".$data[0]."', '".getFechaLibre(DIF_HORA)."', 0, '".$data[1]."', '".$data[2]."');";		
  
  $rs2 = pg_query($dbPg, $sql2);
}

function getCliente($qr)
{
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_cliente, nombre,estado,mail,pais,tipo FROM obvii_cliente where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  //echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
				$data[]=$row2[1];
				$data[]=$row2[2];
				$data[]=$row2[3];
				$data[]=$row2[4];
				$data[]=$row2[5];
				
				
				$datos[]=$data;
		}
		return $datos;
}
function updateCli($qr,$id)
{
	$dbPg=pgSql_db();
	
 $sql2 = "update obvii_cliente set ".$qr." where id_cliente=".$id."";		
  
  $rs2 = pg_query($dbPg, $sql2);
}

function addCliente($data)
{
	
	$dbPg=pgSql_db();
	
  $sql2 = "INSERT INTO obvii_cliente(
             nombre, estado,mail,pais,tipo)
    VALUES ('".$data[0]."', 0,'".$data[1]."','".$data[2]."','".$data[3]."');";		
  
  $rs2 = pg_query($dbPg, $sql2);

}

function getPais($qr)
{
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_pais, nombre,estado FROM obvii_pais where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  //echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
				$data[]=$row2[1];
				$data[]=$row2[2];

				
				
				$datos[]=$data;
		}
		return $datos;
}

function getUsuariosInterno($qr)
{
	
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_usuario_interno, nombre, estado, id_lugar, fecha_registro FROM obvii_usuarios_internos where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  //echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
				$data[]=$row2[1];
				$data[]=$row2[2];
				$data[]=$row2[3];
				$data[]=$row2[4];

				$datos[]=$data;
		}
		return $datos;
}
function addUsuarioInt($data)
{
	
	$dbPg=pgSql_db();
	
  $sql2 = "INSERT INTO obvii_usuarios_internos(
            nombre, estado, id_lugar, fecha_registro)
    VALUES ('".$data[0]."', '".$data[1]."', '".$data[2]."', '".date("Y-m-d H:i:s")."')";		
 
  $rs2 = pg_query($dbPg, $sql2);

	
}
function updateUsuarioInt($qr,$id)
{
	$dbPg=pgSql_db();
	
 $sql2 = "update obvii_usuarios_internos set ".$qr." where id_usuario_interno=".$id."";		
  $rs2 = pg_query($dbPg, $sql2);
}
function addMarcaInt($data)
{
	$dbPg=pgSql_db();
	$sql="INSERT INTO obvii_marcacion_interna(
             id_usuario, fecha_registro, id_lugar, asiste,estado,id_marca_base)
    VALUES ('".$data[0]."', '".getFechaLibre(DIF_HORA)."', '".$data[1]."', '".$data[2]."',0,'".$data[3]."')";
    $rs2 = pg_query($dbPg, $sql);
}
function getMarcaInt($qr)
{
	
	$dbPg=pgSql_db();
	
  $sql2 = "SELECT id_marca, id_usuario, fecha_registro, id_lugar, asiste, estado, id_marca_base FROM obvii_marcacion_interna where 1=1";		
  if($qr!="")
  {
  	$sql2 .=$qr;
  }
  //echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$data=array();
			$data[]=$row2[0];
				$data[]=$row2[1];
				$data[]=$row2[2];
				$data[]=$row2[3];
				$data[]=$row2[4];
				$data[]=$row2[5];
				$data[]=$row2[6];
				$datos[]=$data;
		}
		return $datos;
}
function updateMarcaInt($qr,$id)
{
	$dbPg=pgSql_db();
	
 $sql2 = "update obvii_marcacion_interna set ".$qr." where id_marca=".$id."";		
  $rs2 = pg_query($dbPg, $sql2);
}
?>