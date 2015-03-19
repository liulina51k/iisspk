<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo (C("DB_CHARSET")); ?>" />
<meta name="description" content="<?php echo ($pknowinfo["context"]); ?>" />
<meta name="keywords" content="<?php echo ($pknowinfo["title"]); ?>" />
<title><?php echo ($pknowinfo["title"]); ?>-PK台-战略网</title>
<link href="<?php echo (SITE); ?>/Public/style/basic.v1.4.css" rel="stylesheet" type="text/css">
<link href="<?php echo (SITE); ?>/Public/style/pk2.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.comm.js"></script>
</head>
<body>
<input id="infoid" type="hidden" value="<?php echo ($pknowinfo["id"]); ?>" />
<input id="categoryid" type="hidden" value="-1" />
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="<?php echo (SITE); ?>/Public/images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="<?php echo (SITE); ?>/Public/images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <?php if($pkinfo["id"] > 223): ?><div class="top_banner"><img src="<?php echo (ATTPATH); ?>/<?php echo ($pkinfo["imgurl"]); ?>" alt="<?php echo ($pkinfo["title"]); ?>"></div><?php endif; ?>
</div>
<div id="main">
<div id="pk_titles">
<?php echo ($pknowinfo["title"]); ?><br /><a class="grayfont">所有评论仅代表网友意见，战略网保持中立</a> <a href="<?php echo (IISSSITE); ?>/pk/index/<?php echo ($pknowinfo["id"]); ?>" class="blue">查看PK</a>
</div><div class="clear"></div>
</div>
<div id="pk_main"><div id="borter">
<div id="main_rev">
<div id="rev_ybg"><div class="pktoppage" id='mutipagetop'></div><img class="fist" src="<?php echo (SITE); ?>/Public/images/pk/rev_y.jpg" /><a href="<?php echo (IISSSITE); ?>/pk/opp/<?php echo ($pknowinfo["id"]); ?>/1"><img src="<?php echo (SITE); ?>/Public/images/pk/rev_n.jpg" border="0" /></a><a href="<?php echo (IISSSITE); ?>/pk/plist/1"><img src="<?php echo (SITE); ?>/Public/images/pk/rev_pk.jpg" border="0" /></a><br>
</div>
<div id="gv_comments_pklist_-1_<?php echo ($pknowinfo["id"]); ?>"></div>
<div id="mutipage"></div>
</div>
<div class="clear"></div>
</div></div>
<div id="footer">
  <div>
    <div class="footer_x"></div>
    <span> <a href="http://www.chinaiiss.com/foot/about">关于我们</a>| <a href="http://www.chinaiiss.com/foot/contact">联系我们</a>| <a href="http://www.chinaiiss.com/foot/hire/">加入我们</a>| <a href="http://www.chinaiiss.com/foot/guestbook">留言中心</a>| <a href="http://www.chinaiiss.com/foot/privacy">隐私保护</a>| <a href="http://www.chinaiiss.com/foot/ads">广告服务</a>| <a href="http://www.chinaiiss.com/foot/ad">友情链接</a>| <a href="http://www.chinaiiss.com/foot/sitemap">网站地图</a> </span> <br>
    在线投稿邮箱：tougao@chinaiiss.com    值班电话：工作日 010-62360932(9:00-18:00)<br>
    Copyright ©2002-<script>document.write(new Date().getFullYear());</script>2013 www.chinaiiss.com All Rights Reserved. 京ICP证110164号 京ICP备11008173号<script src="http://s85.cnzz.com/stat.php?id=215831&web_id=215831&show=pic" language="JavaScript"></script></div>
</div>
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fcd0a687f19db4e63c481a5b03c59f4e3' type='text/javascript'%3E%3C/script%3E"));
</script>
</body>
</html>