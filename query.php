<?php
include("funciones.php");
$data_server= explode("?",$_SERVER['HTTP_REFERER']);
$estado_sesion=estado_sesion();
if(substr(strtolower($data_server[0]),0,strlen(PATH_SITE))==PATH_SITE)
{
	
	$cliente=getCliente(" and id_cliente=".$_SESSION['id_cliente']."");
	//print_r($_SESSION);
	if($cliente[0][2]!=0)
	{				
		cerrar_sesion();
		?>
						<script>
							
							window.location.href="index.php";
							
						</script>
		<?php
		
	}
	if($_REQUEST['tipo']==1)
	{
		try
		{
			$eventos=new SoapClient("".PATH_WS_OBVII."".WS_LOGIN."");
			$res= $eventos->validarUsuario(trim(strtolower($_REQUEST['mail'])),trim($_REQUEST['clave']));
		  //if ($res==$_REQUEST['clave']) 
    	if ($res >0) 
    	{
    		$cliente=getUsuario(" and mail like '".trim(strtolower($_REQUEST['mail']))."' and estado=0");
    		$id_cliente=1;
    		if(count($cliente)>0)
    		{
    			$id_cliente=$cliente[4];
    			updateUsuario("id_usuario_obvii=".$res.", clave='".$_REQUEST['clave']."'",$cliente[0]);
    		}
    		
    			$cliente=getCliente(" and id_cliente=".$id_cliente."");
					if($cliente[0][2]==0)
					{				
    				inicioSesion(strtolower($_REQUEST['mail']),$res,$id_cliente,$cliente[0][5]);
    				?>
						<script>
							window.location.href="index.php";
						</script>
						<?php
    			}else
    			{
    				?>
				<script>
					mensaje("Acceso no autorizado","ERROR","myPopup_ses");
				</script>
				<?php
    			}
    		
    	  
    	}else{    	
    		?>
				<script>
					mensaje("Mail o Clave incorrectas","ERROR","myPopup_ses");
				</script>
				<?php
    	}
		} catch (Exception $e) 
		{
			?>
			<script>
				mensaje("Problemas de conexi&oacute;n, por favor int&eacute;ntelo nuevamente.","ERROR","myPopup_ses");
			</script>
			<?php
		}

		
	}elseif($estado_sesion==0 and $_REQUEST['tipo']==2)
	{
		?>
		
		<h4>Marcador de Asistencia</h4>
		<input type="button" onclick="loadNuevo();" value="Marcaci&oacute;n Libre">
		<?php
		$fecha=date("Ymd");
		try
		{
			
			//$registros=new SoapClient("".PATH_WS_OBVII."".WS_LUGARES."");
			//$res= $registros->getRegistrosObvii_Idti(20140101,$fecha,"desarrollo@architeq.cl");
			//print_r($res);
			$lugares=getLugares(" and estado=0 and id_cliente=".$_SESSION["id_cliente"]." order by nombre");
			
			if(count($lugares)>0)
			{
			
				?>
				<ul data-role="listview"  data-theme="b"  data-filter="true" data-filter-placeholder="Buscar" data-inset="false" id="list_registros">	
					<?php
					foreach($lugares as $lug)
					{
						$favorito=getFavoritos(" and id_usuario ilike '".$_SESSION["id_usuario"]."' and estado=0 and id_lugar=".$lug[0]."");
						if(count($favorito)==0)
						{
						$txt_hora="";
						$marcacion=getMarcaciones(" and id_usuario ilike '".$_SESSION["id_usuario"]."' and id_lugar=".$lug[0]." and fecha_registro >= '".date("Y-m-d")."' order by fecha_registro desc limit 1");
						$img_asis="images/icon-servicios.png";
						/*if($_SESSION["tipo_cli"]==1)
						{
							$img_asis="images/student.png";
						}*/
						if(count($marcacion)>0)
						{
							$txt_hora="<img width=20px src='".$img_asis."'>  ".substr($marcacion[0][3], 11);
						}
						$clase="txt_mini";
						$fecha=$marcacion[0][3];
						$segundos=strtotime(getFechaLibre(DIF_HORA))-strtotime($fecha);
						//$diferencia_dias=intval($segundos/60/60/24);
	  				$diferencia_horas=intval($segundos/60/60);
    				
						if($diferencia_horas > 8)
						{
							$clase="txt_mini4";
						}
						$nombre=$lug[1];
						$largo=20;
						 if($_SESSION['tipo_usuario']=="computer")
						 {
						 	$largo=100;
						 }elseif($_SESSION['tipo_usuario']=="tablet")
						 {
						 	$largo=40;	
						 }
		 
					 if(strlen($nombre)> $largo)
					 {
					 	$paso=true;
					 	$nom_resto=$nombre;
					 	$nombre_final="";
					 	while($paso)
					 	{ 		
					 	 $nombre2=substr($nom_resto, 0,$largo);	
					 	 $nom=substr($nom_resto, $largo);
					 	 
					 	 $nombre_final .="<br>".$nombre2;
					 		if(trim($nom)!="")
					 		{
					 				
					 				$nom_resto=$nom;
					 		}else
					 		{
					 		  $paso=false;		
					 		}
					 	}
					 	$nombre=$nombre_final;
					}
					?>
						<li><a  href="javascript:marcar(<?=$lug[0]?>,'<?=$lug[12]?>','<?=$lug[13]?>');"><span class=titulo2><?=ucwords($nombre)?></span> <p class="ui-li-aside"><span class=<?=$clase?>><?=$txt_hora?></span></p></a>					
							<a href="javascript:loadEditar(<?=$lug[0]?>);" data-rel="popup" data-position-to="window" data-transition="pop" data-icon="search">Editar</a>
							
						</li>

						
				  <?php
					}
					}
				?>
			  </ul>	
				<?php
			
			}else
			{
				include("home.php");
			}
		}catch (Exception $e) 
		{
			?>
			<script>
				mensaje("Problemas de conexi&oacute;n, por favor int&eacute;ntelo nuevamente.","ERROR","myPopup");
			</script>
			<?php
		}
    ?>
  
    <?php		
 
	}elseif( $_REQUEST['tipo']==3)
	{
		cerrar_sesion();
	}elseif($_REQUEST['tipo']==4)
	{
		try
		{
	  	$usuarios=new SoapClient("".PATH_WS_OBVII."".WS_REGISTROUSUARIO."");
    	$res= $usuarios->registrarUsuario($_REQUEST['mail'], $_REQUEST['nombre'], $_REQUEST['clave'],  '1', '0', '0', '1', '0', '0');
    	if ($res==1) {
        ?>
			<script>
				cambiar("mod_sesion");
			</script>
			<?php
    	}else
    	{
    		?>
			<script>
				mensaje("Usuario ya se encuentra registrado.","ERROR","myPopup_reg");
			</script>
			<?php
    	}
    }catch (Exception $e) 
		{
			?>
			<script>
				mensaje("Problemas de conexi&oacute;n, por favor int&eacute;ntelo nuevamente.","ERROR","myPopup_reg");
			</script>
			<?php
		}
	
	}elseif($_REQUEST['tipo']==5)
	{
		try
		{
			$usuarios=new SoapClient("".PATH_WS_OBVII."".WS_CONTRASENA."");
    	$res= $usuarios->recuperarPassword(trim($_REQUEST['mail']));
    	if ($res=='0') {
        
    		?>
			<script>
				mensaje("Usuario no existe en nuestros registros.","ERROR","myPopup_rec");
			</script>
			<?php
    	}else
    	{
    		$clave=str_replace("pass","",strtolower($res));
    		$msg="Haz solicitado la recuperacion de tu contrase&ntilde;a del servicio de asistencia de Obvii.<br> <br> Tu contrase&ntilde;a es: <strong>".$clave."</strong>";
    		$msg .="<br><br>Equipo de Obvii";
    		$envio=sendMail($_REQUEST['mail'],$msg,"Obvii");
    		?>
			<script>
				mensaje("Se ha enviado un mail con la contrase&ntilde;a.","MENSAJE","myPopup_rec");
			</script>
			<?php
    	}
    }catch (Exception $e) 
		{
			?>
			<script>
				mensaje("Problemas de conexi&oacute;n, por favor int&eacute;ntelo nuevamente.","ERROR","myPopup_reg");
			</script>
			<?php
		}
	}elseif($_REQUEST['tipo']==6 and $estado_sesion==0) //add lugar
	{
		//consulta direc exacta con chilemap
		
		$direccion=file_get_contents("http://www.chilemap.cl/ws/ws.php?query=".str_replace(" ","+",elimina_acentos(utf8_decode($_REQUEST['calle'])))."+".str_replace(" ","+",$_REQUEST['numero'])."+".str_replace(" ","+",elimina_acentos(utf8_decode($_REQUEST['com'])))."");
		$direc=explode(",",$direccion);
		$lat=0;
		$lon=0;
		if(count($direc)> 1)
		{
			$tipo=1;
			$lat=$direc[6];
			$lon=$direc[7];
		}else //consulta a google o OSM
		{
			
			$direccion=buscarDireccionOSM("".elimina_acentos(utf8_decode($_REQUEST['calle']))." ".$_REQUEST['numero']." ".elimina_acentos(utf8_decode($_REQUEST['com']))." chile");
			if(count($direccion)>0)
			{
				$tipo=2;
				//echo "aqui::".$direccion[0][2];
				$lat=$direccion[0][1];
				$lon=$direccion[0][0];
			}else
			{
				$direccion=getDireccionGoogle("".elimina_acentos(utf8_decode($_REQUEST['calle']))." ".$_REQUEST['numero']." ".elimina_acentos(utf8_decode($_REQUEST['com']))." chile");
				if(count($direccion)>0)
				{
					$tipo=3;
					//echo "aqui::".$direccion[0][2];
					$lat=$direccion[0][7];
					$lon=$direccion[0][8];
				}
				
			}
		}
		
		
		if($lat==0 or $lon==0)
		{
			?>
			<script>
				$.mobile.loading( 'hide');
				mensaje("Direcci&oacute;n no encontrada",'ERROR','myPopup');
				</script>
			<?php
		}else //agrega marcador
		{
			
			$usuario=$_SESSION["id_usuario"];
			$data=array();
			$data[]=$_REQUEST['nom'];
			$data[]=$lat;
			$data[]=$lon;
			$data[]=strtolower(elimina_acentos(utf8_decode($_REQUEST['calle'])));
			$data[]=$_REQUEST['numero'];
			$data[]=strtolower(elimina_acentos(utf8_decode($_REQUEST['com'])));
			$data[]=$usuario;
			$data[]=strtolower($_REQUEST['mail']);
			$coment=true;
			if($_REQUEST['comen']=="off")
			{
				$coment=f;
			}
			$data[]=$coment;
			
			
			$marca=true;
			if($_REQUEST['marcacion']=="off")
			{
				$marca=f;
			}
			$data[]=$marca;
			$data[]=$_SESSION['id_cliente'];
			addLugarObvii($data);
			
			?>
			<script>
				$.mobile.loading( 'hide');
				loadHome();
				mensaje("Lugar Almacenado",'MENSAJE','myPopup');
			</script>
			<?php
			
		}
	}elseif($_REQUEST['tipo']==7 and $estado_sesion==0) //update lugar
	{
		//consulta direc exacta con chilemap
		$lugares=getLugares(" and id_lugar=".$_REQUEST['id']."");
		if(strtolower($_REQUEST['calle'])!= $lugares[0][6] or $_REQUEST['numero']!= $lugares[0][7] or strtolower($_REQUEST['comuna'])!= $lugares[0][7])
		{
			$direccion=file_get_contents("http://www.chilemap.cl/ws/ws.php?query=".str_replace(" ","+",elimina_acentos(utf8_decode($_REQUEST['calle'])))."+".str_replace(" ","+",$_REQUEST['numero'])."+".str_replace(" ","+",elimina_acentos(utf8_decode($_REQUEST['com'])))."");
			$direc=explode(",",$direccion);
			$lat=0;
			$lon=0;
			if(count($direc)> 1)
			{
				$lat=$direc[6];
				$lon=$direc[7];
			}else //consulta a google o OSM
			{
				
				$direccion=buscarDireccionOSM("".elimina_acentos(utf8_decode($_REQUEST['calle']))." ".$_REQUEST['numero']." ".elimina_acentos(utf8_decode($_REQUEST['com']))." chile");
				if(count($direccion)>0)
				{
					//echo "aqui::".$direccion[0][2];
					$lat=$direccion[0][1];
					$lon=$direccion[0][0];
				}else
				{
					$direccion=getDireccionGoogle("".elimina_acentos(utf8_decode($_REQUEST['calle']))." ".$_REQUEST['numero']." ".elimina_acentos(utf8_decode($_REQUEST['com']))." chile");
					if(count($direccion)>0)
					{
						//echo "aqui::".$direccion[0][2];
						$lat=$direccion[0][7];
						$lon=$direccion[0][8];
					}
					
				}
			}
			
		}else
		{
			$lat=$lugares[0][4];
			$lon=$lugares[0][5];
		}
		if($lat==0 or $lon==0)
		{
			?>
			<script>
				$.mobile.loading( 'hide');
				mensaje("Direcci&oacute;n no encontrada",'ERROR','myPopup');
				</script>
			<?php
		}else //agrega marcador
		{
			
			$usuario=$_SESSION["id_usuario"];

			$coment=true;
			if($_REQUEST['comen']=="off")
			{
				$coment=f;
			}
			$marca=true;
			if($_REQUEST['marca']=="off")
			{
				$marca=f;
			}
			$data[]=$coment;
			updateLugarObvii(" marcacion='".$marca."', comentario='".$coment."' , nombre='".$_REQUEST['nom']."', calle='".strtolower(elimina_acentos($_REQUEST['calle']))."', numero_municipal='".$_REQUEST['numero']."', comuna='".strtolower(elimina_acentos($_REQUEST['com']))."'",$_REQUEST['id']);
			
			?>
			<script>
				$.mobile.loading( 'hide');
				loadHome();
				mensaje("Lugar Modificado",'MENSAJE','myPopup');
			</script>
			<?php
			
		}
	}elseif($_REQUEST['tipo']==8 and $estado_sesion==0) //marcar lugar
	{
		 $lugares=getLugares(" and id_lugar=".$_REQUEST['id']."");
	   $registros=new SoapClient("".PATH_WS_OBVII."".WS_MARCACION."");
	   //echo "AQUI::".$_SESSION['id_usuario_obvii'].",".date("Ymd").", ".date('His').", ".$_REQUEST['lat'].",".$_REQUEST['lon'].",".$_REQUEST['accu'].",".$lugares[0][1].",'9988776644','478000012',".$_REQUEST['coment'].",'8888999922',".$mail_post."";
	   $mail_post=trim($lugares[0][10]);
	   
	   if($mail_post=="")
	   {
	   	$cliente=getCliente(" and id_cliente=".$_SESSION["id_cliente"]."");
	   	$mail_post=$cliente[0][3];
	   }
		 $res= $registros->registrarEvento($_SESSION['id_usuario_obvii'], ''.date("Ymd").'', ''.date("His").'', ''.$_REQUEST['lat'].'',''.$_REQUEST['lon'].'',''.$_REQUEST['accu'].'',''.$lugares[0][1].'','9988776644','478000012',''.$_REQUEST['coment'].'','8888999922',''.$mail_post.'');
		 if($res>0)
		 {
		 	$data=array();
		 	$data[]=$_SESSION["id_usuario"];
		 	$data[]=$_SESSION["id_usuario_obvii"];
		 	$data[]=0;
		 	$data[]=$_REQUEST['id'];
		 	$data[]=$_REQUEST['lat'];
		 	$data[]=$_REQUEST['lon'];
		 	$data[]=$_REQUEST['accu'];
		 	$data[]=$_REQUEST['coment'];
		 	$data[]=$_REQUEST['tipo_marca'];
		 	$data[]=$lugares[0][1];
		 	$data[]=$_SESSION["id_cliente"];
		 	$data[]="";
		 	addMarcacion($data);
		 	if($_SESSION["tipo_cli"]==1)
		 	{
		 				 ?>
			<script>
				$.mobile.loading( 'hide');
				loadAsis();
				mensaje("Marcaci&oacute;n realizada",'MENSAJE','myPopup');
			</script>
			<?php
		 	}else
		 	{
		 				 ?>
			<script>
				$.mobile.loading( 'hide');
				loadHome();
				mensaje("Marcaci&oacute;n realizada",'MENSAJE','myPopup');
			</script>
			<?php
		 	}

		}else
		{
				?>
			<script>
				$.mobile.loading( 'hide');
				loadHome();
				mensaje("Se produjo un error, por favor int&eacute;ntarlo nuevamente",'ERROR','myPopup');
			</script>
			<?php
			}
	}elseif($_REQUEST['tipo']==9 and $estado_sesion==0) //eliminar lugar
	{
		updateLugarObvii(" estado=1",$_REQUEST['id']);
		
	}elseif($_REQUEST['tipo']==10 and $estado_sesion==0) //fav lugar
	{
		$data=array();
		$data[]=$_SESSION["id_usuario"];
		$data[]=$_SESSION["id_usuario_obvii"];
		$data[]=$_REQUEST['id'];
		addFavorito($data);
	}elseif($estado_sesion==0 and $_REQUEST['tipo']==11)
	{
		?>
		
		<h4>Marcaciones Favoritas</h4>
		<?php
		$fecha=date("Ymd");
		try
		{

			//$lugares=getLugares(" and id_usuario ilike '".$_SESSION["id_usuario"]."' and estado=0 and id_cliente=".$_SESSION["id_cliente"]." order by nombre");
			$favorito=getFavoritos(" and id_usuario ilike '".$_SESSION["id_usuario"]."' and estado=0 order by fecha_registro");
			
			if(count($favorito)>0)
			{
			
				?>
				<ul data-role="listview"  data-theme="b"  data-filter="true" data-filter-placeholder="Buscar" data-inset="false" id="list_registros">	
					<?php
					foreach($favorito as $fav)
					{
						$lug=getLugares(" and id_lugar=".$fav[3]."");
						$txt_hora="";
						$marcacion=getMarcaciones(" and id_lugar=".$fav[3]." and fecha_registro >= '".date("Y-m-d")."' order by fecha_registro desc limit 1");
						
						if(count($marcacion)>0)
						{
							$txt_hora="<img width=20px src='images/icon-servicios.png'>  ".substr($marcacion[0][3], 11);
						}
						$clase="txt_mini";
						$fecha=$marcacion[0][3];
						$segundos=strtotime(getFechaLibre(DIF_HORA))-strtotime($fecha);
						//$diferencia_dias=intval($segundos/60/60/24);
	  				$diferencia_horas=intval($segundos/60/60);
    				
						if($diferencia_horas > 8)
						{
							$clase="txt_mini4";
						}
					$nombre=$lug[0][1];
					$largo=20;
					 if($_SESSION['tipo_usuario']=="computer")
					 {
					 	$largo=100;
					 }elseif($_SESSION['tipo_usuario']=="tablet")
					 {
					 	$largo=40;	
					 }
					 
					 if(strlen($nombre)> $largo)
					 {
					 	$paso=true;
					 	$nom_resto=$nombre;
					 	$nombre_final="";
					 	while($paso)
					 	{ 		
					 	 $nombre2=substr($nom_resto, 0,$largo);	
					 	 $nom=substr($nom_resto, $largo);
					 	 
					 	 $nombre_final .="<br>".$nombre2;
					 		if(trim($nom)!="")
					 		{
					 				
					 				$nom_resto=$nom;
					 		}else
					 		{
					 		  $paso=false;		
					 		}
					 	}
					 	$nombre=$nombre_final;
					}
						if($lug[0][3]==0)
						{
					?>
						<li><a  href="javascript:marcar(<?=$lug[0][0]?>,'<?=$lug[0][12]?>','<?=$lug[0][13]?>');"><span class=titulo2><?=ucwords($nombre)?></span> <p class="ui-li-aside"><span class=<?=$clase?>><?=$txt_hora?></span></p></a>					
							<a href="javascript:loadEditar(<?=$lug[0][0]?>);" data-rel="popup" data-position-to="window" data-transition="pop" data-icon="search">Editar</a>
						</li>
						
				  <?php
					}
					}
				?>
			  </ul>	
				<?php
			
			}else
			{
				?>
				<p id="form_login">
							
							<span class=titulo2>No hay lugares favoritos.</span>
							
						</p>
				<?php
			}
		}catch (Exception $e) 
		{
			?>
			<script>
				mensaje("Problemas de conexi&oacute;n, por favor int&eacute;ntelo nuevamente.","ERROR","myPopup");
			</script>
			<?php
		}
    ?>
  
    <?php		
 
	}elseif($estado_sesion==0 and $_REQUEST['tipo']==12) //eliminar fav
	{
		upFavoritos(" estado=1",$_REQUEST['id']);
	}elseif($_REQUEST['tipo']==13 and $estado_sesion==0) //marcacion libre
	{
		$cliente=getCliente(" and id_cliente=".$_SESSION["id_cliente"]."");
		$mail_envio=$cliente[0][3];
		if(trim($_REQUEST['mail'])!="")
		{
			$mail_envio=$_REQUEST['mail'];
		}
		
		try
		{
			$registros=new SoapClient("".PATH_WS_OBVII."".WS_MARCACION."");
		 $res= $registros->registrarEvento($_SESSION['id_usuario_obvii'], ''.date("Ymd").'', ''.date("His").'', ''.$_REQUEST['lat'].'',''.$_REQUEST['lon'].'',''.$_REQUEST['accu'].'',''.$_REQUEST['nombre'].'','9988776644','478000012',''.$_REQUEST['coment'].'','8888999922',''.$mail_envio.'');
		 
		 if($res>0)
		 {
		 	$data=array();
		 	$data[]=$_SESSION["id_usuario"];
		 	$data[]=$_SESSION["id_usuario_obvii"];
		 	$data[]=1;
		 	$data[]=0;
		 	$data[]=$_REQUEST['lat'];
		 	$data[]=$_REQUEST['lon'];
		 	$data[]=$_REQUEST['accu'];
		 	$data[]=$_REQUEST['coment'];
		 	$data[]=$_REQUEST['marca'];
		 	$data[]=$_REQUEST['nom'];
		 	$data[]=$_SESSION["id_cliente"];
		 	$data[]="".$_REQUEST['calle']." #".$_REQUEST['numero'].", ".$_REQUEST['com']." ";
		 	addMarcacion($data);
		 	?>
		 	<script>
				$.mobile.loading( 'hide');
				loadHome();
				mensaje("Marcaci&oacute;n Realizada",'MENSAJE','myPopup');
			</script>
			<?php
		 	}else
			{
				?>
			<script>
				mensaje("Problemas de conexi&oacute;n, por favor int&eacute;ntelo nuevamente.","ERROR","myPopup_ses");
			</script>
			<?php
			}
				
		} catch (Exception $e) 
		{
			?>
			<script>
				mensaje("Problemas de conexi&oacute;n, por favor int&eacute;ntelo nuevamente.","ERROR","myPopup_ses");
			</script>
			<?php
		}
		
	}elseif($estado_sesion==0 and $_REQUEST['tipo']==14) //asistencia listado clase
	{
		$fecha=date("Ymd");
		$marcacion=getMarcaciones(" and id_usuario ilike '".$_SESSION["id_usuario"]."' and fecha_registro >= '".date("Y-m-d")."' and tipo_marcacion=0 order by fecha_registro desc limit 1");
		$usuarios=getUsuariosInterno(" and estado=0 and id_lugar=".$marcacion[0][5]." order by nombre");
		?>
		
		<h4>Revisi&oacute;n <?=strtoupper($marcacion[0][11])?></h4>
		  
		<?php
	   if(count($usuarios)==0 and count($marcacion)>0)
	   {
	   	?>
	   	<span class=texto_interior>No disponible para esta marcaci&oacute;n</span>
	   	<?php
	   }elseif(count($marcacion)==0)
	   {
	   	?>
	   	<span class=texto_interior>Para ver listado de asistencia debe realizar una marcaci&oacute;n</span>
	   	<?php
	  	}elseif(count($usuarios)>0)
	   {
	   ?>
	   <a href="javascript:sendLitsaMail(<?=$marcacion[0][5]?>,<?=$marcacion[0][0]?>);" data-role="button" data-mini="false" data-inline="true" data-icon="mail" data-theme="a">Enviar</a>
  		<ul data-role="listview"  data-theme="b"  data-filter="true" data-filter-placeholder="Buscar" data-inset="false" id="list_registros">	
  			<?php
  			
  			foreach($usuarios as $us)
  			{
  				/*
  				$img="images/student3-64.png";
  				$img2="images/ticket3.png";
  				*/
  				$img="images/student2-64.png";
  				$img2="images/ticket2.png";
  				$marca=getMarcaInt(" and estado=0 and id_marca_base=".$marcacion[0][0]." and id_usuario=".$us[0]."");
  				if(count($marca)>0)
  				{
  					if($marca[0][4]=='t')
  					{
  						$img="images/student-64.png";
  						$img2="images/ticket.png";
  					}else
  					{
  						$img="images/student2-64.png";
  						$img2="images/ticket2.png";
  					}
  				}
  				//print_R($marca);
  				
  				if($us[5]==2)
  				{
  					$img=$img2;
  				}
  				$msg=trim(ucwords(str_replace("\n","<br>",$us[6])));
  				if(trim($us[6])=="")
  				{
  					$msg="No hay descripci&oacute;n disponible";
  				}
  			?>
  			 <li onclick="mensaje('<?=$msg?>','Descripci&oacute;n','myPopup');">
    	    <img src="<?=$img?>">
    	    <h3><?=ucwords($us[1])?></h3>
    	    <?php
    	    if(count($marca)>0)
  				{
  					?>
  					<div data-role="controlgroup" class="ui-li-aside" data-type="horizontal" data-mini="true">
    					
    					
    					 <a href="javascript:cancelaMarcaInt(<?=$marca[0][0]?>);"  data-role="button" data-icon="delete" data-iconpos="notext">Icon only</a>
						</div>
  					<?php
  				}else
  				{
  					?>
  					<div data-role="controlgroup" class="ui-li-aside" data-type="horizontal" data-mini="true">
    					
    					<a href="javascript:marcaInt('true',<?=$us[0]?>,<?=$marcacion[0][5]?>,<?=$marcacion[0][0]?>);" data-role="button"  data-icon="check" data-iconpos="notext">SI</a>
    					<!--a href="javascript:marcaInt('false',<?=$us[0]?>,<?=$marcacion[0][5]?>,<?=$marcacion[0][0]?>);" data-role="button">No</a-->    
    					
						</div>
  					<?php
  				}
    	    ?>       
    	    	
    	</li>
					<?php
				}
					?>		
								
			 </li>
  			
  		</ul>
    <?php		
  	}
 
	}elseif($estado_sesion==0 and $_REQUEST['tipo']==15)
	{
		$data=array();
		$data[]=$_REQUEST['id'];
		$data[]=$_REQUEST['lugar'];
		$data[]=$_REQUEST['marca'];
		$data[]=$_REQUEST['marca_base'];
		addMarcaInt($data);	
	}elseif($estado_sesion==0 and $_REQUEST['tipo']==16)
	{
		
		updateMarcaInt("estado=1",$_REQUEST['id']);	
	}elseif($estado_sesion==0 and $_REQUEST['tipo']==17)//envio mail con listado
	{
		$lugar=getLugares(" and id_lugar=".$_REQUEST['id']."");
		$usuarios=getUsuariosInterno(" and id_lugar=".$_REQUEST['id']." and estado=0 order by nombre");
		$html ="Listado de asistencia asociado al lugar ".ucwords($lugar[0][1])."<br><br>";
		foreach($usuarios as $us)
		{
			$marca=getMarcaInt(" and id_usuario=".$us[0]." and id_marca_base=".$_REQUEST['base']." and estado=0");
			
			if(count($marca)>0)
			{
				if($marca[0][4]=='t')
				{
				 $data=ucwords($us[1])." Asiste ".$marca[0][2];
				}else	
				{
				 $data=ucwords($us[1])." NO Asiste ".$marca[0][2];
				}
			}else
			{
				$data=ucwords($us[1])." NO Asiste";
			}
			$html .=$data."<br>";
			
		}
		//echo $html;
		sendMail($lugar[0][14],$html,"Listado asistencia ".ucwords($lugar[0][1])."");
		//print_r($marcas);
	}elseif($estado_sesion==0 and $_REQUEST['tipo']==18)//mpa
	{
		$cliente=getCliente(" and id_cliente=".$_SESSION['id_cliente']."");
		if($cliente[0][4]=="peru")
		{
			?>
			<script>
				PAIS_LON=-77.041752;
				PAIS_LAT=-12.052364;
				OBVII_PAIS="peru";
				</Script>
			<?php
		}
		?>
		<div id=info_pres class=msg_error></div>
		<br>
		<div id="map">
		</div>
		<?php
	}
}
?>