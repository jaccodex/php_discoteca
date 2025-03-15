<?php
  class mySqlDb
  {
      protected $_mysql;
      protected $_query;
      
      function __construct($host, $username, $password, $db)
      {
        if(!$this->_mysql=new mysqli($host, $username, $password, $db))
        {
            trigger_error('Error conectando con la base de datos',E_USER_ERROR);
        }
      }
      
      function query($query)
      {
        $this->_query=filter_var($query,FILTER_SANITIZE_STRING);
        
        $stmt=$this->_prepareQuery();
        
        $stmt->execute();
        $results = $this->_dynamicBindResults($stmt);
      }

      protected function _dynamicBindResults($stmt)
      {
          $meta=$stmt->result_metadata();

          while($field=$meta->fetch_field())
          {
              echo '<pre>';
              print_r($field);
              echo '</pre>';
          }

      }

      protected function _prepareQuery()
      {
          if(!$stmt=$this->_mysql->prepare($this->_query))
          {
              trigger_error('Error preparando query', E_USER_ERROR);
          }

          return $stmt;
      }

      function __destruct()
      {
          $this->_mysql->close();
      }
  }
?>
