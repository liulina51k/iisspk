<?php
namespace Home\Controller;
class ConferenceController extends BaseController {
    private $_instance;
    public function __construct() {
        parent::__construct();
        $this->_instance = D('Conference');
    }
    //pk台首页
    public function index(){
        $id = I('id');
        $flag = $this->_instance -> where("id = $id") -> getField("flag");
        if(strstr($flag,'m')){
			parent :: showmessage("你访问的页面不存在");
		}
        $info = $this->_instance->getConference($id,0);
        $this -> assign('info',$info['info']);
        $this -> assign('oldlist',$info['oldlist']);
        $this -> assign('other',$info['other']);
        $this -> assign('related',$info['related']);
        if($id > 245)
            $this -> display("conference.index.v3.0");
        elseif($id > 201)  
            $this -> display("conference.index.v2.0");
        elseif($id > 66)
            $this -> display("conference.index.v1.0");
        else
            $this -> display("conference.index");
    }
     //pk台首页
    public function preview(){
        $id = I('id');
        $flag = $this->_instance -> where("id = $id") -> getField("flag");
        if(strstr($flag,'m')){
			parent :: showmessage("你访问的页面不存在");
		}
        $info = $this -> _instance -> getConference($id,1);
        $this -> assign('info',$info['info']);
        $this -> assign('oldlist',$info['oldlist']);
        $this -> assign('other',$info['other']);
        $this -> assign('related',$info['related']);
        if($id > 245)
            $this -> display("conference.index.v3.0");
        elseif($id > 201)
            $this -> display("conference.index.v2.0");
        elseif($id > 66)
            $this -> display("conference.index.v1.0");
        else
            $this -> display("conference.index");
    }
    public function add(){
        global $_IGLOBAL;
		$db = M();
	    import("Components.User");
	    $ouser = new \User();//生成用户对象
	    
	    $uid  = $ouser -> getUserid();
	    $uname   = $ouser -> getUserName();
		
		$_IGLOBAL['supe_uid'] = $uid;
		$_IGLOBAL['supe_username'] = $uname;
		$blogurl = 'http://blog.top0001.com';
		//用户名
		/*
		if($uid){
			$con['other']['iiss_supeid'] = 'true';
		}else{
			parent :: showmessage('请登录后再进行相关操作，如果你不是会员请先注册。', IISSSITE.'/user/reg.html', 5);
		}*/
		$val = $db -> table("iissblog_user") -> where("uid = $uid") -> select();
		if(empty($val)){
			parent :: showmessage('您还没开通博客，请先开通博客。', IISSSITE.'/user/blog.html', 5);
		}
		if(submitcheck('sendsubmit')){
			printr(2);
		}else{
			import("Components.Editor");
	        $oeditor = new \Editor();
			
	        import("Components.Sysdata");  
	        $osysdata = new \Sysdata('sysconfig');
			//验证码
			$verify=$osysdata -> getDataValue('sysconfig', 'spaceblog_verify');
			$result=$osysdata -> getDataValue('sysconfig', 'spaceblog_result');
			if(!empty($verify)){
				$key=array_rand($verify);
				$con['other']['vkey'] = $key;
				$con['other']['verifykey'] = $verify[$key];
				$con['other']['resultkey'] = $result[$key];
			}
			$con['other']['uid'] = $uid;
			$con['other']['username'] = $uname;
			$con['other']['blogurl'] = 'http://blog.enchinaiiss.com';
			//获得编辑器
			$editor = $oeditor -> getXh('xheditor-mini', '', 'message', '12', '80', 'width:464px;height:282px');
			$con['other']['editor'] = $editor;
			
			$topid = 59;
			$arrsubnav = array();
			import("Components.Iissdata");
	        $osysdata = new \Iissdata();
			$arrnav = $osysdata -> getSiteNavi();
			foreach($arrnav as $item){
				if($item['categoryid']==$topid){
					$arrsubnav = $item['sub'];
					$channelurl = $item['link'];
					$list['topcategoryname'] = $item['naviname'];
					break;
				}
			}
			foreach($arrsubnav as $item){
				$target = !empty($item['target']) ? 'target="_blank"' : '';
				$submenu[] = '<a href="'.$item['link'].'" title="'.$item['naviname'].'" '.$target.'>'.$item['naviname'].'</a>';
			}
			unset($arrsubnav);
            
			$con['other']['topcategoryid'] = $topid;
			$con['submenu'] = $submenu;
			$this -> assign ('other',$con['other']);
			$this -> assign ('submenu',$con['submenu']);

			$this -> display('conference.add');
		}
    }
}