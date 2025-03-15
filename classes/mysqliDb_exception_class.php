<?php
class mysqliDB_exception extends Exception
{
    protected $_exceptMessage;// array con las lineas del mensaje de error que se pasa a la excepcion
    
    function __construct($userMessage, $excepStr)
    {
    
        parent::__construct();
        
        $this->_showMySqliError($userMessage);
        $this->_exceptMessage=$this->_buildMySqliError($excepStr);
        $this->_logMySqliError($this->_exceptMessage);
        
        //$this->_mailMySqliError($this->_exceptMessage);
        
        echo '<pre>';
        print_r($this->_exceptMessage);
        echo '</pre>';
        
    }
 
    protected function _buildMySqliError($excepStr=null)
    {

        if(count($excepStr)==0)
        {
            $excepStr=array();
        }

        $excepStr[]='File:'. $this->getFile();
        $excepStr[]='Line:'. $this->getLine();
        $excepStr[]='Trace:';

        //para convertir este string en un array delimitado por #
        $trace=explode('#', $this->getTraceAsString());

        foreach($trace as $traceLine)
        {
            $excepStr[]='#' . $traceLine;
        }
      
        return $excepStr;

    }
    
    protected function _logMySqliError()
    {

        if(!defined('PATH_WEBDATA'))
        {
            include($_SERVER['DOCUMENT_ROOT'] . "/includes/var_paths.php");
        }

        $log_file = PATH_WEBDATA . "error_log/log_file_" . date('Ymd') . ".txt";

        $fp=fopen($log_file,'a');

        fwrite($fp, '[' . date('d-M-Y: H:i:s') . ']' . "\r\n");

        $texto=$this->_exceptMessage;
        
        foreach ($texto as $linea)
        {
            $linea.= "\r\n";
            fwrite($fp, $linea);
        }
        
        fwrite($fp, str_repeat('-', 30));
        fwrite($fp, "\r\n");
        fclose($fp);
        
    }

    protected function _showMySqliError($texto)
    {
        
        echo '<div id=\'error\'>';

        echo '<p>Error: ' . $texto . '</p>';

        echo '</div>';

    }

    protected function _mailMySqliError()
    {
  
    require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/smtp.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/include/swift/lib/swift_required.php");

    $transport = new Swift_SmtpTransport(SMTP_SERVER, 25);
    $mailer = new Swift_Mailer($transport);

    $message = new Swift_Message();
    
    //para pruebas
    $para='miusuario@miredlocal.com';
    //asunto
    $asunto='Error en TibaNet';
    
    $mensaje_cab="
        <html>
        <head>
        <style type='text/css' rel='stylesheet'>
        body{ 
        color: #000;
        font-family: 'Myriad Pro',Tahoma,'Trebuchet MS',Georgia,Verdana,Arial,Helvsetica,sans-serif;
        font-size: 14px; 
        background-color: #FFFFFF;
        }
        </style>
        </head>
        <body>";

    $mensaje_pie="</body></html>";

    //$texto es un array de lineas
    $body='';

    $texto=$this->_exceptMessage;
    
    foreach($texto as $linea)
    {
        $body.='<p>' . $linea . '</p>'; 

    }

    $mensaje = $mensaje_cab . $body . $mensaje_pie;
    
    $message->setFrom(array('noresponder@tibanet.es' => 'TibaNet'));
    $message->setTo(array($para => $para));
    $message->setSubject($asunto);
    $message->setBody($mensaje, 'text/html');
    
    $check = $mailer->send($message);

    return $check;

}

}

?>
