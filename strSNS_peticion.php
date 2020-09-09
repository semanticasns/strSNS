<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<style>
table { border-collapse: nocollapse; font-family:Arial; font-size:12pt; border-spacing: 10px 2px; }
table, th { border: 0px solid black; padding: 15px; }
th {text-align: left; background-color:#FFD633;}
td { height: 35px;}
h2 {font-family:Segoe UI; font-size:16pt; background-color:#FFD633;}
body {font-family:'Maven Pro'; font-size:10pt; color: grey; }
.titular { font-size:24pt; color:black; }
.leyenda { font-size:10pt; color:black; }
.titular_area1 {font-size:22pt; color:#990000; }
.titular_area2 {font-size:22pt; color:green; }
.titular_seccion_desc { font-family:Segoe UI; font-size:16pt; color:#990000; border-bottom:2pt solid #990000; }
.titular_seccion_cons { font-family:Segoe UI; font-size:16pt; color:green; border-bottom:2pt solid green; }
.textAreaRespuesta { float:left; position: relative; width: 99%; height: 650px; }
.divtext {overflow:auto; background-color:white; border:solid gray 1px; padding:5px; width:60em; min-height:1.5em; color:navy; font-family:Consolas,Menlo; cursor:text;}
a.button { -webkit-appearance: button; -moz-appearance: button; appearance: button; text-decoration: none; color: initial;}
</style>

<body>
<table>
<tr><td><img alt='Ministerio de Sanidad' width='245px' src='MSAGob.jpg'></td><td class='titular'><b>strSNS</b><br>Servidor de Terminologías de Referencia del SNS</td><td><img alt='strSNS' width='180px' src='logostrsns.jpg'></td></tr>
</table>

<table>
<tr><td width='50%' colspan="3" align=center class='titular_area1' >Descarga de contenidos</td><td width='50%' colspan="3" align=center class='titular_area2' >Consulta de contenidos</td></tr>
<tr><td></td><td></td><td></td><td colspan="3" align='center'><a href='https://webs.somsns.es/cnr/strSNS/strSNS_Contenidos.htm' target='_blank'>Información sobre Bases de datos y tablas</a></td></tr>

<tr><td colspan="3" class='titular_seccion_desc'>SNOMED CT. Ediciones Internacionales y Extensión SNS.</td>&nbsp;&nbsp<td colspan="3" class='titular_seccion_cons'>SNOMED CT. Ediciones Internacionales y Extensión SNS.</td></tr>

<form  action="http:/strSNS/REST_server_concept.php/" method="POST" target='destino'> 
	<tr><td>Consulta por Id de concepto</td>
		<td><input type="text" name="consulta" size="10"></td>
		<td> <input type="submit" value="Descargar" name="descbottom" target='destino'></td></form>
		<form  action='http:/strSNS/Visor_EE.htm' method='POST' target='destino'> 
			<td>Visor de  Extensión para España</td>
			<td></td>		
			<td><input type="submit" value="Consultar" name="buscar" target='destino'></td>	
		</form>  				
	</tr>
 
 <form  action="http:/strSNS/REST_server_term.php/" method="POST" target="destino"> 
	<tr><td>Consulta por término</td>
		<td><input type="text" name="consulta" size="20"></td>
		<td><input type="submit" value="Descargar" name="txtbottom" target='destino'></td></form>
	<form  action='http:/strSNS/Visor_EERefsets.htm' method='POST' target='destino'> 
		<td colspan="2">Conjuntos de referencias Extensión para España</td>
		<td><input type="submit" value="Consultar" name="buscar" target='destino'></td>
		</form> 		
		
<form  action='http:/strSNS/REST_server_refsetEE.php/' method='POST' target='destino'> 
	<tr><td colspan="2">Conjuntos de referencias Extensión para España<br><input list="tablas" name="consulta" size="65"></td>
		<td><input type="submit" value="Descargar" name="buscar target='destino'"></td>
	</form> 
	<form  action="http:/strSNS/MLT_basico.php" method="POST" target='destino'> 
		<td>Árbol Multilingüe de concepto (MLTree)</td>
		<td><input type="text" name="consulta" value='' size="10"></td>
		<td> <input type="submit" value="Consultar" name="Consultar" target='destino'></td>
	</form> 				
	</tr>	
		
	<tr>
		<form  action="http:/strSNS/REST_server_refsetIE.php" method="POST" target='destino'> 
		<td colspan="2">Conjuntos de referencias International Edition<br><input list="tablasie" name="consulta" size="65"></td>
		<td> <input type="submit" value="Descargar" name="refsetie" target='destino'></td>
		</form>  		
		<form  action="http:/strSNS/Visor_refsets_IE.htm" method="POST" target='destino'> 
		<td colspan="2">Conjuntos de referencias International Edition</td>
		<td> <input type="submit" value="Consultar" name="refsetiec" target='destino'></td>
		</form> 		
	</tr>	
	
	<tr>
		<form  action="http:/strSNS/REST_server_refsetIEMap.php/" method="POST" target='destino'> 
		<td colspan="2">Conjuntos de referencias de mapeo a ICD WHO International Edition<br><input list="tablasiemap" name="consulta" size="65"></td>
		<td> <input type="submit" value="Descargar" name="refsetiem" target='destino'></td>	
		</form>	
		<form  action="http:/strSNS/REST_query_est_icd10map.php/" method="POST" target='destino'> 
		<td >Mapeo ICD-10 WHO a SNOMED CT</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td> <input type="submit" value="Consultar" name="mapicd10" target='destino'></td>
		</form> 		
	</tr>	
	
	<tr>
		<form  action='' method='POST' target='destino'> 	
		<td></td><td></td><td></td>
		</form>  		
		<form  action="http:/strSNS/REST_query_est_icd9map.php/" method="POST" target='destino'> 
		<td>Mapeo CIE-9-MC a SNOMED CT</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td> <input type="submit" value="Consultar" name="mapicd9" target='destino'></td>
		</form> 		
	</tr>	

	<tr>
		<form  action='' method='POST' target='destino'> 	
		<td></td><td></td><td></td>
		</form>  		
		<form  action="http:/strSNS/REST_query_est_icdomap.php/" method="POST" target='destino'> 
		<td>Mapeo ICD-O WHO a SNOMED CT</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td> <input type="submit" value="Consultar" name="mapicdo" target='destino'></td>
		</form> 		
	</tr>		
	
	<tr>
		<form  action='' method='POST' target='destino'> 	
		<td></td><td></td><td></td>
		</form>  		
		<form  action="https://browser.ihtsdotools.org" method="GET" target='destino'> 
		<td colspan="2">Browser International Edition</td>
		<td> <input type="submit" value="Consultar" name="Consultar" target='destino'></td>
		</form> 		
	</tr>		
	
	<tr>
		<form  action='' method='POST' target='destino'> 	
		<td></td><td></td><td></td>
		</form>  		
		<form  action="http:/strSNS/MRCM.php" method="GET" target='destino'> 
		<td colspan="2">MRCM (Machine Readable Concept Model SNOMED CT)</td>
		<td> <input type="submit" value="Consultar" name="Consultar" target='destino'></td>
		</form> 		
	</tr>	

	
<tr><td></td></tr>

<tr><td colspan="3" class='titular_seccion_desc'>SNOMED CT Medicamentos y Fórmulas Magistrales</td><td colspan="3" class='titular_seccion_cons'>SNOMED CT Medicamentos y Fórmulas Magistrales</td></tr>
<form  action="http:/strSNS/REST_server_termed.php/" method="POST" target="destino"> 
	<tr><td>Consulta por término</td>
		<td><input type="text" name="consulta" size="20"></td>
		<td><input type="submit" value="Descargar" name="tvtmbottom"></td></form>
		<form action="http:/strSNS/Visor_EERefsetsMed.htm" method="POST" target="destino"> 
		<td  colspan="2"> Conjuntos de referencias Extensión de Medicamentos para España </td>
		<td> <input type="submit" value="Consultar" name="buscar"></td></form>
	</tr>
  
<form  action="http:/strSNS/REST_server_refsetEEM.php/" method="POST" target="destino"> 
	<tr><td  colspan="2"> Conjuntos de referencias Extensión de Medicamentos para España <br><input list="tablasmed"  name="consulta" size="65"></td>
		<td> <input type="submit" value="Descargar" name="buscar"></td></form>
		<form action="http:/strSNS/Visor_OSC.htm" method="POST" target="destino"> 
		<td  colspan="2"> Ontología de Seguridad Clínica de Medicamentos</td>
		<td> <input type="submit" value="Consultar" name="buscar"></td></form>		
	</tr>
  
<form  action='http:/strSNS/REST_server_osc.php/' method='POST' target='destino'> 
	<tr><td colspan="2">Ontología de Seguridad Clínica de Medicamentos</td>
		<td><input type="submit" value="Descargar" name="oscmdesc" target="destino"></td></form>
	</tr>
   
<tr><td></td></tr>

<tr><td colspan="3" class='titular_seccion_desc'>EU Patient Summary. MVC-MTC</td><td colspan="3" class='titular_seccion_cons'>EU Patient Summary. MVC-MTC</td></tr>
<form  action="http:/strSNS/REST_server_mtc.php/" method="POST" target="destino"> 
	<tr><td colspan="2" >MTC (Master Translation Catalog)<br><input list="tablasmtc"  name="consulta" size="65"></td>
		<td><input type="submit" value="Descargar" name="mtc"></td></form>
		<form  action='http:/strSNS/Visor_recursos.htm' method='POST' target='destino'> 
		<td colspan="2">Catálogos MTC (Master Translation Catalog) para EU PS</td>
		<td><input type="submit" value="Consultar" name="buscar target='destino'"></td></form>  	
	</tr>
	
<tr><td></td></tr>

<tr><td colspan="3" class='titular_seccion_desc'>EU Patient Summary. Estándares nacionales</td><td colspan="3" class='titular_seccion_cons'>EU Patient Summary. Estándares nacionales</td></tr>
<form  action="http:/strSNS/REST_server_estandares.php/" method="POST" target="destino"> 
	<tr><td colspan="2" >Clasificación<br><input list="tablassna"  name="consulta" size="65"></td>
		<td><input type="submit" value="Descargar" name="sna"></td></form> 
		<form  action='http:/strSNS/REST_query_est_atc.php/' method="POST" target="destino"> 
		<td>ATC (código/término)</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td><input type="submit" value="Consultar" name="esatc" target="destino"></td></form>  				
	</tr>
<form  action="" method="POST" target="destino"> 
	<tr><td colspan="2" ></td>
		<td></td></form> 
		<form  action='http:/strSNS/REST_query_est_ciap2.php/' method="POST" target="destino"> 
		<td>CIAP-2 (código/término)</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td><input type="submit" value="Consultar" name="esciap2" target="destino"></td></form>  				
	</tr>	
	<form  action="" method="POST" target="destino"> 
	<tr><td colspan="2" ></td>
		<td></td></form> 
		<form  action='http:/strSNS/REST_query_est_cie10mc.php/' method="POST" target="destino"> 
		<td>CIE-10-MC 2020 (código/término)</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td><input type="submit" value="Consultar" name="escie10es" target="destino"></td></form>  				
	</tr>
	<tr><td colspan="2" ></td>
		<td></td></form> 
		<form  action='http:/strSNS/REST_query_est_cie10pcs.php/' method="POST" target="destino"> 
		<td>CIE-10-PCS 2020 (código/término)</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td><input type="submit" value="Consultar" name="escie10pcs" target="destino"></td></form>  				
	</tr>
	<tr><td colspan="2" ></td>
		<td></td></form> 
		<form  action='http:/strSNS/REST_query_est_cie9mc_diag.php/' method="POST" target="destino"> 
		<td>CIE-9-MC 2014 Diagnósticos (código/término)</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td><input type="submit" value="Consultar" name="escie9d" target="destino"></td></form>  				
	</tr>
	<tr><td colspan="2" ></td>
		<td></td></form> 
		<form  action='http:/strSNS/REST_query_est_cie9mc_proc.php/' method="POST" target="destino"> 
		<td>CIE-9-MC 2014 Procedimientos (código/término)</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td><input type="submit" value="Consultar" name="escie9p" target="destino"></td></form>  				
	</tr>
	<tr><td colspan="2" ></td>
		<td></td></form> 
		<form  action='http:/strSNS/REST_query_est_cieo3.php/' method="POST" target="destino"> 
		<td>CIE-O-3 2013 (código/término)</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td><input type="submit" value="Consultar" name="escieo3" target="destino"></td></form>  				
	</tr>

<tr><td></td></tr>
 
<tr><td colspan="3" class='titular_seccion_desc'>EU Patient Summary. Valuesets</td><td colspan="3" class='titular_seccion_cons'>EU Patient Summary. Valuesets</td></tr>
<form  action="http:/strSNS/REST_server_cnm.php/" method="POST" target="destino"> 
	<tr><td>Código nacional de medicamento</td><td><input list=""  name="consulta" size="10"></td>
		<td><input type="submit" value="Descargar" name="cnm"></td></form> 		
		<form  action='http:/strSNS/REST_query_cnm.php/' method="POST" target="destino"> 
		<td>Medicamentos (código/término)</td>
		<td><input list=""  name="consulta" size="20"></td>
		<td><input type="submit" value="Consultar" name="cnma" target="destino"></td></form>  			
	</tr>

<form  action="http:/strSNS/REST_server_valuesets.php/" method="POST" target="destino"> 
	<tr><td colspan="2">Valuesets bilingües (Medicamentos)<br><input list="tablasvss"  name="consulta" size="65"></td>
		<td><input type="submit" value="Descargar" name="sna"></td></form>
		<form  action='http:/strSNS/REST_query_valuesets.php/' method="POST" target="destino"> 
		<td colspan="2">Valueset bilingües (Medicamentos)<br><input list="tablasvss"  name="consulta" size="65"></td>
		<td><input type="submit" value="Consultar" name="snb" target="destino"></td></form>  	
	</tr>
 
<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td colspan="3">© Área SCI, Ministerio de Sanidad, 2020</td></tr>
</table><br>

<?php

// datalist Refsets EE
include $_SESSION['path_include']."/configbd_gex.php"; $dbConn = connect($db);
echo "<datalist id='tablas'>" ;
$sql = $dbConn->prepare("select tabla, nemotec, MTC_Tabla from strsns.str_recursos where grupo = 'refset_ee' order by MTC_Tabla");
$sql->execute() ;
while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
  echo "<option label='".$fila['MTC_Tabla']."' value='".$fila['nemotec']."'>".PHP_EOL;
}
echo "</datalist>" ;

// datalist Refsets EE Medicamentos
echo "<datalist id='tablasmed'>" ;
include $_SESSION['path_include']."/configbd_drug.php"; $dbConn = connect_drug($db_drug);
$sql = $dbConn->prepare("select tabla, nemotec, MTC_Tabla from strsns.str_recursos where grupo = 'refset_med' order by MTC_Tabla");
$sql->execute() ;
while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
  echo "<option label='".$fila['MTC_Tabla']."' value='".$fila['nemotec']."'>".PHP_EOL;
}
echo "</datalist>" ;

// datalist MVC-MTC
echo "<datalist id='tablasmtc'>" ;
include $_SESSION['path_include']."/configbd_str.php"; $dbConn = connect_str($db_str);
$sql = $dbConn->prepare("select tabla, nemotec, MTC_Tabla from strsns.str_recursos where grupo = 'EUPS_mtc' order by MTC_Tabla");
$sql->execute() ;
while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
  echo "<option label='".$fila['MTC_Tabla']."' value='".$fila['nemotec']."'>".PHP_EOL;
}
echo "</datalist>" ;

// datalist estándares nacionales
echo "<datalist id='tablassna'>" ;
$dbConn = connect_str($db_str);
$sql = $dbConn->prepare("select tabla, nemotec, MTC_Tabla from strsns.str_recursos where grupo = 'estandares' order by MTC_Tabla");
$sql->execute() ;
while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
  echo "<option label='".$fila['MTC_Tabla']."' value='".$fila['nemotec']."'>".PHP_EOL;
}
echo "</datalist>" ;

// datalist Valuesets
echo "<datalist id='tablasvss'>" ;
$dbConn = connect_str($db_str);
$sql = $dbConn->prepare("select tabla, nemotec, MTC_Tabla from strsns.str_recursos where grupo = 'valuesets' order by MTC_Tabla") ;
$sql->execute() ;
while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
  echo "<option label='".$fila['MTC_Tabla']."' value='".$fila['nemotec']."'>".PHP_EOL;
}
echo "</datalist>" ;

// datalist Refsets International Edition
echo "<datalist id='tablasie'>" ;
$dbConn = connect_str($db_str);
$sql = $dbConn->prepare("select tabla, nemotec, MTC_Tabla from strsns.str_recursos where grupo = 'International_Edition' order by nemotec");
$sql->execute() ;
while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
  echo "<option label='".$fila['MTC_Tabla']."' value='".$fila['nemotec']."'>".PHP_EOL;
}
echo "</datalist>" ;

// datalist Refsets Mapeo International Edition
echo "<datalist id='tablasiemap'>" ;
$dbConn = connect_str($db_str);
$sql = $dbConn->prepare("select tabla, nemotec, MTC_Tabla from strsns.str_recursos where grupo = 'IE_Map' order by nemotec");
$sql->execute() ;
while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
  echo "<option label='".$fila['MTC_Tabla']."' value='".$fila['nemotec']."'>".PHP_EOL;
}
echo "</datalist>" ;

?>
 
</body> 
</html>
