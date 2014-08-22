<?php
include("../funciones.php");
$data_server= explode("?",$_SERVER['HTTP_REFERER']);
$estado_sesion=estado_sesion_admin();
if(substr(strtolower($data_server[0]),0,strlen(PATH_SITE_ADMIN))==PATH_SITE_ADMIN)
{
	if($_REQUEST['tipo']==1 and $estado_sesion==0) //lista de usuarios
	{
		
		$estado=$_REQUEST['estado'];
		$mail=$_REQUEST['mail'];
		$nombre=$_REQUEST['nombre'];
		

		if(trim($estado)!="" and $estado!=10)
		{
			$query .=" and estado=".$estado.""	;
		}
		if(trim($mail)!="")
		{
			$query .=" and mail ilike '%".$mail."%'"	;
		}	
		if(trim($nombre)!="")
		{
			$query .=" and nombre ilike '%".$nombre."%'"	;
		}	
		$query .=" order by nombre";
		
		$usuarios=getUsuarioAdmin($query);
		//print_r($usuarios);
		?>
		<table border=1 id="table_resul" class="bordered">
			<!--	<tr class="titulo">-->
				<tr>
					<th style="width:5%;">ID</th>				
					<th style="width:30%;">NOMBRE</th>	
					<th style="width:20%;">MAIL</th>				
									
					<TH style="width:25%;">PANEL</TH>
				</tr>
			<?php
			foreach($usuarios as $i=> $us)
			{
			 
				?>
				<tr>
					
					<td style="width:5%;"><?=$us[0]?></td>				
					<td style="width:30%;"><?=$us[1]?></td>	
					<td style="width:20%;"><?=$us[2]?></td>				
					
					
					<Td style="width:25%;">
						
						<a href="javascript:loadUsuario('<?=encrypt($us[0],ENCRIPTACION)?>');">Editar</a>	
						
						<?php
						if($us[4]==0)
						{
							?>
							| <a href="javascript:upUsuarioEst(1,'<?=encrypt($us[0],ENCRIPTACION)?>');">Bajar</a>	
							<?php
						}else
						{
							?>
							| <a href="javascript:upUsuarioEst(0,'<?=encrypt($us[0],ENCRIPTACION)?>');">Subir</a>	
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
	}elseif($_REQUEST['tipo']==2 and $estado_sesion==0)//Editar usuario
	{		
		$id=decrypt($_REQUEST['usuario'],ENCRIPTACION);
		$usuario=getUsuarioAdmin(" and id_usuario_admin=".$id."");
		
		?>
		<table border=1 id="table_resul" class="bordered">
			<tr><td>Nombre</td><td><input id="nom_us" name="nom_us" type="text" value="<?=$usuario[0][1]?>"></td></tr>	
			<tr><td>Mail</td><td><input id="mail_us" name="mail_us" type="text" value="<?=$usuario[0][2]?>"></td></tr>		
			<tr><td>Clave</td><td><input id="clave" name="clave" type="text" value="<?=$usuario[0][3]?>"></td></tr>		
			
			
			<tr><td></td><td><input type="button" onclick="updateUsuario('<?=encrypt($id,ENCRIPTACION)?>');" value="Guardar"></td></tr>
		</table>
		<div id="msg_error_add" class="msg_error"></div>
			<?php
	}elseif($_REQUEST['tipo']==3 and $estado_sesion==0)//update usuario
	{
		updateUsuarioadmin("mail='".$_REQUEST['mail']."', nombre='".$_REQUEST['nom']."', clave='".$_REQUEST['clave']."'",decrypt($_REQUEST['id'],ENCRIPTACION));
	}elseif($_REQUEST['tipo']==4 and $estado_sesion==0)//nuevo usuario
	{
			?>
			<table border=1 id="table_resul" class="bordered">
				<tr><td>Nombre</td><td><input id="nom_us" name="nom_us" type="text" value=""></td></tr>		
				<tr><td>Mail</td><td><input id="mail_us" name="mail_us" type="text" value=""></td></tr>		
				<tr><td>Clave</td><td><input id="key_us" name="key_us" type="text" value=""></td></tr>		
				
				<tr><td></td><td><input type="button" onclick="saveUsuario();" value="Registrar"></td></tr>
			</table>
			<div id="msg_error_add" class="msg_error"></div>
				<?php
	}elseif($_REQUEST['tipo']==5 and $estado_sesion==0)//Nuevo usuario
	{
		$data=array();
		$data[]=$_REQUEST['mail'];
		$data[]=$_REQUEST['clave'];
		$data[]=$_REQUEST['nombre'];
		addUsuarioAdmin($data);
		
	}elseif($_REQUEST['tipo']==6 and $estado_sesion==0)//update estado usuario
	{
		updateUsuarioadmin("estado=".$_REQUEST['estado']."",decrypt($_REQUEST['id'],ENCRIPTACION));
	}
}

?>