<?php
define("PATH_INCLUDE", $_SERVER['DOCUMENT_ROOT'] . "/includes/");
define("PATH_CLASSES", $_SERVER['DOCUMENT_ROOT'] . "/classes/");

define('PATH_WEBDATA', substr($_SERVER['DOCUMENT_ROOT'],0,2) . '/webdata/myapps/appsdata/');
//define('PATH_WEBDATA', substr($_SERVER['DOCUMENT_ROOT'],0,2) . '/webdata/appsdata/myapps/');
define("COVER_PATH", PATH_WEBDATA . "DisCovers/");
define("XML_PATH", PATH_WEBDATA . "DisXML/");

//maximo tama o de MAX_FILE_SIZE en forms en 1/1000000
//cojo directamente el valor que est  indicado en php.ini
define("FORMS_MAX_FILE_SIZE", ini_get('post_max_size'));

?>