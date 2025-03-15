<?php

class log {

    protected $_log_file;
    protected $_data_dir;
    protected $_puntero_fichero_log;
    
    public function __construct($data_dir)
    {
        
        $this->_setDataDir($data_dir);
        $this->_log_file = $this->_data_dir . time() . '.txt';
        
        $this->_puntero_fichero_log= @ fopen($this->_log_file,"w");
		
       	try
		{
			if(!$this->_puntero_fichero_log)
			{
				$excepStr='
				<p>No se ha podido abrir el archivo de logs: ' . $this->_log_file . '</p>';
				
				throw new exception($excepStr);
			}
		}
		catch (exception $e)
		{
			echo '<div id=\'error\'>';
			echo $e->getMessage() . '<br/>';
			echo 'Script: ' . $e->getFile() . '<br/>';
			echo 'Linea: ' . $e->getLine() . '<br/>';
			echo '</div>';
			die();
		}
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
        @ fclose($this->_puntero_fichero_log);
    }
    
}

?>
