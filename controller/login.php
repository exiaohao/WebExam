<?php
header("X-UA-compatible:IE=edge,chrome=1");
session_start();
$_SESSION['utkn'] = md5( time() + rand() );
class login extends core
{
	function __construct()
	{
		parent::__construct();
	}
	function _default()
	{
	}
	function error()
	{
	}
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" /><meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户登录</title>
<link href="/css/bootstrap.min.css?v=3.3.5" rel="stylesheet">
<link href="/css/login.css" rel="stylesheet">
<script src="/js/jquery-1.10.2.min.js"></script>
<!--[if lt IE 9]>
<script src="/js/html5shiv.min.js?v=3.7.2"></script>
<script src="/js/respond.min.js?v=1.4.2"></script>
<![endif]-->
</head>
<body>
<div class="wrapper">
	<div class="login-area">
		<form method="post" action="/takelogin"  class="form-signin">
			<?php
			if( !empty($req_uri[2]))
			{
				echo "<div class=\"alert alert-danger\" role=\"alert\">".urldecode($req_uri[2])."</div>";
			}
			?>
			<h3>嘉兴学院学生工作部(处) 网上考试系统</h3>
			<!--[if lt IE 9]>
			<label for="account">学号</label>
			<![endif]-->
			<input name="account" type="text" id="account" class="form-control" placeholder="学号" value="" required autofocus>
			<!--[if lt IE 9]>
            <label for="inputPassword">密码(身份证最后六位)</label>
            <![endif]-->
			<input name="password" type="password" id="inputPassword" class="form-control" placeholder="密码(身份证最后六位)" required>
			<input name="checkcode" type="hidden" value="<?=$_SESSION['utkn']; ?>">
			<button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
			<p>ETNWS WebExam</p>
		</form>
	</div>	
</div>
