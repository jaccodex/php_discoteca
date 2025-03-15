<?php

function coverFile($id_disco)
{
	$miPath= '/webdata/myapps/appsdata/DisCovers';
	
	//dentro de COVER_PATH hay una subcarpeta 0000 por cada 500 id_disco		
	$fileName=$miPath . sprintf('%04d',floor($id_disco/500)) . '/' . sprintf ('%08s.jpg', $id_disco);
        //$fileName=COVER_PATH . sprintf('%04d',floor($id_disco/500)) . '/' . sprintf ('%08s.jpg', $id_disco);
	
        if(!file_exists($fileName))
	{
		//$fileName=$miPath . '/noCover.png';
            $fileName=COVER_PATH . '/noCover.png';
	}
	
	return $fileName;
}
?>
