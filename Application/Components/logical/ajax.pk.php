<?php
/*
*  $id: do.pk.php 2009-8-5 kelly kong$
*  PK台程序
*/
defined('IN_IISS') or die('Access Denied');

import('pk', 'source');
$opk = & IPK::getInstance();
import('pkvote', 'source');
$ovote = & IPkvote::getInstance();
import('comment', 'source');
$ocomment = & IComment::getInstance();
import('subdate');
$subdate = new ISubdate;
$ac = !empty($_GET['ac']) ? $_GET['ac'] : '';
$json = !empty($_GET['json']) ? $_GET['json'] : '';

//获得参数值
$param = !empty($_GET['parameter']) ? $_GET['parameter'] : exit('参数错误');
$paramarr = explode('_', $param);
IApk::$id = !empty($paramarr[0]) ? intval($paramarr[0]) : 0;
if(!call_user_func(array('IApk', $ac))){
    exit(0);
}

class IApk
{
	public static $id=0;
	//投票
    public static function vote(){
		global $paramarr,$opk,$json,$ovote;

		/* 2010-6-29 取消胜负对投票的限制
        $result = $opk->pkResult(self::$id);
		if($result<=0 || $result>=1){
			 if(!empty($json)){
				$callback = $_GET['jsoncallback'];
				$strreturn = json_encode('true||##此PK胜负已分，投票已结束！');
				$strreturn = json_encode('true||##');
				echo $callback."({data:$strreturn})";
			 }else{
				echo '此PK胜负已分，投票已结束！';
			 }
			 exit;
		}
		*/

        $voteip = getClientIP();
		$ckey = 'voteip'.self::$id;
		 //记录这次点记写入COOKIE

	    if(isset($_COOKIE[$ckey]) && $_COOKIE[$ckey ] ==$voteip){
             if(!empty($json)){
				$callback = $_GET['jsoncallback'];
				$strreturn = json_encode('true||##你已经投过票了');
				echo $callback."({data:$strreturn})";
			 }else{
				echo '你已经投过票了';
			 }
			 exit;
		}else{
		     ssetcookie($ckey, $voteip, 3600);
		}

		if(!empty($json)){
			$callback = $_GET['jsoncallback'];
			$strreturn = json_encode('true||##');
			$callback = $callback."({data:$strreturn})";
		 }else{
			$callback = '';
		 }

		$db = & IFactory::getDB();
		$votefield = $paramarr[1]=='agree' ? 'agreevote' : 'opposevote';
		$sql = "update iiss_pk set $votefield=$votefield+1 where id=".self::$id;
		$query = $db->query($sql);

        $ovote->add(self::$id, $paramarr[1]);
		import('user', 'source');
        $ouser = & IUser::getInstance();
		if($ouser->isLogin()){
			//更新用户积分
			$pkvote_scores = ISysdata::getDataValue('sysconfig', 'pkvote_scores'); //登陆积分
			$pkvote_money = ISysdata::getDataValue('sysconfig', 'pkvote_money'); //登陆金钱
			$uid=$ouser->getUserid();
			$uname=$ouser->getUserName();
			import('pkvoteuser', 'source');
            $ovoteuser = & IPkvoteUser::getInstance();
            $ovoteuser->add(self::$id,array($uid,$uname),$paramarr[1]);
			update_usercredit($uid, $uname, 'vote');			
		}

		echo $callback;
		exit;

	}

	public static function getvote(){
		global $opk;

		$callback = $_GET['jsoncallback'];
		$data = array();
		$data['agreevote'] = $opk->getPk(self::$id, 'agreevote');
		$data['opposevote'] = $opk->getPk(self::$id, 'opposevote');

		echo $callback.'('.json_encode($data).')';
	}

    public static function pk_viewnum() {
        global $opk,$json;

        import('hours', 'source');
        $ohours = & Ihours::getInstance();
        $opk->pkclick(self::$id);
        import('viewrecord', 'source');
        $oview = & IViewrecord::getInstance();
        $oview->add(0,-1,-1,self::$id);
        $ohours->add(0,-1,-1);
		
		if(self::$id){
			//2012-10-26 添加文件记录点击
			import('clicklog','source');
			$oclog = & IClicklog::getInstance();
			$oclog->setFileByModelId(-1);
			//检查当前记录文件是否不存在 不存在自动创建
			$oclog->checkFile();
			$time = time();
			$viewertempid = !empty($_COOKIE['viewertempid']) ? $_COOKIE['viewertempid'] : 0;
			$viewerawaysid = !empty($_COOKIE['viewerawaysid']) ? $_COOKIE['viewerawaysid'] : 0;
			if(0 == $viewertempid){
				$viewertempid = md5($time+rand());
				setcookie('viewertempid',$viewertempid,$time+3600*24,IConfig::COOKIEPATH,IConfig::COOKIEDOMAIN,0);
			}else{
				//setcookie('viewertempid',$viewertempid,$time+60*24,IConfig::COOKIEPATH,IConfig::COOKIEDOMAIN,0);
			}
			if(0 == $viewerawaysid){
				$viewerawaysid = md5($time+rand());
				setcookie('viewerawaysid',$viewerawaysid,$time+60*60*24*180,IConfig::COOKIEPATH,IConfig::COOKIEDOMAIN,0);  
			}
			$categoryid = -1;
			$topid = -1;
			$infoid = self::$id;
			$ip = getClientIP();
			$url = $_SERVER["HTTP_REFERER"];
			//写入文件
			$oclog->writeLog($infoid,$topid,$categoryid,$ip,$viewertempid,$viewerawaysid,$url);
		}
        if(!empty($json)){
				$callback = $_GET['jsoncallback'];
				$strreturn = json_encode('');
				echo $callback."({data:$strreturn})";
		  }else{
				echo 'true||##';
		  }
    }

    /**
     * ajax 获取转播内容
     */
    public static function pk_transmit() {
    	global $json, $paramarr;
		$vars['url'] = IConfig::BASE.'/pkt/index/'.$paramarr[0].'.html';
		$vars['site'] = IConfig::BASE;
		$vars['imageUrl'] =IConfig::IMAGEURL;
		$vars['title'] = $paramarr[1];
		arraytosm($vars);
		$transmit = template( 'pkt.transmit.html', 1 );
    	if(!empty($json)){
				$callback = $_GET['jsoncallback'];
				$strreturn = json_encode($transmit);
				//$strreturn = '{"url":'.$vars['url'].',"site":'.$vars['title'].'}';
				echo $callback."({data:$strreturn})";
		  }else{
				echo 'true||##';
		}
    }

	//2012-3-19 pk退出
	public static function pk_loginout(){
		global $opk,$json;
		import('user', 'source');
		$ouser = & IUser::getInstance();
		$ucloginout = $ouser->quit();
		ssetcookie('supe_login'.$_IGLOBAL['supe_uid'], '', -3600 * 24);
		$login='<form onsubmit="return false;">用户名<input class="text" type="text" name="username">密 码
					<input class="text" type="password" name="password"><input type="image" src="'.IConfig::BASE.'/images/pk/login_bot.jpg" onclick="pklogin();" name="loginuser"><input type="image" src="'.IConfig::BASE.'/images/pk/register_bot.jpg" name="reguser"><span>	其他方式登录：<a class="SINA_login" onclick="out_login( \'sina\')" href="###">微博登录</a>	<a class="QQ_login" onclick="out_login( \'qq\' )" href="###">QQ登录</a>	</span></form>';
		if(!empty($json)){
			$callback = $_GET['jsoncallback'];
			$strreturn = json_encode($login);
			echo $callback."({data:$strreturn})";
		}else{
			echo $data;
		}

	}
	
	//取得信息评论回复
	public static function pk_reap()
	{
		global $ocomment, $paramarr;
		$value = $ocomment->getComment($paramarr[1]);

		import('user', 'source');
		$ouser = & IUser::getInstance();

		if($ouser->isLogin()){
			$userhtml = '你好，'.$ouser->getUserName();
		}else{
			$userhtml = '你还没有登陆,';
		}
		$position = isset($paramarr[2]) ? $paramarr[2] : 0;

		$isquote = isset($paramarr[3]) ? $paramarr[3] : 1;

		$userid = $ouser->getUserid();//用户是否登录
		if(empty($userid)){
			$login='<label>用户名:&nbsp;</label><input type="text" buttonid="login_bbtn" class="txt_com" name="username"><label>&nbsp;密码:&nbsp;</label><input type="password" class="txt_com"  buttonid="login_bbtn" name="password"><input type="button" id="login_bbtn" class="login_btn" value="" onclick="login_on(\'px_login_reply_'. $paramarr[1] .'\',\'conference\');"><a class="register" target="_blank" href="'. IConfig::BASE .'/user/reg.html">注册</a>';
		}else{
			$login="<label>欢迎您，</label><a href='". IConfig::USERURL . "/home/' class='register'>". $ouser->getUserName() ."</a><a href='javascript:quit_quit();' class='register'>退出</a>";
		}

		$checked = empty($userid) ? 'checked="checked"' : '';

		$commhtml="
		<h1 id='top'><span class='close_in'><input name='' type='image' src='".IConfig::BASE."/images/conference/help_spanbg.jpg' onclick='javascript:box_apclose();'/></span><div class='login_com' id='islogin_top'><form onsubmit='return false;' id='px_login_reply_".$paramarr[1]."'>".$login."</form></div><a class='comment_tit' style='cursor: pointer;' >发布评论</a></h1>
		<input name='quoteid' type='hidden' value='".$paramarr[1]."' /><input type='hidden' name='isquote' value='".$isquote."' /><input name='commenttype' type='hidden' value='".$value['type'] ."' />
		<ul>
		<li><img src='".IConfig::BASE."/images/conference/expression_1.gif' title='/微笑' id='/ws' style='cursor:pointer;' onclick='javascript:clicks(this.id);' /></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_2.gif' title='/撇嘴' id='/ps' style='cursor:pointer;' onclick='javascript:clicks(this.id);' /></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_3.gif' title='/色' id='/se' style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_4.gif' title='/发呆' id='/fd'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_5.gif' title='/酷' id='/ku'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_6.gif' title='/流泪' id='/ll'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_7.gif' title='/害羞 ' id='/hx'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_8.gif' title='/闭嘴' id='/bz'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_9.gif' title='/睡' id='/sh' style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_10.gif' title='/大哭' id='/dk'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_11.gif' title='/尴尬' id='/gg'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_12.gif' title='/发怒' id='/dn'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_13.gif' title='/调皮' id='/tp'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_14.gif' title='/呲牙' id='/cy'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		<li><img src='".IConfig::BASE."/images/conference/expression_15.gif' title='/惊讶' id='/jy'  style='cursor:pointer;' onclick='javascript:clicks(this.id);'/></li>
		</ul>
		<br class='clear' />
		<div id='form'>
		<textarea name='content' class='box'></textarea>
		<p>网友评论仅供其表达个人看法，并不表明战略网同意其观点或证实其描述。</p>
		<div class='comment_sub'><input type='checkbox' class='niming' value='1' name='anonymous'><label>匿名</label>
		<input type='image' onclick='comments_submit(\"box\",\"". self::$id."_".$value["infoid"] ."\");' src='". IConfig::BASE ."/images/conference/shout_tj.jpg' class='tj_button'></div><div class='clear'></div>
		</div>";

		echo 'true||##'.$commhtml;
	}

	//取得信息评论回复
	public static function pk_reap_new()
	{	
		global $ocomment, $paramarr, $subdate;
		$value = $ocomment->getComment($paramarr[1]);

		import('user', 'source');
		$ouser = & IUser::getInstance();

		$position = isset($paramarr[2]) ? $paramarr[2] : 0;
		$isquote = isset($paramarr[3]) ? $paramarr[3] : 1;
		$userid = $ouser->getUserid();//用户是否登录
		
		$login = "<input name='specialid' type='hidden' value='".$value["infoid"]."'><input name='categoryid' type='hidden' value='".$value["categoryid"]."'></span>";

		if(empty($userid)){
			$login .= '<div id="gv_user_login_comm" class="plk_foot_l_01">
		<input name="username" id="username" class="plk_foot_l_input" value="用户名" onclick="if ($(this).val() == \'用户名\') {$(this).val(\'\');}" onblur="if($(this).val() == \'\') {$(this).val(\'用户名\');}" type="text">
		<input name="password" id="password" class="plk_foot_l_input" value="" style="display:none;" onblur="if($(this).val()== this.defaultValue){$(\'#gv_user_login_comm #showpass\').show();$(this).hide();}" type="password"><input value="密码" id="showpass" class="plk_foot_l_input" onfocus="$(this).hide();$(\'#gv_user_login_comm #password\').show().focus();" type="text"> 
		<span><a onclick="out_login(\'sina\');" href=\"javascript:void(0);\"><img src="'. IConfig::STATICURL .'/images/sina.jpg" width="16" height="16" /></a></span><span><a  onclick="out_login(\'qq\');" href="javascript:void(0);"><img src="'. IConfig::STATICURL .'/images/QQ.jpg" width="16" height="16" /></a></span><a  href="'.IConfig::BASE.'"/user/reg" class="plk_foot_zc">注册</a></div>
		<div class="plk_foot_r"><span class="plk_foot_checkbox"><input name="anonymous" value="1" type="checkbox"></span><span class="plk_foot_niming">匿名</span>
		<a class="plk_button" id="loginpublish" href="javascript:void(0);" onclick="pkcomments_submit(\'boxbody\');">登录并发布</a></div>';
		
		}else{
			$login.="<div class='plk_foot_l_02'>".$subdate->getDayAMPM()."好：<span class='plk_foot_l_name'>". $ouser->getUserName() ."</span><a href='". $siteuser . "/home/'>个人中心</a>|<a href='javascript:quit_quit();'>退出</a></div>";
			$login .= '<div class="plk_foot_r"><span class="plk_foot_checkbox"><input type="checkbox" value="1" name="anonymous"></span><span class="plk_foot_niming">匿名</span><a onclick="pkcomments_submit(\'boxbody\');" href="javascript:void(0);" id="publish" class="plk_button">发布</a></div>';
		}

		$commhtml="<div class='plk'>
		<div class='plk_top'><span class='plk_top_logo'></span><span class='plk_top_num'><a href='javascript:box_apclose();'><img src='".IConfig::BASE."/static/images/help_spanbg.jpg' /></a></span></div>
		<input name='quoteid' type='hidden' value='".$paramarr[1]."' /><input type='hidden' name='isquote' value='".$isquote."' /><input name='commenttype' type='hidden' value='".$value['type'] ."' />
		<div class='plk_text'>
		<textarea name='content' class='plk_text_nr' placeholder='请输入评论内容 ctrl+enter可快速提交'></textarea>
		<div class='plk_tishi1 hide' >
		<div class='plk_tishi_nrl_01'></div>
		<div class='plk_tishi_nrr_01'><p>用户名 / 密码错误</p><p>请重新输入</p></div>
		</div>
		<div class='plk_tishi2 hide'>
		<div class='plk_tishi_nrl_02'></div>
		<div class='plk_tishi_nrr_02'><p>请先登录再发表</p></div>
		</div>
		<div class='plk_tishi3 hide'>
		<div class='plk_tishi_nrl_03'></div>
		<div class='plk_tishi_nrr_03'><p>已发表到最新回复</p></div>
		</div>
		</div>
		<div class='plk_foot '>".$login."</div>
		<div class='plk_text_mini hide'><textarea name='content' class='box'></textarea><input type='hidden' value='".self::$id."_".$value["infoid"]."' name='c_i'>
		</div>
		</div>";

		echo 'true||##'.$commhtml;
	}
	//提交评论
	public static function pkcomment_submit()
	{
		global $paramarr, $ocomment, $_IGLOBAL;
		$callback = isset($_GET['jsoncallback']) ? $_GET['jsoncallback'] : '';
		
		import('user', 'source');
		$ouser = & IUser::getInstance();
		
		if(!empty($paramarr[6])&&!empty($paramarr[7])){
			if(!$ouser->checkUser(urldecode($paramarr[6]), urldecode($paramarr[7]))){
				$str = 'true||##';
				$str .= 'showmessage(1,"用户名或密码错误");';
				$strreturn = json_encode($str);
				echo $callback."({data:$strreturn})";
				exit;
			}
		}
	
		if(ISysdata::getDataValue('sysconfig', 'comments_purview')){
			// if(!$ouser->isLogin()){
				// $callback = $_GET['jsoncallback'];
				// $str = 'true||##';
				// $str .= 'showmessage(1,"请登录后评论");';
				// $strreturn = json_encode($str);
				// echo $callback."({data:$strreturn})";
				// exit;
			// }
		}
		
		$categoryid = isset($paramarr[0]) ? $paramarr[0] : 0;
		$id = isset($paramarr[1]) ? $paramarr[1] : 0;
		if(!empty($categoryid) && !empty($id)){
			$comments = array();
			$comments['categoryid'] = $categoryid;
			$comments['infoid'] = $id;
			
			//评论信息
			$paramarr[2]  = trim($paramarr[2]);
			$comments['content'] = !empty($paramarr[2]) ? $paramarr[2] : ($str = '请添写留言内容');
			$comments['content'] = shtmlspecialchars($comments['content']);
			$comments['content'] = str_replace(array("\r\n", "\n", "\r"), '<br />', $comments['content']);
			if($comments['content']=='') $str = '请添写留言内容';
			//修改json词语过滤返回 false值
			$comments['content'] = getstr($comments['content'], 2000, 1, 1, 1, -1, 1);
			
			if(!$comments['content']){
				$str = '内容里含有非法字符';
			}
			//2013-5-17 添加 必须含有中文
			if (!preg_match("/[\x{4e00}-\x{9fa5}]/u",$comments['content'])) {
				$str = '请输入有效的评论内容！';
			}
			
			//2013-5-15 添加过滤含有日文的评论
			if(preg_match("/(\xe3(\x82[\xa1-\xbf]|\x83[\x80-\xb6]|\x83\xbc))/",$comments['content']) || preg_match("/(\xe3(\x81[\x81-\xbf]|\x82[\x80-\x93]|\x83\xbc))/",$comments['content'])){
				$str = '内容里含有非法字符';
			}
			
			//2013-3-5 添加评论不能为纯英文或纯数字
			if(!checkstring($comments['content'])){
				$str = '评论不能为纯英文或纯数字！';
			}
			
			//2013-3-26增加IP地址限制
			$userip = getClientIP();
			
			if(!IApk::check_userip($userip)){
				$str = '没有权限';
			}
			$str = '';
			if(empty($str)){
				$address = self::getAddress($userip);
				
				$pkdisablecity = ISysdata::getDataValue('sysconfig', 'pkdisablecity');
				if(!empty($pkdisablecity)){
					if(preg_match('/'.$pkdisablecity.'/i', $address)) {
						$_IGLOBAL['ischeck'] = 0;
					}
				}
			}
			
			//匿名
			$comments['anonymous'] = isset($paramarr[3]) ? $paramarr[3] : 0;
			$comments['type'] =  isset($paramarr[4]) ? $paramarr[4] : 0;
			//2012-2-1
			$ocom=$ocomment->getComment($paramarr[5]);

			$comments['quote']=!empty($ocom['quote'])?($ocom['quote'].','.$paramarr[5]):$paramarr[5];
		
			$comments['ischeck'] = $_IGLOBAL['ischeck'];
		
			//采用截取与与COOKIE记录查看重复提交
			$cutcontent = getstr($comments['content'], 100);
			
			//取得用户信息
			if($ouser->isLogin()){
				$comments['userid'] = $ouser->getUserid();
				$comments['username'] = $ouser->getUserName();
				if(!check_username($comments['username'])){
					$str = '没有权限';
				}
			}
			
			if($comments['anonymous']){
				unset($comments['username']);
			}
			unset($comments['anonymous']);
		
			//记录这次点记写入COOKIE
			$cookiekey = 'comm_-1_'.$id;
			if(isset($_COOKIE[$cookiekey]) && $_COOKIE[$cookiekey] == $cutcontent){
				$str = '请勿重复提交！';
			}else{
				ssetcookie($cookiekey, $cutcontent, 3600);
			}
		
			if(!empty($str)){
				if(!empty($json)){
					$str = 'true||##showmessage(2,"'.$str.'");';
					$strreturn = json_encode($str);
					setcookie('usercontent', '');
					echo $callback."({data:$strreturn})";
					exit;
				}
			}

			//评论写入数据库
			$ocomment->insert($comments);
				
			//更新评论积分
			if($ouser->isLogin()){
				update_usercredit($comments['userid'], $ouser->getUserName(), 'comments');
			}
		
			if(!$_IGLOBAL['ischeck']){
				$checkts = '您的评论正在审核中，请耐心等待';
			}else{
				$checkts = '提交成功';
			}
			
			$str = 'true||##showmessage(3,"'.$checkts.'");$(".plk_text_nr").val("");var url=location.href;url=location.href.split("#");location.href=url[0]';
			$strreturn = json_encode($str);
			setcookie('usercontent', '');
			echo $callback."({data:$strreturn})";
		}
	}
	
	function check_userip($userip){
		$disableip = ISysdata::getDataValue('sysconfig', 'disableip');
		if($disableip && preg_match('/'.$disableip.'/i', $userip)) {
			return false;
		}
		return true;
	}
	function getAddress($ip){
		static $iplocation;
		if(!is_object($iplocation)){
			import('iplocation');
			$iplocation = new IIpLocation();
		}

		$separator = $iplocation->separate(1000);//分成1000块

		$location = $iplocation->getlocation($ip, $separator);//没有分块的查询

		$location['country'] = siconv($location['country'].$location['area'],'utf-8','gb2312');

		return $location['country'];
	}
	
}