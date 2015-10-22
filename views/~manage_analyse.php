<style>
#select-class{
	padding:5px
}
</style>
<h2 class="sub-header">数据分析</h2>
<div class="row">
	<div class="col-xs-12">
		<select id="select-class">
			<option value="null">请选择班级</option>
			<?php
			$examid = $req_uri[3] + 0;
			if($examid > 0)
			{
				$sql = "SELECT COUNT(*) AS `lines`, `school` FROM `answer_result` WHERE `testid` = {$examid} GROUP BY `school` ORDER BY `school`;";
				$data = $this->db->query($sql);
				while($school = mysqli_fetch_array($data))
				{
					echo "<option class='school-item' value='null' disabled><strong>{$school['school']}&nbsp;({$school['lines']})</strong></option>";
					$getClass = $this->db->query("SELECT COUNT(*) AS `clines`, `class` FROM `answer_result` WHERE `testid` = {$examid} AND `school` LIKE '{$school['school']}' GROUP BY `class` ORDER BY `class`;");
					while($class = mysqli_fetch_array($getClass))
					{
						echo "<option value='{$class['class']}'>{$class['class']}&nbsp;({$class['clines']})</option>";
					}
				}
			}
			?>	
		</select>
	</div>
	<hr />
	<div class="col-xs-12">
		<pre>
<strong>人数</strong>	<span id="reqexam">-人</span>	<strong>考试</strong>	<span id="lines">-人</span>	<strong>通过</strong>	<span id="passed">-人</span>	<strong>未考</strong>	<span id="non-examed">-人</span>	<strong>通过率</strong>	<span id="passrate">-%</span>
<strong>最高</strong>	<span id="maxscore">-</span>	<strong>最低</strong>	<span id="minscore">-</span>	<strong>平均</strong>	<span id="avgscore">-</span></pre>
	</div>
	<!--TestResultTable-->
	<div class="col-xs-12">
		<h3 class="sub-header">考试详情</h3>
		<div class="table-responsive">
			<table id="resultTable" class="table table-striped tablesorter">
		    	<thead>
        		<tr>
            	<th width=50>#</th>
               	<th width=150>学院</th>
               	<th width=150>班级</th>
				<th width=130>学号</th>
	            <th width=100>姓名</th>
    	        <th width=100>成绩</th>
				<th width=150>调查</th>
				<th width=100>用时</th>
				<th>交卷时间</th>
         		</tr>
				</thead>
	        <tbody id="showExamResult"></tbody>
			</table>
		</div>
	</div>
	<div class="col-xs-12">
		<h3 class="sub-header">未考名单</h3>
        <div class="table-responsive">
            <table id="resultTable" class="table table-striped tablesorter">
                <thead>
                <tr>
                <th width=50>#</th>
                <th width=150>学院</th>
                <th width=100>班级</th>
                <th width=130>学号</th>
                <th>姓名</th>
                </tr>
                </thead>
            <tbody id="nonExamResult"></tbody>
            </table>
        </div>
    </div>

</div>
	<!---->
</div>



<script src="/js/jquery-1.10.2.min.js"></script>
<script>
$(function(){
	$("#select-class").change(function(){
		$.post(
            "/manage/getAnalyseDat",
            { "cls":$("#select-class").val(), "qid":<?=$examid; ?>},
            function(data)
            {
				$("#reqexam").html(data.reqexam+"人");
				var nonexamed = (data.reqexam - data.lines);
				if(nonexamed > 0)
					$("#non-examed").html("<span class=\"label label-danger\">"+nonexamed+"人</span>");
				else
					$("#non-examed").html(nonexamed+"人");
				$("#lines").html(data.lines+"人");
				$("#passed").html(data.passed+"人");
				$("#maxscore").html(data.max+"分");
				$("#minscore").html(data.min+"分");
				$("#avgscore").html(data.avg+"分");
				$("#passrate").html(Math.round(data.passed/data.lines*100)+"%", 3);
				count = 1;
				$("#showExamResult").html("");
				$(data.testresult).each(function(i,e){
                    var time_min = Math.ceil((e.timeend-e.timestart)/60) - 1;
                    var time_sec = ((e.timeend-e.timestart)%60);
                    if(e.score < 60)    e.score = "<span class=\"label label-danger\">"+e.score+"</span>";
                    else if(e.score > 89) e.score = "<span class=\"label label-success\">"+e.score+"</span>";
                    else    e.score = "<span class=\"label label-info\">"+e.score+"</span>";
                    $("#showExamResult").append("<tr><td>"+count+"</td><td>"+e.school+"</td><td>"+e.class+"</td><td>"+e.sid+"</td><td>"+e.name+"</td><td>"+e.score+"</td><td>"+e.surveyinfo+"</td><td>"+time_min+"'"+time_sec+"\"</td><td>"+e.submittime+"</td></tr>");
                    count = count + 1;
                });
				count = 1;
				$("#nonExamResult").html("");
				$(data.nonexamed).each(function(i,e){
					$("#nonExamResult").append("<tr><td>"+count+"</td><td>"+e.school+"</td><td>"+e.class+"</td><td>"+e.sid+"</td><td>"+e.name+"</td></tr>");
				})
            },
            "json");	
	})
})
</script>
