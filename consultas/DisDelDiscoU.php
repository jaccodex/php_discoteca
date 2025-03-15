<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

require_once(PATH_INCLUDE . "cabecera.php");
?>
</head>
<body>
<?php
require_once(PATH_INCLUDE . "logo.php");
require_once(PATH_INCLUDE . "menu.php");
?>

<div id="content">

<?php

require_once(PATH_INCLUDE . "variables_db_connect.php");
require_once(PATH_CLASSES . 'mysqliDb_class.php');
require_once(PATH_CLASSES . 'mysqliDb_exception_class.php');

require_once(PATH_INCLUDE . "funciones.php");

// recibe id_disco

$db= new mysqliDb();

// borrado de temas
$strTemas="SELECT COUNT(*) as num FROM temas WHERE id_disco=\"" . $_POST['id_disco'] . "\"";
$db->setQueryString($strTemas);
$numTemas = $db->execSELECT();

if($numTemas[0]['num']>0)
{
        $table='temas';
        $datos=array('id_disco'=>$_POST['id_disco']);
	$db->execDELETE($table, $datos);
}

//borrado de caratula
$cover = COVER_PATH . sprintf('%04d',floor($_POST['id_disco']/500)) . '/' . sprintf('%08d',$_POST['id_disco']) . '.jpg';

if (file_exists($cover))
{
    if (!delete_file($cover))
    {	
        echo "<p>No se ha podido borrar la caratula del disco.</p>";
        exit;
    }	
}

//borrado de datos generales
$table='discos';
$datos=array('id_disco'=>$_POST['id_disco']);
$db->execDELETE($table, $datos);

$id_grupo=$_POST['id_grupo'];

?>

<script>
location.href="DisConDiscos.php?id_grupo=<?php echo $id_grupo; ?>";
</script>

</div>
</body>
</html>
