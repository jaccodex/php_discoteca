<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="navigator.css" />
</head>

<body>

<?php
require_once('navigator_class.php');

$recordsPerPage=10;
$param = 'id_disco=1200';

if(isset($_GET['actPage']))
{
    $actPage=$_GET['actPage'];
}
else
{
    $actPage=0;
}

$strQuery = 'SELECT * FROM TABLE WHERE ' . $param . ' LIMIT '
. $actPage*$recordsPerPage . ',' . $recordsPerPage;

echo $strQuery . '<br/>';
$miNavegador= new navigator(
        $_SERVER['PHP_SELF'],
        175,
        $recordsPerPage,
        $param);
echo $miNavegador->showNavigator();

?>
</body>
</html>