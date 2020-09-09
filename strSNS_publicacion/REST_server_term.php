<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<style> 
table { border-collapse: nocollapse; font-family:Calibri; font-size:11pt; }
table, td, th { border: 0px solid black; padding: 2px; }
th {font-family:Calibri; font-size:11pt; text-align:left; background-color:#FFD633}
tr {font-family:Calibri; font-size:11pt; text-align:left; font-weight: normal;}
body {font-family:Calibri; font-size:11pt;}
div { display: block;}
h2 { background-color: #66CDAA; }
h5 { font-size:11pt; font-weight: bold;} ;
strong { font-weight: nobold; }
.divtext {overflow:auto; background-color:red; border:solid gray 1px; padding:5px; width:60em; min-height:1.5em; color:navy; font-family:Consolas,Menlo; cursor:text; }
</style>
</head>

<body>
<h1>Servicio REST</h1>

<?php
// required headers
header("Access-Control-Allow-Origin: *");
$busca = strip_tags($_POST['consulta']) ;

$hoy = getdate();
$dia = strval($hoy['mday']) ; 
$mes = strval($hoy['mon']) ; 
$hora = strval($hoy['hours']) ; 
$minuto = strval($hoy['minutes']) ;
if ( $hoy['mday'] < 10) { $dia = "0"."".$dia ; } 
if ( $hoy['mon'] < 10) { $mes = "0"."".$mes ; } 
if ( $hoy['hours'] < 10) { $hora = "0"."".$hora ; } 
if ( $hoy['minutes'] < 10) { $minuto = "0"."".$minuto ; } 
$fecha  = $hoy['year'].$mes.$dia ; 
echo "<br>" ;


// REST Términos 

	include $_SESSION['path_include']."/configbd_gex.php"; $dbConn = connect($db);
	include $_SESSION['path_include']."/configbd_ftp.php"; $db_ftp = connect2($db_id);		
	$filtro = rtrim($busca) ;	
	
	if ($filtro == null)  { 
		echo "<script language='javascript'> alert('No existen coincidencias')</script><br>" ; 
		echo "<script language='javascript'> window.close() </script> " ;
	}		
	
	$aleat = rand(10000000, 99999999);			
	$fichero = $_SESSION['id_suscrip']."_term_".$aleat."_".$fecha.".json" ;	
	$fice = $_SESSION['id_suscrip']."_term_".$aleat."_".$fecha.".json" ;	

	$sql = $dbConn->prepare("select id, conceptId, term, typeId, languageCode from snomedct_gex.sct2_description_snapshot_global 
			 					where active = 1 and term like ? ");
	$sql->bindValue(1, "%$filtro%", PDO::PARAM_STR);							
	$sql->execute();
	$result = $sql->rowCount();		
	if ($result == 0 )  { 
		echo "<script language='javascript'> alert('No existen coincidencias')</script><br>" ; 
		echo "<script language='javascript'> window.close() </script> " ;
		exit() ;
	}		
	$nomfic = strtr($filtro,"áéíóúñ","aeioun") ;
	$mensajemail = "Notificación de descarga de strSNS (Servidor de Terminologías de Referencia del SNS: \r\n Fecha: ".$fecha."\r\n Archivo: ".$fice."\r\n" ;
	$cabeceras = 'From: semanticaSNS@mscbs.es' ."\r\n".'X-Mailer: PHP/' . phpversion();
//	mail($_SESSION['contacto_mail'],'Asunto: Notificación de descarga strSNS',$mensajemail,$cabeceras) or die ("Error: error envio mail<br>") ;

	$myfile = fopen($fichero, "w") or die("Imposible crear fichero!");				
	$valor9 = $sql->setFetchMode(PDO::FETCH_ASSOC);	
	ini_set('memory_limit', '-1');
	fwrite ($myfile,json_encode( $valor9=$sql->fetchAll(),JSON_UNESCAPED_UNICODE ));			
	fclose($myfile);	
	
//	Establece conexión FTP y posiciona el fichero JSON
	$f1 = ftp_connect($_SESSION['server_ftp']) ; $f2 = ftp_login($f1, "anonymous","") ;
	if (!$upload = ftp_put($f1, $fice, $fichero, FTP_BINARY,0)) {echo "Error en ftp_put al subir $fice..."."<br>";} 
		else {echo "El fichero ".$fice." ha sido posicionado en el servidor FTP <br><br>" ;} 

	//  $readfile = fopen($fichero,'r'); $texto = fread($readfile, filesize($fichero)) ; fclose($readfile) ;	 // comentar linea en versión publicada.
	//	echo $texto ; 

	unlink($fichero);
	exit() ;	

?>
</body>
</html>
