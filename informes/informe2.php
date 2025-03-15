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

//comprobar bandas que no tengan asociado ningun disco

$strCheck="select bandas.id_grupo, bandas.grupo, count(discos.id_disco) as ndiscos from 
bandas left join discos on bandas.id_grupo=discos.id_grupo
group by bandas.id_grupo, bandas.grupo 
having ndiscos=0";

$db->setQueryString($strCheck);

$qCheck=$db->execSELECT();

if ($db->getNumRows()==0)
{
	echo "<p>Todas las bandas tienen al menos un disco asociado</p>\n";
}
else
{
	echo "<p>Bandas sin discos asociados </p>\n";
	echo "<table>\n";
	foreach ($qCheck as $nombre)
	{
            echo "<tr>\n";
            echo "<td>{$nombre['id_grupo']}</td>\n";
            echo "<td>{$nombre['grupo']}</td>\n";
            echo "<td>{$nombre['ndiscos']}</td>\n";
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
