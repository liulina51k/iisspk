<?php
namespace Home\Controller;
use Think\Controller;
class PkController extends Controller {
    private $_instance;
    public function __construct() {
        parent::__construct();
        $this->_instance = D('Pk');
    }
    //pk台首页
    public function pk_home(){
       $pkarr = $this->_instance->get_pk();
       $this->assign('pkinfo',$pkarr['info'][0]);
       $this->assign('pklist',$pkarr['list']);
       $this->display();
    }
    //pk台列表页
    public function pk_list(){
       $data = $this->_instance->pk_list();
       $this->assign('show',$data['show']);
       $this->assign('list',$data['list']);
       $this->display();
    }
    //pk台评论列表页
    public function pk_comment(){
       $this->display();
    }
    //ajax请求的地址（投票功能）
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
				$callback = I('jsoncallback');
				$strreturn = json_encode('true||##你已经投过票了');
				$callback = $callback."({data:$strreturn})";
			 }else{
				echo '你已经投过票了';
			 }
			 exit;
		}else{
		     ssetcookie($ckey, $voteip, 3600);
		}
		if(!empty($json)){
			$callback = I('jsoncallback');
			$strreturn = json_encode('true||##');
			$callback = $callback."({data:$strreturn})";
		 }else{
			$callback = '';
		 }

		$votefield = $paramarr[1]=='agree' ? 'agreevote' : 'opposevote';
        $this->_instance->where("id=$id")->setInc($votefield,1);

		echo $callback;
		exit;
    }
    //pk台登录
    public function pk_ajax_login()
    {   
    	$json = I('json','');
    	$param = I('parameter');
    	$param = urldecode($param);
    	$paramarr = explode('_',$param);
        //是否返回json数据
         if ( !empty($json) ) {
             $callback = $_GET['jsoncallback'];
             echo $callback;
         } else {
             echo 'true||##';
         }
      
         $str = '';
         $logininfo = '';
         $ouser = new \Components\Source\User();
         printr($ouser);
         if ( $str = $ouser -> checkUser( $paramarr[0], $paramarr[1] ) ) {
             import('subdate');
            $odate = new ISubdate;
            $str .='<p class="log_in">'.$odate -> getDayAMPM().'好:<a class="redcolor">'.$ouser -> getUserName().'</a><a href="javascript:loginout(\'com_login\');" class="blueonline">退出</a><a class="bbs" href="'.IConfig::BBSURL.'">进入论坛</a>
                    <a class="member" href="'.IConfig::USERURL.'/home/">进入会员中心</a></p>';
         } else {
             $logininfo .= '用户名或密码错误';
         }

        if ( !empty( $json ) ) {
             $callback = $_GET['jsoncallback'];
             $str = json_encode($str);
             $logininfo = json_encode( $logininfo );
             echo "({data:$str,logininfo:$logininfo})";
         } else {
             echo $str;
         }
    }
}