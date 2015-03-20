<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="zh-CN">
<title>全球议事厅申请做主持人在线投稿_战略网</title>
<link href="<?php echo (SITE); ?>/Public/style/basic.v1.4.css" rel="stylesheet" type="text/css">
<link href="<?php echo (SITE); ?>/Public/style/add.css" rel="stylesheet" type="text/css">
<link href="<?php echo (SITE); ?>/Public/style/thickbox.css" rel="stylesheet" type="text/css" />
<link href="<?php echo (SITE); ?>/Public/style/improm.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.comm.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/thickbox.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/impromptu.min.js"></script>
<script type="text/javascript">
var passVerify = 0;
var editor;
$(function(){
	var i=0;
	$("#subjectlen").text(30-$("#subject").val().length);
	$("#summarylen").text(160-$("#summary").val().length);
	//获取相关表单内容的长度
	$("#subject").keyup(function(){
		var subval = document.getElementById('subject').value;
		$("#stip").hide();
		$("#stip2").show();
		$("#subjectlen").text(30-subval.length);
	});
	$("#summary").keyup(function(){
		var subval = document.getElementById('summary').value;
		$("#mtip").hide();
		$("#mtip2").show();
		$("#summarylen").text(160-subval.length);
	});

	$('#verifycode').change(function(){
	    var paramArray = {param1:'verify',code_str:encodeURI($('#verifycode').val()),"do":'send'};
		$.get(siteurl+'/do.php',paramArray,function(data){
			if(1==data){
				$("img[name='check']").attr({src:siteurl+'/images/send/check_right.gif',id:'1'});
				passVerify = 1;
				$("#codetip").hide();
			}else{
				$("img[name='check']").attr('src',siteurl+'/images/send/check_error.gif');
				passVerify = 0;
				$("#codetip").show();
			}
		});
	});

	//加载编辑器
	editor=$('#message').xheditor({tools:'Separator,Bold,Italic,Underline,Strikethrough,SelectAll,Removeformat,Separator,Align,Separator,Link,Separator,Source',skin:'default'});

});

function checkform(){
    var subject = document.getElementById('subject').value;
	if(subject.length<1){
		$("#subject").focus();
		$("#stip").show();
		$("#stip2").hide();
		return false;
	}
	var summary = document.getElementById('summary').value;
	if(summary.length<1){
		$("#summary").focus();
		$("#mtip").show();
		$("#mtip2").hide();
		return false;
	}
	if($('#verifycode').val()==''){
		//$.prompt('请填写验证码');
		$("#codetip").html('请填写验证码');
		$("#codetip").show();
		$('#verifycode').focus();
		return false;
	}else{alert(passVerify);
        if(passVerify<1){
		
			$("#yz").css("display","inline");
			$("#verifycode").val("");
			$('#verifycode').focus();
			//$.prompt('验证码错误');
			$("#codetip").html('验证码错误');
			$("#codetip").show();
			return false;
		}
	}
	var content = editor.getSource();
	
	if(content==''){
		$("#ctip").show();
		return false;
	}else{
		$("#ctip").hide();
	}
	
	/*
	if(id!=1){
		$.prompt('验证码错误');
        $('#verifycode').focus();
		return false;
	}
	*/

}
function getverify(){
	$('#imgquestion').attr('src', siteurl+'/send/getverify/'+rand());
}
</script>
</head>
<body>
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="<?php echo (SITE); ?>/Public/images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="<?php echo (SITE); ?>/Public/images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <?php if($pkinfo["id"] > 223): ?><div class="top_banner"><img src="<?php echo (ATTPATH); ?>/<?php echo ($pkinfo["imgurl"]); ?>" alt="<?php echo ($pkinfo["title"]); ?>"></div><?php endif; ?>
</div>
<div class="Blank"></div>
<div id="zcr">
	<div class="zcr_left">
	<div class="zcr_LOGO"><img src="<?php echo (SITE); ?>/Public/images/conference/n/yst_LOGO.jpg" width="140" height="41" /></div>
	<h1>全球议事厅申请做主持人在线投稿</h1>
	<form id="form2" name="form2" method="post" action="" onsubmit="return checkform();">
	<input type="hidden" name="formhash" value="<?php echo ($formhash); ?>" /><input value='<?php echo ($other["uid"]); ?>' type='hidden' id='uid'>
        <div class="zcr_form">
			<dl>
				<dt><img src="<?php echo (SITE); ?>/Public/images/conference/n/zcrsq_top.jpg"/>文章标题：</dt>
				<dd><input name="art[subject]" type="text" id="subject" class="input1" value="" maxlength="30" /></dd>
				<dd id="stip" class="t1 hide">请输入文章标题</dd>
				<dd id="stip2" class="t2">您还可以输入<strong id="subjectlen">30</strong>个汉字</dd>
			</dl>
			<dl>
				<dt>文章作者：</dt>
				<dd><?php echo ($other["username"]); ?></dd>
            </dl>
            <dl>
				<dt><img src="<?php echo (SITE); ?>/Public/images/conference/n/zcrsq_top.jpg"/>主持人观点：</dt>
				<dd><input name="art[summary]" id="summary" type="text" maxlength="160" class="input1" /></dd>
				<dd id="mtip" class="t1 hide">请输入主持人观点</dd>
				<dd id="mtip2" class="t2">您还可以输入<strong id="summarylen">160</strong>个汉字</dd>
            </dl>
			<dl class="bjq">
				<dt><img src="<?php echo (SITE); ?>/Public/images/conference/n/zcrsq_top.jpg"/>观点阐述：</dt>
				<dd class="t3">
				<div class="input4_right"><?php echo ($other["editor"]); ?></div>
				</dd>
				<dd class="t7 hide" id="ctip">请输入观点阐述</dd>
			</dl>
            <dl class="t_h">
				<dt>验证码：</dt>
				<dd class="t4"><img id="imgquestion" width="100" height="25" src="<?php echo (IISSSITE); ?>/send/getverify" title="请输入问题答案" /></dd>
				<dd class="t5"><input id="verifycode" name="verifycode" type="text" class="input2" /></dd>
				<dd class="t6"><a href="javascript:getverify();" >看不清 换一个</a> <img src="<?php echo (SITE); ?>/Public/images/send/check_default.gif" name="check" id='0' style="width:16px;height:16px;" /></dd>
				<dd class="t6 t1 hide" id="codetip">请输入验证码</dd>
            </dl>
            <dl class="tj">
				<dt class="tj_dt"><input name="sendsubmit" value=" " class="button" type="submit"></dt>
				<dd class="tj_dd"><input type="checkbox" name="sendblog" id="blog" class='checkbox'/></dd>
				<dd class="tj_dd_zi"><label for="blog">同时关联到战略博客</label></dd>
            </dl>
        </div>
      </form>
          
    </div>
    <div class="zcr_right">
    <h2><img src="<?php echo (SITE); ?>/Public/images/conference/n/tab_ico2.jpg" width="18" height="18" />申请做主持人须知：</h2>
    <p class="font14">尊敬的战略网网友您好：非常感谢您对议事厅的支持！</p>
    <p>1、全球议事厅是战略网的明星产品，做客全球议事厅，可使您以民间战略家的视角，与网友一同讨论国际风云变幻、解读最热国际事件、破解国家发展棋局。做客议事厅，能使您的观点最大限度传播，与众多名家同台讨论，是您博客的完美延伸。</p>
	<p>2、全球议事厅每逢周一15：00、周五10：00出品。出品前主持人需提供一篇针对当前军事、时政热点问题的观点性文章，2000字以内。截稿时间：周一出品的截稿最晚为周一上午；周五出品的截稿最晚为周四下午。</p><p>3、出品后，主持人需在议事厅中与网友互动、与网友进行观点的碰撞、对网友的提问进行解答、对自己的观点进行补充说明等。</p><P>4、报名、咨询请联系QQ：852635155 或发邮件至<img src="<?php echo (SITE); ?>/Public/images/conference/n/email.jpg" alt="邮箱"/>因议事厅文章需要人工审核，请想报名的朋友尽量提前报名以方便安排。</P><P>5、报名者应为战略网博客博主，非博主请在主持前注册战略网博客账户。</P>
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
</body>
</html>