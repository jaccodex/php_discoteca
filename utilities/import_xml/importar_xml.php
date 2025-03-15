<?php
include($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

include(PATH_INCLUDE . "cabecera.php");
?>
</head>
<body>
<?php

include(PATH_INCLUDE . "logo.php");
include(PATH_INCLUDE . "menu.php");

?>
<div id="content">

<?php
ini_set('max_execution_time', 180);

$xml_file = XML_PATH . "import.xml";

require_once(PATH_CLASSES  . "log_class.php");
require_once(PATH_INCLUDE . "variables_db_connect.php");

//importamos el xml a la tabla dvd_import
$xml_object=  simplexml_load_file($xml_file);

$db=new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
$db->set_charset('utf8');

$miLog = new log(XML_PATH);


$strQuery='TRUNCATE TABLE dvd_import';
$result=$db->query($strQuery);
if(!$result)
{
    echo 'error:' . $db->error;
    exit;
}

$strQuery='INSERT INTO dvd_import(duration_in_seconds, artist, album, title, year, track) VALUES (?,?,?,?,?,?)';
$stmt=$db->prepare($strQuery);
$stmt->execute();

foreach($xml_object->media_file as $tema)
{
    $stmt->bind_param('ssssss', $tema->duration_in_seconds, $tema->artist, $tema->album, $tema->title, $tema->year, $tema->track);
    $stmt->execute();
}

//importar datos

$nombre_banda='';
$nombre_disco='';

$strQuery='SELECT duration_in_seconds, artist, album, title, year, track FROM dvd_import';
$stmt=$db->prepare($strQuery);
$stmt->execute();
$stmt->bind_result($duration_in_seconds, $artist, $album, $title, $year, $track);

$results=array();

while($stmt->fetch())
{  
    $results[]=array(
        'duration_in_seconds'=>$duration_in_seconds, 
        'artist'=>$artist, 
        'album'=>$album, 
        'title'=>$title, 
        'year'=>$year, 
        'track'=>$track
    );
    
}

$stmt->close();

foreach($results as $result)
{  
    $banda=trim(strtoupper($result['artist']));
    $nueva_banda=false;
    if(!empty($banda))
    {
        if ($banda<>$nombre_banda)
        {
            $nombre_banda=$banda;
            $nueva_banda=true;
            $id_grupo=agregarBanda($miLog, $db, $banda);

            if(is_null($id_grupo))
            {
                echo "<p class='error'>Se ha producido un error grave al intentar agregar la banda {$banda}.</p>";
                echo "<p class='error'>El proceso de importacion de datos ha sido abortado</p>";
                die();
            }
        }

        
        //procesar album

        $album=trim($result['album']);
        $ano=$result['year'];

        if($album<>$nombre_disco||$nueva_banda)
        {
            $nombre_disco=$album;
            $id_disco=agregarAlbum($miLog, $db, $id_grupo, $album, $ano);

            if(is_null($id_disco))
            {
                echo "<p class='error'>Se ha producido un error grave al intentar agregar el disco {$album} de la banda {$banda}.</p>";
                echo "<p class='error'>El proceso de importacion de datos ha sido abortado</p>";
                
                die();
            }

        }
        
        //procesar tema

        $titulo=trim($result['title']);
        
        if($titulo=='')
        {
            ////si el campo title esta vacio en el XML uso el campo name
            $titulo=$name;
        }

        //Quito la extension .mp3 y .MP3 del final del titulo
        preg_replace('/(\w+)((.mp3)|(.MP3))/','$1',$titulo);

        if(preg_match('/^\d{2}_/', $titulo))
        {
            // si es del modelo '01_Titulo de la cancion'
            $n_tema= (int) substr($titulo,0,2);
            $tema = substr($titulo,3);
        }
        elseif(preg_match('/^\d{3}_/', $titulo))
        {
            // si es del modelo '001_Titulo de la cancion'
            $n_tema= (int) substr($titulo,0,3);
            $tema = substr($titulo,4);                    
        }               
        elseif(preg_match('/^\d{2}\s/', $titulo))
        {
            // si es del modelo '01 Titulo de la cancion'
            $n_tema= (int) substr($titulo,0,2);
            $tema = substr($titulo,3);                   
        }                  
        elseif(preg_match('/^\d{3}\s/', $titulo))
        {
            // si es del modelo '001 Titulo de la cancion'
            $n_tema= (int) substr($titulo,0,3);
            $tema = substr($titulo,4);                    
        }
        elseif(preg_match('/^CD\d{2}_\s/', $titulo))
        {
            // si es del modelo 'CD01_01_Titulo de la cancion'
            $n_tema= (int) substr($titulo,5,2);
            $tema = substr($titulo,8);                    
        }

        elseif(preg_match('/^\w/', $titulo))
        {
            // si es del modelo 'Titulo de la cancion'
            $tema=$titulo;
            $n_tema=(int) trim($result['track']);
            
            if(is_null($n_tema)||$n_tema==0)
            {
                //si no se ha podido obtener el numero de tema se calcula el siguiente
                $strQuery='SELECT MAX(numero) as maxNum FROM temas WHERE id_disco=?';
                $stmtMaxID=$db->prepare($strQuery);
                $stmtMaxID->bind_param('i', $id_disco);
                $stmtMaxID->execute();
                $stmtMaxID->bind_result($ntema);
                $stmtMaxID->fetch();
                
                if(is_null($n_tema)||$n_tema==0)
                {
                    $n_tema=0;
                }

                $n_tema++;
            }                   
        }

        //$tema=addslashes(trim($tema));

        $duracion=calcularDuracion($result['duration_in_seconds']);

        $agregar=agregarTema($miLog, $db, $id_disco, $tema, $n_tema, $duracion);

        if(!$agregar)
        {
            echo "<p class='error'>Se ha producido un error grave al intentar agregar el tema {$tema} del disco {$album} de la banda {$banda}.</p>";
            echo "<p class='error'>El proceso de importacion de datos ha sido abortado</p>";
            die();
        }

    }
    
}

//archivar el xml importado
$new_xml_file = XML_PATH . '/xml/' . date('Y-m-d') . '-' . 'import.xml';
//borrar archivo importado

rename($xml_file, $new_xml_file);
//unlink($xml_file);
     

function agregarBanda($miLog, $db, $banda)
{
    //comprobar si la banda está en la BD
    //en caso de que no esté, agregarla
    //devuelve el id_banda;

    $miLog->registrarLineaLog("Banda:{$banda}", false);
    
    $strCheckBanda="SELECT id_grupo FROM bandas WHERE grupo=?";
    $stmtAg=$db->prepare($strCheckBanda);
    
    $stmtAg->bind_param('s', $banda);
    $stmtAg->execute();
    $stmtAg->store_result();
        
    if( $stmtAg->num_rows == 0)
    {       
        //nueva banda
        $stmtAg->close();
        $strAddIdGrupo="INSERT INTO bandas (grupo) VALUES(?)";
        $stmtIn=$db->prepare($strAddIdGrupo);
       
        $stmtIn->bind_param('s', $banda);

        if(!$stmtIn->execute())
        {
            $id_grupo=NULL;
            $miLog->registrarLineaLog("-> NO SE HA PODIDO AGREGAR!!!");
            $miLog->registrarLineaLog($strAddIdGrupo);
        }
        else
        {
            $id_grupo=$stmtIn->insert_id;
            $miLog->registrarLineaLog("->  AGREGADO:{$id_grupo}");
        }
        $stmtIn->close();
    }
    else
    {   //ya existe
        $stmtAg->bind_result($id_grupo);
        $stmtAg->fetch();
        $miLog->registrarLineaLog("->  YA EXISTE:{$id_grupo}");
        $stmtAg->close();
     }

    return $id_grupo;
}

function agregarAlbum($miLog, $db, $id_grupo, $album, $ano)
{
    //comprobar si el album esta en la BD
    //en caso de que no esta, agregarlo
    //devuelve el id_disco;

    $miLog->registrarLineaLog("Disco:{$album}" . " ," .  "{$ano}", false);

    $strCheckDisco="SELECT id_disco FROM discos WHERE id_grupo=? AND titulo=?";

    $stmtAg=$db->prepare($strCheckDisco);
    $stmtAg->bind_param('is', $id_grupo, $album);
    $stmtAg->execute();
    $stmtAg->bind_result($id_disco);
    $stmtAg->store_result();
    $stmtAg->fetch();
    
    if($stmtAg->num_rows == 0)
    {//nuevo disco

        $strAddDisco="INSERT INTO discos(id_grupo, titulo, ano, fech_add) VALUES(?,?,?,NOW())";

        $stmtIn=$db->prepare($strAddDisco);
        $stmtIn->bind_param('isi', $id_grupo, $album, $ano);

        if(!$stmtIn->execute())
        {
            $id_disco=NULL;
            $miLog->registrarLineaLog("-> NO SE HA PODIDO AGREGAR!!!");
            $miLog->registrarLineaLog($stmtIn->error);
        }
        else
        {
            $id_disco=$stmtIn->insert_id;
            $miLog->registrarLineaLog("->  AGREGADO:{$id_disco}");
        }
        
        $stmtIn->close();
    }
    else
    {//ya existe
        
        $miLog->registrarLineaLog("->  YA EXISTE:{$id_disco}");
    }

    $stmtAg->close();
    
    return $id_disco;

}

function agregarTema($miLog, $db, $id_disco, $tema, $n_tema, $duracion)
{
    //comprobar si el tema esta en la BD
    //en caso de que no este, agregarlo
    //si esta se sustituye;

    $miLog->registrarLineaLog("Tema:{$n_tema}"  . " - " . "{$tema}" . " -" .  "{$duracion}", false);

    //se comprueba si ya existe
    $strCheckTema="SELECT id_tema, titulo, duracion FROM temas WHERE id_disco=? AND numero=?";
    
    $stmtAg=$db->prepare($strCheckTema);
    $stmtAg->bind_param('ii', $id_disco, $n_tema);
    $stmtAg->execute();
    $stmtAg->store_result();
    
    if($stmtAg->num_rows == 0)
    {
        //nuevo tema
        
        $agregar=nuevoTema($miLog, $db, $id_disco, $n_tema, $tema, $duracion);

    }
    else
    {
        //si  ya existe se comprueba si tiene grabada la duracion o el titulo
        //si no la tiene se actualizan
        $stmtAg->bind_result($id_tema, $titulo, $duracion);
        $stmtAg->fetch();
        //$miLog->registrarLineaLog("id_tema->{$id_tema}, titulo->{$titulo}, duracion->{$duracion}");
        
        if(empty($titulo)||empty($duracion))
        {
            //$duracion_format=calcularDuracion($duracion);

            $strUpdate="UPDATE temas SET titulo=?, duracion=? WHERE id_disco=? AND numero=?";
            $stmtU=$db->prepare($strUpdate);
            $stmtU->bind_param('ssii', $tema, $duracion, $id_disco, $n_tema);
            
              
            if(!$stmtU->execute())
            {
                $miLog->registrarLineaLog("-> NO SE HA PODIDO ACTUALIZAR!!!");
                $miLog->registrarLineaLog($stmU->error);
                $agregar=false;
            }
            else
            {
                $miLog->registrarLineaLog("-> ACTUALIZADO:{$n_tema}");
                $agregar=true;
            }

            $stmtU->close();
        }
        else
        {
            $agregar=true; //no se hace nada
            $miLog->registrarLineaLog("-> NO SE HACE NADA:{$n_tema}");
        }

    }
    
    $stmtAg->close();
    
    return $agregar;

}


function nuevoTema($miLog, $db, $id_disco, $numero, $titulo, $duracion)
{
       
    $strAddTema="INSERT INTO temas (id_disco, numero, titulo, duracion)
    VALUES(?, ?, ?, ?)";
    $stmtIn=$db->prepare($strAddTema);
    $stmtIn->bind_param('iiss', $id_disco, $numero, $titulo, $duracion);

    if(!$stmtIn->execute())
    {
        $miLog->registrarLineaLog("-> NO SE HA PODIDO AGREGAR!!!");
        $miLog->registrarLineaLog($stmIn->error);
        return false;
    }
    else
    {
        $miLog->registrarLineaLog("-> AGREGADO:{$numero}");
        $miLog->registrarLineaLog("id_disco->{$id_disco}");
        return true;
    }
}

function calcularDuracion($duration_in_seconds)
{

    //recibe duracion en segundos
    //devuelve duracion en formato hh:mm:ss

    $num_horas= floor($duration_in_seconds/3600);
    $resto_min = $duration_in_seconds % 3600; //resto de la division
    $num_minutos=floor($resto_min/60);
    $resto_seg = $resto_min % 60; //resto de la division

    $duracion = sprintf('%02d', $num_horas) . ":"
    . sprintf('%02d',$num_minutos) . ":"
    . sprintf('%02d',$resto_seg);

    return $duracion;
}

?>

<p>Proceso de importacion completado</p>



