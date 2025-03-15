<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

require_once(PATH_INCLUDE . "cabecera.php");
?>
</head>
<body>
<?php
require_once(PATH_INCLUDE . "logo.php");

?>

<div id="content">
<?php

require_once(PATH_INCLUDE . "menu.php");
?>
<div id="main">
<?php

require_once(PATH_INCLUDE . "variables_db_connect.php");
require_once(PATH_CLASSES . 'mysqliDb_class.php');
require_once(PATH_CLASSES . 'mysqliDb_exception_class.php');

$db= new mysqliDb();
?>

<div id="content">

<?php
//--, bandas.grupo ASC, discos.titulo ASC
//listado de discos que no tienen registro en covers
$strCheck="SELECT discos.id_disco, discos.titulo, bandas.grupo
FROM discos 
LEFT JOIN covers ON discos.id_disco=covers.id_disco
LEFT JOIN bandas ON discos.`id_grupo`=bandas.`id_grupo`
WHERE discos.id_grupo=bandas.id_grupo
AND   covers.id_disco IS NULL
ORDER BY bandas.grupo ASC, discos.titulo ASC;
;";

$db->setQueryString($strCheck);

$qCheck=$db->execSELECT();

if ($db->getNumRows()==0)
{
	echo "<p>No hay discos sin un registro asociado en covers</p>";
}
else
{
	echo "<p>Discos sin cover</p>\n";
	echo "<table>\n";
	foreach ($qCheck as $disco)
        {
            echo "<tr>\n";
            $ruta ="/consultas/DisSubirCaratula.php?id_disco=" . urlencode($disco['id_disco']);
            echo "<td><a href=\"{$ruta}\">{$disco['id_disco']}</a></td>\n";
            echo "<td>{$disco['grupo']}</td>\n";
            echo "<td>{$disco['titulo']}</td>\n";
            echo "</tr>\n";
        }
	echo "</table>\n";	
	echo "<p>No. registros: " . $db->getNumRows() . "</p>";

}

?>
</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
include(PATH_INCLUDE . "pie.php");
?>
