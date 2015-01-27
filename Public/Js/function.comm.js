//设置全局变量
var siteurl = "http://www.top0001.com";
var staticurl = "http://static.top0001.com";
var sitename = "战略网";
//加载执行
$(function(){
			//获取当前URL地址
			var url = document.URL;
			//以最后一个&截取
			var poi = url.lastIndexOf('&');
			var sta = url.substr( poi+6 );
			//首页才进行样式加载
			if($("head").html().indexOf('thickbox')==-1 && sta==='no'){
				$("head").append('<link href="'+siteurl+'/style/thickbox.css" rel="stylesheet" type="text/css" media="all" /><script type="text/javascript" src="'+siteurl+'/include/js/thickbox.js"></script>');
				//解决跨域问题，设置同一基础域
				document.domain = 'top0001.com';
				setTimeout( "tb_show(\'首次登录,请您注册\',\'http://www.top0001.com/do.php?inajax=1&do=ologin&ac=alert_login&height=185&width=300&modal=true&TB_iframe=true\')", 1500 );
			}
			callUserFun();

		   //添加收藏功能
		   $("#addcollect").click(function(){
				var ctrl = (navigator.userAgent.toLowerCase()).indexOf('mac') != -1 ? 'Command/Cmd' : 'CTRL';
			    if (document.all){
				    window.external.addFavorite(siteurl,sitename);
			    }else if (window.sidebar){
				   window.sidebar.addPanel(sitename, siteurl, "");
			    }else {
					alert('您可以尝试通过快捷键'+ctrl+ '+ D 加入到收藏夹~');
			    }
			});

		   //记录有需要的点击
		   $("a[id^='r#']").click(function(){recordclick($(this))});
});
/*
 *外部登录函数
 *
 *@param string $type 	类型
 */
function out_login( type ) {

	//把当前地址放到COOKIE中
	document.cookie = 'nowurl = '+encodeURI(document.URL)+';path=/;domain=chinaiiss.com';
	switch( type ) {
		case 'sina':
			var appkey = '1339831039';
			var url = encodeURI('http://www.chinaiiss.com/callback/wcallback.php');
			window.location.href="https://api.weibo.com/oauth2/authorize?client_id="+appkey+"&redirect_uri="+url;
			break;
		case 'qq':
			var appkey = '100239635';
			var url = 'http://www.chinaiiss.com/callback/qcallback.php';
			var scope = 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo';
			window.location.href="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="+appkey+"&redirect_uri="+url+"&scope="+scope;
			//window.location.href = 'http://bbs.chinaiiss.com/connect.php?mod=login&op=init&referer='+window.location.href;
			break;
		default :
			window.location.href="http://www.chinaiiss.com";
			break;
	}
}
//取得跳转点击
function recordclick(o){
   var idstr = o.attr("id").replace('r#', '');
   doRecord(idstr);
}
//取得区块点击
function recordLocationClick(blockid, title, clink){
	if($("#"+blockid).find("a").length>0){
		$("#"+blockid).find("a").click(function(){
			url = clink || $(this).attr("href");
			var subject = $(this).attr('title');
			subject = title || preg_replace(['"', '\'', '@'], ['', '', ' '], subject);
			var idstr = "click|"+blockid+"@@"+url+"@@"+subject;
			doRecord(idstr);
		});
	}
}
//点击记数
function doRecord(idstr){
	var idarr = idstr.split('|');
   if(idarr.length>1){

			 var url = siteurl+"/do.php?inajax=1&ado="+idarr[0]+"&ac="+idarr[0]+"_count&parameter="+encodeURI(idarr[1])+"&r="+rand()+"&json=1&jsoncallback=?";
			 $.getJSON(url, function(json){
			  var redata = checkReturn(json.data);
			  if(redata==""){
				   return true;
			  }else{
				   return false;
			  }
		});
	}
}
//取得随机数
function rand(){
    return Math.floor(Math.random()*1000);
}
//设置文字大小
function setFontSize(id, size){
   $('#'+id).css('font-size',size);
	$('#'+id).children().css('font-size',size);
	$('#'+id).find('a').css('font-size',size);
}
//回调函数，搜索网站页面ID以GV开头的元素，并执行相关js程序
function callUserFun(){
	$("*[id^=gv]").each(function(){
		var id = $(this).attr("id");
		var funarr = id.split('_');
		var num = funarr.length;
		if(num>2){
			var param = id;
			param = param.replace('gv_'+funarr[1]+"_"+funarr[2]+"_", "");
			var funstr = funarr[1]+"_"+funarr[2]+"('"+id+"','"+param+"');";
			//var scripturl = siteurl+"/include/js/function."+funarr[1]+".js";
			var scripturl = staticurl + "/js/function."+funarr[1]+".js";
			$.ajax({type:"GET", cache:true, url:scripturl, dataType:"script", success: function(script){
					var funtrue = funstr;
					//alert(funtrue);
					eval(funtrue);
				}
			});
		}

	});
}
//通用get方式，调用函数
function doAjaxGet(id, does, action, param, r){
	     var ran = r || null;
		 $.get(siteurl+"/do.php", {inajax:1,ado:does, ac:action, parameter: param, rv:ran}, function(data){
																							var did = id;

																							$('#'+did).html(checkReturn(data));
																							} );
}

function doAjaxGetJSON(id, does, action, param, r){
	     var ran = r || null;

		var url = siteurl+"/do.php?inajax=1&do="+does+"&ac="+action+"&parameter="+param+"&dom="+id+"&json=1&jsoncallback=?";
		url = encodeURI(url);
		$.getJSON(url, function(json){
			var did = id;
			$('#'+did).html(json.data);
		});

}

//通用get方式，调用函数
function doAjaxPro(id, does, action, param, r){
	     var ran = r || null;
		 $.get(siteurl+"/do.php", {inajax:1,ado:does, ac:action, parameter: param, dom:id, rv:ran}, function(data){eval(checkReturn(data));});
}

function doAjaxProJSON(id, does, action, param, r){
		 var ran = r || null;
		 var url = siteurl+"/do.php?inajax=1&do="+does+"&ac="+action+"&parameter="+param+"&dom="+id+"&json=1&jsoncallback=?";
		 $.getJSON(url, function(json){eval(checkReturn(json.data));});

}

//通用检验返回值函数
function checkReturn(value){
	   valarr = value.split("||##");
	   if(valarr[0]=='true'){
		   return valarr[1];
	   }
	   return 'alert("'+value+'");';
}

//滑动门通用函数
var slidetime=0;
var slidetimeout=0;
function slide(id, style, time){
	slidetimeout = time || 0;
	$("#"+id+" li").each(function(index){
	   $(this).mouseout(function(){clearTimeout(slidetime);});
	   $(this).mouseover(function(){
		   var o = $(this);
		   slidetime = setTimeout(function(){dofun();}, slidetimeout);
		   function dofun(){
				  $("#"+id+" li."+style).removeClass(style);
				  o.addClass(style);
				  $("ul[id^='"+id+"'],span[id^='"+id+"'],div[id^='"+id+"']").hide();
				  $("#"+id).show();
				  $("#"+id+ (index+1)).show();
			  }

	   });
	});
}//2011-7-21 调整
function sliden(id, style, time){
	slidetimeout = time || 0;
	$("#"+id+" li a").each(function(index){
	   $(this).mouseout(function(){clearTimeout(slidetime);});
	   $(this).mouseover(function(){
		   var o = $(this);
		   slidetime = setTimeout(function(){dofun();}, slidetimeout);
		   function dofun(){
				  $("#"+id+" li a."+style).removeClass(style);
				  o.addClass(style);
				  $("ul[id^='"+id+"'],span[id^='"+id+"'],div[id^='"+id+"']").hide();
				  $("#"+id).show();
				  $("#"+id+ (index+1)).show();
			  }

	   });
	});
}
function slidebase(id, style, time){
	slidetimeout = time || 0;
	var sid = id.replace('_','');
	var obj = $("#"+sid);
	obj.mouseout(function(){clearTimeout(slidetime);});
	obj.mouseover(function(){
		slidetime = setTimeout(function(){dofun();}, slidetimeout);
		function dofun(){
			  $("#"+id+" li."+style).removeClass(style);
			  $("ul[id^='"+id+"'],span[id^='"+id+"'],div[id^='"+id+"']").hide();
			  $("#"+id+'base').show();
			  $("#"+id).show();
		  }
	});

	$("#"+id+" li").each(function(index){
	   $(this).mouseout(function(){clearTimeout(slidetime);});
	   $(this).mouseover(function(){
		   var o = $(this);
		   slidetime = setTimeout(function(){dofun();}, slidetimeout);
		   function dofun(){
				  $("#"+id+" li."+style).removeClass(style);
				  o.addClass(style);
				  $("ul[id^='"+id+"'],span[id^='"+id+"'],div[id^='"+id+"']").hide();
				  $("#"+id).show();
				  $("#"+id+ (index+1)).show();
			  }

	   });
	});
}

//弹窗提示或表单窗口
function apalert(bar, apbody, topval, leftval, w, h){

	if($("#ap").length<1){
	   $("body").append('<div id="ap"><div id="aptop"><b></b><a href="javascript:apclose();">关闭</a></div><div id="apbody"></div></div>');
	}
    var winwidth = $(window).width();
	if(leftval>=(winwidth-w-30)){
	   leftval = winwidth-w-30;
	}
	$("#ap").css({top:topval+"px",left:leftval+"px",width:w+"px"});

	$("#aptop > b").text(bar);
	$("#apbody").html(apbody);

}

//弹窗内容更换
function apbody(txt){
	   if($("#ap").length>0){
		   $("#apbody").html(txt);
		   $("#ap").fadeOut("20000", function(){apclose();});
	   }else{
		   alert(txt);
	   }
}

//关闭窗口
function apclose(){
   $("#ap").remove();
}
//设置表单如果没有内容为空
function delinput(obj, text){
	if(obj.val()==text){
	   obj.val('');
	}
	obj.blur(function(){if(obj.val()==''){obj.val(text);}});
}

//PK投票
function pkvote(id, type, text, url){
	var ev = getEvent();
	if(ev){
		var oEvent = ev.srcElement || ev.target;
		$(oEvent).attr({"disabled":"disabled"});
	}
	var txt = "";
	if (text)
	{
		txt = ","+text;
	}

	if(typeof($.cookie)!='undefined'){
		var cookie = $.cookie('vote'+id)||'';
	}else{
		var cookie = '';
	}

	if(cookie==''){
		var geturl = siteurl + "/do.php?inajax=1&do=pk&ac=vote&parameter="+id+"_"+type+"&r="+rand()+"&json=1&jsoncallback=?";
		$.getJSON(geturl, function(json){

				  var redata = checkReturn(json.data);
				  if(redata==""){
					  $('#'+type+id).text(parseInt($('#'+type+id).text())+1);
					  var r = confirm("投票成功"+txt);
					  if(r){
						 $("#pktitle"+id).click();
					  }
					  $.cookie('vote'+id, 1, {expires:1});
				  }else{
					  if(text != null){
						  var r = confirm(redata+txt);
						 if(r){
							 $("#pktitle"+id).click();
						 }
					  }else{
						alert(redata);
					  }
				  }
				  if(ev){
					$(oEvent).removeAttr("disabled");
				  }
			});
	}else{
		redata = '你已经投过票了';
		if(text != null){
			var r = confirm(redata+txt);
			if(r){
				$("#pktitle"+id).click();
			}
		}else{
			alert(redata);
		}
	}
}

//添写投票调查函数
function submitvote(id){
	var voteval = '';
	var vtag = '';
	$("input[name='vote_"+id+"']:checked").each(function(){
        voteval += vtag+$(this).val();
		vtag='|';
    });

	if(voteval == ''){
	     apbody('请选择或填写后再进行提交！');return false;
	}
	doAjaxProJSON(id, 'vote', 'vote', id+'_'+voteval , rand());
}

//正则替换函数 模仿PHP
function preg_replace(search, replace, str) {
var len = search.length;
for(var i = 0; i < len; i++) {
re = new RegExp(search[i], "ig");
str = str.replace(re, typeof replace == 'string' ? replace : (replace[i] ? replace[i] : replace[0]));
}
return str;
}

//刷新页面
function refreshpage(){
   window.location.href=window.location.href;
}
//获取事件对像
function getEvent() {
	if(document.all) return window.event;
	func = getEvent.caller;
	while(func != null) {
		var arg0 = func.arguments[0];
		if (arg0) {
			if((arg0.constructor  == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {
				return arg0;
			}
		}
		func=func.caller;
	}
	return null;
}
//记录链接点击
function linkclick(linkid){
	doAjaxProJSON(linkid, 'links', 'links_click', linkid);
}
function AddFavorite(sURL, sTitle){
    try
    {
        window.external.addFavorite(sURL, sTitle);
    }
    catch (e)
    {
        try
        {
            window.sidebar.addPanel(sTitle, sURL, "");
        }
        catch (e)
        {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
function SetHome(obj,vrl){
        try{
                obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
        }
        catch(e){
                if(window.netscape) {
                        try {
                                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                        }
                        catch (e) {
                                alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                        }
                        var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                        prefs.setCharPref('browser.startup.homepage',vrl);
                 }
        }
}
//进行页码跳转
function page(style){
	var	reg = /^(\d+)$/,
	num=$("#int_jump").val(),
	pagecount=$("#pagecount").val();

	if(!reg.test(num) || num ==''){
		return false;
	} else {
		num =parseInt(num);
		if(num>=1 && num <= pagecount  ){
			//分多种情况
			if(style=='list'){
			//增加战略观察的列表页跳转没有categoryid 2012-6-18
			   if($('#categoryid').val() != undefined){
					window.location.href=siteurl+"/list/"+$('#categoryid').val()+"/"+num+".html";
				}else{
					window.location.href = siteurl+"/observe/observelist/"+num+".html";
				}
			}else if(style=='mutipage' || style=='picview'){
				var pageUrl=$('#pageUrl').val();
				if(pageUrl != ''){
					//获取的是第一页的url，所以没有下划线。
					newUrl=(num-1<=0) ?  pageUrl : pageUrl.replace('.html', '_'+(num - 1)+'.html');
					window.location.href=newUrl;
				}else{
					return false;
				}
			}else if(style=='spec'){
				window.location.href = siteurl+"/spec/index-"+$('#categoryid').val()+"/"+num+".html";
			}else if(style=='pkt' || style=="app" || style=="comment"  || style=="conference" || style=='knowcenter' || style=='commentart' || style=="send"){
				var pageUrl=$('#pageUrl').val();
				if(pageUrl != ''){
					newUrl=pageUrl.replace('{pagenum}', num);
					window.location.href=newUrl;
				}else{
					return false;
				}
			}
		} else{
			return false;
		}
	}
}
//页码跳转
$("#int_jump").live('keyup', function(){
	var reg = /^(\d+)$/,
	jumlval=$(this).val();
	if(!reg.test($(this).val())){
		$(this).val('');
		return false;
	}else{
		$('#int_jump').val(jumlval);
	}
}).live('keydown',function(e){
	if(!e)e=window.event;
	var code=e.charCode?e.charCode:e.keyCode;
	if(code== 13){
		var style=$('#style').val();
		page(style);
	}
});
//enter键登陆
function enterSubmit(obj){
    var buttonid = obj.attr('buttonid');
    obj.keydown(function(e){
        if(13 == e.which){
            $("#"+buttonid).click();
            obj.blur();
            obj.focus();
        }
    })
    obj.blur(function(){
        $(this).unbind('keydown');
    });
}
//屏蔽ieenter提交表单
$(document).ready(function(){
//禁用Enter键表单自动提交
    document.onkeydown = function(event) {
        var target, code, tag;
        if (!event) {
            event = window.event; //针对ie浏览器
            target = event.srcElement;
            code = event.keyCode;
            if (code == 13) {
                tag = target.tagName;
                if (tag == "TEXTAREA") { return true; }
                else { return false; }
            }
        }
        else {
            target = event.target; //针对遵循w3c标准的浏览器，如Firefox
            code = event.keyCode;
            if (code == 13) {
                tag = target.tagName;
                if (tag == "INPUT") { return false; }
                else { return true; }
            }
        }
    };
})