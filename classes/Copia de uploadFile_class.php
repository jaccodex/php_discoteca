<?php
class uploadFile
{
	protected $archivo;
	protected $estado;//estado del upload -> 0 : ok, > 0 : error
	protected $errorDesc;//descripcion del error
	protected $tiposValidos;
	protected $destName;
	protected $destFolder;
	protected $identificador;

	function __construct($archivo=null, $identificador=null)
	{
	
		//si no se ha indicado un archivo, abortar
		if(!$archivo||!($archivo['name']))
		{
			$this->errorDesc='No se ha indicado un archivo';
			$this->mostrarErrorUploadFile();
			exit;
		}
		else
		{
			$this->archivo=$archivo;
			$this->estado=$this->archivo['error'];
		}
		
		//comprobar el tipo del archivo subido
		
		if(!$this->filtrarArchivo())
		{
			$this->mostrarErrorUploadFile();
			exit;
		}
		
		//comprobar que el archivo se ha subido
		if($this->estado>0)
		{
			switch ($this->estado)
			{
				case 1:
				$this->errorDesc='El archivo subido excede la directiva upload_max_filesize en php.ini';
				break;

				case 2:
				$this->errorDesc='El archivo subido excede la directiva MAX_FILE_SIZE que fue especificada en el formulario HTML.';
				break;

				case 3:
				$this->errorDesc='El archivo subido fue sólo parcialmente cargado.';
				break;

				case 4:
				$this->errorDesc='Ningún archivo fue subido.';				
				break;

				case 6:
				$this->errorDesc='Falta la carpeta temporal.';
				break;	  

				case 6:
				$this->errorDesc='No se pudo escribir el archivo en el disco.';
				break;
			}
			$this->mostrarErrorUploadFile();
			exit;
		}

	}
	
	public function saveResult($identificador=null)
	{
		$this->identificador=$identificador;	

		$this->getDestFolder();
		$this->getDestName();
		
		$this->destino = $this->destFolder . '/' . $this->destName;
		
		if(!move_uploaded_file($this->archivo['tmp_name'], $this->destino))
		{
			$this->errorDesc='No se ha podido mover el archivo a : ' .  $this->destino;
			$this->mostrarErrorUploadFile();
			exit;
		}

	}
	
	public function comprimir()
	{
		$partesNombre=explode('.',$this->destino);
		$destZipName=$partesNombre[0] . '.zip';
		
		$zip = new ZipArchive();
		$zip->open($destZipName, ZIPARCHIVE::CREATE);
		$zip->addfile($this->destino, basename($this->destino));
		$zip->close();
		
		unlink($this->destino);
	}
	
	
	
	protected function mostrarErrorUploadFile()
	{
		$texto_error='<div class=\'error\'>';
		$texto_error.= "Error: " . $this->errorDesc. "<br />";
		$texto_error.= "Archivo: " . $this->archivo["name"] . "<br />";
		$texto_error.= "Type: " . $this->archivo["type"] . "<br />";
		$texto_error.= "Size: " . ($this->archivo["size"] / 1024) . " Kb<br />";
		$texto_error.= "Stored in: " . $this->archivo["tmp_name"]. "<br />";
		$texto_error.= "</div>";
		
		echo $texto_error;
	}
	
	protected function filtrarArchivo()
	{
		/*
		en esta clase se permite subir cualquier tipo de archivo
		las restricciones deben darse en la clase hijo
		*/
		
		return true;

	}
	
	protected function getDestName()
	{
	
		if(!$this->identificador)
		{	//si no indica identificador, el nombre del archivo de destino es el de origen
			$this->destName=$this->archivo['name'];
		}
		else
		{
			//si se indica parsea el identificador a 8 numeros rellenando con ceros
			//y le pone la misma extension del arvhivo origen
			
			$this->identificador=(int) $this->identificador;
			$this->identificador=sprintf ('%08s', $this->identificador);
			
			$partesNombre=explode('.', $this->archivo['name']);
			$this->destName=$this->identificador . '.' . $partesNombre[count($partesNombre)-1];
			
		}
	}
	
	protected function getDestFolder()
	{
		$this->destFolder=$_SERVER['DOCUMENT_ROOT'];//por defecto se suben al root del server
		
	}
}
?>