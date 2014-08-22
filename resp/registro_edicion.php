<?php
include("funciones.php");
$estado_sesion=estado_sesion();
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
