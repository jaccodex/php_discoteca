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

//comprobar discos que no tengan un registro asociado en bandas

$strCheck="select 
discos.id_disco, discos.titulo
from discos left join bandas on discos.id_grupo=bandas.id_grupo
where bandas.id_grupo is null;";

$db->setQueryString($strCheck);

$qCheck=$db->execSELECT();

if ($db->getNumRows()==0)
{
	echo "<p>No hay discos con id_grupo nulo</p>";
}
else
{
	echo "<p>Discos con id_grupo no existente en tabla bandas </p>\n";
	
        echo "<table>\n";

	foreach ($qCheck as $nombre)
        {
            echo "<tr>\n";
            echo "<td>{$nombre['id_disco']},{$nombre['titulo']} </td>\n";
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
