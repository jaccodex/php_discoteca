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
	$dir_pack=opendir($path_pack);
	
	while($dir_3=readdir($dir_pack))//nivel e:\mp3\pack01\banda
		{
			if($dir_3<>'.'&&$dir_3<>'..'&&$dir_3<>'listado.txt')
			{
				//$dir_3 -> nombre de banda
				$path_banda=$path_pack . "\\" . $dir_3;
				$dir_banda=opendir($path_banda);
				
				while($dir_4=readdir($dir_banda))//nivel e:\mp3\pack01\banda\disco
					{
					if($dir_4<>'.'&&$dir_4<>'..'&&$dir_4<>'listado.txt')
						{
							//$dir_4-> banda_ano_titulo
							
							$partes=explode('_',$dir_4);
							
							/*
							$partes[0] -> banda
							$partes[1] -> ano
							$partes[2] -> titulo
							*/
							
							echo "<p><b>" . $partes[0] . "->" . $partes[1] . "->" . $partes[2] . "</b></p>";
							
							$path_disco=$path_banda . "\\" . $dir_4;
							$dir_disco=opendir($path_disco);
							
							$datos=getIdDisco($partes[0], $partes[2]);
							
							$contador=0;

							while($dir_5=readdir($dir_disco))//nivel e:\mp3\pack01\banda\disco\tema
								{
								if($dir_5<>'.'&&$dir_5<>'..'&&$dir_5<>'listado.txt')
									{	++$contador;
										
										echo "<p>" .sprintf('%02d',$contador)  . "->" . $dir_5;
										
										$nuevoTitulo=checkTitulo($dir_5, $datos['id_disco'], $contador);
																				
										echo "->" . $nuevoTitulo . "->";
										
										
										chdir($path_disco);
										
										$resultado=rename($dir_5, $nuevoTitulo);
										
										if($resultado)
										{
											echo "OK";
										}
										else
										{
											echo "ERROR";
										}
										
										echo "</p>";
			
									}
								}

							closedir($dir_disco);


						}
					}
				closedir($dir_banda);
			}
		}
	closedir($dir_pack);
	}

}

closedir($dir_1);

function checkTitulo($viejoTitulo, $id_disco, $micontador)
{

	$encontrado=false;
	
	//si xx.spcTrackspcxx.mp3
	if(substr($viejoTitulo,2,7)=='. Track')
	{
		$nuevoTitulo=sprintf('%02d',$micontador) . "_" . getTitulo($id_disco, $micontador) . ".mp3";
		$encontrado=true;
	}

	//si Track.mp3
	if(substr($viejoTitulo,0,5)=='Track')
	{
		$nuevoTitulo=sprintf('%02d',$micontador) . "_" . getTitulo($id_disco, $micontador) . ".mp3";
		$encontrado=true;
	}	
	
	//si xxspc-spcPistaspcxx.mp3
	if(substr($viejoTitulo,2,8)==' - Pista')
	{
		$nuevoTitulo=sprintf('%02d',$micontador) . "_" . getTitulo($id_disco, $micontador) . ".mp3";
		$encontrado=true;
	}
	//si xx.spc<titulo>.mp3
	if(!$encontrado&&substr($viejoTitulo,2,2)=='. ')
	{
		$nuevoTitulo=substr_replace($viejoTitulo,"_",2,2);
		$encontrado=true;
	}

	//si xxspc-spc<titulo>.mp3
	if(!$encontrado&&substr($viejoTitulo,2,3)==' - ')
	{
		$nuevoTitulo=substr_replace($viejoTitulo,"_",2,3);
		$encontrado=true;		
	}

	if(!$encontrado)
	{
		$nuevoTitulo=$viejoTitulo;

	}
	
	
	$nuevoTitulo=substr($nuevoTitulo,0,3) . prepareTitulo(substr($nuevoTitulo,3));

	return $nuevoTitulo;
}

function prepareTitulo($titulo)
{
	$titulo=trim(ucwords(strtolower($titulo)));
	$titulo=str_replace(':','_',$titulo);
	$titulo=str_replace('/','-',$titulo);
	$titulo=str_replace('{','(',$titulo);
	$titulo=str_replace('}',')',$titulo);	
	
	return $titulo;
}

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

function getTitulo($id_disco, $numero)
{

$strCheck="select titulo from temas 
where id_disco=\"" . $id_disco . "\"
and   numero=\"" . $numero . "\"";

$qCheck=confirm_query($strCheck);

$titulo=mysql_result($qCheck,0,'titulo');
if(!$titulo)
{

}

return $titulo;

}


?>
</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>
