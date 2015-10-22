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
	function checkTestTime()
	{
		//$j[0] = array("1445525100","1445529000");
		$j[0] = array("1445594400","1445603400");
		$j[1] = array("1445644800","1445677200");
		$j[2] = array("1445853600","1445862600");
		$j[3] = array("1445940000","1445949000");
		$j[4] = array("1446026400","1446035400");
		$j[5] = array("1446112800","1446119100");
		$now = time();
		foreach($j as $n)
		{
			if($now >= $n[0] && $now <= $n[1] )
			{
				return 1;
			}
			else
			{
				if($now < $n[0])
				return $n;
			}
		}
	}
	function select()
	{
		require 'views/exam_select.php';
	}
	function myscore()
	{
		die("");
	}
}
?>
