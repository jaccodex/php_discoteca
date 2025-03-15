<?php
include($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

include(PATH_INCLUDE . "cabecera.php");
?>
</head>
<body>
<?php
include(PATH_INCLUDE . "logo.php");
include(PATH_INCLUDE . "menu.php");
?>

<div id="content">
<?php
require_once(PATH_CLASSES  . "log_class.php");
require_once(PATH_INCLUDE . "variables_db_connect.php");

$db=new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
$db->set_charset('utf8');

$strDeleteTable="TRUNCATE hd_bandas";

$result=$db->query($strDeleteTable);
if(!$result)
{
    echo 'error:' . $db->error;
    exit;
}

$strPopulateHdBandas="INSERT INTO hd_bandas (id_grupo, grupo, artist) 
SELECT DISTINCT bandas.id_grupo, bandas.grupo, UPPER(hd_import.artist)
FROM hd_import LEFT JOIN bandas ON hd_import.artist=bandas.grupo
UNION
SELECT DISTINCT bandas.id_grupo, bandas.grupo, UPPER(hd_import.artist)
FROM bandas LEFT JOIN hd_import ON bandas.grupo=hd_import.artist
;";

$result=$db->query($strPopulateHdBandas);
if(!$result)
{
    echo 'error:' . $db->error;
    exit;
}

$strChkBandas="select distinct id_grupo, grupo, artist from hd_bandas
where grupo is NULL
or    artist is NULL";

$bandasBD=$db->query($strChkBandas);

if(!$bandasBD)
{
    echo 'error:' . $db->error;
    exit;
}

$miLog = new log(XML_PATH);
$miLog->registrarLineaLog('COMPROBACION DE TEMAS');

?>
<table class='bandas'>
    <caption>Bandas no encontradas:<?php echo $bandasBD->num_rows;?></caption>
<thead>
<tr>
<th width="6">Id</th>
<th width="100">Grupo</th>
<th width="100">Artista</th>
<tr>
</thead>

<tbody>
<?php
while($bandaBD=$bandasBD->fetch_array())
{

    echo '<tr>';

    echo '<td>';
    
    $col1='';
    
    if(is_null($bandaBD['id_grupo']))
    {
        $col1='N/D';
    }
    else
    {
	$col1=$bandaBD['id_grupo'];
    }
    
    echo $col1;
    $miLog->registrarLineaLog($col1 . "\t", false);
    
    echo '</td>';


    
    echo '<td>';
    
    $col2='';
    
    if(is_null($bandaBD['grupo']))
    {
        $col2='No encontrado en BD';
    }
    else
    {
	$col2=$bandaBD['grupo'] . '-' . strlen($bandaBD['grupo']);
    }
    
    echo $col2;
    $miLog->registrarLineaLog($col2 . "\t", false);
    
    echo '</td>';

    echo '<td>';
    
    $col3='';
    
    if(is_null($bandaBD['artist']))
    {
        $col3='No encontrado en HD';
    }
    else
    {
	$col3=$bandaBD['artist'] . '-' . strlen($bandaBD['artist']);
    }

    echo $col3;
    $miLog->registrarLineaLog($col3);

    echo '</td>';

    echo '</tr>';


}
        
?>

</table>

</div>
<?php

include(PATH_INCLUDE . "pie.php");

?>