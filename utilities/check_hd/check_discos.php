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

$strDeleteTable="TRUNCATE hd_discos";

$result=$db->query($strDeleteTable);
if(!$result)
{
    echo 'error:' . $db->error;
    exit;
}


$strPopulateHdDiscos="INSERT INTO hd_discos (id_disco, titulo, album)
select distinct view_grupotitulo.id_disco, view_grupotitulo.titulo, hd_import.album
from view_grupotitulo left join hd_import on 
(view_grupotitulo.grupo=hd_import.artist AND view_grupotitulo.titulo=hd_import.album)
union
select distinct view_grupotitulo.id_disco, view_grupotitulo.titulo, hd_import.album
from hd_import left join view_grupotitulo on 
(hd_import.artist=view_grupotitulo.grupo AND hd_import.album=view_grupotitulo.titulo)
;";

$db->query($strPopulateHdDiscos);

$strChkDiscos="select distinct id_disco, titulo, album
from hd_discos
where titulo is null
or    album is null";

$discosBD=$db->query($strChkDiscos);

$miLog = new log(XML_PATH);
$miLog->registrarLineaLog('COMPROBACION DE DISCOS');

?>
<table class='bandas'>
    <caption>Discos no encontrados en HD:<?php echo $discosBD->num_rows;?></caption>
<thead>
<tr>
<th width="6">Id</th>
<th width="100">Titulo</th>
<th width="100">Album</th>
<tr>
</thead>
<tbody>
<?php
while($discoBD=$discosBD->fetch_assoc())
{

    echo '<tr>';

    $col1='';
    
    echo '<td>';
    if(is_null($discoBD['id_disco']))
    {
        $col1='N/D';
        echo $col1;
    }
    else
    {
        $col1=$discoBD['id_disco'];
	echo '<a href=\'' . 
        '/consultas/DisMFicha.php?id_disco=' . $col1 . '\'>';
        echo $col1;
        echo '</a>';
    }
    
    $miLog->registrarLineaLog($col1 . "\t", false);

    
    echo '</td>';

    echo '<td>';
    
    $col2='';
    
    if(is_null($discoBD['titulo']))
    {
        $col2='No encontrado en BD';
    }
    else
    {
	$col2=$discoBD['titulo'];
    }
    
    echo $col2;
    $miLog->registrarLineaLog($col2 . "\t", false);

    echo '</td>';

    echo '<td>';
    
    $col3='';
    
    if(is_null($discoBD['album']))
    {
        $col3='No encontrado en HD';
    }
    else
    {
	$col3=$discoBD['album'];
    }
    
    echo $col3;
    $miLog->registrarLineaLog($col3);

    echo '</td>';

    echo '</tr>';

}

?>

</table>

<?php

include(PATH_INCLUDE . "pie.php");

?>