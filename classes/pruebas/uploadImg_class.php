<?php
class uploadImg extends uploadFile
{

	protected function filtrarArchivo()
	{
		/*comprobar el tipo del archivo subido*/
		
		$this->tiposValidos=array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
				
		if(!in_array($this->archivo['type'], $this->tiposValidos))
		{
			$this->errorDesc='Tipo de archivo no permitido';
			return false;
		}
		else
		{
			switch($this->archivo['type'])
			{
				case 'image/gif':
				$this->_tipoImagen=1;
				break;
				
				case 'image/jpeg':
				$this->_tipoImagen=2;
				break;
				
				case 'image/pjpeg':
				$this->_tipoImagen=2;
				break;
				
				case 'image/png':
				$this->_tipoImagen=3;
				break;				
			}
		
			return true;
		}
	}
	

	public function convertFile($identificador=null, $tipoDestino=2)
	{
		if(!$identificador)
		{
			$this->errorDesc='No se ha proporcionado un identificador valido';
			$this->mostrarErrorUploadFile();
			exit;			
		}
		else
		{
			$this->identificador=$identificador;
		}
	
		//Comprobar si la libreria GD esta cargada
		if (!extension_loaded('gd') && !extension_loaded('gd2'))
		{
			$this->errorDesc='Libreria GD no cargada';
			$this->mostrarErrorUploadFile();
			exit;
		}		
		
		/*
		$tipoDestino: 1-> gif, 2->jpeg, 3->png
		*/
		
		$this->_tipoDestino=$tipoDestino;
		
		$this->getDestFolder();
		$this->getDestName();
		
		$img=$this->archivo['tmp_name'];
		$newfilename=$this->destFolder . $this->destName;
	   
	   	//Get Image size info
		list($width_orig, $height_orig) = getimagesize($img);
	   
		switch ($this->_tipoImagen)
		{
			case 1: 
			$im = imagecreatefromgif($img); 
			break;
			
			case 2: 
			$im = imagecreatefromjpeg($img);  
			break;
			
			case 3: 
			$im = imagecreatefrompng($img); 
			break;
			
			default:
			$this->_errorDesc='Tipo de archivo grafico no soportado';
			$this->mostrarErrorUploadFile();
			exit;			
			break;
			
		}
		//Generate the file, and rename it to $newfilename
		switch($this->_tipoDestino)
		{
			case 1:
			$result=imagegif($im,$newfilename);
			break;
			
			case 2:
			$result=imagejpeg($im,$newfilename);
			break;
			
			case 3:
			$result=imagepng($im,$newfilename);
			break;
		}
		
		
		if(!$result)
		{
			$this->_errorDesc='Error en la conversion del archivo';
			$this->mostrarErrorUploadFile();
			exit;
		}

	}
	
	protected function getDestFolder()
	{
		$this->destFolder=COVER_PATH; // dado por la aplicacion;
		
		//la imagen se guarda en una subcarpeta por grupos de 500

		$this->destFolder.= sprintf('%04d',floor($this->identificador/500)) . '/';
		
		if(!is_dir($this->destFolder))
		{
			mkdir($this->destFolder);
		}

	}	

	protected function getDestName()
	{
		//parsea el identificador a 8 numeros rellenando con ceros y se añade extension jpg
		
		$this->identificador=(int) $this->identificador;
		$this->destName = sprintf('%08s.jpg', $this->identificador);
			
	}
	
}
?>