					<div class="ui-bar ui-bar-a" id=barra_sup style="text-align:center;">
					 Marcaci&oacute;n Libre
					</div>
    	    <p id="form_interior">
						
						<label for="text-basic">Nombre del Lugar</label>
						<input type="text" class=input_form2 name="nom_lug" id="nom_lug" placeholder="ejemplo: Clinica del Norte">						
						<label for="text-basic">Calle</label>
						<input type="text" class=input_form2 name="calle_lug" id="calle_lug" placeholder="juan esteban montero">		
						<label for="text-basic">N&uacute;mero</label>
						<input type="text" class=input_form2 name="num_lug" id="num_lug" placeholder="4550">		
						<label for="text-basic">Comuna</label>
						<input type="text" class=input_form2 name="com_lug" id="com_lug" placeholder="Las condes">		
						<label for="text-basic">Correo Electronico</label>
						<input type="text" class=input_form2 name="mail_lug" id="mail_lug" placeholder="Correo Destino">	
						<label for="text-basic">Comentario</label>	
						<textarea cols="40" rows="4" name="textarea" id="coment_lug" name="coment_lug"></textarea>
						<label for="text-basic">Entrada/Salida?</label>
						<select name="slider1" id="slider1" data-role="slider" data-theme="b">
    					<option value="0" selected>Entrada</option>
    					<option value="1">Salida</option>
							
					</p>          
					<p id="form_login">
						<input type="button" onclick="validaMarcacion();" value="Marcar">
					</p>
