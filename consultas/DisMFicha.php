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
	if (isset($_POST['new_compania'])&&strlen(trim($_POST['new_compania']))>0)
	{
		//si se ha indicado un nuevo nombre de compa�ia
		$new_compania=trim(filter_input(INPUT_POST, 'new_compania', FILTER_SANITIZE_STRING));
		
		$ID=$db->getNextId('companias', 'id_compania');
		
		$table='companias';
                $datos=array(
                    'id_compania'=>$ID,
                    'compania'=>$new_compania);
		$db->execINSERT($table, $datos);

                $id_compania=$ID;
	}
		else
	{
		$id_compania=$_POST['id_compania'];
	}

	//----------------------------------------------	
	if (isset($_POST['new_estilo'])&&strlen(trim($_POST['new_estilo']))>0)
	{
		//si se ha indicado un nuevo nombre de estilo
		$new_estilo=trim($_POST['new_estilo']);
		
		$ID=$db->getNextId('estilos', 'id_estilo');

                $table='estilos';
                $datos=array(
                    'id_estilo'=>$ID,
                    'estilo'=>$new_estilo);
		$db->execINSERT($table, $datos);
		
		$db->executeQuery();
		$id_estilo=$ID;
	}
	else
	{
		$id_estilo=$_POST['id_estilo'];
	}
	
	//----------------------------------------------	
	if (isset($_POST['new_soporte'])&&strlen(trim($_POST['new_soporte']))>0)
	{
		//si se ha indicado un nuevo nombre de soporte
		$new_soporte=trim($_POST['new_soporte']);
	
		$ID=$db->getNextId('soportes', 'id_soporte');

                $table='soportes';
                $datos=array(
                    'id_soporte'=>$ID,
                    'soporte'=>$new_soporte);
		$db->execINSERT($table, $datos);
	
		$db->executeQuery();
		$id_soporte=$ID;
	}
		else
	{
		$id_soporte=$_POST['id_soporte'];
	}

	
	//----------------------------------------------	
	if (isset($_POST['new_fuente'])&&strlen(trim($_POST['new_fuente']))>0)
	{
		//si se ha indicado un nuevo nombre de fuente
		$new_fuente=trim($_POST['new_fuente']);
		
		$ID=$db->getNextId('fuentes', 'id_fuente');

                $table='fuentes';
                $datos=array(
                    'id_fuente'=>$ID,
                    'fuente'=>$new_fuente);
		$db->execINSERT($table, $datos);

		$db->executeQuery();
		$id_fuente=$ID;
	}
		else
	{
		$id_fuente=$_POST['id_fuente'];
	}

        $table='discos';
        $datos=array(
            'id_grupo'=>$_POST['id_grupo'],
            'titulo'=>$_POST['titulo'],
            'ano'=>$_POST['ano'],
            'id_compania'=>$id_compania,
            'id_soporte'=>$id_soporte,
            'id_fuente'=>$id_fuente,
            'id_estilo'=>$id_estilo,
            'notas'=>$_POST['notas'],
            'fech_up'=>date("Y-m-d H:i:s") 
                );
        $condicion=array('id_disco'=>$_POST['id_disco']);
	$db->execUPDATE($table, $datos, $condicion);
	
        ?>
        <p>Actualizacion realizada con exito</p>
        <p>Pulse <a href="DisConDiscos.php?id_grupo=<?php echo $_POST['id_grupo']; ?>">aqui</a> para volver a la discografia.</p>
        <?php
	}
	else
	{
	
	$strDisco = "SELECT  id_disco, id_grupo, titulo, ano, id_compania, id_estilo, 
id_soporte, id_fuente, notas, 
DATE_FORMAT(fech_add, '%d-%m-%y %H:%i:%s') as fech_add, 
DATE_FORMAT(fech_up, '%d-%m-%y %H:%i:%s') as fech_up 
FROM discos WHERE id_disco=" . $_GET['id_disco'];

	$db->setQueryString($strDisco);
	$Disco = $db->execSELECT();

	$strGrupos="SELECT id_grupo, grupo FROM bandas ORDER BY grupo";
	$db->setQueryString($strGrupos);
	$qGrupos=$db->execSELECT();
	
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
	<li><a href="DisMFicha1.php?id_disco=<?php echo $Disco[0]["id_disco"]; ?>">Tracklist</a></li>
	<li><a href="DisSubirCaratula.php?id_disco=<?php echo $Disco[0]["id_disco"]; ?>">Subir Caratula</a></li>
	<li><a href="DisConDiscos.php?id_grupo=<?php echo $Disco[0]["id_grupo"]; ?>">Volver a discografia</a></li>
    </ul>
    </div>

	<div class="formulario">
	
	<fieldset>
	
  	<legend>FICHA DE DISCO - Datos generales</legend>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	
	<input name="id_disco" type="hidden" value="<?php echo $Disco[0]["id_disco"]; ?>" />

  	
   <p>
   <label for="id_grupo">Grupo:</label>
   <select id="id_grupo" name="id_grupo">
   <?php
	foreach ($qGrupos as $Grupo)
		{
		echo "<option value=\"" . $Grupo["id_grupo"] . "\"";
		if ($Grupo["id_grupo"]==$Disco[0]["id_grupo"])
			{ 
			echo " selected='selected'";
			}
		echo ">" . htmlspecialchars($Grupo["grupo"]) . "</option>\n";
		}
	?>
	</select>
    </p>
    
    <p> 
      <label for="titulo">Titulo:</label>
      <input class="text" id="titulo" name="titulo" type="text" value="<?php echo $Disco[0]["titulo"]; ?>" size="50" maxlength="150" /> 
    </p>
 
     <p> 
      <label for="ano">A&ntilde;o:</label>
      <input class="text" id="ano" name="ano" type="text" value="<?php echo $Disco[0]["ano"]; ?>" size="4" maxlength="4" />
    </p>
    
    <p> 
      <label for="id_compania">Compa&ntilde;ia:</label>
      <select id="id_compania" name="id_compania">
      <?php
	foreach ($qCompanias as $Compania)
		{
		echo "<option value=\"" . $Compania["id_compania"] . "\"";
		if ($Compania["id_compania"]==$Disco[0]["id_compania"])
			{ 
			echo " selected='selected'";
			}
		echo ">" . $Compania["compania"] . "</option>";
		}
	  ?>
     </select> 
 
 	<input class="text" name="new_compania" type="text" id="new_compania" /></p>
    
    <p> 
      <label for="id_estilo">Estilo:</label>
      <select id="id_estilo" name="id_estilo" class="inputform" >
      <?php
		foreach ($qEstilos as $Estilo)
			{
			echo "<option value=\"" . $Estilo["id_estilo"] . "\"";
			if ($Estilo["id_estilo"]==$Disco[0]["id_estilo"])
				{ 
				echo " selected='selected'";
				}
			echo ">" . $Estilo["estilo"] . "</option>";
			}
	  ?>
      </select>
  
  <input class="text" name="new_estilo" type="text" id="new_estilo" /></p>

      <p> 
      <label for="id_soporte">Soporte:</label>
      <select id="id_soporte" name="id_soporte">
      <?php
		foreach ($qSoportes as $Soporte)
			{
			echo "<option value=\"" . $Soporte["id_soporte"] . "\"";
			if ($Soporte["id_soporte"]==$Disco[0]["id_soporte"])
				{ 
				echo " selected='selected'";
				}
			echo ">" . $Soporte["soporte"] . "</option>";
			}
	  ?>
     </select> 

	<input class="text" name="new_soporte" type="text" id="new_soporte" /></p>

      <p> 
      <label for="id_fuente">Fuente:</label>
      <select id="id_fuente" name="id_fuente">
      <?php
		foreach ($qFuentes as $Fuente)
			{
			echo "<option value=\"" . $Fuente["id_fuente"] . "\"";
			if ($Fuente["id_fuente"]==$Disco[0]["id_fuente"])
				{ 
				echo " selected='selected'";
				}
			echo ">" . $Fuente["fuente"] . "</option>";
			}
	  ?>
      </select>

	<input class="text" name="new_fuente" type="text" id="new_fuente" /></p>

      <p> 
      <label for="agregado">Agregado:</label>
      <span id="agregado" class="texto_fijo"><?php echo $Disco[0]["fech_add"]; ?></span>
	  </p>

      <p> 
      <label for="modificado">Ultima Modif.:</label>
 	  <span id="nodificado"class="texto_fijo"><?php echo $Disco[0]["fech_up"]; ?></span>
	  </p>

	</fieldset>
	
	<fieldset>
    <legend>Mi Opini&oacute;n</legend>

    <p>
	<textarea name="notas" cols="60" rows="10"><?php echo $Disco[0]["notas"]; ?></textarea>
    </p>
	
	</fieldset>	
	
	<p><input class="submit" type="submit" name="aceptar" value="Grabar" /></p>

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
