function validarEmail( email ) {
	  var valido=true;
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(email) )
        valido=false;
        
   return valido;     
}
function filtrar_em()
{
	
	
	var nombre=$.trim(document.getElementById("nom_em").value);
	var estado=$.trim(document.getElementById("em_estado").value);
	$("#result2").load("query.php", 
						{tipo:3, nombre:nombre,estado:estado} 
							,function(){
									
							}
	);
}	

var LC_lat="lat_em";
var LC_lon="lon_em";
function moveDrag(feature, pixel)
{
	
    		lon=feature.geometry['x'];
    		lat=feature.geometry['y'];
    		lonlat=new OpenLayers.LonLat(lon,lat).transform(
        new OpenLayers.Projection("EPSG:900913"), // de WGS 1984
        new OpenLayers.Projection("EPSG:4326") // a Proyección Esférica Mercator
  			);
    		
         document.getElementById(LC_lon).value=lonlat.lon;
         document.getElementById(LC_lat).value=lonlat.lat;
  
}
function loadEmpresa(id_empresa)
{
	$("#grilla_def").load("query.php", 
							{tipo:4, empresa:id_empresa} 
								,function(){
						OpenModalReg();
								}
		);
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
function BuscarGeo()
{
	var valida=true;
	var calle=$.trim(document.getElementById("calle_em").value);
		var numero=$.trim(document.getElementById("num_em").value);
			
	var comuna=$.trim(document.getElementById("com_em").value);
	if($.trim(calle)=="" || $.trim(numero)=="" ||$.trim(comuna)=="")
	{		
		valida=false;
		msg="<strong>Calle Numero y Comuna son campos son obligatorios.</strong><br>";
	}
	
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$( "#msg_error_add" ).html("Buscando...");
	$("#msg_error_add").load("query.php", 
							{tipo:5, calle:calle, numero:numero,comuna:comuna} 
								,function(){
						
								}
		);
	}
}
function verMapa2()
{
	lat_geo=document.getElementById('lat_em').value;
	lon_geo=document.getElementById('lon_em').value;
	limpiarMapa();		
	limpiarPuntosDrag();		
	//alert(""+lon_geo+","+lat_geo+"");
	addMarcadorVector(lon_geo,lat_geo,'','img/place.png',30,30);
	//moverCentro(lat_geo,lon_geo,13);
	moverCentro2(lon_geo,lat_geo,17);
	activarDrag();	
	OpenModal();	
}
function verMapa(lat_geo,lon_geo)
{
	
	limpiarMapa();	
	limpiarPuntosDrag();		
	addMarcadorVector(lon_geo,lat_geo,'','img/place.png',30,30);
	moverCentro2(lon_geo,lat_geo,18);
	activarDrag();	
	OpenModal();
	
	
	
}

function updateEmpresa(empresa)
{
		var nombre=$.trim(document.getElementById("nombre_em").value);
		
		
		var calle=$.trim(document.getElementById("calle_em").value);
		var numero=$.trim(document.getElementById("num_em").value);
			
	var comuna=$.trim(document.getElementById("com_em").value);
	var latitud=$.trim(document.getElementById("lat_em").value);
	
	
	var longitud=$.trim(document.getElementById("lon_em").value);
	var msg="";
	var valida=true;

	if($.trim(nombre)=="" || $.trim(latitud)=="" ||$.trim(longitud)=="")
	{		
		valida=false;
		msg="<strong>Nombre, latitud y longitud son campos son obligatorios.</strong><br>";
	}
	
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#output").load("query.php", 
							{tipo:6, empresa:empresa,nombre:nombre,calle:calle, numero:numero,comuna:comuna,latitud:latitud,longitud:longitud} 
								,function(){
									CloseModalReg();
										filtrar_em();
								}
		);
	}
}
function upEstadoEmpresa(estado,empresa)
{
	$("#output").load("query.php", 
							{tipo:7, empresa:empresa,estado:estado} 
								,function(){
								
										filtrar_em();
								}
		);
}
function salir()
				{
					$("#contenido").load("query.php", 
						{tipo:2} 
							,function(){	
							}
					);
				}
				
function nuevaEmpresa()
{
	$("#grilla_mapa").load("query.php", 
						{tipo:8} 
							,function(){
									OpenModalMapa();
							}
	);
	
}
function saveEmpresa()
{
		var nombre=$.trim(document.getElementById("nombre_em").value);
		
		var calle=$.trim(document.getElementById("calle_em").value);
		var numero=$.trim(document.getElementById("num_em").value);
			
	var comuna=$.trim(document.getElementById("com_em").value);
	var latitud=$.trim(document.getElementById("lat_em").value);
	
	var mail=$.trim(document.getElementById("mail_em").value);
	var coment=$.trim(document.getElementById("slider2").value);
	var salida=$.trim(document.getElementById("slider1").value);
	
	
	var longitud=$.trim(document.getElementById("lon_em").value);
	var msg="";
	var valida=true;

	if($.trim(nombre)=="" || $.trim(latitud)=="" ||$.trim(longitud)=="")
	{		
		valida=false;
		msg="<strong>Nombre, latitud y longitud son campos son obligatorios.</strong><br>";
	}
	
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#msg_error_add").load("query.php", 
							{tipo:9, nombre:nombre,calle:calle, numero:numero,comuna:comuna,latitud:latitud,longitud:longitud,mail:mail, coment:coment,salida:salida} 
								,function(){
									CloseModalMapa();
										filtrar_em();
								}
		);
	}
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
		
		var tipo=$.trim(document.getElementById("tipo_us").value);
		
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
							{tipo:3, mail:mail,tipo_us:tipo,nom:nombre,id:id_usuario,clave:clave} 
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
		var tipo=$.trim(document.getElementById("tipo_us").value);
		
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
							{tipo:5, mail:mail,tipo_us:tipo,clave:key_us,nombre:nombre} 
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