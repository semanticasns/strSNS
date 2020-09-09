<?php
	session_start();
	$error = '';
   
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$myusername = strip_tags($_POST['user']) ;
		$mypassword = strip_tags($_POST['password']) ; 
		$fecha_actualiz = strip_tags($_POST['fecha_actualiz']) ; 		
		$clave_cifrada = md5($mypassword) ;		
		$_SESSION['path_include'] = "Access"; 		
		include $_SESSION['path_include']."/configbd_str.php"; $dbConn =  connect_str($db_str);     
		$sql = $dbConn->prepare("SELECT id_suscrip, organizacion, contacto_mail, server_ftp, server_ftp_folder FROM str_suscriptores WHERE login = ? and password = ? and id_suscrip = 99");
		$sql->bindParam(1, $myusername, PDO::PARAM_STR);
		$sql->bindParam(2, $clave_cifrada, PDO::PARAM_STR);		
		$sql->execute();	
		$result = $sql->rowCount();
		if($result == 1) {			
			while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {  	
				$_SESSION['login_user'] = $myusername;  
				$_SESSION['password'] = $mypassword;  	
				$_SESSION['id_suscrip'] = $fila['id_suscrip'];  
				$_SESSION['organizacion'] = $fila['organizacion'] ;  
				$_SESSION['contacto_mail'] = $fila['contacto_mail'] ;  	
				$_SESSION["server_ftp"] = $fila['server_ftp'] ;  
				$_SESSION["server_ftp_folder"] = $fila['server_ftp_folder'] ;  
				$_SESSION["fecha_actualiz"] = $fecha_actualiz ;  		
				header("location: REST_server_distribucion.php") ;
			}
		}			
		else { $error = "Usuario o password incorrectos"; }	
	}
	
?>

<html>  
	<head>
      <title>strSNS - Acceso</title>    
      <style>
        body {font-family:Calibri, Helvetica, sans-serif; font-size:24px; }
        label { font-weight:bold; width:100px; font-size:18px; }
        .box { border:#666666 solid 1px; }
		.titular { font-size:20pt; color:darkblue; }
		.leyenda { font-size:10pt; color:darkblue; }
      </style>    
	</head>
   
	<body bgcolor = "#FFFFFF">	
		<table>
		<tr>
		<td><img alt='Ministerio de Sanidad' width='265px' src='MSAGob.jpg'></td><td class='titular'><b>strSNS</b><br>Servidor de Terminologías de Referencia del SNS</td>
		</tr>		
		</table>
		<br>
		<ul><p class='titular' align='center'><b>Distribución de contenidos a suscriptores</b></p></ul>
		<div align = "center"><br>
			<div style = "width:500px; border: solid 1px #333333; " align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>strSNS - Acceso</b></div>				
				<div style = "margin:30px">              
					<form action = "" method = "post">
						<table>
							<tr><td><label>UserName&nbsp&nbsp&nbsp</label><br/></td><td><input type = "text" name = "user" class = "box"/></td></tr>
							<tr><td><label>Password&nbsp&nbsp&nbsp</label><br/></td><td><input type = "password" name = "password" class = "box" /></td></tr>
							<tr><td><label>&nbsp&nbsp&nbsp</label></td><td></td></tr>	
							<tr><td><label>Fecha última actualización&nbsp&nbsp&nbsp</label><br/></td><td><input type = "date" name = "fecha_actualiz" class = "box" /></td></tr>
						</table>
						<br><br>
						<input type = "submit" value = " Acceder "/><br />
					</form>             
					<div style = "font-size:14px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>				
				</div>				
			</div>			
		</div>
	</body>
	
</html>

