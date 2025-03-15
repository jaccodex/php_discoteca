<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");
require_once(PATH_INCLUDE . "cabecera.php");

?>
<link type="text/css" rel="stylesheet" href="../js/jquery/fancybox/jquery.fancybox-1.3.4.css" />
<link type="text/css" rel="stylesheet" href="../css/modal.css" />

<script type="text/javascript" src="../js/jquery/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="../js/DisConDiscos.js"></script>


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

require_once(PATH_CLASSES . 'thumb_class.php');

if(!$_GET['id_disco'])
{
	echo 'no hay id_disco pasada';
	exit;
}

$db= new mysqliDb();

$strDisco = "SELECT  discos.id_disco, bandas.id_grupo, bandas.grupo, 
titulo, ano, companias.compania, estilos.estilo, 
soportes.soporte, fuentes.fuente, 
DATE_FORMAT(fech_add, '%d-%m-%y %H:%i:%s') as fech_add, 
DATE_FORMAT(fech_up,'%d-%m-%y %H:%i:%s') as fech_up, 
notas
FROM discos
INNER JOIN bandas ON bandas.id_grupo=discos.id_grupo
INNER JOIN companias ON companias.id_compania=discos.id_compania
INNER JOIN estilos ON estilos.id_estilo=discos.id_estilo
INNER JOIN soportes ON soportes.id_soporte=discos.id_soporte
INNER JOIN fuentes ON fuentes.id_fuente=discos.id_fuente
WHERE discos.id_disco=" . $_GET['id_disco'];

$db->setQueryString($strDisco);

$Disco=$db->execSELECT();

$strDurac="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(duracion))) AS duracion FROM temas WHERE id_disco=" . $_GET['id_disco'];

$db->setQueryString($strDurac);

$Durac=$db->execSELECT();

?>
<div class="menuHorizontal">
<ul>
<li><a href="DisConDiscos.php?id_grupo=<?php echo $Disco[0]["id_grupo"]; ?>">Volver a discografia</a></li>
</ul>
</div>

<div id="ficha_disco">

  <div id="caratula_disco">
    <?php

    ?>
    <a class='linkThumbMini' href="<?php echo '/includes/crearThumb.php?id_disco=' . $_GET['id_disco'];?>" target="_blank">
    <img class='cover' src="<?php echo '/includes/crearThumb.php?id_disco=' . $_GET['id_disco'];?>&amp;width=180" alt='' />
    </a>

    <?php

    ?>
  </div>

  <div id="datos_disco">

  <p><label for="grupo">Grupo:</label><span id="grupo" class="dato"><?php echo $Disco[0]["grupo"]; ?></span></p>
  <p><label for="titulo">Titulo:</label><span id="titulo" class="dato"><?php echo $Disco[0]["titulo"]; ?></span></p>
  <p><label for="ano">A&ntilde;o:</label><span id="ano" class="dato"><?php echo $Disco[0]["ano"]; ?></span></p>
  <p><label for="compania">Compa&ntilde;ia:</label><span id="compania" class="dato"><?php echo $Disco[0]["compania"]; ?></span></p>
  <p><label for="estilo">Estilo:</label><span id="estilo" class="dato"><?php echo $Disco[0]["estilo"]; ?></span></p>  
  <p><label for="duracion">Duraci&oacute;n:</label><span id="duracion" class="dato">
  <?php
    if ($Durac[0]['duracion']=="00:00:00"){
      echo "N/D";
      }
      else{
      echo $Durac[0]['duracion'];
      }
  ?>
  </span></p>  
  <p><label for="soporte">Soporte:</label><span id="soporte" class="dato"><?php echo $Disco[0]["soporte"]; ?></span></p>   
  <p><label for="fuente">Fuente:</label><span id="fuente" class="dato"><?php echo $Disco[0]["fuente"]; ?></span></p>   
  <p><label for="agregado">Agregado:</label><span id="agregado" class="dato"><?php echo $Disco[0]["fech_add"]; ?></span></p>     
  <p><label for="modif">Ultima Modif.:</label><span id="modif" class="dato"><?php echo $Disco[0]["fech_up"]; ?></span></p>     
  <p><label for="trackListHeader">Tracklist</label><span id="trackListHeader" class="dato"><a href="#">Ver Tracklist</a></span></p>   
  </div>


  <div id="temas_disco">

  <?php 

  $strTemas="SELECT id_tema, numero, titulo, duracion, bitrate, tamano FROM temas WHERE id_disco=" . $_GET['id_disco']
  . " ORDER BY numero";

  $db->setQueryString($strTemas);

  $Temas=$db->execSELECT();


  if (count($Temas)==0)
    {
    ?>
    <p><label for="trackListHeader">Tracklist</label><span id="trackListHeader" class="dato">No disponible</span></p>
    <?php
    }
    else
    {
    ?>
    

    <table id="trackList" cellpadding="0" cellspacing="0">
      <thead> 
      <tr>
          <th width="42"></th>
          <th width="42"></th>
          <th width="42">N.</th>
          <th width="432">Titulo</th>
          <th>Duraci&oacute;n</th>
          <th>Bitrate</th>
          <th>Tamaño</th>
      </tr>

    </thead>
    
    <tbody>
    <?php

    $par=true;
    
    foreach ($Temas as $Tema)
    {
      echo "<tr>";

    ?>
    <td><a href="#" class="link_mp3" data-id_tema="<?php echo $Tema["id_tema"];?>">mp3</a></td>
    <td><a href="#" class="link_ogg" data-id_tema="<?php echo $Tema["id_tema"];?>">ogg</a></td>

      <td><?php echo $Tema["numero"]; ?></td>
      <td class="texto"><?php echo $Tema["titulo"];?></td>
      <td><?php echo $Tema["duracion"]; ?></td>
      <td><?php echo $Tema["bitrate"]; ?></td>
      <td><?php echo number_format($Tema["tamano"],0,',','.'); ?></td>
    </tr>
    <?php
    }
  }

  ?>
  </tbody>
  </table>
  </div>

  <div id="comments_disco">

  <table cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr> 
      <th>Mi Opini&oacute;n</th>
    </tr>
    
    <tr> 
      <td>
    <?php 
  
    if(empty($Disco[0]["notas"]))
    {
      echo "No hay comentarios.";
    }
    else
    {
      echo nl2br($Disco[0]["notas"]); 
    }
    ?>
      </td>
      </tr>
      
  </table>
  </div>

  </div>
  </div><!-- fin de ficha_disco -->
</div><!--fin de main  -->
</div><!--fin de content  -->

<div id="modal" style="display:none">
   <div id="ventana_modal" class="contenedor" style="display:none">
      
   </div>
</div>



<?php
include(PATH_INCLUDE . "pie.php");
?>