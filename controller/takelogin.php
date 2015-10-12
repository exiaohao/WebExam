<?php
header("Content-type: text/html; charset=utf-8");
session_start();
class takelogin extends core
{
	function __construct()
	{
		parent::__construct();
	}
	function _default()
	{
		print_r($_SESSION);
		print_r($_POST);
		if($_POST['checkcode'] == $_SESSION['utkn'])
		{
			
			$stuid = is_numeric($_POST['account'])?$_POST['account']:header("Location:/login/error/".urlencode("错误的登录请求"));
			//	
			$stuinfo = $this->stu_getdata($stuid);
			print_r($stuinfo);
			if($stuinfo[0] > 0)
			{
				$pass_ori = substr($stuinfo[9], 12);
				if($_POST['password'] == $pass_ori)
				{
					$_SESSION['user'] = $stuinfo;
					$_SESSION['valid'] = 1;
					$_SESSION['loginTime'] = time();
					$this->sys_log("INFO", "STU_LOGIN", $stuid);
					header("Location:/exam/select");
				}
				else
				{
					$this->sys_log("EINFO", "STU_LOGIN_FAILED", $stuid);
					header("Location:/login/error/".urlencode("用户名或密码错误 E4.1"));
				}
			}
			else
			{
				$this->sys_log("EINFO", "STU_LOGIN_NOACC", $stuid);
				header("Location:/login/error/".urlencode("用户名或密码错误 E4.0"));
			}
		}
		else
		{
			header("Location:/login/error/".urlencode("错误的登录请求"));
		}
	}
}

?>
