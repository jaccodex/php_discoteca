<!DOCTYPE html>
<html>

<head>
<meta charset=UTF-8" />
<title>Discoteca</title>
<body>
<?php

$texto='Arvo PÃ¤rt';
echo 'con htmlentities: ' . htmlentities($texto,$quote=ENT_NOQUOTES,$encoding='UTF-8');
echo 'con html_entity_decode: ' . html_entity_decode($texto) . '</br>';


?>
</body>
</html>
