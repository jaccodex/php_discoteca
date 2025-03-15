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

if (isset($_POST['borrar']))
{
	// si se pulsa el boton Borrar agregar
	// recibe id_tema, id_disco
	
        $table='temas';
        $datos=array('id_tema'=>$_POST['id_tema']);
	$db->execDELETE($table, $datos);

        ?>
        <script>
        location.href="DisMFicha1.php?id_disco=<?php echo $_POST['id_disco']; ?>";
        </script>
        <?php

}
else
{

$strTema="SELECT 
numero, 
titulo, 
hour(duracion) as hh, 
minute(duracion) as mm, 
second(duracion) as ss 
FROM temas WHERE id_tema=" . $_GET['id_tema'];

$db->setQueryString($strTema);
$Tema=$db->execSELECT();

$strDisco = "SELECT  discos.id_disco, discos.id_grupo, bandas.grupo, discos.titulo 
FROM discos, bandas WHERE discos.id_disco=" . $_GET['id_disco'] . " AND discos.id_grupo=bandas.id_grupo";

$db->setQueryString($strDisco);
$Disco = $db->execSELECT();

?>

<div class="formulario">

<h1>FICHA DE DISCO - Borrar  Tema</h1>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<input type="hidden" name="id_tema" value="<?php echo $_GET['id_tema']; ?>" />
<input type="hidden" name="id_disco" value="<?php echo $_GET['id_disco']; ?>" />

<fieldset>
    <p>
    <label for="id_grupo">Grupo:</label>
    <span id='id_grupo'><?php echo $Disco[0]['grupo']; ?></span>
    </p>
    
    <p>
    <label for="titulo">Titulo:</label>
    <span id='titulo'><?php echo $Disco[0]['titulo']; ?></span>
    </p>

    <p>
    <label for="numero">Numero:</label>
    <span id='numero'><?php echo $Tema[0]['numero']; ?></span>
    </p>

    <p>
    <label for="titulo_tema">Titulo:</label>
    <span id='titulo_tema'><?php echo $Tema[0]['titulo']; ?></span>
    </p>
	
    <p>
    <label for="duracion">Duraci&oacute;n:</label>
    <span><?php echo $Tema[0]['hh']; ?></span>
    <span><?php echo $Tema[0]['mm']; ?></span>
    <span><?php echo $Tema[0]['ss']; ?></span>
    </p>

</fieldset>

  <p><input class="submit" name="borrar" type="submit" id="borrar" value="Confirmar Borrado" /></p>
  
</form>
</div>
<?php
}
?>
</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>