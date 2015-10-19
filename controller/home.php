<?php
class home extends core
{
	function __construct()
	{
		parent::__construct();
		$lib = $this->db->query('SELECT * FROM `lib`;');
	//	var_dump(mysqli_num_rows($lib));
	}
	function _default()
	{
		//var_dump("I/m Default page");
		require 'views/welcome.html';
	}
	function myact()
	{
		var_dump("I'm new action");
	}
}

?>
