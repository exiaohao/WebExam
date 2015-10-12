<h2 class="sub-header">创建考试</h2>
<form action="/manage/creat_new_exam" method="post">
	<div class="form-group">
		<label>考试名称</label>
		<input class="form-control" type="text" name="title" placeholder="考试名称">
	</div>
	
	<div class="form-group">
		<label>描述内容</label>
		<textarea class="form-control" name="descr"></textarea>
	</div>
	
	<div class="form-group">
	    <label>开始时间</label>
	    <input type="date" name="time-start" class="form-control" style="width:180px;">
	</div>

	<div class="form-group">
        <label>结束时间</label>
        <input type="date" name="time-expired" class="form-control" style="width:180px;">
    </div>
	
	<div class="form-group">
        <label>允许年级</label>
    </div>
	<?php
	for($s = date(Y); $s>(date(Y)-6); $s--)
	{
		echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;<label><input name=\"available_year[]\" type=\"checkbox\" value=\"{$s}\">&nbsp;{$s}</label></div>";
	}
	?>
	<div class="form-group">
		<button type="submit" class="btn btn-primary">创 建 考 试</button>
	</div>	
</form>
