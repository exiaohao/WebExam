<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>选择考试</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/exam_select.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.min.js"></script>
      <script src="/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<nav class="navbar navbar-fixed-top navbar-inverse">
    	<div class="container">
        	<div class="navbar-header">
          		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
    	        <span class="icon-bar"></span>
        	    <span class="icon-bar"></span>
            	<span class="icon-bar"></span>
	          </button>
    	      <a class="navbar-brand" href="#">在线调查/考试系统</a>
        	</div>
        	<div id="navbar" class="collapse navbar-collapse">
          		<ul class="nav navbar-nav">
		        	<li class="active"><a href="/exam/select">欢迎</a></li>
        			<li><a href="/exam/myscore">我的成绩</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="javascript:;"><?=$_SESSION['user'][1]; ?></a></li>
            		<li><a href="/logout">退出</a></li>
          		</ul>
			</div>
      </div>
	</nav>

<div class="container">
	<div class="row row-offcanvas row-offcanvas-right">
		<div class="col-xs-12 col-sm-9">
        	<p class="pull-right visible-xs">
            	<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>
          <div class="jumbotron">
            <h1>欢迎,<?=$_SESSION['user'][1]; ?></h1>
            <p>您可以选择右侧的考试/调查列表直接进入</p>
          </div>
          <div class="row">
			<?php
			$exam = $this->db->query("SELECT * FROM `exam`");
			while($ex_item = mysqli_fetch_array($exam))
			{
				echo '<div class="col-xs-6 col-lg-4">
              <h2>'.$ex_item['title'].'</h2>
              <p>'.$ex_item['descr'].'</p>
			  <p>从'.date("Y/m/d H:i", $ex_item['time_start']).' 到'.date("Y/m/d H:i", $ex_item['time_expired']).' </p>';
				if(time() > $ex_item['time_start'] && time() < $ex_item['time_expired'])
				{
              		echo '<p><a class="btn btn-success" href="/testroom/'.$ex_item['type'].'/'.$ex_item['id'].'" role="button">进入'.($ex_item['type']=="exam"?" 考试":"调查").' »</a></p>';
				}
				else
				{
					if(time() < $ex_item['time_start'])
					{
						echo '<p><a class="btn btn-default" href="javascript:;" role="button">尚未开始</a></p>';
					}
					if(time() > $ex_item['time_expired'])
					{
						echo '<p><a class="btn btn-default" href="javascript:;" role="button">已经结束</a></p>';
					}
				}
            	echo'</div>';
			}
			?>
          </div><!--/row-->
        </div><!--/.col-xs-12.col-sm-9-->
		<!--
        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
          <div class="list-group">
            <a href="#" class="list-group-item active">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
          </div>
        </div>
      </div>
		-->
		</div>
      <hr>

      <footer>
        <p>&copy; ETNWS WebExam</p>
      </footer>

    </div><!--/.container-->
    


<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

