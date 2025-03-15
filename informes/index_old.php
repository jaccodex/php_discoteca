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

<div id="menu_listados">

<h1>Informes</h1>

<ul>
<li><a href="informe1.php">Listado de bandas con nombre NULL o vacio</a></li>
<li><a href="informe2.php">Listado de bandas sin discos asociados</a></li>
<li><a href="informe3.php">Listado de discos sin banda asociada</a></li>
<li><a href="informe4.php">Listado de temas sin disco asociado</a></li>
<li><a href="informe5.php">Listado de discos sin tracklist</a></li>
<li><a href="informe6.php">Listado de discos sin cover</a></li>
<li><a href="informe7.php">Comprobacion de tabla covers</a></li>

</ul>

</div>

</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>
