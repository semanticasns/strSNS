<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<style> 
table { border-collapse: nocollapse; font-family:Calibri; font-size:11pt; }
table, td, th { border: 0px solid black; padding: 2px; vertical-align: text-top; }
th {font-family:Calibri; font-size:10pt; text-align:left; background-color:#FFD633}
tr {font-family:Calibri; font-size:10pt; text-align:left; font-weight: normal;}
body {font-family:Calibri; font-size:11pt;}
div { display: block;}
h2 {  }
h5 { font-size:11pt; font-weight: bold;} ;
strong { font-weight: nobold; }
input[type=button], input[type=reset] { font-family:Calibri; background-color: #4CAF50;  border: none;  color: white;  padding: 4px 8px;  text-decoration: none;  margin: 1px 1px;  cursor: pointer;}
input[type=submit] { font-family:Calibri; background-color: #FEFECF;  border: none;  color: black;  padding: 2px 4px;  text-decoration: none;  margin: 1px 1px;  cursor: pointer;}

.cabecera { background-color: #FFD633; }
.divtext {overflow:auto; background-color:red; border:solid gray 1px; padding:5px; width:60em; min-height:1.5em; color:navy; font-family:Consolas,Menlo; cursor:text; }
</style>
</head>

<body>

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

 
// REST Mapeo SNOMED CT a ICD-9 WHO

	include $_SESSION['path_include']."/configbd_gex.php"; $dbConn =  connect($db);
	include $_SESSION['path_include']."/configbd_ftp.php"; $db_ftp= connect2($db_id);	
	$filtro = rtrim($busca) ;
	if ($filtro == null)  { 
		echo "<script language='javascript'> alert('No existen coincidencias')</script><br>" ; 
		echo "<script language='javascript'> window.close() </script> " ;
		exit() ;
	}

	$sql = $dbConn->prepare("select * from der2_iissscrefset_complexmapsnapshot_int a inner join sct2_description_snapshot_global b
		on a.referencedComponentId = b.conceptId 
		where b.typeId = 900000000000003001 and b.languageCode != 'en' and b.active = 1 and 
		(b.term like '%".$filtro."%' or b.term like '".$filtro."%' or referencedComponentId like '".$busca."%' or referencedComponentId like '%".$busca."%' or mapTarget like '%".$busca."%') order by term ");

//	$sql->bindValue(1, "$filtro", PDO::PARAM_INT);
	$sql->execute();
	$result = $sql->rowCount();	
	if ($result == 0)  { 
		echo "<script language='javascript'> alert('No existen coincidencias')</script><br>" ; 
		echo "<script language='javascript'> window.close() </script> " ;
		exit() ;
	}		
	echo "<h2>CIE-9-MC Map to SNOMED CT</h2>" ;
	echo "<table>" ;
	echo "<tr><td class=cabecera width=10%>referencedComponentId</td><td class=cabecera>term</td><td class=cabecera>mapTarget</td><td class=cabecera>active</td><td class=cabecera>effectiveTime</td><td class=cabecera>moduleId</td><td class=cabecera>refsetId</td><td class=cabecera>Mail</td></tr>" ;
	while ($qr = $sql->fetch(PDO::FETCH_ASSOC)) : 	
		$line = 1 ; 	
		$var01 = $qr['effectiveTime'] ;	
		$var02 = $qr['active'] ;	
		$var03 = $qr['moduleId'] ;	
		$var04 = $qr['refsetId'] ;	
		$var05 = $qr['referencedComponentId'] ;	
		$var06 = $qr['term'] ;				
		$var07 = $qr['mapTarget'] ;		
		$var09 = "<form action='https://webs.somsns.es/cnr/MLT_basico.php' method='POST' target='_blank' >
				<input type='submit' value='".$var05."' name='consulta' target='_blank' ></form>" ;
		$var08 = "<a href='mailto:semanticasns@mscbs.es?subject=Comentario sobre Refset Alergias. Causas de registro&body=Concepto: ".$var05." |".$var06."|  %0A%0AComentarios: %0A%0A' >e-mail</a>" ;
		echo "<tr><td>".$var09."</td><td>".$var06."</td><td>".$var07."</td><td>".$var02."</td><td>".$var01."</td><td>".$var03."</td><td>".$var04."</td><td>".$var08."</td></tr>" ;
	endwhile ;
	echo "</table>" ;	

	exit() ;	
?>
</body>
</html>
