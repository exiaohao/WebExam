<?php
$_SESSION['manager_utk'] = md5(time()-rand());
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" /><meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>管理用户登录</title>
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
		<form method="post" action="/manage/takelogin"  class="form-signin">
			<h3>管理员登录</h3>
			<!--[if lt IE 9]>
			<label for="account">用户</label>
			<![endif]-->
			<input name="account" type="text" id="account" class="form-control" placeholder="用户" value="" required autofocus>
			<!--[if lt IE 9]>
            <label for="inputPassword">密码</label>
            <![endif]-->
			<input name="password" type="password" id="inputPassword" class="form-control" placeholder="密码" required>
			<input name="checkcode" type="hidden" value="<?=$_SESSION['manager_utk']; ?>">
			<button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
			<p>ETNWS WebExam</p>
		</form>
	</div>	
</div>

