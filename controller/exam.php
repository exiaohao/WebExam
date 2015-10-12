<?php
header("X-UA-compatible:IE=edge,chrome=1");
session_start();
if($_SESSION['valid'] != 1)
{
	header("Location:/login");
	die();
}
class exam extends core
{
	function select()
	{
		require 'views/exam_select.php';
	}
}
?>
