<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="navigator.css" />
</head>

<body>

<?php
require_once('indiceAlfabetico_class.php');
require_once('navigator_class.php');

$iniciales=array();

$iniciales[]='A';
$iniciales[]='B';
$iniciales[]='C';
$iniciales[]='D';
$iniciales[]='E';
$iniciales[]='F';
$iniciales[]='G';
$iniciales[]='H';
$iniciales[]='I';
$iniciales[]='J';
$iniciales[]='K';
$iniciales[]='L';
$iniciales[]='M';

$miIndiceAlfabetico= new indiceAlfabetico(
        $_SERVER['PHP_SELF'],
        $iniciales
        );

echo $miIndiceAlfabetico->showNavigator();

$recordsPerPage=10;

if(isset($_GET['actPage']))
{
    $actPage=$_GET['actPage'];
}
else
{
    $actPage=0;
}

if(isset($_GET['actPageI']))
{
    $actPageI=$_GET['actPageI'];
}
else
{
    $actPageI=0;
}

$condicion = 'iniciales=' . $iniciales[ $actPageI];

$strQuery = 'SELECT * FROM TABLE WHERE ' . $condicion . ' LIMIT '
. $actPage*$recordsPerPage . ',' . $recordsPerPage;

echo $strQuery . '<br/>';
$miNavegador= new navigator(
        $_SERVER['PHP_SELF'],
        175,
        $recordsPerPage);
echo $miNavegador->showNavigator();

?>
</body>
</html>