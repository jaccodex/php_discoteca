<?php

require_once('log.php');
define('DATADIR', 'd:/webdata');

$miLog=new log(DATADIR);
echo $miLog->getDataDir();
$miLog->registrarLineaLog('Esto es una prueba de log');

?>
