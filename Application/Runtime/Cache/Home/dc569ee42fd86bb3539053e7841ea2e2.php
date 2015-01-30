<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="">
<meta name="keywords" content="">
<title>辩论pk台_战略网</title>
<link href="/Public/Style/basic.css" rel="stylesheet" type="text/css">
<link href="/Public/Style/pk.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/Public/Js/jquery.js"></script>
<script type="text/javascript" src="/Public/Js/function.comm.js"></script>
<script type="text/javascript" src="/Public/Js/function.comments.js"></script>
<script type="text/javascript" src="/Public/Js/function.pk.js"></script>
<script type="text/javascript" src="/Public/Js/function.login.js"></script>
<script type="text/javascript" src="/Public/Js/function.cookie.js"></script>
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
	off = $("#cmm_"+id).offset();
	$.get(siteurl+"/do.php", {inajax:1,ado:'pk', ac:'pk_reap', parameter: $("#categoryid").val()+'_'+id}, function(data){
		comment('发表评论', checkReturn(data), off.top+20, off.left-210,500,0);
	});

}

function clicks(id) {
	ids=$("li [id="+id+"]").parents('.comment_box').attr('id');
	$("."+ids).attr('value',$("."+ids).attr('value')+$("li [id="+id+"]").attr("id"));
}
//PK会员登录
function pklogin(){
	//清除帐号密码的空格
	var username = encodeURI( $('input[name="username"]').val().replace(/\s/g,"") );
	var password = encodeURI( $('input[name="password"]').val().replace(/\s/g,"") );

	if ( username === '' ) {
		$('input[name="username"]').focus();
		return;
	}
	if ( password === '' ) {
		$('input[name="password"]').focus();
		return;
	}

	var url = "http://www.iisspk.com/index.php/Home/Pk/pk_ajax_login/parameter/"+username+"_"+password+"/json/1/jsoncallback/?";
	$.getJSON(url,function(json){
		if ( json.logininfo) {
			alert(json.logininfo);
		} else {
			$('.com_login').find('form').hide();
			$('.com_login #com_login').append(json.data);
		}
	});
}
//2012-3-19 退出登录
function loginout(id,param){
   doAjaxGetJSON(id,'pk','pk_loginout',param);
}
//获取评论总个数
function getCommentTotal(pkid) {
	var url = siteurl+"/do.php?inajax=1&do=comment&ac=comment_total&parameter=-1_"+pkid+"&json=1&jsoncallback=?";
	$.getJSON(url,function(json){
		$('#comment_total').text(json.data);
	});
}
var wid = null;
$(function(){
	//页面加载，把帐号和密码框里面的内容都清空
	$("input[name='reguser']").click(function(){
		window.location=siteurl+"/user/reg";
	});
	$(".text").attr('value','');
	$(".text").focus(function(){
		$(this).attr('value','');
	});

	//获取PKID
	var pkid = $('#infoid').attr('value');
	//获取评论总数
	getCommentTotal(pkid);
	wid = window.setInterval("showheight()",1000);
});
function showheight(){
	var lefth = $(".main_left .review_bk").height();
	var righth = $(".main_right .reviews_bk").height();
	var toplefth = $(".hot_red").height();
	var toprighth = $(".hot_blue").height();
	var tmph = g = 0;
	if(lefth>26 && lefth+toplefth>righth+toprighth){
		var tmph = toplefth;
		$(".main_left .review_bk .review").each(function(){
			tmph += $(this).height();
			if(tmph>righth+toprighth){
				g += 1;
				if(g>1){
					$(this).hide();
				}
			}
		});
		window.clearInterval(wid);
	}else{
		if(righth>26){
			var tmph = toplefth;
			$(".main_right .reviews_bk .review").each(function(){
				tmph += $(this).height();
				if(tmph>lefth+toplefth){
					g += 1;
					if(g>1){
						$(this).hide();
					}
				}
			});
			window.clearInterval(wid);
		}
	}
}
</script>
</head>
<body>
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="/Public/Images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="/Public/Images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <div class="top_banner"><img src="/Public/Images/111421_645.jpg" alt="中俄是否已建立新的反美轴心？"></div>
</div>
<div style="margin:8px 0px 8px 0px;"> 
  <div style="display:none">-</div><a href="#"><img src="/Public/Images/ad.jpg" width="960" height="60" /></a></div>
<div id="main">
  <div class="main_l">
    <div class="block">
      <h1><span class="fright"><img src="/Public/Images/pk_h1bg_r.jpg"></span><a class="bjzl"></a></h1>
      <p>　<?php echo (substr($pkinfo["context"],0,1000)); if($url != ''): ?>[<a class="blue" href="<?php echo ($url); ?>" target="_blank">详细背景资料</a>]<?php endif; ?></p>
    </div>
  </div>
  <div class="main_r">
    <div class="block">
      <h1><span class="fright"><img src="/Public/Images/pk_h1bg_r.jpg"></span><span class="fright"><a href="/index.php/Home/Pk/pk_list">更多&gt;&gt;</a></span><a class="wqht"></a></h1>
      <ul>
        <?php if(is_array($pklist)): foreach($pklist as $k=>$vo): if(($k >= 0 ) AND ($k < 8)): ?><li><a href="<?php echo ($vo["pkurl"]); ?>" title="<?php echo ($vo["title"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endif; endforeach; endif; ?>
      </ul>
    </div>
  </div>
  <div class="clear"></div>
</div>
<div id="main">
  <div class="top_bg"></div>
</div>
<div id="main">
  <div class="pk_img">
    <div class="main_left">
      <div class="pk_yes">
        <h1>正方:<?php echo ($pkinfo["agreeguide"]); ?></h1>
        <form>
          <input onclick="pkvote(<?php echo ($pkinfo["id"]); ?>, 'agree');return false;" src="/Public/Images/pk_yes_botton.jpg" type="image">
          <span>[<a class="red" id="agree<?php echo ($pkinfo["id"]); ?>"><?php echo ($pkinfo["agreevote"]); ?></a> 票]</span>
        </form>
        <p><?php echo ($pkinfo["agreeintro"]); ?></p>
      </div>
    </div>
    <div class="main_right">
      <div class="pk_no">
        <h1>反方:<?php echo ($pkinfo["opposeguide"]); ?></h1>
        <form>
          <input onclick="pkvote(<?php echo ($pkinfo["id"]); ?>, 'oppose');" src="/Public/Images/pk_no_botton.jpg" type="image">
          <span>[<a class="blue" id="oppose<?php echo ($pkinfo["id"]); ?>"><?php echo ($pkinfo["opposevote"]); ?></a> 票]</span>
        </form>
        <p><?php echo ($pkinfo["opposeintro"]); ?></p>
      </div>
    </div>
    <div class="clear"></div>
    <div class="pk_rule"><a href="http://www.chinaiiss.com/pkt/help/1" target="_blank">PK规则说明&gt;&gt;</a></div>
  </div>
  <div class="pk_vs_pic"> <img src="/Public/Images/pk_red.gif" class="fleft" height="80" width="65">
    <div class="vleft" style="padding-left:626px;"> <img src="/Public/Images/pk_tank.jpg" height="50" width="160"> </div>
    <img src="/Public/Images/pk_blue.gif" class="fright" height="80" width="65"> </div>
  <div class="clear"></div>
</div>
<div id="main">
  <div class="comment_bg">
    <div class="com_login">
      <p class="fright">(评论<a class="red" id="comment_total">0</a>条 <a class="red"><?php echo ($pkinfo["clicks"]); ?></a>名网友参与)</p>
      <div id="com_login">
        <form onsubmit="return false;">
          用户名
          <input class="text" name="username" type="text">
          密 码
          <input value="" class="text" name="password" type="password">
          <input name="loginuser" onclick="pklogin();" src="/Public/Images/login_bot.jpg" type="image">
          <input name="reguser" src="/Public/Images/register_bot.jpg" type="image">
          <!--2012-1-11 pk增加外部接入样式--> 
          <span>其他方式登录：<a class="SINA_login" href="###" onclick="out_login( 'sina')">微博登录</a><a class="QQ_login" href="###" onclick="out_login( 'qq' )">QQ登录</a></span> 
          <!--end-->
        </form>
      </div>
    </div>
    <div class="main_left">
      <div class="comment" id="comment_submityes">
        <h1><a></a></h1>
        <form onsubmit="return false;">
          <textarea name="content" onfocus="delinput($(this), '内容:');">内容:</textarea>
          <input src="/Public/Images/pk_texttj.jpg" onclick="checkform('comment_submityes');comments_submit('comment_submityes', '-1_435');setTimeout('getCommentTotal(435)',5000);" type="image">
        </form>
      </div>
    </div>
    <div class="main_right">
      <div class="comment" id="comment_submitno">
        <h1><a class="zcff"></a></h1>
        <form onsubmit="return false;">
          <textarea name="content" onfocus="delinput($(this), '内容:');">内容:</textarea>
          <input src="/Public/Images/pk_texttj.jpg" onclick="checkform('comment_submitno');comments_submit('comment_submitno', '-1_435');setTimeout('getCommentTotal(435)',5000);" type="image">
        </form>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div style="padding:8px 0px 8px 0px;"> 
  <div style="display:none">-</div>
<a href="#"><img src="/Public/Images/ad.jpg" width="960" height="60" /></a></div>
<div id="main">
  <div class="main_left">
    <div class="hot_red">
      <h1><a></a></h1>
      <?php if(is_array($goodcomm)): foreach($goodcomm as $key=>$vo): ?><div class="review" id="cmmtop_<?php echo ($vo["id"]); ?>">
        <dl>
          <dt><em><?php echo ($vo["postdate"]); ?></em><a href="#"><?php echo ($vo["username"]); ?></a></dt>
          
           <?php if($vo["quotecomm"] != ''): ?><dd>
	           <div class="bg">引用：<?php echo ($vo["quotecomm"]["username"]); ?></a><br />
					<span>
					<p><?php echo ($vo["quotecomm"]["content"]); ?></p>
					</span>
			   </div>
		   </dd><?php endif; ?>
		   <dd><p><?php echo ($vo["content"]); ?></p></dd>
          </dd>
          <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(<?php echo ($vo["id"]); ?>);">支持</a><span class="colorred"><b id="topgood<?php echo ($vo["id"]); ?>"><?php echo ($vo["good"]); ?></b>票</span></li>
              <li><a class="support" href="javascript:rebadtop(<?php echo ($vo["id"]); ?>);">反对</a><span class="colorblue"><b id="topbad<?php echo ($vo["id"]); ?>"><?php echo ($vo["bad"]); ?></b>票</span></li>
              <li><a class="blackonline" href="javascript:recommenttop(<?php echo ($vo["id"]); ?>);">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="javascript:transmit_pk(<?php echo ($pkinfo["id"]); ?>,'<?php echo (strip_tags($vo["content"])); ?>','appyes');">转发</a></li>
            </ul>
            <br class="clear">
          </dd>
        </dl>
      </div><?php endforeach; endif; ?>
      <div class="review_more"><a class="blueonline" href="http://www.iisspk.com/Pkt/app/<?php echo ($pkinfo["id"]); ?>">查看全部评论&gt;&gt;</a></div>
    </div>
    
	<div class="review_bk"><a name="new_comment_view"></a>
		<div id="gv_comments_pklist_-1_<?php echo ($pkinfo["id"]); ?>"></div>
		<div class="review_more"><a class="blueonline" href="http://www.iisspk.com/Pkt/app/<?php echo ($pkinfo["id"]); ?>">查看全部评论>></a></div>
	</div>
  </div>
  <div class="main_right">
    <div class="hot_blue">
      <h1><a></a></h1>
      <?php if(is_array($badcomm)): foreach($badcomm as $key=>$vo): ?><div class="review" id="cmmtop_<?php echo ($vo["id"]); ?>">
        <dl>
          <dt><em><?php echo ($vo["postdate"]); ?></em><a href="#"><?php echo ($vo["username"]); ?></a></dt>
          
           <?php if($vo["quotecomm"] != ''): ?><dd>
	           <div class="bg">引用：<?php echo ($vo["quotecomm"]["username"]); ?></a><br />
					<span>
					<p><?php echo ($vo["quotecomm"]["content"]); ?></p>
					</span>
			   </div>
		   </dd><?php endif; ?>
		   <dd><p><?php echo ($vo["content"]); ?></p></dd>
          </dd>
          <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(<?php echo ($vo["id"]); ?>);">支持</a><span class="colorred"><b id="topgood<?php echo ($vo["id"]); ?>"><?php echo ($vo["good"]); ?></b>票</span></li>
              <li><a class="support" href="javascript:rebadtop(<?php echo ($vo["id"]); ?>);">反对</a><span class="colorblue"><b id="topbad<?php echo ($vo["id"]); ?>"><?php echo ($vo["bad"]); ?></b>票</span></li>
              <li><a class="blackonline" href="javascript:recommenttop(<?php echo ($vo["id"]); ?>);">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="javascript:transmit_pk(<?php echo ($pkinfo["id"]); ?>,'<?php echo (strip_tags($vo["content"])); ?>','appno');">转发</a></li>
            </ul>
            <br class="clear">
          </dd>
        </dl>
      </div><?php endforeach; endif; ?>
      <div class="review_more"><a class="blueonline" href="http://www.iisspk.com/Pkt/opp/<?php echo ($pkinfo["id"]); ?>">查看全部评论&gt;&gt;</a></div>
    </div>
    
	<div class="reviews_bk">
		<div id="gv_comments_pktlist_-1_<?php echo ($pkinfo["id"]); ?>"></div>
		<div class="review_more"><a class="blueonline" href="http://www.iisspk.com/Pkt/opp/<?php echo ($pkinfo["id"]); ?>">查看全部评论>></a></div>
	</div>
  </div>
  <div class="clear"></div>
</div>
<div style="margin:8px 0px 8px 0px;"> 
  <script type="text/javascript">/*PK台3通栏960*60，创建于2011-11-29*/ var cpro_id = 'u694612';</script><script src="/Public/Images/c.js" type="text/javascript"></script><script type="text/javascript" charset="utf-8" src="/Public/Images/ecom_002"></script>
  <div style="display:none">-</div>
<a href="#"><img src="/Public/Images/ad.jpg" width="960" height="60" /></a></div>
<div id="main">
  <div class="block">
    <h1><span class="fright"><img src="/Public/Images/pk_h1bg_r.jpg"></span><span class="fright"><a href="http://www.chinaiiss.com/pkt/plist/1" target="_blank">更多&gt;&gt;</a></span><a class="wqht"></a></h1>
  </div>
  <div class="main_left">
  <?php if(is_array($pklist)): foreach($pklist as $k=>$vo): if(($k < 6) AND ($k % 2 == 0)): ?><div class="pk_new">
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
  <?php if(is_array($pklist)): foreach($pklist as $k=>$vo): if(($k < 6) AND ($k % 2 == 1)): ?><div class="pk_new">
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
  <div class="Blank"></div>
</div>
<div class="Blank"></div>
<div id="footer">
  <div>
    <div class="footer_x"></div>
    <span> <a href="http://www.chinaiiss.com/foot/about">关于我们</a>| <a href="http://www.chinaiiss.com/foot/contact">联系我们</a>| <a href="http://www.chinaiiss.com/foot/hire/">加入我们</a>| <a href="http://www.chinaiiss.com/foot/guestbook">留言中心</a>| <a href="http://www.chinaiiss.com/foot/privacy">隐私保护</a>| <a href="http://www.chinaiiss.com/foot/ads">广告服务</a>| <a href="http://www.chinaiiss.com/foot/ad">友情链接</a>| <a href="http://www.chinaiiss.com/foot/sitemap">网站地图</a> </span> <br>
    在线投稿邮箱：tougao@chinaiiss.com    值班电话：工作日 010-62360932(9:00-18:00)<br>
    Copyright ©2002-<script>document.write(new Date().getFullYear());</script>2013 www.chinaiiss.com All Rights Reserved. 京ICP证110164号 京ICP备11008173号<script src="js/stat.php" language="JavaScript"></script><script src="js/cnzz_core.php" charset="utf-8" type="text/javascript"></script><a href="http://www.cnzz.com/stat/website.php?web_id=215831" target="_blank" title="站长统计"><img src="/Public/Images/pic.gif" border="0" hspace="0" vspace="0"></a></div>
</div>
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fcd0a687f19db4e63c481a5b03c59f4e3' type='text/javascript'%3E%3C/script%3E"));


$('.return_top').live('click',function(){
	$("html, body").animate({ scrollTop: 0 }, 'fast');
});	
</script>
</body>
</html>