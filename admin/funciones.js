function validarEmail( email ) {
	  var valido=true;
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(email) )
        valido=false;
        
   return valido;     
}



function OpenModal()
{
		$( "#grilla" ).dialog( "open" );
}
function OpenModalMapa()
{
		$( "#grilla_mapa" ).dialog( "open" );
		
}
function CloseModalMapa()
{
		$( "#grilla_mapa" ).dialog( "close" );
}
function CloseModal()
{
		$( "#grilla2" ).dialog( "close" );
}
function OpenModal2()
{
		$( "#grilla2" ).dialog( "open" );
}
function OpenModalReg()
{
		$( "#grilla_def" ).dialog( "open" );
}
function CloseModalReg()
{
		$( "#grilla_def" ).dialog( "close" );
}


function salir()
				{
					$("#contenido").load("query.php", 
						{tipo:2} 
							,function(){	
							}
					);
				}
				


/*USUARIOS*/
function filtrar_us()
{
	var mail=$.trim(document.getElementById("nom_em").value);
	var estado=$.trim(document.getElementById("em_estado").value);
	var nombre=$.trim(document.getElementById("nom_fil").value);
	$("#result2").load("qr_usuarios.php", 
						{tipo:1, nombre:nombre,estado:estado,mail:mail} 
							,function(){
									
							}
	);
}
function loadUsuario(id_usuario)
{
	$("#grilla_def").load("qr_usuarios.php", 
							{tipo:2, usuario:id_usuario} 
								,function(){
						OpenModalReg();
								}
		);
}
function updateUsuario(id_usuario)
{
	var mail=$.trim(document.getElementById("mail_us").value);
	var nombre=$.trim(document.getElementById("nom_us").value);	
	var clave=$.trim(document.getElementById("clave").value);	
		
		
		
	var msg="";
	var valida=true;

	if($.trim(mail)=="" || !validarEmail(mail))
	{		
		valida=false;
		msg="<strong>Mail es obligatorio y debe tener formato correcto.</strong><br>";
	}
	
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#output").load("qr_usuarios.php", 
							{tipo:3, mail:mail,nom:nombre,id:id_usuario,clave:clave} 
								,function(){
									CloseModalReg();
										filtrar_us();
								}
		);
	}
}

function nuevoUsuario()
{

	$("#grilla_mapa").load("qr_usuarios.php", 
						{tipo:4} 
							,function(){
									OpenModalMapa();
							}
	);
	

}
function saveUsuario()
{
	var mail=$.trim(document.getElementById("mail_us").value);
	var nombre=$.trim(document.getElementById("nom_us").value);
		
		var key_us=$.trim(document.getElementById("key_us").value);
	
		
	var msg="";
	var valida=true;

	if(!validarEmail(mail))
	{		
		valida=false;
		msg +="<strong>Mail es obligatorio y debe tener formato correcto.</strong><br>";
	}
	if($.trim(key_us)=="" || $.trim(mail)=="" || $.trim(nombre)=="")
	{		
		valida=false;
		msg +="<strong>Todos los campos son obligatorios</strong><br>";
	}
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#output").load("qr_usuarios.php", 
							{tipo:5, mail:mail,clave:key_us,nombre:nombre} 
								,function(){
									CloseModalMapa();
										filtrar_us();
								}
		);
	}
	
}
function upUsuarioEst(estado,id_usuario)
{

		$("#output").load("qr_usuarios.php", 
							{tipo:6, estado:estado,id:id_usuario} 
								,function(){
									CloseModalReg();
										filtrar_us();
								}
		);
	
}

function filtrar_cli()
{
		
	var estado=$.trim(document.getElementById("em_estado").value);
	var nombre=$.trim(document.getElementById("nom_fil").value);
	$("#result2").load("query.php", 
						{tipo:3, nombre:nombre,estado:estado} 
							,function(){
									
							}
	);
}
function nuevoCliente()
{

	$("#grilla_mapa").load("query.php", 
						{tipo:7} 
							,function(){
									OpenModalMapa();
							}
	);
	

}
function loadCliente(id_usuario)
{
	$("#grilla_def").load("query.php", 
							{tipo:4, usuario:id_usuario} 
								,function(){
						OpenModalReg();
								}
		);
}
function saveCliente()
{

	var nombre=$.trim(document.getElementById("nom_us").value);
	var mail=$.trim(document.getElementById("mail_us").value);
	var pais=$.trim(document.getElementById("pais_us").value);
	var tipo=$.trim(document.getElementById("tipo_us").value);
	
		
	var msg="";
	var valida=true;
 if(nombre=="" || !validarEmail(mail))
  {
  	msg +="Nombre es obligatorio<br>Formato del mail debe ser valido";
  	valida=false;
  }
	

	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#output").load("query.php", 
							{tipo:8,nombre:nombre,mail:mail,pais:pais,tipo_cli:tipo} 
								,function(){
									CloseModalMapa();
										filtrar_cli();
								}
		);
	}
	
}
function updateCliente(id_usuario)
{
	
	
	var nombre=$.trim(document.getElementById("nom_ed").value);	
	var mail=$.trim(document.getElementById("mail_ed").value);
	var pais=$.trim(document.getElementById("pais_ed").value);		
		
		var tipo=$.trim(document.getElementById("tipo_ed").value);
	var msg="";
	var valida=true;
  if(nombre=="" || (!validarEmail(mail) && mail!=""))
  {
  	msg +="Nombre es obligatorio<br>Formato del mail debe ser valido";
  	valida=false;
  }
	
	
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#output").load("query.php", 
							{tipo:5,nom:nombre,id:id_usuario,mail:mail,pais:pais,tipo_cli:tipo} 
								,function(){
									CloseModalReg();
										filtrar_cli();
								}
		);
	}
}
function upClienteEst(estado,id_usuario)
{

		$("#output").load("query.php", 
							{tipo:6, estado:estado,id:id_usuario} 
								,function(){
									CloseModalReg();
										filtrar_cli();
								}
		);
	
}

function filtrar_clius()
{
	var estado=$.trim(document.getElementById("em_estado").value);
	var nombre=$.trim(document.getElementById("nom_fil").value);
	var cliente=$.trim(document.getElementById("cli").value);
	$("#result2").load("qr_usuarios_cli.php", 
						{tipo:1, nombre:nombre,estado:estado,cliente:cliente} 
							,function(){
									
							}
	);
}

function loadUsuariocli(id_usuario)
{
	$("#grilla_def").load("qr_usuarios_cli.php", 
							{tipo:2, usuario:id_usuario} 
								,function(){
						OpenModalReg();
								}
		);
}
function updateUsuarioCli(id_usuario)
{
	
	var mail=$.trim(document.getElementById("mail_us").value);
	var nombre=$.trim(document.getElementById("nom_us").value);	
	var clave=$.trim(document.getElementById("clave").value);	
	var tipo_us=$.trim(document.getElementById("tipo_us").value);	
		
		
		
	var msg="";
	var valida=true;

	if($.trim(mail)=="" || !validarEmail(mail))
	{		
		valida=false;
		msg="<strong>Mail es obligatorio y debe tener formato correcto.</strong><br>";
	}
	
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#output").load("qr_usuarios_cli.php", 
							{tipo:3, mail:mail,nom:nombre,id:id_usuario,clave:clave,tipo_us:tipo_us} 
								,function(){
									CloseModalReg();
										filtrar_clius();
								}
		);
	}
}

function nuevoUsuarioCli()
{

	$("#grilla_mapa").load("qr_usuarios_cli.php", 
						{tipo:4} 
							,function(){
									OpenModalMapa();
							}
	);
	

}
function saveUsuarioCli()
{
	var cliente=$.trim(document.getElementById("cli_us").value);
	var mail=$.trim(document.getElementById("mail_us").value);
	var nombre=$.trim(document.getElementById("nom_us").value);
		var tipo_us=$.trim(document.getElementById("tipo_us").value);
		var key_us=$.trim(document.getElementById("key_us").value);
	
		
	var msg="";
	var valida=true;

	if(!validarEmail(mail))
	{		
		valida=false;
		msg +="<strong>Mail es obligatorio y debe tener formato correcto.</strong><br>";
	}
	if($.trim(key_us)=="" || $.trim(mail)=="" || $.trim(nombre)=="")
	{		
		valida=false;
		msg +="<strong>Todos los campos son obligatorios</strong><br>";
	}
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#output").load("qr_usuarios_cli.php", 
							{tipo:5, mail:mail,clave:key_us,nombre:nombre,cliente:cliente,tipo_us:tipo_us} 
								,function(){
									CloseModalMapa();
										filtrar_clius();
								}
		);
	}
	
}
function upUsuarioEstCli(estado,id_usuario)
{

		$("#output").load("qr_usuarios_cli.php", 
							{tipo:6, estado:estado,id:id_usuario} 
								,function(){
									CloseModalReg();
										filtrar_clius();
								}
		);
	
}