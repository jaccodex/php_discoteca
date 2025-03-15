<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

require_once(PATH_INCLUDE . "variables_db_connect.php");
require_once(PATH_CLASSES . 'pdoDb_class.php');


$id_disco=$_POST['id_disco'];

$strDisco = "SELECT ano, companias.compania, estilos.estilo, 
soportes.soporte, fuentes.fuente
FROM discos, bandas, companias, estilos, soportes, fuentes
WHERE discos.id_disco=" . $id_disco .
" AND bandas.id_grupo=discos.id_grupo
AND companias.id_compania=discos.id_compania
AND estilos.id_estilo=discos.id_estilo
AND soportes.id_soporte=discos.id_soporte
AND fuentes.id_fuente=discos.id_fuente";

$db= new pdoDb();

$db->setQueryString($strDisco);

$Discos=$db->execSELECT();
$Disco=$Discos[0];

$strDurac="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(duracion))) AS duracion FROM temas WHERE id_disco=" . $id_disco;

$db->setQueryString($strDurac);

$Durac=$db->execSELECT();
echo '<p><span class="titulo">Año</span>' . $Disco['ano'] . '</p>';
echo '<p><span class="titulo">Compañia</span>' . $Disco['compania'] . '</p>';
echo '<p><span class="titulo">Estilo</span>' . $Disco['estilo'] . '</p>';
echo '<p><span class="titulo">Soporte</span>' . $Disco['soporte'] . '</p>';
echo '<p><span class="titulo">Fuente</span>' . $Disco['fuente'] . '</p>';
echo '<p><span class="titulo">Duracion</span>' . $Durac[0]['duracion'] . '</p>';

?>
    