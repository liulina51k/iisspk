//评论列表
function conference_list(id, param){
	doAjaxProJSON(id, 'conference2', 'conference_list', param);
}
//最热评论
function conference2_hotlist(id, param){
	doAjaxProJSON(id, 'conference2', 'conference_hotlist', param);
}
//精华评论
function conference_recommlist(id, param){
	doAjaxProJSON(id, 'conference2', 'conference_recommlist', param);
}
//提交评论
function conference_submit(id, param){
	var content = '';
	if($("#"+id+" textarea[name='content']").val()){
		content = $("#"+id+" textarea[name='content']").val();
	}
	
	if($("#"+id+" input[name='content']").val()){
		content = $("#"+id+" input[name='content']").val();
	}
	if(content == '请输入评论内容'){
		$(".pl_xfgd_textnr").val('');
		alert('请添加评论内容');return false;
	}
	if(content==''){
		alert('请添加评论内容');return false;
	}
	if(content.length>1000){
		alert('内容请少于1000字');return false;
	}
	
	param += '_'+preg_replace(['&', '<', '>', '"', '_'], ['&amp;', '&lt;', '&gt;', '&quot;', ' '], content);
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

	//是否引用整楼
	var isquote = $("#"+id+" input[name='isquote']").val() || 1;
	param += '_'+isquote;

	param = encodeURI(param);
	var username = '';
	var password = '';
	if($("#"+id+" input[name='username']").val()){
        username = $("#"+id+" input[name='username']").val();
		if(username=='用户名') username = '';
    }
	if($("#"+id+" input[name='password']").val()){
        password = $("#"+id+" input[name='password']").val();
		if(password=='密码') password = '';
    }
	
	var url = siteurl+"/do.php?do=conference2";
	$.post(url, {inajax:1, ac:'conference_submit', parameter:param, dom:id, username:encodeURIComponent(username), password:encodeURI(password)}, function(data){eval(checkReturn(data));});
}

//评论支持点击
function conference_regood(id){
	$.get(siteurl+"/do.php", {inajax:1,ado:'conference2', ac:'comment_vote', parameter: $("#categoryid").val()+'_'+id+'_good',rv:new Date().getTime()}, function(data){
		if(data=='1'){
			$('#good'+id).text(parseInt( $('#good'+id).text())+1);
		}else{
			alert(data);
		}
	});
}

function conference_commentnum(id,param){
	doAjaxGetJSON(id, 'conference2', 'conference_commentnum', param);
}
//退出登录2012-3-19
function loginout(id,param){
   doAjaxGetJSON(id,'conference2','conference_loginout',param);
}