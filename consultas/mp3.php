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
$origen_mp3=str_replace('\\','\\\\',$info[0]['fullpath']);
//echo $origen_mp3;

if(file_exists($origen_mp3)){

	/*
         * echo '<p>Existe el archivo</p>';
         
	
	$dest_mp3=$_SERVER['DOCUMENT_ROOT'] . "/tmp/" . $_POST['id_tema'] . ".mp3";

	
	if(!copy($info[0]['fullpath'], $dest_mp3)){
		echo '<p>No se ha podido copiar el archivo de ' . $info[0]['fullpath'] . ' a :' . $dest_mp3 . '</p>';
	}
	else{
		echo "<p><audio controls autoplay>";
		echo "<source src=\".\\..\\tmp\\{$_POST['id_tema']}.mp3 \">";
		//echo "<source src=\"file:///{$dest_mp3}\">";
		echo "</audio></p>";
	}

        */
  		echo "<p><audio controls autoplay>";
		echo "<source src=\"". $origen_mp3 . "\"" . " type=\"audio/mpeg\">";
		//echo "<source src=\"file:///{$dest_mp3}\">";
		echo "</audio></p>";  
    
}
else{
	echo '<p>No Existe el archivo</p>';
}




?>