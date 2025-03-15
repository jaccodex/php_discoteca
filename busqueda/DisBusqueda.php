<?php
include($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

include(PATH_INCLUDE . "cabecera.php");
?>
<script type="text/javascript" src="/js/DisBusqueda.js"></script>

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

<div class="formulario">
<form action="/consultas/DisConDiscos.php" method="post">

<label for="banda">Banda a buscar:</label>
<input type="text" class="largo" name="banda" id="banda" />
<input type="hidden" name="id_grupo" id="id_grupo" />

<input type="submit" class="submit" name="Buscar" id="buscar" />

</form>

</div>

<div class="respuesta">

</div>

</div><!--fin de main  -->
</div><!--fin de content  -->
<?php
include(PATH_INCLUDE . "pie.php");
?>