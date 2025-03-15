<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

require_once(PATH_INCLUDE . "cabecera.php");
?>
<!-- TinyMCE -->
<script type="text/javascript" src="/discoteca/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>
<!-- /TinyMCE -->
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
	//----------------------------------------------
        $new_compania=trim(filter_input(INPUT_POST,'new_compania',FILTER_SANITIZE_STRING));
		
	if (isset($new_compania)&&strlen($new_compania)>0)
	{
		//si se ha indicado un nuevo nombre de compa�ia
		
		$ID=$db->getNextId('companias', 'id_compania');
		
		$table='companias';
                $datos=array('id_compania'=>$ID, 
                    'compania'=>$new_compania);
                
		$db->execINSERT($table, $datos);
		$id_compania=$ID;
	}
	else
	{
		$id_compania=$new_compania;
	}
	
	//----------------------------------------------

        $new_estilo=trim(filter_input(INPUT_POST, 'new_estilo',FILTER_SANITIZE_STRING));

	if (isset($new_estilo)&&strlen($new_estilo)>0)
	{
		//si se ha indicado un nuevo nombre de estilo
		
		$ID=$db->getNextId('estilos', 'id_estilo');
		
                $table='estilos';
                $datos=array('id_estilo'=>$ID, 
                    'estilo'=>$new_estilo);

		$db->execINSERT($table, $datos);
	
		$id_estilo=$ID;
	}
	else
	{
		$id_estilo=$new_estilo;
	}
	
	//----------------------------------------------

        $new_soporte=trim(filter_input(INPUT_POST, 'new_soporte',FILTER_SANITIZE_STRING));

	if (isset($new_soporte)&&strlen($new_soporte)>0)
	{
		//si se ha indicado un nuevo nombre de soporte

                $ID=$db->getNextId('soportes', 'id_soporte');

                $table='soportes';
                $datos=array('id_soporte'=>$ID, 
                    'estilo'=>$new_soporte);

		$db->execINSERT($table, $datos);
	
		$id_soporte=$ID;
	}
	else
	{
		$id_soporte=$new_soporte;
	}
	
	//----------------------------------------------

        $new_fuente=trim(filter_input(INPUT_POST, 'new_fuente',FILTER_SANITIZE_STRING));

	if (isset($new_fuente)&&strlen($new_fuente)>0)
	{
		//si se ha indicado un nuevo nombre de fuente
		
		$ID=$db->getNextId('fuentes', 'id_fuente');

                $table='fuentes';
                $datos=array('id_fuente'=>$ID, 
                    'fuente'=>$new_fuente);

		$db->execINSERT($table, $datos);

		$id_fuente=$ID;
	}
		else
	{
		$id_fuente=$new_fuente;
	}

	$fecha=date("Y-m-d H:i:s");
	$notas=$_POST['notas'];

        $table='discos';
        $datos=array(
            'id_grupo'=>$_POST['id_grupo'], 
            'titulo'=>$_POST['titulo'],
            'ano'=>$_POST['ano'],
            'id_compania'=>$id_compania,
            'id_soporte'=>$id_soporte,
            'id_fuente'=>$id_fuente,
            'id_estilo'=>$id_estilo,
            'notas'=>$notas,
            'fech_add'=>$fecha
            );

        $db->execINSERT($table, $datos);

        ?>
        <p>Actualizacion realizada con exito</p>
        <p>Pulse <a href="DisConDiscos.php?id_grupo=<?php echo $_POST['id_grupo']; ?>">aqui</a> para volver a la discografia.</p>
        <?php

	}
	else
	{
	
	$strGrupo="SELECT grupo FROM bandas WHERE id_grupo=\"" . $_GET['id_grupo'] . "\"";
	$db->setQueryString($strGrupo);
	$grupos=$db->execSELECT();
	$grupo=$grupos[0]['grupo'];
		
	$strCompanias="SELECT id_compania, compania FROM companias ORDER BY compania";
	$db->setQueryString($strCompanias);
	$qCompanias=$db->execSELECT();

	$strEstilos="SELECT id_estilo, estilo FROM estilos ORDER BY estilo";
	$db->setQueryString($strEstilos);
	$qEstilos=$db->execSELECT();
	
	$strSoportes="SELECT id_soporte, soporte FROM soportes ORDER BY soporte";
	$db->setQueryString($strSoportes);
	$qSoportes=$db->execSELECT();
	
	$strFuentes="SELECT id_fuente, fuente FROM fuentes ORDER BY fuente";
	$db->setQueryString($strFuentes);
	$qFuentes=$db->execSELECT();	

	?>
	<div class="menuHorizontal">
    <ul>
	<li><a href="DisConDiscos.php?id_grupo=<?php echo $_GET['id_grupo']; ?>">Volver a discografia</a></li>
    </ul>
    </div>

	<div class="formulario">

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		
		<fieldset>	
	 
		<legend>AGREGAR DISCO - Datos generales</legend>
		
		<input type="hidden" name="id_grupo" value="<?php echo $_GET['id_grupo']; ?>" />

	   <p>
	   <label for="id_grupo">Grupo:</label>
		<span class="texto_fijo"><?php echo $grupo; ?></span>
		</p>
		
		<p>
		<label for="titulo">Titulo:</label>
		<input class="text" name="titulo" id="titulo" type="text"  value="" size="50" maxlength="50" />
		</p>

		<p>
		<label for="ano">A&ntilde;o:</label>    
		<input class="text corto" name="ano" id="ano" type="text" value="" size="4" maxlength="4" />
		</p>    
		
		<p>
		<label for="id_compania">Compa&ntilde;ia:</label>    
		<select name="id_compania" id="id_compania">
		<?php
		foreach ($qCompanias as $Compania)
			{
			echo "<option value=\"" . $Compania["id_compania"] . "\"";
			echo ">" . $Compania["compania"] . "</option>";
			}
		?>
		</select>
		
		<input class="text" name="new_compania" type="text"  id="new_compania" />
		</p>
	 
		<p>
		<label for="estilo">Estilo:</label>    
		<select name="id_estilo" id="id_estilo">
		<?php
		foreach ($qEstilos as $Estilo)
			{
			echo "<option value=\"" . $Estilo["id_estilo"] . "\"";
			echo ">" . $Estilo["estilo"] . "</option>";
			}
		?>
		</select> 
		
		<input class="text" name="new_estilo" type="text" id="new_estilo" />
		</p>
	 
		 <p>
		<label for="id_soporte">Soporte:</label>    
		<select name="id_soporte" id="id_soporte">
		<?php
		foreach ($qSoportes as $Soporte)
			{
			echo "<option value=\"" . $Soporte["id_soporte"] . "\"";
			echo ">" . $Soporte["soporte"] . "</option>";
			}
		?>
		</select> 
		
		<input class="text" name="new_soporte" type="text" id="new_soporte" />
		</p>
	   
		 <p>
		<label for="id_fuente">Fuente:</label>    
		<select name="id_fuente" id="id_fuente">
		<?php
		foreach ($qFuentes as $Fuente)
			{
			echo "<option value=\"" . $Fuente["id_fuente"] . "\"";
			echo ">" . $Fuente["fuente"] . "</option>";
			}
		?>
		</select> 
		
		<input class="text" name="new_fuente" type="text"  id="new_fuente" />
		</p>
		</fieldset>
		
		<fieldset>
		
		<legend>Mi Opini&oacute;n</legend>

		<p>
		<textarea name="notas" cols="60" rows="10"></textarea>
		</p>
		
		</fieldset>
		
		<p><input class="submit" type="submit" name="aceptar" value="Grabar" /></p>
    
	</form>
	
	</div><!--fin de formulario-->

	<?php
	}
?>
</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
include(PATH_INCLUDE . "pie.php");
?>