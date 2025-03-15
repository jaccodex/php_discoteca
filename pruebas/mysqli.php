<?php

define("HOST", 'localhost');
define("USERNAME", 'johndoe');
define("PASSWORD", 'whoknows');

define("DATABASE", 'discoteca');

$conn = new mysqli(HOST,USERNAME,PASSWORD);

if(!$conn)
{
	echo 'error de conexion:' . $conn->connect_error;
	exit;
}

if(!$conn->select_db(DATABASE))
{
	echo 'error en seleccion de base de datos:' . $conn->error;
	exit;
}

$strQuery='SELECT * FRO M hd_bandas limit 10';

$result=$conn->query($strQuery);

if(!$result)
{
	echo 'error en la query:' . $conn->error;
	exit;
}

while($fila=$result->fetch_array())
{
	echo '<p>' . $fila[0] . ',' . $fila[1] . '</p>';
}

$conn->close();

?>