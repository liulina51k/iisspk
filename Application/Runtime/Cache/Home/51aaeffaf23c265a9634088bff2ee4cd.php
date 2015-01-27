<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="">
<meta name="keywords" content="">
<title>辩论pk台_战略网</title>
<link href="/Public/Style/basic.css" rel="stylesheet" type="text/css">
<link href="/Public/Style/pk.css" rel="stylesheet" type="text/css">
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
  <h1>中国是否该大规模抛售美国国债？</h1><h2>所有评论仅代表网友意见，战略网保持中立    2013-5-20</h2>
  <div class="top_bg_list"></div>
  <div class="main_l_list">
  <!--左侧选项卡-->
    <div class="tabs">
        <span class="topsBtnDiv">
            <ul>
            <li><a href="#" class="current"><span>正方评论</span></a></li>
            <li><a href="#"><span>反方评论</span></a></li>
            </ul>
        </span>
      <div>
      <ul>
          <li><h2><em>1024楼</em>四川省电信111 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi"><a href="#" class=" zf share_a" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
          <li>
            <h2><em>1024楼</em>四川省电信111 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi zf"><a href="#" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
          <li>
            <h2><em>1024楼</em>四川省电信111 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><h3><p>引用：太不靠谱</p><p>中国最强大，我们会各种强烈谴责，别的国家比得了吗</p>
</h3><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi zf"><a href="#" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
          <li><h2><em>1024楼</em>四川省电信111 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi zf"><a href="#" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
          <li>
            <h2><em>1024楼</em>四川省电信111 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi zf"><a href="#" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
        </ul>
        <ul class="pages_fy_list"><li class="pages_list"><span class="cur">1</span><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_1.html">2</a><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_2.html">3</a><span style="border:0px solid;">...</span><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_16.html">17</a><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_17.html">18</a><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_1.html">下一页</a><span class="jump_page"><p class="fl">至</p> <input class="int_jump" id="int_jump" type="text"> 页</span><a href="###" target="_self" id="jump" onclick="page('mutipage');">跳转</a><input id="pagecount" value="18" type="hidden"><input id="pageUrl" value="http://mil.chinaiiss.com/html/20136/1/a5f14c.html" type="hidden"><input id="style" value="mutipage" type="hidden"></li></ul>        
      </div>
      <div>
        <ul>
          <li><h2><em>1024楼</em>四川省电信222 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi zf"><a href="#" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
          <li>
            <h2><em>1024楼</em>四川省电信222 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi zf"><a href="#" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
          <li>
            <h2><em>1024楼</em>四川省电信222 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi zf"><a href="#" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
          <li>
            <h2><em>1024楼</em>四川省电信222 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi zf"><a href="#" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
          <li>
            <h2><em>1024楼</em>四川省电信222 <a class="name">战略网网友</a> 2013-06-19 09:02:47</h2><p>台独在岛内还是有一定市场的。统一，不能太乐观，要考虑的方方面面太多。在马英九任期内的统一还是无法想象的，从台日"渔业协定"到未定的台菲"渔业协定"</p>
				<dl>
                  <dd><span class="fright"><span class="zhichi"><a href="#" >支持</a></span><span class="zhichi_num"><strong>123</strong>票</span><span class="zhichi"><a href="#" >反对</a></span><span class="zhichi_num"><strong class="blue">123</strong>票</span><span class="zhichi hf"><a href="#" >回复此评论</a></span> <span class="zhichi zf"><a href="#" >转发</a></span></span>
                  </dd>
				</dl>
          </li>
        </ul>
        <ul class="pages_fy_list"><li class="pages_list"><span class="cur">1</span><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_1.html">2</a><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_2.html">3</a><span style="border:0px solid;">...</span><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_16.html">17</a><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_17.html">18</a><a href="http://mil.chinaiiss.com/html/20136/1/a5f14c_1.html">下一页</a><span class="jump_page"><p class="fl">至</p> <input class="int_jump" id="int_jump" type="text"> 页</span><a href="###" target="_self" id="jump" onclick="page('mutipage');">跳转</a><input id="pagecount" value="18" type="hidden"><input id="pageUrl" value="http://mil.chinaiiss.com/html/20136/1/a5f14c.html" type="hidden"><input id="style" value="mutipage" type="hidden"></li></ul>        
      </div>
    </div>
  </div>
  <div class="main_r_list">
    <div class="block_list">
      <span class="fright"><img src="images/pk_h1bg_r.jpg"></span><a class="wqht"></a>
      </div>
      <div class="pk_new_list">
      <ul>
        <li><a href="http://www.chinaiiss.com/pk/index/433" title="中国是否会从美国手中接过中东领导权？">中国是否会从美国手中接过中东领导权？</a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list">18836 / 30336</div>
            <div class="pk_jdt_a_list"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:30%"></span><span class="fanfang" style="width:70%"></span></div>
        </div>
        </li>
        <li><a href="http://www.chinaiiss.com/pk/index/433" title="中国是否会从美国手中接过中东领导权？">中国是否会从美国手中接过中东领导权？</a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list">18836 / 30336</div>
            <div class="pk_jdt_a_list"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:30%"></span><span class="fanfang" style="width:70%"></span></div>
        </div>
        </li>
        <li><a href="http://www.chinaiiss.com/pk/index/433" title="中国是否会从美国手中接过中东领导权？">中国是否会从美国手中接过中东领导权？</a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list">18836 / 30336</div>
            <div class="pk_jdt_a_list"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:30%"></span><span class="fanfang" style="width:70%"></span></div>
        </div>
        </li>
        <li><a href="http://www.chinaiiss.com/pk/index/433" title="中国是否会从美国手中接过中东领导权？">中国是否会从美国手中接过中东领导权？</a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list">18836 / 30336</div>
            <div class="pk_jdt_a_list"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:30%"></span><span class="fanfang" style="width:70%"></span></div>
        </div>
        </li>
        <li><a href="http://www.chinaiiss.com/pk/index/433" title="中国是否会从美国手中接过中东领导权？">中国是否会从美国手中接过中东领导权？</a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list">18836 / 30336</div>
            <div class="pk_jdt_a_list"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:30%"></span><span class="fanfang" style="width:70%"></span></div>
        </div>
        </li>
        <li><a href="http://www.chinaiiss.com/pk/index/433" title="中国是否会从美国手中接过中东领导权？">中国是否会从美国手中接过中东领导权？</a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list">18836 / 30336</div>
            <div class="pk_jdt_a_list"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:30%"></span><span class="fanfang" style="width:70%"></span></div>
        </div>
        </li>
        <li><a href="http://www.chinaiiss.com/pk/index/433" title="中国是否会从美国手中接过中东领导权？">中国是否会从美国手中接过中东领导权？</a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list">18836 / 30336</div>
            <div class="pk_jdt_a_list"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:30%"></span><span class="fanfang" style="width:70%"></span></div>
        </div>
        </li>
        <li><a href="http://www.chinaiiss.com/pk/index/433" title="中国是否会从美国手中接过中东领导权？">中国是否会从美国手中接过中东领导权？</a>
        <div class="pk_jdt_list">
            <div class="pk_jdt_num_list">18836 / 30336</div>
            <div class="pk_jdt_a_list"><a href="#" class="zczhengfang"></a><a href="#" class="zcfanfang"></a></div>
            <div class="pk_jdt_nr_list"><span class="zhengfang" style="width:30%"></span><span class="fanfang" style="width:70%"></span></div>
        </div>
        </li>
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
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fcd0a687f19db4e63c481a5b03c59f4e3' type='text/javascript'%3E%3C/script%3E"));


$('.return_top').live('click',function(){
	$("html, body").animate({ scrollTop: 0 }, 'fast');
});	

</script><script src="js/h.js" type="text/javascript"></script><script type="text/javascript">BAIDU_CLB_SLOT_ID = "253313";</script> 
<script type="text/javascript" src="js/o.js"></script><script charset="utf-8" src="js/ecom"></script> 
<script type="text/javascript">var vjAcc = '860010-00401';</script><script type="text/javascript" src="js/a.js"></script><img src="images/a.gif" style="visibility:hidden;position:absolute;left:0px;top:0px;z-index:-1" height="1" width="1">
</body>
</html>