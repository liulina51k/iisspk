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
}