<?php
//include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/calidad/var_calidad.php");
define("HOST", 'localhost');
define("DATABASE", 'calendario');
//include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/var_user.php");
define("USER_TIBANET","tibanet");
define("PASS_TIBANET", 'boixy66');

include('mySqliDb_class.php');
include('mySqliDb_exception_class.php');

$db= new mysqliDb('calendario');

/*
$strFechas="select areas.area,
periodos.periodo,
date_format(fechas.fecha_limite,'%d-%m-%y') as fecha_limite,
(now()>fechas.fecha_limite) as caducada,
fechas.ano
from fechas,areas,periodos,dptos
where fechas.id_dpto=dptos.id_dpto 
and   fechas.id_area=areas.id_area 
and   fechas.id_periodo=periodos.id_periodo
and   fechas.id_dpto=? and   
(datediff(now(),fechas.fecha_limite)>=? and 
datediff(now(),fechas.fecha_limite)<=? )
order by fechas.fecha_limite asc";

$valores=array(
    'id_dpto'=>1,
    'fecha_limite_inf'=>-45,
    '$fecha_limite_sup'=>100);

$db->setQueryString($strFechas);
$results=$db->execSELECT($valores);

print_r($results);

*/
/*
$strFechas="SELECT * FROM areas";
$db->setQueryString($strFechas);
$results=$db->execSELECT();
*/

$datos=array(
    'id_area'=>5,
    'id_dpto'=>2,
    'area'=>'Area de Prueba2'
        );
//$condicion=array('id_area'=>5);

$db->execINSERT('areas', $datos);

echo $db->getQueryString();


?>
<pre>
<?php
//print_r($results);
?>
</pre>
<?php
?>
