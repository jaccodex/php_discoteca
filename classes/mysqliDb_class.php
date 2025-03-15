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
    protected $_exceptMessage;// array con las lineas del mensaje de error que se pasa a la excepcion
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
            $this->_conn = @ new mysqli(HOST, USERNAME, PASSWORD, $baseDatos);

            if($this->_conn->connect_error)
            {
                $userMessage='Se ha producido un error en la conexi&oacute;n con la base de datos.';
                
                $excepStr[]='User Message: ' . $userMessage;
                $excepStr[]='Error Message: ' . mysqli_connect_error(); 
                $excepStr[]='Error number: ' . mysqli_connect_errno();
                
                throw new mysqliDb_exception($userMessage, $excepStr);    
            }
        }
        catch(mysqliDb_exception $e ) 
        {
            die();
        }


        $this->_conn->set_charset('utf8');
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

    public function execUPDATE($table, $datos, $condicion=null)
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
        
        //bind parameters
        $this->_dynamicBindParams($valores);

        //execute con control de errores

        $this->_executeQuery($datos);
      
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
                
        while($num!==0){
            if($num==1){
                $strQuery.='?)';
            }
            else{
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

        $this->_executeQuery($datos);
      
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
        
        //bind parameters
        $this->_dynamicBindParams($valores);

        //execute con control de errores

        $this->_executeQuery($datos);
      
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
                $userMessage='Se ha producido un error en la consulta con la base de datos.';
                
                $excepStr[]='User Message:' . $userMessage;
                $excepStr[]='Error Message: ' . $this->_conn->error;
                $excepStr[]='Error Number: ' . $this->_conn->errno;
                $excepStr[]='Query:' . $this->_queryString;               
                
                throw new mysqliDb_exception($userMessage, $excepStr);
            }
        }
        catch(mysqliDb_exception $e) 
        {
            die();
        }

    }
    
    protected function _executeQuery($parametros=null)
    {

        try
        {
            $this->_stmt->execute();
            if($this->_stmt->error)
            {
                $userMessage='Se ha producido un error en la consulta con la base de datos.';

                $excepStr[]='User Message:' . $userMessage;
                $excepStr[]='Error Message: ' . $this->_stmt->error;
                $excepStr[]='Error Number: ' . $this->_stmt->errno;
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
                    $excepStr[]='TypeList: ' . $this->_typeList;
                }                
                
                throw new mysqliDb_exception($userMessage, $excepStr);
            }
        }
        catch (mysqliDb_exception $e)
        {

            die();
            
        }

        $this->_stmt->store_result();
        

    }

    protected function _dynamicBindParams($valores)
    {
        //bind parameters
        $this->_typeList='';
        
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
         /*
         * no funciona el informe de errores - revisar
         */
        try
        {
            if(!@call_user_func_array(array($this->_stmt, 'bind_param'), $args))
            {
                $userMessage='Se ha producido un error en la consulta con la base de datos (bind param).';

                $excepStr[]='User Message:' . $userMessage;
                $excepStr[]='Error Message: ' . $this->_conn->error;
                $excepStr[]='Error Number: ' . $this->_conn->errno;
                $excepStr[]='Query:' . $this->_queryString;
                foreach($args as $key=>$valor)
                {
                    $excepStr[]='parametro: key:' . $key . ', valor:' . $valor;
                }
                
                throw new mysqliDb_exception($userMessage, $excepStr);
            }
        }
        catch (mysqliDb_exception $e)
        {

            die();
            
         }

    }

    protected function _dynamicBindResults() 
    {

        $parameters = array();
        $results = array();

        $meta = $this->_stmt->result_metadata();

        while ($field = $meta->fetch_field()) {
         $parameters[] = &$row[$field->name];
        }
        /*
         * no funciona el informe de errores - revisar
         */

        try
        {
            if(!@call_user_func_array(array($this->_stmt, 'bind_result'), $parameters))
            {
                
                $userMessage='Se ha producido un error en la consulta con la base de datos (bind results).';
                $excepStr[]='User Message:' . $userMessage;
                $excepStr[]='Error Message: ' . $this->_stmt->error;
                $excepStr[]='Error Number: ' . $this->_stmt->errno;
                $excepStr[]='Query:' . $this->_queryString;                
                
                throw new mysqliDb_exception($userMessage, $excepStr);
            }
        }
        catch (mysqliDb_exception $e)
        {

            die();
            
         }

         while ($this->_stmt->fetch()) {
         $x = array();
         foreach ($row as $key => $val) {
                $x[$key] = $val;
         }
         $results[] = $x;
        }
        
        return $results;
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
        default:
            return 's';
            break;
         }
        
    }

    function __destruct()
    {
        if(!$this->_conn->connect_error)
        {
                $this->_conn->close();
        }
        $this->_stmt->close();
    }
	
}
