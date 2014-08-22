<?php
include("../funciones.php");

$data_server= explode("?",$_SERVER['HTTP_REFERER']);
$estado_sesion=estado_sesion();
if(1==1)
{
	

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
    				inicioSesion(strtolower($_REQUEST['mail']),$res,$id_cliente);
    				?>
						<script>
							NOMBRE_USER = "<?=$_REQUEST['mail']?>";
        			MAIL_USER = "<?=$_REQUEST['mail']?>";
        			ID_USER = "<?=$cliente[0]?>";
        			ID_OBVII_USER = "<?=$res?>";
							addUsuarioBDLocal(ID_USER,NOMBRE_USER,MAIL_USER,0,ID_OBVII_USER);
							
							
							//$("#mod_sesion").dialog( "close" );
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

		
	}elseif($_REQUEST['tipo']==2)
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
						$txt_hora="";
						$marcacion=getMarcaciones(" and id_lugar=".$lug[0]." and fecha_registro >= '".date("Y-m-d")."' order by fecha_registro desc limit 1");
						if(count($marcacion)>0)
						{
							$txt_hora="<img width=20px src='images/icon-servicios.png'>  ".substr($marcacion[0][3], 11);
						}
						$clase="txt_mini";
						$fecha=$marcacion[0][3];
						$segundos=strtotime(getFecha())-strtotime($fecha);
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
 
	}elseif($_REQUEST['tipo']==8) //marcar lugar
	{
		$lugares=getLugares(" and id_lugar=".$_REQUEST['id']."");
	   $registros=new SoapClient("".PATH_WS_OBVII."".WS_MARCACION."");
		 $res= $registros->registrarEvento($_SESSION['id_usuario_obvii'], ''.date("Ymd").'', ''.date("His").'', ''.$_REQUEST['lat'].'',''.$_REQUEST['lon'].'',''.$_REQUEST['accu'].'',''.$lugares[0][1].'','9988776644','478000012',''.$_REQUEST['coment'].'','8888999922',''.$lugares[0][10].'');
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
		 	addMarcacion($data);
		 ?>
			<script>
				$.mobile.loading( 'hide');
				loadHome();
				mensaje("Marcaci&oacute;n realizada",'MENSAJE','myPopup');
			</script>
			<?php
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
	}elseif($_REQUEST['tipo']==10) //fav lugar
	{
		$data=array();
		$data[]=$_SESSION["id_usuario"];
		$data[]=$_SESSION["id_usuario_obvii"];
		$data[]=$_REQUEST['id'];
		addFavorito($data);
	}elseif($_REQUEST['tipo']==11) //favoritas
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
						$segundos=strtotime(getFecha())-strtotime($fecha);
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
 
	}elseif($_REQUEST['tipo']==12) //eliminar fav
	{
		upFavoritos(" estado=1",$_REQUEST['id']);
	}elseif($_REQUEST['tipo']==14) //historial
	{
	$fecha=getFechaLibre(744); // 31 dias
	$marcaciones=getMarcaciones(" and fecha_registro >= '".$fecha."' and id_usuario ilike '%".$_SESSION["id_usuario"]."%' and id_usuario_obvii=".$_SESSION["id_usuario_obvii"]." order by fecha_registro desc");
	//print_R($marcaciones);
	?>
	<h4>Historial de Asistencia</h4>
	<ul data-role="listview"  data-theme="b"  data-filter="true" data-filter-placeholder="Buscar" data-inset="false" id="list_registros">	
				
	
	<?php
	foreach($marcaciones as $marca)
	{
		$lugar=getLugares(" and id_lugar=".$marca[5]."");
		$txt_hora="<img width=20px src='images/icon-servicios.png'>  ";
		if($marca[3]>= date("Y-m-d"))
				$txt_hora .=substr($marca[3], 11);
		else
				$txt_hora .=$marca[3];
		$clase="txt_mini2";
		
		$fecha=$marca[3];
		$segundos=strtotime(getFecha())-strtotime($fecha);
		//$diferencia_dias=intval($segundos/60/60/24);
	  $diferencia_horas=intval($segundos/60/60);

		if($diferencia_horas > 8)
		{
			$clase="txt_mini3";
		}
		$icono="<img class='ui-li-icon ui-corner-none' src='images/entrada.png'>";
		if($marca[10]==1)
		   $icono="<img class='ui-li-icon ui-corner-none' src='images/salida.png'>";
		   $esp="";		   
		$nombre=ucwords($lugar[0][1]);   
		if($marca[4]==1)
		{
		   $esp="marca_esp";
		   $nombre=ucwords($marca[11]);
		 }
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
	
		<li class=<?=$esp?>><?=$icono?><span class=titulo2><?=$nombre?></span><p class="ui-li-aside"><span class=<?=$clase?>><?=$txt_hora?></span></p></li>
	
				
	<?php
	}
	?>
	</ul>	
	<?php
	}elseif($_REQUEST['tipo']==15)
	{
		$lugares=getLugares(" and id_lugar=".$_REQUEST['id']."");
if($lugares[0][12]=='t')
{
	$check2="selected";
	$check1="";
}else
{
	$check2="";
	$check1="selected";
}
if($lugares[0][13]=='t')
{
	$check_s2="selected";
	$check_s1="";
}else
{
	$check_s2="";
	$check_s1="selected";
}

?>
					<div class="ui-bar ui-bar-a" id=barra_sup style="text-align:center;">
					 Edici&oacute;n Lugar
					</div>
    	    <p id="form_interior">
						
						<label for="text-basic">Nombre del Lugar</label>
						<span class=titulo_basico><?=ucwords($lugares[0][1])?></span>
						<label for="text-basic">Direcci&oacute;n</label>
						<span class=titulo_basico><?=ucwords($lugares[0][6])?> #<?=$lugares[0][7]?>,<?=ucwords($lugares[0][8])?></span>
						<label for="text-basic">Correo Electronico</label>
						<span class=titulo_basico><?=$lugares[0][10]?></span>
						<label for="text-basic">Comentario?</label>
						<select name="slider2" id="slider2" data-role="slider" data-theme="b">
    					<option value="off" <?=$check1?>>No</option>
    					<option value="on" <?=$check2?>>Si</option>
						</select> <span class=texto_interior>Esta opci&oacute;n activa una casilla de comentario cada vez que se ejecute una acci&oacute;n con el lugar registrado.</span>
							<br><br>
							<label for="text-basic">Entrada y Salida??</label>
						<select name="slider1" id="slider1" data-role="slider" data-theme="b">
    					<option value="off" <?=$check_s1?>>No</option>
    					<option value="on" <?=$check_s2?>>Si</option>
						</select> <span class=texto_interior>Esta opci&oacute;n activa la opci&oacute;n de marcar una salida para este lugar.</span>
					</p>          
					<p id="form_login">
						<?php
						$favorito=getFavoritos(" and id_usuario ilike '".$_SESSION["id_usuario"]."' and estado=0 and id_lugar=".$_REQUEST['id']."");
						if(count($favorito)> 0)
						{
							?>
							<input type="button" onclick="delFav(<?=$favorito[0][0]?>)" value="Eliminar Favoritos">
							<?php
						}else
						{
							?>
							<input type="button" onclick="addFav(<?=$_REQUEST['id']?>)" value="Agregar Favoritos">
							<?php
						}
						?>
					</p>
					<?php

	}
}
?>