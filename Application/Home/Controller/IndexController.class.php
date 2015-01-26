<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
       $objpk = M();
       $arr = $objpk->select();
       printr($arr);
    }
}