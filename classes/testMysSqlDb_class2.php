<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/calidad/var_calidad.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/var_user.php");

$strFechas="select areas.area,
periodos.periodo,
date_format(fechas.fecha_limite,'%d-%m-%y') as fecha_limite,
(now()>fechas.fecha_limite) as caducada,
fechas.ano
from fechas,areas,periodos,dptos
where fechas.id_dpto=dptos.id_dpto 
and   fechas.id_area=areas.id_area 
and   fechas.id_periodo=periodos.id_periodo
and   fechas.id_dpto=? 
and   (
datediff(now(),fechas.fecha_limite)>=?
and 
datediff(now(),fechas.fecha_limite)<=?
)
order by fechas.fecha_limite asc";

$myDb=new mysqli('localhost','tibanet','boixy66','calendario');

$stmt=$myDb->prepare($strFechas);

$id_dpto=1;
$fecha_limite_inf=-45;
$fecha_limite_sup=1000;

$stmt->bind_param('iii', $id_dpto,$fecha_limite_inf, $fecha_limite_sup);
$stmt->execute();
$stmt->bind_result($area, $periodo, $fecha_limite, $caducada, $ano);
while ($r=$stmt->fetch())
{
    echo $area . '->' . $periodo . '->' . $fecha_limite. '->' . $caducada . '->' . $ano . '</br>'; 
    
}
$stmt->close();
$myDb->close();

echo 'OK';


?>
