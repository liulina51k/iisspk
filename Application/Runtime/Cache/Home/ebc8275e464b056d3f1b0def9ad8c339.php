<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo (C("DB_CHARSET")); ?>" />
<meta name="description" content="正方评论,<?php echo ($pknowinfo["title"]); ?>,战略网" />
<meta name="keywords" content="正方评论,战略网,战略网" />
<title>正方评论-战略网-辩论pk台-战略网</title>
<link href="<?php echo (SITE); ?>/Public/style/basic.v1.4.css" rel="stylesheet" type="text/css">
<link href="<?php echo (SITE); ?>/Public/style/pk_s1.css" rel="stylesheet" type="text/css">
<link href="<?php echo (SITE); ?>/Public/style/plk_new.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.comm.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.pk.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.cookie.js"></script>
<script>

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
	off = $("#cmm_"+id).offset();
	$.get(siteurl+"/do.php", {inajax:1,ado:'pk', ac:'pk_reap', parameter: $("#categoryid").val()+'_'+id}, function(data){
		comment('发表评论', checkReturn(data), off.top+20, off.left-200,500,0);
	});

}
function clicks(id) {
	ids=$("li [id="+id+"]").parents('.comment_box').attr('id');
	$("."+ids).attr('value',$("."+ids).attr('value')+$("li [id="+id+"]").attr("id"));
}
function setform(id){
	$("#"+id+" input[name='subject']").val('标题:');
	$("#"+id+" textarea[name='content']").val('内容:');
}
function showmessage(type,message){
	$(".plk_tishi1").hide();
	$(".plk_tishi2").hide();
	$(".plk_tishi3").hide();
	var str = '<p>' + message + '</p>';
	$(".plk_tishi_nrr_0"+type).html(str);
	$(".plk_tishi"+type).show();
	
	if(type!='3'){
		//添加读取cookie
	    usercomment = $.cookie('usercomment');
	    if('' != usercomment && null!= usercomment && undefined != usercomment){
	        $("#boxbody textarea[name='content']").val(usercomment);
	        $.cookie('usercomment','');
	    }
	}
	return false;
}
$(function (){
	$(".plk_text").live("click",function(){
    	$(".plk_tishi1").hide();
    	$(".plk_tishi2").hide();
    	$(".plk_tishi3").hide();
		$(".plk_text_nr").focus();
    });
});

</script>
</head>
<body>
<input id="infoid" type="hidden" value="<?php echo ($pknowinfo["id"]); ?>" />
<input id="categoryid" type="hidden" value="-1" />
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="<?php echo (SITE); ?>/Public/images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="<?php echo (SITE); ?>/Public/images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <?php if($pkinfo["id"] > 223): ?><div class="top_banner"><img src="<?php echo (ATTPATH); ?>/<?php echo ($pkinfo["imgurl"]); ?>" alt="<?php echo ($pkinfo["title"]); ?>"></div><?php endif; ?>
</div>
<div id="main_list">
  <a href="<?php echo (IISSSITE); ?>/pk/index/<?php echo ($pknowinfo["id"]); ?>" title="<?php echo ($pknowinfo["title"]); ?>" class="tm"><h1><?php echo ($pknowinfo["title"]); ?></h1></a><h2>所有评论仅代表网友意见，战略网保持中立</h2>
  <div class="top_bg_list"></div>
  <div class="main_l_list">
  <!--左侧选项卡-->
    <div class="tabs">
        <span class="topsBtnDiv">
            <ul>
            <li><a href="<?php echo (IISSSITE); ?>/pkt/app/<?php echo ($pknowinfo["id"]); ?>/1" class="current"><span>正方评论</span></a></li>
            <li><a href="<?php echo (IISSSITE); ?>/pkt/opp/<?php echo ($pknowinfo["id"]); ?>/1"><span>反方评论</span></a></li>
            </ul>
        </span>
        <div class="tabsdiv">
			<?php if(is_array($goodcomm)): foreach($goodcomm as $key=>$vo): ?><div class="review">
				<dl class="margin15">
					<dt class="no_bg"><em><?php echo ($vo["floor"]); ?>楼</em><?php echo ($vo["postip"]); ?><a><?php echo ($vo["username"]); ?></a><?php echo (date("Y-m-d H:i:s",$vo["postdate"])); ?></dt>
					<dd class="font14">
						<?php echo ($vo["quotehtml"]); ?><p><?php echo ($vo["content"]); ?></p>
					</dd>
					<dd class="bot_bit">
						<ul>
							<li><a class="support" href="javascript:regoodtop(<?php echo ($vo["id"]); ?>);">支持</a><span class="colorred"><b id="topgood<?php echo ($vo["id"]); ?>"><?php echo ($vo["good"]); ?></b>票</span></li>
							<li><a class="support" href="javascript:rebadtop(<?php echo ($vo["id"]); ?>);">反对</a><span class="colorblue"><b id="topbad<?php echo ($vo["id"]); ?>"><?php echo ($vo["bad"]); ?></b>票</span></li>
							<li id="cmm_<?php echo ($vo["id"]); ?>"><a class="blackonline" href="javascript:recommenttop(<?php echo ($vo["id"]); ?>);">回复此评论</a></li>
							<li class="zf" id="cmmm_<?php echo ($vo["id"]); ?>"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="javascript:transmit_pk(<?php echo ($pknowinfo["id"]); ?>,'<?php echo (strip_tags($vo["content"])); ?>','cmmm_<?php echo ($vo["id"]); ?>');">转发</a></li>
						</ul>
						<br class="clear" />
					</dd>
					<dd class="dd_boder"></dd>
				</dl>
			</div><?php endforeach; endif; ?>
			<?php echo ($show); ?>
		</div>
    </div>
  </div>
  <div class="main_r_list">
    <div class="block_list">
      <span class="fright"><img src="<?php echo (SITE); ?>/Public/images/pk/pk_h1bg_r.jpg"></span><a class="wqht"></a>
      </div>
      <div class="pk_new_list">
      <ul>
		<?php if(is_array($pkoldlist)): foreach($pkoldlist as $k=>$vo): ?><li><a href="<?php echo ($vo["pkurl"]); ?>" title="<?php echo ($vo["title"]); ?>" target="_blank"><span id="pktitle<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></span></a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list"><b id="agree<?php echo ($vo["id"]); ?>"><?php echo ($vo["agreevote"]); ?></b> / <b id="oppose<?php echo ($vo["id"]); ?>"><?php echo ($vo["opposevote"]); ?></b></div>
            <div class="pk_jdt_a_list"><a class="zczhengfang"  onclick="pkvote(<?php echo ($vo["id"]); ?>, 'agree');" style="cursor:pointer"></a><a class="zcfanfang" onclick="pkvote(<?php echo ($vo["id"]); ?>, 'oppose');" style="cursor:pointer"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:<?php echo ($vo["point_good"]); ?>%"></span><span class="fanfang" style="width:<?php echo ($vo["point_bad"]); ?>%"></span></div>
        </div>
        </li><?php endforeach; endif; ?>
      </ul>
    </div>
  </div>
  <div class="clear"></div>
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
</body>
</html>