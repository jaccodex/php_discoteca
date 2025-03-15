<?php
require_once("definitions.php");

$all_files=PMBP_get_backup_files();
print_r($all_files);


foreach($all_files as $file) {
	echo "./".PMBP_EXPORT_DIR.$file . '<br>';
	//$filename=preg_replace("/\.*\/*/", "", "./".PMBP_EXPORT_DIR.$file);
    $parts=explode(".",$file);
	print_r($parts);
/*
	$db=PMBP_file_info("db","./".PMBP_EXPORT_DIR.$file);
    $time=PMBP_file_info("time","./".PMBP_EXPORT_DIR.$file);
	echo $db . '->' . $time;
	
*/
}


