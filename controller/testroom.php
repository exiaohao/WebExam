<?php
session_start();
class testroom extends core
{
	//显示考试内容
	function exam()
	{
		if(!$_SESSION['valid'])	header("Location:/login");
		$req_uri = explode('/', $_SERVER['REQUEST_URI']);
		$examid = is_numeric($req_uri[3])?$req_uri[3]:-1;
		if($examid > 0)
		{
			$exinfo = $this->db->query("SELECT * FROM `exam` WHERE `id` = {$examid};");
			if($exinfo->num_rows)
			{
				//Load Test Page
				$ex_info = mysqli_fetch_assoc($exinfo);
				require 'views/~testroom_exam.php';
			}
			else
			{
				PageError::HttpError(410);
			}	
		}
		else
		{
			PageError::HttpError(410);
		}
	}
}

?>
