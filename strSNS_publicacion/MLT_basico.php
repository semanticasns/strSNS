<?php session_start(); 
$_SESSION['path_include'] = "Access"; 
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<style> 
table { border-collapse: nocollapse; font-family:Calibri; font-size:11pt; }
table, td, th { border: 0px solid black; padding: 2px; }
th {font-family:Calibri; font-size:11pt; text-align:left; background-color:#FFD633}
tr {font-family:Calibri; font-size:11pt; text-align:left; font-weight: normal;}
.cabe {font-family:Calibri; font-size:16pt; text-align:left; font-weight: normal;}
.cabein {color:rgb(0,143,0) ; font-family:Calibri; font-size:16pt; text-align:left; font-weight: normal;   } 
.descrip {color:rgb(0,143,0) ; font-family:Calibri; text-align:left; font-weight: normal;   } 
.descrip-es {color:rgb(1,0,255) ; font-family:Calibri; text-align:left; font-weight: normal;   } 
body {font-family:Calibri; font-size:11pt;}
div { display: block;}
h1 { font-color:blue ; }
h5 { font-size:11pt; font-weight: bold;} 
strong { font-weight: nobold; }
.divtext {
overflow:auto;
background-color:white;
border:solid gray 1px;
padding:5px;
width:60em;
min-height:1.5em;
font-family:Consolas,Menlo;
cursor:text;
}
</style>
  
<?php  
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
$nocon = '' ; $nocon1 = '' ; $nocon2 = '' ; $nocon3 = '' ; 

include $_SESSION['path_include']."/configbd_gex.php"; $dbConn =  connect($db);	
$filtro = $busca ;
	
echo "<h1><b>SNOMED CT Multilingual Tree Basic. Concept Report</b></h1>" ;

$line = 0 ;
$sql1a =$dbConn->prepare("select id, active, effectiveTime, definitionStatusId from snomedct_gex.sct2_concept_snapshot_global where active=1 and id= :idconcept ") ;
$sql1a->bindParam(':idconcept', $filtro, PDO::PARAM_STR);
$sql1a->execute()  ;
$result = $sql1a->rowCount();	

while ($qraw = $sql1a->fetch(PDO::FETCH_ASSOC)) :  
	$var1 = $qraw['id'] ;
	if ($qraw['active']== 1) { $var2 = "activo" ;} else { $var2 = "desactivado" ;} ;	
	$var3 = $qraw['effectiveTime'] ;
	if ($qraw['definitionStatusId']== 900000000000073002) { $var4 = "definido" ;} else { $var4 = "primitivo" ;} ;	
	
	// ver en IE
	$sql3b = $dbConn->prepare("select term, conceptId  from snomedct_gex.sct2_description_snapshot_en_int f where active = 1 and f.conceptId = :idconcept and f.typeid = 900000000000003001 ") ;
	$sql3b->bindParam(":idconcept", $filtro, PDO::PARAM_STR);	
	$sql3b->execute();	
	while ($qrow = $sql3b->fetch(PDO::FETCH_ASSOC)) : 	
		$line = 1 ; 	
		$var6 = $qrow['term'] ;	
		$sql2b = $dbConn->prepare("select term, conceptId  from snomedct_gex.sct2_description_spanishextensionsnapshot_es_int f where active = 1 and f.conceptId = :idconcept and f.typeid = 900000000000003001 ") ;
		$sql2b->bindParam(":idconcept", $filtro, PDO::PARAM_STR);	
		$sql2b->execute() or die ("Error: " . mysqli_error($dbConn)) ;
		while ($qriw = $sql2b->fetch(PDO::FETCH_ASSOC)) :  
			$var5 = $qriw['term'] ;	
		endwhile ;
		echo "<table>" ;
		echo "<tr><td class=cabe>Id</td><td class=cabe><b>".$filtro."</b></td></tr>" ;
		echo "<tr><td class=cabe>FSN (es)</td><td class=cabe><b>".$var5."</b></td></tr>" ;
		echo "<tr><td class=cabe>FSN (en)</td><td class=cabein><b>".$var6."</b></td></tr>" ;
		echo "<tr><td><b>Estado</b></td><td>".$var2."</td></tr>" ;
		echo "<tr><td><b>EffectiveTime</b></td><td>".$var3."</td></tr>" ;
		echo "<tr><td><b>DefinitionStatus</b></td><td>".$var4."</td></tr>" ;
		echo "</table><br>" ;				
	endwhile ;

	echo "<br>" ;
	echo "<form action='MLT_snomed.php' method='POST' target='_completo' > " ;  
	echo "<input type='hidden' name='consulta' value=".$filtro.">";
	echo "<input type='submit' value='Consultar informe completo'>" ;
	echo "</form><br>" ;
	
	
	// ver en EE
	if ($line != 1) {
		$sql3b = $dbConn->prepare("select term, conceptId  from snomedct_gex.sct2_description_spainextensionsnapshot_es_es f where active = 1 and f.conceptId = :idconcept and f.languageCode = 'es-ES' and f.typeid = 900000000000003001 " ) ;
		$sql3b->bindParam(":idconcept", $filtro, PDO::PARAM_STR);	
		$sql3b->execute();		
		while ($qrow = $sql3b->fetch(PDO::FETCH_ASSOC)) : 
			$line = 1 ; 
			$var6 = $qrow['term'] ;	
			$var5 = '' ;	
			$sql2b = $dbConn->prepare("select term, conceptId  from snomedct_gex.sct2_description_spainextensionsnapshot_es_es f where active = 1 and f.conceptId = :idconcept and f.languageCode = 'en' and f.typeid = 900000000000003001 " ) ;
			$sql2b->bindParam(":idconcept", $filtro, PDO::PARAM_STR);	
			$sql2b->execute();
			while ($qriw = $sql2b->fetch(PDO::FETCH_ASSOC)) : 
				$var5 = $qriw['term'] ;	
			endwhile ;
			echo "<table>" ;
			echo "<tr><td class=cabe>Id</td><td class=cabe><b>".$filtro."</b></td></tr>" ;
			echo "<tr><td class=cabe>FSN (es)</td><td class=cabe><b>".$var6."</b></td></tr>" ;
			echo "<tr><td class=cabe>FSN (en)</td><td class=cabein><b>".$var5."</b></td></tr>" ;
			echo "<tr><td><b>Estado</b></td><td>".$var2."</td></tr>" ;
			echo "<tr><td><b>EffectiveTime</b></td><td>".$var3."</td></tr>" ;
			echo "<tr><td><b>DefinitionStatus</b></td><td>".$var4."</td></tr>" ;
			echo "</table><br>" ;					
		endwhile ;
	}
	
	// ver en EE Medicamento
	if ($line != 1) {
		$sql3b = $dbConn->prepare("select term, conceptId  from snomedct_drug.sct2_description_spaindrugsnapshot_es_es f where active = 1 and f.conceptId = :idconcept and f.languageCode = 'es-ES' and f.typeid = 900000000000003001 ") ;
		$sql3b->bindParam(":idconcept", $filtro, PDO::PARAM_STR);	
		$sql3b->execute();	
		while ($qrow = $sql3b->fetch(PDO::FETCH_ASSOC)) : 
			$line = 1 ; 
			$var6 = $qrow['term'] ;	
			$var5 = '' ;
			$sql2b = $dbConn->prepare("select term, conceptId  from snomedct_drug.sct2_description_spaindrugsnapshot_es_es f where active = 1 and f.conceptId = :idconcept and f.languageCode = 'en' and f.typeid = 900000000000003001 ") ;
			$sql2b->bindParam(":idconcept", $filtro, PDO::PARAM_STR);	
			$sql2b->execute();	
			while ($qriw = mysqli_fetch_array($res2b)) :
				$var5 = $qriw['term'] ;	
			endwhile ;
			echo "<table>" ;
			echo "<tr><td class=cabe>Id</td><td class=cabe><b>".$filtro."</b></td></tr>" ;
			echo "<tr><td class=cabe>FSN (es)</td><td class=cabe><b>".$var6."</b></td></tr>" ;
			echo "<tr><td class=cabe>FSN (en)</td><td class=cabein><b>".$var5."</b></td></tr>" ;
			echo "<tr><td><b>Estado</b></td><td>".$var2."</td></tr>" ;
			echo "<tr><td><b>EffectiveTime</b></td><td>".$var3."</td></tr>" ;
			echo "<tr><td><b>DefinitionStatus</b></td><td>".$var4."</td></tr>" ;
			echo "</table><br>" ;				
		
		endwhile ;
	}	
	
	
endwhile ; 


// Ficha Técnica
echo "<details close><summary><b>Ficha Técnica</b></summary>" ;
echo "<table>" ;
echo "<tr><td>Versión:</td><td>2.0</td></tr>" ;
echo "<tr><td>Fuente:</td><td>CNR - Centro Nacional de Referencia de SNOMED CT - España </td></tr>" ;
echo "<tr><td>Contacto:</td><td><a href='mailto:semanticaSNS@mscbs.es'>semanticaSNS@mscbs.es</a></td></tr>" ;
echo "<tr><td>Sistema:</td><td>strSNS - Servidor Terminológico de Referencia para el Sistema Nacional de Salud</td></tr>" ;
echo "<tr><td>Derechos:</td><td>Copyright SNOMED International</td></tr>" ;
echo "<tr><td>Vista:</td><td>Instantánea, muestra componentes activos e inactivos</td></tr>" ;
echo "<tr><td>Fecha/hora:</td><td>".$dia."/".$mes."/".$hoy['year']." hora: ".$hora.":".$minuto."</td></tr>" ;
echo "</table><br>" ;
echo "</details>" ;

// Comentarios
echo "<form>";
echo "<details close><summary><b>Comentarios</b></summary>" ;
echo "<i>Espacio para incluir sus notas (no quedarán grabadas pero pueden ser impresas o convertir la página a formato PDF y remitir por correo electrónico).</i><br>" ;
echo "<div id='0001' class='divtext' contentEditable='true'></div> <br/>";
echo "</form>";
echo "</details><br>" ;


// Descriptions  
$sql2 = $dbConn->prepare("SELECT 1 as ord, a.*, 'International Edition' as edición, b.acceptabilityId from snomedct_gex.sct2_description_snapshot_en_int a, der2_crefset_languagesnapshot_en_int b where conceptId = :idconcept AND a.id = b.referencedComponentId 
		UNION SELECT 2 as ord, a.*, 'Spanish Edition' as edición, b.acceptabilityId from snomedct_gex.sct2_description_spanishextensionsnapshot_es_int a, der2_crefset_languagespanishextensionsnapshot_es_int b where conceptId = :idconcept AND a.id = b.referencedComponentId
		UNION SELECT 3 as ord, a.*, 'Extensión España' as edición, b.acceptabilityId from snomedct_gex.sct2_description_spainextensionsnapshot_es_es a, der2_crefset_languagespainextensionsnapshot_es_es b where conceptId = :idconcept and languageCode = 'es-ES' AND a.id = b.referencedComponentId 
		UNION SELECT 4 as ord, a.*, 'Extensión España_en' as edición, b.acceptabilityId from snomedct_gex.sct2_description_spainextensionsnapshot_es_es a, der2_crefset_languagespainextensionsnapshot_en_us b where conceptId = :idconcept and languageCode = 'en' AND a.id = b.referencedComponentId
		UNION SELECT 5 as ord, a.*, 'Extensión España Medicamentos' as edición, b.acceptabilityId from snomedct_drug.sct2_description_spaindrugsnapshot_es_es a, snomedct_drug.der2_crefset_languagespaindrugsnapshot_es_es b where conceptId = :idconcept and languageCode = 'es-ES' AND a.id = b.referencedComponentId
		UNION SELECT 6 as ord, a.*, 'Extensión España Medicamentos_en' as edición, b.acceptabilityId from snomedct_drug.sct2_description_spaindrugsnapshot_es_es a, snomedct_drug.der2_crefset_languagespaindrugsnapshot_en b where conceptId = :idconcept and languageCode = 'en' AND a.id = b.referencedComponentId 
		order by ord, acceptabilityId, active desc " ) ;
		$sql2->bindParam(":idconcept", $busca, PDO::PARAM_STR);			
		$sql2->execute();	
//		while ($qrpw = $sql2->fetch(PDO::FETCH_ASSOC)) : 
			echo "<h2><details open><summary>Descripciones</summary>" ;
			echo "<table align=top>" ;
			echo "<h5><tr bgcolor=Moccasin><td>edición</td><td>active</td><td>term</td><td>typeId</td><td>acceptability</td></tr>";	
			while ($qr4w = $sql2->fetch(PDO::FETCH_ASSOC)) : 			
//				if ($qr4w['id'] > 0) {				
					$nopro = str_ireplace("-","",$qr4w['effectiveTime']) ;		
					if ($qr4w['typeId']== 900000000000013009) { $piq = "SYN" ;} else { $piq = "FSN" ;} 
					if ($qr4w['acceptabilityId']== 900000000000548007) { $puq = "preferido" ;} else { $puq = "aceptable" ;} 		
					if ($qr4w['languageCode'] == 'en')
						{ echo "<tr><td>".$qr4w['edición']."</td><td>".$qr4w['active']."</td><td class=descrip>".$qr4w['term']."</td><td>".$piq."</td><td>".$puq."</td></tr>" ; }
						else
						{ if ($qr4w['languageCode'] == 'es-ES') { echo "<tr><td>".$qr4w['edición']."</td><td>".$qr4w['active']. "</td><td class=descrip-es>".$qr4w['term']."</td><td>".$piq."</td><td>".$puq."</td></tr>" ; }
							else { echo "<tr><td>".$qr4w['edición']."</td><td>".$qr4w['active']. "</td><td>".$qr4w['term']."</td><td>".$piq."</td><td>".$puq."</td></tr>" ; }
						}
//					}
			endwhile ; 					
//		endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Textdefinition 

$sql55x = $dbConn->prepare("select *, 'Extensión España' as edición from snomedct_gex.sct2_textdefinition_spainextensionsnapshot_es_es where languageCode = 'es-ES'  and conceptId = :idconcept union select *, 'Extensión España_en' as edición from snomedct_gex.sct2_textdefinition_spainextensionsnapshot_es_es where languageCode = 'en' and conceptId = :idconcept union select *, 'Extensión España Medicamentos' as edición from snomedct_drug.sct2_textdefinition_spaindrugsnapshot_es where conceptId = :idconcept union select *, 'Spanish Edition' as edición from snomedct_gex.sct2_textdefinition_spanishextensionsnapshot_en_int where conceptId = :idconcept union select *, 'International Edition' as edición from snomedct_gex.sct2_textdefinition_snapshot_en_int where conceptId = :idconcept  " );
$sql55x->bindParam(":idconcept", $busca, PDO::PARAM_STR);		
$sql55x->execute();	
$lona = 0 ;	$hay = 0 ;
while ($qr6w = $sql55x->fetch(PDO::FETCH_ASSOC)) : 
	if ($qr6w['id'] > 0) {	
		$hay = 1 ;			
		if ($lona == 0) {
			echo "<h2><details open><summary>Definiciones (Text Definitions)</summary>" ;
			echo "<table align=top>" ;
			echo "<h5><tr bgcolor=Moccasin><td>edición</td><td>active</td><td>term</td></tr>";	
		}
		$nopro = str_ireplace("-","",$qr6w['effectiveTime']) ;	
		if ($qr6w['languageCode'] == 'en')
			{ echo "<tr><td>".$qr6w['edición']."</td><td>".$qr6w['active']. "</td><td  class=descrip>".$qr6w['term']."</td></tr>" ; }
			else
			{ if ($qr6w['languageCode'] == 'es-ES') { echo "<tr><td>".$qr6w['edición']."</td><td>".$qr6w['active']. "</td><td  class=descrip-es>".$qr6w['term']."</td></tr>" ; }
				else { echo "<tr><td>".$qr6w['edición']."</td><td>".$qr6w['active']. "</td><td>".$qr6w['term']."</td></tr>" ; }
			}		
		$lona ++ ;
	}	
endwhile ; 
if ($hay == 1) {
	echo "</h5></table>" ;
	echo "</details></h2>" ;	
}


// Relationships 
$sql33 = $dbConn->prepare("select e.*,'Extensión España' as edición, b.term as ts, c.term as td from snomedct_gex.sct2_relationship_spainextensionsnapshot_es e, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where sourceId = :idconcept and e.typeId = b.conceptId and b.typeId = 900000000000003001 and e.destinationId = c.conceptId and c.typeId = 900000000000003001 AND b.active = 1 AND c.active = 1
	UNION select e.*,'Extensión España Medicamentos' as edición, b.term as ts, c.term as td from snomedct_drug.sct2_relationship_spaindrugsnapshot_es e, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where sourceId = :idconcept and e.typeId = b.conceptId and b.typeId = 900000000000003001 and e.destinationId = c.conceptId and c.typeId = 900000000000003001 AND b.active = 1 AND c.active = 1
	UNION select d.*,'Spanish Edition' as edición, b.term as ts, c.term as td from snomedct_gex.sct2_relationship_spanishextensionsnapshot_int d, sct2_description_spanishextensionsnapshot_es_int b, sct2_description_spanishextensionsnapshot_es_int c where sourceId = :idconcept and d.typeId = b.conceptId and b.typeId = 900000000000003001 and d.destinationId = c.conceptId and c.typeId = 900000000000003001 AND b.active = 1 AND c.active = 1
	UNION select a.*,'International Edition' as edición, b.term as ts, c.term as td from snomedct_gex.sct2_relationship_snapshot_int a, sct2_description_spanishextensionsnapshot_es_int b, sct2_description_spanishextensionsnapshot_es_int c where sourceId = :idconcept and a.typeId = b.conceptId and b.typeId = 900000000000003001 and a.destinationId = c.conceptId and c.typeId = 900000000000003001 AND b.active = 1 AND c.active = 1
	order by edición, id, relationshipGroup, effectiveTime " );
$sql33->bindParam(":idconcept", $busca, PDO::PARAM_STR);			
$sql33->execute();		
echo "<h2><details open><summary>Relaciones (inferidas)</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=Moccasin><td>edición</td><td>active</td><td>term_typeid</td><td>destinationId</td><td>term_destinationId</td><td>relationshipGroup</td></tr>";
while ($qr0w = $sql33->fetch(PDO::FETCH_ASSOC)) : 	
	if ($qr0w['id'] > 0) {				
		$nopro = str_ireplace("-","",$qr0w['effectiveTime']) ;		
		echo "<tr><td>".$qr0w['edición']."</td><td>".$qr0w['active']. "</td><td>".$qr0w['ts']."</td><td>".$qr0w['destinationId']."</td><td>".$qr0w['td']."</td><td>".$qr0w['relationshipGroup']."</td></tr>" ;
	}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Refsets EE
$sql9 = $dbConn->prepare("select * from snomedct_gex.vistarefsets_snapshot  where concepto = :idconcept order by refset " );	
$sql9->bindParam(":idconcept", $busca, PDO::PARAM_STR);			
$sql9->execute();	
echo "<h2><details open><summary>Conjuntos de Referencia (Refsets)</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=Moccasin><td>edición</td><td>refset</td><td>active</td><td>term</td></tr>";
while ($qruw = $sql9->fetch(PDO::FETCH_ASSOC)) :
	if ($qruw['concepto'] > 0) {				
		$nopro = str_ireplace("-","",$qruw['effectiveTime']) ;		
		echo "<tr><td>Extensión España</td><td>".$qruw['Refset']."</td><td>".$qruw['activo']. "</td><td>".$qruw['descripción']."</td></tr>" ;
	}		
endwhile ; 

$sqla9 = $dbConn->prepare("select 'VTM          ' as Refset, referencedComponentId, effectiveTime, active, moduleId, term from snomedct_drug.der2_crefset_vtmspaindrugsnapshot_es where referencedComponentId = :idconcept 
	union select 'VMP  ' as refset, referencedComponentId, effectiveTime, active, moduleId, term  from snomedct_drug.der2_crefset_vmpspaindrugsnapshot_es where referencedComponentId = :idconcept 
	union select 'VMPP' as refset, referencedComponentId, effectiveTime, active, moduleId, term  from snomedct_drug.der2_crefset_vmppspaindrugsnapshot_es where referencedComponentId = :idconcept order by refset " );
$sqla9->bindParam(":idconcept", $busca, PDO::PARAM_STR);			
$sqla9->execute();	
while ($qra8w = $sqla9->fetch(PDO::FETCH_ASSOC)) :
	if ($qra8w['referencedComponentId'] > 0) {				
		$nopro = str_ireplace("-","",$qra8w['effectiveTime']) ;		
		echo "<tr><td>Extensión España Medicamentos</td><td>".$qra8w['Refset']."</td><td>".$qra8w['active']. "</td><td>".$qra8w['term']."</td></tr>" ;
	}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;	


// echo "Más información: <a href=https://webs.somsns.es/cnr/ArbolMLT_soloactivo.php?T1=".$busca." target='_blank'>MLT - Multilingual Tree completo </a><br>" ;
echo "<br>Fin del Informe. <br>" ;

?>

</html>
