<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<style> 
table { border-collapse: nocollapse; font-family:Calibri; font-size:10pt; }
table, td, th { border: 0px solid black; padding: 2px; vertical-align: text-top;}
th {font-family:Calibri; font-size:11pt; text-align:left; background-color:#FFD633}
tr {font-family:Calibri; font-size:10pt; text-align:left; font-weight: normal;}
body {font-family:Calibri; font-size:10pt;}
div { display: block;}
h2 { }
h5 { font-size:11pt; font-weight: bold;} ;
strong { font-weight: nobold; }
.cabecera { background-color: #FFD633; }
.divtext {overflow:auto; background-color:red; border:solid gray 1px; padding:5px; width:60em; min-height:1.5em; color:navy; font-family:Consolas,Menlo; cursor:text; }
</style>
</head>

<body>
<h2>Consulta de Valuesets (bilingües) de Medicamentos</h2>

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
 
// REST código nacional de medicamentos

	include $_SESSION['path_include']."/configbd_drug.php"; $dbConn =  connect_drug($db_drug);
	include $_SESSION['path_include']."/configbd_ftp.php"; $db_ftp= connect2($db_id);	
	$filtro = rtrim($busca) ;	
	
	if ($filtro == null)  { 
		echo "<script language='javascript'> alert('No existen coincidencias')</script><br>" ; 
		echo "<script language='javascript'> window.close() </script> " ;
		exit() ;
	}

	$sql = $dbConn->prepare("select * from snomedct_drug.npa_valueset_medicamentos where (des_prese like '%".$filtro."%' or cod_nacion like '".$busca."%') and active = 1 ");
//	$sql->bindValue(1, "$filtro", PDO::PARAM_INT);
	$sql->execute();
	$result = $sql->rowCount();	
	if ($result == 0)  { 
		echo "<script language='javascript'> alert('No existen coincidencias')</script><br>" ; 
		echo "<script language='javascript'> window.close() </script> " ;
		exit() ;
	}					

	ini_set('memory_limit', '-1');	
	echo "<h2>Medicamentos (Nomenclátor Alcántara)</h2>" ;
	echo "<table>" ;
	echo "<tr><td class=cabecera>system</td><td class=cabecera>version</td><td class=cabecera>active</td><td class=cabecera>cod_nacion</td><td class=cabecera>des_prese</td>
		<td class=cabecera>term_dcsa_es</td><td class=cabecera>term_dcsa_en</td><td class=cabecera>udosis_descriptor_es</td><td class=cabecera>udosis_descriptor_en</td>
		<td class=cabecera>ucontenido_descriptor_es</td><td class=cabecera>ucontenido_descriptor_en</td><td class=cabecera>ff_descriptor_es</td><td class=cabecera>ff_descriptor_en</td>		
		</tr>" ;
	while ($qr = $sql->fetch(PDO::FETCH_ASSOC)) : 	
		$line = 1 ; 	
		$var01 = $qr['system'] ;	
		$var02 = $qr['version'] ;	
		$var03 = $qr['active'] ;	
		$var04 = $qr['cod_nacion'] ;	
		$var05 = $qr['des_prese'] ;	
		$var06 = $qr['term_dcsa_es'] ;	
		$var07 = $qr['term_dcsa_en'] ;	
		$var08 = $qr['udosis_descriptor_es'] ;	
		$var09 = $qr['udosis_descriptor_en'] ;		
		$var10 = $qr['ucontenido_descriptor_es'] ;	
		$var11 = $qr['ucontenido_descriptor_en'] ;	
		$var12 = $qr['ff_descriptor_es'] ;	
		$var13 = $qr['ff_descriptor_en'] ;			
		echo "<tr><td>".$var01."</td><td>".$var02."</td><td>".$var03."</td><td>".$var04."</td><td>".$var05."</td><td>".$var06."</td><td>".$var07."</td><td>".$var08."</td><td>".$var09."</td><td>".$var10."</td><td>".$var11."</td><td>".$var12."</td><td>".$var13."</td></tr>" ;
	endwhile ;
	echo "</table>" ;
	
	exit() ;	

?>
</body>
</html>
