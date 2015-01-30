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
       $data = $this->_instance->pk_list();
       $this->assign('show',$data['show']);
       $this->assign('list',$data['list']);
       $this->display();
    }
    //正方评论列表
    public function app(){
      $data = $this->_instance->comment_list();
      $this->assign('show',$data['show']);
      $this->assign('list',$data['list']);
      $this->display();
    }
    //反方评论列表
    public function opp(){
    	
    }
}