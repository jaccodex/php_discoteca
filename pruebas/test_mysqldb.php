<?php
require_once('mysqldb.php');
require_once('variables_db_connect.php');

$mySql= new mySqlDb(HOST, USERNAME, PASSWORD, DATABASE);
$mySql->query('SELECT * FROM estilos LIMIT 10');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
    TODO write content
  </body>
</html>
