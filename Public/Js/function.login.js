//显示登录框
function login_show(id, param){
	doAjaxGetJSON(id, 'login', param+'_login', rand());
}
//登录
function login_on(id, ac){
    var username = $("#"+id+" input[name='username']").val();
	if(username == ''){
		alert('请填写用户名！');
		return false;
	}
	
	var password = $("#"+id+" input[name='password']").val();
	if(password == ''){
		alert('请填写密码！');return false;
	}
	username= encodeURIComponent(username);
	password = encodeURIComponent(password);
	var param = username+'_'+password;
    if(arguments[2]){
        param += '_'+arguments[2];
    }
	param = encodeURI(param);

	doAjaxGetJSON(id, 'login',  ac+'_on', param,rand());
}
//登录2
function login_on_new(id, ac){
    var username = $("#"+id+" input[name='username']").val();
	if(username == ''){
		alert('请填写用户名！');
		return false;
	}
	
	var password = $("#"+id+" input[name='password']").val();
	if(password == ''){
		alert('请填写密码！');return false;
	}
	var categoryid = $("input[name='categoryid']").val();
	var specialid = $("input[name='specialid']").val();
	categoryid=encodeURIComponent(categoryid);
	specialid=encodeURIComponent(specialid);
	username= encodeURIComponent(username);
	password = encodeURIComponent(password);
	var param = username+'_'+password+'_'+categoryid+'_'+specialid;

    if(arguments[2]){
        param += '_'+arguments[2];
    }
	param = encodeURI(param);

	doAjaxGetJSON(id, 'login',  ac+'_new', param,rand());
}
//pk
function login_on_pk(id, ac){
    var username = $("#"+id+" input[name='username']").val();
	if(username == ''){
		alert('请填写用户名！');
		return false;
	}
	
	var password = $("#"+id+" input[name='password']").val();
	if(password == ''){
		alert('请填写密码！');return false;
	}
	var categoryid = $("input[name='categoryid']").val();
	var specialid = $("input[name='specialid']").val();
	categoryid=encodeURIComponent(categoryid);
	specialid=encodeURIComponent(specialid);
	username= encodeURIComponent(username);
	password = encodeURIComponent(password);
	var param = username+'_'+password+'_'+categoryid+'_'+specialid;
	alert(param);
    if(arguments[2]){
        param += '_'+arguments[2];
    }
	param = encodeURI(param);

	doAjaxGetJSON(id, 'login',  ac+'_pk', param,rand());
}

//新对话框
function login_on_box(id, ac){
    var username = $("#"+id+" input[name='username']").val();
	if(username == ''){
		alert('请填写用户名！');
		return false;
	}
	
	var password = $("#"+id+" input[name='password']").val();
	if(password == ''){
		alert('请填写密码！');return false;
	}
	var categoryid = $("input[name='categoryid']").val();
	var specialid = $("input[name='specialid']").val();
	categoryid=encodeURIComponent(categoryid);
	specialid=encodeURIComponent(specialid);
	username= encodeURIComponent(username);
	password = encodeURIComponent(password);
	var param = username+'_'+password+'_'+categoryid+'_'+specialid;

    if(arguments[2]){
        param += '_'+arguments[2];
    }
	param = encodeURI(param);

	doAjaxGetJSON(id, 'login',  ac+'_box', param,rand());
}
//退出登陆
function login_quit(id, ac){
    doAjaxGetJSON(id,'login', ac+'_quit',rand());
}

//登陆并刷新页面
function user_login_pro(id){
    var username = $("#"+id+" input[name='username']").val();
    if(username == ''){
        alert('请填写用户名！');
        $("#"+id+" input[name='username']").focus();
        return false;
    }
    
    var password = $("#"+id+" input[name='password']").val();
    if(password == ''){
        alert('请填写密码！');
        $("#"+id+" input[name='password']").focus();
        return false;
    }
	username= encodeURIComponent(username);
	password = encodeURIComponent(password);
    var param = username+'_'+password;
	if($("#"+id+" input[name='anchor']").length)
	{
		param += '_'+$("#"+id+" input[name='anchor']").val();
	}
	if(arguments[1]){
        param += '_'+arguments[1];
    }
    param = encodeURI(param);
    doAjaxProJSON(id, 'login', 'pro_login', param,rand());
}

//退出并刷新页面
function quit_quit(){
    doAjaxProJSON(rand(), 'login', 'quit_quit',rand());
}
//正文页顶部登陆2012-8-2
function login_newsheadlogin(id,param){
    doAjaxGetJSON(id, 'login', 'newshead_login', rand());
}

//最新头部登陆  2012-9-13
function login_newheadlogin(id,param){
    doAjaxGetJSON(id, 'login', 'newhead_login', rand());
}

//最新评论框登陆 2013-08-21
function newcomments_login(id){
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
	paramlogin = encodeURI(paramlogin);
	var ran = rand();
/*	$.get(siteurl+"/do.php", {inajax:1,ado:'login', ac:'newcom_login',parameter: paramlogin,dom:id,rv:ran}, function(data){
		alert(data);
		window.str = data;
		alert(window.str);
	});*/
	
	$.ajax({
		  url: siteurl+"/do.php",
		  data: {inajax:1,ado:'login', ac:'newcom_login',parameter: paramlogin,dom:id,rv:ran},
		  success: function(data){
					window.str = data;
				},
		  type:'GET',
		  async:false
		});
}
