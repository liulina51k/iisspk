<?php
namespace Home\Controller;
use Think\Controller;
class PkController extends Controller {
    public function pk_home(){
       $objpk = D('Pk');
       //$arr = $objpk->get_pk();
       $this->display();
    }
    public function pk_list(){
       $this->display();
    }
    public function pk_comment(){
       $this->display();
    }
}