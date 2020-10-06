<?php
session_start(); $_SESSION['path_include'] = "Access"; 
include $_SESSION['path_include']."/configbd_gex_msqli.php"; 
$busca = $_POST['consulta'] ;

// Prueba de cambio en Github

$sql01 = "select nombretabla, contenido, ordenado, mapeo, componente, nombretablasnapshot, visor from scibasetablas where visor = ".$busca." " ;
$res01 = mysqli_query($conn,$sql01) or die ("Error: " . mysqli_error()); 
while ($rg1 = mysqli_fetch_array($res01)) :	
	$va01 = $rg1['nombretabla'] ;	
	$va02 = $rg1['contenido'] ;
	$va03 = $rg1['ordenado'] ;
	$va04 = $rg1['mapeo'] ;  
	$va05 = $rg1['componente'] ;  
	$va06 = $rg1['nombretablasnapshot'] ;	
endwhile ;

$hoy = getdate();
$dia = strval($hoy['mday']) ; $mes = strval($hoy['mon']) ; $hora = strval($hoy['hours']) ; $minuto = strval($hoy['minutes']) ;
if ( $hoy['mday'] < 10) { $dia = "0"."".$dia ; } 
if ( $hoy['mon'] < 10) { $mes = "0"."".$mes ; } 
if ( $hoy['hours'] < 10) { $hora = "0"."".$hora ; } 
if ( $hoy['minutes'] < 10) { $minuto = "0"."".$minuto ; } 
$fecha  = $dia."/".$mes."/".$hoy['year'] ; 

$vis01 = '' ;	// fijo
$vis01A = '' ;
$vis01B = '' ;  // fijo
$vis01C = '' ;
$vis01D = '' ;  // fijo
$vis01E = '' ;
$vis02 = '' ;
$vis03 = '' ;   // fijo
$vis03A = '' ;
$vis03B = '' ;  // fijo
$vis04 = '' ;
$vis05 = '' ;   // fijo

$vis01 = $vis01 ."".  '<!DOCTYPE html>' ."\r\n" ;
$vis01 = $vis01 ."".  '<html onload="setcounter">'."\r\n" ;
$vis01 = $vis01 ."".  '<meta charset="UTF-8">'."\r\n" ;
$vis01 = $vis01 ."".  '<head>'."\r\n" ;
$vis01 = $vis01 ."".  ''."\r\n" ;
$vis01 = $vis01 ."".  '<script>' ."\r\n";
$vis01 = $vis01 ."".  'var colum=0; // columna por la que se filtrará' ."\r\n";
$vis01 = $vis01 ."".  'var valor; // value del botón que se ha pulsado' ."\r\n" ;
$vis01 = $vis01 ."".  "var filas = t.getElementsByTagName('tr');" ."\r\n";
$vis01 = $vis01 ."".  'var nTtotalRows = 0;' ."\r\n" ;
$vis01 = $vis01 ."".  'var nFilteredRows = 0;' ."\r\n" ;

$vis01 = $vis01 ."".  'function selecciona(obj,num) {' ."\r\n" ;
$vis01 = $vis01 ."".  "  t = document.getElementById('tab');" ."\r\n" ;
$vis01 = $vis01 ."".  "  filas = t.getElementsByTagName('tr');" ."\r\n" ;
$vis01 = $vis01 ."".  '  // Deseleccionar columna anterior' ."\r\n" ;
$vis01 = $vis01 ."".  '  for (i=1; ele=filas[i]; i++) ' ."\r\n";
$vis01 = $vis01 ."".  "    ele.getElementsByTagName('td')[colum].className='';" ."\r\n" ;
$vis01 = $vis01 ."".  '  // Seleccionar columna actual' ."\r\n" ;
$vis01 = $vis01 ."".  '  colum=num;' ."\r\n" ;
$vis01 = $vis01 ."".  ' for (i=1; ele=filas[i]; i++)' ."\r\n";
$vis01 = $vis01 ."".  "    ele.getElementsByTagName('td')[colum].className='celdasel';" ."\r\n" ;
$vis01 = $vis01 ."".  ' // Cambiar botón por cuadro de texto' ."\r\n" ;
$vis01 = $vis01 ."".  ' valor = obj.value;' ."\r\n" ;
$vis01 = $vis01 ."".  '  celda = obj.parentNode;' ."\r\n" ;
$vis01 = $vis01 ."".  '  celda.removeChild(obj);' ."\r\n" ;
$vis01 = $vis01 ."".  "  txt = document.createElement('input');" ."\r\n" ;
$vis01 = $vis01 ."".  '  // alert(num);' ."\r\n";
$vis01 = $vis01 ."".  ' // modificar de acuerdo a la estructura de la tabla' ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==0) { txt.size = '45'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==1) { txt.size = '10'; } " ."\r\n";
$vis01 = $vis01 ."".  " if (num==2) { txt.size = '7'; } " ."\r\n";
$vis01 = $vis01 ."".  "  if (num==3) { txt.size = '7'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==4) { txt.size = '7'; } "."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==5) { txt.size = '7'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==6) { txt.size = '7'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==7) { txt.size = '7'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==8) { txt.size = '3'; } " ."\r\n" ;
$vis01 = $vis01 ."".  ' celda.appendChild(txt);' ."\r\n" ;
$vis01 = $vis01 ."".  ' txt.onblur = function() {' ."\r\n" ;
$vis01 = $vis01 ."".  '  ponerBoton(this,num);' ."\r\n" ;
$vis01 = $vis01 ."".  '  updateCounter();' ."\r\n" ;
$vis01 = $vis01 ."".  '  };' ."\r\n" ;
$vis01 = $vis01 ."".  '  txt.onkeyup = function() {filtra(this.value)};' ."\r\n" ;
$vis01 = $vis01 ."".  '  // alert("Se han filtrado "+nFilteredRows+" triadas");' ."\r\n" ;
$vis01 = $vis01 ."".  ' txt.focus();' ."\r\n" ;
$vis01 = $vis01 ."".  '  // Desactivar los demás botones' ."\r\n" ;
$vis01 = $vis01 ."".  "  for (i=0; ele=t.getElementsByTagName('input')[i]; i++)" ."\r\n";
$vis01 = $vis01 ."".  "    if (ele.type == 'button') ele.disabled=true;" ."\r\n" ;
$vis01 = $vis01 ."".  '}' ."\r\n" ;

$vis01 = $vis01 ."".  "function ponerBoton(obj,num) { " ."\r\n" ;
$vis01 = $vis01 ."".  "  celda = obj.parentNode; " ."\r\n" ;
$vis01 = $vis01 ."".  " celda.removeChild(obj); " ."\r\n"  ;
$vis01 = $vis01 ."".  "  boton = document.createElement('input'); " ."\r\n" ;
$vis01 = $vis01 ."".  "  boton.type = 'button'; " ."\r\n" ;
$vis01 = $vis01 ."".  "  boton.value = valor; " ."\r\n" ;
$vis01 = $vis01 ."".  "  boton.onclick = function() {selecciona(this,num)} " ."\r\n" ;
$vis01 = $vis01 ."".  "  boton.onkeypress = function() {selecciona(this,num)} " ."\r\n" ;
$vis01 = $vis01 ."".  "  celda.appendChild(boton); " ."\r\n" ;
$vis01 = $vis01 ."".  "  // Activar botones " ."\r\n" ;
$vis01 = $vis01 ."".  "  for (i=0; ele=t.getElementsByTagName('input')[i]; i++) " ."\r\n" ;
$vis01 = $vis01 ."".  "    ele.disabled=false; " ."\r\n" ;
$vis01 = $vis01 ."".  "} " ."\r\n" ;


$vis01 = $vis01 ."".  "function filtra(txt) { " ."\r\n" ;
$vis01 = $vis01 ."".  "  var t = document.getElementById('tab'); " ."\r\n" ;
$vis01 = $vis01 ."".  "  var filas = t.getElementsByTagName('tr'); " ."\r\n" ;
$vis01 = $vis01 ."".  "  var pattern = new RegExp(txt.replace(/ /g,'.*'),'i') ; "."\r\n"; 
$vis01 = $vis01 ."".  "  var filteredRows = 0; " ."\r\n" ;
$vis01 = $vis01 ."".  " for (i=1; ele=filas[i]; i++) { " ."\r\n" ;
$vis01 = $vis01 ."".  "    texto = ele.getElementsByTagName('td')[colum].innerHTML.toUpperCase(); " ."\r\n" ;
$vis01 = $vis01 ."".  "    for (j=0; ra=document.forms[0].rad[j]; j++) // Comprobar radio seleccionado " ."\r\n" ;
$vis01 = $vis01 ."".  "     if (ra.checked) num = j; " ."\r\n" ;
$vis01 = $vis01 ."".  "    if (num==1) posi = (texto.indexOf(txt.toUpperCase()) == 0); // comienza con " ."\r\n" ;
$vis01 = $vis01 ."".  "    else if (num==2) posi = (texto.lastIndexOf(txt.toUpperCase()) == texto.length-txt.length); // termina en " ."\r\n" ;
$vis01 = $vis01 ."".  "    else if (num==3) posi = (texto.toUpperCase() == txt || texto.indexOf('>'+txt.toUpperCase()+'<') != -1); // igual a " ."\r\n" ;
$vis01 = $vis01 ."".  "    else if (num==4) posi = (pattern.test(texto) == true); // expresión regular encuentra pattern de txt en text del contenido " ."\r\n" ;
$vis01 = $vis01 ."".  "    else if (num==0) posi = (texto.indexOf(txt.toUpperCase()) != -1); // contiene  " ."\r\n" ;
$vis01 = $vis01 ."".  "	filteredRows = (posi) ? filteredRows+1 : filteredRows; " ."\r\n" ;
$vis01 = $vis01 ."".  "    ele.style.display = (posi) ? '' : 'none';  " ."\r\n" ;
$vis01 = $vis01 ."".  "	if (ele.style.display!='none') filteredRows++ ; " ."\r\n" ;
$vis01 = $vis01 ."".  " }  " ."\r\n" ;
$vis01 = $vis01 ."".  "} " ."\r\n" ;

$vis01 = $vis01 ."".  "function seleccionaGlosario(obj,num) {" ."\r\n" ;
$vis01 = $vis01 ."".  "  t = document.getElementById('tabGlosario');" ."\r\n" ;
$vis01 = $vis01 ."".  "  filas = t.getElementsByTagName('tr');" ."\r\n" ;
$vis01 = $vis01 ."".  "  // Deseleccionar columna anterior" ."\r\n" ;
$vis01 = $vis01 ."".  "  for (i=1; ele=filas[i]; i++) " ."\r\n" ;
$vis01 = $vis01 ."".  "    ele.getElementsByTagName('td')[colum].className='';" ."\r\n" ;
$vis01 = $vis01 ."".  "  // Seleccionar columna actual" ."\r\n" ;
$vis01 = $vis01 ."".  "  colum=num;" ."\r\n" ;
$vis01 = $vis01 ."".  "  for (i=1; ele=filas[i]; i++)" ."\r\n" ;
$vis01 = $vis01 ."".  "   ele.getElementsByTagName('td')[colum].className='celdasel';" ."\r\n" ;
$vis01 = $vis01 ."".  " // Cambiar botón por cuadro de texto" ."\r\n" ;
$vis01 = $vis01 ."".  " valor = obj.value;" ."\r\n" ;
$vis01 = $vis01 ."".  "  celda = obj.parentNode;" ."\r\n" ;
$vis01 = $vis01 ."".  "  txt = document.createElement('input');" ."\r\n" ;
$vis01 = $vis01 ."".  "  // alert(num);" ."\r\n" ;
$vis01 = $vis01 ."".  " // modificar de acuerdo a la estructura de la tabla" ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==0) { txt.size = '45'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==1) { txt.size = '10'; } " ."\r\n";
$vis01 = $vis01 ."".  " if (num==2) { txt.size = '7'; } " ."\r\n";
$vis01 = $vis01 ."".  "  if (num==3) { txt.size = '7'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==4) { txt.size = '7'; } "."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==5) { txt.size = '7'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==6) { txt.size = '7'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==7) { txt.size = '7'; } " ."\r\n" ;
$vis01 = $vis01 ."".  "  if (num==8) { txt.size = '3'; } " ."\r\n" ;

$vis01 = $vis01 ."".  "  celda.appendChild(txt);" ."\r\n" ;
$vis01 = $vis01 ."".  "  txt.onblur = function() {" ."\r\n" ;
$vis01 = $vis01 ."".  "  ponerBotonGlosario(this,num);" ."\r\n" ;
$vis01 = $vis01 ."".  "  updateCounterGlosario();" ."\r\n" ;
$vis01 = $vis01 ."".  "  };" ."\r\n" ;
$vis01 = $vis01 ."".  " txt.onkeyup = function() {filtraGlosario(this.value)};" ."\r\n" ;
$vis01 = $vis01 ."".  " // alert('Se han filtrado '+nFilteredRows+' triadas');" ."\r\n" ;
$vis01 = $vis01 ."".  "  txt.focus();" ."\r\n" ;

$vis01 = $vis01 ."".  " // Desactivar los demás botones" ."\r\n" ;
$vis01 = $vis01 ."".  "  for (i=0; ele=t.getElementsByTagName('input')[i]; i++)" ."\r\n" ;
$vis01 = $vis01 ."".  "    if (ele.type == 'button') ele.disabled=true;" ."\r\n" ;
$vis01 = $vis01 ."".  "}" ."\r\n" ;

$vis01 = $vis01 ."".  "function ponerBotonGlosario(obj,num) {" ."\r\n" ;
$vis01 = $vis01 ."".  "  celda = obj.parentNode;" ."\r\n" ;
$vis01 = $vis01 ."".  " celda.removeChild(obj);" ."\r\n" ;
$vis01 = $vis01 ."".  " boton = document.createElement('input');" ."\r\n" ;
$vis01 = $vis01 ."".  "  boton.type = 'button';" ."\r\n" ;
$vis01 = $vis01 ."".  "  boton.value = valor;" ."\r\n" ;
$vis01 = $vis01 ."".  "  boton.onclick = function() {filtraGlosario(this,num)}" ."\r\n" ;
$vis01 = $vis01 ."".  "  boton.onkeypress = function() {filtraGlosario(this,num)}" ."\r\n" ;
$vis01 = $vis01 ."".  "  celda.appendChild(boton);" ."\r\n" ;
$vis01 = $vis01 ."".  "  // Activar botones" ."\r\n" ;
$vis01 = $vis01 ."".  "  for (i=0; ele=t.getElementsByTagName('input')[i]; i++)" ."\r\n" ;
$vis01 = $vis01 ."".  "    ele.disabled=false;" ."\r\n" ;
$vis01 = $vis01 ."".  "}" ."\r\n" ;

$vis01 = $vis01 ."".  "function filtraGlosario(txt) {" ."\r\n" ;
$vis01 = $vis01 ."".  "  var t = document.getElementById('tabGlosario');" ."\r\n" ;
$vis01 = $vis01 ."".  "  var filas = t.getElementsByTagName('tr');" ."\r\n" ;
$vis01 = $vis01 ."".  "  // var filteredRows = 0;" ."\r\n" ;
$vis01 = $vis01 ."".  "  for (i=1; ele=filas[i]; i++) {" ."\r\n" ;
$vis01 = $vis01 ."".  "    texto = ele.getElementsByTagName('td')[colum].innerHTML.toUpperCase();" ."\r\n" ;
$vis01 = $vis01 ."".  "   for (j=0; ra=document.forms[0].rad[j]; j++) // Comprobar radio seleccionado" ."\r\n" ;
$vis01 = $vis01 ."".  "      if (ra.checked) num = j;" ."\r\n" ;
$vis01 = $vis01 ."".  "    if (num==1) posi = (texto.indexOf(txt.toUpperCase()) == 0); // comienza con" ."\r\n" ;
$vis01 = $vis01 ."".  "    else if (num==2) posi = (texto.lastIndexOf(txt.toUpperCase()) == texto.length-txt.length); // termina en" ."\r\n" ;
$vis01 = $vis01 ."".  "    else if (num==3) posi = (texto.toUpperCase() == txt || texto.indexOf('>'+txt.toUpperCase()+'<') != -1); // igual a" ."\r\n" ;
$vis01 = $vis01 ."".  "    else posi = (texto.indexOf(txt.toUpperCase()) != -1); // contiene" ."\r\n" ;
$vis01 = $vis01 ."".  "	// filteredRows = (posi) ? filteredRows+1 : filteredRows;" ."\r\n" ;
$vis01 = $vis01 ."".  "    ele.style.display = (posi) ? '' : 'none'; " ."\r\n" ;
$vis01 = $vis01 ."".  "	if (ele.style.display!='none') nFilteredRowsGlosario++ ;" ."\r\n" ;
$vis01 = $vis01 ."".  "  } " ."\r\n" ;
$vis01 = $vis01 ."".  "}" ."\r\n" ;


$vis01 = $vis01 ."".  "function setCounter() { " ."\r\n" ;
$vis01 = $vis01 ."".  "var t = document.getElementById('tab'); " ."\r\n" ;
$vis01 = $vis01 ."".  "var filas = t.getElementsByTagName('tr'); " ."\r\n" ;
$vis01 = $vis01 ."".  "// var totalRows = 0; " ."\r\n" ;
$vis01 = $vis01 ."".  "for (i=1; ele=filas[i]; i++) { totalRows = i; } " ."\r\n" ;
$vis01 = $vis01 ."".  "// alert('Se han cargado ' + totalRows + ' triadas.'); " ."\r\n" ;
$vis01 = $vis01 ."".  "document.getElementById('totalRows').value = totalRows; " ."\r\n" ;
$vis01 = $vis01 ."".  "document.getElementById('filteredRows').value = totalRows; " ."\r\n" ;
$vis01 = $vis01 ."".  "} " ."\r\n" ;

$vis01 = $vis01 ."".  "function updateCounter() { " ."\r\n" ;
$vis01 = $vis01 ."".  "nFilteredRows = 0; " ."\r\n" ;
$vis01 = $vis01 ."".  "var t = document.getElementById('tab'); " ."\r\n" ;
$vis01 = $vis01 ."".  "var filas = t.getElementsByTagName('tr'); " ."\r\n" ;
$vis01 = $vis01 ."".  "// var totalRows = 0; " ."\r\n" ;
$vis01 = $vis01 ."".  "for (i=1; ele=filas[i]; i++) {  " ."\r\n" ;
$vis01 = $vis01 ."".  "	if (ele.style.display != 'none') nFilteredRows++ ; " ."\r\n" ;
$vis01 = $vis01 ."".  "} " ."\r\n" ;
$vis01 = $vis01 ."".  "document.getElementById('filteredRows').value = nFilteredRows; " ."\r\n" ;
$vis01 = $vis01 ."".  "} " ."\r\n" ; 

$vis01 = $vis01 ."".  "function setCounterGlosario() {" ."\r\n" ; 
$vis01 = $vis01 ."".  "var nTotalRowsGlosario = 0;" ."\r\n" ; 
$vis01 = $vis01 ."".  "var t = document.getElementById('tabGlosario');" ."\r\n" ; 
$vis01 = $vis01 ."".  "var filas = t.getElementsByTagName('tr');" ."\r\n" ; 
$vis01 = $vis01 ."".  "// var totalRows = 0;" ."\r\n" ; 
$vis01 = $vis01 ."".  "for (i=1; ele=filas[i]; i++) { nTotalRowsGlosario = i; }" ."\r\n" ; 
$vis01 = $vis01 ."".  "// alert('Se han cargado ' + totalRows + ' triadas.');" ."\r\n" ; 
$vis01 = $vis01 ."".  "document.getElementById('totalRowsGlosario').value = nTotalRowsGlosario;" ."\r\n" ; 
$vis01 = $vis01 ."".  "document.getElementById('filteredRowsGlosario').value = nTotalRowsGlosario;" ."\r\n" ; 
$vis01 = $vis01 ."".  "}" ."\r\n" ; 

$vis01 = $vis01 ."".  "function updateCounterGlosario() {" ."\r\n" ; 
$vis01 = $vis01 ."".  "var nFilteredRowsGlosario = 0;" ."\r\n" ; 
$vis01 = $vis01 ."".  "var t = document.getElementById('tabGlosario');" ."\r\n" ; 
$vis01 = $vis01 ."".  "var filas = t.getElementsByTagName('tr');" ."\r\n" ; 
$vis01 = $vis01 ."".  "// var totalRows = 0;" ."\r\n" ; 
$vis01 = $vis01 ."".  "for (i=1; ele=filas[i]; i++) { " ."\r\n" ; 
$vis01 = $vis01 ."".  "	if (ele.style.display != 'none') nFilteredRowsGlosario++ ;" ."\r\n" ; 
$vis01 = $vis01 ."".  "}" ."\r\n" ; 
$vis01 = $vis01 ."".  "document.getElementById('filteredRowsGlosario').value = nFilteredRowsGlosario;" ."\r\n" ; 
$vis01 = $vis01 ."".  "}" ."\r\n" ; 

$vis01 = $vis01 ."".  "function sincroniza(ngroup) { " ."\r\n" ;
$vis01 = $vis01 ."".  "// parent.frames[2].location='DHCGruposArbol.htm'+'#'+group; " ."\r\n" ;
$vis01 = $vis01 ."".  "alert('Estoy sincronizando con '+'DHCGruposArbol.htm'+'#'+ngroup);	 "  ."\r\n" ;
$vis01 = $vis01 ."".  "} " ."\r\n" ;

$vis01 = $vis01 ."".  "</script> " ."\r\n" ;

$vis01 = $vis01 ."".  "<style type='text/css'> " ."\r\n" ;
$vis01 = $vis01 ."". "input[type=button], input[type=reset] { font-family:Calibri; background-color: #4CAF50;  border: none;  color: white;  padding: 4px 8px;  text-decoration: none;  margin: 1px 1px;  cursor: pointer;}" ;
$vis01 = $vis01 ."". "input[type=submit] { font-family:Calibri; background-color: #FEFECF;  border: none;  color: black;  padding: 2px 4px;  text-decoration: none;  margin: 1px 1px;  cursor: pointer;}" ;
$vis01 = $vis01 ."".  "body {font-family:Calibri; font-size:11pt; } " ."\r\n" ;
$vis01 = $vis01 ."".  "table tr th {font-family:Calibri; font-size:10pt; text-align:left; background-color:#EEEEDD; padding-left:1px; } " ."\r\n" ;
$vis01 = $vis01 ."".  "table tr td {font-family:Calibri; font-size:10pt; text-align:left; vertical-align: top; padding-left:3px; cursor:pointer; } " ."\r\n" ;
$vis01 = $vis01 ."".  "table tr td {border-bottom:1px dotted lightgray; } " ."\r\n" ;
$vis01 = $vis01 ."".  "pre {font-family:Calibri; font-size:11pt; text-align:left; vertical-align: top; padding-left:3px; cursor:pointer; } " ."\r\n" ;
$vis01 = $vis01 ."".  "input.number {text-align: right;} " ."\r\n" ;
$vis01 = $vis01 ."".  ".descriptor {font-weight:bold; background-color:#EFEFEF;} "  ."\r\n";
$vis01 = $vis01 ."".  ".alternative {font-style: italic;} " ."\r\n" ;
$vis01 = $vis01 ."".  ".help {color:#088A85; cursor:pointer;} " ."\r\n" ;
$vis01 = $vis01 ."".  "</style> " ."\r\n" ;

$vis01 = $vis01 ."".  "</head> " ."\r\n" ;
$vis01 = $vis01 ."".  "<body onload='setCounter()'> " ."\r\n" ;

$vis01 = $vis01 ."".  "<form class='formu' action=''> " ."\r\n" ;

$vis01A = $vis01A ."".  "<!-- Título principal -----------------------------------------------------------------------------> " ."\r\n" ;
$vis01A = $vis01A ."".  "<big><big><big>Visor web del conjunto de referencias de ".$va02." </big></big></big><br/> " ."\r\n" ;
$vis01A = $vis01A ."".  "<hr/> " ."\r\n" ;

$vis01B = $vis01B ."".  "<!-- Producto -------------------------------------------------------------------------------------> " ."\r\n" ;
$vis01B = $vis01B ."".  "&copy; Ministerio de Sanidad - Centro Nacional de Referencia de SNOMED CT para España. Versión del Visor: ".$fecha.".   (Requiere Google Chrome ™). "  ;

$vis01C = $vis01C ."".  "<!-- Ayuda ----------------------------------------------------------------------------------------> " ."\r\n" ;
$vis01C = $vis01C ."".  "<hr/> " ."\r\n" ;
$vis01C = $vis01C ."".  "<details class='help'><summary>Ayuda</summary> " ."\r\n";
$vis01C = $vis01C ."".  "<ul> " ."\r\n";
$vis01C = $vis01C ."".  "<li><strong>Contenido:</strong> " ."\r\n";
$vis01C = $vis01C ."".  "<ul> " ."\r\n" ;
$vis01C = $vis01C ."".  "<li>El conjunto de referencia de ".$va02." se presenta como una tabla con buscadores para cada columna.</li> " ."\r\n";
$vis01C = $vis01C ."".  "<li>Cada fila contiene un elemento del conjunto de referencia de ".$va02.".</li> " ."\r\n";
$vis01C = $vis01C ."".  "<li>La codificación de los conceptos ha sido normalizada por medio de la terminología clínica SNOMED CT. " ."\r\n";
$vis01C = $vis01C ."".  "Algunos componentes del refset forman parte de la Extensión para España de SNOMED CT.</li><br>" ."\r\n";
$vis01C = $vis01C ."".  "</ul></li> " ."\r\n" ;

$vis01C = $vis01C ."".  "<li><strong>Tabla de Contenidos:</strong> " ."\r\n" ;
$vis01C = $vis01C ."".  "<ul><li>La Tabla de Contenidos puede filtrar el contenido del refset pulsando sobre los botones de las cabeceras de columna  " ."\r\n" ;
$vis01C = $vis01C ."".  "introduciendo la cadena que quiera buscar.</li> " ."\r\n" ;
$vis01C = $vis01C ."".  "<li>La columna Id concept muestra los códigos SNOMED CT de los conceptos relacionados, y actúa como enlace a la página MultilingualTree, donde se muestra toda la información relacionada con el concepto.</li>" ."\r\n" ;
$vis01C = $vis01C ."".  "<li>La columna term contiene la descripción de la enfermedad en SNOMED CT" ."\r\n" ;
$vis01C = $vis01C ."".  "<li>La columna effectiveTime contiene la fecha desde la que está vigente el estado del concepto." ."\r\n" ;
$vis01C = $vis01C ."".  "<li>La columna active identifica el estado del concepto (activo/inactivo).. " ."\r\n" ;
$vis01C = $vis01C ."".  "<li>La columna refsetId contiene el identificador SNOMED CT del conjunto de referencias." ."\r\n" ;
$vis01C = $vis01C ."".  "<li>La columna moduleId identifica el identificador SNOMED CT del módulo al que pertenece el concepto. " ."\r\n" ;
$vis01C = $vis01C ."".  "<li>La columna Mail incluye un enlace para el envío por correo electrónico al área de Semántica del Ministerio de Sanidad. " ."\r\n" ;
$vis01C = $vis01C ."".  "</ul></li> " ."<br>" ;
$vis01C = $vis01C ."".  "<li>Criterios de búsqueda: puede modificar la modalidad de búsqueda utilizando las opciones del menú 'Buscar:'. " ."\r\n" ;
$vis01C = $vis01C ."".  "<li>Marcando la opción 'Expresión' puede realizar búsquedas utilizando varias partículas de palabra." ."\r\n" ;
$vis01C = $vis01C ."".  "</details><hr/> " ."\r\n" ;

$vis01D = $vis01D ."".  "<!-- Modalidad ------------------------------------------------------------------------------------------------> " ."\r\n" ;
$vis01D = $vis01D ."".  "Buscar: <input type='button' value='Todos' onclick='window.location.reload()'> ... Filtrar:  " ."\r\n";
$vis01D = $vis01D ."".  "<label for='fil1'><input type='radio' name='rad' id='fil1' />Contiene ...</label> " ."\r\n" ;
$vis01D = $vis01D ."".  "<label for='fil2'><input type='radio' name='rad' id='fil2' />Comienza con ...</label> " ."\r\n" ;
$vis01D = $vis01D ."".  "<label for='fil3'><input type='radio' name='rad' id='fil3' />Termina con ...</label> " ."\r\n" ;
$vis01D = $vis01D ."".  "<label for='fil4'><input type='radio' name='rad' id='fil4' />Igual a ...</label> " ;
$vis01D = $vis01D ."".  "<label for='fil5'><input type='radio' name='rad' id='fil5' checked='checked' >Expresión ...</label> " ;

$vis01D = $vis01D ."".  "<!-- Contadores ------------------------------------------------------------------------------------------------------> " ."\r\n" ;
$vis01D = $vis01D ."".  " |   Seleccionados <input id='filteredRows' type='text' size='6' class='number' readonly='readonly'> elementos  " ."\r\n" ;
$vis01D = $vis01D ."".  "de <input id='totalRows' type='text' size='6' class='number'  readonly='readonly'>."."\r\n"  ;

$vis03 = $vis03 ."".  "<hr /><br> " ."\r\n" ;
$vis03 = $vis03 ."".  "<!-- Apartado -----------------------------------------------------------------------------------> " ."\r\n" ;
$vis03 = $vis03 ."".  "<!-- Cabecera -------------------------------------------------------------------------------------> " ."\r\n" ;
$vis03 = $vis03 ."".  "<a name='Top'></a> " ."\r\n" ;
$vis03 = $vis03 ."".  "<table width='100%' class='tabla' id='tab'> " ."\r\n" ;
$vis03 = $vis03 ."".  "<tr class='encabezado'> " ."\r\n" ;
$vis03A = $vis03A ."".  "<th width='10%'><input type='button' title='' value='Id concept' onclick='selecciona(this,0)' onkeypress='selecciona(this,0)' /></th> " ."\r\n" ;
$vis03A = $vis03A ."".  "<th width='60%'><input type='button' title='' value='term' onclick='selecciona(this,1)' onkeypress='selecciona(this,1)' /></th> " ."\r\n" ;
$vis03A = $vis03A ."".  "<th width='7%'><input type='button' title='' value='effectiveTime' onclick='selecciona(this,2)' onkeypress='selecciona(this,2)' /></th> " ."\r\n" ;
$vis03A = $vis03A ."".  "<th width='5%'><input type='button' title='' value='active' onclick='selecciona(this,3)' onkeypress='selecciona(this,3)' /></th> " ."\r\n" ;
$vis03A = $vis03A ."".  "<th width='7%'><input type='button' title='' value='moduleId' onclick='selecciona(this,4)' onkeypress='selecciona(this,4)' /></th> " ."\r\n" ;
$vis03A = $vis03A ."".  "<th width='7%'><input type='button' title='' value='refsetId' onclick='selecciona(this,5)' onkeypress='selecciona(this,5)' /></th> " ."\r\n" ;
$vis03A = $vis03A ."".  "<th width='5%'><input type='button' title='' value='Mail' onclick='selecciona(this,6)' onkeypress='selecciona(this,6)' /></th> " ."\r\n" ;
$vis03A = $vis03A ."".  "</tr> " ."\r\n" ;

$vis05 = $vis05 ."".  "</table> " ."\r\n" ;
$vis05 = $vis05 ."".  "</form> " ."\r\n" ;
$vis05 = $vis05 ."".  "<hr /> " ."\r\n" ;
$vis05 = $vis05 ."".  "Fin del documento. " ."\r\n" ;
$vis05 = $vis05 ."".  "</body> " ."\r\n" ;
$vis05 = $vis05 ."".  "</html> " ."\r\n" ;

// Genera Visor 

$sql00 = "select id, effectiveTime, active, referencedComponentId, moduleid, refsetid, rtrim(term) as term from ".$va06." order by term, effectivetime " ;
$res00 = mysqli_query($conn,$sql00) or die ("Error: " . mysqli_error()); 
	$vis04 = $vis04 ."<form action=''></form>" ;

	$line = 0 ;
	while ($row = mysqli_fetch_array($res00)): 
		$line ++ ;	
		$cmp1 = $row['effectiveTime'] ;
		$cmp2 = $row['active'] ;		
		$cmp3 = $row['moduleid'] ;	
		$cmp4 = $row['refsetid'] ;				
		$cmp5 = $row['referencedComponentId'] ;	
		$cmp6 = $row['term'] ;				
		$cmp7 = "<form action='https://webs.somsns.es/cnr/MLT_basico.php' method='POST' target='_blank' >
				<input type='submit' value='".$cmp5."' name='consulta' target='_blank' ></form>" ;		
		$cmp8 = "<a href='mailto:semanticasns@mscbs.es?subject=Comentario sobre Refset ".$va02."&body=Concepto: ".$cmp5." |".$cmp6."|  %0A%0AComentarios: %0A%0A' >e-mail</a>" ;
		$fila2 = "<tr><td>".$cmp7."</td><td>".$cmp6."</td><td>".$cmp1."</td><td>".$cmp2."</td><td>".$cmp3."</td><td>".$cmp4."</td><td>".$cmp8."</td></tr>"."\r\n" ;	
		$vis04 = $vis04 ."". $fila2 ;	
	endwhile ; 
 
echo $vis01 ;	
echo $vis01A ;	
echo $vis01B ;	
echo $vis01C ;	
echo $vis01D ;	
echo $vis01E ;	
echo $vis02 ;	
echo $vis03 ;	
echo $vis03A ;	
echo $vis04 ;
echo $vis05 ;	

mysqli_close($conn);


?>
