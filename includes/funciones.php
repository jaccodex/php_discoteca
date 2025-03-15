<?php

function delete_file($file)
{ 
   $delete = @unlink($file); 
   clearstatcache();
   if (@file_exists($file)) 
   { 
      $filesys = preg_replace("/","\\",$file); 
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