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

include(PATH_INCLUDE . "conexion.php");
include(PATH_INCLUDE . "funciones_db.php");
include(PATH_INCLUDE . "funciones.php");

$strDisco = "SELECT  discos.id_disco, bandas.id_grupo, bandas.grupo, 
titulo, ano, companias.compania, estilos.estilo, 
soportes.soporte, fuentes.fuente, 
DATE_FORMAT(fech_add, '%d-%m-%y %H:%i:%s') as fech_add, DATE_FORMAT(fech_up,'%d-%m-%y %H:%i:%s') as fech_up, 
notas
FROM discos, bandas, companias, estilos, soportes, fuentes
WHERE id_disco=" . $_GET['id_disco'] .
" AND bandas.id_grupo=discos.id_grupo
AND companias.id_compania=discos.id_compania
AND estilos.id_estilo=discos.id_estilo
AND soportes.id_soporte=discos.id_soporte
AND fuentes.id_fuente=discos.id_fuente";

$qDisco = confirm_query($strDisco);
$Disco = mysql_fetch_array($qDisco);

$strTemas="SELECT numero, titulo, duracion FROM temas WHERE id_disco=" . $_GET['id_disco']
. " ORDER BY numero";

$qTemas=confirm_query($strTemas);

$strDurac="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(duracion))) AS duracion FROM temas WHERE id_disco=" . $_GET['id_disco'];
$qDurac=confirm_query($strDurac);

?>
<script language="Javascript">
function VerPortada(destino)
{
var VentanaExt=window.open(destino,'Cover','scrollbars=no,location=no,status=no,width=350,height=350');
VentanaExt.focus();	
}
</script>

<div class="MenuHorizontal">
<p>
<a href="DisConDiscos.php?id_grupo=<?php echo $Disco["id_grupo"]; ?>">Volver a discografia</a>
</p>
</div>



<div id="caratula_disco">
    <?php
	$cover = COVER_PATH . cover_file($_GET['id_disco']);

	if (!file_exists($cover))
	{
		?>
		<img class="cover" src="nocover.gif" alt="" />
		<?php
	}
	else
	{
		$destino="../DisVerCoverMas.php?id_disco=" . $_GET['id_disco'];
		?>
		<a target="_blank" 
		onclick="javascript:VerPortada('<?php echo $destino; ?>');"
		>
		<img class="cover" src="../DisVerCover.php?id_disco=<?php echo $_GET['id_disco']; ?>" alt="Click para agrandar" title="Click para agrandar" />
		</a>
		<?php
	}
	?>
</div>

<div id="datos_disco">

<p><span class="titulo">Grupo:</span><span class="texto"><?php echo $Disco["grupo"]; ?></span></p>
<p><span class="titulo">Titulo:</span><span class="texto"><?php echo $Disco["titulo"]; ?></span></p>
    
<p><span class="titulo">A&ntilde;o:</span><span class="texto"><?php echo $Disco["ano"]; ?></span></p>
<p><span class="titulo">Compa&ntilde;ia:</span><span class="texto"><?php echo $Disco["compania"]; ?></span></p>
<p><span class="titulo">Estilo:</span><span class="texto"><?php echo $Disco["estilo"]; ?></span></p>
<p><span class="titulo">Duraci&oacute;n:</span><span class="texto"> 
      <?php
	$Durac=mysql_fetch_array($qDurac);
	if ($Durac["duracion"]=="00:00:00")
		{
		echo "N/D";
		}
		else
		{
		echo $Durac["duracion"];
		}
	?>
    </span></p>
<p><span class="titulo">Soporte:</span><span class="texto"><?php echo $Disco["soporte"];?></span></p>
<p><span class="titulo">Fuente:</span><span class="texto"><?php echo $Disco["fuente"]; ?></span></p>
<p><span class="titulo">Agregado:</span><span class="texto"><?php echo $Disco["fech_add"]; ?></span></p>
<p><span class="titulo">Ultima Modif.:</span><span class="texto"><?php echo $Disco["fech_up"]; ?></span></p>

</div>

<table class="track_list" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
<?php 

if (mysql_num_rows($qTemas)==0)
  {
  ?>
  <th colspan="4">Tracklist No Disponible</th>
  <?php
  }
  else
  {
  ?>
  <th colspan="4">Tracklist</th>
  </tr>
  
  <tr> 
    <th>N.</th>
    <th>Titulo</th>
    <th>Duraci&oacute;n</th>
    <th></th>
  </tr>

  <?php
  while ($Tema=mysql_fetch_array($qTemas))
  {
  ?>
  <tr> 
    <td><?php echo $Tema["numero"]; ?></td>
    <td class="texto"><?php echo $Tema["titulo"];?></td>
    <td><?php echo $Tema["duracion"]; ?></td>
    <td></td>
  </tr>
  <?php
  }
}

?>
</table>

<table class="ficha_disco" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <th width="550">Mi Opini&oacute;n</th>
  </tr>
  
  <tr> 
    <td>
	<?php 
	if(empty($Disco["notas"]))
	{
		echo "No hay comentarios.";
	}
	else
	{
		echo nl2br($Disco["notas"]); 
	}
	?>
    </td>
    </tr>
    
</table>

</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>