<?php

class log {

    protected $_log_file;
    protected $_data_dir;
    protected $_puntero_fichero_log;
    
    public function __construct($data_dir)
    {
        
        $this->_setDataDir($data_dir);
        $this->_log_file = $this->_data_dir . time() . '.txt';
        
        $this->_puntero_fichero_log=fopen($this->_log_file,"w");
       
    }

    protected function _setDataDir($data_dir)
    {
        $this->_data_dir=$data_dir . '/';
        
    }
 
    public function getDataDir()
    {
        return $this->_data_dir;
        
    }
 
    public function registrarLineaLog($texto)
    {
        fputs($this->_puntero_fichero_log ,$texto . '\r\n');
    }

    public function __destruct()
    {
        fclose($this->_puntero_fichero_log);
    }
    
}

class logXML extends log{

    protected function _setDataDir($data_dir)
    {
        $this->_data_dir=$data_dir . '/DisXML/';
        
    }    
    
}

?>
