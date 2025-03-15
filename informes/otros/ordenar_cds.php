<?php

include($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

include(PATH_INCLUDE . "cabecera.php");
?>
</head>
<body>
<?php
include(PATH_INCLUDE . "logo.php");
include(PATH_INCLUDE . "menu.php");

include(PATH_INCLUDE . "conexion.php");
include(PATH_INCLUDE . "funciones_db.php");
?>

<div id="content">

<?php


$path_inicial="E:\Mis documentos\mp3\CD01";
$path_destino="E:\Mis documentos\mp3\CD_ordenado";

$dir_1=opendir($path_inicial); //nivel E:\Mis documentos\mp3\CD01

while($disco=readdir($dir_1))//nivel e:\mp3\disco en origen
{
	if($disco<>'.'&&$disco<>'..')
	{
		$partes=explode('_',$disco);
		
		$inicial=strtoupper(substr($partes[0],0,1));
		
		$destino1=$path_destino . "\\" . $inicial;
		
		//echo "<p>" . $inicial . "->" . $destino1 . "->";
		
		if (!file_exists($destino1))//crear directorio de destino con inicial de la banda
		{
			if(!mkdir($destino1))
			{ 
			echo "- No se ha podido crear el directorio: " . $destino1 . "<br>";
			exit;
			}
		}

		$banda=ucwords($partes[0]);
		
		$destino2=$destino1 . "\\" . $banda;
		
		
		if (!file_exists($destino2))//crear directorio de destino con nombre de la banda
		{
			if(!mkdir($destino2))
			{ 
			echo "- No se ha podido crear el directorio: " . $destino2 . "<br>";
			exit;
			}
		}
		
		
		$destino3=$destino2 . "\\" . $disco;
		
		if (!file_exists($destino3))//crear directorio de destino con disco
		{
			if(!mkdir($destino3))
			{ 
			echo "- No se ha podido crear el directorio: " . $destino3 . "<br>";
			exit;
			}
		}		
		
		/*
		if($inicial=='W')
		{
			$path_disco_origen=$path_inicial . "\\" . $disco;
			$path_disco_destino=$destino3;
			
			$dir_2=opendir($path_disco_origen); 
			
			while($temadisco=readdir($dir_2))//copiado de temas
			{
				if($temadisco<>'.'&&$temadisco<>'..')
				{
					$temadisco_origen_con_path=$path_disco_origen . "\\" . $temadisco;
					$temadisco_destino=$path_disco_destino . "\\" . $temadisco;		
					
					if (!rename($temadisco_origen_con_path, $temadisco_destino))
					{
						echo "- No se ha podido mover: " . $temadisco_origen_con_path . "<br>";
					}
				}
			}
			
			closedir($dir_2);
			
			if(!rmdir($path_disco_origen))
			{
				echo " - No se ha podido borrar la carpeta: " . $path_disco_origen . "<br>";
			}
			else
			{
				echo " - Se ha borrado la carpeta: " . $path_disco_origen . "<br>";			
			}
		}
		
		
		//echo "</p>";
		
		*/
	}

}

closedir($dir_1);

?>
</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>
