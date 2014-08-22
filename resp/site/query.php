<?php
include("../funciones.php");
$data_server= explode("?",$_SERVER['HTTP_REFERER']);
$estado_sesion=estado_sesion_web();
if(substr(strtolower($data_server[0]),0,strlen(PATH_SITE_WEB))==PATH_SITE_WEB)
{
	if($_REQUEST['tipo']==1)
	{
		$clave=$_REQUEST['clave'];
				try
		{
			$eventos=new SoapClient("".PATH_WS_OBVII."".WS_LOGIN."");
			$res= $eventos->validarUsuario(trim(strtolower($_REQUEST['mail'])),$clave);
		  //if ($res==$_REQUEST['clave']) 
    	if ($res >0) 
    	{
    		$usuario=getUsuario(" and mail ilike '".$_REQUEST['mail']."' and tipo_usuario=1");
    		
				if(count($usuario)>0)
				{
					$cliente=getCliente(" and id_cliente=".$usuario[4]."");
					if($cliente[0][2]==0)
					{
						inicioSesion_web($_REQUEST['mail'],$_REQUEST['mail'],$usuario[4],$res,$cliente[0][4]);
						updateUsuario("id_usuario_obvii=".$res.", clave='".$clave."'",$usuario[0]);
						?>
							<script>
								window.location="index.php";
							</script>
						<?php
					}else
					{
						echo "Acceso no autorizado";
					}
						
				}
    	}else{    	
					echo "Mail o Clave incorrectas";


    	}
		} catch (Exception $e) 
		{
			
				echo "Problemas de conexi&oacute;n, por favor int&eacute;ntelo nuevamente.";
			
		}

		
	}elseif($_REQUEST['tipo']==2)
	{
		cerrar_sesion_web();
			?>
		<script>
			window.location="login.php";
		</script>
		<?php
	}elseif($_REQUEST['tipo']==3 and $estado_sesion==0) //lista de empresas / lugares
	{
		$cliente=$_SESSION['id_cliente_web'];
		$estado=$_REQUEST['estado'];
		$nombre=$_REQUEST['nombre'];
		
		if(trim($cliente)!="")
		{
			$query .=" and id_cliente=".$cliente.""	;
		}
		if(trim($estado)!="" and $estado!=10)
		{
			$query .=" and estado=".$estado.""	;
		}
		if(trim($nombre)!="")
		{
			$query .=" and nombre ilike '%".$nombre."%'"	;
		}	
		$query .=" order by nombre";
		
		$empresas=getLugares($query);
		//print_r($empresas);
		?>
		<table border=1 id="table_resul" class="bordered">
			<!--	<tr class="titulo">-->
				<tr>
					<th style="width:5%;">ID</th>				
					<th style="width:30%;">NOMBRE</th>				
					<th style="width:40%;">DIRECCION</th>
					
					<TH style="width:25%;">PANEL</TH>
				</tr>
			<?php
			foreach($empresas as $i=> $us)
			{
			
				?>
				<tr>
					<td style="width:5%;"><?=$us[0]?></td>				
					<td style="width:30%;"><?=ucwords($us[1])?></td>				
					<td style="width:40%;"><?=ucwords($us[6])?> #<?=ucwords($us[7])?>, <?=ucwords($us[8])?></td>
					
					<Td style="width:25%;">
						
						<a href="javascript:loadEmpresa(<?=$us[0]?>);">Editar</a>	
						|<a href="javascript:limpiarMapa();verMapa(<?=$us[4]?>,<?=$us[5]?>);">Mapa</a>	
						<?php
						if($us[3]==0)
						{
							?>
							| <a href="javascript:upEstadoEmpresa(1,<?=$us[0]?>);">Bajar</a>	
							<?php
						}else
						{
							?>
							| <a href="javascript:upEstadoEmpresa(0,<?=$us[0]?>);">Subir</a>	
							<?php
							
						}
						?>
						</Td>
				</tr>
				<?php
			}
			?>
			</table>
		<?php
	}elseif($_REQUEST['tipo']==4 and $estado_sesion==0)//Editar Empresa
	{		
		$id=$_REQUEST['empresa'];
		$empresa=getLugares(" and id_lugar=".$id."");
		
		?>
		<table border=1 id="table_resul" class="bordered">
			
			<tr><td>Nombre</td><td><input id="nombre_em" name="nombre_em" type="text" value="<?=$empresa[0][1]?>"></td></tr>		

			<tr><td>Calle</td><td><input type="text" id="calle_em" name="calle_em" value="<?=$empresa[0][6]?>"></td></tr>
			<tr><td>Numero</td><td><input type="text" id="num_em" name="num_em" value="<?=$empresa[0][7]?>"></td></tr>
			
			<tr><td>Comuna</td><td><input type="text" id="com_em" name="com_em" value="<?=$empresa[0][8]?>"></td></tr>		
			<tr><td>Latitud</td><td><input type="text" id="lat_em" name="lat_em" value="<?=$empresa[0][4]?>"></td></tr>
			<tr><td>Longitud</td><td><input type="text" id="lon_em" name="lon_em" value="<?=$empresa[0][5]?>"></td></tr>
			<tr><td></td><td><input type="button" onclick="BuscarGeo();" value="GEO"><input type="button" onclick="limpiarMapa();verMapa2();" value="Ver Mapa"><input type="button" onclick="updateEmpresa(<?=$id?>);" value="Guardar"></td></tr>
		</table>
		<div id="msg_error_add" class="msg_error"></div>
			<?php
	}elseif($_REQUEST['tipo']==5 and $estado_sesion==0)//busca GEO
	{
		if(strtolower($_SESSION['pais_web'])=="chile")
		{
			$direccion=file_get_contents("http://www.chilemap.cl/ws/ws.php?query=".str_replace(" ","+",elimina_acentos(utf8_decode($_REQUEST['calle'])))."+".str_replace(" ","+",$_REQUEST['numero'])."+".str_replace(" ","+",elimina_acentos(utf8_decode($_REQUEST['comuna'])))."");
			$direc=explode(",",$direccion);
		}
		
		
		$direccion_completa=trim(elimina_acentos(utf8_decode($_REQUEST['calle']))." ".$_REQUEST['numero']." ".$_REQUEST['comuna']." ".$_SESSION['pais_web']."");
		
		
		if(count($direc)>1 and strtolower($_SESSION['pais_web'])=="chile")
		{
			?>
			<script>
				
				document.getElementById("lat_em").value="<?=$direc[6]?>";				
				document.getElementById("lon_em").value="<?=$direc[7]?>";
				</script>
			<?php
			
			echo "OK exacta";
		}else //busca en OSM
		{
			$direc=buscarDireccionOSM($direccion_completa);
			//print_r($direc);
			
			if(count($direc)>0 and trim($direc[0][1])!="")
			{
				//calle,numero_municipal,latitud,longitud,comuna,id_comuna,region,id_region,query_completa,geom,origen
				$data[]="Chile";
				$data[]=$direc[0][3];
				$data[]=$direc[0][2];
				$data[]=$direc[0][7];
				$data[]=$direc[0][8];
				$data[]=$direc[0][5];
				$data[]=0;
				$data[]=$direc[0][4];
				$data[]=0;
				$data[]="".$direc[0][3]." ".$direc[0][2]." ".$direc[0][4]."";
				//addDireccion($data,2);
				?>
			<script>
				
				document.getElementById("lat_em").value="<?=$direc[0][1]?>";
				
				document.getElementById("lon_em").value="<?=$direc[0][0]?>";
				</script>
			<?php
			echo "OK osm";
			}else //GOOOGLE
			{
				
				$direc=getDireccionGoogle($direccion_completa);
				//print_r($direc);
				if(count($direc)>0 and trim($direc[0][7])!="")
				{
					//calle,numero_municipal,latitud,longitud,comuna,id_comuna,region,id_region,query_completa,geom,origen
					$data=array();
				$data[]="Chile";
				$data[]=$direc[0][3];
				$data[]=$direc[0][2];
				$data[]=$direc[0][7];
				$data[]=$direc[0][8];
				$data[]=$direc[0][5];
				$data[]=0;
				$data[]=$direc[0][4];
				$data[]=0;
				$data[]="".$direc[0][3]." ".$direc[0][2]." ".$direc[0][4]."";
				//addDireccion($data,2);
					
					?>
				<script>
					
					document.getElementById("lat_em").value="<?=trim($direc[0][7])?>";
					
					document.getElementById("lon_em").value="<?=trim($direc[0][8])?>";
					</script>
					
				<?php
				echo "OK google";
			}else
			{
				echo "No se encuentran coordenadas";
			}
			}
		}
	}elseif($_REQUEST['tipo']==6 and $estado_sesion==0)//update Empresa
	{
		
		$data[]=$_REQUEST['nombre'];
		$data[]=$_REQUEST['calle'];
		$data[]=$_REQUEST['numero'];
		$data[]=$_REQUEST['comuna'];
		$data[]=$_REQUEST['region'];
		$data[]=$_REQUEST['latitud'];
		$data[]=$_REQUEST['longitud'];
		
		
		updateLugarObvii("nombre='".$_REQUEST['nombre']."', calle='".$_REQUEST['calle']."', numero_municipal='".$_REQUEST['numero']."', comuna='".$_REQUEST['comuna']."',  latitud='".$_REQUEST['latitud']."',  longitud='".$_REQUEST['longitud']."', geom=ST_GeomFromText('POINT(".$_REQUEST['longitud']." ".$_REQUEST['latitud'].")',2276)",$_REQUEST['empresa']);
	}elseif($_REQUEST['tipo']==7 and $estado_sesion==0)//update Empresa
	{
		updateLugarObvii("estado=".$_REQUEST['estado']." ",$_REQUEST['empresa']);
	}elseif($_REQUEST['tipo']==8 and $estado_sesion==0)//nuevo Empresa
	{		
		
			
			?>
			<table border=1 id="table_resul" class="bordered">
				
				<tr><td>Nombre</td><td><input id="nombre_em" name="nombre_em" type="text" value=""></td></tr>		
				<tr><td>Mail Notificaci&oacute;n</td><td><input type="text" id="mail_em" name="mail_em" value=""></td></tr>
				<tr><td>Comentario</td><td><select name="slider2" id="slider2" >
    					<option value="off">No</option>
    					<option value="on">Si</option>
						</select></td></tr>
				<tr><td>Entrada/Salida</td><td><select name="slider1" id="slider1" data-role="slider" data-theme="b">
    					<option value="off">No</option>
    					<option value="on">Si</option>
						</select></td></tr>
				<tr><td>Calle</td><td><input type="text" id="calle_em" name="calle_em" value=""></td></tr>
				<tr><td>Numero</td><td><input type="text" id="num_em" name="num_em" value=""></td></tr>				
				<tr><td>Comuna</td><td>
						<input type="text" id="com_em" name="com_em" value="">
				</td></tr>		
				<tr><td>Latitud</td><td><input type="text" id="lat_em" name="lat_em" value=""></td></tr>
				<tr><td>Longitud</td><td><input type="text" id="lon_em" name="lon_em" value=""></td></tr>
				<tr><td></td><td><input type="button" onclick="BuscarGeo();" value="GEO"><input type="button" onclick="limpiarMapa();verMapa(document.getElementById('lat_em').value,document.getElementById('lon_em').value);" value="Ver Mapa"><input type="button" onclick="saveEmpresa();" value="Registrar"></td></tr>
			</table>
			<div id="msg_error_add" class="msg_error"></div>
			<div id="msg_ayuda" class="msg_error">
			1: Ingresa los datos: nombre, mail, calle, numero y comuna<br>
			2: Selecciona boton "GEO" para obtener localizaci&oacute;n de la direcci&oacute;n ingresada<br>
			3: Selecciona "Ver Mapa" para confirmar la posicion del lugar<br>
			4: Registra el lugar
		</div>
		
				<?php
	 
	}elseif($_REQUEST['tipo']==9 and $estado_sesion==0)//almacena empresa
	{
		
		$data=array();
		$data[]=$_REQUEST['nombre'];
		$data[]=$_REQUEST['latitud'];
		$data[]=$_REQUEST['longitud'];
		
		$data[]=$_REQUEST['calle'];
		$data[]=$_REQUEST['numero'];
		$data[]=$_REQUEST['comuna'];
		$data[]='';
		$data[]=$_REQUEST['mail'];
		$data[]=$_REQUEST['coment'];
		$data[]=$_REQUEST['salida'];
		$data[]=$_SESSION['id_cliente_web'];
		
		addLugarObvii($data);
	}
}

?>