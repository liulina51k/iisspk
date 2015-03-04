<?php
namespace Home\Controller;
use Think\Controller;
class PktController extends Controller {
    private $_instance;
    public function __construct() {
       parent::__construct();
       $this->_instance = D('Pk');
    }
    //pk台列表页
    public function plist(){
       $data = $this->_instance->pkt_list();
       $this->assign('show',$data['show']);
       $this->assign('list',$data['list']);
       $this->display();
    }
    //正方评论列表
    public function app(){
       $data = $this->_instance->comment_goodlist();
       $this->assign('pknowinfo',$data['pknowinfo'][0]);
       $this->assign('pkoldlist',$data['pkoldlist']);
       $this->assign('goodcomm',$data['goodcomm']['artlist']);
       $this->assign('show',$data['show']);
       $this->display();
    }
    //反方评论列表
    public function opp(){
       $data = $this->_instance->comment_badlist();
       $this->assign('pknowinfo',$data['pknowinfo'][0]);
       $this->assign('pkoldlist',$data['pkoldlist']);
       $this->assign('badcomm',$data['badcomm']['artlist']);
       $this->assign('show',$data['show']);
       $this->display();
    }
    //pk规则说明
    public function help(){
	  $refer = I("server.HTTP_REFERER");//获得请求来源
      $this->assign('refer',$refer);
	  $this->assign('site',$site);
	  $this->display();
    }
}