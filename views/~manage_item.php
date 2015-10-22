<h2 class="sub-header">考试和问卷</h2>
<?php
$req_uri = explode('/', $_SERVER['REQUEST_URI']);
if($req_uri[3] == "msg" && !empty($req_uri[4]))
{
	echo "<div class=\"alert alert-success\" role=\"alert\">".urldecode($req_uri[4])."</div>";
}
?>
<div class="table-responsive">
	<table class="table table-striped">
    	<thead>
        	<tr>
            	<th>#</th>
               	<th>类型</th>
               	<th>名称</th>
	            <th>开始时间</th>
    	        <th>结束时间</th>
				<th>已答题</th>
				<th>设置</th>
         	</tr>
		</thead>
        <tbody>
		<?php
		$exam_list = $this->db->query("SELECT * FROM `exam` ORDER BY `exam`.`time_start` ASC");
		$count = 1;
		while($item = mysqli_fetch_array($exam_list))
		{
			$time_start = date("Y/m/d H:i:s", $item['time_start']);
			$time_expired = date("Y/m/d H:i:s", $item['time_expired']);
			$answered = $this->db->query("SELECT * FROM `answer_result` WHERE `testid` = {$item['id']};");
			echo "<tr><td>{$count}</td>
				<td>{$item['type']}</td>
				<td>{$item['title']}</td>
				<td>{$time_start}</td>
				<td>{$time_expired}</td>
				<td>{$answered->num_rows}&nbsp;<a href=\"/manage/result/{$item['id']}\">答题结果</a></td>
				<td><a href=\"\">修改</a>&nbsp;<a href=\"/manage/question/{$item['id']}\">添加试题</a>&nbsp;<a href=\"/manage/qlist/{$item['id']}\">试题列表</a>&nbsp;<a href=\"/manage/analyse/{$item['id']}\">考试分析</a></td></tr>";
			$count++;
		}
		?>
		</tbody>
	</table>
</div>
