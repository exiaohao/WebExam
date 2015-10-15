<style>
.input-group{
	margin:0 0 3px 0
}
</style>
<h2 class="sub-header">试题</h2>
<?php
$req_uri = explode('/', $_SERVER['REQUEST_URI']);
$qid = is_numeric($req_uri[3])?$req_uri[3]:die("Bad Request!");
$ex = $this->db->query("SELECT * FROM `exam` WHERE `id` = {$qid};");
if( $ex->num_rows > 0)
{
	$ex_info = mysqli_fetch_assoc($ex);
	$ex_strc = json_decode($ex_info['structure']);
	echo "<pre><strong>您需要提供</strong><br />单选题:{$ex_strc->singleselect}题<br />多选题:{$ex_strc->multiselect}题<br />判断题:{$ex_strc->yorn}题<br />填空题:{$ex_strc->blank}题</pre>";
}
else
{
	die("考试不存在");
}
?>
<form>
<div  class="form-group">
	<label>题干</label>
	<input type="text" class="form-control" placeholder="题干内容">
</div>

<div class="input-group">
	<span class="input-group-addon" id="basic-addon2">选项A</span>
  	<input type="text" name="sel-option[A]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
  	<span class="input-group-addon" id="basic-addon2">
		<input type="radio" name="correct[]" value="A">
	</span>
</div>
<div class="input-group">
    <span class="input-group-addon" id="basic-addon2">选项B</span>
    <input type="text" name="sel-option[A]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
    <span class="input-group-addon" id="basic-addon2">
        <input type="radio" name="correct[]" value="B">
    </span>
</div>
<div class="input-group">
    <span class="input-group-addon" id="basic-addon2">选项C</span>
    <input type="text" name="sel-option[A]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
    <span class="input-group-addon" id="basic-addon2">
        <input type="radio" name="correct[]" value="C">
    </span>
</div>
<div class="input-group">
    <span class="input-group-addon" id="basic-addon2">选项D</span>
    <input type="text" name="sel-option[A]" class="form-control" placeholder="选项内容" aria-describedby="basic-addon2">
    <span class="input-group-addon" id="basic-addon2">
        <input type="radio" name="correct[]" value="D">
    </span>
</div>



</form>
