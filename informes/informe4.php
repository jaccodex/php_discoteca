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

//comprobar que no existen temas que tengan id_disco que exista en la tabla discos 
$strCheck="select temas.id_tema, temas.titulo, temas.duracion
from temas left join discos on discos.id_disco=temas.id_disco
where discos.id_disco is null";

$db->setQueryString($strCheck);

$qCheck=$db->execSELECT();

if ($db->getNumRows()==0)
{
	echo "<p>No hay temas con id_disco nulo</p>";
}
else
{
	echo "<p>Temas con id_disco no existente en tabla discos </p>\n";
	echo "<table>\n";
	foreach ($qCheck as $nombre)
        {
            echo "<tr>\n";
            echo "<td>{$nombre['id_tema']}</td>\n";
            echo "<td>{$nombre['titulo']}</td>\n";
            echo "<td>{$nombre['duracion']}</td>\n";
            echo "<td>";

            $strDeleteTema="delete from temas where id_tema=\"" . $nombre['id_tema'] . "\"";
            $db->setQueryString($strDeleteTema);

            if($db->executeQuery())
            {
                    echo "Borrado OK id_tema: " .$nombre['id_tema'];
            }
            else
            {
                    echo "ERROR GRAVE al borrar registro id_tema: " .$nombre['id_tema'];
            }

            echo "</td>\n";
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
