//查看投票
function viewresearch(voteid){
	window.open(siteurl+'/vote/'+voteid+'.html');
}

//战略观察投票显示
function vote_obvoteshow(id, param){
     doAjaxGet(id, 'vote', 'obvote_show', param, rand());
}

//战略观察投票显示
function vote_specvoteshow(id, param){
     doAjaxGet(id, 'vote', 'specvote_show', param, rand());
}