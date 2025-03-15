<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/discoteca/includes/variables.php");
require_once(PATH_CLASSES . 'thumb_class.php');
/*
class crearThumb
{

		function __construct($id_disco)
		{
			$this->id_disco=$id_disco;
		}

		
		function getThumb()
		{
		
		
		$file=COVER_PATH . sprintf ('%08s.jpg', $this->id_disco);
		
		header('Content-Type: image/jpeg');
		
		$imagen=imagecreatefromjpeg($file);
		
		imagejpeg($imagen,'',100);	
		
		}
}
*/

$thumb = new crearThumb($_GET['id_disco']);
$thumb->getThumb();

?>