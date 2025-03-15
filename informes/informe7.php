<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

require_once(PATH_INCLUDE . "cabecera.php");
?>
</head>
<body>
<?php
require_once(PATH_INCLUDE . "logo.php");

?>

<div id="content">
<?php

require_once(PATH_INCLUDE . "menu.php");
?>
<div id="main">
<?php

require_once(PATH_INCLUDE . "variables_db_connect.php");
require_once(PATH_CLASSES . 'mysqliDb_class.php');
require_once(PATH_CLASSES . 'mysqliDb_exception_class.php');

$db= new mysqliDb();
?>

<div id="content">

<?php

//comprobacion de tabla covers
//1.- todo registro en covers debe tener una entrada de directorio en COVER_PATH
//2.- todas las entradas de directorio en COVER_PATH deben tener un registro en covers


//1.
$strCheck="select id_disco from covers order by id_disco;";

$db->setQueryString($strCheck);
$discos=$db->execSELECT();

$contador=0;

foreach ($discos as $Disco)
{
	$id=$Disco['id_disco'];

	$destFolder= sprintf('%04d',floor($id/500));
	$fich_cover= COVER_PATH . $destFolder . '/' . sprintf('%08s',$id). ".jpg";

        //echo $fich_cover . '<br/>';

	if(!file_exists($fich_cover))
	{

		$contador++;
		
		echo "<p>" . $id . "--->" . $fich_cover. "--> NO ENCONTRADO</p>";


                /*
                $strDel="delete from covers where id_disco=\"$id\"";
		$db->setQueryString($strCheck);

		if ($db->executeQuery())
		{
			echo "<p>------------------------->BORRADO REGISTRO EN COVERS</p>";
		}
                */
	}

}

if ($contador==0)
{
	echo "<p>No hay registros en covers que no tengan un archivo asociado</p>";
	
}

//2.-------------------------------------

$contador=0;

$dir_nombre=COVER_PATH;
$dir_handle=opendir($dir_nombre);

while (false!==($file_nombre=readdir($dir_handle)))
{
        $subdir_nombre=$dir_nombre . $file_nombre;

        if(is_dir($subdir_nombre)&&$file_nombre!=='.'&&$file_nombre!=='..')
        {

            $subdir_handle=opendir($subdir_nombre);

            while (false!==($subfile_nombre=readdir($subdir_handle)))
            {
                $nombre_con_path=$subdir_nombre . '/' . $subfile_nombre;

                $nombre_completo=explode(".", basename($subfile_nombre));
                $nombre=$nombre_completo[0];

                $id= (int) $nombre;
                $extension = $nombre_completo[1];

                if(is_file($nombre_con_path)&&$extension=="jpg"&&$nombre!=="."&&$nombre!=="..")
                {
                    $strDisco="select id_disco from covers where id_disco=\"$id\"";
                    $db->setQueryString($strDisco);
                    $db->execSELECT();

                    if ($db->getNumRows()==0)
                    {
                            $contador++;
                            echo "<p>Error: Caratula sin registro -->$id";

                            $fecha=getdate(filemtime($nombre_con_path));

                            $ano=sprintf('%04s',$fecha['year']);
                            $mes=sprintf('%02s',$fecha['mon']);
                            $dia=sprintf('%02s',$fecha['mday']);
                            $hora=sprintf('%02s',$fecha['hours']);
                            $minutos=sprintf('%02s',$fecha['minutes']);
                            $segundos=sprintf('%02s',$fecha['seconds']);

                            $fecha_new=$ano . "-" . $mes . "-" . $dia . " $hora:$minutos:$segundos";

                            //echo $id . ' ,' . $fecha_new . ' , ' . $fecha_new . '</p>';

                            
                            $strAdd="insert into covers(id_disco,fech_up,fech_mod) values(\"$id\",\"$fecha_new\",\"$fecha_new\")";
                            $db->setQueryString($strAdd);

                            if ($db->executeQuery())
                            {
                                    echo "-->Agregado";
                            }
                            else
                            {
                                    echo "--> Error al intentar agregar";
                            }
                            echo "</p>\n";

                       
                            

                    }
                }
            }
            closedir($subdir_handle);
        }

}


if ($contador==0)
{
	echo "<p>No hay archivos jpg que no esten registrados en covers</p>";

}
closedir($dir_handle);

?>
</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
include(PATH_INCLUDE . "pie.php");
?>
