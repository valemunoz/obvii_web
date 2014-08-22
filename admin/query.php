<?php
include("../funciones.php");
$data_server= explode("?",$_SERVER['HTTP_REFERER']);
$estado_sesion=estado_sesion_admin();
if(substr(strtolower($data_server[0]),0,strlen(PATH_SITE_ADMIN))==PATH_SITE_ADMIN)
{
	if($_REQUEST['tipo']==1)
	{
		$clave=$_REQUEST['clave'];

    		$usuario=getUsuarioAdmin(" and mail ilike '".$_REQUEST['mail']."' and estado=0 and clave ='".$clave."'");
    		
				if(count($usuario)>0)
				{
					inicioSesion_admin($_REQUEST['mail'],$usuario[0][0]);
					
						?>
				<script>
					window.location="index.php";
				</script>
				<?php
				}else
				{
					echo "Usuario no valido";
				}
    	
		

		
	}elseif($_REQUEST['tipo']==2)
	{
		cerrar_sesion_admin();
			?>
		<script>
			window.location="login.php";
		</script>
		<?php
	}elseif($_REQUEST['tipo']==3 and $estado_sesion==0) //lista de clientes
	{
		
		$estado=$_REQUEST['estado'];
		
		$nombre=$_REQUEST['nombre'];
		

		if(trim($estado)!="" and $estado!=10)
		{
			$query .=" and estado=".$estado.""	;
		}
		
		if(trim($nombre)!="")
		{
			$query .=" and nombre ilike '%".$nombre."%'"	;
		}	
		$query .=" order by nombre";
		
		$usuarios=getCliente($query);
		//print_r($usuarios);
		?>
		<table border=1 id="table_resul" class="bordered">
			<!--	<tr class="titulo">-->
				<tr>
					<th style="width:5%;">ID</th>				
					<th style="width:30%;">NOMBRE</th>	
								
									
					<TH style="width:25%;">PANEL</TH>
				</tr>
			<?php
			foreach($usuarios as $i=> $us)
			{
			 
				?>
				<tr>
					
					<td style="width:5%;"><?=$us[0]?></td>				
					
					<td style="width:20%;"><?=$us[1]?></td>				
					
					
					<Td style="width:25%;">
						
						<a href="javascript:loadCliente('<?=encrypt($us[0],ENCRIPTACION)?>');">Editar</a>	
						
						<?php
						if($us[2]==0)
						{
							?>
							| <a href="javascript:upClienteEst(1,'<?=encrypt($us[0],ENCRIPTACION)?>');">Bajar</a>	
							<?php
						}else
						{
							?>
							| <a href="javascript:upClienteEst(0,'<?=encrypt($us[0],ENCRIPTACION)?>');">Subir</a>	
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
	}elseif($_REQUEST['tipo']==4 and $estado_sesion==0)//Editar cli
	{		
		$id=decrypt($_REQUEST['usuario'],ENCRIPTACION);
		$usuario=getCliente(" and id_cliente=".$id."");
		$paises=getPais(" and estado=0 order by nombre");
		$sel_tip="";
		$sel_tip2="";
		if($usuario[0][5]==0)
			$sel_tip="selected";
		else
			$sel_tip2="selected";
		?>
		<table border=1 id="table_resul" class="bordered">
			<tr><td>Nombre</td><td><input id="nom_ed" name="nom_ed" type="text" value="<?=$usuario[0][1]?>"></td></tr>				
			<tr><td>Mail</td><td><input id="mail_ed" name="mail_ed" type="text" value="<?=$usuario[0][3]?>"></td></tr>	
			<tr><td>Tipo Cliente</td>
						<td>
							<select id="tipo_ed" name='tipo_ed'>
								
									<option value="0" <?=$sel_tip?>> Asistencia Normal</option>
									<option value="1" <?=$sel_tip2?>>Asistencia + lista alumno</option>
								
							</select>
						</td></tr>						
			<tr><td>Pais</td>
						<td>
							<select id="pais_ed" name='pais_ed'>
								<?php
								foreach($paises as $pais)
								{
									$select="";
									if(strtolower($pais[1])==strtolower($usuario[0][4]))
									  $select="selected";
									?>
									<option value=<?=$pais[1]?> <?=$select?> ><?=ucwords($pais[1])?></option>
									<?php
								}
								?>
							</select>
						</td></tr>				
			<tr><td></td><td><input type="button" onclick="updateCliente('<?=encrypt($id,ENCRIPTACION)?>');" value="Guardar"></td></tr>
		</table>
		<div id="msg_error_add" class="msg_error"></div>
			<?php
	}elseif($_REQUEST['tipo']==5 and $estado_sesion==0)//update cli
	{
		updateCli("tipo=".$_REQUEST['tipo_cli']." , pais='".$_REQUEST['pais']."', mail='".$_REQUEST['mail']."', nombre='".$_REQUEST['nom']."'",decrypt($_REQUEST['id'],ENCRIPTACION));
	}elseif($_REQUEST['tipo']==6 and $estado_sesion==0)//update cli estado
	{
		updateCli("estado='".$_REQUEST['estado']."'",decrypt($_REQUEST['id'],ENCRIPTACION));
	}elseif($_REQUEST['tipo']==7 and $estado_sesion==0)//nuevo Cliente
	{
		$paises=getPais(" and estado=0 order by nombre");
			?>
			<table border=1 id="table_resul" class="bordered">
				<tr><td>Nombre</td><td><input id="nom_us" name="nom_us" type="text" value=""></td></tr>						
				<tr><td>Mail</td><td><input id="mail_us" name="mail_us" type="text" value=""></td></tr>	
				<tr><td>Tipo Cliente</td>
						<td>
							<select id="tipo_us" name='tipo_us'>
								
									<option value=0 selected> Asistencia Normal</option>
									<option value=1>Asistencia + lista alumno</option>
								
							</select>
						</td></tr>					
					<tr><td>Pais</td>
						<td>
							<select id="pais_us" name='pais_us'>
								<?php
								foreach($paises as $pais)
								{
									?>
									<option value=<?=$pais[1]?>><?=ucwords($pais[1])?></option>
									<?php
								}
								?>
							</select>
						</td></tr>			
						
				<tr><td></td><td><input type="button" onclick="saveCliente();" value="Registrar"></td></tr>
			</table>
			<div id="msg_error_add" class="msg_error"></div>
				<?php
	}elseif($_REQUEST['tipo']==8 and $estado_sesion==0)//nuevo Cliente
	{
		$data=array();
		$data[]=$_REQUEST['nombre'];
		$data[]=$_REQUEST['mail'];
		$data[]=$_REQUEST['pais'];
		$data[]=$_REQUEST['tipo_cli'];
		addCliente($data);
	}
}

?>