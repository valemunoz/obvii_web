<?php
include("../funciones.php");
$data_server= explode("?",$_SERVER['HTTP_REFERER']);
$estado_sesion=estado_sesion_web();
if(substr(strtolower($data_server[0]),0,strlen(PATH_SITE_WEB))==PATH_SITE_WEB)
{
	if($_REQUEST['tipo']==1 and $estado_sesion==0) //lista de usuarios
	{
		$cliente=$_SESSION['id_cliente_web'];
		
		$mail=$_REQUEST['mail'];
		
		$lugar=$_REQUEST['lugar'];
		
		$fecha_inicio=$_REQUEST['fec_ini'];
		$fecha_termino=$_REQUEST['fec_ter'];

    $query = " and id_cliente=".$cliente."";
    if(trim($fecha_inicio) !="")
    {
    	$query .=" and fecha_registro >= '".$fecha_inicio."'"	;
    }
    if(trim($fecha_termino) !="")
    {
    	$query .=" and fecha_registro <= '".$fecha_termino." 23:59:59'"	;
    }
		if(trim($mail)!="")
		{
			$query .=" and id_usuario ilike '%".$mail."%'"	;
		}	
		
		if(trim($lugar)>0)
		{
			$query .=" and id_lugar = ".$lugar.""	;
		}	
		$query .=" order by fecha_registro desc";
		
		$usuarios=getMarcaciones($query);
		//print_r($usuarios);
		?>
		<table border=1 id="table_resul" class="bordered">
			<!--	<tr class="titulo">-->
				<tr>
								
					<th style="width:20%;">USUARIO</th>						
					<th style="width:20%;">LUGAR</th>		
					<th style="width:15%;">Entrada/Salida</th>
					<th style="width:20%;">FECHA</th>						
					<TH style="width:25%;">PANEL</TH>
				</tr>
			<?php
			foreach($usuarios as $i=> $us)
			{
				$detalle_lugar=getLugares(" and id_lugar=".$us[5]."");
			  $user=getUsuario(" and mail ilike '".$us[1]."'");
			  $entSal="entrada";
			  if($us[10]==1)
			  {
			  	$entSal="Salida";
			  }
			  $color="";
			  if($us[4]==1)//libre marcacion
			  {
			  	$color="#A6D2FF";
			  }
				?>
				<tr style="background-color:<?=$color?>">
					
					<td style="width:20%;"><?=ucwords($user[7])?></td>				
					<td style="width:20%;"><?=ucwords($us[11])?></td>	
					<td style="width:20%;"><?=ucwords($entSal)?></td>	
					<td style="width:20%;"><?=ucwords($us[3])?></td>	
						
					<Td style="width:25%;">
						
						<a href="javascript:loadDetMarca('<?=encrypt($us[0],ENCRIPTACION)?>');">Detalle</a>	
						
						
						</Td>
				</tr>
				<?php
			}
			?>
			</table>
		<?php
	}elseif($_REQUEST['tipo']==2 and $estado_sesion==0)//Detalle marcacion
	{		
		$id=decrypt($_REQUEST['marca'],ENCRIPTACION);
		$marca=getMarcaciones(" and id_marcacion=".$id."");
		$user=getUsuario(" and mail ilike '".$marca[0][1]."'");
		//print_R($marca);
		$tipo="entrada";
		if($marca[0][10]==1)
			$tipo="salida";
		$tip_mar="Normal";
		if($marca[0][4]==1)	
		{
			$tip_mar="Libre";	
		}
		?>
		<table border=1 id="table_resul" class="bordered">
		 <tr><td>Lugar</td><td><?=ucwords($marca[0][11])?></td></tr>	
		 	<tr><td>Tipo</td><td><?=$tip_mar?></td></tr>	
		 	<?php
		 	if($marca[0][4]==1)	
		 	{
		 		?>
		 		<tr><td>Direcci&oacute;n</td><td><?=$marca[0][12]?></td></tr>	
		 		<?php
		 	}
		 	?>
		 <tr><td>Usuario</td><td><?=ucwords($user[7])?></td></tr>
		 <tr><td>Mail Usuario</td><td><?=ucwords($marca[0][1])?></td></tr>		
			<tr><td>Fecha Marcaci&oacute;n</td><td><?=ucwords($marca[0][3])?></td></tr>		
			<tr><td>Comentario</td><td><?=ucwords($marca[0][9])?></td></tr>		
			<tr><td>Tipo Marcaci&oacute;n</td><td><?=ucwords($tipo)?></td></tr>	
			</table>
			<?php
			if($_SESSION['tip_cli_web']==1)
			{
				$asistentes=getMarcaInt(" and estado=0 and id_marca_base=".$id."");
			?>
				<br>	
				<spam class=tit_pop>Asistencia </spam>
				<?php
				if(count($asistentes)>0)
				{
					?>
					<img src="img/email_open.png" onclick="mailLista(<?=$id?>);" class=mano title="Enviar por mail">
					<br><br>
					<div class=table_lista>
					<table border=1 id="table_resul" class="bordered">
					
					<tr><td><strong>Usuario</strong></td><td><strong>Asiste</strong></td></tr>	
					<?php
					
					foreach($asistentes as $i => $asis)
					{
						$usuario_int=getUsuariosInterno(" and id_usuario_interno=".$asis[1]."");
						$as="SI";
						if($asis[4]=='f')
						{
							$as="NO";
						}
						$color="";
						if($i % 10==0)
						{
							$color="tabla_fila";
						}
						?>
						<tr class="<?=$color?>"><td><?=ucwords($usuario_int[0][1])?></td><td><?=$as?></td></tr>	
						
						<?php
					}
					?>	
				</table>
				<?php
			}else
			{
				echo "No Disponible";
			}
		}
		?>
	</div>
		<div id="msg_error_add" class="msg_error"></div>
			<?php
	}elseif($_REQUEST['tipo']==3 and $estado_sesion==0)
	{
		$asistentes=getMarcaInt(" and estado=0 and id_marca_base=".$_REQUEST['marca']."");
		$html ="<br>Listado de asistencia<br><br>";
		$html .='<table border=1 id="table_resul" class="bordered">';
			
			$html .='<tr><td><strong>Usuario</strong></td><td><strong>Asiste</strong></td></tr>	';
			
			
			foreach($asistentes as $asis)
			{
				$usuario_int=getUsuariosInterno(" and id_usuario_interno=".$asis[1]."");
				$as="SI";
				if($asis[4]==1)
				{
					$as="NO";
				}
				
				$html .='<tr><td><?=ucwords($usuario_int[0][1])?></td><td><?=$as?></td></tr>	';
				
				
			}
				
		$html .='</table>';
		sendMail($_SESSION["usuario_web"],$html,"Lista asistencia");
	}
}

?>