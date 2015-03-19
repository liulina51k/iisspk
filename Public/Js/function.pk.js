//调取用户查看数
function pk_viewnum(id, param){
     if(param == '') return false;
     doAjaxGetJSON(id,'pk','pk_viewnum',param);
}

/*
 *转发函数
 *@param pkid PKid
 *@param title 转播内容
 *@param id 对应标签上的id
 */
function transmit_pk(pkid,title,id) {
    alert(1);
	//获取被点击的标签的ID 整个页面是唯一的!
	var obj = $('#'+id);
	//判断是左边的点击还是右边的点击  左边差200PX正好  右边差688PX正好
	var objleft = parseInt(obj.position().left);
/*
	if ( parseInt(objleft) > parseInt(700) ) {
		var offsetleft = parseInt(objleft) - parseInt(688);
	} else {
		var offsetleft = parseInt(objleft) - parseInt(200);
	}
*/	
	var offsetleft = parseInt(objleft) - parseInt(obj.parents('div').position().left);
	//转发时，需要URLENCODE转义
	var title = encodeURI(title);
	
	//地址，把PKID和TITLE传送过去，通过PHP文件把内容赋给模版变量，并返回整个转发的<SPAN>标签
	$url = siteurl+'/do.php?inajax=1&do=pk&ac=pk_transmit&parameter='+pkid+'_'+title+"&json=1&jsoncallback=?";
	$.getJSON( $url, function(json) {
		//把通过JSON获取到的转发标签 插入到相对应的ID标签的下面
		obj.parents('div').append(json.data);
		//设置弹出框跟随点击按钮的位置改变而改变
		obj.parents('dl').siblings('span').find('div').css('left',offsetleft+'px');
		//并显示相关的转发微博标签
		obj.parents('dl').siblings('span').find('div').show();
		//当点击转发微博标签上面的 转发按钮时  执行CLICK事件
		obj.parents('dl').siblings('span').find('a:first-child').click(function(){
		//把相应的微博标签清空掉
		obj.parents('dl').siblings('span').empty();
		});
	});
}
function pkcomments_submit(id, type, length) {
	var ev = getEvent();
	slength = (typeof (length) == "undefined") ? 200 : length;
	var uname = $('.plk_foot_l_name').html();
	if (uname == null) {
	    var username = $("#"+id+" input[name='username']").val();
		var password = $("#"+id+" input[name='password']").val();
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

	 //类型，支持者还是反对都
	var commenttype = $("#"+id+" input[name='commenttype']").val() || 0;
	param += '_'+commenttype;
	//引用ID
	var quoteid = $("#"+id+" input[name='quoteid']").val() || 0;
	param += '_'+quoteid;
	
	param +='_'+paramlogin;

	param = encodeURI(param);

	if (ev) {
		var oEvent = ev.srcElement || ev.target;
		$(oEvent).attr({
			"disabled" : "disabled"
		});
	}
	$.ajaxSettings.async = true;
	doAjaxProJSON(id, 'pk', 'pkcomment_submit', param, rand());
	return true;
}