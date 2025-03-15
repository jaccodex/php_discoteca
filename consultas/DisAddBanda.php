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

if (isset($_POST['aceptar'])){

    $grupo=strtoupper(filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_STRING));

	if(empty($grupo)||strlen(trim($grupo))==0){
		?>
		<p>Actualizacion no realizada</p>
		<p>La banda debe tener un nombre. Pulse <a href="DisAddBanda.php">aqui</a> para volver a intentarlo.</p>
		<?php
		exit;
	}


	$strCheck="select id_grupo from bandas where grupo=\"" . $grupo . "\"";
	$db->setQueryString($strCheck);
	$db->execSELECT();
	
	if ($db->getNumRows()>0){
		?>
		  <p>Actualizacion no realizada</p>
          <p>La banda que desea agregar ya existe en la base de datos. Pulse <a href="DisAddBanda.php">aqui</a> para volver a intentarlo.</p>
		<?php
		exit;
		}

	$MaxId=$db->getNextId('bandas', 'id_grupo');
	$country_id = $_POST['country_id']=='0'?null:(int) $_POST['country_id'];
	$formacion =  $_POST['formacion']==''?null:(int) $_POST['formacion'];
	
	$values = [
		'id_grupo'=>$MaxId,
		'grupo'=>$grupo,
		'country_id'=>$country_id,
		'formacion'=>$formacion
	];

	$db->execINSERT('bandas', $values);

	//if ($ok){
		?>
		  <p>Actualizacion realizada con exito</p>
		  <p>Pulse <a href="DisConBandas.php?i=<?php echo substr($grupo,0,1); ?>">aqui</a> para volver a la lista de bandas.</p>
		<?php
		/*
		}
		else{
		?>
		  <p>No se ha podido realizar la actualizacion</p>
		<p><?php echo $strAdd; ?></p>
		<p>Pulse <a href="DisAddBanda.php">aqui</a> para volver a intentarlo.</p>
		<p><?php echo $ok;?></p>
		<?php
		}
	*/
	}
	else{

	$strCountries = 'SELECT cast(id as char) AS id , name FROM countries ORDER BY name ASC';
	$db->setQueryString($strCountries);
	$countries = array_merge([['id'=>'0', 'name'=>'']], $db->execSELECT());

	?>
	
	<div class="formulario">
	
	<form class="addBanda" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
	
	<fieldset>	
 
	<legend>ALTA DE BANDA</legend>
	
	<p>
    <label for="identificador">ID:</label><span class="texto_fijo" id="identificador">-</span>
    </p>

    <p>
    <label for="grupo">Banda:</label>
    <input class="text" name="grupo" type="text" id="grupo" value="" size="40" maxlength="50" />
    </p>

	<p>
    <label for="country_id">Pais:</label>
		<select name="country_id" id="country_id">
		<?php 
		
		foreach($countries as $country){

			echo "<option value={$country['id']}>";
			$country_name=$country['name']==null?'':$country['name'];
			echo "{$country_name}</option>";
		}
		?>
		</select>
	</p>

	<p>
    <label for="formacion">Formación:</label>
    <input class="text" name="formacion" type="number" id="formacion" value="" />
    </p>  

	</fieldset>  
	
	<p>
    <input class="submit" name="aceptar" type="submit" id="aceptar" value="Aceptar" />
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