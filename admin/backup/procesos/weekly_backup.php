<?php
// This code was created by phpMyBackupPro v.2.1 
// http://www.phpMyBackupPro.net
$_POST['db']=array("discoteca");
$_POST['tables']="on";
$_POST['data']="on";
$_POST['drop']="on";

$period=(3600*24)*15;

$security_key="2e534826ef9188b4f62818b7a947a92d";
// This is the relative path to the phpMyBackupPro v.2.1 directory
@chdir("./admin/backup");
@include("backup.php");
?>