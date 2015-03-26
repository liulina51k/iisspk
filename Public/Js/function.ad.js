var JSONCHINAIISS = '';
var adtypes = new Array();
var scurl = 'http://sc.top0001.com';

function ad_show(id, param){
     doAjaxGetJSON(id,'ad','ad_show',param);
}

//记录广告点击
function adclick(adid){
	doAjaxProJSON(adid, 'ad', 'ad_click', adid);
}
//预加载广告信息
function CHINAIISS_CLB_preloadSlots(){

	for(i=0;i<arguments.length;i++){
		adtypes.push(arguments[i]);
	}
	var refurl = document.referrer;
	if(refurl.indexOf('?')>0){
		refurl = refurl.substr(0, refurl.indexOf('?'));
	}
	var url = scurl+'/do.php?do=ad&ac=ad_show_json&parameter='+encodeURI(adtypes.join(','))+'&refurl='+refurl+'&r='+new Date().getTime().toString().substr(0,7);
	document.write('<script type="text/javascript" charset="utf-8" src="'+url+'"><\/script>');
}
//填充广告信息
function CHINAIISS_CLB_fillSlot(typeid){
	var jdata = JSONCHINAIISS.data;
	var html = jdata[typeid];
	if(html){html = html.replace('<\\/script>', '</script>');html = html.replace('<\\/script>', '</script>');}
	if(typeid == 542)
	{
		return html;
	}else{
	   document.write(html);
	}
}