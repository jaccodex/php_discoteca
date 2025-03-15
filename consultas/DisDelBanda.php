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

if (isset($_POST['aceptar']))
	{

        $table='bandas';
        $datos=array('id_grupo'=>$_POST['id_grupo']);
	$db->execDELETE($table, $datos);
	
        ?>
        <p>Actualizacion realizada con exito</p>
        <p>Pulse <a href="DisConBandas.php?i=<?php echo substr($_POST['grupo'],0,1); ?>">aqui</a> para volver a la lista de bandas.</p>
        <?php
	}
	else
	{

	$strBanda = "SELECT grupo FROM bandas WHERE id_grupo=\"" . $_GET['id_grupo'] ."\"";
	$db->setQueryString($strBanda);
	
	$Banda=$db->execSELECT();

	?>
	<div class="formulario">
	
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
	
	<fieldset>

	<input type="hidden" name="grupo" value="<?php echo $Banda[0]['grupo'];?>" />
	<input type="hidden" name="id_grupo" value="<?php echo $_GET['id_grupo'];?>" />
    
	<p>
	<label for="grupo">Banda:</label>
	<span id="grupo" class='texto_fijo'><?php echo $Banda[0]["grupo"]; ?></span>
	</p>

	</fieldset>

	<p>
	<input class="submit" name="aceptar" type="submit" id="aceptar" value="Confirmar Borrado de Banda" />
	</p>

	</form>
	
	</div>
	
	<?php
	}
?>
</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
include(PATH_INCLUDE . "pie.php");
?>