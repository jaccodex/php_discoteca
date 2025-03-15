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
	
$strDisco = "SELECT  discos.id_disco, bandas.id_grupo, bandas.grupo, titulo
FROM discos, bandas
WHERE id_disco=\"" . $_GET['id_disco'] . "\" 
AND bandas.id_grupo=discos.id_grupo";

$db->setQueryString($strDisco);
$Disco = $db->execSELECT();

?>
<div class="formulario">

<form action="DisDelDiscoU.php" method="post">

<fieldset>

<input name="id_grupo" type="hidden" id="id_grupo" value="<?php echo $Disco[0]["id_grupo"]; ?>" />
<input name="id_disco" type="hidden" id="id_disco" value="<?php echo $Disco[0]["id_disco"]; ?>" />

<p>
<label for="grupo">Banda:</label>
<span id="grupo" class='texto_fijo'><?php echo $Disco[0]["grupo"]; ?></span>
</p>

<p>
<label for="titulo">Titulo:</label>
<span id="titulo" class='texto_fijo'><?php echo $Disco[0]["titulo"]; ?></span>
</p>
	
</fieldset>

<p>
<input class="submit" name="aceptar" type="submit" id="aceptar" value="Confirmar Borrado de Disco" />
</p>

</form>

</div>

</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
include(PATH_INCLUDE . "pie.php");
?>