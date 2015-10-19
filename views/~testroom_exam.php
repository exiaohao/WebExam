<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>选择考试</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/exam_select.css" rel="stylesheet">
	<link href="/css/exam_structure.css" rel="stylesheet">
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
            	<h2><?=$ex_info['title']; ?></h2>
            	<p><?=$ex_info['descr']; ?></p>
			</div>
			<div class="row">
				<form class="examform" action="/submit/exam/<?=$examid;?>" method="post">
				<input type="hidden" name="time_start" value="<?=time(); ?>" >
				<?php
				$strc_kv = array("singleselect"=>"select", "multiselect"=>"multiselect", "yorn"=>"check", "blank"=>"blank","surveyselect"=>"surveyselect");
				$strc_inputtype = array("singleselect"=>"<label><input class=\"singleselect\" name=\"singleselect[%s][]\" value=\"%s\" type=\"radio\">&nbsp;%s</label>", "multiselect"=>"<label><input class=\"multiselect\" name=\"multiselect[%s][]\" value=\"%s\" type=\"checkbox\">&nbsp;%s</label>", "yorn"=>"<label><input class=\"check\" name=\"check[%s][]\" value=\"%s\" type=\"radio\">&nbsp;%s</label>", "survey_singleselect"=>"<label><input name=\"singleselect[%s][]\" value=\"%s\" type=\"radio\">&nbsp;%s</label>", "surveyselect"=>"<label><input name=\"surveyselect[%s][]\" value=\"%s\" type=\"radio\">&nbsp;%s</label>");
				$ex_strc = json_decode($ex_info['structure']);
				//Total Count
				$total = 0;
				foreach($ex_strc as $node=>$n_num)
				{
					if($n_num > 0)
					{
						$sql = "SELECT * FROM `exam_question` WHERE `examid` = {$examid} AND `type` LIKE '{$strc_kv[$node]}' order by rand() LIMIT {$n_num};";
						$exdata = $this->db->query($sql);
						$count = 1;
						while($exitem = mysqli_fetch_array($exdata))
						{
							echo "
			<div class=\"col-lg-12 question\">
				<h4>{$count}.{$exitem['title']}</h4>
				<ul class=\"list-group\">";
				$exam = json_decode($exitem['question']);
				foreach($exam->q as $qnode=>$nodestr)
				{
					echo "<li class=\"list-group-item\">".sprintf($strc_inputtype[$node], $exitem['id'], $qnode, urldecode($nodestr) )."</li>";
				}
				echo "</ul>
			</div>
							";
							$count++;
							$total++;
						}
					}
				}
				?>
				<button type="submit" class="btn btn-primary" name="signup" value="Sign up">提交答卷</button>
				</form>
			</div>
		</div>
		 <div class="col-xs-3" id="sidebar">
          <div class="list-group">
            <a class="list-group-item active">考试中</a>
            <a id="clock1" class="list-group-item">剩余时间</a>
            <a class="list-group-item">已完成
				<div class="progress">
					<div id="answer-progress" class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="49" style="width: 0%;"></div>
				</div>
			</a>
          </div>
        </div><!--/.sidebar-offcanvas-->
	</div>
	<hr>

      <footer>
        <p>&copy; ETNWS WebExam</p>
      </footer>

    </div><!--/.container-->
    


<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery.countdown.js"></script>
<script>
$(function(){
	//Exam Countdown
	var countdown = new Date()
	countdown.setSeconds(countdown.getSeconds() + <?=$ex_info['time']*60; ?>)
	$('#clock1').countdown(countdown, function(event) {
		$(this).html(event.strftime('距自动收卷 <span class="pull-right">%H:%M:%S</span>'));
	});
	//
	$(".examform input").click(function(){
		var finishedItem = 0;
		$(".singleselect:checked").each(function(i,e){
			//console.log(i + "." + $(this).attr("name"))
			finishedItem = finishedItem + 1;
		})
		var last_id = null;
		$(".multiselect:checked").each(function(i,e){
			if(last_id == $(this).attr("name"))
			{
				last_id = $(this).attr("name");
			}
			else
			{
				finishedItem = finishedItem + 1;
				last_id = $(this).attr("name");
			}
        })
		$(".check:checked").each(function(i,e){
            //console.log(i + "." + $(this).attr("name"))
            finishedItem = finishedItem + 1;
        })
	 	$(".surveyselect:checked").each(function(i,e){
            //console.log(i + "." + $(this).attr("name"))
            finishedItem = finishedItem + 1;
        })
		var totalQes = <?=$total; ?>;
		$("#answer-progress").css({"width":(finishedItem/totalQes*100)+"%"}).html(finishedItem+"/"+totalQes);
	})
})
</script>
