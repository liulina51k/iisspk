<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo (C("DB_CHARSET")); ?>">
<meta name="description" content="<?php echo ($context); ?>">
<meta name="keywords" content="<?php echo ($info["subject"]); ?>" />
<title><?php if($info["seosubject"] != ''): echo ($info["seosubject"]); else: echo ($info["subject"]); ?>_全球议事厅_<?php endif; ?>战略网</title>
<link href="<?php echo (SITE); ?>/Public/style/basic.v1.4.css" rel="stylesheet" type="text/css">
<link type="text/css" href="<?php echo (SITE); ?>/Public/style/chamber.v1.2.css" rel="stylesheet">
<link type="text/css" href="<?php echo (SITE); ?>/Public/style/thickbox.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.comm.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.conference.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.comments.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.vote.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.login.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/thickbox.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/jquery-ui-1.8.20.custom.min.js"></script>
 
<script type="text/javascript">
var conference_title = "<?php echo ($info["subject"]); ?>";conference_id = "<?php echo ($info["id"]); ?>";conference_no = '<?php echo ($info["no"]); ?>';conference_topno = '<?php echo ($info["topno"]); ?>';
conference_topno = conference_topno>0 ? '（特刊）' : '';
var conference_subject = '全球议事厅第'+conference_no+'期'+conference_topno;
function recommenttop(id, bottom, quote){
	bottom = bottom || 0;
	if(!bottom){
		off = $("#cmmhot_"+id).offset();
		position = 3;
	} else {
		off = $("#cmm_"+id).offset();
		position = 4;
	}
	$.get(siteurl+"/do.php?inajax=1&ado=login&ac=login_reap&parameter="+$("#categoryid").val()+'_'+id+'_'+position+'_'+quote,function(data){
		    comment('发表评论', checkReturn(data), off.top, off.left+200,500,200);
			$("#box").draggable({opacity:0.5});
		});
}

function copyLink(){
	var idstr = 'click|conference_copy_'+conference_id+'$'+conference_subject+"搬救兵@@"+siteurl+'/conference/index/'+conference_id+"@@"+conference_title+'_搬救兵';
//	doRecord(idstr);
	if(document.all){
		var clipBoardContent=document.getElementById('txturl').value;
		window.clipboardData.setData("Text",clipBoardContent);
		$("body").append('<div class="help_block"><span class="fright"><input type="image" onclick="$(\'.help_block\').hide();" src="'+siteurl+'/images/conference/help_spanbg.jpg" /></span><p><input type="image" onclick="$(\'.help_block\').hide();" src="'+siteurl+'/images/conference/help_pbg.jpg" /></p></div>');
		$(".help_block").css({
			position:'absolute',
		   'z-index':99999,
		   left: ($(window).width() - $('.help_block').outerWidth())/2, //位置关键
		   top: $(document).scrollTop()-($('.help_block').outerHeight())/2
		});

	}else{
		alert('对不起你的浏览器不支持此复制功能！\n请使用CTRL+C或鼠标右键复制地址栏');
	}
}
function clicks(id) {
	ids=$("li [id=\""+id+"\"]").parents('.comment_box').attr('id');
	$("."+ids).attr('value',$("."+ids).attr('value')+$("li [id=\""+id+"\"]").attr("id"));
}
$(function () {
	$("li [id^='/']").click(function () {
		ids=$(this).parents('.comment_bk').attr('id');
		$("."+ids).attr('value',$("."+ids).attr('value')+$(this).attr("id"));
	});
});
function show_allcomment(param){
	doAjaxProJSON(param, 'conference', 'get_comment', param);
}
</script>

</head>
<body>
<input id="categoryid" type="hidden" value="-7" />
<input id="txturl" type="hidden" value="<?php echo (SITE); ?>/conference/index/<?php echo ($info["id"]); ?>" />
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="<?php echo (SITE); ?>/Public/images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="<?php echo (SITE); ?>/Public/images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <?php if($pkinfo["id"] > 223): ?><div class="top_banner"><img src="<?php echo (ATTPATH); ?>/<?php echo ($pkinfo["imgurl"]); ?>" alt="<?php echo ($pkinfo["title"]); ?>"></div><?php endif; ?>
</div>
<div class="banner_bg">
<div class="banner">
	<div class="banner_left">
	<h1><?php echo ($info["subject"]); ?></h1>
	<p><?php echo ($info["shortsubject"]); ?></p>
	</div>
	<div class="banner_right">
		<p><span>逢周一、五出品</span>  第 <a style="color:#FFFF00;"><?php echo ($info["no"]); ?></a> 期 <?php if($info["topno"] != ''): ?>（特刊）<?php endif; ?></p>
		<img src="<?php echo (ATTPATH); ?>/<?php echo ($info["banner"]); ?>" />
	</div>
	</div>
</div>
  <div id="main">
    <div class="main_t">
    <div class="main_l">
	<div class="text_main">
    <img src="<?php echo (SITE); ?>/Public/images/conference/top_2_bg.jpg" />
      <div class="text_p">
	<p class="p_text"><?php echo (nl2br($info["viewtext"])); ?></p>
        <div class="xy"></div>
        <div id="comment_top" class="comment_bk">
		<h1>
        	<a class="comment_tit" style="cursor: pointer;" name="acomment">快速回复</a>
        	<div class="login_com" id="islogin_top">
           	  	<form onsubmit="return false;" id="conference_login_top">
				<?php if($other["loginuser"] == ''): ?><label>用户名:&nbsp;</label><input type="text" class="txt_com" name="username"><label>&nbsp;密码:&nbsp;</label><input type="password" class="txt_com" name="password"><input type="submit" class="login_btn" value="" onclick="login_on('conference_login_bottom','conference');"><a class="register" target="_blank" href="<?php echo (SITE); ?>/user/reg.html">注册</a>
				<?php else: ?>
				<label>欢迎您，</label><a href="<?php echo ($siteuser); ?>/home/" class="register"><?php echo ($other["loginusername"]); ?></a><a href="javascript:login_quit('conference_login_bottom','conference');" class="register">退出</a><?php endif; ?>
			</form>
            </div>
		</h1>
		<div class="comment_main">
		   <form onsubmit="return false;">
			   <textarea rows="1" class="comment_top line_one" id="toplable" name="content"></textarea>
                          <div class="clear"></div>
			   <p>网友评论仅供其表达个人看法，并不表明战略网同意其观点或证实其描述。</p>
			   <div class="comment_sub">
					<input value="1" name="anonymous" class="niming" type="checkbox"><label>&nbsp;匿名&nbsp;</label>
					<input onclick="conference_submit('comment_top', '-7_<?php echo ($info["id"]); ?>_1');" src="<?php echo (SITE); ?>/Public/images/conference/shout_tj.jpg" class="tj_button" type="image">
			   </div>
			   <div class="clear"></div>
		   </form>
		</div>
	</div>
       <div class="Blank"></div>
    </div>
    </div>
</div>
    <div class="main_r">
      <div class="r_t">
     <img src="<?php echo (SITE); ?>/Public/images/conference/right_h2_r.jpg" />
      <div class="pic_t">
        <div class="pic_pic">
		 <div class="big_pic">
			<?php if(is_array($related)): foreach($related as $key=>$vo): if($key == 0): ?><a href="<?php echo ($vo["id"]); ?>" target="_blank"><img src="<?php echo (ATTPATH); ?>/<?php echo ($vo["pic"]); ?>" width="300" height="218"/></a>
		        <a href="<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["subject"]); ?>" class="zs"><span></span><?php echo ($vo["subject"]); ?></a><?php endif; endforeach; endif; ?>
		</div>
        <ul class="pic_big_text">
				<?php if(is_array($related)): foreach($related as $key=>$vo): if($key > 0): ?><li><a href="<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["subject"]); ?>" target="_blank"><?php echo (mb_substr($vo["subject"],0,22,'utf8')); ?></a></li><?php endif; endforeach; endif; ?>
        </ul>
        </div>
      </div>
      <div class="right_r2">
       <h2><a class="cursor">投票调查</a></h2>
                <div class="vote_main">
			<?php if(is_array($other["arrvote"])): foreach($other["arrvote"] as $key=>$vo): ?><script>var idstr = 'click|conference_vote_<?php echo ($info["id"]); ?>'+'$'+conference_subject+'调查@@<?php echo (SITE); ?>/vote/<?php echo ($vo["voteid"]); ?>.html@@<?php echo ($info["subject"]); ?>_调查';</script>
			<h5><?php echo ($vo["title"]); ?></h5>
			<form id="form<?php echo ($vo["voteid"]); ?>" name="form<?php echo ($vo["voteid"]); ?>" onsubmit="return false;">
			<?php if(is_array($vo["vote"])): foreach($vo["vote"] as $key=>$vo1): ?><label title="<?php echo ($vo1); ?>">
			  <input type='<?php if($vo["votetype"] == 1): ?>radio<?php else: ?>checkbox<?php endif; ?>' name="vote_<?php echo ($vo["voteid"]); ?>" id="vote<?php echo ($key); ?>" value="<?php echo ($key); ?>" />
			<?php echo ($vo1); ?></label><br /><?php endforeach; endif; ?>
			</form>
			<span class="fright"><a href="javascript:viewresearch(<?php echo ($vo["voteid"]); ?>);">查看结果&gt;&gt;</a></span><input class="tj_left" type="image" onclick="submitvote(<?php echo ($vo["voteid"]); ?>);" src="<?php echo (SITE); ?>/Public/images/vote_button.jpg" /><?php endforeach; endif; ?>
		</div>
      </div>
      <div class="right_r2">
       <h2><a class="cursor">本期主持</a></h2>
       <div class="chair">
		<p><?php echo (mb_substr($other["authorsummary"],0,22,'utf8')); ?></p>
		<div class="zhc">
                      <div class="zhc_pic">
                      <img src="<?php echo ($other["avatarpic"]); ?>" style=" width:100px; height:100px;"/>
                      </div>
                      <h3><a href="<?php echo ($other["blogurl"]); ?>/<?php echo ($other["authordomain"]); ?>" target="_blank"><?php echo ($other["author"]); ?></a></h3>
                 </div> 
	</div>
       </div>
      </div>
    </div>
    <div class="clear"></div>
    </div>
    <div class="main_f"></div>
  </div>
  <div class="Blank"></div>
  <div id="main">
    <div class="ty">
	 <div class="plys">
      <h2><a name="acommentlist"></a><span>(共有<?php echo ($other["count"]); ?>位网友参与，<?php echo ($other["commcount"]); ?>条精华评论)</span></h2>
      <div class="clear"></div>
	  
	<div class="ty_text">
	     <div id="gv_conference_list_-7_<?php echo ($info["id"]); ?>_<?php echo ($other["authorid"]); ?>-<?php echo ($other["loginuser"]); ?>"></div>
	</div>
	
        <div class="new_pl">
          <div class="ty_text">
               <div class="pagebox_bk">
			<div id="mutipage1"></div>
	         </div>
        </div>	
        </div>
	
        <div class="Blank"></div>
     </div>
    </div>
  <div class="clear"></div>
  <div class="Blank"></div>
  <?php if($info["endtext"] != ''): ?><div class="ty">
	<div class="past_events">
		<h1><span class="fright"><a href="<?php echo (SITE); ?>/spec/index-274/1.html" target="_blank">更多&gt;&gt;</a></span><a></a></h1>
	<div class="text_pic">
        <img src="<?php echo (SITE); ?>/Public/images/conference/ad2.jpg" />
        <p><?php echo ($info["endtext"]); ?></p>
        <div class="clear"></div>
        </div>
	</div>
    </div><?php endif; ?>
</div>
<div class="Blank"></div>
  <div id="main">
	<div class="comment_bk" id="comment_foot">
		<h1>
        	<a name="acomment" style="cursor: pointer;" class="comment_tit">发表评论</a>
        	<div id="islogin_top" class="login_com">
           	  	<form onsubmit="return false;" id="conference_login_bottom">
				<?php if($other["loginuser"] == ''): ?><label>用户名:&nbsp;</label><input type="text" class="txt_com" name="username"><label>&nbsp;密码:&nbsp;</label><input type="password" class="txt_com" name="password"><input type="submit" class="login_btn" value="" onclick="login_on('conference_login_bottom','conference');"><a class="register" target="_blank" href="<?php echo (SITE); ?>/user/reg.html">注册</a>
				<?php else: ?>
				<label>欢迎您，</label><a href="<?php echo ($siteuser); ?>/home/" class="register"><?php echo ($other["loginusername"]); ?></a><a href="javascript:login_quit('conference_login_bottom','conference');" class="register">退出</a><?php endif; ?>
			 </form>
                </div>
                </h1>
		<ul><li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_1.gif" title="/微笑" id="/ws" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_2.gif" title="/撇嘴" id="/ps" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_3.gif" title="/色" id="/se" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_4.gif" title="/发呆" id="/fd" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_5.gif" title="/酷" id="/ku" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_6.gif" title="/流泪" id="/ll" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_7.gif" title="/害羞 " id="/hx" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_8.gif" title="/闭嘴" id="/bz" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_9.gif" title="/睡" id="/sh" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_10.gif" title="/大哭" id="/dk" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_11.gif" title="/尴尬" id="/gg" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_12.gif" title="/发怒" id="/dn" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_13.gif" title="/调皮" id="/tp" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_14.gif" title="/呲牙" id="/cy" style="cursor: pointer;"></li>
		<li><img src="<?php echo (SITE); ?>/Public/images/conference/expression_15.gif" title="/惊讶" id="/jy" style="cursor: pointer;"></li>
		</ul>
		<div class="comment_main">
			   <form onsubmit="return false;">
			   <textarea class="comment_foot" id="footlable" name="content"></textarea>
			  <p>网友评论仅供其表达个人看法，并不表明战略网同意其观点或证实其描述。</p>
			   <div class="comment_sub">
                                 <input class="tj_button" src="<?php echo (SITE); ?>/Public/images/conference/shout_tj.jpg" onclick="conference_submit('comment_foot', '-7_<?php echo ($info["id"]); ?>_2');" type="image">
			   	<label>&nbsp;匿名</label><input class="niming" name="anonymous" value="1" type="checkbox">
			  </div>
			  <div class="clear"></div>
			   </form>
		</div>
	</div>

</div>
<div class="clear"></div>
<div class="Blank"></div>
<?php if($oldlist != ''): ?><div id="main">
	<div class="past_events">
		<h1><span class="fright"><a href="<?php echo ($iisssite); ?>/spec/index-274/1.html" target="_blank">更多>></a></span><a></a></h1>
		<div class="past_events_m">
		       <?php if(is_array($oldlist)): foreach($oldlist as $key=>$vo): ?><p>
					<a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["subject"]); ?>" target="_blank"><img src="<?php echo (ATTPATH); ?>/<?php echo ($vo["pic"]); ?>" /></a>
					<a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["subject"]); ?>" target="_blank"><?php echo (mb_substr($vo["shortsubject"],0,14,'utf8')); ?></a>
				</p><?php endforeach; endif; ?>
		</div>
	</div>
</div><?php endif; ?>
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
<script type="text/javascript">var vjAcc = '860010-00402';</script><script type="text/javascript" src="http://analysis.chinaiiss.com/script/a.js"></script>
</body>
</html>