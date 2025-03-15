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

if (isset($_POST['subir']))
{
	require_once(PATH_CLASSES  . "uploadFile_class.php");
	require_once(PATH_CLASSES  . "uploadImg_class.php");
	
	$upload = new uploadImg($_FILES['cover']);
	$upload->convertFile($_POST['id_disco']);
	
	//una vez generada y subida la imagen al server, generamos registro en tabla covers.
	
	$texto="Actualizacion realizada con exito.";
	$fecha_new=date('Y-m-d H:i:s');	

	$id_disco=$_POST['id_disco'];
	
	//ver si ya existe un registro en tabla covers	
	$strCover="SELECT id_disco FROM covers WHERE id_disco=?";
	
	$db->setQueryString($strCover);

	$db->execSELECT(array('id_disco'=>$id_disco));
	
	if ($db->getNumRows()==0)
	{	//si no existe, agregar registro

            $table='covers';
            $datos=array(
                'id_disco'=>$id_disco,
                'fech_up'=>$fecha_new,
                'fech_mod'=>$fecha_new
                );

            $db->execINSERT($table, $datos);
	}
	else
	{
            $table='covers';
            $datos=array('fech_mod'=>$fecha_new);
            $condicion=array('id_disco'=>$id_disco);

            $db->execUPDATE($table, $datos, $condicion);
	}
	
	?>
	<p>Pulse <a href="DisConFicha.php?id_disco=<?php echo $_POST['id_disco']; ?>">aqui</a> para ir a la ficha del disco.</p>
	<?php

}
else
{
	
	$id_disco=$_GET['id_disco'];

	$strDisco="SELECT discos.titulo, bandas.grupo
	FROM discos, bandas 
	WHERE discos.id_disco=?	AND   discos.id_grupo=bandas.id_grupo";

	$db->setQueryString($strDisco);

	$Disco=$db->execSELECT(array('id_disco'=>$id_disco));
	
?>
<div class="formulario">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">

<fieldset>

<legend>FICHA DE DISCO - Subir Caratula</legend>

<p><label for="grupo">Grupo:</label><span id="grupo" class="texto_fijo"><?php echo $Disco[0]["grupo"]; ?></span></p>
<p><label for="titulo">Titulo:</label><span id="titulo" class="texto_fijo"><?php echo $Disco[0]["titulo"]; ?></span></p>

<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
<input type="hidden" name="id_disco" value="<?php echo $_GET['id_disco']; ?>" />


    
<p><label for="cover">Cover:</label>
<input class="text" type="file" id="cover" name="cover" size="40" maxlength="256" /></p>

</fieldset>
<p>500 K maximo</p>
<p><input class="submit" type="submit" name="subir" value="subir" /></p>

</form>
</div>

</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
}

include(PATH_INCLUDE . "pie.php");
?>
