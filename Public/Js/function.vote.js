//�鿴ͶƱ
function viewresearch(voteid){
	window.open(siteurl+'/vote/'+voteid+'.html');
}

//ս�Թ۲�ͶƱ��ʾ
function vote_obvoteshow(id, param){
     doAjaxGet(id, 'vote', 'obvote_show', param, rand());
}

//ս�Թ۲�ͶƱ��ʾ
function vote_specvoteshow(id, param){
     doAjaxGet(id, 'vote', 'specvote_show', param, rand());
}