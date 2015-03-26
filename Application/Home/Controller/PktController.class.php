<?php
namespace Home\Controller;
use Think\Controller;
class PktController extends Controller {
    private $_instance;
    public function __construct() {
       parent::__construct();
       $this->_instance = D('Pk');
    }
    //pkt台首页
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
           $this->display("Pk/index");
       else 
           $this->display("Pk/index2");
    }
    //pkt台列表页
    public function plist(){
       $data = $this->_instance->pk_list();
       $this->assign('show',$data['showpkt']);
       $this->assign('list',$data['list']);
       $this->assign('refer',$data['refer']);
       $this->display();
    }
    //正方评论列表
    public function app(){
       $pid = I('id');
       $data = $this->_instance->comment_goodlist($pid);
       $this->assign('pknowinfo',$data['pknowinfo'][0]);
       $this->assign('pkoldlist',$data['pkoldlist']);
       $this->assign('goodcomm',$data['goodcomm']['artlist']);
       $this->assign('show',$data['showpkt']);
       if($pid > 223)
           $this->display();
       else 
           $this->display("Pk/app");
    }
    //反方评论列表
    public function opp(){
       $pid = I('id');
       $data = $this->_instance->comment_badlist($pid);
       $this->assign('pknowinfo',$data['pknowinfo'][0]);
       $this->assign('pkoldlist',$data['pkoldlist']);
       $this->assign('badcomm',$data['badcomm']['artlist']);
       $this->assign('show',$data['showpkt']);
       if($pid > 223)
           $this->display();
       else 
           $this->display("Pk/opp");
    }
    //pkt规则说明
    public function help(){
	  $refer = I("server.HTTP_REFERER");//获得请求来源
      $this->assign('refer',$refer);
	  $this->assign('site',$site);
	  $this->display();
    }
}