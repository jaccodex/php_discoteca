<?php
class crearThumb
{
	private $_id_disco;
	private $_miPath;
	private $_fileName;
	private $_imagen;
	private $_dimensiones; // dimensiones en origen
	private $_mimeType;
	private $_ancho; // ancho de destino

	public function __construct($id_disco, $ancho=0)
	{
	
		if(!$id_disco)
		{
			$this->_errorDesc = 'No se ha indicado ningun archivo.';
			$this->mostrarError();
			exit;
		}
		else
		{
			$this->_id_disco=$id_disco;
		}
		
		$this->_miPath= COVER_PATH;//definido a nivel de aplicacion

		//dentro de COVER_PATH hay una subcarpeta 0000 por cada 500 id_disco		
		$this->_fileName=$this->_miPath 
		. sprintf('%04d',floor($this->_id_disco/500)) . '/' 
		. sprintf ('%08s.jpg', $this->_id_disco);
		
		if(!file_exists($this->_fileName))
		{
			$this->_fileName=$this->_miPath . 'noCover.png';
		}

		$this->_dimensiones=getimagesize($this->_fileName);	
		$this->_mimeType=$this->_dimensiones['mime'];

		switch ($this->_mimeType)
		{
			case 'image/jpeg':
			$this->_imagen=imagecreatefromjpeg($this->_fileName);
			break;
			
			case 'image/gif':
			$this->_imagen=imagecreatefromgif($this->_fileName);
			break;
			
			case 'image/png':
			$this->_imagen=imagecreatefrompng($this->_fileName);
			break;
		}

		/*
		escala
		*/

		$anchoOrig=$this->_dimensiones[0];
		$altoOrig =$this->_dimensiones[1];
		
		switch ($ancho)
		{
			case 0://sin cambios
			$anchoDest	= $anchoOrig;
			$altoDest	= $altoOrig;
			break;
			
			default://si se indica ancho de destino cambiar medidas 
			$anchoDest	= $ancho;
			$altoDest	= $altoOrig * $anchoDest / $anchoOrig;
			break;
		}

		$copy = imagecreatetruecolor($anchoDest, $altoDest);
		imagecopyresampled($copy, $this->_imagen,0,0,0,0,$anchoDest, $altoDest, $anchoOrig,$altoOrig) or die ("Image copy failed.");
		
		imagedestroy($this->_imagen);
		$this->_imagen = $copy;
	}
	
	public function getThumb()
	{
		header('Content-Type: $this->_mimeType');
		imagejpeg($this->_imagen,'',100);	
	}
	
	public function __destruct()
	{
		imagedestroy($this->_imagen);
	}
	
	public function mostrarError()
	{
		$texto_error='<div class=\'error\'>';
		$texto_error.= "Error: " . $this->_errorDesc. "<br />";
		$texto_error.= "</div>";
		
		echo $texto_error;
	}
}
?>