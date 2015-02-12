<?php
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
        	

			$db = M();

            @list($this->_userpass, $this->_userid, $this->_cookietime) = explode("\t", authcode($_COOKIE['auth'], 'DECODE'));
			
		    $this->_userid = intval($this->_userid);
            if($this->_userid){
            	
				$query = $db->query("SELECT uid,username,password FROM iiss_session WHERE uid='".$this->_userid."' /*and password='".$this->_userpass."'*/");
				if($query){
					 $this->setParam($query[0]);
				}else{
                     $query = $db->query("SELECT * FROM iiss_member WHERE uid='$this->_userid'/* AND password='$this->_userpass'*/");
					 if($query) {
						$this->_username = addslashes($query['username']);
						 $this->setParam($query[0]);
					 } else {
						 $this->_userid = 0;
					 }
				}
			}
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

}