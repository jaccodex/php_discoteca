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

if (isset($_POST['subir']))
{

    require_once(PATH_CLASSES  . "uploadFile_class.php");
    require_once(PATH_CLASSES  . "uploadXML_class.php");

    $upload = new uploadXML($_FILES['xml_file']);

    $upload->saveResult();


    ?>
    <p>El archivo se ha subido con exito.</p>
    <p>Pulse <a href="/utilities/import_xml/importar_xml.php">aqui</a> para procesar el archivo subido.</p>
    <?php

}
else
{

?>

<div class="formulario">

<fieldset>
<legend>Seleccione el archivo XML a importar</legend>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >

<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo FORMS_MAX_FILE_SIZE * 1000000;?>"/>
<input class="text largo" type="file" name="xml_file" size="40" maxlength="256" />

</fieldset>
<p><input class="submit" type="submit" name="subir" value="Subir" /></p>

</form>
</div>
<?php
}

include(PATH_INCLUDE . "pie.php");
?>
