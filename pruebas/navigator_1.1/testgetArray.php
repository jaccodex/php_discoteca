<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
		require_once('testGetArray_class.php');
  
       $miTestGet = new testGetArray();

       print_r($miTestGet->readGetVars());
 
  
        ?>
    </body>
</html>
