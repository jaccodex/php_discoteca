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
	
$strDisco = "SELECT  id_disco, id_grupo, titulo FROM discos WHERE id_disco=" . $_GET['id_disco'];
$db->setQueryString($strDisco);
$Disco = $db->execSELECT();

$strGrupo="SELECT grupo FROM bandas WHERE id_grupo=\"" . $Disco[0]["id_grupo"] . "\"";
$db->setQueryString($strGrupo);
$Grupo=$db->execSELECT();
		
$strTemas="SELECT id_tema, numero, titulo, duracion FROM temas WHERE id_disco=\"" . $Disco[0]["id_disco"] . "\" ORDER BY numero";
$db->setQueryString($strTemas);
$qTemas=$db->execSELECT();

?>
<div class="menuHorizontal">
<ul>
<li><a href="DisMFicha.php?id_disco=<?php echo $_GET['id_disco']; ?>">Volver a Ficha</a></li>
<li><a href="DisAddTema.php?id_disco=<?php echo $_GET['id_disco']; ?>">Nuevo Tema</a></li>
</ul>
</div>

<div class="fichaDisco">
	  
<!--<h1>FICHA DE DISCO - Tracklist</h1>-->

<p class="titular">
<label for="grupo">Grupo:</label>
<span class="dato" id="grupo"><?php echo $Grupo[0]["grupo"]; ?></span>
</p>
<p class="titular">
<label for="titulo">Titulo:</label>
<span class="dato" id="titulo"><?php echo $Disco[0]["titulo"]; ?></span>
</p>

<table cellpadding="0" cellspacing="0">

  <tr> 
    <th width="27">N.</th>
    <th>Titulo</th>
    <th width="80">Duraci&oacute;n</th>
    <th width="20">&nbsp;</th>
    <th width="20">&nbsp;</th>
  </tr>
  
  <?php
  
  $par=true;
  
  	foreach ($qTemas as $Tema)
	{
	
	$par=!$par;
	
    echo "<tr";
	if($par)
	{
	echo " class='par'";
	}else
	{
	echo " class='impar'";
	}
	echo ">";
	?>
   
    <td><?php echo $Tema["numero"]; ?></td>
    <td><?php echo $Tema["titulo"]; ?></td>
    <td><?php echo $Tema["duracion"]; ?></td>
    <td><a class="icons" href="DisMTema.php?id_tema=<?php echo $Tema['id_tema']; ?>&amp;id_disco=<?php echo $_GET['id_disco']; ?>"><img src="../img/editar.png" alt="Editar Tema" title="Editar Tema"/></a></td>
    <td><a class="icons" href="DisDelTema.php?id_tema=<?php echo $Tema['id_tema']; ?>&amp;id_disco=<?php echo $_GET['id_disco']; ?>"><img src="../img/borrar.png" alt="Borrar Tema" title="Borrar Tema"/></a></td>
  </tr>
  <?php
	}
	?>
</table>

</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>

