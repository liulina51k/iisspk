<?php
namespace Components\Source;
/**
* user.clase.php 网站用户类
* 此类针对表iiss_member的操作，取得与用户有关的数据
* @writer: kelly
* @time: 2009-5-15
*/

class User
{
	//用户ID
    protected $_userid = 0;

    //用户名
	protected $_username = '';

    //用户密码
	private $_userpass;

	//用户在线时间
	private $_cookietime;

    //构造函数
	public function __construct()
	{
        if(!empty($_COOKIE['auth'])){

			$db = & IFactory::getDB();

            @list($this->_userpass, $this->_userid, $this->_cookietime) = explode("\t", authcode($_COOKIE['auth'], 'DECODE'));
			
		    $this->_userid = intval($this->_userid);
            if($this->_userid){
				$query = $db->query("SELECT uid,username,password FROM iiss_session WHERE uid='".$this->_userid."' /*and password='".$this->_userpass."'*/");
				if($member = $db->fetch_array($query)){
					 $this->setParam($member);
				}else{
                     $query = $db->query("SELECT * FROM iiss_member WHERE uid='$this->_userid'/* AND password='$this->_userpass'*/");
					 if($member = $db->fetch_array($query)) {
						$this->_username = addslashes($member['username']);
						$this->updateSession(array('uid' =>$this->_userid, 'username' => $this->_username, 'password' => $this->_userpass));//登录
						 $this->setParam($member);
					 } else {
						 $this->_userid = 0;
					 }
				}
			}

			if(empty($this->_userid)) {
				$this->quit();
			}
        }

	}

	/**
    * 获得单一实例
    */
    public static function getInstance()
    {
         static $instance;

	     if(!is_object($instance)){
	        $instance = new IUser();
	     }

	     return $instance;
    }
	
    /**
	* 判断用户是否登陆
	*/
	public function isLogin()
	{
		//有UID 并且大于0  则说明已登录，此时把信息同步登录到UC中
		if($this->_userid > 0){
			
			//若不不能包含uc客户端文件，则提示系统错误!
			if(!@include_once I_ROOT.'/uc_client/client.php') {
				showmessage('系统错误');
			}
			//实现UC同步登录
			return uc_user_synlogin($this->_userid);
			
		//还未登录
		}else{
            return false;
		}
	}

	/**
	* 设置用户参数
	* @param array $member: 管理员数据数组
	*/
	public function setParam($member)
	{	
		$this->_userid = $member['uid'];
		$this->_username = $member['username'];
		$this->_userpass = $member['password'];
	}
	
	/**
	 * 外部登录用户验证
	 * 
	 * @param int $conopenid 唯一标识
	 * @param string $type 	  类型
	 */
	public function ocheckUser( $conopenid, $type, $username='', $avatar='' ) {
		global $_IGLOBAL;
		
		if ( empty( $conopenid ) || empty( $type ) ) {
			return false;
		}

		if(!include_once I_ROOT.'/uc_client/client.php') {
			showmessage('系统错误');
		}
		
		//根据传递过来的类型，查询相应的OPENID
		$ucresult = uc_user_ologin( $conopenid, $type, $username, $avatar );
		//如果存在,用户放入到用户信息中
		if ( $ucresult[0] > 0 ) {	
			
				$passport['uid'] = $ucresult[0];
				$passport['username'] = $ucresult[1];
				$passport['email'] = $ucresult[3];
				$passport['conopenid'] = $ucresult[4];
			
		//如果不存在  返回FALSE
		} else {
			return false;
		}

		$setarr = array(
			'uid' => $passport['uid'],
			'username' => addslashes($passport['username']),	
			'password' => md5("$passport[uid]|$_IGLOBAL[timestamp]"), //本地密码随机生成
			'ip'=>getClientIP()
	     );
	     
	    $db = & IFactory::getDB();
		$query = $db->query("SELECT password FROM iiss_member WHERE uid='$setarr[uid]'");
		if($value = $db->fetch_array($query)) {
			$setarr['password'] = addslashes($value['password']);
		} else {
			$setarr['logintime'] = time();
			$db->inserttable('iiss_member', $setarr, 0, true);
		}
		$this->setParam($setarr);
		unset($setarr['logintime']);
		$this->updateSession($setarr);
		$this->setCookie();
		$login_scores = ISysdata::getDataValue('sysconfig', 'login_scores'); //登陆积分 
			$login_money = ISysdata::getDataValue('sysconfig', 'login_money'); //登陆金钱		
			if(import('member', 'source')){
				$omem = & IMember::getInstance();
				//1天登录一次进行金钱经验加分2011-9-22
				if(date('Y/m/d', $_IGLOBAL['timestamp'])<>date('Y/m/d', $this->_cookietime)){
					if($login_scores>0){
						 $omem->updateCredit(1, $login_scores, $setarr['uid'], $setarr['username']);
					}
	
					if($login_money>0){
						 $omem->updateCredit(3, $login_money, $setarr['uid'], $setarr['username']);
					}
				}
			}
			
		include_once I_ROOT.'/uc_client/client.php';
	    return uc_user_synlogin($setarr['uid']);
	}
	
	/**
	* 验证登陆用户   需要用户名跟密码
	*/
	public function checkUser($username, $password)
	{
		global $_IGLOBAL;

		//如果用户名或者密码为空返回FALSE
		if(empty($username) || empty($password)){
		    return false;
		}

		//同步获取用户源
		//if(!$passport = $this->getpassport($username, $password)) {
		    //exit('同步信息出错');
		//}

		if(!@include_once I_ROOT.'/uc_client/client.php') {
			showmessage('系统错误');
		}
		
		//UC用户登录检查方法  返回一个数组，>0为成功代表用户id，-1为用户不存在，-2密码错误
		$ucresult = uc_user_login($username, $password);
		
		//如果有此用户 把用户的信息放到PASSPORT数组中
		if($ucresult[0] > 0) {
			$passport['uid'] = $ucresult[0];
			$passport['username'] = $ucresult[1];
			$passport['email'] = $ucresult[3];

		//如果没有，则返回FALSE
		}else{	
			return false;
		}
		
		//把获取到的个人信息重新整理，放到$setarr数组中
		$setarr = array(
			'uid' => $passport['uid'],
			'username' => addslashes($passport['username']),	
			'password' => md5("$passport[uid]|$_IGLOBAL[timestamp]")
	     );
		
	    //获取数据库操作类
		$db = & IFactory::getDB();
		
		//检索当前用户	在iiss_member表中查询 UID对应的密码
		$query = $db->query("SELECT password FROM iiss_member WHERE uid='$setarr[uid]'");
		
		//如果有值，则密码不改变
		if($value = $db->fetch_array($query)) {
			$setarr['password'] = addslashes($value['password']);
		} else {
			$setarr['ip'] = getClientIP();
			$setarr['logintime'] = time();
			//如果没值，说明用户不在本地的iiss_member表里，把通过uc_member表获取的信息插入到iiss_member表里
			//更新本地用户库  如果数据库中没有这个密码，则更新本地数据密码
			$db->inserttable('iiss_member', $setarr, 0, true);
		}
		
        //更新会员类变量 设置UID 帐号 密码
		$this->setParam($setarr);

		//清理在线session
		unset($setarr['logintime']);
	    $this->updateSession($setarr);

		//设置COOCKIE
	    $this->setCookie();
		
		//用户登录更新积分 2012-1-31 修改
		update_usercredit($setarr['uid'], $setarr['username'], 'login');

		//同步登录
	    include_once I_ROOT.'/uc_client/client.php';
	    return uc_user_synlogin($setarr['uid']);
	}

	/**
	* 获取用户数据
	*/
	public function getPassPort($username, $password)
	{
		$passport = array();
		if(!@include_once I_ROOT.'/uc_client/client.php') {
			showmessage('系统错误');
		}

		$ucresult = uc_user_login($username, $password);
		if($ucresult[0] > 0) {
			$passport['uid'] = $ucresult[0];
			$passport['username'] = $ucresult[1];
			$passport['email'] = $ucresult[3];
		}
		return $passport;
	}
	
	/**
	 * 获取用户数据  （网站用户数据）
	 */
	public function getUser($username, $password){
		if(!@include_once I_ROOT.'/uc_client/client.php') {
			showmessage('系统错误');
		}
		
		$ucresult = uc_user_login($username, $password);		

		$db = & IFactory::getDB();
		$query = $db->query("select * from iiss_admin where uid = '$ucresult[0]'");
		$data = $db->fetch_array($query);

		return $data;
	
	}	

	/**
	* 更新在线session
	*/
	public function updateSession($setarr)
	{
        global $_IGLOBAL;
        $db = & IFactory::getDB();     
		$onlinetime = & ISysdata::getDataValue('sysconfig', 'onlinetime');
		if($onlinetime < 300) $onlinetime = 300;
		$query = $db->query("SELECT lastactivity FROM iiss_session WHERE uid='$setarr[uid]'");
		$val = $db->fetch_array($query);
		if($val){
			$this->_cookietime = $val['lastactivity'];
		}else{
			$this->_cookietime = $_IGLOBAL['timestamp'];
		}
		$db->query("DELETE FROM iiss_session WHERE uid='$setarr[uid]' OR lastactivity<'".($_IGLOBAL['timestamp']-$onlinetime)."'");

		//添加在线
		$setarr['lastactivity'] = $_IGLOBAL['timestamp'];
		$setarr['ip'] = getClientIP();
		$db->inserttable('iiss_session', $setarr, 0, true);

	 }

	/**
	* 获得用户ID
	*/
	public function getUserid()
	{
	    return $this->_userid;
	}

	/**
	* 获得用户名
	*/
	public function getUserName()
	{
	    return $this->_username;
	}

	/**
	* 设置会员登陆COOKIE
	*/
	public function setCookie(){
		global $_IGLOBAL;
		//取得系统设置参数
		$onlinetime = ISysdata::getDataValue('sysconfig', 'onlinetime');
        //设置cookie
	    ssetcookie('auth', authcode($this->_userpass."\t".$this->_userid."\t".$this->_cookietime."\t".$this->_username, 'ENCODE'), $onlinetime);
	}

	/**
	* 后退出时做一些操作
	*/
	public function quit(){
        ssetcookie('auth', '');
		$this->_userid = 0;

		//同步登录
	    include_once I_ROOT.'/uc_client/client.php';
	    return uc_user_synlogout();
	}

	/**
	* 获得会员名字
	*/
	public function getMember($uid, $field='')
	{
		  $db = & IFactory::getDB();
		  $query = $db->query("SELECT * FROM iiss_member WHERE uid='$uid'");
		  if(!$member = $db->fetch_array($query)) {
		      return false;
		  }

		  return $field ? $member[$field] : $member;
	}
	
	/**
	* 检查会员的操作权限
	*/
	public function isAllow($do){
	    static $typearr;
		if(empty($typearr)){
			//获取用户组
			$db = & IFactory::getDB();
			$query = $db->query("SELECT uid,usertype,username,password FROM iiss_admin WHERE username='{$this->_username}'");
			if(!$admin = $db->fetch_array($query)){
				return false;
			}
			import('admintype', 'source');
			$objtype = IAdmintype::getInstance();

			$typearr = $objtype->getType($admin['usertype']);
			$typearr = $typearr['purviews'];
		}

		if(in_array('supeadmin', $typearr) || in_array($do, $typearr)){
             return true;		
  		}else{
		     return false;
		}


	}

    /**
    * 获得authcookie
    * 
    */
    public function getAuth(){
        return authcode($this->_userpass."\t".$this->_userid."\t".$this->_cookietime."\t".$this->_username, 'ENCODE');
    }
	
	public function checkIP(){
		$db = & IFactory::getDB();
		$ip = getClientIP();
		$parttime = time()-3600;
		$query = $db->query("SELECT * FROM iiss_member WHERE ip='$ip' and logintime>$parttime");
		$value = $db->fetch_array($query);
		if($value){
			return true;
		}else{
			return false;
		}
		
	}
}