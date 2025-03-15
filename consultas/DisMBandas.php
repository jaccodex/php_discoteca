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
	
        $grupo=addslashes(filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_STRING));

	    $table='bandas';
        $datos=array(
			'grupo'=>strtoupper($grupo), 
			'country_id'=>$_POST['country_id']=='0'?null:(int) $_POST['country_id'],
			'formacion'=>$_POST['formacion']==''?null:(int) $_POST['formacion']
		);
        $condicion=array('id_grupo'=> (int) $_POST['id_grupo']);

		$db->execUPDATE($table, $datos, $condicion);

        ?>
          <p>Actualizacion realizada con exito</p>
          <p>Pulse <a href="DisConBandas.php?i=<?php echo substr($grupo,0,1); ?>">aqui</a> para volver a la lista de bandas.</p>
        <?php
	}
	else
	{

	$strBanda = "SELECT id_grupo, grupo, country_id, formacion FROM bandas WHERE id_grupo=" . $_GET['id_grupo'];
	$db->setQueryString($strBanda);
	$Banda=$db->execSELECT();

	$strCountries = 'SELECT cast(id as char) AS id , name FROM countries ORDER BY name ASC';
	$db->setQueryString($strCountries);
	$countries = array_merge([['id'=>'0', 'name'=>'']], $db->execSELECT());

	?>
	<div class="formulario">
	
	<form class="addBanda" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
	
	<fieldset>
        <legend>EDICION DE BANDA</legend>	
    
        <input name="id_grupo" type="hidden" id="id_grupo" value="<?php echo $Banda[0]["id_grupo"]; ?>" />

    <p>
    <label for="id_grupo">ID:</label>
    <span class="texto_fijo"><?php echo $Banda[0]["id_grupo"]; ?></span>
	</p>
    
    <p>
    <label for="grupo">Banda:</label>
    <input class="text" name="grupo" type="text" id="grupo" value="<?php echo $Banda[0]["grupo"]; ?>" size="40" maxlength="50" />
    </p>

	<p>
    <label for="country_id">Pais:</label>
		<select name="country_id" id="country_id">
		<?php 
		
		foreach($countries as $country){

			echo "<option value={$country['id']}";
			if((int) $country['id']== $Banda[0]['country_id']) echo ' selected';

			echo ">";

			$country_name=$country['name']==null?'':$country['name'];
			echo "{$country_name}</option>";
		}
		?>
		</select>
	</p>

	<p>
    <label for="formacion">Formación:</label>
    <input class="text" name="formacion" type="number" id="formacion" value="<?php echo $Banda[0]["formacion"]; ?>" />
    </p>       
	</fieldset>
	
	<p>
    <input class="submit" name="aceptar" type="submit" id="aceptar" value="Aceptar" />
	</p>
	
	</form>
	<?php
	}
?>
</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
include(PATH_INCLUDE . "pie.php");
?>