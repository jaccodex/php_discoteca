<?php
class testGetArray
{
private $_getVars = array();

  public function __construct()
	  {
			foreach($_GET as $getIndex=>$getValue)
			{
				$this->_getVars[$getIndex]=$getValue;
			}
	  }

	  public function readGetVars()
	  {
		  return $this->_getVars;

	  }

}
?>