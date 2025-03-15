<?php
function confirm_query($strquery)
{
	global $connection;
	
	if ($query=mysql_query($strquery))
	{
		return $query;
	}
	else
	{
	?>
        <div id="error">
        <p>Error en la consulta :<?php echo $strquery;?></p>
		<p>Conexion :<?php echo $connection;?></p>
        <p><?php echo "Error: " . mysql_error($connection);?></p>
        <p><?php echo "Database: " . DATABASE;?></p>
        </div>
        <?php
		die();
    }
}

function mysql_prep($value)
{
	$magic_quotes_active=get_magic_quotes_gpc();
	$new_enough_php=function_exists("mysql_real_escape_string");//para PHP 4.3 o superior
	
	if ($new_enough_php)
	{//si es PHP 4.3 o superior
		if($magic_quotes_active)
		{	//si tiene activo magic_quotes, deshacerlo
			$value=stripslashes($value);
		}
		
		$value=mysql_real_escape_string($value);
	}
	else
	{
		//si es inferior a PHP 4.3
		if(!$magic_quotes_active)
		{
			$value=addslashes($value);
		}
	}
	
	return $value;
}

function fullUpper($str){
   // convert to entities
   $subject = htmlentities($str,ENT_QUOTES);
   $pattern = '/&([a-z])(uml|acute|circ';
   $pattern.= '|tilde|ring|elig|grave|slash|horn|cedil|th);/e';
   $replace = "'&'.strtoupper('\\1').'\\2'.';'";
   $result = preg_replace($pattern, $replace, $subject);
   // convert from entities back to characters
   $htmltable = get_html_translation_table(HTML_ENTITIES);
   foreach($htmltable as $key => $value) {
      $result = ereg_replace(addslashes($value),$key,$result);
   }
   return(strtoupper($result));
}