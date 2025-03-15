<?php
/*
 * HOST, USERNAME, PASSWORD y DATABASE son constantes dadas por la aplicacion
 * 
 */

class pdoDb
{
    protected $_conn;
    protected $_queryString;
    protected $_stmt;
    protected $_exceptMessage;// array con las lineas del mensaje de error que se pasa a la excepcion
    
    public function __construct($bd=NULL)
    {
        //se pasa bd cuando se quiere conectar a otra bd distinta de la de por defecto
        if(is_null($bd))
        {
            $baseDatos=DATABASE;
        }
        else
        {
            $baseDatos=$bd;
        }

        $pdo_conn_str='mysql:host=' . HOST . ';dbname=' . DATABASE;
        
        try 
        {   
            $this->_conn = new pdo($pdo_conn_str, USERNAME, PASSWORD);
            
        }
        catch(PDOException $e ) 
        {
        
            $this->_error=$e;
            
            $this->_error->_userMessage='Se ha producido un error en la conexi&oacute;n con la base de datos.';

            $excepStr[]='User Message: ' . $this->_error->_userMessage;

            $this->_error->_excepStr=$excepStr;
            
            $this->_processError();
            
            //var_dump($e);
            
            die();
        }

        $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_conn->exec('SET NAMES "utf8"');
    }

    public function setQueryString($queryString)
    {
        $this->_queryString=filter_var($queryString);
    }

    public function getQueryString()
    {
        return $this->_queryString;
    }

    public function execSELECT($parametros=null)
    {
        /*
         * devuelve un array con resultados de una select
         */
        
        $this->_prepareQuery();
        
        if(is_array($parametros)&&!empty($parametros))
        {
            $this->_executeQuery($parametros);
        }
        else 
        {
            $this->_executeQuery();
        }
    
        $results = $this->_bindResults();

        return $results;
    }

    public function execUPDATE($table, $datos, $condicion=null)
    {
        /**
         * UPDATE con prepared statements
         * $table 
         * $datos es un array con key 'campo' y valor 'valor'
         * $condicion es un array con lo que sigue a WHERE
         * 
         */
        
        $campos=array_keys($datos);
        $valores=array_values($datos);
        $num=count($datos);
        
        //construyo la query
        $strQuery='UPDATE ' . $table ;
        
        $strQuery.= ' SET ';
                
        foreach($campos as $campo)
        {
            $strQuery.= $campo . '=?';
            
            if($num!==1)
            {
                $strQuery.= ', ';
            }
          
            $num--;
        }

        if(is_array($condicion)&&!empty($condicion))
        {
            $camposCond=array_keys($condicion);
            $valoresCond=array_values($condicion);
            $num=count($condicion);

            
            $strQuery.= ' WHERE (';
                
            foreach($camposCond as $campo)
            {
                if($num==count($condicion))
                {
                    $strQuery.= $campo . '=?';
                }
                else
                {
                    $strQuery.= ' AND ' . $campo .  '=?';               
                }
            
                $num--;
            }   

            $strQuery.= ')';

            $valores=array_merge($valores, $valoresCond);
        }
        
        $this->setQueryString($strQuery);
        
        //prepare
        $this->_prepareQuery($this->_queryString);
        
        //execute con control de errores

        $this->_executeQuery($valores);
      
    }

    public function execINSERT($table, $datos)
    {
        /**
         * INSERT con prepared statements
         * $table 
         * $datos es un array con key 'campo' y valor 'valor'
         */
        
        $campos=array_keys($datos);
        $valores=array_values($datos);
        $num=count($datos);
        
        //construyo la query
        $strQuery='INSERT INTO ' . $table . '(';
        
        $strQuery.= implode($campos,', ');
        $strQuery.=')';
        
        $strQuery.= ' VALUES(';
                
        while($num!==0)
        {
            if($num==1)
            {
                $strQuery.='?)';
            }
            else
            {
                $strQuery.='?, ';               
            }
            
            $num--;
        }

        $this->setQueryString($strQuery);
        
        //prepare
        $this->_prepareQuery($this->_queryString);
        
        //execute con control de errores

        $this->_executeQuery($valores);
      
    }
    
    public function execDELETE($table, $datos)
    {
        /**
         * DELETE con prepared statements
         * $table 
         * $datos es un array con key 'campo' y valor 'valor'
         * todos van unidos con AND
         */
        
        $campos=array_keys($datos);
        $valores=array_values($datos);
        $num=count($datos);
        
        //construyo la query
        $strQuery='DELETE FROM ' . $table;
        
        $strQuery.= ' WHERE (';
                
        foreach($campos as $campo)
        {
            if($num==count($datos))
            {
                $strQuery.= $campo . '=?';
            }
            else
            {
                $strQuery.= ' AND ' . $campo .  '=?';               
            }
            
            $num--;
        }

        $strQuery.= ')';
        
        $this->setQueryString($strQuery);
        
        //prepare
        $this->_prepareQuery($this->_queryString);
        
        //execute con control de errores

        $this->_executeQuery($valores);
      
    }
    
    public function getNumRows()
    {
        /*
         * devuelve el numero de filas afectadas por un insert, update, delete
         */
        return $this->_stmt->rowCount();
    }

    public function getLastId()
    {
        //devuelve el ultimo id insertado en una tabla con autoincrement
        return $this->_conn->lastInsertId();
    }

    public function getNextId($tabla, $campo, $condicion=null)
    {
        //devuelve el siguiente id en un campo no autoincrement

        $strQuery='SELECT MAX(' . $campo . ') as maxId FROM ' . $tabla;

        if($condicion)
        {
            $strQuery .= ' ' . $condicion;

        }
        $this->setQueryString($strQuery);
        
        $this->_prepareQuery();
        
        $this->_executeQuery();

        $id=$this->_bindResults();

        $miId=$id[0]['maxId'];

        if(is_null($miId))
        {
            $miId==0;
        }

        $miId++;

        return $miId;
    }

    protected function _prepareQuery()
    {

        try 
        {    
            $this->_stmt=$this->_conn->prepare($this->_queryString);
            
            if(!$this->_stmt)
            {
                throw new PDOException;
            }
        }
        catch(PDOException $e) 
        {
            $this->_error=$e;

            $this->_error->_userMessage='Se ha producido un error en la consulta con la base de datos (prepare).';

            $excepStr[]='User Message:' . $this->_error->_userMessage;
            $excepStr[]='Query:' . $this->_queryString;               

            $this->_error->_excepStr=$excepStr;
            
            $this->_processError();
            
            die();
        }

    }
    
    protected function _executeQuery($parametros=null)
    {

        /*
         * $parametros es un array de valores que se pasan en prepared statements
         * no uso placeholders
         */
        try
        {
            $execute_error=$this->_stmt->execute($parametros);

            if(!$execute_error)
            {
                throw new PDOException;
            }
                
        }
        catch (PDOException $e)
        {
            $this->_error=$e;
            
            $this->_error->_userMessage='Se ha producido un error en la consulta con la base de datos (execute).';

            $excepStr[]='User Message:' . $this->_error->_userMessage;
            $excepStr[]='Query:' . $this->_queryString;
            
            
            if(is_array($parametros)&&!empty($parametros))
            {

                $campos=array_keys($parametros);
                $valores=array_values($parametros);

                $excepStr[]='Campos: ';
                foreach($campos as $campo)
                {
                    $excepStr[]= '-' . $campo;
                }
                $excepStr[]='Valores: ';
                foreach($valores as $valor)
                {
                    $excepStr[]= '-' . $valor;
                }
            }                
            $this->_error->_excepStr=$excepStr;

            $this->_processError();

            die();
            
        }

        //$this//->_stmt->store_result();

    }


    protected function _bindResults() 
    {

        try
        {
            $results = $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->_error=$e;

            $this->_error->_userMessage='Se ha producido un error en la consulta con la base de datos (bind results).';

            $excepStr[]='User Message:' . $this->_error->_userMessage;
            $excepStr[]='Query:' . $this->_queryString;

            $this->_error->_excepStr=$excepStr;
            $this->_processError();

            die();
            
         }

        return $results;
    }

    function __destruct()
    {
        /*
        if(!$this->_conn->connect_error)
        {
                $this->_conn->close();
        }
        */
    }
    
    protected function _processError()
    {
        
        $this->_error->_exceptMessage=$this->_buildErrorInfo($this->_error->_excepStr);
        $this->_error->_time=time();

        $this->_logError();//ojo, el log del error es necesario para que se muestre el mensaje al usuario correctamente
        
        //$this->_mailError();

        $this->_showError();

    }
    
    protected function _showError()
    {
        header('location:/includes/error_page.php?id=' . $this->_error->_time);
    }

    protected function _buildErrorInfo($excepStr)
    {
        if(count($excepStr)==0)
        {
            $excepStr=array();
        }

        $excepStr[]='Error Message: ' . $this->_error->getMessage(); 
        $excepStr[]='Error number: ' . $this->_error->getCode();
        $excepStr[]='File:'. $this->_error->getFile();
        $excepStr[]='Line:'. $this->_error->getLine();
        $excepStr[]='Trace:';

        //para convertir este string en un array delimitado por #
        $trace=explode('#', $this->_error->getTraceAsString());

        foreach($trace as $traceLine)
        {
            $excepStr[]='#' . $traceLine;
        }
      
        return $excepStr;
    }
    
    protected function _logError()
    {

        if(!defined('PATH_WEBDATA'))
        {
            include($_SERVER['DOCUMENT_ROOT'] . "/includes/var_paths.php");
        }

        $log_file = PATH_WEBDATA . "error_log/error_log_" . $this->_error->_time . ".txt";

        $fp=fopen($log_file,'a');

        fwrite($fp, '[' . date('d-M-Y: H:i:s',$this->_error->_time) . ']' . "\r\n");

        $texto=$this->_error->_exceptMessage;
        
        foreach ($texto as $linea)
        {
            $linea.= "\r\n";
            fwrite($fp, $linea);
        }
        
        fwrite($fp, str_repeat('-', 30));
        fwrite($fp, "\r\n");
        fclose($fp);
        
    }
    
    protected function _mailError()
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

    $texto=$this->_error->_exceptMessage;
    
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

