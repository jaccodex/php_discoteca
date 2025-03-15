<?php
class uploadXML extends uploadFile
{


	protected function filtrarArchivo()
	{

		$this->tiposValidos=array('text/xml');

		if(!in_array($this->archivo['type'], $this->tiposValidos))
		{
			$this->errorDesc='Tipo de archivo no permitido';
			return false;
		}
		else
		{
			return true;
		}

	}
	
	protected function getDestName()
	{

		$this->destName='import.xml';
			
	}
	
	protected function getDestFolder()
	{
		$this->destFolder=XML_PATH;//dado por la aplicacion
		
	}

}
?>