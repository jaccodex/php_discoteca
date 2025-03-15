<?php
include($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

include(PATH_INCLUDE . "cabecera.php");
?>

<script type='text/javascript' src='/js/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='/js/tips_actualizaciones.js'></script>

<link type="text/css" rel="stylesheet" href="/css/tabbed_menu.css" />
<link type="text/css" rel="stylesheet" href="/css/tips.css" />

</head>
<body>
<?php
include(PATH_INCLUDE . "logo.php");
include(PATH_INCLUDE . "menu.php");
?>

<div id="content">


<?php
include(PATH_INCLUDE . "variables_db_connect.php");
require_once(PATH_CLASSES . 'mysql_class.php');

if(isset($_GET["act"]))
	{
	$act=$_GET["act"];
	}
else
	{
	$act=1;
	}

if(isset($_GET["pag"]))
	{
	$pag=$_GET["pag"];
	}
else
	{
	$pag=0;
	}


$lineas=10;

if ($act==1)
{
	$strLastUpQuery="select 
	bandas.grupo, 
	discos.id_disco, 
	discos.titulo, 
	discos.fech_up,
	date_format(discos.fech_up, '%d/%m/%y') as fech_upf
	from discos, bandas
	where discos.id_grupo=bandas.id_grupo
	order by discos.fech_up desc";
}

if ($act==2)
{
	$strLastUpQuery="select 
	bandas.grupo, 
	discos.id_disco, 
	discos.titulo, 
	covers.fech_mod,
    date_format(covers.fech_mod, '%d/%m/%y') as fech_upf 
    from covers, discos, bandas 
    where discos.id_grupo=bandas.id_grupo and covers.id_disco=discos.id_disco 
    order by covers.fech_mod desc";
}

$db= new mysql();

$db->setQueryString($strLastUpQuery);
$db->getQueryResult();
$db->getNumRows();
$numRegs=$db->numRows;

$strLastUp= $strLastUpQuery . ' limit ' . $lineas*$pag . "," . $lineas;
$db->setQueryString($strLastUp);
$db->getQueryResult();

?>
<!--<div id="container_menu_tabular">-->

<div class="menuConTabs">
	<ul>
		<?php
		if ($act==1)
		{
			?>
			<li class="activa">Actualizaciones en Fichas</li>
            <?php
		}
		else
		{
			?>
			<li><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=1">Actualizaciones en Fichas</a></li>
            <?php
		}
		
		if ($act==2)
		{
			?>
			<li class="activa">Ultimas Actualizaciones en Covers</li>
            <?php
		}
		else
		{
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
  <th width="190">Grupo</th>
  <th>Titulo</th>
  <th width="62">Actualiz.</th>
</tr>
</thead>

<tbody>

<?php

$par=true;

while($LastUp=$db->fetchResults())
{
    $grupo=$LastUp['grupo'];
    $titulo=$LastUp['titulo'];
	
    if (strlen($grupo)>20)
    {
        $grupo=substr($grupo,0,20) . " ...";
    }
    if (strlen($titulo)>30)
    {
        $titulo=substr($titulo,0,30) . " ...";
    }

	$destino = "./consultas/DisConFicha.php?id_disco=" . $LastUp['id_disco'];

	$par=!$par;
	
    ?>
	<tr
	<?php 
	if($par)
	{
	echo "class='par'";
	}else
	{
	echo "class='impar'";
	}
	?>>
<!--    <td class='izq'><?php echo htmlentities(stripslashes($grupo));?></td>-->
    <td class='izq'><?php echo stripslashes($grupo);?>
    <td class='izq'><a href="<?php echo $destino;?>" rel="<?php echo $LastUp['id_disco'];?>"><?php echo stripslashes($titulo);?></a></td>
    <td class='centr'><?php echo $LastUp['fech_upf'];?></td>
    </tr>
    <?php
}
/* consultas para resumen a pie de pagina */
$strBandas="SELECT count(*) from bandas";
$strDiscos="SELECT count(*) from discos";
$strTemas ="SELECt count(*) from temas";

$db->setQueryString($strBandas);
$db->getQueryResult();
$nBandas=$db->fetchResult();

$db->setQueryString($strDiscos);
$db->getQueryResult();
$nDiscos=$db->fetchResult();

$db->setQueryString($strTemas);
$db->getQueryResult();
$nTemas=$db->fetchResult();
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

</div><!-- Fin de contenido_menu_tabular-->

<?php 

echo "<p> Actualmente hay en la base de datos " . $nBandas[0] . " bandas, " . $nDiscos[0] . " discos y " .
$nTemas[0] . " temas, Paginas necesarias: " . $numpags . "</p>";

?>

<!--</div>--><!--fin de container_menu_tabular-->


</div><!--fin de content-->

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/admin/backup/procesos/weekly_backup.php");
?>

<?php
include(PATH_INCLUDE . "pie.php");
?>

