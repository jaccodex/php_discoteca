<?php
class mysql
{
	private $_host;
	private $_user;
	private $_pass;
	private $_database;
	
	private $_queryString;
	public  $resultSet;
	public  $resultArray;
	
	public  $value; //valor que se pasa por $_POST para formar parte de una query
	public  $numRows;
	
	public function __construct($db=null)
	{
		$this->_host=HOST; //estos tres son definidos por la aplicacion
		$this->_user=USERNAME;
		$this->_pass=PASSWORD;
		
		if(!$db)
		{
			$this->_database=DATABASE;//y este tambien
		}
		else
		{
			$this->_database=$db;		
		}
	
		try
		{
			if(!$this->_connection=@mysql_connect($this->_host,$this->_user, $this->_pass))
			{
				$excepStr='
				<p>Error en la conexion con la base de datos.</p>' . 
				'<ul>' . 
				'<li>Error:' . mysql_error(). '</li>' .
				'<li>Error no:'.mysql_errno() .'</li>';

				throw new exception($excepStr);
			}

		}
		catch (exception $e)
		{
			echo '<div id=\'error\'>';
			echo $e->getMessage();
			$info=$e->getTrace();
			echo '<li>Script: ' . $info[0]['file'] . ' en linea ' . $info[0]['line'] . '</li>';
			echo '</ul>';
			echo '</div>';
			die();
		}
		
		try
		{
			/*
			mi bd usa utf8
			*/
			$strUtf="SET NAMES 'utf8'";
			mysql_query($strUtf);
		
			if(!mysql_select_db($this->_database, $this->_connection))
			{
				$excepStr='
				<p>Error en la seleccion de la base de datos.</p>' . 
				'<ul>' . 
				'<li>Error:' . mysql_error(). '</li>' .
				'<li>Error no:'.mysql_errno() .'</li>';
				
				throw new exception($excepStr);
			}
			else
			{
				mysql_query('SET NAMES \'utf8\'');
			}

		}
		catch (exception $e)
		{
			echo '<div id=\'error\'>';
			echo $e->getMessage();
			$info=$e->getTrace();
			echo '<li>Script: ' . $info[0]['file'] . ' en linea ' . $info[0]['line'] . '</li>';
			echo '</ul>';
			echo '</div>';
			die();
		}
	

		
	}
	
	public function __destruct()
	{
		if($this->_connection)
		{
			mysql_close($this->_connection);
		}
	}
	
	public function setQueryString($strQuery)
	{
		$this->_queryString=$strQuery;
	}
	
	public function getQueryString()
	{
		return $this->_queryString;
	}
	
	public function getQueryResult()
	{	
		try
		{

			
			if(!$this->resultSet=mysql_query($this->_queryString, $this->_connection))
			{
				$excepStr='
				<p>Error al realizar la query.</p>' . 
				'<ul>' . 
				'<li>Query String:' . $this->_queryString . '</li>' .
				'<li>Mensaje del sistema:' . mysql_error(). '</li>' .
				'<li>Error no:'.mysql_errno() .'</li>';
						
				throw new exception($excepStr);
			}

		}
		catch (exception $e)
		{
			echo '<div id=\'error\'>';
			echo $e->getMessage();
			$info=$e->getTrace();
			echo '<li>Script: ' . $info[0]['file'] . ' en linea ' . $info[0]['line'] . '</li>';
			echo '</ul>';
			echo '</div>';
			die();
		}
					
		
		
		
	}

	public function getResultSet()
	{
		return $this->resultSet;
	}
	
	public function fetchResults()
	{
		$fetch=mysql_fetch_assoc($this->resultSet);
		return $fetch;
	}

	public function fetchResult()
	{
		$fetch=mysql_fetch_row($this->resultSet);
		return $fetch;
	}
	
	public function getNumRows()
	{
		$this->numRows=mysql_num_rows($this->resultSet);	
	}

	public function prepValue($value)
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
	
}
?>