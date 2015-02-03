//取得评论统计
function comments_count(id, param){
	 if(param == '') return false;
     doAjaxGetJSON(id,'comment','comment_count',param);
}
//取得评论参与总数2012-7-27
function comments_usercount(id, param){
     if(param == '') return false;
     doAjaxGetJSON(id,'comment','comment_usercount',param);
}
//取得评论统计2 2012-7-27
function comments_count2(id, param){
     if(param == '') return false;
     doAjaxGetJSON(id,'comment','comment_count',param);
}
//取得评论参与总数2   2012-7-27
function comments_usercount2(id, param){
     if(param == '') return false;
     doAjaxGetJSON(id,'comment','comment_usercount',param);
}
//提交评论
function comments_submit(id, param, type, length){
	var ev = getEvent();
	slength = (typeof(length)=="undefined") ? 200 : length;
	
	if($("#"+id+" textarea[name='content']").val()==''){
		alert('请添写留言内容');return false;
	}
	var categoryid=param.split('_');
	if(categoryid[0] == '-1'){
		
	}else{
		if($("#"+id+" input[name='username']").length){
			$("#"+id+" input[name='username']").focus();
			return false;
		}
	}
	
	//pk
	
	var ispk='';
	/*
	var categoryid=param.split('_');
	if(categoryid[0] == '-1'){
		//判断是否已登录
		if(id=='comment_submityes' || id=='comment_submitno'){
			var username=$('.log_in .redcolor').html();
			if(username == null){
				alert('请登陆后评论!');
				$("#com_login input[name='username']").focus();
				return false;
			}
		}
		ispk=1;
	}
	
	//2012-10-23添加 start
	if(!ispk){
		if($("#boxbody input[name='username']").length){
			$("#boxbody input[name='username']").focus();
			return false;
		}
	}
	*/
	//2012-10-23添加 ent
	var content = $("#"+id+" textarea[name='content']").val();
	content = content.replace(/<[^>].*?>/g,"");
	// var content = preg_replace(['&', '<', '>', '"', '_'], ['&amp;', '&lt;', '&gt;', '&quot;', ' '], content);
	param += '_'+content;
	
	if($("#"+id+" textarea[name='content']").val().length>slength){
		alert('内容请少于'+slength+'字。');return false;
	}
	
	var anonymous = 0;
	if($("#"+id+" input[name='anonymous']").attr("checked")){
         anonymous = $("#"+id+" input[name='anonymous']").val();
    }
	param += '_'+anonymous;
    //类型，支持者还是反对都
	var commenttype = $("#"+id+" input[name='commenttype']").val() || 0;
	param += '_'+commenttype;
	//引用ID
	var quoteid = $("#"+id+" input[name='quoteid']").val() || 0;
	param += '_'+quoteid;

	//获得标题
	var subject = $("#"+id+" input[name='subject']").val() || '';
	param += '_'+subject;
    //2012-1-29
    type=(typeof(type)==undefined)?'':type;
    param += '_'+type;
	//判断pk
	if(ispk){
		param += '_'+id;
	}

	param = encodeURI(param);
	if(ev){
		var oEvent = ev.srcElement || ev.target;
		$(oEvent).attr({"disabled":"disabled"});
	}
    $.ajaxSettings.async = true;
   
    doAjaxProJSON(id, 'comment', 'comment_submit', param, rand());
        return true;
	
}

//提取评论列表
function comments_list(id, param){
	doAjaxProJSON(id, 'comment', 'comment_list', param);
}

//提取评论反对列表
function comments_tlist(id, param){
	doAjaxProJSON(id, 'comment', 'comment_tlist', param);
}

//PK 评论正方列表
function comments_pklist(id, param){
	doAjaxProJSON(id, 'comment', 'comment_pklist', param);
}
//PK 评论反方列表
function comments_pktlist(id, param){
	doAjaxProJSON(id, 'comment', 'comment_pktlist', param);
}

//回复此评论
function recomment(id){
	off = $("#cmm_"+id).offset();
	$.get(siteurl+"/do.php", {inajax:1,ado:'comment', ac:'comment_reap', parameter: $("#categoryid").val()+'_'+id}, function(data){
																															
		    apalert('回复评论', checkReturn(data), off.top, off.left+200, 360);
		});

}

//评论支持点击
function regood(id){
	var url = siteurl+"/do.php?inajax=1&ado=comment&ac=comment_vote&parameter="+$("#categoryid").val()+"_"+id+"_good&json=1&jsoncallback=?";
	$.getJSON(url,function(json){  
		$('#good'+id).text(parseInt( $('#good'+id).text())+1);
	});
}

//评论反对点击
function rebad(id){
	var url = siteurl+"/do.php?inajax=1&ado=comment&ac=comment_vote&parameter="+$("#categoryid").val()+"_"+id+"_bad&json=1&jsoncallback=?";
	$.getJSON(url,function(json){  
		$('#bad'+id).text(parseInt( $('#bad'+id).text())+1);
	});
}
//弹窗提示或评论窗口
function comment(bar, apbody, topval, leftval, w, h){
	if($("#box").length<1){
	   $("body").append('<div id="box" class="comment_box"><div class="comment_bk" id="boxbody"></div></div>');
	}
    var winwidth = $(window).width();
	if(leftval>=(winwidth-w-30)){
	   leftval = winwidth-w-30;
	}
	$("#box").css({top:topval+"px",left:leftval+"px",width:w+"px"});	
	$("#boxbody").html(apbody);

}//弹窗内容更换
function box_apbody(txt){
	   if($("#box").length>0){
		   $("#boxbody").html(txt);
		   $("#box").fadeOut("20000", function(){box_apclose();});
	   }else{
		   alert(txt);
	   }
}

//关闭窗口
function box_apclose(){
   $("#box").remove();
}
//新版评论页面回复框 2012-1-29
function respcomment(id){
	off = $("#cmm_"+id).offset();

    url =siteurl+'/do.php?inajax=1&do=comment&ac=comment_responsion&parameter='+$("#categoryid").val()+'_'+id+'&json=1&jsoncallback=?';
	
	$.getJSON(url, function(json) {
		var data=checkReturn(json[0]);
		$("#cmm_"+id).parents('dd').append(data);
		topval=off.top+17.5;
		leftval=off.left-507.5;
		var winwidth = $(window).width();
		if(leftval>=(winwidth-30)){
			leftval = winwidth-30;
		}
		$("#hf").css({top:topval+"px",left:leftval+"px"});
	});
	/*
	$.get(siteurl+"/do.php", {inajax:1,ado:'comment', ac:'comment_responsion', parameter: $("#categoryid").val()+'_'+id}, function(data){
		   data=checkReturn(data);
           $("#cmm_"+id).parents('dd').append(data);
           topval=off.top+17.5;
           leftval=off.left-507.5;
           var winwidth = $(window).width();
          if(leftval>=(winwidth-30)){
             leftval = winwidth-30;
          }
           $("#hf").css({top:topval+"px",left:leftval+"px"});
		});
*/
}
//弹窗内容更换  2012-1-29
function apbody(txt){
	   if($("#hf").length>0){
		   $("#hf").html(txt);
		   $("#hf").fadeOut("20000", function(){$("#hf").remove();});
	   }else{
		   alert(txt);
	   }
}
//2012-1-29
function transmit(url,title,id) {

	//获取被点击的标签的ID 整个页面是唯一的!
	var obj = $('#'+id);
	//判断是左边的点击还是右边的点击  左边差19PX正好  右边差504PX正好
	var objleft = parseInt(obj.position().left);
    var offsetleft = parseInt(objleft)- parseInt(202) ;

    date='<span style="position:absolute; height:0px; width:0px;"><div class="share" style="height:190px"><h6 ><a style="cursor:pointer">转发</a></h6><ul class="noticeList"><li><a id="ttt" href="http://v.t.qq.com/share/share.php?url='+url+'&site='+siteurl+'&pic=&title='+title+'" title="分享到腾讯微博" target="_blank"><img src="'+siteurl+'/images/QQ_LOGO.jpg" />腾讯微博</a></li><li><a href="http://v.t.sina.com.cn/share/share.php?title='+title+'&url='+siteurl+'" target="_blank" title="分享到新浪微博" target="_blank"><img src="'+siteurl+'/images/sina_LOGO.jpg" />新浪微博</a></li><li><a href="http://t.163.com/article/user/checkLogin.do?source='+siteurl+'&info='+title+' '+siteurl+'&images=" title="分享到网易微博" target="_blank"><img src="'+siteurl+'/images/WY_LOGO.jpg" />网易微博</a></li><li> <a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+siteurl+'&desc='+title+'" title="分享到QQ空间" target="_blank"><img src="'+siteurl+'/images/QQ_KLOGO.jpg" />QQ空间</a></li><li><a href="http://share.renren.com/share/buttonshare.do?link='+siteurl+'&title='+title+'" title="分享到人人网" target="_blank"><img src="'+siteurl+'/images/RR_LOGO.jpg" />人人网</a></li><li><a href="http://www.kaixin001.com/rest/records.php?content='+title+'&url='+siteurl+'&style=11&pic=" title="分享到开心网" target="_blank"><img src="'+siteurl+'/images/KX_LOGO.jpg" />开心网</a></li><li><a href="http://www.douban.com/recommend/?url='+siteurl+'&title='+title+'" title="分享到豆瓣网" target="_blank"><img src="'+siteurl+'/images/DB_LOGO.jpg" />豆瓣网</a></li><li><a href="http://t.sohu.com/third/post.jsp?&url='+siteurl+'&title='+title+'" title="分享到搜狐微博" target="_blank"><img src="'+siteurl+'/images/SOHO_LOGO.jpg" />搜狐微博</a></li></ul></div></span>';

      //把通过JSON获取到的转发标签 插入到相对应的ID标签的下面
      obj.parents('div:eq(0)').append(date);
      //设置弹出框跟随点击按钮的位置改变而改变
      obj.parents('dl').siblings('span').find('div').css('left',offsetleft+'px');
      //并显示相关的转发微博标签
      obj.parents('dl').siblings('span').find('div').show();
      //当点击转发微博标签上面的 转发按钮时  执行CLICK事件
      obj.parents('dl').siblings('span').find('a:first-child').click(function(){
      //把相应的微博标签清空掉
      obj.parents('dl').siblings('span').remove();
      });
}
//2012-1-29
function login(){
      var name = $("#username").val();
      if(name == ''){
        $("#username").focus();
        return;
      }
      var pass = $("#password").val();
      if(pass == ''){
        $("#password").focus();
        return;
      }
      islastlogin=$("#islastlogin").attr('checked')?1:0;
      parameter=encodeURI(name+'_'+pass+'_'+islastlogin);
      url =siteurl+'/do.php?inajax=1&do=comment&ac=login&parameter='+parameter+'&json=1&jsoncallback=?';
      $.getJSON(url, function(json) {
            if(json.data){
                var url = location.href.split("#");
                location.href=url[0];
            }else
            {
                alert(json.message);
            }
      });
}
//2012-1-29
function slide(id){
      $('#slide a').removeClass('onselect');
      $('#'+id).addClass('onselect');
      $('.pl_top .ranking').hide();
      $('#'+id+'s').show();
}
//获取评论人数 2012-4-16
function comments_PeopleCount(id, param){
	 if(param == '') return false;
     doAjaxGetJSON(id,'comment','comments_PeopleCount',param);
}

//2013-2-6修改军事评论的转发列表位置
function transmit1(url,title,id) {
	//获取被点击的标签的ID 整个页面是唯一的!
	var obj = $('#'+id);
	//判断是左边的点击还是右边的点击  左边差19PX正好  右边差504PX正好
	var objleft = parseInt(obj.position().left);
    var offsetleft = parseInt(objleft)- parseInt(202) ;
    date='<span style="position:absolute; height:0px; width:0px;"><div class="share" style="height:190px"><h6 ><a style="cursor:pointer">转发</a></h6><ul class="noticeList"><li><a id="ttt" href="http://v.t.qq.com/share/share.php?url='+url+'&site='+siteurl+'&pic=&title='+title+'" title="分享到腾讯微博" target="_blank"><img src="'+siteurl+'/images/QQ_LOGO.jpg" />腾讯微博</a></li><li><a href="http://v.t.sina.com.cn/share/share.php?title='+title+'&url='+siteurl+'" target="_blank" title="分享到新浪微博" target="_blank"><img src="'+siteurl+'/images/sina_LOGO.jpg" />新浪微博</a></li><li><a href="http://t.163.com/article/user/checkLogin.do?source='+siteurl+'&info='+title+' '+siteurl+'&images=" title="分享到网易微博" target="_blank"><img src="'+siteurl+'/images/WY_LOGO.jpg" />网易微博</a></li><li> <a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+siteurl+'&desc='+title+'" title="分享到QQ空间" target="_blank"><img src="'+siteurl+'/images/QQ_KLOGO.jpg" />QQ空间</a></li><li><a href="http://share.renren.com/share/buttonshare.do?link='+siteurl+'&title='+title+'" title="分享到人人网" target="_blank"><img src="'+siteurl+'/images/RR_LOGO.jpg" />人人网</a></li><li><a href="http://www.kaixin001.com/rest/records.php?content='+title+'&url='+siteurl+'&style=11&pic=" title="分享到开心网" target="_blank"><img src="'+siteurl+'/images/KX_LOGO.jpg" />开心网</a></li><li><a href="http://www.douban.com/recommend/?url='+siteurl+'&title='+title+'" title="分享到豆瓣网" target="_blank"><img src="'+siteurl+'/images/DB_LOGO.jpg" />豆瓣网</a></li><li><a href="http://t.sohu.com/third/post.jsp?&url='+siteurl+'&title='+title+'" title="分享到搜狐微博" target="_blank"><img src="'+siteurl+'/images/SOHO_LOGO.jpg" />搜狐微博</a></li></ul></div></span>';
      //把通过JSON获取到的转发标签 插入到相对应的ID标签的下面
      obj.parents('div:eq(0)').append(date);
      //并显示相关的转发微博标签
      obj.parents('dl').siblings('span').find('div').show();
      //当点击转发微博标签上面的 转发按钮时  执行CLICK事件
      obj.parents('dl').siblings('span').find('a:first-child').click(function(){
      //把相应的微博标签清空掉
      obj.parents('dl').siblings('span').remove();
      });
}


// 新版评论框提交评论（2013-08-21）
function newcomments_submit(id, type, length) {
	var ev = getEvent();
	slength = (typeof (length) == "undefined") ? 200 : length;
	var uname = $('.plk_foot_l_name').html();
	if (uname == null) {
	    var username = $("#"+id+" input[name='username']").val();
		if(username == ''){
			showmessage(1,'请填写用户名');
			return false;
		}
		var password = $("#"+id+" input[name='password']").val();
		if(password == ''){
			showmessage(1,'请填写密码');
			return false;
		}
		var username= encodeURIComponent(username);
		var password = encodeURIComponent(password);
		var paramlogin = username+'_'+password;
	}
	if ($("#" + id + " textarea[name='content']").val() == '') {
		showmessage(2,'评论内容不能为空');return false;
	}
	var param = $("#" + id + " input[name='c_i']").val();
	param += '_'+ preg_replace([ '&', '<', '>', '"', '_' ], [ '&amp;', '&lt;','&gt;', '&quot;', ' ' ], $("#" + id + " textarea[name='content']").val());
	if ($("#" + id + " textarea[name='content']").val().length > slength) {
		showmessage(2,'内容请少于' + slength + '字。');return false;
	}

	var anonymous = 0;
	if ($("#" + id + " input[name='anonymous']").attr("checked")) {
		anonymous = $("#" + id + " input[name='anonymous']").val();
	}
	param += '_' + anonymous;

	type = (typeof (type) == undefined) ? '' : type;
	param += '_' + type;

	var quoteid = $("#" + id + " input[name='quoteid']").val() || 0;
	param += '_' + quoteid;
	
	//判断提交评论的页面
	var subtype = $("#" + id + " input[name='subtype']").val() || 0;
	param += '_' + subtype;
	
	param +='_'+paramlogin;
	
	param = encodeURI(param);

	if (ev) {
		var oEvent = ev.srcElement || ev.target;
		$(oEvent).attr({
			"disabled" : "disabled"
		});
	}
	$.ajaxSettings.async = true;
	doAjaxProJSON(id, 'comment', 'newcomment_submit', param, rand());
	return true;
}


// 新版评论框提交评论（2013-08-29）
function comments_submit_plk(id, type, length) {
	var ev = getEvent();
	slength = (typeof (length) == "undefined") ? 200 : length;
	var uname = $('.plk_foot_l_name').html();
	if (uname == null) {
	    var username = $("#"+id+" input[name='username']").val();
		if(username == ''){
			showmessage(1,'请填写用户名');
			return false;
		}
		var password = $("#"+id+" input[name='password']").val();
		if(password == ''){
			showmessage(1,'请填写密码');
			return false;
		}
		var username= encodeURIComponent(username);
		var password = encodeURIComponent(password);
		var paramlogin = username+'_'+password;
	}
	
	if ($("#" + id + " textarea[name='content']").val() == '') {
		showmessage(2,'评论内容不能为空');return false;
	}
//	var param = $("#" + id + " input[name='c_i']").val();
	var categoryid = $("#" + id + " input[name='categoryid']").val();
	var infoid = $("#" + id + " input[name='specialid']").val();
	var param = categoryid+'_'+infoid;
	var content = $("#"+id+" textarea[name='content']").val();
	content = content.replace(/<[^>].*?>/g,"");
	param += '_'+content;
	
	if ($("#" + id + " textarea[name='content']").val().length > slength) {
		showmessage(2,'内容请少于' + slength + '字。');return false;
	}

	var anonymous = 0;
	if ($("#" + id + " input[name='anonymous']").attr("checked")) {
		anonymous = $("#" + id + " input[name='anonymous']").val();
	}
	param += '_' + anonymous;

	type = (typeof (type) == undefined) ? '' : type;
	param += '_' + type;

	var quoteid = $("#" + id + " input[name='quoteid']").val() || 0;
	param += '_' + quoteid;
	
	//判断提交评论的页面
	var subtype = $("#" + id + " input[name='subtype']").val() || 0;
	param += '_' + subtype;
	
	param +='_'+paramlogin;

	param = encodeURI(param);

	if (ev) {
		var oEvent = ev.srcElement || ev.target;
		$(oEvent).attr({
			"disabled" : "disabled"
		});
	}
	$.ajaxSettings.async = true;
	doAjaxProJSON(id, 'comment', 'newcomment_submit', param, rand());
	return true;
}


// 新版评论框提交评论（2013-08-29）
function pkcomments_submit(id, type, length) {
	var ev = getEvent();
	slength = (typeof (length) == "undefined") ? 200 : length;
	var uname = $('.plk_foot_l_name').html();
	if (uname == null) {
	    var username = $("#"+id+" input[name='username']").val();
		if(username == ''){
			showmessage(1,'请填写用户名');
			return false;
		}
		var password = $("#"+id+" input[name='password']").val();
		if(password == ''){
			showmessage(1,'请填写密码');
			return false;
		}
		var username= encodeURIComponent(username);
		var password = encodeURIComponent(password);
		var paramlogin = username+'_'+password;
	}
	
	if ($("#" + id + " textarea[name='content']").val() == '') {
		showmessage(2,'评论内容不能为空');return false;
	}
//	var param = $("#" + id + " input[name='c_i']").val();
	var categoryid = $("#" + id + " input[name='categoryid']").val();
	var infoid = $("#" + id + " input[name='specialid']").val();
	var param = categoryid+'_'+infoid;
	var content = $("#"+id+" textarea[name='content']").val();
	content = content.replace(/<[^>].*?>/g,"");
	param += '_'+content;
	
	if ($("#" + id + " textarea[name='content']").val().length > slength) {
		showmessage(2,'内容请少于' + slength + '字。');return false;
	}

	var anonymous = 0;
	if ($("#" + id + " input[name='anonymous']").attr("checked")) {
		anonymous = $("#" + id + " input[name='anonymous']").val();
	}
	param += '_' + anonymous;

	type = (typeof (type) == undefined) ? '' : type;
	param += '_' + type;

	var quoteid = $("#" + id + " input[name='quoteid']").val() || 0;
	param += '_' + quoteid;
	
	//判断提交评论的页面
	var subtype = $("#" + id + " input[name='subtype']").val() || 0;
	param += '_' + subtype;
	
	param +='_'+paramlogin;

	param = encodeURI(param);

	if (ev) {
		var oEvent = ev.srcElement || ev.target;
		$(oEvent).attr({
			"disabled" : "disabled"
		});
	}
	$.ajaxSettings.async = true;
	doAjaxProJSON(id, 'comment', 'newcomment_submit', param, rand());
	return true;
}