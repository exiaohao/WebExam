<?php
class submit extends core{
	function exam()
	{
		echo '<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>选择考试</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/exam_select.css" rel="stylesheet">
	<link href="/css/exam_structure.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.min.js"></script>
      <script src="/js/respond.min.js"></script>
    <![endif]-->
</head>';
		echo "<pre>";
		print_r($_POST);
	}
}

?>
