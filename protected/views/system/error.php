<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>错误 <?php echo $data['code']; ?></title>

<style type="text/css">
/*<![CDATA[*/
body {font-family:"Verdana";font-weight:normal;color:black;background-color:white;}
h1 { font-family:"Verdana";font-weight:normal;font-size:18pt;color:red }
h2 { font-family:"Verdana";font-weight:normal;font-size:14pt;color:maroon }
h3 {font-family:"Verdana";font-weight:bold;font-size:11pt}
p {font-family:"Verdana";font-weight:normal;color:black;font-size:9pt;margin-top: -5px}
.version {color: gray;font-size:8pt;border-top:1px solid #aaaaaa;}
/*]]>*/
</style>
</head>

<body>
<h1>错误 <?php echo $data['code']; ?></h1>
<h2><?php echo nl2br(CHtml::encode($data['message'])); ?></h2>
<p>
当网页服务器处理您的请求时, 发生以上错误.
</p>
<p>
如果您认为此错误是服务器造成的,请与管理员联系; <?php echo CHtml::link('点此返回首页',Yii::app()->homeUrl); ?>
</p>
<p>
谢谢.
</p>
<div class="version">
<?php echo date('Y-m-d H:i:s',$data['time']); ?>
</div>
</body>
</html>