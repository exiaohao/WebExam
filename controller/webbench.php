<?php
class webbench extends core
{
	function _default()
	{
		echo '<pre>WebBench</p>';
		for($i = 0; $i < 5; $i++)
		{
			$sql = "SELECT * FROM `exam_question` WHERE `examid` = 3 order by rand() LIMIT 50;";
			$list = $this->db->query($sql);
			while($item = mysqli_fetch_array($list))
			{
				print_r($item);
			}
		}
	}
}

?>
