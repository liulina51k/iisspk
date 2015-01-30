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
       $pkarr = $this->_instance->get_pk();
       $this->assign('pkinfo',$pkarr['info'][0]);
       $this->assign('pklist',$pkarr['list']);
       //$this->assign('goodcomm',$pkarr['goodcomm']);
       //$this->assign('badcomm',$pkarr['badcomm']);
       $this->display();
    }
}