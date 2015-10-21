<?php
define('DEFAULT_CONTROLLER', 'home');
define('_ARL_AUTHECARD', 1);

require 'mysql.php';
//Core Class
class core extends mysql
{
	function __construct()
	{
		parent::__construct();
	}
	/*
	获取IP地址
	*/
	function get_real_ip(){
		$ip = false;
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
			for ($i = 0; $i < count($ips); $i++)
			{
				if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i]))
				{
					$ip = $ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
	/*
	验证用户API读取权限
	*/
	function user_apilegal($utkn, $resid)
	{
		$return = array('errid'=>-1, 'err_msg'=>'not completed');
		//
		$qTknLine = $this->db->query("SELECT * FROM `api_resource_control` WHERE `tkn` LIKE '{$utkn}' LIMIT 0 ,1;");
		if($qTknLine->num_rows)
		{
			$tknInfo = $qTknLine->fetch_assoc();
			foreach(json_decode($tknInfo['permission']) as $pnode)
			{
				if($pnode == $resid)
				{
					$return = array('errid'=>200, 'err_msg'=>'token authenticated');
					return $return;
				}
			}
			$return = array('errid'=>403, 'err_msg'=>'token illegal');
			return $return;
		}
		else
		{
			$return = array('errid'=>404, 'err_msg'=>'token not exists!');
			return $return;
		}
	}
	/*
	验证访问频度和日请求量
	*/
	function user_queryfreqlegal($utkn, $resid)
	{
	
	}
	/*
	日志
	*/
	function sys_log($type, $act, $detail = NULL)
	{
		$log_time = time();
		$ip_addr = $this->get_real_ip(); 
		$sql = "INSERT INTO `syslog`(`time`, `act`, `detail`, `ip_addr`, `type`) VALUES ('{$log_time}', '{$act}', '{$detail}', '{$ip_addr}', '{$type}')";
		$this->db->query($sql);
	}
	/*
	读取学生信息
	*/
	function stu_getdata($stuid)
	{
		$content = file('http://210.33.28.180/stu/info.asp?xh='.$stuid.'&user=jwcjsbm');
		$text=implode("\n",$content);
		$text=preg_replace("/\<xml xmlns(.+?) xmlns:z=\"#RowsetSchema\">/is"," ", $text);
		$text=preg_replace("/\<s:Schema id=\"RowsetSchema\"\>(.+?)\<\/s:Schema\>/is"," ", $text);
		$text=preg_replace("/\<rs:data\>(.+?)\<\/rs:data\>/is","\\1", $text);
		$text=str_replace('<z:row','',$text);
		$text=str_replace('/>','',$text);
		$text=str_replace('</xml>','',$text);
		$elements=explode('"',$text);
		$i=0;
		for( $c = 1; $c < count($elements); $c++ )
		{
			if($i == 1)
			{
				$udata[$i++]=trim(mb_convert_encoding($elements[$c], 'UTF-8', 'gbk'));
			}
			else
			{
		    	$udata[$i++]=trim(iconv("GB2312","UTF-8//TRANSLIT",$elements[$c]));
			}
	    	$c++;
		}
		return $udata;
	}
}


//Page Error
class PageError
{
	function HttpError($ecode)
	{
		require dirname(__FILE__)."/../static/{$ecode}.php";
		die();
	}
}
//
if( !@IN_PAGE )
{
	PageError::HttpError(403);
}
//URL Parser
//Define $req_uri
//
//REQUEST_URI
$req_uri_clearq = explode('?', $_SERVER['REQUEST_URI']);
$req_uri_clearm = explode(':', $req_uri_clearq[0]);
$req_uri = array_slice(explode('/', $req_uri_clearm[0]), 1);
//
$_GET['_controller'] = @ $req_uri[0];
$_GET['_action'] = @ $req_uri[1];

if( empty($_GET['_controller']))
{
	$_GET['_controller'] = DEFAULT_CONTROLLER;
	require dirname(__FILE__).'/../controller/'.$_GET['_controller'].'.php';
	$page = new $_GET['_controller'];
	$_pHandle = $page->_default(@$req_uri_clearm[1]);
}
else
{
	$fp_file = dirname(__FILE__).'/../controller/'.$_GET['_controller'].'.php';
	$fp = @ fopen($fp_file, 'r');
	if($fp)
	{
		require dirname(__FILE__).'/../controller/'.$_GET['_controller'].'.php';
		if( empty($_GET['_action']) )
		{
			
			$page = new $_GET['_controller'];
    		$_pHandle = $page->_default(@$req_uri_clearm[1]);
		}
		else
		{
			$page = new $_GET['_controller'];
            $_pHandle = $page->$_GET['_action'](@$req_uri_clearm[1]);
		}
	}
	else
	{
		PageError::HttpError(404);
	}
}

?>
