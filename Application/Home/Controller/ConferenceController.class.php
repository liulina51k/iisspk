<?php
namespace Home\Controller;
use Think\Controller;
class ConferenceController extends Controller {
    private $_instance;
    public function __construct() {
        parent::__construct();
        $this->_instance = D('Conference');
    }
    //pk台首页
    public function index(){
        $id = I('id');
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
		//用户名
		if($uid){
			$con['other']['iiss_supeid'] = 'true';
		}else{
			U('请登录后再进行相关操作，如果你不是会员请先注册。', IISSSITE.'/user/reg.html', 5);
		}
		
		$val = $db -> table("iissblog_user") -> where("uid = $uid") -> select();
		if(empty($val)){
			U('您还没开通博客，请先开通博客。', IISSSITE.'/user/blog.html', 5);
		}
		if(submitcheck('sendsubmit')){
			printr(1);
		}else{
			import("Components.Editor");
	        $oeditor = new \Editor();
			
	        import("Components.Sysdata");
	        $osysdata = new \Sysdata();
			//验证码
			$verify=$osysdata -> getDataValue('sysconfig', 'spaceblog_verify');
			$result=$osysdata -> getDataValue('sysconfig', 'spaceblog_result');
			if(!empty($verify)){
				$key=array_rand($verify);
				strtosm('vkey', $key);
				strtosm('verifykey', $verify[$key]);
				strtosm('resultkey', $result[$key]);
			}
			strtosm('uid', $uid);
			strtosm('username', $uname);
			strtosm('blogurl', IConfig::BLOGURL);
			//获得编辑器
			strtosm('editor',IEditor::getXh('xheditor-mini', '', 'message', '12', '80', 'width:464px;height:282px'));

			$topid = 59;
			$arrsubnav = array();
			$arrnav = IIissdata::getSiteNavi();
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

			strtosm('submenu',$submenu);
			strtosm('topcategoryid',$topid);

			$this -> display('conference.add');
		}
    }
}