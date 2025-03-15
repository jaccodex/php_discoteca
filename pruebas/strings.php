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

$nombre = "Queensrÿche";
$nombre=strtoupper($nombre);

echo mysql_prep($nombre);



?>
</div>
<?php
include(PATH_INCLUDE . "pie.php");
?>