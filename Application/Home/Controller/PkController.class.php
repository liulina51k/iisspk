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
    public function index(){
       $pid = I('id');
       $pkarr = $this->_instance->get_pk($pid);
       $this->assign('site','http://www.iisspk.com');
       $this->assign('pkinfo',$pkarr['info'][0]);
       $this->assign('pklist',$pkarr['list']);
       $this->assign('goodcomm',$pkarr['goodcomm']);
       $this->assign('badcomm',$pkarr['badcomm']);
       $this->assign('result',$pkarr['result']);
       if($pid > 223)
           $this->display();
       else 
           $this->display("index2");
    }
    //pk规则说明
    public function help(){
    	$refer = I("server.HTTP_REFERER");//获得请求来源
    	$this->assign('refer',$refer);
    	$this->display();
    }
}