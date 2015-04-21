<?php
namespace Home\Controller;
use Think\Controller\RestController;
class ConferenceController extends RestController {
    private $_instance;
    public function __construct() {
        parent::__construct();
        $this->_instance = D('Conference');
    }
    //pk台首页
    public function rest(){
        $id = I('id');
        $flag = $this->_instance -> where("id = $id") -> getField("flag");
        if(strstr($flag,'m')){
			showmessage("你访问的页面不存在");
		}
        $info = $this->_instance->getConference($id,0);
        $this -> assign('info',$info['info']);
        $this -> assign('oldlist',$info['oldlist']);
        $this -> assign('other',$info['other']);
        $this -> assign('related',$info['related']);
        if($id > 269)
            $this -> display("conference.index.v4.0");
        elseif($id > 245)  
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
			showmessage("你访问的页面不存在");
		}
        $info = $this -> _instance -> getConference($id,1);
        $this -> assign('info',$info['info']);
        $this -> assign('oldlist',$info['oldlist']);
        $this -> assign('other',$info['other']);
        $this -> assign('related',$info['related']);
        if($id > 269)
            $this -> display("conference.index.v4.0");
        elseif($id > 245)  
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
		$blogurl = C("BLOGURL");
		//用户名
		
		if($uid){
			$con['other']['iiss_supeid'] = 'true';
		}else{
			showmessage('请登录后再进行相关操作，如果你不是会员请先注册。', IISSSITE.'/user/reg.html', 5);
		}
		$val = $db -> table("iissblog_user") -> where("uid = $uid") -> select();
		if(empty($val)){
			showmessage('您还没开通博客，请先开通博客。', IISSSITE.'/user/blog.html', 5);
		}
		if(submitcheck('sendsubmit')){
			session_start();
			if($_SESSION['code_str']!=$_POST['verifycode']){
				showmessage('验证码输入错误');
			}
			$art = $_POST['art'];
			$artfield = array();

            //转化内容
			$artfield['message'] = !empty($_POST['message']) ? sstripslashes($_POST['message']) : showmessage('文章内容不能为空');
			if(strlen($artfield['message'])<500){
				showmessage('文章内容少于500字');
			}
			$art['subject'] = !empty($art['subject']) ? sstripslashes($art['subject']) : showmessage('文章标题不能为空');
			$art['shortsubject'] = $art['subject'];

		    //删除非站内链接
			$artfield['message'] = preg_replace_callback('/<a\s*href=["\"\']http:\/\/([^\/]*)(["\"\'])?(.*?)\\1>(.*?)<\/a>/U', 'removelink', $artfield['message']);
			//删除标签属性除img
			preg_match_all('/(<img\s*?.*?>)/',$artfield['message'],$messageStr);
			
			$artfield['message'] = preg_replace('/<([a-z]+)\s*?.*?>/','<$1>',$artfield['message']);
			foreach($messageStr[0] as $key=>$messageVal){
				$artfield['message'] = preg_replace('/<img>/',$messageVal,$artfield['message'],1);
			} 
			//去掉内容的span标签
			$artfield['message'] = preg_replace('/<span>/',' ',$artfield['message']);
			$artfield['message'] = preg_replace('/<\/span>/',' ',$artfield['message']);
			
			$artfield['message'] = saddslashes($artfield['message']);

			//获取父ID
			$art['topid'] = 59;

			//获取专栏
			$db = M();
			$art['writerid'] = $db -> table("iiss_writer") -> where("userid=$uid") -> getField("wid");
			//获取时间
			$art['pubdate'] = $art['modifydate'] = $_IGLOBAL['timestamp'];
			//文章实例
			$sortnum = $db -> table("iiss_writerart") -> field("max(sortnum) sortnum") -> find();
			$art['sortnum'] = $sortnum['sortnum']+1;

			//审核状态
			$art['ischeck'] = 0;
			$art['original'] = 1;//原创
			$art['froms'] = 2;//来源 1 群英论见投稿 2 议事厅投稿
			
			//写入数据库
			if(empty($artfield['message'])){
				showmessage('文章内容不能为空');
			}
			$art['original']=1;
			$art['categoryid']='308';
			$art['userid'] = $uid;

			$artfield['writer'] = $uname;
			$artfield['postip']=getClientIP();
			$artfield['pagingtype']=1;
			
			if(isset($_POST['sendblog'])){
				$blog['classid'] = $db -> table("iissblog_class") -> where("uid=$uid and classname='全球议事厅'") -> getField("classid");
				if(empty($blog['classid'])){
					$classarr = array('classname'=>'全球议事厅','uid'=>$uid,'dateline'=>$_IGLOBAL['timestamp']);
					$db -> table("iissblog_class") -> add($classarr);
					$classid = $db -> table("iissblog_class") -> field("max(classid) classid") -> find();
			        $blog['classid'] = $classid['classid'];
				}
				$blog['uid'] = $uid;
				$blog['username'] = $uname;
				$blog['subject'] = $art['subject'];
				$blog['tag'] = $artfield['tag'];
				$blog['message'] = $artfield['message'];
				$blog['postip'] = getClientIP();
				$blog['dateline'] = $_IGLOBAL['timestamp'];
				$blog['isshow'] = 1;
				$blog['ispic'] = empty($artfield['attachment'])?'':1;
				$db -> table("iissblog_blog") -> add($blog);
				$blogid = $db -> table("iissblog_blog") -> field("max(blogid) blogid") -> find();
		        $blogid = $blogid['blogid'];
				//更新空间
				$db->execute('update iissblog_user set updatetime='.$_IGLOBAL['timestamp'].',notenum=notenum+1 where uid='.$uid);
				//更新用户积分
				$scores = file_get_contents(IISSSITE."/sysinfo.php?var=sysconfig&datavalue=spaceblog_scores");//积分
				$money = file_get_contents(IISSSITE."/sysinfo.php?var=sysconfig&datavalue=spaceblog_money");//金钱
				$scoreslimit = file_get_contents(IISSSITE."/sysinfo.php?var=sysconfig&datavalue=spaceblog_scoreslimit"); //积分限制
				$moneylimit = file_get_contents(IISSSITE."/sysinfo.php?var=sysconfig&datavalue=spaceblog_moneylimit");//金钱限制
				
				$user_scoreslimit = isset($_COOKIE[$uid.'_spaceblog_scoreslimit']) ? $_COOKIE[$uid.'_spaceblog_scoreslimit'] : 0;
				$user_moneylimit = isset($_COOKIE[$uid.'_spaceblog_moneylimit']) ? $_COOKIE[$uid.'_spaceblog_moneylimit'] : 0;
				if($scores>0){
					if($scoreslimit>0 && $scoreslimit>$user_scoreslimit || empty($scoreslimit)){
						file_get_contents(IISSSITE."/updateCredit.php?credit=1&amount=$scores&uid=$uid&discuz_user=$username");
						if($scoreslimit>0)
							ssetcookie($uid.'_spaceblog_scoreslimit', $user_scoreslimit+$scores);
					}
				}
				if($money>0){
					if($moneylimit>0 && $moneylimit>$user_moneylimit || empty($moneylimit)){
						file_get_contents(IISSSITE."/updateCredit.php?credit=3&amount=$scores&uid=$uid&discuz_user=$username");
						if($moneylimit>0)
							ssetcookie($uid.'_spaceblog_moneylimit', $user_moneylimit+$money);
					}
				}
				
				//记录事件
				//事件feed
				$domain = $db -> table("iissblog_user") -> where("uid = $uid") -> getField("domain");
				$fs = array();
				$fs['icon'] = 'blog';

				$fs['title_data'] = array();

				$fs['title_template'] = '{actor} 发表了新日志';
				$fs['body_template'] = '{subject}<br />{summary}';
				$fs['body_data'] = array(
					'subject' => "<a href=\"$blogurl/$domain/blog/view/$blogid\" class=\"blue\">$blog[subject]</a>",
					'summary' => getstr($blog['message'], 150, 1, 1, 0, -1)
				);
				feed_add($fs['icon'], $fs['title_template'], $fs['title_data'], $fs['body_template'], $fs['body_data']);

				$art['blogid']=$blogid;
			}
			
			$db -> table("iiss_writerart") -> add($art);
			$artid = $db -> table("iiss_writerart") -> field("max(artid) artid") -> find();
			$artfield['artid'] = $artid['artid'];
			$db -> table("iiss_writerartfield") -> add($artfield);
			
			showmessage('投稿发布成功！', $_IGLOBAL['refer']);
			
		}else{
			$con['other']['uid'] = $uid;
			$con['other']['username'] = $uname;
			$con['other']['blogurl'] = $blogurl;
			$this -> assign ('other',$con['other']);
			$this -> assign ('formhash',formhash());
			$this -> display('conference.add');
		}
    }
}