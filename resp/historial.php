<?php
include("funciones.php");
$data_server= explode("?",$_SERVER['HTTP_REFERER']);
$estado_sesion=estado_sesion();
if(substr(strtolower($data_server[0]),0,strlen(PATH_SITE))==PATH_SITE)
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
}
?>					