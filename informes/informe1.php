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

//comprobar bandas que tengan nombre null o nombre vacio

$strCheck="select bandas.id_grupo, bandas.grupo from bandas 
where bandas.grupo=NULL 
or TRIM(bandas.grupo)=''";

$db->setQueryString($strCheck);

$qCheck=$db->execSELECT();

if ($db->getNumRows()==0)
{
	echo "<p>Nombres de bandas OK </p>\n";
}
else
{
	echo "<p>Bandas con nombre nulo o vacio </p>\n";
	echo "<table>\n";
	foreach ($qCheck as $nombre)
	{
            echo "<tr>\n";
            echo "<td>{$nombre['id_grupo']}</td>\n";
            echo "<td>{$nombre['grupo']}</td>\n";
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
