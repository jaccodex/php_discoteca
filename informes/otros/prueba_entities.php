<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>

<?php
/*
$path_inicial="E:\\Mis documentos\\mp3\\CD01\\Wang Wen_2010_L And R\\";
$path_destino="E:\\Mis documentos\\mp3\\CD_ordenado\\";

$tema='04 - Ўыmўъ.mp3';

$tema_inicial	=$path_inicial . $tema;
$tema_final		=$path_destino . $tema;

echo 'tema inicial: ' . $tema_inicial . "<br>";
echo 'tema final: ' . $tema_final . "<br>";

if(!file_exists($tema_inicial))
{
	echo 'no existe el archivo: ' . $tema_inicial . '<br>';
	
	$dir_1=opendir($path_inicial);
	
	while(false !== ($disco=readdir($dir_1)))
	{
		
		$file_utf8 = utf8_encode($disco );
		echo $file_utf8 . '<br>';
		
		
		echo basename($disco) . "<br>";
		
	}

	exit;
}

if(copy($tema_inicial, $tema_final))
{
	echo "copia OK";
}
else
{
	echo "error en copia";
}


$cadena_cod=utf8_encode('04 - Ўыmўъ.mp3');

echo 'Codificada: ' . $cadena_cod . "<br>\n";

$cadena_decod=utf8_decode($cadena_cod);

echo 'Decodificada: ' . $cadena_decod;
*/

//$path_inicial="E:/Mis documentos/mp3/CD01/Wang Wen_2010_L And R/";

$path_inicial=dir($_SERVER['DOCUMENT_ROOT'] . "/discoteca/informes/otros/");

//$directorio=dir($path_inicial);

while(false !== ($tema=$path_inicial->read()))
{
	if(is_file($tema))
	{ 
	$tipo='archivo';
	}
	else
	{
		if(is_dir($tema))
		{
		$tipo='directorio';
		}
		else
		{
		$tipo='desconocido';
		}
	}
	echo $tema . '->' . $tipo . "<br>";

}

$path_inicial->close();

?>



</body>
</html>
