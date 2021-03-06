<h2 class="sub-header">答题结果</h2>
<div class="table-responsive">
	<table id="resultTable" class="table table-striped tablesorter">
    	<thead>
        	<tr>
            	<th width=50>#</th>
               	<th width=150>
				<select id="select-school" name="select-school">
				<?php
				$getSchool = $this->db->query("SELECT COUNT(*) AS `lines`, `school` FROM `answer_result` WHERE `testid` = {$req_uri[3]} GROUP BY `school` ORDER BY `school`;");
				$school_total = 0;
				while($school_item = mysqli_fetch_array($getSchool))
				{
					$school_total += 1;
					echo "<option value=\"{$school_item['school']}\">{$school_item['school']} ({$school_item['lines']})</option>";
				}
				echo "<option value=\"null\" selected=\"selected\">所有学院 ({$school_total})</option>";
				?>
				</select>
				</th>
               	<th width=100>
				<select id="select-class" name="select-class"><option value="null">班级</option></select>
				</th>
				<th width=130>学号</th>
	            <th width=100>姓名</th>
    	        <th>成绩</th>
				<th>调查</th>
				<th>用时</th>
				<th>交卷时间</th>
         	</tr>
		</thead>
        <tbody id="showExamResult">
		</tbody>
	</table>
</div>

<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/jquery.tablesorter.min.js"></script>
<script>
$(function(){
	$.post("/manage/getAnsweredDat",
		{ "school":"null", "cls":"null", "qid":<?=$req_uri[3]; ?>},
        function(data)
		{
			var count = 1;
                $(data).each(function(i,e){
                    var time_min = Math.ceil((e.timeend-e.timestart)/60) -1;
                    var time_sec = ((e.timeend-e.timestart)%60);
					if(e.score < 60)	e.score = "<span class=\"label label-danger\">"+e.score+"</span>";
					else if(e.score > 89) e.score = "<span class=\"label label-success\">"+e.score+"</span>";
					else	e.score = "<span class=\"label label-info\">"+e.score+"</span>";
                    $("#showExamResult").append("<tr><td>"+count+"</td><td>"+e.school+"</td><td>"+e.class+"</td><td>"+e.sid+"</td><td>"+e.name+"</td><td>"+e.score+"</td><td>"+e.surveyinfo+"</td><td>"+time_min+"'"+time_sec+"\"</td><td>"+e.submittime+"</td></tr>");
                    count = count + 1;
                })

        },
    "json");
	$("#select-school").change(function(){
		$("#showExamResult").html("<tr><div class=\"alert alert-warning\" role=\"alert\">正在加载数据</div></tr>")
		console.log($(this).val())
		$.post(
			"/manage/getAnsweredDat",
			{ "school":$(this).val(), "cls":$("#select-class").val(), "qid":<?=$req_uri[3]; ?>},
			function(data)
			{
				var count = 1;
				$(data).each(function(i,e){
					var time_min = Math.ceil((e.timeend-e.timestart)/60) - 1;
					var time_sec = ((e.timeend-e.timestart)%60);
					if(e.score < 60)    e.score = "<span class=\"label label-danger\">"+e.score+"</span>";
					else if(e.score > 89) e.score = "<span class=\"label label-success\">"+e.score+"</span>";
                    else    e.score = "<span class=\"label label-info\">"+e.score+"</span>";
					$("#showExamResult").append("<tr><td>"+count+"</td><td>"+e.school+"</td><td>"+e.class+"</td><td>"+e.sid+"</td><td>"+e.name+"</td><td>"+e.score+"</td><td>"+e.surveyinfo+"</td><td>"+time_min+"'"+time_sec+"\"</td><td>"+e.submittime+"</td></tr>");
					count = count + 1;
				})
        	},
			"json");
		$.post(
			"/manage/getAnseredClass",
			{ "school":$(this).val(), "cls":$("#select-class").val(), "qid":<?=$req_uri[3]; ?>},
			function(data)
            {
				$("#select-class").html("<option value='null'>所有班级</option>");
				$(data).each(function(i,e){
					$("#select-class").append("<option value='"+e.name+"'>"+e.name+"&nbsp;("+e.lines+")</option>");
				})
			},
			"json");
	})
	$("#select-class").change(function(){
        $("#showExamResult").html("<tr><div class=\"alert alert-warning\" role=\"alert\">正在加载数据</div></tr>");
        $.post(
            "/manage/getAnsweredDat",
            { "school":$(this).val(), "cls":$("#select-class").val(), "qid":<?=$req_uri[3]; ?>},
            function(data)
            {
                var count = 1;
                $(data).each(function(i,e){
                    var time_min = Math.ceil((e.timeend-e.timestart)/60) - 1;
                    var time_sec = ((e.timeend-e.timestart)%60);
                    if(e.score < 60)    e.score = "<span class=\"label label-danger\">"+e.score+"</span>";
                    else if(e.score > 89) e.score = "<span class=\"label label-success\">"+e.score+"</span>";
                    else    e.score = "<span class=\"label label-info\">"+e.score+"</span>";
                    $("#showExamResult").append("<tr><td>"+count+"</td><td>"+e.school+"</td><td>"+e.class+"</td><td>"+e.sid+"</td><td>"+e.name+"</td><td>"+e.score+"</td><td>"+e.surveyinfo+"</td><td>"+time_min+"'"+time_sec+"\"</td><td>"+e.submittime+"</td></tr>");
                    count = count + 1;
                })
            },
            "json");
    })
})
$(document).ready(function() {
	$("#resultTable").tablesorter();
})
</script>
