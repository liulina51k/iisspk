//提取评论列表
function conference_list(id, param){
	doAjaxProJSON(id, 'conference', 'conference_list', param);
}
//提交评论
function conference_submit(id, param){
	//param += '_'+$("#"+id+" textarea[name='content']").val().replace('_', ' ');
	param += '_'+preg_replace(['&', '<', '>', '"', '_'], ['&amp;', '&lt;', '&gt;', '&quot;', ' '], $("#"+id+" textarea[name='content']").val());

	if($("#"+id+" textarea[name='content']").val().length>1000){
		alert('内容请少于1000字。');return false;
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

	//是否引用整楼
	var isquote = $("#"+id+" input[name='isquote']").val() || 1;
	param += '_'+isquote;

	//param = encodeURI(param);
	
	var url = siteurl+"/do.php?do=conference";
	$.post(url, {inajax:1, ac:'conference_submit', parameter:param, dom:id}, function(data){eval(checkReturn(data));});
    //doAjaxProJSON(id, 'conference', 'conference_submit', param, rand());
}

//评论支持点击
function conference_regood(id){
	$.get(siteurl+"/do.php", {inajax:1,ado:'comment', ac:'comment_vote', parameter: $("#categoryid").val()+'_'+id+'_good',rv:new Date().getTime()}, function(data){
		      $('#good'+id).text(parseInt( $('#good'+id).text())+1);
		});

}

function conference_commentnum(id,param){
	doAjaxGetJSON(id, 'conference', 'conference_commentnum', param);
}
//退出登录2012-3-19
function loginout(id,param){
   doAjaxGetJSON(id,'conference','conference_loginout',param);
}