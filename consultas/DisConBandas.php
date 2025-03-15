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

require_once(PATH_INCLUDE . "variables_db_connect.php");
require_once(PATH_CLASSES . 'mysqliDb_class.php');
require_once(PATH_CLASSES . 'mysqliDb_exception_class.php');

require_once(PATH_CLASSES . 'indiceAlfabetico_class.php');
require_once(PATH_CLASSES . 'navigator_class.php');
 
$strInic="SELECT DISTINCT LEFT(grupo,1) AS ini FROM bandas ORDER BY ini";

$db= new mysqliDb();
$db->setQueryString($strInic);

$qInic=$db->execSELECT();

$iniciales=array();

foreach($qInic as $inic)
{
    $iniciales[]=$inic['ini'];
}

/*
 $i es la inicial de la lista de bandas.
 si es la primera vez, se muestra la pagina de la primera letra
*/
if (!isset($_GET['actPageI']))
{
    $i=$iniciales[0];
}
else
{
    $i=$iniciales[$_GET['actPageI']];
}

?>
<div id="main">
<div class="menuHorizontal">
<ul>
<li><a href="/index.php"><span>Volver a Inicio</span></a></li> 
<li><a href="DisAddBanda.php"><span>Nueva Banda</span></a></li> 
</ul>
</div>

<?php

$indiceAlfabetico = new indiceAlfabetico($_SERVER['PHP_SELF'], $iniciales,0,15);
echo $indiceAlfabetico->showNavigator();

/*
$nlineas: numero de lineas que aparecen en pantalla
$pag    : numero de la pagina actual
$npags  : numero de paginas

/*
si es la primera pagina, pag=0
*/

$recordsPerPage=15;

if (!isset($_GET['actPage'])){
	$pag=0;
	}
else{
	$pag=$_GET['actPage'];
	}

$strLista="SELECT bandas.id_grupo, bandas.grupo, COUNT(discos.id_disco) AS discos, 
countries.emojiU, countries.iso2, countries.name as country_name
FROM bandas
LEFT JOIN countries ON bandas.country_id=countries.id 
LEFT JOIN discos ON bandas.id_grupo=discos.id_grupo 
WHERE LEFT(bandas.grupo,1)=\"" . $i . "\"GROUP BY bandas.id_grupo
ORDER BY bandas.grupo LIMIT " . $pag*$recordsPerPage . "," . $recordsPerPage;

$strListaT="SELECT DISTINCT bandas.id_grupo
FROM bandas 
WHERE LEFT(bandas.grupo,1)=\"" . $i . "\"";

$db->setQueryString($strLista);
$qLista=$db->execSELECT();


$db->setQueryString($strListaT);
$db->execSELECT();
$num=$db->getNumRows();

?>

<table class="bandas">
  <!--DWLayoutTable-->
  <tr> 
    <th class='cab-grupo'>Banda</th>
	<th width="30"></th>
	<!--<th width="30"></th>-->
    <th class='cab-ano'>N&deg; Discos</th>
    <th width="30"></th>
    <th width="30"></th>
  </tr>
  <?php

//mostrar lista de bandas de inicial activa

foreach ($qLista as $b){
	$lpag="DisConDiscos.php?id_grupo=" . $b["id_grupo"];

	?>
    <tr>
		<td>
			<?php echo "<a href='{$lpag}'>{$b['grupo']}";
			if ($b['iso2']!=null){
				echo "<span class='small_label'> - ({$b['iso2']})</span>";
			}
			echo "</a>"; 
			?>
		</td>
	
		<td>
		<?php

		if($b['iso2']!=null){
			$flag="/img/country-flags-main/svg/{$b['iso2']}.svg";
			echo "<img src='{$flag}' class='flag' title='{$b['country_name']}'/>";
		}

		?>
		</td>

		<!--<td><?php echo $b['iso2'];?></td>-->
		<td><?php echo $b["discos"];?></td>

		<td>
		<a class="icons" href="DisMBandas.php?id_grupo=<?php echo $b["id_grupo"]; ?>"><img class="icons" src="/img/editar.png" alt="Editar Ficha de Banda" title="Editar Ficha de Banda" /></a>
		</td>

		<td>
		<?php
		if ($b["discos"]==0){
			?>
			<a href="DisDelBanda.php?id_grupo=<?php echo $b["id_grupo"]; ?>">
			<img class="icons" src="/img/borrar.png" alt="Borrar Ficha de Banda" title="Borrar Ficha de Banda" />
			</a>
			<?php
		}
		?>
		</td>
	</tr>
	<?php
    }
?>
</table>

<?php

$miNavegador= new navigator($_SERVER['PHP_SELF'],$num,$recordsPerPage);

echo $miNavegador->showNavigator();
?>
</div> <!-- fin de main -->
</div><!-- fin de content -->
<?php
include(PATH_INCLUDE . "pie.php");
?>