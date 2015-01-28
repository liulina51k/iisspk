<?php
namespace Home\Controller;
use Think\Controller;
class PkController extends Controller {
    private $_instance;
    public function __construct() {
        parent::__construct();
        $this->_instance = D('Pk');
    }
    public function pk_home(){
       $arr = $this->_instance->get_pk();
       $this->display();
    }
    public function pk_list(){
       $data = $this->_instance->pk_list();
       $this->assign('show',$data['show']);
       $this->assign('list',$data['list']);
       $this->display();
    }
    public function pk_comment(){
       $this->display();
    }
    public function pk_ajax_vote(){
    	$json = I('json','');
    	$param = I('parameter');
    	$paramarr = explode('_', $param);
        $id = !empty($paramarr[0]) ? intval($paramarr[0]) : 0;
    	$voteip = getClientIP();
        $ckey = 'voteip'.$id;
		 
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
        printr(1);
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
}