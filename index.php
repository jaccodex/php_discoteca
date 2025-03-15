<?php

ini_set('display_startup_errors', true);
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

require_once(PATH_INCLUDE . "cabecera.php");
?>

<script type='text/javascript' src='/js/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='/js/tips_actualizaciones.js'></script>

<link type="text/css" rel="stylesheet" href="/css/tabbed_menu.css" />
<link type="text/css" rel="stylesheet" href="/css/tips.css" />

</head>
<body>
<?php

require_once(PATH_INCLUDE . "variables_db_connect.php");
require_once(PATH_CLASSES . 'mysqliDb_class.php');
require_once(PATH_CLASSES . 'mysqliDb_exception_class.php');

if(isset($_GET["act"]))	{
	$act=$_GET["act"];
	}
else{
	$act=1;
	}

if(isset($_GET["pag"])){
	$pag=$_GET["pag"];
	}
else{
	$pag=0;
	}


$lineas=20;

if ($act==1){
	$strLastUpQuery="SELECT 
	bandas.grupo, 
	discos.id_disco, 
	discos.titulo, 
	discos.fech_up,
	discos.ano,
	DATE_FORMAT(discos.fech_up, '%d/%m/%y') AS fech_upf 
	FROM discos
	INNER JOIN bandas ON discos.id_grupo=bandas.id_grupo
	ORDER BY discos.fech_up DESC";
}

if ($act==2){
	$strLastUpQuery="SELECT 
	bandas.grupo, 
	discos.id_disco, 
	discos.titulo, 
	discos.ano,
	covers.fech_mod,
  DATE_FORMAT(covers.fech_mod, '%d/%m/%y') AS fech_upf 
	FROM covers
	INNER JOIN discos ON covers.id_disco=discos.id_disco
	INNER JOIN bandas ON discos.id_grupo=bandas.id_grupo  
  ORDER BY covers.fech_mod DESC";
}

$db= new mysqliDb();

$db->setQueryString($strLastUpQuery);
$results=$db->execSELECT();
$numRegs=$db->getNumRows();

$strLastUp= $strLastUpQuery . ' limit ' . $lineas*$pag . "," . $lineas;
$db->setQueryString($strLastUp);
$results=$db->execSELECT();


require_once(PATH_INCLUDE . "logo.php");

?>

<div id="content">

<?php

require_once(PATH_INCLUDE . "menu.php");



?>
<div id="main">

<div class="menuConTabs">
<ul>
<?php
if ($act==1){
    ?>
    <li class="activa">Actualizaciones en Fichas</li>
    <?php
}
else{
    ?>
    <li><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=1">Actualizaciones en Fichas</a></li>
    <?php
}

if ($act==2){
    ?>
    <li class="activa">Ultimas Actualizaciones en Covers</li>
    <?php
}
else{
    ?>
    <li><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=2">Ultimas Actualizaciones en Covers</a></li>
    <?php
}
?>		
</ul>
</div>

<div class="bodyConTabs">

<table class="actualizaciones" cellspacing="0" cellpadding="1">
<thead>
<tr> 
  <th class='cab-grupo'>Grupo</th>
  <th class='cab-titulo'>Titulo</th>
	<th class='cab-ano'>Año</th>
  <th class='cab-act'>Actualiz.</th>
</tr>
</thead>

<tbody>

<?php

foreach($results as $LastUp){
    $grupo=$LastUp['grupo'];
    $titulo=$LastUp['titulo'];
		$ano=$LastUp['ano'];
		
    if (strlen($grupo)>30){
        $grupo=substr($grupo,0,30) . " ...";
    }
    if (strlen($titulo)>40){
        $titulo=substr($titulo,0,40) . " ...";
    }

	$destino = "./consultas/DisConFicha.php?id_disco=" . $LastUp['id_disco'];

    ?>
	<tr>
<!--<td class='izq'><?php echo htmlentities(stripslashes($grupo));?></td>-->
    <td class='izq'><?php echo stripslashes($grupo);?>
    <td class='izq'><a href="<?php echo $destino;?>" rel="<?php echo $LastUp['id_disco'];?>">
    <?php echo stripslashes($titulo);?>
    </a></td>
		<td class='centr'><?php echo $ano;?>
    <td class='centr'><?php echo $LastUp['fech_upf'];?></td>
    </tr>
    <?php
}
/* consultas para resumen a pie de pagina */
$strBandas="SELECT count(*) as num from bandas";
$strDiscos="SELECT count(*) as num from discos";
$strTemas ="SELECt count(*) as num from temas";

$db->setQueryString($strBandas);
$nBandas=$db->execSELECT();

$db->setQueryString($strDiscos);
$nDiscos=$db->execSELECT();

$db->setQueryString($strTemas);
$nTemas=$db->execSELECT();

/*---------------------------*/

$pagant=$pag-1;
$pagpos=$pag+1;

$numpags=(int) ($numRegs/$lineas);
if(($numRegs % $lineas)>0)
{
	$numpags+=1;
}

$strCheckAnt= $strLastUpQuery . $lineas*$pagant . "," . $lineas;
$strCheckPos= $strLastUpQuery . $lineas*$pagpos . "," . $lineas;

$enlace0=$_SERVER['PHP_SELF'] . "?act=" . $act . "&amp;pag=0";
$enlace1=$_SERVER['PHP_SELF'] . "?act=" . $act . "&amp;pag=" . $pagant;
$enlace2=$_SERVER['PHP_SELF'] . "?act=" . $act . "&amp;pag=" . $pagpos;
$enlace3=$_SERVER['PHP_SELF'] . "?act=" . $act . "&amp;pag=" . ($numpags-1);

?>
	<tr class="ultima">

    <td class="finalIzq">
    <?php
	if ($pag>0)//si estamos en la primera pagina no mostrar link atras
	{
		echo "<a href=\"{$enlace0}\"><img src='/img/resultset_first.png' /</a>";
		echo "&nbsp;";
		echo "&nbsp;";
		echo "<a href=\"{$enlace1}\"><img src='/img/resultset_previous.png' /</a>";
	}
	else
	{
		echo "&nbsp;";
	}
	?>
  	</td>
    
    <td>&nbsp;</td>
    
    <td class="finalDer">
    <?php
	
	if ($pag<($numpags-1))
	{
		echo "<a href=\"{$enlace2}\"><img src='/img/resultset_next.png' /></a>";
		echo "&nbsp;";
		echo "&nbsp;";
		echo "<a href=\"{$enlace3}\"><img src='/img/resultset_last.png' /></a>";
	}
	else
	{
		echo "&nbsp;";
	}
	?>
    </td>
    </tr>
  </tbody>  
  </table>

</div><!-- Fin de main -->

<?php 
echo "<p> Actualmente hay en la base de datos " . $nBandas[0]['num'] . " bandas, " . $nDiscos[0]['num']  . " discos y " .
$nTemas[0]['num']  . " temas, Paginas necesarias: " . $numpags . "</p>";

?>

<!--</div>--><!--fin de container_menu_tabular-->


</div><!--fin de content-->

<?php
//include_once($_SERVER['DOCUMENT_ROOT'] . "/admin/backup/procesos/weekly_backup.php");
?>

<?php
include(PATH_INCLUDE . "pie.php");
?>

