<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>网页信息提示页面</title>
<link href="<?php echo (SITE); ?>/Public/style/basic.v1.4.css" rel="stylesheet" type="text/css">
<?php if($second != ''): ?><script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.comm.js"></script><?php endif; ?>
<body>
<div id="main">
	<div class="bug_block">
		<div class="bug_outside">
			<div class="bug_inside">
			<img src="<?php echo (SITE); ?>/Public/images/blog/blog_bug.jpg" width="67" height="67" />
			<p><span><?php echo ($message); ?> </span><br /><?php if(strstr($message,$blogurl) > 0): ?>5秒钟页面将自动跳转至博客<?php else: ?>5秒钟页面将自动跳转至首页<?php endif; ?></p>
		</div>
		<div class="bug_insides">
			<a class="blue" href="javascript:history.go(-1);">返回上一页</a><a class="blue" href="<?php echo (SITE); ?>">返回首页</a><a class="grayfont" href="<?php echo (SITE); ?>">战略网</a>
		</div>
		</div></div>
	</div>
</div>
</body>
</html>