<HTML>
<HEAD><link href="style.css" rel="stylesheet" type="text/css"></HEAD>
<BODY>

<?php
include("DisFunciones.php");

if (!ConectarDis("root","vampire"))
	{
    echo "No se ha podido conectar a la web ...";
    echo "<br>";
    exit;
    }
	else
	{
    echo "Conectado a la web ...";
	echo "<br>";
	}
//-------------------------------------
/* la tabla importar contiene los datos
id_grupo->0
grupo
id_disco->0
disco
ano
pista
titulo
duracion
*/
//--------------------------------------
//importacion de bandas

echo "Procesando bandas ...";
echo "<br>";

$strImpBandas="SELECT DISTINCT grupo FROM importar WHERE id_grupo=0";

$qImpBandas = mysql_query($strImpBandas);

while ($ImpBandas=mysql_fetch_array($qImpBandas))
	{
	
	//echo $ImpBandas["grupo"];
	$grupo=strtoupper($ImpBandas["grupo"]);

	$strBanda="SELECT id_grupo FROM bandas WHERE grupo=\"$grupo\"";
	$qBanda=mysql_query($strBanda);

	if (mysql_num_rows($qBanda)==0)
		{// es un grupo nuevo

			$strAddBanda="INSERT INTO bandas (grupo) VALUES (\"$grupo\")";

			if (!$qAddBanda=mysql_query($strAddBanda))
				{
				echo "Error al agregar banda:" . $strAddBanda . "<br>\n";
				$id_grupo=0;
				}
				else
				{
				$strID="SELECT LAST_INSERT_ID() FROM bandas";
				$qID=mysql_query($strID);
				$id_grupo=mysql_result($qID,0);
				}

		}
		else
		{// el grupo ya existe

			$id_grupo=mysql_result($qBanda,0);	

		}

	$strUpdate="UPDATE importar SET id_grupo=\"$id_grupo\" WHERE grupo=\"$grupo\"";

	if (!mysql_query($strUpdate))
		{
		echo "Error al modificar id_grupo:" . $strUpdate;
		}


	}

echo "Bandas a importar procesadas";
echo "<br>";

	
//importacion de discos

echo "Procesando discos ...";
echo "<br>";

$strImpDiscos="SELECT DISTINCT id_grupo, ano, disco FROM importar WHERE id_disco=0";

$qImpDiscos = mysql_query($strImpDiscos);

while ($ImpDiscos=mysql_fetch_array($qImpDiscos))
	{

	$id_grupo=$ImpDiscos["id_grupo"];
	$id_disco=$ImpDiscos["id_disco"];
	$ano     =$ImpDiscos["ano"];
	$disco   =strtoupper($ImpDiscos["disco"]);

	$strDisco="SELECT id_disco FROM discos WHERE id_grupo=\"$id_grupo\" 
	AND ano=\"$ano\" AND titulo=UPPER(\"$disco\")";
	$qDisco=mysql_query($strDisco);

	
	if (mysql_num_rows($qDisco)==0)
		{// es un disco nuevo

			$strAddDisco="INSERT INTO discos (id_grupo, titulo, ano, fech_add) VALUES (\"" . $id_grupo . "\", \"" . $ImpDiscos["disco"] . "\", \"" . $ano . "\", \"" . date("Y-m-d H:i:s") . "\")";

			//echo $strAddDisco . "<br>\n";
			
			if (!$qAddDisco=mysql_query($strAddDisco))
				{
				echo "Error al agregar disco:" . $strAddDisco . "<br>\n";
				$id_disco=0;
				}
				else
				{
				$strID="SELECT LAST_INSERT_ID() FROM discos";
				$qID=mysql_query($strID);
				$id_disco=mysql_result($qID,0);
				}

		}
		else
		{// el disco ya existe

			$id_disco=mysql_result($qDisco,0);	

		}

	$strUpdate="UPDATE importar SET id_disco=\"$id_disco\" WHERE id_grupo=\"$id_grupo\" AND ano=\"$ano\" AND disco=\"" . $ImpDiscos["disco"] . "\"";

	echo $strUpdate . "<br>\n";

	if (!mysql_query($strUpdate))
		{
		echo "Error al modificar id_disco:" . $strUpdate;
		}

	}

echo "Discos a importar procesados";
echo "<br>";


echo "Importando temas ...";
echo "<br>";

$strImpTemas="SELECT id_disco, ano, pista, titulo, duracion FROM importar";

$qImpTemas = mysql_query($strImpTemas);

while ($ImpTemas=mysql_fetch_array($qImpTemas))
		{
		$strCheck="SELECT id_tema FROM temas WHERE id_disco=\"" . $ImpTemas["id_disco"] . "\" AND numero=\"" . $ImpTemas["pista"] . "\"";
		
		$qCheck=mysql_query($strCheck);

		if (mysql_num_rows($qCheck)==0)
			{
				$strUp="INSERT INTO temas (id_disco, numero, titulo, duracion) VALUES (\"" . $ImpTemas["id_disco"] . "\" , \"" . $ImpTemas["pista"] . "\" ,\"" . ucwords($ImpTemas["titulo"]) . "\", \"" . $ImpTemas["duracion"] . "\")";
			}
			else
			{
				$Check=mysql_fetch_array($qCheck);
				$id_tema=$Check["id_tema"];

				$strUp="UPDATE temas SET titulo=\"" . ucwords($ImpTemas["titulo"]) . "\" , duracion=\"" . $ImpTemas["duracion"] . "\" WHERE id_tema=\"" . $id_tema . "\"";
			}

		if (!mysql_query($strUp))
			{
				echo $strUp . "-->ERROR";
			}
			else
			{
				$strDel="DELETE FROM importar WHERE id_disco=\"" . $ImpTemas["id_disco"] . "\" AND pista=\"" . $ImpTemas["pista"] . "\"";

				if (!mysql_query($strDel))
					{
					echo "Error al borrar registro:" . $strDel;
					}

			}

		}
echo "Temas a importar procesados";				
echo "<br>";

mysql_close();
	
	
?>
</BODY>
</HTML>