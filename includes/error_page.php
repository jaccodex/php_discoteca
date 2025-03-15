<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/variables.php");

require_once(PATH_INCLUDE . "cabecera.php");
?>

<script type='text/javascript' src='/js/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='/js/tips_actualizaciones.js'></script>

<link type="text/css" rel="stylesheet" href="/css/tabbed_menu.css" />
<link type="text/css" rel="stylesheet" href="/css/tips.css" />

</head>
<body>
<?php
require_once(PATH_INCLUDE . "logo.php");
require_once(PATH_INCLUDE . "menu.php");
?>

<div id="content">
    <div id="error">
<?php
$error=$_GET['id'];

if(!defined('PATH_WEBDATA'))
{
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/var_paths.php");
}

$log_file = PATH_WEBDATA . "error_log/error_log_" . $error . ".txt";

if(!file_exists($log_file))
{
    echo '<p>Ha ocurrido un error pero no se ha podido recuperar la informacion del archivo log: ' . $error . '</p>';
}
else
{
    
    $fp=fopen($log_file,'r');

    while ( ($line = fgets($fp)) !== false) 
    {
        $identificador='User Message:';

        $regexp='/^' . $identificador . '/';

        if(preg_match($regexp, $line))
        {
            echo '<p>' . str_replace($identificador,'',$line) . '</p>';
            break;
        }

    }

    fclose($fp);
    
    echo '<p>La informaci&oacute;n correspondiente ya ha sido enviada al administrador para su soluci&oacute;n.</p>';
}
    
?>
    </div><!-- fin de error -->
</div><!-- fin de content>

<?php
include(PATH_INCLUDE . "pie.php");
?>
