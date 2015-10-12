<?php
session_start();
class logout extends core
{
	function _default()
	{
		foreach($_SESSION as $key=>$value)
		{
			$_SESSION[$key] = null;
		}
		header("Location:/login");
	}
}

?>
