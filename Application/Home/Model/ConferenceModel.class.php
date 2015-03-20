<?php
namespace Home\Model;
use Think\Model;
class ConferenceModel extends Model{
    public function getConference($id,$act){
    	$con['info'] = $this -> where("id = $id") -> find();
    	/*if(empty($con['info'])) $this->error("你访问的页面不存在");
		if(strstr($con['flag'],'m') && empty($act)){
			$this->error("你访问的页面不存在");
		}*/
    	
    	import("Components.Comment");
	    $ocomment = new \Comment();//生成评论类对象
  
	    import("Components.User");
        $ouser = new \User();//生成用户对象
        $userid  = $ouser -> getUserid();
        $username   = $ouser -> getUserName();
        
        //取得往期
        $con['oldlist'] = $this -> where("id < $id and find_in_set('m',flag) = 0")->order("pubdate desc")->limit(5)->select();	
		$con['oldlist'] = sizeof($con['oldlist']) < 5 ? '' : $con['oldlist'];
        
		$con['other']['count']         =  $ocomment -> getAllCommUserCount($id, -7);
		$con['other']['loginuser']     =  $userid;
		$con['other']['loginusername'] =  $username;
		
		//相关资料
		$con['related'] = unserialize($con['info']['related']);
		//投票
		$db = M();
		$con['other']['arrvote'] = $db -> table("iiss_vote") -> where("infoid = $id and categoryid = -7") -> select();
		if(isset($con['other']['arrvote'][0]['vote']))
		    $con['other']['arrvote'][0]['vote'] = unserialize($con['other']['arrvote'][0]['vote']);
		
		$author = $con['info']['name'];
		$bloguser = $db -> table('iissblog_user') -> where("name = '$author' or username = '$author'")  -> find();
		//$uid = $bloguser['uid'];
		//$con['authorsummary'] = $db -> table("iiss_member") -> where("id = $uid") -> getField("bio");
		$con['other']['authordomain'] = $bloguser['domain'];
		$con['other']['authorid'] = $bloguser['uid'];
		$con['other']['author'] = $author;
		$con['other']['loginuserid'] = $userid;
		$con['other']['loginusername'] = $username;
		
		if(empty($act)){
			//点击计数
			$sql = 'update iiss_conference set clicks=clicks+1  where id='.intval($id);
		    $db->execute($sql);
		    
		    import("Components.Hours");
            $ohours = new \Hours();
			
			import("Components.Viewrecord");
            $oview = new \Viewrecord();//生成记录对象
            
			$oview->add(0,-7,-7,$id);
			$ohours->add(0,-7,-7);
			
			//2012-10-26 添加议事厅记录点击
			import("Components.Clicklog");
            $oclog = new \Clicklog();//生成点击日志对象
			
			$oclog->setFileByModelId(-7);
			//检查当前记录文件是否不存在 不存在自动创建
			$oclog->checkFile();
			$time = time();
			$viewertempid = !empty($_COOKIE['viewertempid']) ? $_COOKIE['viewertempid'] : 0;
			$viewerawaysid = !empty($_COOKIE['viewerawaysid']) ? $_COOKIE['viewerawaysid'] : 0;
			if(0 == $viewertempid){
				$viewertempid = md5($time+rand());
				setcookie('viewertempid',$viewertempid,$time+3600*24,C('COOKIE_PATH'),C('COOKIE_DOMAIN'),0);
			}else{
				//setcookie('viewertempid',$viewertempid,$time+60*24,IConfig::COOKIEPATH,IConfig::COOKIEDOMAIN,0);
			}
			if(0 == $viewerawaysid){
				$viewerawaysid = md5($time+rand());
				setcookie('viewerawaysid',$viewerawaysid,$time+60*60*24*180,C('COOKIE_PATH'),C('COOKIE_DOMAIN'),0);  
			}
			$categoryid = -7;
			$topid = -7;
			$infoid = $id;
			$ip = getClientIP();
			$url = $_SERVER["HTTP_REFERER"];
			//写入文件
			$oclog->writeLog($infoid,$topid,$categoryid,$ip,$viewertempid,$viewerawaysid,$url);
		}
		
		$dayampm = getDayAMPM(); 
		//获取论坛中心地址 和 个人用户中心地址
		$bbsurl  = 'http://bbs.top0001.com';
		$userurl = 'http://user.top0001.com';
		$praise  = $db -> table("iiss_conference_author_praise") -> where("conferenceid = $id") -> getField("praise");
		//strtosm('avatarpic', avatar($uid, 'middle'));
		if($id>201){
			//评论数
			$commcount = $ocomment->getNumByInfoId($id, -7);
		}else{
			//精华数
			$commcount = $ocomment->getNumByRecomm($id, -7);
		}
		$con['other']['dayampm'] = $dayampm;
		$con['other']['bbsurl'] = $bbsurl;
		$con['other']['userurl'] = $userurl;
		$con['other']['praise'] = isset($praise) ? $praise : 0;
		$con['other']['avatarpic'] = "http://ucenter.top0001.com/avatar.php?uid={$bloguser['uid']}&size=middle";
		$con['other']['commcount'] = $commcount;
		return $con;
    }
    public function addCon(){
        
    }
}