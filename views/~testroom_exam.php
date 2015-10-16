
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
					<li><a href="/exam/select">欢迎</a></li>
		        	<li class="active"><a href="javascript:;">考试</a></li>
        			<li><a href="/exam/myscore">我的成绩</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="javascript:;"><? ?></a></li>
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
            	<h2><?=$ex_info['title']; ?></h2>
            	<p><?=$ex_info['descr']; ?></p>
				<pre>
				<?=print_r($ex_info);?>
				</pre>
			</div>
			<div class="row">
				<div class="col-lg-12">
					Col-lg-12	
				</div>
			</div>
		</div>
	</div>
	<hr>

      <footer>
        <p>&copy; Company 2014</p>
      </footer>

    </div><!--/.container-->
    


<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
