<?php
/*
 * HOST, USER_TIBANET, PASS_TIBANET y DATABASE son constantes dadas por la aplicacion
 * 
 */

class mysqliDb
{
    protected $_conn;
    protected $_queryString;
    protected $_stmt;
    protected $_exceptMessage;
    protected $_typeList=''; // cadena de caracteres utilizado para SELECT/INSERT/UPDATE con prepared statements
    
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

        try 
        {   
            $this->_conn = @ new mysqli(HOST, USER_TIBANET, PASS_TIBANET, $baseDatos);

            if($this->_conn->connect_error)
            {
                throw new Exception;    
            }
        }
        catch(Exception $e ) 
        {
            $userMessage='Se ha producido un error en la conexi&oacute;n con la base de datos.';
            $this->_showMySqliError($userMessage);

            //aqui enviar mail con texto $exceptStrMail y/o se añade al error log
            $excepStr[]='User Message: ' . $userMessage;
            $excepStr[]='Error Message: ' . mysqli_connect_error(); 
            $excepStr[]='Error number: ' . mysqli_connect_errno();
            
            $this->_exceptMessage=$this->_buildMySqliError($e, $excepStr);
            
            $this->_logMySqliError($this->_exceptMessage);

            die();
        }


        $this->_conn->set_charset('utf8');
    }

    public function setQueryString($queryString)
    {
        
        $this->_queryString=filter_var($queryString);
        //$this->_queryString=filter_var($queryString, FILTER_SANITIZE_STRING);
        //$this->_queryString=$this->_conn->real_escape_string($this->_queryString);
    }

    public function getQueryString()
    {
        return $this->_queryString;
    }

    public function execSELECT($parametros=null)
    {
        /*
         * devuelve un array con resultados de una select
         * $valores es cuando se utilizan variables en prepared statements
         */
        
        $this->_prepareQuery();
        
        if(is_array($parametros)&&!empty($parametros))
        {
            $this->_dynamicBindParams($parametros);
        }

        $this->_executeQuery();

        $results = $this->_dynamicBindResults();

        return $results;
    }

    public function update($table, $datos, $condicion=null)
    {
        /**
         * UPDATE con prepared statements
         * $table 
         * $datos es un array con key 'campo' y valor 'valor'
         * $condicion es lo que sigue a WHERE
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

        if($condicion){
            
            $strQuery.=' WHERE ' . $condicion;
        }
        
        $this->setQueryString($strQuery);
        
        //prepare
        $this->_prepareQuery($this->_queryString);
        
        //bind parameters
        $this->_dynamicBindParams($valores);

        //execute con control de errores
        /*try
        {
            $this->_stmt->execute();
            if($this->_stmt->error)
            {
                throw new Exception;
            }
        }
        catch (exception $e)
        {
            $userMessage='Se ha producido un error al insertar un registro en la base de datos.';
            $this->_showMySqliError($userMessage);

            $excepStr[]='User Message:' . $userMessage;
            $excepStr[]='Error Message: ' . $this->_stmt->error;
            $excepStr[]='Error Number: ' . $this->_stmt->errno;
            $excepStr[]='Query:' . $this->_queryString;
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
            $excepStr[]='TypeList: ' . $this->_typeList;

            $this->_exceptMessage=$this->_buildMySqliError($e, $excepStr);
            
            $this->_logMySqliError($this->_exceptMessage);
            //$this->_mailMySqliError($this->_exceptMessage);

            die();
            
            }

        $this->_stmt->store_result();
        */
      
    }

    public function insert($table, $datos)
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
        
        //bind parameters
        $this->_dynamicBindParams($valores);

        //execute con control de errores
        try
        {
            $this->_stmt->execute();
            if($this->_stmt->error)
            {
                throw new Exception;
            }
        }
        catch (exception $e)
        {
            $userMessage='Se ha producido un error al insertar un registro en la base de datos.';
            $this->_showMySqliError($userMessage);

            $excepStr[]='User Message:' . $userMessage;
            $excepStr[]='Error Message: ' . $this->_stmt->error;
            $excepStr[]='Error Number: ' . $this->_stmt->errno;
            $excepStr[]='Query:' . $this->_queryString;
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
            $excepStr[]='TypeList: ' . $this->_typeList;

            $this->_exceptMessage=$this->_buildMySqliError($e, $excepStr);
            
            $this->_logMySqliError($this->_exceptMessage);
            //$this->_mailMySqliError($this->_exceptMessage);

            die();
            
            }

        $this->_stmt->store_result();

      
    }
    
    

    public function getNumRows()
    {
        return $this->_stmt->num_rows;
    }

    public function getLastId()
    {
        //devuelve el ultimo id insertado en una tabla con autoincrement
        return $this->_stmt->insert_id;
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

        $id=$this->_dynamicBindResults();

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

            if ($this->_conn->error) 
            {
                throw new Exception;
            }
        }
        catch(Exception $e ) 
        {

            $userMessage='Se ha producido un error en la consulta con la base de datos.';
            $this->_showMySqliError($userMessage);

            //aqui enviar mail con texto $exceptStrMail y/o se añade al error log
            $excepStr[]='User Message:' . $userMessage;
            $excepStr[]='Error Message: ' . $this->_conn->error;
            $excepStr[]='Error Number: ' . $this->_conn->errno;
            $excepStr[]='Query:' . $this->_queryString;
            
            $this->_exceptMessage=$this->_buildMySqliError($e, $excepStr);
            
            $this->_logMySqliError($this->_exceptMessage);
            //$this->_mailMySqliError($this->_exceptMessage);
            
            die();
        }

    }
    
    protected function _executeQuery()
    {
        //$this->_prepareQuery($this->_queryString);

        try
        {
            $this->_stmt->execute();
            if($this->_stmt->error)
            {
                throw new Exception;
            }
        }
        catch (exception $e)
        {
            $userMessage='Se ha producido un error en la consulta con la base de datos.';
            $this->_showMySqliError($userMessage);

            //aqui enviar mail con texto $exceptStrMail y/o se añade al error log
            $excepStr[]='User Message:' . $userMessage;
            $excepStr[]='Error Message: ' . $this->_stmt->error;
            $excepStr[]='Error Number: ' . $this->_stmt->errno;
            $excepStr[]='Query:' . $this->_queryString;
            
            $this->_exceptMessage=$this->_buildMySqliError($e, $excepStr);
            
            $this->_logMySqliError($this->_exceptMessage);
            //$this->_mailMySqliError($this->_exceptMessage);

            die();
            
            }

        $this->_stmt->store_result();

    }

    protected function _dynamicBindParams($valores)
    {
        //bind parameters
    
        foreach($valores as $key=>$valor)
        {
            $this->_typeList.=$this->_determineType($valor);//construyo la cadena
            //$valores[$key]="'{$valor}'";//meto los valores entre comillas
        }
        
        $args=array();
        $args[]= $this->_typeList;//el primer valor de args es el typelist
        
        foreach($valores as $key=>$valor)
        {
             $args[]=&$valores[$key];//y los demas son los valores
        }
        
        call_user_func_array(array($this->_stmt, 'bind_param'), $args);
    }

    protected function _dynamicBindResults() 
    {
        /*
        * This helper method takes care of prepared statements' "bind_result method
        * , when the number of variables to pass is unknown.
        *
        * @param object $stmt Equal to the prepared statement object.
        * @return array The results of the SQL fetch.
        */            

        $parameters = array();
        $results = array();

        $meta = $this->_stmt->result_metadata();

        while ($field = $meta->fetch_field()) {
         $parameters[] = &$row[$field->name];
        }

        call_user_func_array(array($this->_stmt, 'bind_result'), $parameters);

        while ($this->_stmt->fetch()) {
         $x = array();
         foreach ($row as $key => $val) {
                $x[$key] = $val;
         }
         $results[] = $x;
        }
        
        return $results;
    }

    protected function _buildMySqliError($e, $excepStr=null)
    {
            
            if(count($excepStr)==0)
            {
                $excepStr=array();
            }
            
            $excepStr[]='File:'. $e->getFile();
            $excepStr[]='Line:'. $e->getLine();
            $excepStr[]='Trace:';
            
            //para convertir este string en un array delimitado por #
            $trace=explode('#', $e->getTraceAsString());
            
            foreach($trace as $traceLine)
            {
                $excepStr[]='#' . $traceLine;
                
            }
            
            return $excepStr;
            
    }

    protected function _logMySqliError($texto)
    {

        if(!defined('PATH_WEBDATA'))
        {
            include($_SERVER['DOCUMENT_ROOT'] . "/includes/var_paths.php");
        }

        $log_file = PATH_WEBDATA . "error_log/log_file_" . date('Ymd') . ".txt";

        $fp=fopen($log_file,'a');

        fwrite($fp, '[' . date('d-M-Y: H:i:s') . ']' . "\r\n");

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

    protected function _mailMySqliError($texto)
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

    protected function _determineType($item) 
    {
        switch (gettype($item)) 
        {
         case 'string':
            return 's';
            break;

         case 'integer':
            return 'i';
            break;

         case 'blob':
            return 'b';
            break;

         case 'double':
            return 'd';
            break;
        }
    }

    function __destruct()
    {
        if(!$this->_conn->connect_error)
        {
                $this->_conn->close();
        }
    }
	
}
