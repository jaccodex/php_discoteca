<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

require_once(PATH_CLASSES . 'thumb_class.php');

if(isset($_GET['width']))
{
    $width=$_GET['width'];
    
}
else
{
    $width=0;
}

$thumb = new crearThumb($_GET['id_disco'], $width);
$thumb->getThumb();

?>