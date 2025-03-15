<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
<title>Discoteca</title>
<body>
<?php
if(isset($_POST['subir']))
{
	require('uploadFile.class.php');
	require('uploadImg.class.php');
	define('COVER_PATH',$_SERVER['DOCUMENT_ROOT'] . '/');
	$miUpload=new uploadImg($_FILES['archivo']);
	$miUpload->convertFile(125);

}
else
{
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">

<fieldset>

<legend>Subir Archivo</legend>

<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
    
<p><label for="archivo">Archivo:</label>
<input class="text" type="file" id="archivo" name="archivo" size="40" maxlength="256" /></p>

</fieldset>
<p><input class="submit" type="submit" name="subir" value="subir" /></p>
</form>
<?php
}
?>
</body>
</html>