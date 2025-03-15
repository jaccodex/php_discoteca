<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<?php
@date_default_timezone_set("GMT"); 

$writer = new XMLWriter(); 

$writer->setIndent(true);
$writer->setIndentString('  ');

$writer->openURI('./xml_ouput.xml');
$writer->startDocument('1.0', 'UTF-8'); 

$writer->startElement('discos'); 


$path_1="E:\Mis documentos\mp3\CD_ordenado";
$dir_1=opendir($path_1); 

while($abrev=readdir($dir_1))//nivel abreviatura de banda
{
	if($abrev<>'.'&&$abrev<>'..')
	{
	
		$path_2=$path_1 . "\\" . $abrev;
		$dir_2=opendir($path_2); 

		while($banda=readdir($dir_2))//nivel de banda
		{
			if($banda<>'.'&&$banda<>'..')
			{
				$path_3=$path_2 . "\\" . $banda;
				$dir_3=opendir($path_3);
				
				while($disco=readdir($dir_3))//nivel de disco
				{				 
					if($disco<>'.'&&$disco<>'..')
					{			
						$datos=explode('_', $disco);
						
						$writer->startElement('disco'); 
						
						$writer->writeElement('grupo', htmlentities($datos[0])); 
						$writer->writeElement('ano',   $datos[1]); 						
						$writer->writeElement('titulo', htmlentities($datos[2])); 
						
						$writer->endElement();// fin de disco
					}
				}
				closedir($dir_3);
			}
		}
		closedir($dir_2);

	}

}

closedir($dir_1);

$writer->endElement();// fin de discos

$writer->endDocument();

$writer->flush(); 

?>
</body>
</html>
