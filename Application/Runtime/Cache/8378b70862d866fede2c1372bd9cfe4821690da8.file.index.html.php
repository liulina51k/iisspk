<?php /* Smarty version Smarty-3.1.6, created on 2015-01-27 16:03:54
         compiled from "./Application/Home/View\Index\index.html" */ ?>
<?php /*%%SmartyHeaderCode:2615254c7466a8583b7-44060327%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8378b70862d866fede2c1372bd9cfe4821690da8' => 
    array (
      0 => './Application/Home/View\\Index\\index.html',
      1 => 1422345832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2615254c7466a8583b7-44060327',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.6',
  'unifunc' => 'content_54c7466ad59f8',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54c7466ad59f8')) {function content_54c7466ad59f8($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="">
<meta name="keywords" content="">
<title>辩论pk台_战略网</title>
<link href="__PUBLIC__/Style/basic.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/Style/pk.css" rel="stylesheet" type="text/css">
<script src="js/logAdvanced.js" async="" charset="utf-8"></script>
<script src="js/log.js" async="" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/function.js"></script>
<script type="text/javascript" src="js/function_004.js"></script>
<script type="text/javascript" src="js/function_003.js"></script>
<script type="text/javascript" src="js/function_002.js"></script>
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

</script>
</head>
<body>
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="__PUBLIC__/Images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="__PUBLIC__/Images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <div class="top_banner"><img src="__PUBLIC__/Images/111421_645.jpg" alt="中俄是否已建立新的反美轴心？"></div>
</div>
<div style="margin:8px 0px 8px 0px;"> 
  <div style="display:none">-</div><a href="#"><img src="__PUBLIC__/Images/ad.jpg" width="960" height="60" /></a></div>
<div id="main">
  <div class="main_l">
    <div class="block">
      <h1><span class="fright"><img src="__PUBLIC__/Images/pk_h1bg_r.jpg"></span><a class="bjzl"></a></h1>
      <p>　　若没有俄罗斯和中国的合作，泄密者爱德华·斯诺登上月就不可能乘飞机从香港前往莫斯科。在斯诺登事件中，这两个国家的行为表明，它们的态度
        日益坚定，愿意在损害美国利益的情况下采取行动。除了保护斯诺登之外，中国与俄罗斯对叙利亚的政策让联合国安理会瘫痪了两年，阻止了国际联合行动。中国对
        美国企业的黑客入侵以及俄罗斯对邻国的网络入侵也引起了华盛顿的担忧。为增强合作的新潜力，中国目前正在与俄罗斯举行其有史以来规模最大的海上联合军演。
        [<a class="blue" href="http://mil.chinaiiss.com/html/20137/8/a6049c.html" target="_blank">详细背景资料</a>] </p>
    </div>
  </div>
  <div class="main_r">
    <div class="block">
      <h1><span class="fright"><img src="__PUBLIC__/Images/pk_h1bg_r.jpg"></span><span class="fright"><a href="http://www.chinaiiss.com/pkt/plist/1">更多&gt;&gt;</a></span><a class="wqht"></a></h1>
      <ul>
        <li><a href="http://www.chinaiiss.com/pk/index/434" title="中东格局面临重新洗牌，中国是否应主动介入？">中东格局面临重新洗牌，中国是否应主动介入？</a></li>
        <li><a href="http://www.chinaiiss.com/pk/index/433" title="中国是否会从美国手中接过中东领导权？">中国是否会从美国手中接过中东领导权？</a></li>
        <li><a href="http://www.chinaiiss.com/pk/index/432" title="中国是否将成为比美国更好的超级大国？">中国是否将成为比美国更好的超级大国？</a></li>
        <li><a href="http://www.chinaiiss.com/pk/index/431" title="中国以德报怨是否能换取菲越不折腾？">中国以德报怨是否能换取菲越不折腾？</a></li>
        <li><a href="http://www.chinaiiss.com/pk/index/430" title="中国是否应该将斯诺登引渡回美国？">中国是否应该将斯诺登引渡回美国？</a></li>
        <li><a href="http://www.chinaiiss.com/pk/index/429" title="中国对斯诺登事件是否应该积极出手干预？">中国对斯诺登事件是否应该积极出手干预？</a></li>
        <li><a href="http://www.chinaiiss.com/pk/index/428" title="吴伯雄访问大陆是否在为“习马会”铺路？">吴伯雄访问大陆是否在为“习马会”铺路？</a></li>
        <li><a href="http://www.chinaiiss.com/pk/index/427" title="中美所谓新型大国关系是否包含“G2”构想？">中美所谓新型大国关系是否包含“G2”构想？</a></li>
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
        <h1>正方:中俄都面对美巨大的战略压力 抱团应对有效合理</h1>
        <form>
          <input onclick="pkvote(435, 'agree');return false;" src="__PUBLIC__/Images/pk_yes_botton.jpg" type="image">
          <span>[<a class="red" id="agree435">5155</a> 票]</span>
        </form>
        <p>俄罗斯和中国似乎已经认定，要更好地推进自身利益，必须灭灭华盛顿的威风。随着这种共同利益感的形成，俄罗斯与中国日益紧密的合作会对美国构成严重的风险。 </p>
      </div>
    </div>
    <div class="main_right">
      <div class="pk_no">
        <h1>反方:中俄并不是亲密无间 部分合作并不是结盟的全部</h1>
        <form>
          <input onclick="pkvote(435, 'oppose');" src="__PUBLIC__/Images/pk_no_botton.jpg" type="image">
          <span>[<a class="blue" id="oppose435">1379</a> 票]</span>
        </form>
        <p>中国和俄罗斯仍然因互不信任的历史、相互冲突的经济利益以及对中国的领土野心的巨大担忧。中国对朝鲜的担忧胜过俄罗斯，而莫斯科在叙利亚的赌注比北京大。</p>
      </div>
    </div>
    <div class="clear"></div>
    <div class="pk_rule"><a href="http://www.chinaiiss.com/pkt/help/1" target="_blank">PK规则说明&gt;&gt;</a></div>
  </div>
  <div class="pk_vs_pic"> <img src="__PUBLIC__/Images/pk_red.gif" class="fleft" height="80" width="65">
    <div class="vleft" style="padding-left:626px;"> <img src="__PUBLIC__/Images/pk_tank.jpg" height="50" width="160"> </div>
    <img src="__PUBLIC__/Images/pk_blue.gif" class="fright" height="80" width="65"> </div>
  <div class="clear"></div>
</div>
<div id="main">
  <div class="comment_bg">
    <div class="com_login">
      <p class="fright">(评论<a class="red" id="comment_total">42</a>条 <a class="red">1953</a>名网友参与)</p>
      <div id="com_login">
        <form onsubmit="return false;">
          用户名
          <input class="text" name="username" type="text">
          密 码
          <input value="" class="text" name="password" type="password">
          <input name="loginuser" onclick="pklogin();" src="__PUBLIC__/Images/login_bot.jpg" type="image">
          <input name="reguser" src="__PUBLIC__/Images/register_bot.jpg" type="image">
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
          <input src="__PUBLIC__/Images/pk_texttj.jpg" onclick="checkform('comment_submityes');comments_submit('comment_submityes', '-1_435');setTimeout('getCommentTotal(435)',5000);" type="image">
        </form>
      </div>
    </div>
    <div class="main_right">
      <div class="comment" id="comment_submitno">
        <h1><a class="zcff"></a></h1>
        <form onsubmit="return false;">
          <textarea name="content" onfocus="delinput($(this), '内容:');">内容:</textarea>
          <input src="__PUBLIC__/Images/pk_texttj.jpg" onclick="checkform('comment_submitno');comments_submit('comment_submitno', '-1_435');setTimeout('getCommentTotal(435)',5000);" type="image">
        </form>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div style="padding:8px 0px 8px 0px;"> 
  <div style="display:none">-</div>
<a href="#"><img src="__PUBLIC__/Images/ad.jpg" width="960" height="60" /></a></div>
<div id="main">
  <div class="main_left">
    <div class="hot_red">
      <h1><a></a></h1>
      <div class="review" id="cmmtop_756783">
        <dl>
          <dt><em>2013-7-08 11:54:12</em><a href="#">没当上将军</a></dt>
          <dd>
            <p>这世界只要有美国存在，中俄两国就必须存小异求大同，联合对抗美国的战略压力就是两国高于一切的最大共同利益。</p>
          </dd>
          <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
            <br class="clear">
          </dd>
        </dl>
      </div>
      <div class="review_more"><a class="blueonline" href="http://www.chinaiiss.com/pkt/app/435">查看全部评论&gt;&gt;</a></div>
    </div>
    <div class="review_bk">
      <div id="">
        <div class="review">
          <dl>
            <dt><em>2013-07-09 08:47:23</em><a class="grayfont">47楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>目前美国佬独大，必然会是中 俄PK美！今后中国佬独大，必然会是俄 美PK中！将来俄国佬独大，必然会是中 美PK俄！轮回嘿嘿！</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-09 00:47:19</em><a class="grayfont">42楼</a><a target="_blank">中年男子</a></dt>
            <dd>
              <p>我坚持世界统一</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-09 00:45:10</em><a class="grayfont">41楼</a><a target="_blank">中年男子</a></dt>
            <dd>
              <p>为何不实行世界统一管理更好的为人民服务 建造美好未来</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-09 00:42:48</em><a class="grayfont">40楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>美国现在是我们最大敌人。</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 23:36:23</em><a class="grayfont">39楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>把俄中的“实用主义”推广到全球！</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 23:31:53</em><a class="grayfont">38楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>新建一条链接世界的互联星空网（排除美国）</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 23:28:51</em><a class="grayfont">37楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>新建一条链接世界金融结算的光纤！（排除美国）</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 23:25:17</em><a class="grayfont">36楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>不用美元！世界各国贸易，货币互换！</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 23:18:16</em><a class="grayfont">35楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>美国和加拿大 墨西哥也有大片的领土争议！可以挑动一下。</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 23:13:01</em><a class="grayfont">34楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>俄中军火公司也可以合资联营。</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 23:07:44</em><a class="grayfont">33楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>美洲地区，中东地区是武器的大市场，俄中可以大胆挺进！</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 23:03:23</em><a class="grayfont">32楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>俄中向美洲，中东卖先进的武器，比打口水仗好！实在！管用！</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 22:51:44</em><a class="grayfont">31楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>对付美国的霸道，俄中可以向美洲，中东卖先进的武器！</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 22:04:05</em><a class="grayfont">30楼</a><a target="_blank">战略网网友</a></dt>
            <dd>
              <p>中俄只有联手才不会被美帝牵着鼻子走！！中俄现在都有共同的利益</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 21:40:50</em><a class="grayfont">29楼</a><a target="_blank">丘比特之箭</a></dt>
            <dd>
              <div class="bg">引用：默贾宸<br>
                <span>
                <p>首先，美国可能不存在吗？这是不可能的假设。再者，美国与中俄之间有很多利益交织，假如按您说的美国不存在了，就意味着中俄的国际市场小了些，这对中俄经济的发展不见得是好事，而一个国家的经济是最重要的，从某种意义上来说，甚至比军事还重要。</p>
                </span></div>
            </dd>
            <dd>
              <p>中国爆发战火，习近平在那里天天泡彭丽媛，我们浴血拼杀没有我们什么事情，太不公平了，起码把习明泽给我们玩玩。</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="#">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
      </div>
      <div class="review_more"><a class="blueonline" href="http://www.chinaiiss.com/pkt/app/435">查看全部评论&gt;&gt;</a></div>
    </div>
  </div>
  <div class="main_right">
    <div class="hot_blue">
      <h1><a></a></h1>
      <div class="review">
        <dl>
          <dt><em>2013-7-08 15:06:50</em><a href="#">战略网网友</a></dt>
          <dd>
            <p>“三足鼎”力才是合理的世界模式。美国佬想独大，中、俄是不甘心滴----谁想独大，余必与之抗衡！</p>
          </dd>
          <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
            <br class="clear">
          </dd>
        </dl>
      </div>
      <div class="review_more"><a class="blueonline" href="http://www.chinaiiss.com/pkt/opp/435">查看全部评论&gt;&gt;</a></div>
    </div>
    <div class="reviews_bk">
      <div id="">
        <div class="review">
          <dl>
            <dt><em>2013-07-08 20:45:52</em><a class="grayfont">22楼</a><a target="_blank" class="blue">中医奇才孙思邈</a></dt>
            <dd>
              <div class="bg">引用：战略网网友<br>
                <span>
                <p>“三足鼎”力才是合理的世界模式。美国佬想独大，中、俄是不甘心滴----谁想独大，余必与之抗衡！</p>
                </span></div>
            </dd>
            <dd>
              <p>三足鼎立不成立，俄国如果和中国好，毁灭的预言会遍布俄国，俄国必然与中国翻脸与美国结盟。</p>
            </dd>
            <dd class="bot_bit">
            <ul>
              <li><a class="support" href="javascript:regoodtop(756783);">支持</a><span class="colorred"><b id="topgood756783">51</b>票</span></li>
              <li><a class="support" href="#">反对</a><span class="colorblue"><b id="topbad756783">4</b>票</span></li>
              <li><a class="blackonline" href="#">回复此评论</a></li>
              <li class="zf"><a id="appyes" class="blackonline share_a" style="cursor:pointer" name="transmit_button2" href="#">转发</a></li>
            </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 20:44:10</em><a class="grayfont">21楼</a><a target="_blank" class="blue">中医奇才孙思邈</a></dt>
            <dd>
              <div class="bg">引用：战略网网友<br>
                <span>
                <p>狗日的俄罗斯，也不是好鸟我们一定要保持警惕如果美国衰落了如果中俄要有一个出来当老大到时一定翻脸。谁要讲俄国是好东西，谁就是猪。</p>
                </span></div>
            </dd>
            <dd>
              <p>中俄联合，战略也不平衡，美国也会做大一家独大，俄国与中国翻脸是必然，中国地大震动，到处都是死亡和废墟，中国经济半年内后退六十年。</p>
            </dd>
            <dd class="bot_bit">
              <ul>
                <li><a class="support" href="javascript:regood(757076);">支持</a><span class="colorred"><b id="good757076">1</b>&nbsp;票</span></li>
                <li><a class="support" href="javascript:rebad(757076);">反对</a><span class="colorblue"><b id="bad757076">0</b>&nbsp;票</span></li>
                <li><a class="blackonline" href="javascript:recommenttop(757076);" id="cmm_757076">回复此评论</a></li>
                <li class="zf"><a id="cmmm_757076" class="blackonline share_a" style="cursor:pointer;" name="transmit_button" href="#">转发</a></li>
              </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 20:41:09</em><a class="grayfont">20楼</a><a target="_blank" class="blue">中医奇才孙思邈</a></dt>
            <dd>
              <div class="bg">引用：战略网网友<br>
                <span>
                <p>狗日的俄罗斯，也不是好鸟我们一定要保持警惕如果美国衰落了如果中俄要有一个出来当老大到时一定翻脸。谁要讲俄国是好东西，谁就是猪。</p>
                </span></div>
            </dd>
            <dd>
              <p>聪明！美俄必然联合，俄国必然与中国翻脸，中国全国地大震动，周边国家猛攻中国，德意日等国从海陆千万精锐进攻中国，北京城被炸平一半，俄国千万大军杀气腾腾派往俄远东，俄国倾国核武库剑指东北三省，十五国精锐猛攻新疆，中国领空领海消失。</p>
            </dd>
            <dd class="bot_bit">
              <ul>
                <li><a class="support" href="javascript:regood(757072);">支持</a><span class="colorred"><b id="good757072">1</b>&nbsp;票</span></li>
                <li><a class="support" href="javascript:rebad(757072);">反对</a><span class="colorblue"><b id="bad757072">0</b>&nbsp;票</span></li>
                <li><a class="blackonline" href="javascript:recommenttop(757072);" id="cmm_757072">回复此评论</a></li>
                <li class="zf"><a id="cmmm_757072" class="blackonline share_a" style="cursor:pointer;" name="transmit_button" href="#">转发</a></li>
              </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 20:34:04</em><a class="grayfont">19楼</a><a target="_blank" class="blue">战略网网友</a></dt>
            <dd>
              <p>狗日的俄罗斯，也不是好鸟我们一定要保持警惕如果美国衰落了如果中俄要有一个出来当老大到时一定翻脸。谁要讲俄国是好东西，谁就是猪。</p>
            </dd>
            <dd class="bot_bit">
              <ul>
                <li><a class="support" href="javascript:regood(757066);">支持</a><span class="colorred"><b id="good757066">1</b>&nbsp;票</span></li>
                <li><a class="support" href="javascript:rebad(757066);">反对</a><span class="colorblue"><b id="bad757066">0</b>&nbsp;票</span></li>
                <li><a class="blackonline" href="javascript:recommenttop(757066);" id="cmm_757066">回复此评论</a></li>
                <li class="zf"><a id="cmmm_757066" class="blackonline share_a" style="cursor:pointer;" name="transmit_button" href="#">转发</a></li>
              </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 18:45:06</em><a class="grayfont">12楼</a><a target="_blank" class="blue">默贾宸</a></dt>
            <dd>
              <p>大
                国之间的关系根本不可能是单一性的，因为各有众多庞大的利益集团，利益交织在一起，即彼此需要又彼此有矛盾，如果说是“抱团”是不客观的，是带有情绪化
                的，这是片面地只从军事一方面来看待国际关系的结果。俄罗斯与美国也有利益需要之处，而且不亚于与中国的利益需要，只不过俄罗斯与美国有军事竞争关系，看
                似与美国关系很差似的。</p>
            </dd>
            <dd class="bot_bit">
              <ul>
                <li><a class="support" href="javascript:regood(757003);">支持</a><span class="colorred"><b id="good757003">1</b>&nbsp;票</span></li>
                <li><a class="support" href="javascript:rebad(757003);">反对</a><span class="colorblue"><b id="bad757003">0</b>&nbsp;票</span></li>
                <li><a class="blackonline" href="javascript:recommenttop(757003);" id="cmm_757003">回复此评论</a></li>
                <li class="zf"><a id="cmmm_757003" class="blackonline share_a" style="cursor:pointer;" name="transmit_button" href="#">转发</a></li>
              </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 18:19:07</em><a class="grayfont">11楼</a><a target="_blank" class="blue">战略网网友</a></dt>
            <dd>
              <p>每个国家都有自己的打算；中国和美国好俄罗斯受不了；中俄好美国也受不了；俄美好中国更加顶不了；中国要协调好俄美关系。</p>
            </dd>
            <dd class="bot_bit">
              <ul>
                <li><a class="support" href="javascript:regood(756997);">支持</a><span class="colorred"><b id="good756997">2</b>&nbsp;票</span></li>
                <li><a class="support" href="javascript:rebad(756997);">反对</a><span class="colorblue"><b id="bad756997">0</b>&nbsp;票</span></li>
                <li><a class="blackonline" href="javascript:recommenttop(756997);" id="cmm_756997">回复此评论</a></li>
                <li class="zf"><a id="cmmm_756997" class="blackonline share_a" style="cursor:pointer;" name="transmit_button" href="#">转发</a></li>
              </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 18:12:07</em><a class="grayfont">10楼</a><a target="_blank" class="blue">战略网网友</a></dt>
            <dd>
              <p>毛熊的强悍不是你我和美国可以阻挡的，这就是战斗民族的体现！！</p>
            </dd>
            <dd class="bot_bit">
              <ul>
                <li><a class="support" href="javascript:regood(756993);">支持</a><span class="colorred"><b id="good756993">0</b>&nbsp;票</span></li>
                <li><a class="support" href="javascript:rebad(756993);">反对</a><span class="colorblue"><b id="bad756993">0</b>&nbsp;票</span></li>
                <li><a class="blackonline" href="javascript:recommenttop(756993);" id="cmm_756993">回复此评论</a></li>
                <li class="zf"><a id="cmmm_756993" class="blackonline share_a" style="cursor:pointer;" name="transmit_button" href="#">转发</a></li>
              </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 16:54:14</em><a class="grayfont">8楼</a><a target="_blank" class="blue">孰悟涅槃</a></dt>
            <dd>
              <p>俄罗斯在共同利益面前与中国合作，但对中国担忧和对美国是一样的。也在一定的程度上遏制着中国的发展与强大。</p>
            </dd>
            <dd class="bot_bit">
              <ul>
                <li><a class="support" href="javascript:regood(756953);">支持</a><span class="colorred"><b id="good756953">7</b>&nbsp;票</span></li>
                <li><a class="support" href="javascript:rebad(756953);">反对</a><span class="colorblue"><b id="bad756953">0</b>&nbsp;票</span></li>
                <li><a class="blackonline" href="javascript:recommenttop(756953);" id="cmm_756953">回复此评论</a></li>
                <li class="zf"><a id="cmmm_756953" class="blackonline share_a" style="cursor:pointer;" name="transmit_button" href="#">转发</a></li>
              </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 16:48:14</em><a class="grayfont">7楼</a><a target="_blank" class="blue">战略网网友</a></dt>
            <dd>
              <p>中俄两国之间的合作是一种潜在的契约关系，这种关系存在的理由中，美国这个超级大国占了很大原因。而且中俄之间也是冲突不断的。</p>
            </dd>
            <dd class="bot_bit">
              <ul>
                <li><a class="support" href="javascript:regood(756951);">支持</a><span class="colorred"><b id="good756951">4</b>&nbsp;票</span></li>
                <li><a class="support" href="javascript:rebad(756951);">反对</a><span class="colorblue"><b id="bad756951">0</b>&nbsp;票</span></li>
                <li><a class="blackonline" href="javascript:recommenttop(756951);" id="cmm_756951">回复此评论</a></li>
                <li class="zf"><a id="cmmm_756951" class="blackonline share_a" style="cursor:pointer;" name="transmit_button" href="#">转发</a></li>
              </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
        <div class="review">
          <dl>
            <dt><em>2013-07-08 15:06:50</em><a class="grayfont">5楼</a><a target="_blank" class="blue">战略网网友</a></dt>
            <dd>
              <p>“三足鼎”力才是合理的世界模式。美国佬想独大，中、俄是不甘心滴----谁想独大，余必与之抗衡！</p>
            </dd>
            <dd class="bot_bit">
              <ul>
                <li><a class="support" href="javascript:regood(756863);">支持</a><span class="colorred"><b id="good756863">27</b>&nbsp;票</span></li>
                <li><a class="support" href="javascript:rebad(756863);">反对</a><span class="colorblue"><b id="bad756863">2</b>&nbsp;票</span></li>
                <li><a class="blackonline" href="javascript:recommenttop(756863);" id="cmm_756863">回复此评论</a></li>
                <li class="zf"><a id="cmmm_756863" class="blackonline share_a" style="cursor:pointer;" name="transmit_button" href="#">转发</a></li>
              </ul>
              <br class="clear">
            </dd>
          </dl>
        </div>
      </div>
      <div class="review_more"><a class="blueonline" href="http://www.chinaiiss.com/pkt/opp/435">查看全部评论&gt;&gt;</a></div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<div style="margin:8px 0px 8px 0px;"> 
  <script type="text/javascript">/*PK台3通栏960*60，创建于2011-11-29*/ var cpro_id = 'u694612';</script><script src="__PUBLIC__/Images/c.js" type="text/javascript"></script><script type="text/javascript" charset="utf-8" src="__PUBLIC__/Images/ecom_002"></script>
  <div style="display:none">-</div>
<a href="#"><img src="__PUBLIC__/Images/ad.jpg" width="960" height="60" /></a></div>
<div id="main">
  <div class="block">
    <h1><span class="fright"><img src="__PUBLIC__/Images/pk_h1bg_r.jpg"></span><span class="fright"><a href="http://www.chinaiiss.com/pkt/plist/1" target="_blank">更多&gt;&gt;</a></span><a class="wqht"></a></h1>
  </div>
  <div class="main_left">
  <div class="pk_new">
      <h1>第333期:<a href="http://www.chinaiiss.com/pk/index/434" title="中东格局面临重新洗牌，中国是否应主动介入？" target="_blank">中东格局面临重新洗牌，中国是否应主动介入？</a></h1>
    <div class="pk_more">
      <div class="pkm_view">
        <div class="pkm_yes">
          <p title="中国唯有主动出击，才能在中东扩大自身影响力">正方：中国唯有主动出击，才能在中东扩大自身影响力</p>
         </div>
        <div class="pkm_no">
          <p title="中东是战争的火药桶 一旦进入很难会全身而退">反方：中东是战争的火药桶 一旦进入很难会全身而退</p>
          </div>
        <div class="clear"></div>
        <div class="pk_jdt">
            <div class="pk_jdt_num">18836 / 30336</div>
            <div class="pk_jdt_a"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr"><span class="zhengfang" style="width:30%"></span><span class="fanfang" style="width:70%"></span></div>
        </div>
      </div>
    </div>
    </div>
  <div class="pk_new">
      <h1>话题:<a href="http://www.chinaiiss.com/pk/index/434" title="中东格局面临重新洗牌，中国是否应主动介入？" target="_blank">中东格局面临重新洗牌，中国是否应主动介入？</a></h1>
    <div class="pk_more">
      <div class="pkm_view">
        <div class="pkm_yes">
          <p title="中国唯有主动出击，才能在中东扩大自身影响力">正方：中国唯有主动出击，才能在中东扩大自身影响力</p>
         </div>
        <div class="pkm_no">
          <p title="中东是战争的火药桶 一旦进入很难会全身而退">反方：中东是战争的火药桶 一旦进入很难会全身而退</p>
          </div>
        <div class="clear"></div>
        <div class="pk_jdt">
            <div class="pk_jdt_num">18836 / 30336</div>
            <div class="pk_jdt_a"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr"><span class="zhengfang"></span><span class="fanfang"></span></div>
        </div>
      </div>
    </div>
    </div>
  <div class="pk_new">
      <h1>话题:<a href="http://www.chinaiiss.com/pk/index/434" title="中东格局面临重新洗牌，中国是否应主动介入？" target="_blank">中东格局面临重新洗牌，中国是否应主动介入？</a></h1>
    <div class="pk_more">
      <div class="pkm_view">
        <div class="pkm_yes">
          <p title="中国唯有主动出击，才能在中东扩大自身影响力">正方：中国唯有主动出击，才能在中东扩大自身影响力</p>
         </div>
        <div class="pkm_no">
          <p title="中东是战争的火药桶 一旦进入很难会全身而退">反方：中东是战争的火药桶 一旦进入很难会全身而退</p>
          </div>
        <div class="clear"></div>
        <div class="pk_jdt">
            <div class="pk_jdt_num">18836 / 30336</div>
            <div class="pk_jdt_a"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr"><span class="zhengfang"></span><span class="fanfang"></span></div>
        </div>
      </div>
    </div>
    </div>
  </div>
  <div class="main_right">
  <div class="pk_new">
      <h1>话题:<a href="http://www.chinaiiss.com/pk/index/434" title="中东格局面临重新洗牌，中国是否应主动介入？" target="_blank">中东格局面临重新洗牌，中国是否应主动介入？</a></h1>
    <div class="pk_more">
      <div class="pkm_view">
        <div class="pkm_yes">
          <p title="中国唯有主动出击，才能在中东扩大自身影响力">正方：中国唯有主动出击，才能在中东扩大自身影响力</p>
         </div>
        <div class="pkm_no">
          <p title="中东是战争的火药桶 一旦进入很难会全身而退">反方：中东是战争的火药桶 一旦进入很难会全身而退</p>
          </div>
        <div class="clear"></div>
        <div class="pk_jdt">
            <div class="pk_jdt_num">18836 / 30336</div>
            <div class="pk_jdt_a"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr"><span class="zhengfang"></span><span class="fanfang"></span></div>
        </div>
      </div>
    </div>
    </div>
  <div class="pk_new">
      <h1>话题:<a href="http://www.chinaiiss.com/pk/index/434" title="中东格局面临重新洗牌，中国是否应主动介入？" target="_blank">中东格局面临重新洗牌，中国是否应主动介入？</a></h1>
    <div class="pk_more">
      <div class="pkm_view">
        <div class="pkm_yes">
          <p title="中国唯有主动出击，才能在中东扩大自身影响力">正方：中国唯有主动出击，才能在中东扩大自身影响力</p>
         </div>
        <div class="pkm_no">
          <p title="中东是战争的火药桶 一旦进入很难会全身而退">反方：中东是战争的火药桶 一旦进入很难会全身而退</p>
          </div>
        <div class="clear"></div>
        <div class="pk_jdt">
            <div class="pk_jdt_num">18836 / 30336</div>
            <div class="pk_jdt_a"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr"><span class="zhengfang"></span><span class="fanfang"></span></div>
        </div>
      </div>
    </div>
    </div>
  <div class="pk_new">
      <h1>话题:<a href="http://www.chinaiiss.com/pk/index/434" title="中东格局面临重新洗牌，中国是否应主动介入？" target="_blank">中东格局面临重新洗牌，中国是否应主动介入？</a></h1>
    <div class="pk_more">
      <div class="pkm_view">
        <div class="pkm_yes">
          <p title="中国唯有主动出击，才能在中东扩大自身影响力">正方：中国唯有主动出击，才能在中东扩大自身影响力</p>
         </div>
        <div class="pkm_no">
          <p title="中东是战争的火药桶 一旦进入很难会全身而退">反方：中东是战争的火药桶 一旦进入很难会全身而退</p>
          </div>
        <div class="clear"></div>
        <div class="pk_jdt">
            <div class="pk_jdt_num">18836 / 30336</div>
            <div class="pk_jdt_a"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr"><span class="zhengfang"></span><span class="fanfang"></span></div>
        </div>
      </div>
    </div>
    </div>
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
    Copyright ©2002-<script>document.write(new Date().getFullYear());</script>2013 www.chinaiiss.com All Rights Reserved. 京ICP证110164号 京ICP备11008173号<script src="js/stat.php" language="JavaScript"></script><script src="js/cnzz_core.php" charset="utf-8" type="text/javascript"></script><a href="http://www.cnzz.com/stat/website.php?web_id=215831" target="_blank" title="站长统计"><img src="__PUBLIC__/Images/pic.gif" border="0" hspace="0" vspace="0"></a></div>
</div>
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fcd0a687f19db4e63c481a5b03c59f4e3' type='text/javascript'%3E%3C/script%3E"));


$('.return_top').live('click',function(){
	$("html, body").animate({ scrollTop: 0 }, 'fast');
});	
</script>
</body>
</html><?php }} ?>