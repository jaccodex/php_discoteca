<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
include("../DisFunciones.php");

if (!ConectarDis("root","vampire"))
	{
    echo "No se ha podido conectar a la web ...";
    echo "<br>";
    exit;
    }else
	{
	echo "Conectado a la web";
    echo "<br>";
	}
$strDis = "SELECT DISTINCT id_disco FROM temas ORDER BY id_disco";
$qDis = confirm_query($strDis);

while ($disco=mysql_fetch_array($qDis))
	{
	echo "ID-DISCO:" . $disco["id_disco"];
	echo "<br>";
	$strTemas="SELECT id_tema, numero, titulo, duracion FROM temas WHERE id_disco=" . $disco["id_disco"] .
	" ORDER BY id_tema";
	$qTemas=confirm_query($strTemas);
	$num=0;
	while ($tema=mysql_fetch_array($qTemas))
		{
		$num++;
		echo $tema["id_tema"]. "-" . $num . "-" . $tema["titulo"];
		$strUp="UPDATE temas SET numero=$num WHERE id_tema=" . $tema["id_tema"];
		if (confirm_query($strUp))
			{echo "-->OK";}
			else
			{echo "-->ERROR";}
		echo "<br>";
		}
		
	}

mysql_close();	
	
?>	
</body>
</html>
