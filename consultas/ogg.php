<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");
require_once(PATH_INCLUDE . "variables_db_connect.php");
require_once(PATH_CLASSES . 'mysqliDb_class.php');

$db= new mysqliDb();
$strCheck="SELECT 
bandas.grupo, 
discos.titulo, 
temas.titulo,
temas.duracion,
temas.tamano,
temas.bitrate, 
temas.fullpath 
FROM temas, bandas, discos 
WHERE id_tema=\"" . $_POST['id_tema'] . "\" 
AND discos.id_disco=temas.id_disco
AND discos.id_grupo=bandas.id_grupo";

$db->setQueryString($strCheck);
$info=$db->execSELECT();

/*
echo '<table>';
foreach ($info[0] as $clave => $valor){
	echo '<tr><td>' . $clave . '</td><td>' . $valor . '</td></tr>';
}
echo '</table>';
*/

if(file_exists($info[0]['fullpath'])){

	//echo '<p>Existe el archivo</p>';
	
	$dest_mp3=$_SERVER['DOCUMENT_ROOT'] . "\\tmp\\" . $_POST['id_tema'] . ".mp3";
	
	if(!copy($info[0]['fullpath'], $dest_mp3)){
		echo '<p>No se ha podido copiar el archivo de ' . $info[0]['fullpath'] . ' a :' . $dest_mp3 . '</p>';
	}
	else{
		$orig_mp3=$_SERVER['DOCUMENT_ROOT'] . "\\tmp\\" . $_POST['id_tema'] . ".mp3";
		$dest_ogg=$_SERVER['DOCUMENT_ROOT'] . "\\tmp\\" . $_POST['id_tema'] . ".ogg";
		
		$exc_string=".\\..\\include\\ffmpeg\\bin\\ffmpeg.exe -y -i {$orig_mp3} -acodec libvorbis {$dest_ogg}";
		$retCode = exec($exc_string);
		
		echo "<p><audio controls>";
		//echo "<source src=\".\\..\\tmp\\{$_POST['id_tema']}.ogg\" type=\"audio/ogg>\">";
		echo "<source src=\".\\..\\tmp\\{$_POST['id_tema']}.ogg \">";
		echo "</audio></p>";
	}

}
else{
	echo '<p>No Existe el archivo</p>';
}




?>