<fieldset>
<legend>Men&uacute;</legend>

<table id=table_filtro style="text-align:center;">

             <tr>
             	<td >
                    <a href="javascript:window.location='index.php'"><img src="img/lugar.png" height=30px width=30px title="Lugares"></a>
                </td>
                <td >
                    <a href="javascript:window.location='usuarios.php'"><img src="img/usuarios.png" height=30px width=30px title="Usuarios"></a>
                </td>
               <?php
               if($_SESSION['tip_cli_web']==1) //uuario+ lista asistencia alumno
               {
               	
               	?>
               	<td >
                    <a href="javascript:window.location='internos.php'"><img src="img/student.png" height=30px width=30px title="Lista Usuarios Interna"></a>
                </td>
               	<?php
               }
               ?>
               <td >
                    <a href="javascript:window.location='asistencias.php'"><img src="img/report_check2.png" height=30px width=30px title="Marcaciones"></a>
                </td>

                <td >
                    <a href="javascript:salir();"><img src="img/cancel.png" height=30px width=30px title="Cerrar Sesi&oacute;n"></a>
                </td>
               
            </tr>
		
</table>
</fieldset>