<?php
class uploadPdf extends uploadFile
{

	protected function filtrarArchivo()
	{
		/*comprobar el tipo del archivo subido*/
		
		$this->tiposValidos=array('application/pdf');
				
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
	
	protected function getDestFolder()
	{
		$this->destFolder=UPLOAD_PATH; // dado por la aplicacion;
	}	

	protected function getDestName()
	{
		//parsea el identificador a 8 numeros rellenando con ceros y se añade extension pdf
		
		$this->identificador=(int) $this->identificador;
		$this->destName = sprintf('%08s.pdf', $this->identificador);
			
	}
	
}
?>