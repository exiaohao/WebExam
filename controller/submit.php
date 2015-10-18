<?php
session_start();
class submit extends core{
	function exam()
	{
		$req_uri = explode('/', $_SERVER['REQUEST_URI']);

		echo '<!DOCTYPE html>
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
	<style>
	.testfinished{
		background-color: #eee;
    padding: 30px;
    border-radius: 10px;
    position: fixed;
    width: 1140px;
    top: 10px;
	margin:0 0 0 15px;
	}
	.ircorrect{
		margin-top: 230px;
	}
	</style>
</head>
<div class="container">
<div class="row ircorrect">
';
		//Check if Answered
		$answer_rec = $this->db->query("SELECT * FROM `answer_result` WHERE `testid` = {$req_uri[3]} AND `sid` LIKE '{$_SESSION['user'][0]}'");
		if($answer_rec->num_rows > 0)
		{
			die("<div class=\"alert alert-danger\" role=\"alert\">你已经答过题了，请勿重复答题!</div>");
		}
		//echo "<pre>";
		$score = 4;
		$time_end = time();
		//print_r($_POST);
		$exPartArr = array("singleselect", "multiselect", "check");
		$surveyArr = array("surveyselect");
		$notCorrectCount = 0;
		foreach($_POST as $exPart=>$exNode)
		{
			if( in_array($exPart, $exPartArr) )
			{
				foreach($exNode as $exNodeId => $exNodeAnswer)
				{
					$getQuesInfo = $this->db->query("SELECT * FROM `exam_question` WHERE `id` = {$exNodeId} AND `examid` = {$req_uri[3]}");
					$quesInfo = mysqli_fetch_assoc($getQuesInfo);
					$answer = json_decode($quesInfo['question']);
					//echo "<p>{$exPart}\t\t{$exNodeId} -> ".var_dump($exNodeAnswer)."\t Correct Answer:";
					//var_dump($answer->a);
					if((array)$answer->a === $exNodeAnswer)
					{
						//echo "<br /><code>ISCorrect</code></p>";
						$score += 2;
					}
					else
					{
						echo "<div class=\"col-lg-12\"><h4>".($notCorrectCount+1).".&nbsp;{$quesInfo['title']}</h4><p>正确答案是</p><div class=\"alert alert-success\" role=\"alert\">";
						if( !is_array($answer->a))
						{
							$correct_selection = $answer->a;
							echo "{$correct_selection}. ".urldecode($answer->q->$correct_selection);
						}
						else
						{
							foreach($answer->a as $correct_selection)
							{
								echo "{$correct_selection}. ".urldecode($answer->q->$correct_selection)."<br />";
							}
						}
						echo "</div><p>你的答案是</p><div class=\"alert alert-danger\" role=\"alert\">";
						//print_r($exNodeAnswer);
						if( !is_array($exNodeAnswer))
                        {
                            echo "{$exNodeAnswer}. ".urldecode($answer->q->$exNodeAnswer);
                        }
                        else
                        {
                            foreach($exNodeAnswer as $correct_selection)
                            {
                                echo "{$correct_selection}. ".urldecode($answer->q->$correct_selection)."<br />";
                            }
                        }

						echo "</div></div>";
						$notCorrectCount ++;
						//echo "<br /><code>Mistake</code></p>";
					}
				}
			}
			if(in_array($exPart, $surveyArr))
			{
				//echo "<p>SurveyInfo<br />";
				//print_r($exNode);
				//echo "</p>";
			}
		}
		echo "</div>
		<div class='row'>
			<div class=\"testfinished col-lg-12\"><h2>考试结束,你的分数是{$score}分</h2>";
		if($score >= 60)
		{
			echo "<h2 style='color: #3c763d;font-weight: bold;'>恭喜你，通过了考试</h2>";
		}
		else
		{
			echo "<h2 style='color: #900; font-weight: bold;'>很遗憾，你没有通过考试，下次再加油噢！</h2>";
		}
		echo "<br /><h4>下面是你做错的题,要记住正确答案哦!</h4>";


		//echo "<pre><p>Debug Info</p>";
		
		$answer_rawinfo = json_encode($_POST);
		//GetSurveyInfo
		$survArr = array();
		$survCount = 0;
		foreach($_POST['surveyselect'] as $survId=>$survVal)
		{
			$getSurv = $this->db->query("SELECT * FROM `exam_question` WHERE `id` = {$survId} AND `examid` = {$req_uri[3]} LIMIT 1;");
			$survInfo = mysqli_fetch_assoc($getSurv);
			$survArr[$survCount]['title'] = urlencode($survInfo['title']);
			//Decode
			$survSelect = json_decode($survInfo['question']);
			//var_dump($survSelect->q);
			foreach($survVal as $svKey=>$svVal)
			{
				$survArr[$survCount]['answer'] .= $survSelect->q->$svVal.",";
			}
		}	
		$survInfo = json_encode($survArr);
		//var_dump($survInfo);
		//var_dump($_SESSION);
		//getUserIPAddr
		$uip = $this->get_real_ip();
		$saveSql = "INSERT INTO `answer_result`(`testid`, `rawinfo`, `timestart`, `timeend`, `score`, `surveyinfo`, `sid`, `name`, `class`, `school`, `client`, `track`) VALUES ('{$req_uri[3]}', '{$answer_rawinfo}', '{$_POST['time_start']}', '{$time_end}', '{$score}', '{$survInfo}', '{$_SESSION['user'][0]}', '{$_SESSION['user'][1]}', '{$_SESSION['user'][5]}', '{$_SESSION['user'][3]}', '{$uip}', '');";
		$act = $this->db->query($saveSql);
		if($act)
		{
			$this->sys_log("INFO", "SAVE_EXAM", $_SESSION['user'][0]);
			echo "<h4>你的成绩已经成功保存!</h4>";
		}
		else
		{
			$this->sys_log("FATAL", "SAVE_EXAM", base64_encode($saveSql));
			echo "<h4>成绩保存失败,不过，俺还是会把你的记录留下来的!</h4>";
		}
		//echo "<p>{$saveSql}</p>";
		echo "</div></div>";
		
	}
}

?>
