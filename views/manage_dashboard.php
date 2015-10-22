<?php
if($_SESSION['manage_valid'] != 1)
{
	header("Location:/manage/login");
	die();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>控制台</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/manage_dashboard.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
    	<div class="navbar-header">
        	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            	<span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
    	        <span class="icon-bar"></span>
        	    <span class="icon-bar"></span>
			</button>
          	<a class="navbar-brand" href="/manage/dashboard">控制台</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
            	<li><a href="#"><?=$_SESSION['manager']['name']; ?></a></li>
	            <li><a href="/logout">退出</a></li>
			</ul>
		</div>
	</div>
</nav>

<div class="container-fluid">
	<div class="row">
    	<div class="col-sm-3 col-md-2 sidebar">
        	<ul class="nav nav-sidebar">
            	<li><a class="anchorjs-link" href="/manage/dashboard">概况<span class="sr-only">(current)</span></a></li>
        	    <li><a href="/manage/item">正在进行</a></li>
          	</ul>
          	<ul class="nav nav-sidebar">
				<li><a class="anchorjs-link" href="/manage/new_exam">新建考试</a></li>
                <li><a href="/manage/new_survey">新建问卷</a></li>
          	</ul>
			<ul class="nav nav-sidebar">
                <li><a href="/manage/item">考试分析</a></li>
            </ul>
        </div>
    	<!--MAIN-->
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<?php
			require "~manage_{$_GET['_action']}.php";
			?>
        </div>
	</div>
</div>

<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
