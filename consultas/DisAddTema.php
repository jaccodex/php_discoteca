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
	

        $numero=(int)$_POST['numero'];
        
        if($numero==0||is_null($numero))
	{

		$strMaxNum="select max(numero) as maxNum from temas where id_disco=" . $_POST['id_disco'];
		$db->setQueryString($strMaxNum);
		$results=$db->execSELECT();
		
		$MaxNum=$results[0]['maxNum'];
		
		if ($MaxNum==null||$MaxNum==0)
                {
                    $numero=1;
                }
                else
                {
                    $numero=$MaxNum+1;
                }
	}
        
	if ($_POST['hh']==null){$txt_hh="00";}else{$txt_hh=$_POST['hh'];}
	if ($_POST['mm']==null){$txt_mm="00";}else{$txt_mm=$_POST['mm'];}
	if ($_POST['ss']==null){$txt_ss="00";}else{$txt_ss=$_POST['ss'];}

        $titulo=ucwords(trim(filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING)));

	$dur=$txt_hh . ":" . $txt_mm . ":" . $txt_ss;

        $table='temas';
        $datos=array(
            'id_disco'=>$_POST['id_disco'],
            'numero'=>$numero,
            'titulo'=>$titulo,
            'duracion'=>$dur
            );
	
        $db->execINSERT($table, $datos);	

        ?>
        <script>
        location.href="DisMFicha1.php?id_disco=<?php echo $_POST['id_disco']; ?>";
        </script>

        <?php

}
else
{

$strDisco = "SELECT id_disco, id_grupo, titulo FROM discos WHERE id_disco=" . $_GET['id_disco'];
$db->setQueryString($strDisco);
$Disco=$db->execSELECT();

$strGrupo="SELECT grupo FROM bandas WHERE id_grupo=\"" . $Disco[0]["id_grupo"] . "\"";
$db->setQueryString($strGrupo);
$Grupo=$db->execSELECT();
		
?>

<div class="menuHorizontal">
<ul>
<li><a href="DisMFicha.php?id_disco=<?php echo $_GET['id_disco']; ?>">Volver a Ficha</a></li>
</ul>
</div>

<div class="formulario">

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<fieldset>
<legend>FICHA DE DISCO - Agregar Tema</legend>	

<input type="hidden" name="id_disco" value="<?php echo $_GET['id_disco']; ?>" />


    <p>
    <label for="id_grupo">Grupo:</label>
	<span class="dato" id="id_grupo"><?php echo $Grupo[0]['grupo']; ?></span>
    </p>
    
    <p>
    <label for="titulo">Titulo:</label>
    <span class="dato" id="titulo"><?php echo $Disco[0]['titulo']; ?></span>
    </p>
	
    <p>
    <label for="numero">Numero:</label>
	<input name="numero" type="text" class="text muyCorto" id="numero" size="3" maxlength="3" value=""/>
    </p>

    <p>
    <label for="titulo">Titulo:</label>
	<input name="titulo" type="text" class="text largo" id="titulo" size="50" maxlength="50" value="" />
    </p>

    <p>
    <label>Duraci&oacute;n:</label>
	<input name="hh" type="text" class="text muyCorto" id="hh" value="00" size="5" maxlength="2" />
    <input name="mm" type="text" class="text muyCorto" id="mm" value="00" size="5" maxlength="2" />
    <input name="ss" type="text" class="text muyCorto" id="ss" value="00" size="5" maxlength="2" />
    </p>

	</fieldset>
	
  <p>
  <input class="submit" type="submit" name="agregar"  id="agregar" value="Agregar" />
  </p>
  

</form>

</div>
<?php
}
?>
</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>