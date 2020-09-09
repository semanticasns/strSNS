<?php
session_start(); $_SESSION['path_include'] = "Access"; 
include $_SESSION['path_include']."/configbd_gex_msqli.php"; 
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<style> 
table { border-collapse: nocollapse; font-family:Calibri; vertical-align:top; width:95%;}
table, td, th { border: 0px solid black; padding: 1px; vertical-align:top; }
th {font-family:Calibri; font-size:11pt; text-align:left; vertical-align:top; background-color:#FFD633}
tr {font-family:Calibri; text-align:left; vertical-align:top; font-weight: normal;}
body {font-family:Calibri; font-size:11pt;}
div { display: block;}
h1 { font-family:Arial; font-size:24pt; font-weight: normal;} ;
h2 { font-family:Arial; font-size:20pt; font-weight: normal;} ;
h3 { font-family:Arial; font-size:20pt; font-weight: normal;} ;
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
$T1 = "" ; 

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

echo "<table align=top><tr><td align=top width=20%><img src='SNOMED_logo_200pc.png'></td>
	<td align=top font-size=76px><h1>SNOMED CT MRCM.</h1>
	<h1>Machine Readable Concept Model</h1><a href=MRCM.php><i>ir a vista Dominios</i></a></td>
	</tr>
	</table>";

/* Tabla de atributos */
echo "<h1>Atributos</h1>" ;
$variab1 = "" ;  $variab2 = "" ; $todo = '' ; $filtro1 = '' ; $filtro2 = '' ; $launo = '' ;

$launo = mysqli_query($conn,"select distinct(referencedComponentId),b.term from der2_cissccrefset_mrcmattributedomainsnapshot_int a, sct2_description_snapshot_globalsp b
		where a.active = 1 and b.active = 1  and b.typeId = 900000000000003001 and a.referencedComponentId = b.conceptId 
		order by b.term ; ") or die ("Error: " . mysqli_error($conn)) ; 
while ($qr0w = mysqli_fetch_array($launo)) do {
	$filtro1 = $qr0w['referencedComponentId'] ; 
	$sqlfi = "select a.referencedComponentId, b.term as term_referenced, a.domainId, e.term as term_domain, a.grouped, a.attributeCardinality, 
       a.attributeInGroupCardinality, a.ruleStrengthId as rule1, c.term as term_rule, a.contentTypeId as rule2, d.term as term_content, f.rangeConstraint, 
	   f.attributeRule, f.ruleStrengthId as rule3, f.contentTypeId as rule4, g.term as term_rule3 , h.term as term_rule4
	   from der2_cissccrefset_mrcmattributedomainsnapshot_int a, sct2_description_snapshot_globalsp b, sct2_description_snapshot_globalsp c, sct2_description_snapshot_globalsp d, 
	   sct2_description_snapshot_globalsp e, der2_ssccrefset_mrcmattributerangesnapshot_int f, sct2_description_snapshot_globalsp g, sct2_description_snapshot_globalsp h
	 where  a.referencedComponentId = ".$filtro1." and 
	   a.referencedComponentId = b.conceptId and a.ruleStrengthId = c.conceptId and a.contentTypeId = d.conceptId and a.domainId = e.conceptId  
	   and f.ruleStrengthId = g.conceptId and f.contentTypeId = h.conceptId and f.referencedComponentId = a.referencedComponentId  
  	   and b.typeId = 900000000000003001 and c.typeId = 900000000000003001 and d.typeId = 900000000000003001 and e.typeId = 900000000000003001 and g.typeId = 900000000000003001 and h.typeId = 900000000000003001
	   and a.active = 1 and b.active = 1 and c.active = 1 and d.active = 1 and e.active = 1 and g.active = 1 and h.active = 1 
	   order by b.term " ;
	$resfi = mysqli_query($conn,$sqlfi) or die ("Error: " . mysqli_error($conn)) ; 

    $nom_atributo = '' ; $inicial = 1 ;
	while ($qr5w = mysqli_fetch_array($resfi)) do {
		$nom_atributo = $qr5w['referencedComponentId']." |".$qr5w['term_referenced']."|" ;
		$variab2 = $variab2.
		 "<table><tr><td width=5%></td><td colspan='2' align='center' bgcolor='beige'>Datos del Atributo</td></tr>".
		 "<tr><td width=5%></td><td>grouped: </td><td>".$qr5w['grouped']."</td></tr>".
		 "<tr><td width=5%></td><td>attributeCardinality: </td><td>".$qr5w['attributeCardinality']."</td></tr>".	
		 "<tr><td width=5%></td><td>attributeInGroupCardinality: </td><td>".$qr5w['attributeInGroupCardinality']."</td></tr>".
		 "<tr><td width=5%></td><td>ruleStrength: </td><td width=80%>".$qr5w['rule1']." |".$qr5w['term_rule']."|</td></tr>".	 
		 "<tr><td width=5%></td><td>contentType: </td><td>".$qr5w['rule2']." |".$qr5w['term_content']."|</td></tr>".
		 "<tr><td width=5%></td><td colspan='2'><font color='FireBrick'><b>Rangos del atributo </b></font></td></tr>".		 
		 "<tr><td width=5%></td><td>rangeConstraint: </td><td>".$qr5w['rangeConstraint']."</td></tr>".		
		 "<tr><td width=5%></td><td>attributeRule: </td><td>".$qr5w['attributeRule']."</td></tr>".	
		 "<tr><td width=5%></td><td>ruleStrength rango: </td><td>".$qr5w['rule3']." |".$qr5w['term_rule3']."|</td></tr>".		
		 "<tr><td width=5%></td><td>contentType rango: </td><td>".$qr5w['rule4']." |".$qr5w['term_rule4']."|</td></tr>".			 
		 "</table>" ;		 
		if ($inicial == 1) {
			echo "<details><summary>".$nom_atributo."</summary>" ; 
		}
		$inicial ++ ;
		$domine = $qr5w['domainId'] ;		
		 
		$sqlff = "select a.referencedComponentId, b.term as term_k, a.domainConstraint, a.parentDomain, a.proximalPrimitiveConstraint, a.proximalPrimitiveRefinement, 
			a.domainTemplateForPrecoordination, a.domainTemplateForPostcoordination, a.guideURL 
			from der2_sssssssrefset_mrcmdomainsnapshot_int a, sct2_description_snapshot_globalsp b 
			where a.referencedComponentId = ".$domine." and a.referencedComponentId = b.conceptId and b.typeId = 900000000000003001 and a.active = 1 and b.active = 1 
			order by b.term  " ;
		$resff = mysqli_query($conn,$sqlff) or die ("Error: " . mysqli_error($conn)) ; 		
			
		while ($qr3w = mysqli_fetch_array($resff)) do {	
			$variab1 = $variab1.			
			 "<table><tr><td width=5%></td><td colspan='2' align='center' bgcolor='beige'>Datos del Dominio</td></tr>".
			 "<tr><td width=5%></td><td>domainConstraint: </td><td>".$qr3w['domainConstraint']."</td></tr>". 
			 "<tr><td width=5%><td>parentDomain: </td><td>".$qr3w['parentDomain']."</td></tr>".
			 "<tr><td width=5%><td>proximalPrimitiveConstraint: </td><td>".$qr3w['proximalPrimitiveConstraint']."</td></tr>".	
			 "<tr><td width=5%><td>proximalPrimitiveRefinement: </td><td>".$qr3w['proximalPrimitiveRefinement']."</td></tr>".
			 "<tr><td width=5%><td>domainTemplateForPrecoordination: </td><td width=80%>".$qr3w['domainTemplateForPrecoordination']."</td></tr>".	 
			 "<tr><td width=5%><td>domainTemplateForPostcoordination: </td><td width=80%>".$qr3w['domainTemplateForPostcoordination']."</td></tr>".	
			 "<tr><td width=5%><td>guideURL: </td><td>".$qr3w['guideURL']."</td></tr>".		 
			 "</table><br>" ;			
			$domino = $qr3w['referencedComponentId'] ;	
			echo "<tr><td><details close><summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$qr3w['referencedComponentId']." ".$qr3w['term_k']."</summary></td><td>".$variab2."<br>".$variab1."</details></td></tr>" ; 	

			$variab1 = "" ;
			$variab2 = "" ;				
		}	
		while ($qr3w = mysqli_fetch_array($resff)); 	
			
	}
	while ($qr5w = mysqli_fetch_array($resfi));
	echo "</details>" ;		
}		
while ($qr0w = mysqli_fetch_array($launo)); 

echo "<br><br>Fin del Informe. Fecha: ".$dia."/".$mes."/".$hoy['year']." hora: ".$hora.":".$minuto."<br>" ;
?>

<script type="text/javascript">
//	function generarXML() {
var mivarJS="<?php echo $myfile2 ; ?>" ;
//	window.open(mivarJS, 'nuevo', 'width=1200, height=600, top=50, left=50, menubar=NO, status=NO, toolbar=NO, titlebar=NO, directories=NO, location=NO') ;
	window.open(mivarJS, '_SELF' ) ;
//		} 
</script>
</html>

		