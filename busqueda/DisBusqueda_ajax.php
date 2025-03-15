<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

require_once(PATH_INCLUDE . "variables_db_connect.php");
require_once(PATH_CLASSES . 'mysqliDb_class.php');
require_once(PATH_CLASSES . 'mysqliDb_exception_class.php');
 
$banda=addslashes(strtoupper($_POST['banda']));

$strBandasTotal="SELECT DISTINCT grupo FROM bandas WHERE UPPER(grupo) LIKE '$banda%'";


$db= new mysqliDb();

$db->setQueryString($strBandasTotal);
$results=$db->execSELECT();
$numeroTotal=$db->getNumRows();
 
$strBandas="SELECT DISTINCT grupo, id_grupo FROM bandas WHERE UPPER(grupo) LIKE '$banda%' LIMIT 0,10";

$db->setQueryString($strBandas);
$bandas=$db->execSELECT();
$numero=$db->getNumRows();

if($numero>0)
{
	echo "<table>";

	foreach($bandas as $banda)
        {
            $link = '/consultas/DisConDiscos.php?id_grupo=' . $banda['id_grupo'];
            echo "<tr>";
            echo "<td class='banda_busqueda'>
            <a href='{$link}'>" . $banda['grupo'] . "</a>
            </td>";
            echo "</tr>";
	}
	
	echo "</table>";
	
	echo "<p>--- Numero de registros: " . $numeroTotal . " ---</p>";
}
//include(PATH_INCLUDE . "pie.php");
?>