<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>


<?php
if (isset($_POST['enviar']))
{

	define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/pruebas_upload/');
	
	require_once('uploadFile_class.php');
	require_once('uploadPdf_class.php');
	
	$upload=new uploadPdf($_FILES['archivo']);
	
	$upload->saveResult(125);
	
	//print_r($_FILES);
}
else
{
?>

<form method="post" enctype="multipart/form-data" action="">

<input type='file' name='archivo' />
<input type="submit" name='enviar' value='guardar' />
</form>
<?php
}
?>
</body>
</html>
