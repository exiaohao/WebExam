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
}
?>
