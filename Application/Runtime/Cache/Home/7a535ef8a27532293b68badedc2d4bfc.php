<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="">
<meta name="keywords" content="">
<title>辩论pk台_战略网</title>
<link href="<?php echo ($site); ?>/Public/style/basic.css" rel="stylesheet" type="text/css">
<link href="<?php echo ($site); ?>/Public/style/pk.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo ($site); ?>/Public/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo ($site); ?>/Public/js/function.comm.js"></script>
<script type="text/javascript" src="<?php echo ($site); ?>/Public/js/function.cookie.js"></script>
</head>
<body>
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="<?php echo ($site); ?>/Public/images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="<?php echo ($site); ?>/Public/images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <?php if($pkinfo["id"] > 223): ?><div class="top_banner"><img src="<?php echo ($attpath); ?>/<?php echo ($pkinfo["imgurl"]); ?>" alt="<?php echo ($pkinfo["title"]); ?>"></div><?php endif; ?>
</div>
<div id="main">
  <div class="block">
    <h1><span class="fright"><img src="<?php echo ($site); ?>/Public/images/pk_h1bg_r.jpg"></span><a class="gdpk"></a></h1>
  </div>
  <div class="main_left">
 <?php if(is_array($list)): foreach($list as $k=>$vo): if($k % 2 == 0): ?><div class="pk_new">
      <h1>话题:<a href="<?php echo ($vo["pkurl"]); ?>" title="<?php echo ($vo["title"]); ?>" target="_blank"><?php echo ($vo["title"]); ?></a></h1>
    <div class="pk_more">
      <div class="pkm_view">
        <div class="pkm_yes">
          <p title="<?php echo ($vo["agreetitle"]); ?>"><?php echo ($vo["agreeguide"]); ?></p>
         </div>
        <div class="pkm_no">
          <p title="<?php echo ($vo["opposetitle"]); ?>"><?php echo ($vo["opposeguide"]); ?></p>
          </div>
        <div class="clear"></div>
        <div class="pk_jdt">
            <div class="pk_jdt_num"><b id="agree<?php echo ($vo["id"]); ?>"><?php echo ($vo["agreevote"]); ?></b> / <b id="oppose<?php echo ($vo["id"]); ?>"><?php echo ($vo["opposevote"]); ?></b></div>
            <div class="pk_jdt_a"><a class="zczhengfang" onclick="pkvote(<?php echo ($vo["id"]); ?>, 'agree');" style="cursor:pointer;"></a><a class="zcfanfang" onclick="pkvote(<?php echo ($vo["id"]); ?>, 'oppose');" style="cursor:pointer;"></a></div>
            <div class="pk_jdt_nr"><span class="zhengfang" style="width:<?php echo ($vo["point_good"]); ?>%"></span><span class="fanfang" style="width:<?php echo ($vo["point_bad"]); ?>%"></span></div>
        </div>
      </div>
    </div>
    </div><?php endif; endforeach; endif; ?> 
  </div>
  <div class="main_right">
  <?php if(is_array($list)): foreach($list as $k=>$vo): if($k % 2 == 1): ?><div class="pk_new">
      <h1>话题:<a href="<?php echo ($vo["pkurl"]); ?>" title="<?php echo ($vo["title"]); ?>" target="_blank"><?php echo ($vo["title"]); ?></a></h1>
    <div class="pk_more">
      <div class="pkm_view">
        <div class="pkm_yes">
          <p title="<?php echo ($vo["agreetitle"]); ?>"><?php echo ($vo["agreeguide"]); ?></p>
         </div>
        <div class="pkm_no">
          <p title="<?php echo ($vo["opposetitle"]); ?>"><?php echo ($vo["opposeguide"]); ?></p>
          </div>
        <div class="clear"></div>
        <div class="pk_jdt">
            <div class="pk_jdt_num"><b id="agree<?php echo ($vo["id"]); ?>"><?php echo ($vo["agreevote"]); ?></b> / <b id="oppose<?php echo ($vo["id"]); ?>"><?php echo ($vo["opposevote"]); ?></b></div>
            <div class="pk_jdt_a"><a class="zczhengfang" onclick="pkvote(<?php echo ($vo["id"]); ?>, 'agree');" style="cursor:pointer;"></a><a class="zcfanfang" onclick="pkvote(<?php echo ($vo["id"]); ?>, 'oppose');" style="cursor:pointer;"></a></div>
            <div class="pk_jdt_nr"><span class="zhengfang" style="width:<?php echo ($vo["point_good"]); ?>%"></span><span class="fanfang" style="width:<?php echo ($vo["point_bad"]); ?>%"></span></div>
        </div>
      </div>
    </div>
    </div><?php endif; endforeach; endif; ?>
  </div>
  <div class="clear"></div>
	<div class="xy">
            <?php echo ($show); ?>
       </div>
</div>
<div class="Blank"></div>
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
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fcd0a687f19db4e63c481a5b03c59f4e3' type='text/javascript'%3E%3C/script%3E"));


$('.return_top').live('click',function(){
	$("html, body").animate({ scrollTop: 0 }, 'fast');
});	

</script><script src="js/h.js" type="text/javascript"></script><script type="text/javascript">BAIDU_CLB_SLOT_ID = "253313";</script> 
<script type="text/javascript" src="js/o.js"></script><script charset="utf-8" src="js/ecom"></script> 
<script type="text/javascript">var vjAcc = '860010-00401';</script><script type="text/javascript" src="js/a.js"></script><img src="/Public/Images/a.gif" style="visibility:hidden;position:absolute;left:0px;top:0px;z-index:-1" height="1" width="1">
</body>
</html>