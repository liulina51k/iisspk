<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ($sitechar); ?>" />
<meta name="description" content="<?php echo ($context); ?>" />
<meta name="keywords" content="<?php echo ($title); ?>" />
<title><?php echo ($pkinfo["title"]); ?>-PK台-战略网</title>
<link href="/Public/style/basic.v1.4.css" rel="stylesheet" type="text/css">
<link href="/Public/style/pk.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/js/jquery.js"></script>
<script type="text/javascript" src="/Public/js/function.comm.js"></script>
<script type="text/javascript" src="/Public/js/function.cookie.js"></script>
<script>
function checkform(id){
  var content = $("#"+id+" textarea[name='content']").val();
  var subject = $("#"+id+" input[name='subject']").val();
  if(subject == '标题:' ){
     $("#"+id+" input[name='subject']").val('');
  }
  if(content == '内容:' ){
     $("#"+id+" textarea[name='content']").val('');
  }
  return false;
}

function setform(id){
$("#"+id+" input[name='subject']").val('标题:');
$("#"+id+" textarea[name='content']").val('内容:');
}
//评论支持点击
function regoodtop(id){
	$.get(siteurl+"/do.php", {inajax:1,ado:'comment', ac:'comment_vote', parameter: $("#categoryid").val()+'_'+id+'_good'}, function(data){
		      $('#topgood'+id).text(parseInt( $('#topgood'+id).text())+1);
		});

}

//评论反对点击
function rebadtop(id){
	$.get(siteurl+"/do.php", {inajax:1,ado:'comment', ac:'comment_vote', parameter: $("#categoryid").val()+'_'+id+'_bad'}, function(data){
		      $('#topbad'+id).text(parseInt( $('#topbad'+id).text())+1);
		});

}

//回复此评论
function recommenttop(id){
	off = $("#cmmtop_"+id).offset();
	$.get(siteurl+"/do.php", {inajax:1,ado:'comment', ac:'comment_reap', parameter: $("#categoryid").val()+'_'+id}, function(data){
																															
		    apalert('反驳评论', checkReturn(data), off.top, off.left+200, 360);
		});

}
 
</script>
</head>
<body>
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="/Public/Images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="/Public/Images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <div class="top_banner"><img src="/Public/Images/111421_645.jpg" alt="中俄是否已建立新的反美轴心？"></div>
</div>
<input id="infoid" type="hidden" value="<?php echo ($pkinfo["id"]); ?>" />
<input id="categoryid" type="hidden" value="-1" />
<div id="pk_main"><div id="borter">
	<div id="pk_news">
		<img src="<?php echo ($attpath); ?>/<?php echo ($imgurl); ?>" width="740" height="80" alt="<?php echo ($title); ?>" />
<div class="main_news"><a class="blackfont"><?php echo (mb_substr($pkinfo["context"],0,500,'utf8')); if($pkinfo["url"] != ''): ?>[<a class="blue" href="<?php echo ($pkinfo["url"]); ?>" target="_blank">详细背景资料</a>]<?php endif; ?></a></div>
	</div>
	<div id="pk_more">
	<span class="more_right"><b>>>></b></span><h3><a href="<?php echo ($site); ?>/Pkt/plist/">往期辩论话题</a></h3>
		<ul>
        <?php if(is_array($pklist)): foreach($pklist as $k=>$vo): if(($k >= 0 ) AND ($k < 8)): ?><li>·<a class="blue" href="<?php echo ($vo["pkurl"]); ?>" title="<?php echo ($vo["title"]); ?>"><?php echo (mb_substr($vo["title"],0,16,'utf8')); ?></a></li><?php endif; endforeach; endif; ?>
		</ul>
	</div>
	<div class="clear" id="gv_pk_viewnum_<?php echo ($pkinfo["id"]); ?>"></div>
<div id="pk_title">
<a class="blueonline fright" href="<?php echo ($site); ?>/Pkt/help/1" target="_parent">PK规则说明>></a>
<?php echo ($pkinfo["title"]); ?>
</div>
<div id="vote_maim">
<div id="vote_l"><h4>正方:<?php echo ($pkinfo["agreetitle"]); ?></h4>
<div class="votes">[<span class="vote_number" id="agree<?php echo ($pkinfo["id"]); ?>"><?php echo ($pkinfo["agreevote"]); ?></span> 票]</div><img src="/Public/images/pk/vote_y.jpg" style="cursor:pointer;" onclick="pkvote(<?php echo ($pkinfo["id"]); ?>, 'agree');"/>
<div id="vote_yes"><?php echo ($pkinfo["agreeintro"]); ?></div></div>
<div id="vote_r"><h4>反方:<?php echo ($pkinfo["opposetitle"]); ?></h4>
<div class="votes">[<span class="votes_number" id="oppose<?php echo ($pkinfo["id"]); ?>"><?php echo ($pkinfo["opposevote"]); ?></span> 票]</div><img src="/Public/images/pk/vote_n.jpg" style="cursor:pointer;" onclick="pkvote(<?php echo ($pkinfo["id"]); ?>, 'oppose');" />
<div id="vote_no"><?php echo ($pkinfo["opposeintro"]); ?></div></div>
<div class="clear"></div>
<!---start-->
<div class="pk_vs_pic"><img src="<?php echo ($site); ?>/images/{if $result <= 0}pk_redno.gif{elseif $result >=1}pk_redyes.gif{else}pk_red.gif{/if}" width="65" height="80" class="fleft" />
  <div class="vleft" style="padding-left:<?php echo ($result*626); ?>px;"><img src="/Public/images/pk/pk_tank.jpg" width="160" height="50"></div>
<img src="<?php echo ($site); ?>/images/{if $result <= 0}pk_blueyes.gif{elseif $result >=1}pk_blueno.gif{else}pk_blue.gif{/if}" width="65" height="80" class="fright" /></div>
<div class="clear"></div>
</div>
<!---end-->
<div class="box_main box_x">
<div id="comment_bg"><div id="comment_submityes" class="comment"><img src="/Public/images/pk/pk_yes.jpg" width="71" height="18" /><label><input name="subject" type="text" value="标题:" onfocus="delinput($(this), '标题:');"/></label><form onsubmit="return false;"><textarea name="content" class="form_text" onfocus="delinput($(this), '内容:');">内容:</textarea><div><input type="image" class="inputs"  onclick="checkform('comment_submityes');comments_submit('comment_submityes', '-1_<?php echo ($id); ?>');setform('comment_submityes')" src="/Public/images/pk/pk_input.jpg" /><br /></div></form>
    </div></div>
<div id="comment_bg"><div id="comment_submitno" class="comment"><img src="/Public/images/pk/pk_no.jpg" width="71" height="18" /><label><input name="subject" type="text" value="标题:" onfocus="delinput($(this), '标题:');" /><input name="commenttype" type="hidden" value="1" /></label><form onsubmit="return false;"><textarea name="content" class="form_text" onfocus="delinput($(this), '内容:');">内容:</textarea><div><input type="image" class="inputs"  onclick="checkform('comment_submitno');comments_submit('comment_submitno', '-1_<?php echo ($id); ?>');setform('comment_submitno')" src="/Public/images/pk/pk_input.jpg" /><br /></div></form></div></div><div class="clear"></div>
</div>
<div class="box_main box_xt">
<div id="review_left">
 <?php if(is_array($goodcomm)): foreach($goodcomm as $key=>$vo): ?><div class="review" id="cmmtop_<?php echo ($vo["id"]); ?>">
<dl><dt class="r"><a href="<?php echo ($site); ?>/Pkt/app/
<?php echo ($pkinfo["id"]); ?>" class="blue morelist">查看正方全部评论>></a><img src="/Public/images/pk/best_y.jpg" /><Br /><em><?php echo (date("Y-m-d H:i:s",$vo["postdate"])); ?></em><a target="_blank" href="#"><?php echo ($vo["username"]); ?></a></dt>
             <dd>标题：<?php echo ($vo["subject"]); ?></dd>
            <?php if($vo["quotecomm"] != ''): ?><dd>
	           <div class="bg">引用：<?php echo ($vo["quotecomm"]["username"]); ?></a><br />
					<span>
					<p><?php echo ($vo["quotecomm"]["content"]); ?></p>
					</span>
			   </div>
		   </dd><?php endif; ?>
             
              <dd>
                   <p><?php echo ($vo["content"]); ?></p>
        </dd>
             <dd>
                  <ul><li><span class="colorred" id="topgood<?php echo ($vo["id"]); ?>"><?php echo ($vo["good"]); ?></span>人<br><a href="javascript:regoodtop(<?php echo ($vo["id"]); ?>);">支持</a></li><li><span class="colorblue" id="topbad<?php echo ($vo["id"]); ?>"><?php echo ($vo["bad"]); ?></span>人<br><a href="javascript:rebadtop(<?php echo ($vo["id"]); ?>);">反对</a></li></ul><Br /><a class="blackonline" href="javascript:recommenttop(<?php echo ($vo["id"]); ?>);">反驳此评论</a>
				  <br class="clear" />       
             </dd>
</dl>
</div><?php endforeach; endif; ?>

<div id="review_r"></div>
<div id="gv_comments_list_-1_<?php echo ($id); ?>"></div>

<div id="rev_m"><a href="<?php echo ($site); ?>/pk/app/<?php echo ($id); ?>" class="blueonline">查看正方全部评论>></a></div></div>
<div id="review_right">

<?php if(is_array($badcomm)): foreach($badcomm as $key=>$vo): ?><div class="reviews" id="cmmtop_<?php echo ($vo["id"]); ?>">
<dl><dt class="n"><a href="<?php echo ($site); ?>/Pkt/opp/
<?php echo ($pkinfo["id"]); ?>" class="blue morelist">查看反方全部评论>></a><img src="/Public/images/pk/best_n.jpg" /><Br /><em><?php echo (date("Y-m-d H:i:s",$vo["postdate"])); ?></em><a class="blue" target="_blank" href="#"><?php echo ($vo["username"]); ?></a></dt>
             <dd>标题：<?php echo ($vo["subject"]); ?></dd>
           <?php if($vo["quotecomm"] != ''): ?><dd>
	           <div class="bg">引用：<?php echo ($vo["quotecomm"]["username"]); ?></a><br />
					<span>
					<p><?php echo ($vo["quotecomm"]["content"]); ?></p>
					</span>
			   </div>
		   </dd><?php endif; ?>
             
              <dd>
                   <p><?php echo ($vo["content"]); ?></p>
        </dd>
             <dd>
                  <ul><li><span class="colorred" id="topgood<?php echo ($vo["id"]); ?>"><?php echo ($vo["good"]); ?></span>人<br><a href="javascript:regoodtop(<?php echo ($vo["id"]); ?>);">支持</a></li><li><span class="colorblue" id="topbad<?php echo ($vo["id"]); ?>"><?php echo ($vo["bad"]); ?></span>人<br><a href="javascript:rebadtop(<?php echo ($vo["id"]); ?>);">反对</a></li></ul><Br /><a class="blackonline" href="javascript:recommenttop(<?php echo ($vo["id"]); ?>);">反驳此评论</a>
				  <br class="clear" />       
             </dd>
</dl>
</div><?php endforeach; endif; ?>



<div id="review_n"></div>
<div id="gv_comments_tlist_-1_<?php echo ($pkinfo["id"]); ?>"></div>
<div id="rev_m"><a href="<?php echo ($site); ?>/Pkt/opp/
<?php echo ($pkinfo["id"]); ?>" class="blueonline">查看反方全部评论>></a></div>
</div>
<div class="clear"></div>

  </div></div></div></div>
<div id="footer">
  <div>
    <div class="footer_x"></div>
    <span> <a href="http://www.chinaiiss.com/foot/about">关于我们</a>| <a href="http://www.chinaiiss.com/foot/contact">联系我们</a>| <a href="http://www.chinaiiss.com/foot/hire/">加入我们</a>| <a href="http://www.chinaiiss.com/foot/guestbook">留言中心</a>| <a href="http://www.chinaiiss.com/foot/privacy">隐私保护</a>| <a href="http://www.chinaiiss.com/foot/ads">广告服务</a>| <a href="http://www.chinaiiss.com/foot/ad">友情链接</a>| <a href="http://www.chinaiiss.com/foot/sitemap">网站地图</a> </span> <br>
    在线投稿邮箱：tougao@chinaiiss.com    值班电话：工作日 010-62360932(9:00-18:00)<br>
    Copyright ©2002-<script>document.write(new Date().getFullYear());</script>2013 www.chinaiiss.com All Rights Reserved. 京ICP证110164号 京ICP备11008173号<script src="js/stat.php" language="JavaScript"></script><script src="js/cnzz_core.php" charset="utf-8" type="text/javascript"></script><a href="http://www.cnzz.com/stat/website.php?web_id=215831" target="_blank" title="站长统计"><img src="/Public/Images/pic.gif" border="0" hspace="0" vspace="0"></a></div>
</div>
<script type="text/javascript" >BAIDU_CLB_SLOT_ID = "253313";</script>
<script type="text/javascript" src="http://cbjs.baidu.com/js/o.js"></script>
</body>
</html>