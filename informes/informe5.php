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

//listado de discos que tienen tracklist vacio o de suma 0
$strCheck="select 
discos.id_disco, 
discos.titulo, 
bandas.grupo, 
count(temas.id_tema) as ntemas, 
sum(temas.duracion) as sduracion
from discos left join temas on discos.id_disco=temas.id_disco, bandas
where (discos.id_grupo=bandas.id_grupo)
group by 1,2,3
having ntemas=0 or sduracion is NULL";

$db->setQueryString($strCheck);

$qCheck=$db->execSELECT();

if ($db->getNumRows()==0)
{
	echo "<p>No hay discos con tracklist vacio o sin duracion</p>";
}
else
{
	echo "<p>Discos con tracklist vacio o nulo</p>\n";
	echo "<table>\n";
	foreach ($qCheck as $disco)
        {
            echo "<tr>\n";
            $ruta ="/consultas/DisMFicha1.php?id_disco=" . urlencode($disco['id_disco']);
            echo "<td><a href=\"{$ruta}\">{$disco['id_disco']}</a></td>\n";
            echo "<td>{$disco['grupo']}</td>\n";
            echo "<td>{$disco['titulo']}</td>\n";
            echo "</tr>\n";
        }
	echo "</table>\n";	

}

?>
</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
include(PATH_INCLUDE . "pie.php");
?>
