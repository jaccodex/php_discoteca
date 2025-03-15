<?php
/*
actualiza la base de datos con el nombre del pack donde esta el original del CD
*/

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


$path_inicial="e:\\mp3";

$dir_1=opendir($path_inicial); //nivel e:\mp3

while($dir_2=readdir($dir_1))//nivel e:\mp3\pack01
{
	if($dir_2<>'.'&&$dir_2<>'..')
	{
	//$dir_2 -> nombre del pack
	$path_pack=$path_inicial . "\\" . $dir_2;

	//echo "<p>" . $path_pack . "</p>";
	
	$dir_pack=opendir($path_pack);
	
	while($dir_3=readdir($dir_pack))//nivel e:\mp3\pack01\banda
		{
			if($dir_3<>'.'&&$dir_3<>'..'&&$dir_3<>'listado.txt')
			{
				//$dir_3 -> nombre de banda
				$path_banda=$path_pack . "\\" . $dir_3;
				//echo "<p>" . $path_banda . "</p>";
			
				$dir_banda=opendir($path_banda);
				
				$errorDisco=true;
			
				while($dir_4=readdir($dir_banda))//nivel e:\mp3\pack01\banda\disco
					{
					if($dir_4<>'.'&&$dir_4<>'..'&&$dir_4<>'listado.txt')
						{
							//$dir_4-> nombre del disco
							
							$datos=getIdDisco($dir_3, $dir_4);

							$path_disco=$path_banda . "\\" . $dir_4;
							
							if($datos['id_disco']==null)
							{
							$errorDisco=true;
							}
							else{$errorDisco=false;
							}
							
							if(!$errorDisco)
							{
							$nuevo_path= $dir_3 . "_" . $datos['ano'] . "_" . $dir_4;
							echo "<p>carpeta: " . $dir_4 . " -> nueca carpeta: " . $nuevo_path;
							
							chdir($path_banda);
							
							$resultado=rename($dir_4, $nuevo_path);
							
							if($resultado){echo "OK";}else{echo "<b>ERROR!!</b>";}
							
							echo "</p>";
							}

						}
					}
				closedir($dir_banda);
			}
		}
	closedir($dir_pack);
	}

}

closedir($dir_1);

function getIdDisco($banda, $disco)
{

$strCheck="select id_disco, ano from
discos left join bandas on discos.id_grupo=bandas.id_grupo
where UCASE(bandas.grupo)=UCASE(\"" . $banda . "\")
and   UCASE(discos.titulo)=UCASE(\"" . $disco . "\")";

$qCheck=confirm_query($strCheck);

if(mysql_num_rows($qCheck)==0)
{
	$datos['id_disco']=null;
	$datos['ano']=null;
}
else
{
	$datos['id_disco']=mysql_result($qCheck,0,'id_disco');
	$datos['ano']=mysql_result($qCheck,0,'ano');
}


return $datos;

}



?>
</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>
