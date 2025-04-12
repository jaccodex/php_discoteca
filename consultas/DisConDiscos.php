<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");
//require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php");

require_once(PATH_INCLUDE . "cabecera.php");

?>
<script type="text/javascript" src="../js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.js"></script>
<!--<script type="text/javascript" src="../js/farinspace/jquery.imgpreload.js"></script>-->

<script type="text/javascript" src="../js/DisConDiscos.js"></script>

<link href="../js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css">

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

require_once(PATH_CLASSES . 'navigator_class.php');

if(isset($_GET['id_grupo']))
{
	$id_grupo=$_GET['id_grupo'];
}else
{
    if(isset($_POST['id_grupo']))
    {
        $id_grupo=$_POST['id_grupo'];
    }
    else
    {
        ?>
        <div class='error'>
        <p>Ha habido un error en el acceso a esta pagina</p>
        </div>
        <?php
        include(PATH_INCLUDE . "pie.php");
    }
}

$strBanda = "SELECT grupo FROM bandas WHERE id_grupo=" . $id_grupo;

$db= new mysqliDb();

$db->setQueryString($strBanda);
$Banda=$db->execSELECT();

?>
<div class="menuHorizontal">
<ul>
<li><a href="DisConBandas.php?i=<?php echo substr($Banda[0]["grupo"],0,1); ?>"><span>Volver a Indice de Bandas</span></a></li>
<li><a href="DisAddDisco.php?id_grupo=<?php echo $id_grupo; ?>"><span>Agregar disco</span></a></li>
</ul>
</div>

<div class="fichaDisco">

<p class="titular">
<label for="grupo">Banda:&nbsp;</label>
<span class="dato" id="grupo"><?php echo $Banda[0]["grupo"]; ?></span>
</p>

<table>
<thead>
  <tr> 
    <th class='cab-titulo'>Discografia disponible</th>
    <th class='cab-ano'>Año</th>
    <th width="20"></th>
    <th width="20"></th>
  </tr>
</thead>
<tbody>
<?php

$recordsPerPage=8;

if (!isset($_GET['actPage']))
	{
	$pag=0;
	}
else
	{
	$pag=$_GET['actPage'];
	}

$strDiscosT="SELECT discos.id_disco FROM discos WHERE discos.id_grupo=" . $id_grupo;

$db->setQueryString($strDiscosT);

$discos=$db->execSELECT();
$num=$db->getNumRows();

$strDiscos="
SELECT 
discos.id_disco, 
discos.titulo, 
discos.ano,
companias.compania,
count(*) as ntemas,
SEC_TO_TIME(SUM(TIME_TO_SEC(temas.duracion))) AS duracion
FROM discos 
LEFT JOIN temas on (discos.id_disco=temas.id_disco) 
LEFT JOIN companias ON discos.id_compania=companias.id_compania 
WHERE discos.id_grupo=" . $id_grupo . 
" GROUP by 1,2,3 
ORDER BY ano DESC, titulo DESC LIMIT " . $pag*$recordsPerPage . "," . $recordsPerPage;

$db->setQueryString($strDiscos);
$discos=$db->execSELECT();

foreach ($discos as $Disco)
	{
	
	?>
    <tr>
	<td>
    
    <a class='linkThumbMini' href="<?php echo '/includes/crearThumb.php?id_disco=' . $Disco['id_disco'];?>" target="_blank">
	<img class='miniThumb' src="<?php echo '/includes/crearThumb.php?id_disco=' . $Disco['id_disco'];?>&amp;width=50" alt='' />
	</a>
    <div class='discografia'>
        <p class='discografia_titulo'><a href="DisConFicha.php?id_disco=<?php echo $Disco["id_disco"]; ?>">
        <?php 
        echo substr($Disco["titulo"], 0, 50);
        if(strlen($Disco["titulo"])>46)
        {
            echo '...';
        }
        ?></a></p>
    <p class='discografia_datos'><?php echo $Disco["compania"] . ' ,' . $Disco['ntemas'] . ' temas , duracion: ' . $Disco['duracion']; ?></p>
    </div>

    </td>
	<td><?php echo $Disco['ano'];?></td>
  <td>
  <a class="icons" href="DisMFicha.php?id_disco=<?php echo $Disco["id_disco"]; ?>">
  <img src="/img/editar.png" alt="Editar Disco" title="Editar Disco" />
  </a>
  </td>
  
  <td>
  <a class="icons" href="DisDelDisco.php?id_disco=<?php echo $Disco["id_disco"]; ?>">
  <img src="/img/borrar.png" alt="Borrar Disco" title="Borrar Disco" />
  </a>
  </td>
  
  </tr>
	<?php
    }
?>
</tbody>
</table>
</div>

<?php
$miNavegador= new navigator($_SERVER['PHP_SELF'],$num,$recordsPerPage);

echo $miNavegador->showNavigator();

?>
<div class='preload_area'>

</div>

</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
include(PATH_INCLUDE . "pie.php");
?>