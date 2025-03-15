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
ini_set('max_execution_time', 3600);

$xml_file = XML_PATH . "import.xml";

require_once(PATH_INCLUDE . "variables_db_connect.php");

//importamos el xml a la tabla dvd_import
$xml_object=  simplexml_load_file($xml_file);

$db=new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
$db->set_charset('utf8');

$strQuery='TRUNCATE TABLE hd_import';
$result=$db->query($strQuery);
if(!$result)
{
    echo 'error:' . $db->error;
    exit;
}

$strQuery='INSERT INTO hd_import(duration_in_seconds, artist, album, title, year, track) VALUES (?,?,?,?,?,?)';
$stmt=$db->prepare($strQuery);
$stmt->execute();

foreach($xml_object->media_file as $tema)
{
    $stmt->bind_param('ssssss', 
            $tema->duration_in_seconds, 
            $tema->artist, 
            $tema->album, 
            $tema->title, 
            $tema->year, 
            $tema->track);
    
    $stmt->execute();
}

unlink($xml_file);

?>

<p>El archivo se ha importado subido con exito.</p>
<p>Pulse <a href="index.php">aqui</a> para continuar.</p>
<?php
