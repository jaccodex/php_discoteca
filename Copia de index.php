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


$lineas=15;

?>
<!--<div id="container_menu_tabular">-->

<div class="menuHorizontal">
	<ul>
		<?php
		if ($act==1)
		{
			?>
			<li><a href="#" class="actual"><span>Actualizaciones en Fichas</span></a></li>
            <?php
		}
		else
		{
			?>
			<li><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=1"><span>Actualizaciones en Fichas</span></a></li>
            <?php
		}
		
		if ($act==2)
		{
			?>
			<li><a href="#" class="actual"><span>Ultimas Actualizaciones en Covers</span></a></li>
            <?php
		}
		else
		{
			?>
			<li><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=2"><span>Ultimas Actualizaciones en Covers</span></a></li>
            <?php
		}
	?>		
	</ul>
</div>

<div id="contenido_menu_tabular">

<table class="actualizaciones" cellspacing="0" cellpadding="1">

<tr> 
  <th width="190">Grupo</th>
  <th>Titulo</th>
  <th width="62">Actualiz.</th>
</tr>

<?php

if ($act==1)
{
	$strLastUpQuery="select bandas.grupo, discos.id_disco, discos.titulo, discos.fech_up,
	date_format(discos.fech_up, '%d/%m/%y') as fech_upf
	from discos, bandas
	where discos.id_grupo=bandas.id_grupo
	order by discos.fech_up desc
	limit ";
}

if ($act==2)
{
	$strLastUpQuery="select bandas.grupo, discos.id_disco, discos.titulo, covers.fech_mod,
    date_format(covers.fech_mod, '%d/%m/%y') as fech_upf
    from covers, discos, bandas
    where discos.id_grupo=bandas.id_grupo and covers.id_disco=discos.id_disco
    order by covers.fech_mod desc
	limit ";
}

$strLastUp= $strLastUpQuery . $lineas*$pag . "," . $lineas;
$qLastUp=confirm_query($strLastUp, $connection);

$par=true;

while ($LastUp=mysql_fetch_array($qLastUp))
{
    $grupo=$LastUp['grupo'];
    $titulo=$LastUp['titulo'];
    if (strlen($grupo)>23)
    {
        $grupo=substr($grupo,0,23) . " ...";
    }
    if (strlen($titulo)>32)
    {
        $titulo=substr($titulo,0,32) . " ...";
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
    <td class='izq'><?php echo htmlentities(stripslashes($grupo));?></td>
    <td class='izq'><a href="<?php echo $destino;?>"><?php echo htmlentities(stripslashes($titulo));?></a></td>
    <td class='centr'><?php echo $LastUp['fech_upf'];?></td>
    </tr>
    <?php
}

$pagant=$pag-1;
$pagpos=$pag+1;

$strCheckAnt= $strLastUpQuery . $lineas*$pagant . "," . $lineas;
$strCheckPos= $strLastUpQuery . $lineas*$pagpos . "," . $lineas;

$enlace1=$_SERVER['PHP_SELF'] . "?act=" . $act . "&amp;pag=" . $pagant;
$enlace2=$_SERVER['PHP_SELF'] . "?act=" . $act . "&amp;pag=" . $pagpos;

?>
	<tr class="par">

    <td>
    <?php
	if (mysql_query($strCheckAnt))
	{
		echo "<a href=\"{$enlace1}\">&lt;&lt;</a>";
	}
	else
	{
		echo "&nbsp;";
	}
	?>
  	</td>
    
    <td>&nbsp;</td>
    
    <td>
    <?php
	if (mysql_query($strCheckPos))
	{
		echo "<a href=\"{$enlace2}\">&gt;&gt;</a>";
	}
	else
	{
		echo "&nbsp;";
	}
	?>
    </td>
    </tr>
    
  </table>

</div><!-- Fin de contenido_menu_tabular-->

<?php 

$strBandas="SELECT count(*) from bandas";
$strDiscos="SELECT count(*) from discos";
$strTemas ="SELECt count(*) from temas";

$qBandas=confirm_query($strBandas, $connection);
$qDiscos=confirm_query($strDiscos, $connection);
$qTemas=confirm_query($strTemas, $connection);

$nBandas=mysql_result($qBandas,0);
$nDiscos=mysql_result($qDiscos,0);
$nTemas=mysql_result($qTemas,0);

echo "<p> Actualmente hay en la base de datos " . $nBandas . " bandas, " . $nDiscos . " discos y " .
$nTemas . " temas.</p>";

?>

<!--</div>--><!--fin de container_menu_tabular-->


</div><!--fin de content-->
<?php
include(PATH_INCLUDE . "pie.php");
?>