<?php
	
	//registrar usuario ..
	/*$usuarios=new SoapClient("http://www.totalaccess.cl/server/obvii_registro.wsdl");
    $res= $usuarios->registrarUsuario('valemunoz@gmail.com', 'valemunoz@gmail.com', '123',  '1', '90780892', '0', '1', '0', '0');
    if ($res==1) {
        echo "registro usuario ok ..";
    }
	*/
	
	
	//registrar evento ..
	$eventos=new SoapClient("http://93.92.170.66:8080/server/obvii_eventos.wsdl");
	$res= $eventos->registrarEvento('130', '20130809', '101020', '-33.426714','-70.57478','5','etiqueta test 4','9988776644','478000012','descripcion test 4','8888999922','demo@demo.cl');
    if ($res==1) {
        echo "registro evento ok ..";
    }

	
	
	
	//login - validar usuario ..
/*	$eventos=new SoapClient("http://93.92.170.66:8080/server/obvii_login.wsdl");
	$res= $eventos->validarUsuario('valemunoz@gmail.com','123456');
	echo "<br>".$res;
    if ($res==1) {
        echo "login: usuario Valido";
    }else{
    	echo "login: usuario NO Valido!!";
    }
	
	*/
	
	//recuperar password ..
	/*$eventos=new SoapClient("http://93.92.170.66:8080/server/obvii_recuperar_pass.wsdl");
	$res= $eventos->recuperarPassword('valemunoz@gmail.com');	
	if ($res=='0') {
        echo "error: usuario No existe en registros de Base Datos ..";
    }else{
    	echo $res;
    }
	*/
		//lista de marcaciones
	/*$registros=new SoapClient("http://93.92.170.66:8080/server/obvii_registros_obvii_idti.wsdl");
$res= $registros->getRegistrosObvii_Idti(20140101,20140612,'desarrollo@architeq.cl');
 
echo '<pre>';
print_r($res);
echo '</pre>';
 */
	
	
 ?>
