<?php
include("funciones.php");
require_once("Mobile_Detect.php");
$estado_sesion=estado_sesion();

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$data_server= explode("?",$_SERVER['REQUEST_URI']);
$_SESSION['tipo_usuario']=$deviceType;
//print_r($detect);
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title>Obvii</title>
        <link rel="shortcut icon" href="images/point.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
				<meta name="author" content="Chilemap.cl" />     
         <link rel="stylesheet" href="css/themes/themes.css" />
         
         
  			<link rel="stylesheet" href="css/themes/jquery.mobile.icons.min.css" />
        <link rel="stylesheet" href="css/jquery.mobile.structure-1.4.0-rc.1.min.css" />
        
        <script src="js/jquery-1.10.2.min.js"></script> 
				<script src="js/jquery.mobile-1.4.0-rc.1.min.js"></script> 
				<script src="js/funciones.js"></script> 
        <link rel="stylesheet" href="css/style.mobile.css" type="text/css">
        <link rel="stylesheet" href="css/style.mobile-jq.css" type="text/css">
   			<link rel="stylesheet" href="css/style.css" />
    </head>
    <body onload=inicio();>

    <div data-role="page" id="mappage" data-theme="a">

   		<div data-role="header" >
   			 
      		<h1><img src="images/obvii-logo-white.png">     		 </h1>  		 
      		<img id=close_boton src="images/salir.png" class="ui-btn-right" onclick="cerrarSesion();">
   		</div>    		
   	  <div data-role="content" id="contenido" >
   	  	
    	  	<div data-role="popup" id="myPopup">
							<p>This is a completely basic popup, no options set.						</p>
					</div>
					<div class="ui-bar ui-bar-a" id=barra_sup style="text-align:right;">
					  <span class="titulo3">Bienvenido: <?=$_SESSION["id_usuario"]?></span>
					</div>
					
					<div id="contenido_sesion">
    	    	
					</div>          
					
    	</div>
    	<div data-role="footer" data-id="foo1" data-position="fixed">
	<div data-role="navbar">
		<ul>
			<li ><a  href="javascript:loadFav();"><img src="images/fav2.png"></a></li>
			<li ><a  href="javascript:loadHome();"><img src="images/icon-servicios.png"></a></li>
			<?php
			if($_SESSION["tipo_cli"]==1)
			{
			?>
			<li ><a  href="javascript:loadAsis();"><img src="images/student-32.png"></a></li>
			<?php
			}
			?>
			
			<li><a href="javascript:loadHistorial();"><img src="images/historial.png"></a></li>
			<li><a href="javascript:loadInfo();"><img src="images/icon-info.png"></a></li>
		</ul>
	</div><!-- /navbar -->
</div><!-- /footer -->
    	
    	
    	  
 </div>

    <div data-role="page" id="mod_registro" data-theme="a">

   	<?php
   	include("mod_registro.php")
   	?>	
    	  
 		</div>
 		<div data-role="page" id="mod_recuperar" data-theme="a">

   	<?php
   	include("mod_recuperar.php")
   	?>	
    	  
 		</div>
 		<div data-role="page" id="mod_sesion" data-theme="a">

   	<?php
   	include("mod_sesion.php")
   	?>	
    	  
 		</div> 		
 		
 		<script>
 			function inicio()
 			{
 				<?PHP
 				if($estado_sesion!=0) 				
 				{
 					?>
 					cambiar("mod_sesion");
 					<?php
 				}else
 				{
 					?>
 					loadFav();
 					<?php
 				}
 				//cerrar_sesion();
 					?>
 				
 			}
 			</script>
 			<div id=output></div>
    </body>
</html>
