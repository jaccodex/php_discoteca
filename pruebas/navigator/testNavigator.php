<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link type="text/css" rel="stylesheet" href="navigator.css" />
    </head>
    <body>
    <div id='content'>
        <?php

        
        require_once('navigator_class.php');
        
        $num=20;
        $recordsPerPage=2;
        
        
        $miNavegador= new navigator($_SERVER['PHP_SELF'],$num,$recordsPerPage);

        echo $miNavegador->showNavigator();
        

        
        ?>
    </div>
    </body>
</html>
