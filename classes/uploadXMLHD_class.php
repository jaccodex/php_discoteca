<?php
class uploadXMLHD extends uploadFile
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

		$this->destName='HD_import.xml';
			
	}
	
	protected function getDestFolder()
	{
		$this->destFolder=XML_PATH;//dado por la aplicacion
		
	}

}
?>