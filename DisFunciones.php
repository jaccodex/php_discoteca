<?php
Function cover_file($id_disco)
{
	//devuelve el id_disco como un string parseado a '00000000'
	// y con extension .jpg

	$id_disco=(int) $id_disco;
	$str_id_disco=sprintf ('%08s.jpg', $id_disco);

	return $str_id_disco;
}


Function delete_file($file)
{ 
   $delete = @unlink($file); 
   clearstatcache();
   if (@file_exists($file)) 
   { 
      $filesys = eregi_replace("/","\\",$file); 
      $delete = @system("del $filesys");
      clearstatcache();
      if (@file_exists($file)) 
	  { 
         $delete = @chmod ($file, 0775); 
         $delete = @unlink($file); 
         $delete = @system("del $filesys");
      }
   }
   clearstatcache();
   if (@file_exists($file))
   {
      return false;
   }
   else
	  {
            return true;
      }
}  // end function 

?>