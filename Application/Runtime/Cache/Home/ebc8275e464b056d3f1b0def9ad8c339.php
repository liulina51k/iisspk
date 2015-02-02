<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="">
<meta name="keywords" content="">
<title>正方评论-<?php echo ($pknowinfo["title"]); ?>-辩论pk台-战略网</title>
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

	var url = siteurl+"/do.php?inajax=1&do=user&ac=pk_login&parameter="+username+"_"+password+"&json=1&jsoncallback=?";
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
//选项卡左侧
 var n
 $(function(){
     $(".tabs div:not(:first)").hide() //显示第一个div，隐藏其他div
     $(".topsBtnDiv li").click(function(){
	    n = $(this).index() //取得当前的序号
		$(this).children("a").addClass("current") // 所触发的tab变成当前状态
		$(this).siblings().children("a").removeClass("current") // 其他tab变成非当前状态

        $($(".tabs div")[n]).show()  //显示当前tab对应的div
		$($(".tabs div")[n]).siblings("div").hide()//隐藏其他div
	 })
 }); 
</script>
</head>
<body>
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="/Public/Images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="/Public/Images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <div class="top_banner"><img src="/Public/Images/111421_645.jpg" alt="中俄是否已建立新的反美轴心？"></div>
</div>
<div id="main_list">
  <h1><?php echo ($pknowinfo["title"]); ?></h1><h2>所有评论仅代表网友意见，战略网保持中立</h2>
  <div class="top_bg_list"></div>
  <div class="main_l_list">
  <!--左侧选项卡-->
    <div class="tabs">
        <span class="topsBtnDiv">
            <ul>
            <li><a href="<?php echo ($site); ?>/pkt/app/<?php echo ($pknowinfo["id"]); ?>" class="current"><span>正方评论</span></a></li>
            <li><a href="<?php echo ($site); ?>/pkt/opp/<?php echo ($pknowinfo["id"]); ?>"><span>反方评论</span></a></li>
            </ul>
        </span>
      <div>
      <ul>
          <?php if(is_array($goodcomm)): foreach($goodcomm as $key=>$vo): ?><li><h2><em><?php echo ($vo["floor"]); ?>楼</em><?php echo ($vo["postip"]); ?><a class="name"><?php echo ($vo["username"]); ?></a><?php echo ($vo["postdate"]); ?></h2><p><?php echo ($vo["content"]); ?></p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="javascript:regoodtop(<?php echo ($vo["id"]); ?>);" >支持</a></span><span class="zhichi_num"><strong id="topgood
<?php echo ($vo["id"]); ?>"><?php echo ($vo["good"]); ?></strong>票</span><span class="zhichi"><a href="javascript:rebadtop(<?php echo ($vo["id"]); ?>);" >反对</a></span><span class="zhichi_num"><strong class="blue" id="topbad
<?php echo ($vo["id"]); ?>"><?php echo ($vo["bad"]); ?></strong>票</span><span class="zhichi hf"><a href="javascript:recommenttop(<?php echo ($vo["id"]); ?>);" >回复此评论</a></span> <span class="zhichi"><a href="javascript:transmit_pk(<?php echo ($pknowinfo); ?>,'<?php echo (strip_tags($vo["content"])); ?>','cmmm_<?php echo ($vo["id"]); ?>');" class=" zf share_a" >转发</a></span></span>
                  </dd>
				</dl>
          </li><?php endforeach; endif; ?>
        </ul>
        <?php echo ($show); ?>     
      </div>
    </div>
  </div>
  <div class="main_r_list">
    <div class="block_list">
      <span class="fright"><img src="/Public/Images/pk_h1bg_r.jpg"></span><a class="wqht"></a>
      </div>
      <div class="pk_new_list">
      <ul>
        <?php if(is_array($pkoldlist)): foreach($pkoldlist as $k=>$vo): ?><li><a href="<?php echo ($vo["pkurl"]); ?>" title="<?php echo ($vo["title"]); ?>"><?php echo ($vo["title"]); ?></a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list"><b id="agree<?php echo ($vo["id"]); ?>"><?php echo ($vo["agreevote"]); ?></b> / <b 
id="oppose<?php echo ($vo["id"]); ?>"><?php echo ($vo["opposevote"]); ?></b></div>
            <div class="pk_jdt_a_list"><a class="zczhengfang"  onclick="pkvote(<?php echo ($vo["id"]); ?>, 
'agree');" style="cursor:pointer"></a><a class="zcfanfang" onclick="pkvote(<?php echo ($vo["id"]); ?>, 
'oppose');" style="cursor:pointer"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:
<?php echo ($vo["point_good"]); ?>%"></span><span class="fanfang" style="width:<?php echo ($vo["point_bad"]); ?>%"></span></div>
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
    Copyright ©2002-<script>document.write(new Date().getFullYear());</script>2013 www.chinaiiss.com All Rights Reserved. 京ICP证110164号 京ICP备11008173号<script src="js/stat.php" language="JavaScript"></script><script src="js/cnzz_core.php" charset="utf-8" type="text/javascript"></script><a href="http://www.cnzz.com/stat/website.php?web_id=215831" target="_blank" title="站长统计"><img src="/Public/Images/pic.gif" border="0" hspace="0" vspace="0"></a></div>
</div>
</body>
</html>