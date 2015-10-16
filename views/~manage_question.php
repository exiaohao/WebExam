<style>
section{
	margin: 10px 0
}
.input-group{
	margin:0 0 3px 0
}
</style>
<h2 class="sub-header">试题</h2>
<?php
$req_uri = explode('/', $_SERVER['REQUEST_URI']);
$qid = is_numeric($req_uri[3])?$req_uri[3]:die("Bad Request!");
$ex = $this->db->query("SELECT * FROM `exam` WHERE `id` = {$qid};");

if($req_uri['4'] == "success")
{
	echo "<div class=\"alert alert-success\" role=\"alert\"><strong>操作信息</strong> ".urldecode($req_uri[5])."</div>";
}
if( $ex->num_rows > 0)
{
	$ex_info = mysqli_fetch_assoc($ex);
	$ex_strc = json_decode($ex_info['structure']);
	//获取已经保存的题
	$single_select = $this->db->query("SELECT * FROM `exam_question` WHERE `examid` = {$qid} AND `type` LIKE 'select';");
	$multi_select = $this->db->query("SELECT * FROM `exam_question` WHERE `examid` = {$qid} AND `type` = 'multiselect';");
	$question_check = $this->db->query("SELECT * FROM `exam_question` WHERE `examid` = {$qid} AND `type` = 'check';");
	$question_blank = $this->db->query("SELECT * FROM `exam_question` WHERE `examid` = {$qid} AND `type` = 'blank';");
	//
	echo "<pre><strong>您需要提供\t\t题库已有</strong><br />单选题:{$ex_strc->singleselect}题\t\t{$single_select->num_rows}题<br />多选题:{$ex_strc->multiselect}题\t\t{$multi_select->num_rows}题<br />判断题:{$ex_strc->yorn}题\t\t{$question_check->num_rows}题<br />填空题:{$ex_strc->blank}题\t\t{$question_blank->num_rows}题</pre>";
}
else
{
	die("考试不存在");
}
?>

<h2 class="sub-header">选择新增类型</h2>
<div class="btn-group" role="group">
	<button id="ss" type="button" class="btn btn-default selectquestion">单选题</button>
	<button id="ms" type="button" class="btn btn-default selectquestion">多选题</button>
	<button id="yn" type="button" class="btn btn-default selectquestion">判断题</button>
	<button id="blank" type="button" class="btn btn-default selectquestion">填空题</button>
</div>



<!--SingleSelect-->
<section id="newQuestion-ss" class="question-section">
	<form action="/manage/addQuestion/<?=$qid; ?>" method="post">
		<input type="hidden" name="qtype" value="ss">
		<div  class="form-group">
			<label>题干</label><input type="text" name="question" class="form-control" placeholder="题干内容">
		</div>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon2">选项A</span>
		  	<input type="text" name="sel-option[A]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
		  	<span class="input-group-addon" id="basic-addon2"><input type="radio" name="correct" value="A"></span>
		</div>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon2">选项B</span>
		    <input type="text" name="sel-option[B]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
		    <span class="input-group-addon" id="basic-addon2"><input type="radio" name="correct" value="B"></span>
		</div>
		<div class="input-group">
		    <span class="input-group-addon" id="basic-addon2">选项C</span>
		    <input type="text" name="sel-option[C]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
		    <span class="input-group-addon" id="basic-addon2"><input type="radio" name="correct" value="C"></span></div>
		<div class="input-group">
		    <span class="input-group-addon" id="basic-addon2">选项D</span>
		    <input type="text" name="sel-option[D]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
		    <span class="input-group-addon" id="basic-addon2"><input type="radio" name="correct" value="D"></span>
		</div>
		<p><button type="submit" class="btn btn-info">保存问题</button></p>
	</form>
</section>
<!--MultiSelect-->
<section id="newQuestion-ms" class="question-section">
	<form action="/manage/addQuestion/<?=$qid; ?>" method="post">
		<input type="hidden" name="qtype" value="ms">
		<div class="form-group">
		    <label>题干</label><input type="text" name="question" class="form-control" placeholder="题干内容">
		</div>
		<div class="input-group">
		    <span class="input-group-addon" id="basic-addon2">选项A</span>
		    <input type="text" name="sel-option[A]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
		    <span class="input-group-addon" id="basic-addon2"><input type="checkbox" name="correct[]" value="A"></span>
		</div>
		<div class="input-group">
		    <span class="input-group-addon" id="basic-addon2">选项B</span>
	    	<input type="text" name="sel-option[B]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
		    <span class="input-group-addon" id="basic-addon2"><input type="checkbox" name="correct[]" value="B"></span>
		</div>
		<div class="input-group">
		    <span class="input-group-addon" id="basic-addon2">选项C</span>
		    <input type="text" name="sel-option[C]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
		    <span class="input-group-addon" id="basic-addon2"><input type="checkbox" name="correct[]" value="C"></span>
		</div>
		<div class="input-group">
		    <span class="input-group-addon" id="basic-addon2">选项D</span>
		    <input type="text" name="sel-option[D]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
		    <span class="input-group-addon" id="basic-addon2"><input type="checkbox" name="correct[]" value="D"></span>
		</div>
		<p><button type="submit" class="btn btn-info">保存问题</button></p>
	</form>
</section>

<!--YesOrNo-->
<section id="newQuestion-yn" class="question-section">
	<form action="/manage/addQuestion/<?=$qid; ?>" method="post">
		<input type="hidden" name="qtype" value="yn">
		<div  class="form-group"><label>题干</label><input type="text" name="question" class="form-control" placeholder="题干内容"></div>
		<div class="input-group">
		    <span class="input-group-addon" id="basic-addon2">对</span>
		    <input type="text" name="sel-option[A]" class="form-control" placeholder="对" value="对" aria-describedby="basic-addon2">
		    <span class="input-group-addon" id="basic-addon2"><input type="radio" name="correct[]" value="A"></span>
		</div>
		<div class="input-group">
		    <span class="input-group-addon" id="basic-addon2">错</span>
    		<input type="text" name="sel-option[B]" class="form-control" placeholder="错" value="错" aria-describedby="basic-addon2">
		    <span class="input-group-addon" id="basic-addon2"><input type="radio" name="correct[]" value="B"></span>
		</div>
		<p><button type="submit" class="btn btn-info">保存问题</button></p>
	</form>
</section>
<!--Blank-->
<section id="newQuestion-blank" class="question-section">
	<form action="/manage/addQuestion/<?=$qid; ?>" method="post">
		<input type="hidden" name="qtype" value="blank">
		<div  class="form-group"><label>题目</label>
			<input type="text" name="question" class="form-control" placeholder="题目">
			<span>填空处用<code>@@@</code>表示</span>
		</div>
		<div  class="form-group">
		    <label>正确答案</label>
		    <input type="text" name="correct" class="form-control" placeholder="正确答案">
		</div>
		<p><button type="submit" class="btn btn-info">保存问题</button></p>
	</form>
</section>



<script src="/js/jquery-1.10.2.min.js"></script>
<script>
$(function(){
	$(".question-section").hide();
	$(".selectquestion").click(function(){
		console.log($(this).attr("id"))
		$(".question-section").hide();
		$("#newQuestion-"+$(this).attr("id")).show();	
	})
})
</script>
