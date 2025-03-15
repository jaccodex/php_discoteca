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

$db= new mysqliDb();

if (isset($_POST['agregar']))
{
	// si se pulsa el boto agregar
	// recibe id_disco, numero, titulo, hh,mm y ss
	
	if ($_POST['hh']==null){$txt_hh="00";}else{$txt_hh=$_POST['hh'];}
	if ($_POST['mm']==null){$txt_mm="00";}else{$txt_mm=$_POST['mm'];}
	if ($_POST['ss']==null){$txt_ss="00";}else{$txt_ss=$_POST['ss'];}

	$dur=$txt_hh . ":" . $txt_mm . ":" . $txt_ss;

	$table='temas';
        $datos=array(
            'numero'=>$_POST['numero'],
            'titulo'=>ucwords(trim($_POST['titulo'])),
            'duracion'=>$dur
        );
        $condicion=array('id_tema'=>$_POST['id_tema']);

        $db->execUPDATE($table, $datos, $condicion);
	
        ?>
        <p>Actualizacion realizada con exito</p>
        <p>Pulse <a href="DisMFicha1.php?id_disco=<?php echo $_POST['id_disco']; ?>">aqui</a> para volver al tracklist.</p>
        <?php
}
else
{

$strDisco = "SELECT  id_disco, id_grupo, titulo FROM discos WHERE id_disco=" . $_GET['id_disco'];
$db->setQueryString($strDisco);
$Disco = $db->execSELECT();

$strGrupo="SELECT grupo as grupo FROM bandas WHERE id_grupo=\"" . $Disco[0]["id_grupo"] . "\"";
$db->setQueryString($strGrupo);
$Grupo=$db->execSELECT();

$strTema="SELECT numero, titulo, hour(duracion) as hh, minute(duracion) as mm, second(duracion) as ss FROM temas WHERE id_tema=\"" . $_GET['id_tema'] . "\"";
$db->setQueryString($strTema);
$Tema=$db->execSELECT();

		
?>

<div class="formulario">

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<fieldset>	

<legend>FICHA DE DISCO - Modificar  Tema</legend>

<input type="hidden" name="id_tema" value="<?php echo $_GET['id_tema']; ?>" />
<input type="hidden" name="id_disco" value="<?php echo $_GET['id_disco']; ?>" />

    <p>
    <label for="id_grupo">Grupo:</label>
	<span class="texto_fijo" id="id_grupo"><?php echo $Grupo[0]['grupo']; ?></span>
    </p>
    
    <p>
    <label for="disco">Disco:</label>
    <span class="texto_fijo" id="disco"><?php echo $Disco[0]['titulo']; ?></span>
    </p>

    <p>
    <label for="numero">Numero:</label>
	<input class="text muyCorto" name="numero" type="text" id="numero" size="3" maxlength="3" value="<?php echo $Tema[0]["numero"]; ?>"/>
    </p>

    <p>
    <label for="titulo">Titulo:</label>
	<input class="text" name="titulo" type="text" id="titulo" size="50" maxlength="50" value="<?php echo $Tema[0]["titulo"]; ?>" />
    </p>
	
    <p>
    <label for="duracion">Duraci&oacute;n:</label>
	<input class="text muyCorto" name="hh" type="text" id="hh" value="<?php echo $Tema[0]["hh"]; ?>" size="5" maxlength="2" /><span class="texto_fijo">HH</span>
    <input class="text muyCorto" name="mm" type="text" id="mm" value="<?php echo $Tema[0]["mm"]; ?>" size="5" maxlength="2" /><span class="texto_fijo">MM</span>
    <input class="text muyCorto" name="ss" type="text" id="ss" value="<?php echo $Tema[0]["ss"]; ?>" size="5" maxlength="2" /><span class="texto_fijo">SS</span>
    </p>

	</fieldset>
	
  <p><input type="submit" class="submit" name="agregar" type="submit" id="agregar" value="Modificar" /></p>
  
</form>

</div>
<?php
}
?>
</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>