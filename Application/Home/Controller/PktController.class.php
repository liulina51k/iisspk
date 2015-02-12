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
       global $site,$iisssite,$attpath;
       $data = $this->_instance->pk_list();
       $this->assign('site',$site);
       $this->assign('attpath',$attpath);
       $this->assign('iisssite',$iisssite);
       $this->assign('show',$data['show']);
       $this->assign('list',$data['list']);
       $this->display();
    }
    //正方评论列表
    public function app(){
       global $site;
       $data = $this->_instance->comment_goodlist();
       $this->assign('site',$site);
       $this->assign('pknowinfo',$data['pknowinfo'][0]);
       $this->assign('pkoldlist',$data['pkoldlist']);
       $this->assign('goodcomm',$data['goodcomm']['artlist']);
       $this->assign('show',$data['show']);
       $this->display();
    }
    //反方评论列表
    public function opp(){
       global $site;
       $data = $this->_instance->comment_badlist();
       $this->assign('site',$site);
       $this->assign('pknowinfo',$data['pknowinfo'][0]);
       $this->assign('pkoldlist',$data['pkoldlist']);
       $this->assign('badcomm',$data['badcomm']['artlist']);
       $this->assign('show',$data['show']);
       $this->display();
    }
    //pk规则说明
    public function help(){
	  global $site;
	  $refer = I("server.HTTP_REFERER");//获得请求来源
      $this->assign('refer',$refer);
	  $this->assign('site',$site);
	  $this->display();
    }
}