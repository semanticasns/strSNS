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


// REST Concept

	include $_SESSION['path_include']."/configbd_gex.php"; $dbConn =  connect($db);
	include $_SESSION['path_include']."/configbd_ftp.php"; $db_ftp= connect2($db_id);	
	$filtro = rtrim($busca) ;	
	
	// Concept
	$sql = $dbConn->prepare("select * from sct2_concept_snapshot_global where id = :idfilter ");
	$sql->bindParam(":idfilter", $filtro, PDO::PARAM_STR);
    $sql->execute();		
	$result = $sql->rowCount();			
	if ($result == 0)  { 
		echo "<script language='javascript'> alert('No existe el concepto solicitado')</script><br>" ; 
		echo "<script language='javascript'> window.close() </script> " ;
		exit() ;
	}	
		
	$aleat = rand(10000000, 99999999);			
	$fichero = $_SESSION['id_suscrip']."_concept_".$aleat."_".$fecha.".json" ;
	$fice = $_SESSION['id_suscrip']."_concept_".$aleat."_".$fecha.".json" ;	
	$myfile = fopen($fichero, "w") or die("Imposible crear fichero!");	
	$valor9 = $sql->setFetchMode(PDO::FETCH_ASSOC);	
	$result = $sql->rowCount(); 
	if ($result !=0 )
		{
		$cuanto = json_encode( $valor9=$sql->fetchAll(),JSON_UNESCAPED_UNICODE ) ; 
		$cuantot = "[".substr($cuanto,1,strlen($cuanto)-2)."," ;
		}
	
	// Descriptions
	$sqla1 = $dbConn->prepare("select * from sct2_description_snapshot_global where conceptId = :idfilter ") ;	
	$sqla1->bindParam(":idfilter", $filtro, PDO::PARAM_STR);
    $sqla1->execute();	
	$valora1 = $sqla1->setFetchMode(PDO::FETCH_ASSOC);	
	$result = $sqla1->rowCount(); if ($result !=0 )
	{	
	$cuanto = json_encode( $valora1=$sqla1->fetchAll(),JSON_UNESCAPED_UNICODE ) ; 
	$cuantot = $cuantot.substr($cuanto,1,strlen($cuanto)-2)."," ;
	}
	
	// Textdefinition 
	$sql5 = $dbConn->prepare("select * from sct2_textdefinition_snapshot_global where conceptId = :idfilter order by id ") ;
	$sql5->bindParam(":idfilter", $filtro, PDO::PARAM_STR);
    $sql5->execute();	
	$valor5 = $sql5->setFetchMode(PDO::FETCH_ASSOC);	
	$result = $sql5->rowCount(); if ($result !=0 )
	{
	$cuanto = json_encode( $valora5=$sql5->fetchAll(),JSON_UNESCAPED_UNICODE ) ; 
	$cuantot = $cuantot.substr($cuanto,1,strlen($cuanto)-2)."," ;
	}
		
	// Language Refsets
	$sql3 = $dbConn->prepare("select c.id, c.effectiveTime, c.active, c.moduleId, c.refsetId, c.referencedComponentId, c.acceptabilityId 
		from sct2_description_spainextensionsnapshot_es_es b, der2_crefset_languagespainextensionsnapshot_es_es c, sct2_concept_spainextensionsnapshot_es a 
		where a.id = :idfilter and a.id = b.conceptId and c.referencedComponentId = b.id 
	union select c.id, c.effectiveTime, c.active, c.moduleId, c.refsetId, c.referencedComponentId, c.acceptabilityId 
		from sct2_description_spainextensionsnapshot_es_es b, der2_crefset_languagespainextensionsnapshot_en_us c, sct2_concept_spainextensionsnapshot_es a 
		where a.id = :idfilter and a.id = b.conceptId and c.referencedComponentId = b.id 
	union select c.id, c.effectiveTime, c.active, c.moduleId, c.refsetId, c.referencedComponentId, c.acceptabilityId 
		from sct2_description_snapshot_en_int b, der2_crefset_languagesnapshot_en_int c, sct2_concept_snapshot_int a
		where a.id = :idfilter and a.id = b.conceptId and c.referencedComponentId = b.id and c.refsetId = 900000000000509007
	union select c.id, c.effectiveTime, c.active, c.moduleId, c.refsetId, c.referencedComponentId, c.acceptabilityId 
		from sct2_description_spanishextensionsnapshot_es_int b, der2_crefset_languagespanishextensionsnapshot_es_int c, sct2_concept_spanishextensionsnapshot_int a 
		where a.id = :idfilter and a.id = b.conceptId and c.referencedComponentId = b.id ") ;
	$sql3->bindParam(":idfilter", $filtro, PDO::PARAM_STR);
    $sql3->execute();	
	$valor3 = $sql3->setFetchMode(PDO::FETCH_ASSOC);	
	$result = $sql3->rowCount(); if ($result !=0 )
	{
	$cuanto = json_encode( $valor3=$sql3->fetchAll(),JSON_UNESCAPED_UNICODE ) ; 
	$cuantot = $cuantot.substr($cuanto,1,strlen($cuanto)-2)."," ;
	}
	
	// Relationships
	$sqla2 = $dbConn->prepare("select * from sct2_relationship_snapshot_global where sourceid = :idfilter ") ;	
	$sqla2->bindParam(":idfilter", $filtro, PDO::PARAM_STR);
    $sqla2->execute();	
	$valora2 = $sqla2->setFetchMode(PDO::FETCH_ASSOC);	
	$result = $sqla2->rowCount(); if ($result !=0 )
	{
	$cuanto = json_encode( $valora2=$sqla2->fetchAll(),JSON_UNESCAPED_UNICODE ) ; 
	$cuantot = $cuantot.substr($cuanto,1,strlen($cuanto)-2)."," ;
	}
	
	// Relationships stated OWL
	$sql7 = $dbConn->prepare("select * from sct2_srefset_owlexpressionspanishextensionsnapshot_int  where referencedcomponentid = :idfilter 
		UNION select * from sct2_srefset_owlexpressionsnapshot_int where referencedcomponentid = :idfilter order by id, effectiveTime ") ;
	$sql7->bindParam(":idfilter", $filtro, PDO::PARAM_STR);
    $sql7->execute();	
	$valor7 = $sql7->setFetchMode(PDO::FETCH_ASSOC);		
	$result = $sql7->rowCount(); if ($result !=0 )
	{
	$cuanto = json_encode( $valor7=$sql7->fetchAll(),JSON_UNESCAPED_UNICODE ) ; 
	}
	$cuantot = $cuantot.substr($cuanto,1,strlen($cuanto)-2)."]";	
	fwrite ($myfile,$cuantot) ;			
	fclose($myfile);	
	ini_set('memory_limit', '-1');
//	Establece conexión FTP y posiciona el fichero JSON
	$f1 = ftp_connect($_SESSION['server_ftp']) ; $f2 = ftp_login($f1, "anonymous","") ;
	if (!$upload = ftp_put($f1, $fice, $fichero, FTP_BINARY,0)) {echo "Error en ftp_put al subir $fice..."."<br>";} 
		else {echo "El fichero ".$fice." ha sido posicionado en el servidor FTP <br><br>" ;} 

//	$readfile = fopen($fichero,'r'); $texto = fread($readfile, filesize($fichero)) ;  // comentar linea en versión publicada.
//   echo $texto ; fclose($readfile) ;
	unlink($fichero);
	
	exit() ;
?>
</body>
</html>








