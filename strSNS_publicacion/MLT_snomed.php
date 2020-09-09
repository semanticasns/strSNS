<?php session_start(); $_SESSION['path_include'] = "Access"; ?>
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
.descrip_es {color:rgb(1,0,255) ; font-family:Calibri; text-align:left; font-weight: normal;   } 
body {font-family:Calibri; font-size:11pt;}
div { display: block;}
h5 { font-size:11pt; font-weight: bold;} ;
strong { font-weight: nobold; }
.divtext {
overflow:auto;
background-color:white;
border:solid gray 1px;
padding:5px;
width:60em;
min-height:1.5em;
color:navy;
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
$filtro = rtrim($busca) ;

echo "<h1><b>CNR SNOMED CT. Concept Report (Snapshot).</b></h1>";
echo "<table align=top>" ;

$sql1b =$dbConn->prepare("select 'international_edition' as edition, term, conceptId from sct2_description_snapshot_en_int d where active = 1 and d.conceptId =:idconcept and d.typeid = 900000000000003001 UNION select 'extension_españa' as edition, term,conceptId from sct2_description_spainextensionsnapshot_es_es e where active = 1 and e.conceptId =:idconcept and e.typeid = 900000000000003001 AND languageCode != 'en' UNION select 'extension_españa_en' as edition, term,conceptId from sct2_description_spainextensionsnapshot_es_es e where active = 1 and e.conceptId =:idconcept and e.typeid = 900000000000003001 AND languageCode = 'en' UNION select 'extension_medicamentos' as edition, term,conceptId from snomedct_drug.sct2_description_spaindrugsnapshot_es_es e where active = 1 and e.conceptId =:idconcept and e.typeid = 900000000000003001 UNION select 'spanish_edition' as edition, term,conceptId from sct2_description_spanishextensionsnapshot_es_int f where active = 1 and f.conceptId =:idconcept and f.typeid = 900000000000003001 " ) ;
$sql1b->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql1b->execute()  ;

$line = 0 ;
echo "<table><tr bgcolor=whitesmoke><td><b>Términos FSN activos</td><td><b>Identificador de concepto: ".$busca."</b></td></tr>" ;
while ($qrow = $sql1b->fetch(PDO::FETCH_ASSOC)) : 				
		if ($line == 0 ) {echo "<tr><td>".$qrow['edition']."</td><td class=cabe><b>".$qrow['term']."</b></td>
		</tr>" ;	}		
			else {echo "<tr><td>".$qrow['edition']."</td><td class=cabe><b>".$qrow['term']."</b></td><td></td></tr>" ;}
		$line ++ ;
endwhile ; 
echo "</table><br>" ;

// Eliminado. Sustituir por POST
// echo "<tr></tr><tr><td><b>".$qrow['term']."</b><a href=/ArbolMLT_activos.php?T1=".$busca.">Ver MLT Concept Report Full</a></td>
// <td><a href=https://browser.ihtsdotools.org/?perspective=full&conceptId1=".$busca."&edition=es-edition&release=v20181031&server=https://prod-browser-exten.ihtsdotools.org/api/snomed&langRefset=450828004 target='_blank'>Ver en Browser SNOMED CT</a></td></tr>" ;
// echo "</table><br>" ;

echo "<form>";
echo "Comentarios: ";
echo "<div id='0001' class='divtext' contentEditable='true'></div> <br/>";
echo "</form>";


// Concepts 
$sql1 =$dbConn->prepare("select * from sct2_concept_spainextensionsnapshot_es where id = :idconcept UNION select * from sct2_concept_snapshot_int where id = :idconcept  UNION select * from sct2_concept_spanishextensionsnapshot_int where id = :idconcept  " ) ;
$sql1->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql1->execute() ;

/* Tabla de concepto */
$sqlff =$dbConn->prepare("select *,'extension_españa' as edition from sct2_concept_spainextensionsnapshot_es where id = :idconcept UNION select *,'extension_medicamentos' as edition from snomedct_drug.sct2_concept_spaindrugsnapshot_es where id = :idconcept UNION select *,'spanish_edition' as edition from sct2_concept_spanishextensionsnapshot_int where id = :idconcept UNION select *,'international_edition' as edition from sct2_concept_snapshot_int where id = :idconcept  order by edition, effectiveTime ") ;
$sqlff->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sqlff->execute()  ;
echo "<h2><details open><summary>Concept</summary>" ;
echo "<table align=top>" ;
echo "<tr bgcolor=whitesmoke><td>edition</td><td>conceptId</td><td>effectiveTime</td><td>active</td><td>moduleid</td><td>definitionStatusId</td></tr>";
while ($qr3w = $sqlff->fetch(PDO::FETCH_ASSOC)) : 		
	if ($qr3w['id'] > 0) {				
		$nopro = str_ireplace("-","",$qr3w['effectiveTime']) ;	
		if ($qr3w['definitionStatusId']== 900000000000073002) { $piq = "defined" ;} else { $piq = "primitive" ;} ;		
		echo "<tr><td>".$qr3w['edition']."</td><td>".$qr3w['id']."</td><td>".$nopro."</td><td>".$qr3w['active']. "</td><td>".$qr3w['moduleId']."</td><td>".$piq."</td></tr>" ;
		}		
endwhile ; 
echo "</table>" ;
echo "</details></h2>" ;


// Descriptions  
$sql2 = $dbConn->prepare("select *, 'extension_españa' as edition from sct2_description_spainextensionsnapshot_es_es where conceptId = :idconcept and languageCode = 'es-ES' union select *, 'extension_españa_en' as edition from sct2_description_spainextensionsnapshot_es_es where conceptId = :idconcept and languageCode = 'en' union select *, 'extension_medicamentos' as edition from snomedct_drug.sct2_description_spaindrugsnapshot_es_es where conceptId = :idconcept and languageCode = 'es-ES' union select *, 'extension_medicamentos_en' as edition from snomedct_drug.sct2_description_spaindrugsnapshot_es_es where conceptId = :idconcept and languageCode = 'en' union select *, 'spanish_edition' as edition from sct2_description_spanishextensionsnapshot_es_int where conceptId = :idconcept union select *, 'international_edition' as edition from sct2_description_snapshot_en_int where conceptId = :idconcept order by edition, typeId, id, effectiveTime " ) ;
$sql2->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql2->execute() ;
echo "<h2><details open><summary>Descriptions</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td><b>edition</b></td><td><b>id</b></td><td><b>effectiveTime</b></td><td>active</td><td>term</td><td>languageCode</td><td>typeId</td></tr>";
while ($qr4w = $sql2->fetch(PDO::FETCH_ASSOC)) : 	
	if ($qr4w['id'] > 0) {				
		$nopro = str_ireplace("-","",$qr4w['effectiveTime']) ;		
		if ($qr4w['typeId']== 900000000000013009) { $piq = "synonym" ;} else { $piq = "FSN" ;} ;
		echo "<tr><td>".$qr4w['edition']."</td><td>".$qr4w['id']."</td><td>".$nopro."</td><td>".$qr4w['active']. "</td><td>".$qr4w['term']."</td><td>".$qr4w['languageCode']."</td><td>".$piq."</td></tr>" ;
		}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Language Refsets
$sql55x = $dbConn->prepare("select 'extension_españa' as edition, c.effectiveTime, c.active, c.moduleId, c.refsetId, c.referencedcomponentId, c.acceptabilityId, b.term from sct2_description_spainextensionsnapshot_es_es b, der2_crefset_languagespainextensionsnapshot_es_es c, sct2_concept_snapshot_global a where a.id = :idconcept and a.id = b.conceptId and c.referencedComponentId = b.id union select 'extension_españa_en' as edition, c.effectiveTime, c.active, c.moduleId, c.refsetId, c.referencedcomponentId, c.acceptabilityId, b.term from sct2_description_spainextensionsnapshot_es_es b, der2_crefset_languagespainextensionsnapshot_en_us c, sct2_concept_snapshot_global a where a.id = :idconcept and a.id = b.conceptId and c.referencedComponentId = b.id union select 'extension_medicamentos' as edition, c.effectiveTime, c.active, c.moduleId, c.refsetId, c.referencedcomponentId, c.acceptabilityId, b.term from snomedct_drug.sct2_description_spaindrugsnapshot_es_es b, snomedct_drug.der2_crefset_languagespaindrugsnapshot_es_es c, sct2_concept_snapshot_global a where a.id = :idconcept and a.id = b.conceptId and c.referencedComponentId = b.id  union select 'extension_medicamentos' as edition, c.effectiveTime, c.active, c.moduleId, c.refsetId, c.referencedcomponentId, c.acceptabilityId, b.term from snomedct_drug.sct2_description_spaindrugsnapshot_es_es b, snomedct_drug.der2_crefset_languagespaindrugsnapshot_en c, sct2_concept_snapshot_global a where a.id = :idconcept and a.id = b.conceptId and c.referencedComponentId = b.id  union select 'spanish_edition' as edition, c.effectivetime, c.active, c.moduleid, c.refsetid, c.referencedcomponentid, c.acceptabilityId, b.term from sct2_description_spanishextensionsnapshot_es_int b, der2_crefset_languagespanishextensionsnapshot_es_int c, sct2_concept_snapshot_global a where a.id = :idconcept and a.id = b.conceptId and c.referencedComponentId = b.id  and c.active = 1 union select 'international_edition' as edition, c.effectivetime, c.active, c.moduleid, c.refsetid, c.referencedcomponentid, c.acceptabilityId, b.term from sct2_description_snapshot_en_int b, der2_crefset_languagesnapshot_en_int c, sct2_concept_snapshot_int a where a.id = :idconcept and a.id = b.conceptId and c.referencedComponentId = b.id and c.refsetId = 900000000000509007  and c.active = 1 order by edition, referencedcomponentId, effectiveTime " );
$sql55x->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql55x->execute() ;
echo "<h2><details close><summary>Language Refset</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td>edition</td><td>effectiveTime</td><td>active</td><td>moduleid</td><td>refsetid</td><td>referencedcomponentId</td><td>term</td><td>acceptabilityId</td></tr>";
while ($qr5w = $sql55x->fetch(PDO::FETCH_ASSOC)) : 
	if ($qr5w['referencedcomponentId'] > 0) {				
		$nopro = str_ireplace("-","",$qr5w['effectiveTime']) ;		
		if ($qr5w['acceptabilityId']== 900000000000548007) { $piq = "preferido" ;} else { $piq = "aceptable" ;} ;
		echo "<tr><td>".$qr5w['edition']."</td><td>".$nopro."</td><td>".$qr5w['active']. "</td><td>".$qr5w['moduleId']. "</td><td>".$qr5w['refsetId']. "</td><td>".$qr5w['referencedcomponentId']. "</td><td>".$qr5w['term']."</td><td>".$piq."</td></tr>" ;
		}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Textdefinition 
$sql55x = $dbConn->prepare("select *, 'extension_españa' as edition from sct2_textdefinition_spainextensionsnapshot_es_es where conceptId = :idconcept union select *, 'extension_medicamentos' as edition from snomedct_drug.sct2_textdefinition_spaindrugsnapshot_es where conceptId = :idconcept union select *, 'spanish_edition' as edition from sct2_textdefinition_spanishextensionsnapshot_en_int where conceptId = :idconcept union select *, 'international_edition' as edition from sct2_textdefinition_snapshot_en_int where conceptId = :idconcept order by typeId, conceptId, effectiveTime " )  ;
$sql55x->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql55x->execute() ;
echo "<h2><details close><summary>Text Definitions</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td>edition</td><td>id</td><td>effectiveTime</td><td>active</td><td>conceptId</td><td>term</td><td>languageCode</td><td>typeId</td></tr>";
while ($qr6w = $sql55x->fetch(PDO::FETCH_ASSOC)) : 	
	if ($qr6w['id'] > 0) {				
		$nopro = str_ireplace("-","",$qr6w['effectiveTime']) ;		
		echo "<tr><td>".$qr6w['edition']."</td><td>".$qr6w['id']."</td><td>".$nopro."</td><td>".$qr6w['active']. "</td><td>".$qr6w['conceptId']. "</td><td>".$qr6w['term']."</td><td>".$qr6w['languageCode']."</td><td>".$qr6w['typeId']."</td></tr>" ;
		}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Relationships stated
$sql3 = $dbConn->prepare("select e.*,'extension_españa' as edition, b.term as ts, c.term as td from sct2_statedrelationship_spainextensionsnapshot_es e, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where sourceId = :idconcept and e.typeId = b.conceptId and b.typeId = 900000000000003001 and e.destinationId = c.conceptId and c.typeId = 900000000000003001 and b.active = 1 and c.active = 1 UNION select e.*,'extension_medicamentos' as edition, b.term as ts, c.term as td from snomedct_drug.sct2_statedrelationship_spaindrugsnapshot_es e, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where sourceId = :idconcept and e.typeId = b.conceptId and b.typeId = 900000000000003001 and e.destinationId = c.conceptId and c.typeId = 900000000000003001 and b.active = 1 and c.active = 1 UNION select d.*,'spanish_edition' as edition, b.term as ts, c.term as td from sct2_statedrelationship_spanishextensionsnapshot_int d, sct2_description_spanishextensionsnapshot_es_int b, sct2_description_spanishextensionsnapshot_es_int c where sourceId = :idconcept and d.typeId = b.conceptId and b.typeId = 900000000000003001 and d.destinationId = c.conceptId and c.typeId = 900000000000003001 and b.active = 1 and c.active = 1 UNION select a.*,'international_edition' as edition, b.term as ts, c.term as td from sct2_statedrelationship_snapshot_int a, sct2_description_spanishextensionsnapshot_es_int b, sct2_description_spanishextensionsnapshot_es_int c where sourceId = :idconcept and a.typeId = b.conceptId and b.typeId = 900000000000003001 and a.destinationId = c.conceptId and c.typeId = 900000000000003001 and b.active = 1 and c.active = 1 order by edition, id, relationshipGroup, id, effectiveTime " ) ;
$sql3->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql3->execute() ;
echo "<h2><details close><summary>Relationships (stated)</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td><b>edition</b></td><td><b>id</b></td><td><b>effectiveTime</b></td><td>active</td><td>moduleid</td><td>typeId</td><td>term_typeid</td><td>destinationId</td><td>term_destinationId</td><td>relationshipGroup</td></tr>";
while ($qr1w = $sql3->fetch(PDO::FETCH_ASSOC)) : 	
	if ($qr1w['id'] > 0) {				
		$nopro = str_ireplace("-","",$qr1w['effectiveTime']) ;		
		echo "<tr><td>".$qr1w['edition']."</td><td>".$qr1w['id']."</td><td>".$nopro."</td><td>".$qr1w['active']. "</td><td>".$qr1w['moduleId']."</td><td>".$qr1w['typeId']."</td><td>".$qr1w['ts']."</td><td>".$qr1w['destinationId']."</td><td>".$qr1w['td']."</td><td>".$qr1w['relationshipGroup']."</td></tr>" ;
		}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Relationships stated OWL
$sql3 = $dbConn->prepare("select 'spanish_edition' as edition, d.effectiveTime, d.active, d.referencedComponentId, c.term as td , d.owlExpression from sct2_srefset_owlexpressionspanishextensionsnapshot_int d, sct2_description_spanishextensionsnapshot_es_int c where referencedcomponentid = :idconcept and d.referencedcomponentid = c.conceptId and c.typeId = 900000000000003001 and c.active = 1 UNION select 'international_edition' as edition, a.effectiveTime, a.active, a.referencedComponentId, c.term as td, a.owlExpression from sct2_srefset_owlexpressionsnapshot_int a, sct2_description_spanishextensionsnapshot_es_int c where referencedcomponentid = :idconcept and a.referencedcomponentid = c.conceptId and c.typeId = 900000000000003001 and c.active = 1 order by edition, referencedComponentId, effectiveTime " );
$sql3->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql3->execute() ;
echo "<h2><details close><summary>OWL stated</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td>edition</td><td>conceptId</td><td>effectiveTime</td><td>active</td><td>term</td><td>OWL Expression</td></tr>";
while ($qr1w = $sql3->fetch(PDO::FETCH_ASSOC)) : 	
	if ($qr1w['referencedComponentId'] > 0) {	$nopro = str_ireplace("-","",$qr1w['effectiveTime']) ;		
	echo "<tr><td>".$qr1w['edition']."</td><td>".$qr1w['referencedComponentId']."</td><td>".$nopro."</td><td>".$qr1w['active']. "</td><td>".$qr1w['td']."</td><td>".$qr1w['owlExpression']."</td></tr>" ;
	}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Relationships 
$sql33 = $dbConn->prepare("select e.*,'extension_españa' as edition, b.term as ts, c.term as td from sct2_relationship_spainextensionsnapshot_es e, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where sourceId = :idconcept and e.typeId = b.conceptId and b.typeId = 900000000000003001 and e.destinationId = c.conceptId and c.typeId = 900000000000003001 and b.active = 1 and c.active = 1 UNION select e.*,'extension_medicamentos' as edition, b.term as ts, c.term as td from snomedct_drug.sct2_relationship_spaindrugsnapshot_es e, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where sourceId = :idconcept and e.typeId = b.conceptId and b.typeId = 900000000000003001 and b.active = 1 and e.destinationId = c.conceptId and c.typeId = 900000000000003001 and b.active = 1 UNION select d.*,'spanish_edition' as edition, b.term as ts, c.term as td from sct2_relationship_spanishextensionsnapshot_int d, sct2_description_spanishextensionsnapshot_es_int b, sct2_description_spanishextensionsnapshot_es_int c where sourceId = :idconcept and d.typeId = b.conceptId and b.typeId = 900000000000003001 and b.active = 1 and d.destinationId = c.conceptId and c.typeId = 900000000000003001 and c.active = 1 UNION select a.*,'international_edition' as edition, b.term as ts, c.term as td from sct2_relationship_snapshot_int a, sct2_description_spanishextensionsnapshot_es_int b, sct2_description_spanishextensionsnapshot_es_int c where sourceId = :idconcept and a.typeId = b.conceptId and b.typeId = 900000000000003001 and b.active = 1 and a.destinationId = c.conceptId and c.typeId = 900000000000003001 and c.active = 1 order by edition, id, relationshipGroup, effectiveTime " );
$sql33->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql33->execute() ;
echo "<h2><details close><summary>Relationships (inferred)</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td><b>edition</b></td><td><b>id</b></td><td><b>effectiveTime</b></td><td>active</td><td>moduleid</td><td>typeId</td><td>term_typeid</td><td>destinationId</td><td>term_destinationId</td><td>relationshipGroup</td></tr>";
while ($qr0w = $sql33->fetch(PDO::FETCH_ASSOC)) : 	
	if ($qr0w['id'] > 0) {				
		$nopro = str_ireplace("-","",$qr0w['effectiveTime']) ;		
		echo "<tr><td>".$qr0w['edition']."</td><td>".$qr0w['id']."</td><td>".$nopro."</td><td>".$qr0w['active']. "</td><td>".$qr0w['moduleId']."</td><td>".$qr0w['typeId']."</td><td>".$qr0w['ts']."</td><td>".$qr0w['destinationId']."</td><td>".$qr0w['td']."</td><td>".$qr0w['relationshipGroup']."</td></tr>" ;
		}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Refsets EE
$sql9 = $dbConn->prepare("select * from vistarefsets_snapshot  where concepto = :idconcept order by refset " );	
$sql9->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql9->execute() ;
echo "<h2><details close><summary>Refsets Extensión España</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td>refset</td><td>conceptId</td><td>effectiveTime</td><td>active</td><td>moduleid</td><td>term</td></tr>";
while ($qr8w = $sql9->fetch(PDO::FETCH_ASSOC)) : 	
	if ($qr8w['concepto'] > 0) {				
		$nopro = str_ireplace("-","",$qr8w['effectiveTime']) ;		
		echo "<tr><td>".$qr8w['Refset']."</td><td>".$qr8w['concepto']."</td><td>".$nopro."</td><td>".$qr8w['activo']. "</td><td>".$qr8w['moduleid']."</td><td>".$qr8w['descripción']."</td></tr>" ;
		}		
endwhile ; 

$sqla9 = $dbConn->prepare("select 'VTM' as Refset, referencedComponentId, effectiveTime, active, moduleId, term from snomedct_drug.der2_crefset_vtmspaindrugsnapshot_es where referencedComponentId = :idconcept union select 'VMP' as refset, referencedComponentId, effectiveTime, active, moduleId, term  from snomedct_drug.der2_crefset_vmpspaindrugsnapshot_es where referencedComponentId = :idconcept union select 'VMPP' as refset, referencedComponentId, effectiveTime, active, moduleId, term  from snomedct_drug.der2_crefset_vmppspaindrugsnapshot_es where referencedComponentId = :idconcept order by refset " );
$sqla9->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sqla9->execute() ;
while ($qra8w = $sqla9->fetch(PDO::FETCH_ASSOC)) : 
	if ($qra8w['referencedComponentId'] > 0) {				
	$nopro = str_ireplace("-","",$qra8w['effectiveTime']) ;		
	echo "<tr><td>".$qra8w['Refset']."</td><td>".$qra8w['referencedComponentId']."</td><td>".$nopro."</td><td>".$qra8w['active']. "</td><td>".$qra8w['moduleId']."</td><td>".$qra8w['term']."</td></tr>" ;
	}		
endwhile ; 

echo "</h5></table>" ;
echo "</details></h2>" ;	


// Mapeos  
echo "<h2><details close><summary>Mapping</summary>" ;
echo "<table align=top>" ;
echo "<tr bgcolor=whitesmoke><td>Recurso_mapeado</td><td>effectiveTime</td><td>active</td><td>mapGroup</td><td>mapPriority</td><td>mapRule</td><td>mapAdvice</td><td>mapTarget</td><td>mapTargetName</td><td>mapCategoryId</td><td>mapCategoryName</td></tr>";
				
// Map CIE-10-MC 
$sql2 = $dbConn->prepare("select * from tls_icd10cmhumanreadablemap_us where referencedComponentId = :idconcept and active = 1 order by mapGroup, mapPriority" );
$sql2->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql2->execute() ;
while ($qr4w = $sql2->fetch(PDO::FETCH_ASSOC)) : 	
	$spanish = $qr4w['mapTarget'] ;
	echo "<tr><td>CIE-10-MC</td><td>".$qr4w['effectiveTime']."</td><td>".$qr4w['active']."</td><td>".$qr4w['mapGroup']."</td><td>".$qr4w['mapPriority']."</td><td>".$qr4w['mapRule']."</td><td>".$qr4w['mapAdvice']."</td><td>".$qr4w['mapTarget']."</td><td>".$qr4w['mapTargetName']."</td><td>".$qr4w['mapCategoryId']."</td><td>".$qr4w['mapCategoryName']."</td></tr>" ;	

	$sql5 = $dbConn->prepare("select * from recursos.mtc_local_cie10es_2018 where id = :idspanish " );
	$sql5->bindParam(":idspanish", $spanish, PDO::PARAM_STR);
	$sql5->execute() ;	
	while ($qr7w = $sql5->fetch(PDO::FETCH_ASSOC)) : 
		echo "<tr><td>CIE-10-MC</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>".$qr7w['es_ES']."</td><td></td><td></td></tr>" ;	
	endwhile ;
endwhile ; 

// Map CIE-9-MC 
$sql3 = $dbConn->prepare("select a.*, b.descriptor_es as descri from der2_iissscrefset_complexmapsnapshot_int a, recursos.mtc_local_cie9mc_dx_2014 b where a.mapTarget = b.code and referencedComponentId = :idconcept  and a.active = 1 order by mapGroup, mapPriority " ) ;
$sql3->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql3->execute() ;
while ($qr6w = $sql3->fetch(PDO::FETCH_ASSOC)) : 		
	echo "<tr><td>CIE-9-MC</td><td></td><td></td><td>".$qr6w['mapGroup']."</td><td>".$qr6w['mapPriority']."</td><td>".$qr6w['mapRule']."</td><td>".$qr6w['mapAdvice']."</td><td>".$qr6w['mapTarget']."</td><td>".$qr6w['descri']."</td><td></td><td></td></tr>" ;	
endwhile ; 

// Map Medicamentos
$sqlb9 = $dbConn->prepare("select 'Mapeo CN a VMPP' as Refset, mapTarget, effectiveTime, active, mapGroup, mapRule, mapPriority, mapAdvice, mapCategoryId, moduleId, referencedComponentId, referencedComponentTerm from snomedct_drug.der2_crefset_vmppcnspaindrugmapsnapshot_es where mapTarget = :idconcept" );
$sqlb9->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sqlb9->execute() ;
while ($qrb8w = $sqlb9->fetch(PDO::FETCH_ASSOC)) : 				
	$nopro = str_ireplace("-","",$qrb8w['effectiveTime']) ;		
	echo "<tr><td>".$qrb8w['Refset']."</td><td>".$nopro."</td><td>".$qrb8w['active']. "</td><td>".$qrb8w['mapGroup']."</td><td>".$qrb8w['mapPriority']."</td><td>".$qrb8w['mapRule']."</td><td>".$qrb8w['mapAdvice']."</td><td>".$qrb8w['referencedComponentId']."</td><td>".$qrb8w['referencedComponentTerm']."</td><td>".$qrb8w['mapCategoryId']."</td><td></td></tr>" ;
endwhile ; 

// Map Problemas de Salud CIAP-2
$sqlb9 = $dbConn->prepare("select 'CIAP-2' as Refset, mapTarget, effectiveTime, active, mapTargetDescriptor, moduleId, referencedComponentId  from der2_crefset_problemassaludapmapspainextensionsnapshot_es where referencedComponentId = :idconcept " );
$sqlb9->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sqlb9->execute() ;
while ($qrb8w = $sqlb9->fetch(PDO::FETCH_ASSOC)) : 			
	$nopro = str_ireplace("-","",$qrb8w['effectiveTime']) ;		
	echo "<tr><td>".$qrb8w['Refset']."</td><td>".$nopro."</td><td>".$qrb8w['active']. "</td><td></td><td></td><td></td><td></td><td>".$qrb8w['mapTarget']."</td><td>".$qrb8w['mapTargetDescriptor']."</td><td></td><td></td></tr>" ;
endwhile ;

echo "</h5></table>" ;
echo "</details></h2>" ;


// Ontologías (OSC) 
$sql10 = $dbConn->prepare("select id,'OSC' as edition, subject, subject_description, predicate, predicate_description, object, object_description from snomedct_drug.clinical_safety_ontology a where (subject = :idconcept or predicate = :idconcept or object = :idconcept) order by edition, subject " );
$sql10->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql10->execute() ;	
echo "<h2><details close><summary>Ontologías</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td>edition</td><td>subject</td><td>subject_description</td><td>predicate</td><td>predicate_description</td><td>object</td><td>object_description</td></tr>";
while ($qr10w = $sql10->fetch(PDO::FETCH_ASSOC)) : 	
	if ($qr10w['id'] > 0) {					
	echo "<tr><td>".$qr10w['edition']."</td><td>".$qr10w['subject']."</td><td>".$qr10w['subject_description']."</td><td>".$qr10w['predicate']."</td><td>".$qr10w['predicate_description']."</td><td>".$qr10w['object']."</td><td>".$qr10w['object_description']."</td></tr>" ;
	}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Attribute Value
echo "<h2><details close><summary>AttributeValue</summary>" ;
echo "<table align=top>" ;		
echo "<h5><tr bgcolor=whitesmoke><td>edition</td><td>effectiveTime</td><td>active</td><td>refsetid</td><td>term_refset</td><td>referencedComponentId</td><td>valueid</td><td>term_value</td></tr>";

$sqlww = $dbConn->prepare("select id from sct2_description_spainextensionsnapshot_es_es where conceptId = :idconcept and languageCode = 'es-ES' union select id from sct2_description_spainextensionsnapshot_es_es where conceptId = :idconcept and languageCode = 'en' union select id from snomedct_drug.sct2_description_spaindrugsnapshot_es_es where conceptId = :idconcept and languageCode = 'es-ES' union select id from snomedct_drug.sct2_description_spaindrugsnapshot_es_es where conceptId = :idconcept and languageCode = 'en' union select id from sct2_description_spanishextensionsnapshot_es_int where conceptId = :idconcept union select id from sct2_description_snapshot_en_int where conceptId = :idconcept " );
$sqlww->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sqlww->execute() ;	
while ($qrww = $sqlww->fetch(PDO::FETCH_ASSOC)) : 	
//	if ($qrww['id'] > 0) {
		$noww = $qrww['id'] ;		
		$sql9 = $dbConn->prepare("select a.*,'extension_españa' as edition, b.term as term_refset, c.term as term_value from der2_crefset_attributevaluespainextensionsnapshot_es a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where a.referencedComponentId = :idattr and a.refsetid = b.conceptId and b.typeid = 900000000000003001 and a.valueId = c.conceptId and c.typeid = 900000000000003001 UNION select a.*,'extension_medicamentos' as edition, b.term as term_refset, c.term as term_value from snomedct_drug.der2_crefset_attributevaluespaindrugsnapshot_es a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where a.referencedComponentId = :idattr and a.refsetid = b.conceptId and b.typeid = 900000000000003001 and a.valueId = c.conceptId and c.typeid = 900000000000003001 UNION select a.*,'spanish_edition' as edition, b.term as term_refset, c.term as term_value from der2_crefset_attributevaluespanishextensionsnapshot_int a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where a.referencedComponentId = :idattr and a.refsetid = b.conceptId and b.typeid = 900000000000003001 and a.valueId = c.conceptId and c.typeid = 900000000000003001 UNION select a.*,'international_edition' as edition, b.term as term_refset, c.term as term_value from der2_crefset_attributevaluesnapshot_int a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where a.refsetid = :idattr and a.referencedComponentId = b.conceptId and b.typeid = 900000000000003001 and a.refsetid = b.conceptId and b.typeid = 900000000000003001 and a.valueId = c.conceptId and c.typeid = 900000000000003001 order by edition, referencedComponentId " );
		$sql9->bindParam(":idattr", $noww, PDO::PARAM_STR);
		$sql9->execute() ;			
		while ($qr2w = $sql9->fetch(PDO::FETCH_ASSOC)) : 
//			if ($qr2w['id'] > 0) {				
				$nopro = str_ireplace("-","",$qr2w['effectiveTime']) ;		
				echo "<tr><td>".$qr2w['edition']."</td><td>".$nopro."</td><td>".$qr2w['active']. "</td><td>".$qr2w['refsetId']."</td><td>".$qr2w['term_refset']."</td><td>".$qr2w['referencedComponentId']."</td><td>".$qr2w['valueId']."</td><td>".$qr2w['term_value']."</td></tr>" ;
//				}		
		endwhile ; 
//	}		
endwhile ; 

$sqlw9 = $dbConn->prepare("select a.*,'extension_españa' as edition, b.term as term_refset, c.term as term_value from der2_crefset_attributevaluespainextensionsnapshot_es a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where a.referencedComponentId = :idconcept and a.refsetid = b.conceptId and b.typeid = 900000000000003001 and a.valueId = c.conceptId and c.typeid = 900000000000003001 
	UNION select a.*,'extension_medicamentos' as edition, b.term as term_refset, c.term as term_value from snomedct_drug.der2_crefset_attributevaluespaindrugfull_es a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where a.referencedComponentId = :idconcept and a.refsetid = b.conceptId and b.typeid = 900000000000003001 and a.valueId = c.conceptId and c.typeid = 900000000000003001 
	UNION select a.*,'spanish_edition' as edition, b.term as term_refset, c.term as term_value from der2_crefset_attributevaluespanishextensionsnapshot_int a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where a.referencedComponentId = :idconcept and a.refsetid = b.conceptId and b.typeid = 900000000000003001 and a.valueId = c.conceptId and c.typeid = 900000000000003001 
	UNION select a.*,'international_edition' as edition, b.term as term_refset, c.term as term_value from der2_crefset_attributevaluesnapshot_int a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where a.referencedComponentId = :idconcept AND a.refsetid = b.conceptId and b.typeid = 900000000000003001 and a.valueId = c.conceptId and c.typeid = 900000000000003001 order by edition, referencedComponentId " );
$sqlw9->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sqlw9->execute() ;	
while ($qr2ww = $sqlw9->fetch(PDO::FETCH_ASSOC)) : 		
		$nopro = str_ireplace("-","",$qr2ww['effectiveTime']) ;		
		echo "<tr><td>".$qr2ww['edition']."</td><td>".$nopro."</td><td>".$qr2ww['active']. "</td><td>".$qr2ww['refsetId']."</td><td>".$qr2ww['term_refset']."</td><td>".$qr2ww['referencedComponentId']."</td><td>".$qr2ww['valueId']."</td><td>".$qr2ww['term_value']."</td></tr>" ;	
endwhile ; 

echo "</h5></table>" ;
echo "</details></h2>" ;


// AssociationReference Refset
$sql09 = $dbConn->prepare("select a.*,'international_edition' as edition, b.term as rd, c.term as td from der2_crefset_associationreferencesnapshot_int a, sct2_description_spanishextensionsnapshot_es_int b, sct2_description_spanishextensionsnapshot_es_int c where referencedComponentId = :idconcept and a.referencedComponentId = b.conceptId and b.typeId = 900000000000003001 and a.targetComponentId = c.conceptId and c.typeId = 900000000000003001 union select a.*,'extension_españa' as edition, b.term as rd, c.term as td from der2_crefset_associationreferencespainextensionsnapshot_es a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c where referencedComponentId = :idconcept and a.referencedComponentId = b.conceptId and b.typeId = 900000000000003001 and a.targetComponentId = c.conceptId and c.typeId = 900000000000003001 order by edition, referencedComponentId " ) ;	
$sql09->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$sql09->execute() ;	
echo "<h2><details close><summary>AssociationReference Refset</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td>edition</td><td>effectiveTime</td><td>active</td><td>refsetid</td><td>referencedComponentId</td><td>term_referencedComponentId</td><td>targetComponentId</td><td>term_targetComponentId</td></tr>";
while ($qr7w = $sql09->fetch(PDO::FETCH_ASSOC)) : 		
	if ($qr7w['id'] > 0) {				
		$nopro = str_ireplace("-","",$qr7w['effectiveTime']) ;		
		echo "<tr><td>".$qr7w['edition']."</td><td>".$nopro."</td><td>".$qr7w['active']. "</td><td>".$qr7w['refsetId']."</td><td>".$qr7w['referencedComponentId']."</td><td>".$qr7w['rd']."</td><td>".$qr7w['targetComponentId']."</td><td>".$qr7w['td']."</td></tr>" ;
		}		
endwhile ; 
echo "</h5></table>" ;
echo "</details></h2>" ;


// Jerarquía del concepto

$cons1 = $dbConn->prepare("select subType, superType, term from sct2_transitiveclosure_snapshot_global a, sct2_description_snapshot_globalsp b where subType = :idconcept and a.superType = b.conceptId and b.active = 1 and b.typeId = 900000000000003001 order by term " );
$cons1->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$cons1->execute() ;	

$cons2 = $dbConn->prepare("select subType, superType, term from sct2_transitiveclosure_snapshot_global a, sct2_description_snapshot_globalsp b where superType = :idconcept and a.subType = b.conceptId and b.active = 1 and b.typeId = 900000000000003001 order by term " );
$cons2->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$cons2->execute() ;	


// Impresión Jerarquía
echo "<h2><details close><summary>Transitive Closure (Jerarquia de Concepto)</summary>" ;
echo "<table align=top>" ;
echo "<h5><tr bgcolor=whitesmoke><td><b>Jerarquía</b></td><td></td></tr>";
echo "<tr><td align=right>138875005</td><td>concepto de SNOMED CT (SNOMED RT+CTV3)</td></tr>";

$cons11 = $dbConn->prepare("select subType, superType, term from sct2_transitiveclosure_snapshot_global a, sct2_description_snapshot_globalsp b where subType = :idconcept and a.superType = b.conceptId and b.active = 1 and b.typeId = 900000000000003001 order by term " );
$cons11->bindParam(":idconcept", $busca, PDO::PARAM_STR);
$cons11->execute() ;	
while ($rrr = $cons11->fetch(PDO::FETCH_ASSOC)) : 	
	$c11 = $rrr['superType'] ;
	$cons24 = $dbConn->prepare("select sourceId, destinationId, term from sct2_relationship_snapshot_global a, sct2_description_snapshot_globalsp b 
		where a.sourceId = :idsuper and a.destinationId = 138875005 and a.sourceId = b.conceptId and b.active = 1 and b.typeId = 900000000000003001 " );
	$cons24->bindParam(":idsuper", $c11, PDO::PARAM_STR);
	$cons24->execute() ;		
	while ($rrx = $cons24->fetch(PDO::FETCH_ASSOC)) :	
		$c111 = $rrx['sourceId'] ;
		echo "<tr><td>".$c111."</td><td>".$rrx['term']."</td></tr>";		
	endwhile ;
endwhile ;

echo "<h5><tr bgcolor=whitesmoke><td><b>Parents</b></td><td></td></tr>";
while ($ruw = $cons1->fetch(PDO::FETCH_ASSOC)) : 	
	$c1 =  $ruw['superType'] ;
	$c2 =  $ruw['term'] ;
	if ($c1 != 138875005 and $c1 != $c111 ) {echo "<tr><td align=right>".$c1."</td><td>".$c2."</td></tr>";  }		
endwhile ; 

echo "<tr bgcolor=whitesmoke><td><b>Children</b></td><td></td></tr>";
while ($riw = $cons2->fetch(PDO::FETCH_ASSOC)) : 	
	$c1 =  $riw['subType'] ;
	$c2 =  $riw['term'] ;
	echo "<tr><td align=right>".$c1."</td><td>".$c2."</td></tr>";
endwhile ;
echo "</h5></table>" ;
echo "</details></h2>" ;

echo "<br><br>Fin del Informe. Fecha: ".$dia."/".$mes."/".$hoy['year']." hora: ".$hora.":".$minuto."<br>" ;
?>

</html>
