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
       $this->assign('pkinfo',$pkarr['info'][0]);
       $this->assign('pklist',$pkarr['list']);
       $this->assign('goodcomm',$pkarr['goodcomm']);
       $this->assign('badcomm',$pkarr['badcomm']);
       $this->assign('result',$pkarr['result']);
       $this->assign('data',$pkarr['data']);
       if($pid > 223)
           $this->display();
       else 
           $this->display("index2");
    }
    //pk台列表页
    public function plist(){
       $data = $this->_instance->pk_list();
       $this->assign('show',$data['showpk']);
       $this->assign('list',$data['list']);
       $this->display();
    }
    //正方评论列表
    public function app(){
       $pid = I('id');
       if($pid <= 223){
	       $pknowinfo = $this->_instance->where("id=$pid")->select();
	       $this->assign('pknowinfo',$pknowinfo[0]);
           $this->display();
       }else{
       	   $data = $this->_instance->comment_goodlist($pid);
	       $this->assign('pknowinfo',$data['pknowinfo'][0]);
	       $this->assign('pkoldlist',$data['pkoldlist']);
	       $this->assign('goodcomm',$data['goodcomm']['artlist']);
	       $this->assign('show',$data['showpk']);
           $this->display("app2");
       }
    }
    //反方评论列表
    public function opp(){
       $pid = I('id');
       if($pid <= 223){
       	   $pknowinfo = $this->_instance->where("id=$pid")->select();
           $this->assign('pknowinfo',$pknowinfo[0]);
           $this->display();
       }else{
       	   $data = $this->_instance->comment_badlist($pid);
	       $this->assign('pknowinfo',$data['pknowinfo'][0]);
	       $this->assign('pkoldlist',$data['pkoldlist']);
	       $this->assign('badcomm',$data['badcomm']['artlist']);
	       $this->assign('show',$data['showpk']);
           $this->display("opp2");
       }
    }
    //pk规则说明
    public function help(){
    	$refer = I("server.HTTP_REFERER");//获得请求来源
    	$this->assign('refer',$refer);
    	$this->display();
    }
}