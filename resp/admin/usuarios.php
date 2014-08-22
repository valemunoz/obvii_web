<?php
include("../funciones.php");

$estado_web=estado_sesion_admin();
if($estado_web!=0)
{
	?>
	<script>
		window.location="login.php";
	</script>
	<?php 
	
}

?>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="css/rounded.css" />
		<link rel="shortcut icon" href="img/pin.png">
		<!--<link type="text/css" rel="stylesheet" href="/ws/sites/css/blitzer/jquery-ui-1.10.3.custom.min.css" />-->
		<link href="http://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet" type="text/css" />
		
		<title>Administrador General Obvii</title>
		<!--<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script> -->
<script src="funciones.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
 		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	 	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	 	
	<script>
			 $(function() {
    $( "#grilla" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
        $( "#grilla_mapa" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
         $( "#grilla_def" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

  });
		 $(function() {
    		$( "#desde" ).datepicker({dateFormat:"yy-mm-dd"});
  			});	

		 $(function() {
    		$( "#hasta" ).datepicker({dateFormat:"yy-mm-dd"});
  			});	
  			

		</script>
		
		<style>
			#mapa , #map
				{
					width:500px !important;
					height:400px !important;
				
				}
				#resultado
				{
					height:60% !important;
					overflow:auto;
					width:112%;
				}
.ui-dialog
{
	background-color:#F3F3F3;
	border-bottom: 1px solid #7EB6E2;
	color: #1F5988;
  font-size: 15px;
  height:500px !important;
  width:530px !important;
  
  text-align:center !important
}
#popup_CM3
{
	z-index:6000 !important;
}
			</style>

		</head>
	<body>
		<div class="img_left"><a target=BLANK_ href="http://www.architeq.cl"><img src="img/obvii-logo-blue.png"></a></div>
			
			
		<div id="contenido">
			
<div id="buscador">
<?php
include("header.php");
$cliente=getCliente(" and estado=0 order by nombre");
?>
</div>
<br>
		<div id="buscador">
	<fieldset>
	<legend>Filtro Usuarios</legend>
	
	<table id=table_filtro>
	
	            <tr>
	            	<td>
	            		
	            	</td>
	                <td>
	                    Cliente
	                </td>
		<td>
	                    Estado
	                </td>
	 <td>
	                    Nombre
	                </td>
	                <td>
	                    Mail
	                </td>
	               
	            </tr>
			<tr>
				<td>
	            		<img src="img/add.png" class=mano onclick="nuevoUsuarioCli();" title="Nuevo Usuario">
	            	</td>
	            	<td>
				<select id="cli" name="cli">				
						<option value=0 selected>Todos</option>
						<?php
						foreach($cliente as $cli)
						{
							?>
							<option value=<?=$cli[0]?>><?=ucwords($cli[1])?></option>
							<?php
						}
						?>
					
				</select>
				</td>
	
			<td>
				<select id="em_estado" name="em_estado">				
						<option value=10 selected>Todos</option>
						<option value=0>Activo</option>
						<option value=1>Inactivo</option>
					
				</select>
				</td>
			<td>
				<input type="text" id="nom_fil" name="nom_fil">
				</td>
			<td><input type="text" id='nom_em' name='nom_em'></td>
			<td><input type="button" onclick="filtrar_clius();" value="Filtrar"></td>
		</tr>	
	</table>
	
	
	</fieldset>
	</div>
<div id="spacer" style="height:50px"></div>
		<div id="result2">
		
	</div>
			<div id="resultado">
		
	</div>
	<div id="grilla_mapa" title="Datos">
<div id="map">
  </div>
	</div>
	<div id="grilla" title="Mapa">
  <div id="mapa">
  </div>
  
	</div>
	
	<div id="grilla_def" title="Mapa">
  
  
	</div>
<script>
	
	filtrar_clius();
	</script>
	
	<div id="output"></div>
	
	
	</body>
</html>
