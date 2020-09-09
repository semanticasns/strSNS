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
$busca = $_SESSION["fecha_actualiz"] ;

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

// REST distribución 

	include $_SESSION['path_include']."/configbd_gex.php"; $dbConn = connect($db);
	include $_SESSION['path_include']."/configbd_ftp.php"; $db_ftp = connect2($db_id);		
	$filtro = rtrim($busca) ;	
	
	if ($busca == null)  { 
		echo "<script language='javascript'> alert('Error en fecha')</script><br>" ; 
		echo "<script language='javascript'> window.close() </script> " ;
	}				
	$sql = $dbConn->prepare("select * from strsns.str_suscriptores ");						
	$sql->execute();
	$result = $sql->rowCount();		
	if ($result == 0 )  { 
		echo "<script language='javascript'> alert('No existen suscriptores')</script><br>" ; 
		echo "<script language='javascript'> window.close() </script> " ;
		exit ;
	}		
	while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) { 
		$sus1 = $fila['id_suscrip'] ;		
		$sql2 = $dbConn->prepare("select * from strsns.str_suscripciones where id_suscrip = ? and fecha_ini > ?  order by id_suscrip desc ");
		$sql2->bindValue(1, $sus1, PDO::PARAM_STR);	
		$sql2->bindValue(2, $busca, PDO::PARAM_STR);	
		$sql2->execute();
		$result2 = $sql2->rowCount();			
		if ($result2 != 0 )  { 		
			$server_ftp_client = $fila['server_ftp'] ;
			while ($fila2 = $sql2->fetch(PDO::FETCH_ASSOC)) { 		
				$sus2a  = $fila2['id_suscrip'] ;
				$sus2b = $fila2['id_recurso'] ;			
				$sql3  = $dbConn->prepare("select * from strsns.str_recursos where nemotec = ? and fecha_ultima_actualiz > ? ");
				$sql3->bindValue(1, $sus2b, PDO::PARAM_STR);	
				$sql3->bindValue(2, $busca, PDO::PARAM_STR);					
				$sql3->execute();				
				$result3 = $sql3->rowCount();	
				if ($result3 != 0 )  { 								
					while ($fila3 = $sql3->fetch(PDO::FETCH_ASSOC)) { 					
						$sus3a  = $fila3['nemotec'] ;
						$sus3b  = $fila3['MTC_tabla'] ;
						$sus3c  = $fila3['tabla'] ;	
						$sus3d  = $fila3['bd'] ;	
						$aleat = rand(10000000, 99999999);							
						if ( $sus3d != "ZIP" ) {							
							$sql4  = $dbConn->prepare("select * from ".$sus3d.".".$sus3c." ");
//							$sql4->bindValue(1, $sus3c, PDO::PARAM_STR);								
							$sql4->execute();			
							$fichero = $sus2a."_".$sus3a."_".$aleat."_".$fecha.".json" ;								
							$myfile = fopen($fichero, "w") or die("Imposible crear fichero!");				
							$valor9 = $sql4->setFetchMode(PDO::FETCH_ASSOC);								
							ini_set('memory_limit', '-1');
							fwrite ($myfile,json_encode( $valor9=$sql4->fetchAll(),JSON_UNESCAPED_UNICODE ));			
							fclose($myfile);	
							$ficdestino = $fichero ;
						} 
						else 
							{ $fichero = "Files/".$sus3c."" ; $ficdestino = $sus2a."_".$sus3a."_".$aleat."_".$sus3c ;  }
						
						//	Establece conexión FTP y posiciona el fichero JSON
		$f1 = ftp_connect($_SESSION['server_ftp']) ; $f2 = ftp_login($f1, "anonymous","") ;
						if (!$upload = ftp_put($f1, $ficdestino, $fichero, FTP_BINARY,0)) {echo "Error al subir $ficdestino..."."<br>";} 
							else {echo "El fichero ".$ficdestino." ha sido posicionado en el servidor FTP <br>" ;} 
						if ( $sus3d != "ZIP" ) { unlink($fichero); }					
					}
				}
			}
		}
	}	 
	echo '<script language="javascript">alert("Proceso finalizado.");</script><br>'; 
//	header("location: strSNS_login.php") ;
	exit ;

?>
</body>
</html>
