<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/discoteca/includes/variables.php");
require_once(PATH_CLASSES . 'thumb_class.php');

$thumb = new crearThumb($_GET['id_disco']);
$thumb->getThumb();

?>