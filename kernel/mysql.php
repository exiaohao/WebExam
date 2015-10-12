<?php
if( @IN_PAGE != 1 )
{
    require '../static/403.php';
	die();
}


define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'webexam');
define('MYSQL_PAWD', 'AYRwWED8M8bpKXr4');
define('MYSQL_TABL', 'webexam');

class mysql{
    var $db;
    function __construct()
    {
        $this->db = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PAWD, MYSQL_TABL) or die("SQL Error " . mysqli_error());
        $this->db->query("SET NAMES utf8;");
    }
}
?>
