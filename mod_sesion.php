<div data-role="header" >
   			 
      		<h1><img src="images/obvii-logo-white.png">     		 </h1>  		 
      		
   		</div>    		
   	  <div data-role="content" id="contenido" >
   	  	<div data-role="popup" id="myPopup_ses">
							<p>This is a completely basic popup, no options set.						</p>
					</div>
    	  	
					<div class="ui-bar ui-bar-a" id=barra_sup style="text-align:center;">
					 Login
					</div>
    	    <p id="form_login">
						
						<input type="text" class=input_form name="mail_ses" id="mail_ses" value="<?=$_SESSION["mail_log"]?>" placeholder="Correo electronico">						
						<input type="password" class=input_form name="clave_ses" id="clave_ses"  autocomplete="off" placeholder="Contrase&ntilde;a">						
						<input type="button" onclick="inicioSesion();" value="Ingresar">
						<!--input type="button" onclick="cambiar('mod_registro');" value="Registrarme"-->
						<div id="msg_error_ses" class="msg_error"></div>
					</p>          
					<p id="form_login">
						<input type="button" onclick="cambiar('mod_recuperar');" value="Recuperar Contrase&ntilde;a">
					</p>
    	</div>