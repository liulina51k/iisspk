<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo (C("DB_CHARSET")); ?>">
<meta name="description" content="<?php echo ($context); ?>">
<meta name="keywords" content="<?php echo ($info["subject"]); ?>" />
<title><?php if($info["seosubject"] != ''): echo ($info["seosubject"]); else: echo ($info["subject"]); ?>_全球议事厅_<?php endif; ?>战略网</title>
<link href="<?php echo (SITE); ?>/Public/style/basic.css" rel="stylesheet" type="text/css">
<link href="<?php echo (SITE); ?>/Public/style/index.css?9" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.comm.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.conference2.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.vote.js"></script>
<script type="text/javascript" src="<?php echo (SITE); ?>/Public/js/function.login.js"></script>

<script type="text/javascript">
	var loginuser = '<?php echo ($info["loginuser"]); ?>';
	var authorid = '<?php echo ($other["authorid"]); ?>';
	var conference_title = "<?php echo ($info["subject"]); ?>";conference_id = "<?php echo ($info["id"]); ?>";conference_no = '<?php echo ($info["no"]); ?>';conference_topno = '<?php echo ($info["topno"]); ?>';
	var comment_param = '-7_'+conference_id+'_'+authorid+'-'+loginuser;
	conference_topno = conference_topno>0 ? '（特刊）' : '';
	var conference_subject = '全球议事厅第'+conference_no+'期'+conference_topno;
	
	function recommenttop(id, position, floor, first){
		var sid = id;
		if(floor==2) sid = first+'_'+id;
		if($(".hidden_"+sid).html() == ''){
			$.getJSON(siteurl+"/do.php?inajax=1&ado=conference2&ac=reply_box&parameter="+$("#categoryid").val()+'_'+conference_id+'_'+id+'_'+position+'_'+floor+'&jsoncallback=?',function(json){
				$("div[class^='hidden']").html('');
				$(".hidden_"+sid).append(json.data);
			});
		}else{
			$(".hidden_"+sid).html('');
		}
	}
	
	
	function box_apbody(txt){
		   if($("#box").length>0){
			   $("#boxbody").html(txt);
			   $("#box").fadeOut("20000", function(){box_apclose();});
		   }else{
			   alert(txt);
		   }
	}

	function copyLink(){
//		var idstr = 'click|conference_copy_'+conference_id+'$'+conference_subject+"搬救兵@@"+siteurl+'/conference/index/'+conference_id+"@@"+conference_title+'_搬救兵';
//		doRecord(idstr);
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
		$("#box_reply").attr('value',$("#box_reply").attr('value')+$("li [id=\""+id+"\"]").attr("id"));
	}
	
	function show_allcomment(param){
		doAjaxProJSON(param, 'conference2', 'get_comment', param);
	}
	//页面跳回顶部
	function toTop(){
		var totalHeight=$(window).height(),
		scrollHeight=$(document).scrollTop();
		
		if(scrollHeight >= totalHeight ){ 		
			$("#top").show(); 
			//IE6下的定位
			if (!window.XMLHttpRequest) {
				$("#xf").css("top",  totalHeight+scrollHeight-200);
			}
			//返回顶部
			$("#top").click(function(){
				$(this).hide();
				//跳到页面最上面
				$(window).scrollTop(0);
			});
		} else {
			$("#top").hide();
		}
	}
	function rightScroll(){
		if (document.all){
				var browser=navigator.appName; 
				var b_version=navigator.appVersion; 
				var version=b_version.split(";");
				
				var trim_Version=version[1].replace(/[ ]/g,""); 
				if(browser=="Microsoft Internet Explorer" && trim_Version=="MSIE6.0") {var llq="ie6"}else{var llq="ie"}
		}else{var llq="noie"}
		
		var obj=document.getElementById("main_r2");
		var scrollTop = document.body.scrollTop || document.documentElement.scrollTop || 0;
		var allH = document.body.scrollHeight-1200;
		//底部
		var scroH = document.documentElement.scrollTop+document.body.scrollTop;
		
		if(scroH >600 && scroH <allH){
			if (llq=="ie6") {obj.style.cssText="margin-top:-25px;position:absolute;top:"+scroH+"px";}
			else{
				obj.style.cssText="margin-top:10px;top:0px;position:fixed;";
			}
		}else if(scroH>allH){
			obj.style.cssText="margin-top:0px;position:absolute;top:"+allH+"px";
		}else{
			
			if (llq=="ie6") {obj.style.cssText="margin-top:0px;position:absolute;top:580px";}
			else{
				obj.style.cssText="margin-top:0px;position:absolute;";
			}
		}
	}
	window.onscroll = function(){
		var scrollTop = document.body.scrollTop || document.documentElement.scrollTop || 0;
		rightScroll();
		if(scrollTop>document.getElementById('m_left_plxf').offsetTop){
			$("#m_left_plxf_nr").addClass("pl_xfgd");
		}else{
			$("#m_left_plxf_nr").removeClass("pl_xfgd");
		} 
		 
		//对话框
		$("#m_left_plxf").hide();
		if(scrollTop>$("#test2").offset().top){
			$("#m_left_plxf").show();
		}
		toTop();
	}
	function showlist(type){
		if(type==1){
			$("#comment_guid").html('<span class="ckjhpl"><a href="###" onclick="showlist(2);">查看精华评论</a></span><span>最新评论</span>');
			$("#new_comment_list").html('');
			$("#mutipage1").html('');
			conference_list('new_comment_list', comment_param);
		}else{
			$("#comment_guid").html('<span class="ckjhpl"><a href="###" onclick="showlist(1);">查看最新评论</a></span><span>精华评论</span>');
			$("#new_comment_list").html('');
			$("#mutipage1").html('');
			conference_recommlist('new_comment_list', comment_param);
		}
	}
	
	$(function () {
		toTop();
		$("li [id^='/']").click(function () {
			ids=$(this).parents('.conference').attr('id');
			$("."+ids).attr('value',$("."+ids).attr('value')+$(this).attr("id"));
		});
		conference_list('new_comment_list', comment_param);
		//setInterval('rightScroll',1000);
		$(".pl_xfgd_textnr").click(function(){
			if($(".pl_xfgd_textnr").val()=='请输入评论内容'){
				$(".pl_xfgd_textnr").val('');
			}
		});
		$(".pl_xfgd_textnr").mouseout(function(){
			if($(".pl_xfgd_textnr").val()==''){
				$(".pl_xfgd_textnr").val('请输入评论内容');
			}
		});
		if($(".pl_xfgd_textnr").val()==''){
			$(".pl_xfgd_textnr").val('请输入评论内容');
		}
	});
</script>
</head>
<body>
<input id="categoryid" type="hidden" value="-7" />
<input id="txturl" type="hidden" value="<?php echo (SITE); ?>/conference/index/<?php echo ($info["id"]); ?>" />
<div class="top_head"> <a href="http://www.chinaiiss.com/"><img class="comm_logo" alt="战略网" src="<?php echo (SITE); ?>/Public/images/top_head_logo.jpg"></a> <a href="http://www.chinaiiss.com/pk/index/435"> <img class="comm_logo" alt="辩论PK台" src="<?php echo (SITE); ?>/Public/images/top_pk_logo.jpg"> </a>
  <p id="guid__"><a class="red" href="http://www.chinaiiss.com/" title="首页">首 页</a>|<a href="http://news.chinaiiss.com/" title="时政要闻">时政要闻</a>|<a href="http://mil.chinaiiss.com/" title="军事天地">军事天地</a>|<a href="http://observe.chinaiiss.com/" title="战略观察">战略观察</a>|<a href="http://grass.chinaiiss.com/" title="群英论见">群英论见</a>|<a href="http://history.chinaiiss.com/" title="历史长河">历史长河</a>|<a href="http://society.chinaiiss.com/" title="社会民生">社会民生</a>|<a href="http://world.chinaiiss.com/" title="世界博览">世界博览</a>|<a href="http://pic.chinaiiss.com/" title="图库">图 库</a>|<a href="http://blog.chinaiiss.com/" title="博客">博 客</a>|<a href="http://club.chinaiiss.com/" title="社区">社 区</a>|<a href="http://www.iissbbs.com/" title="论坛">论 坛</a>|<a href="http://book.chinaiiss.com/" title="读书">读书</a></p>
  <?php if($pkinfo["id"] > 223): ?><div class="top_banner"><img src="<?php echo (ATTPATH); ?>/<?php echo ($pkinfo["imgurl"]); ?>" alt="<?php echo ($pkinfo["title"]); ?>"></div><?php endif; ?>
</div>
<div class="banner_index">
	<div class="fanjiao">
		<img src="<?php echo (SITE); ?>/Public/images/conference/you-jiao.jpg" width="182" height="111" border="0" usemap="#Map" />
		<map name="Map" id="Map">
			<area shape="poly" coords="136,17,52,73,33,109,180,21" href="http://grass.chinaiiss.com/html/20141/22/wa124db.html" target="_blank" />
			<area shape="poly" coords="144,45,41,106,178,107,176,68" href="http://internet20.isc.org.cn/internet/vote.php" target="_blank" />
			<area shape="poly" coords="51,68,71,8,132,16,51,69,50,70" href="http://internet20.isc.org.cn/internet/vote.php" target="_blank" />
			<area shape="circle" coords="168,46,10" href="javascript:void(0);" onclick="$('.fanjiao').hide();" />
		</map>
	</div>
	<div class="banner_top"><span>逢周一、五出品&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第<strong id="qi"><?php echo ($info["no"]); ?></strong>期</span></div>
    <div class="banner_pic"><img src="<?php echo (ATTPATH); ?>/<?php echo ($info["banner"]); ?>" width="350" height="200" /></div>
	<div class="banner_zi">
        <h1><?php echo ($info["subject"]); ?></h1>
        <h2><?php echo ($info["shortsubject"]); ?></h2>       
    </div>
</div>
<div class="main">
<div class="main_left">
    <div class="tab"><span><img src="<?php echo (SITE); ?>/Public/images/conference/n/tab_ico.jpg"/>主持人观点</span></div>
    <div class="zcr"><p><?php echo ($info["starttext"]); ?></p><a href="<?php echo (SITE); ?>/conference/index/<?php echo ($info["id"]); ?>#flag">回复主持人观点</a></div>
    <div class="clear"></div>
    <div class="main_zw"><p><?php echo (nl2br($info["viewtext"])); ?></p></div>
    <div class="share">
	<!-- Baidu Button BEGIN -->
	<div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
	<span class="share_p">分享到：</span>
	<a href="#" title="分享到QQ空间" class="bds_qzone"></a>
	<a href="#" title="分享到新浪微博" class="bds_tsina"></a>
	<a href="#" title="分享到人人网" class="bds_renren"></a>
	<a href="#" title="分享到腾讯微博" class="bds_tqq"></a>
	<a href="#" title="分享到网易微博" class="bds_t163"></a>
	<a href="#" title="更多分享" class="bds_more"></a>
	<a class="shareCount"></a></div>
	<script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=707353" ></script>
	<script type="text/javascript" id="bdshell_js"></script>
	<script type="text/javascript">
		var bds_config={"snsKey":{'tsina':'1339831039','tqq':'','t163':'','tsohu':''}}
		document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
	</script>
	<!-- Baidu Button END -->
    </div>
    <div class="tab_02" id="flag"><span class="pl_num"><strong><?php echo ($other["commcount"]); ?></strong>条评论</span><span class="pl_num"><strong><?php echo ($other["count"]); ?></strong>人参与</span><span>我要评论</span></div>
	<!--我要评论-->    
	<div class="pl">
	   	<div class="pl_nr" id="comment_bottom">
			<form onsubmit="return false;" id="conference_login_reply_<?php echo ($infoid); ?>">
			<textarea name="content" id="artcomment" cols="" rows="" class="plk" placeholder="请输入评论内容"></textarea>
			<?php if($loginusername != ''): ?><div class="dl_warp" id="flogin">
				<div class="dl"><input name="username" type="text" class="dlk"  value="用户名" onblur="if($(this).val()=='')$(this).val('用户名');" placeholder="用户名" onfocus="enterSubmit($(this));if($(this).val()=='用户名')$(this).val('');"  buttonid="artcomlogin"><label class="passwordTips" name="passwordTips">密码</label><input name="password" type="password" class="dlk" onfocus="enterSubmit($(this));$('.passwordTips').html('');" onblur="if($(this).val()=='')$('.passwordTips').html('密码');"  buttonid="artcomlogin"></div>
				<div class="zc_ico"><span><a href="<?php echo (SITE); ?>/user/reg.html" target="_blank">注册</a></span><a href="###" onclick="out_login('sina')"><img src="<?php echo (SITE); ?>/Public/images/conference/n/sina.jpg"></a><a href="###" onclick="out_login('qq')"><img src="<?php echo (SITE); ?>/Public/images/conference/n/QQ.jpg"></a></div>
				<div class="fb"><span class="tj_button"><a href="###" onclick="return conference_submit('comment_bottom', '-7_<?php echo ($info["id"]); ?>_2');" id="artcomlogin">登录/发布</a></span><input name="anonymous" class="nm" type="checkbox"><span>匿名</span></div>
				<div class="clear"></div>
			</div>
			<?php else: ?>
			<div class="dl_warp2">
				<div class="plk_foot_l_02"><?php echo ($other["dayampm"]); ?>好：<span class="plk_foot_l_name"><?php echo ($other["loginusername"]); ?></span><a href="<?php echo ($userurl); ?>/home/" target="_blank">个人中心</a>|<a href="javascript:quit_quit();">退出</a></div>
				<div class="fb"><span class="tj_button"><a href="###" onclick="return conference_submit('comment_bottom', '-7_<?php echo ($info["id"]); ?>_2');">发布</a></span><input name="anonymous" value="1" class="nm" type="checkbox"><span>匿名</span></div>
				<div class="clear"></div>
			</div><?php endif; ?>       
			</form>
			<div class="clear"></div>
		</div>
	</div>
	<div id="m_left_plxf">
	<div class="" id="m_left_plxf_nr">
	<form onsubmit="return false;" id="comment_top">
	<div class="pl_xfgd_text"><input name="content" type="text" class="pl_xfgd_textnr" ></div>
	<div class="pl_xfgd_button"><input name="sendsubmit" value=" "  type="submit" class="pl_xfgd_buttonnr" onclick="return conference_submit('comment_top', '-7_<?php echo ($info["id"]); ?>_1');" /></div>
	</form>
	</div>
	<div class="clear"></div>
	</div>
    <div class="tab_02" id="test2"><span>最热评论</span></div>
	<div class="new_pl" id="gv_conference2_hotlist_-7_<?php echo ($info["id"]); ?>_<?php echo ($other["authorid"]); ?>-<?php echo ($other["loginuser"]); ?>"></div>
	<a name="acommentlist"></a>
	<!--最新评论/精华评论-->
    <div class="tab_02" id="comment_guid"><span class="ckjhpl"><a href="###" onclick="showlist(2);">查看精华评论</a></span><span>最新评论</span></div>
	<div class="new_pl" id="newcom"></div>
    <div class="new_pl" id="new_comment_list">
	
	</div>
	<div id="mutipage1"></div>
</div>

<div class="main_right">
<div class="Blank"></div>
    <div class="m_r_01">
        <div class="m_r_01_pic"><a href="<?php echo ($other["blogurl"]); ?>/<?php echo ($other["authordomain"]); ?>" target="_blank"><img src="<?php echo ($other["avatarpic"]); ?>" width="80" height="80" /></a></div>
        <div class="m_r_01_zi"><h2><?php echo ($other["author"]); ?></h2><h3>本期议事厅主持人</h3></div>
        <div class="clear"></div>
        <div class="m_r_01_p"><p><?php echo (nl2br(mb_substr($other["authorsummary"],0,108,'utf8'))); ?></p></div>
        <div class="zhuchiren"><a href="<?php echo (SITE); ?>/conference/add.html"><img src="<?php echo (SITE); ?>/Public/images/conference/n/zcr.jpg" width="140" height="30" alt="我要做主持人" /></a></div>
    </div>
    <div class="Blank"></div>
    <div id="main_r_w" style="height:570px; width:300px;">
    <div class="" id="main_r2" style="width:300px;">
        <div class="m_r_02">
            <h2>相关阅读</h2>
			<?php if(is_array($related)): foreach($related as $key=>$vo): if($key == 0): ?><div class="m_r_02_pic"><a href="<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["subject"]); ?>" target="_blank"><img src="<?php echo (ATTPATH); ?>/<?php echo ($vo["pic"]); ?>" width="285" height="207"/></a><div class="pic_title"><a href="<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["subject"]); ?>" target="_blank"><?php echo ($vo["subject"]); ?></a></div></div><?php endif; endforeach; endif; ?>
            <ul>
            <?php if(is_array($related)): foreach($related as $key=>$vo): if($key > 0): ?><li><a href="<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["subject"]); ?>" target="_blank"><?php echo (mb_substr($vo["subject"],0,22,'utf8')); ?></a></li><?php endif; endforeach; endif; ?>
            </ul>
        </div>
        <div class="Blank"></div>
        <div class="m_r_02" style="width:285px;">
            <h2>投票调查</h2>
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
    </div>
    </div>
</div>
<div class="clear"></div>
</div>
<!--往期回顾-->
<?php if($oldlist != ''): ?><div class="wqhg" id="wangqi">
    <div class="tab_02"><span class="more"><a href="<?php echo (SITE); ?>/spec/index-274/1.html" target="_blank">更多</a></span><span>往期回顾</span></div>
    <div class="wqhg_nr">
    <ul>
	<?php if(is_array($oldlist)): foreach($oldlist as $key=>$vo): ?><li>
    <div class="wqhg_nr_pic"><a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["subject"]); ?>" target="_blank"><img src="<?php echo (ATTPATH); ?>/<?php echo ($vo["pic"]); ?>" width="160" height="117" /></a></div>
    <h2><span>第<?php echo ($vo["no"]); ?>期</span></h2>
    <h3><a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["subject"]); ?>" target="_blank"><?php echo (mb_substr($vo["subject"],0,13,'utf8')); ?></a></h3>
    </li><?php endforeach; endif; ?>
    </ul>
    </div>
</div><?php endif; ?>
<!--//往期回顾结束-->
<div id="xf"><a href="#" title="返回顶部" id="top"></a></div><script>$("#top").hide();</script>
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