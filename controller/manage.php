<?php
session_start();

header("cache-control:private, max-age=0");
header("x-frame-options:SAMEORIGIN");

class manage extends core
{
	function login()
	{
		require 'views/manage_login.php';
	}
	function takelogin()
	{
		if($_SESSION['manager_utk'] == $_POST['checkcode'])
		{
			$username = $_POST['account'];
			$admin = $this->db->query("SELECT * FROM `admin` WHERE `user` LIKE '{$username}' LIMIT 1;");
			$admin_info = mysqli_fetch_assoc($admin);
			if(md5($_POST['password']) == $admin_info['pass'])
			{
				$_SESSION['manage_valid'] = 1;
				$_SESSION['manager'] = $admin_info;
				header("Location:/manage/dashboard");
			}
			else
			{
				header("Location:/manage/login/error/用户名或密码错误");
			}
		}
		else
		{
			header("Location:/manage/login/error/错误的请求");
		}
	}
	function dashboard()
	{
		require 'views/manage_dashboard.php';
	}
	function new_exam()
	{
		require 'views/manage_dashboard.php';
	}
	function creat_new_exam()
	{
		$testTime = ($_POST['time']+0)>0?($_POST['time']+0):die("考试时间需大于0分钟");
		$struct = json_encode($_POST['struct']);
		$sql = "INSERT INTO `exam`(`type`, `title`, `descr`, `time_start`, `time_expired`, `available_user`, `status`, `structure`, `time`) VALUES ('exam', '{$_POST['title']}', '".nl2br($_POST['descr'])."', '".strtotime($_POST['time-start'])."', '".strtotime($_POST['time-expired'])."', '".json_encode($_POST['available_year'])."', 1, '{$struct}', '{$testTime}');";
		$act = $this->db->query($sql);
		if($act)
		{
			header("Location:/manage/item/msg/创建新考试成功");
		}
		else
		{
			die("Creat Exam Failed!{$sql}");
		}
	}
	function item()
	{
		require 'views/manage_dashboard.php';
	}
	function question()
	{
		require 'views/manage_dashboard.php';
	}
	//添加问题
	function addQuestion()
	{
		$req_uri = explode('/', $_SERVER['REQUEST_URI']);
		$examid = $req_uri[3]+0;
		if($examid > 0)
		{
			$title = $_POST['question'];
			//
			foreach($_POST['sel-option'] as $sokey=>$soval)
			{
				$_POST['sel-option'][$sokey] = urlencode($soval);
			}
			//	
			$question['q'] = $_POST['sel-option'];
			if(!empty($_POST['correct']))
			{
				$question['a'] = $_POST['correct'];
			}
			else
			{
				die("No Correct Answer!");
			}
			$question = json_encode($question);
		}
		else	die("Bad Exam Info");
		//print_r($_POST);
		switch($_POST['qtype'])
		{
			case "ss":
			{
				$sql = "INSERT INTO `exam_question`(`examid`, `title`, `question`, `type`) VALUES ('{$examid}', '{$title}', '{$question}', 'select');";
				break;
			}
			case "ms":
			{
				$sql = "INSERT INTO `exam_question`(`examid`, `title`, `question`, `type`) VALUES ('{$examid}', '{$title}', '{$question}', 'multiselect');";
				break;
			}
			case "yn":
			{
				$sql = "INSERT INTO `exam_question`(`examid`, `title`, `question`, `type`) VALUES ('{$examid}', '{$title}', '{$question}', 'check');";
				break;
			}
			case "blank":
			{
				$sql = "INSERT INTO `exam_question`(`examid`, `title`, `question`, `type`) VALUES ('{$examid}', '{$title}', '{$question}', 'blank');";
				break;
			}
			default:
			{
				header("Content-type: text/html; charset=utf-8");   
				echo "<pre>";
				print_r($_POST);
				die("Bad Request!");
			}
		}
		//SQL Esxists
		if(!empty($sql))
		{	
			$act = $this->db->query($sql);
			if($act)
			{
				header("Location:/manage/question/{$examid}/success/添加试题成功");
			}
			else
			{
				header("Location:/manage/question/{$examid}/fail/添加试题成功");
			}
		}
	}
	/*
	Get qlist
	*/
	function qlist()
	{
		
	}
	function result()
	{
		$req_uri = explode('/', $_SERVER['REQUEST_URI']);
		require 'views/manage_dashboard.php';
	}
	function getAnsweredDat()
	{
		$school = $_POST['school'];
		$class = $_POST['cls'];
		$qid = $_POST['qid'];
		
		if($school == "null")
		{
			$sql = "SELECT * FROM `answer_result` WHERE `testid` = {$qid} ORDER by `timeend` DESC;";
		}
		elseif($school != "null" && $class == "null")
		{
			$sql = "SELECT * FROM `answer_result` WHERE `testid` = {$qid} AND `school` LIKE '{$school}' ORDER by `timeend` DESC;";
		}
		elseif($school != "null" && $class != "null")
        {
            $sql = "SELECT * FROM `answer_result` WHERE `testid` = {$qid} AND `class` LIKE '{$class}' ORDER by `timeend` DESC;";
        }

		$list = $this->db->query($sql);
		$return = array();
		$cpy_item = array("sid", "name", "class", "school", "score", "timestart", "timeend");
		$i = 0;
		while($item = mysqli_fetch_array($list))
		{
			foreach($cpy_item as $cpitem)
			{
				$return[$i][$cpitem] = $item[$cpitem];
			}
			$survinfo = json_decode($item['surveyinfo']);
			foreach($survinfo as $svyi)
			{
				if(!empty($svyi->answer))	$return[$i]['surveyinfo'] = urldecode($svyi->answer);
			}
			$return[$i]['submittime'] = date("Y/m/d H:i:s", $item['timeend']);
			$i++;
		}
		header('content-type:application/json;charset=utf8');
		echo json_encode($return);
	}
	
	function getAnseredClass()
	{
		$school = $_POST['school'];
		$qid = $_POST['qid'];
		$school_class = $this->db->query("SELECT COUNT(*) AS `lines`, `class` FROM `answer_result` WHERE `school` LIKE '{$school}' GROUP BY `class` ORDER BY `class`;");
		
		$count = 0;
		$class = array();
		while($item = mysqli_fetch_array($school_class))
		{
			$class[$count]['name'] = $item['class'];
			$class[$count]['lines'] = $item['lines'];
			$count ++;
		}
		echo json_encode($class);
	}
	function analyse()
	{
		$req_uri = explode('/', $_SERVER['REQUEST_URI']);
        require 'views/manage_dashboard.php';
	}
	function getAnalyseDat()
	{
		$examid = $_POST['qid'] + 0;
		$cls = $_POST['cls'];
		//--------
		$return['max'] = 0;
		$return['min'] = 101;
		$rerurn['avg'] = 0;
		$return['passed'] = 0;
		$return['lines'] = 0;
		$return['reqexam'] = 0;
		$return['testresult'] = array();
		$unset_array = array("track", "rawinfo", "client", 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
		//--------
		$namelist = $this->db->query("SELECT * FROM `student` WHERE `class` LIKE '{$cls}' ORDER BY `student`.`sid` ASC;");
		while($name = mysqli_fetch_array($namelist))
		{
			//echo json_encode($_POST);
			$return['reqexam'] ++;
			$sql = "SELECT * FROM `answer_result` WHERE `testid` = {$examid} AND `sid` LIKE '{$name['sid']}';";
			$dat = $this->db->query($sql);
			//已考
			if($dat->num_rows > 0)
			{
				while($line = mysqli_fetch_array($dat))
				{
					$line['exam_time'] = $line['timeend'] - $line['timestart'];
					$survinfo = json_decode($line['surveyinfo']);
					$line['surveyinfo'] = "";
					$line['submittime'] = date("Y/m/d H:i:s", $line['timeend']);
					foreach($survinfo as $svynode)
					{
						$line['surveyinfo'] .= urldecode($svynode->answer);
					}
					foreach($unset_array as $unarr)
					{
						unset($line[$unarr]);
					}
					$return['testresult'][] = $line;
					if($line['score'] > $return['max'])		$return['max'] = $line['score'];
					if($line['score'] < $return['min'])		$return['min'] = $line['score'];
					if($line['score'] > 59)					$return['passed']++;
					$return['avg'] += $line['score'];
					$return['lines'] ++;
				}
			}
			//未考
			else
			{
				$return['nonexamed'][] = $name;
			}
		}
		//$return['sql'] = "SELECT * FROM `student` WHERE `class` LIKE '{$cls}' ORDER BY `student`.`sid` ASC;";
		//
		$return['avg'] = number_format($return['avg']/$return['lines'], 2);
		header('content-type:application/json;charset=utf8');
		echo json_encode($return);
	}
}
?>
