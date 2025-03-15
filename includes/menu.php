<div id="menu">

<?php
$miScript=$_SERVER['SCRIPT_FILENAME'];
$lenDocRoot=strlen($_SERVER['DOCUMENT_ROOT']);
$miPath=substr($miScript, $lenDocRoot);


$menu=array(
"Inicio"=>array("Inicio"=>"/index.php"),
"Consultas"=>array(
"Fichas Bandas"=>"/consultas/DisConBandas.php",
"Busquedas"=>"/busqueda/DisBusqueda.php"),
"Informes" =>array(
"Bandas con nombre NULL o vacio"=>"/informes/informe1.php",
"Bandas sin discos asociados"=>"/informes/informe2.php",
"Discos sin banda asociada"=>"/informes/informe3.php",
"Temas sin disco asociado"=>"/informes/informe4.php",
"Discos sin tracklist"=>"/informes/informe5.php",
"Discos sin cover"=>"/informes/informe6.php",
"Comprobacion de tabla covers"=>"/informes/informe7.php"),
"Utilidades"=>array(
"Importar CD_XML"=>"/utilities/import_xml/index.php",
"Check HD"=>"/utilities/check_hd/index.php")
);

foreach($menu as $mimenu => $opciones){

	if($mimenu<>''){echo "<h3 class='desplegado'>{$mimenu}</h3>\n";}
	
	echo "<ul>\n";
	
	foreach($opciones as $clave=>$valor){
		echo "<li";
		if($valor==$miPath){echo " class='actual'";}
		echo ">";
		echo "<a href='{$valor}'>{$clave}</a></li>\n";
	}
	echo "</ul>\n";
}
?>

</div> <!-- fin de menu -->