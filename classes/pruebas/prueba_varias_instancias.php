<?php
include($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

include(PATH_INCLUDE . "cabecera.php");
?>
</head>
<body>

<div id="content">
<?php

include(PATH_INCLUDE . "variables_db_connect.php");
require_once('mysql_class.php');

$id_grupo=2;

$strBanda = "SELECT grupo FROM bandas WHERE id_grupo=" . $id_grupo;

$db= new mysql();
$db->setQueryString($strBanda);

$db->getQueryResult();
$Banda=$db->fetchResults();

?>

<div class="fichaDisco">

<p class="titular">
<label for="grupo">Banda:&nbsp;</label>
<span class="dato" id="grupo"><?php echo $Banda["grupo"]; ?></span>
</p>

<table>
<thead>
  <tr> 
    <th width="400">Discografia disponible</th>
    <th width="20"></th>
    <th width="20"></th>
  </tr>
</thead>
<tbody>
<?php

$strDiscos="
SELECT 
discos.id_disco, 
discos.titulo, 
discos.ano,
soportes.soporte,
companias.compania,
count(*) as ntemas,
SEC_TO_TIME(SUM(TIME_TO_SEC(temas.duracion))) AS duracion
FROM discos LEFT JOIN temas on (discos.id_disco=temas.id_disco), soportes, companias
WHERE discos.id_grupo=" . $id_grupo . 
" AND   discos.id_soporte=soportes.id_soporte
AND   discos.id_compania=companias.id_compania
GROUP by 1,2,3 
ORDER BY ano DESC, titulo ASC";

$db->setQueryString($strDiscos);

$db->getQueryResult();

$par=true;

while ($Disco=$db->fetchResults())
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
	<td>
    
	<a href="DisConFicha.php?id_disco=<?php echo $Disco["id_disco"]; ?>">
	<img class='miniThumb' src="/includes/crearThumbMini.php?id_disco=<?php echo $Disco['id_disco']; ?>" alt=''>
	</a>
    <div class='discografia'>
    <p class='discografia_titulo'><?php echo $Disco["titulo"]; ?></p>
    <p class='discografia_datos'><?php echo $Disco['ano'] . ' , ' . $Disco["compania"]; ?></p>
    <p class='discografia_datos'><?php echo $Disco["soporte"] . ' ,' . $Disco['ntemas'] . ' temas , duracion: ' . $Disco['duracion']; ?></p>
    </div>

    </td>
	
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
include(PATH_INCLUDE . "pie.php");
?>