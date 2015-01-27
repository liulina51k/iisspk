<?php
namespace Home\Model;
use Think\Model;
class PkModel extends Model{
      /**
	*  取得PK言论
	*/
	public function get_pk(){
	    $pkarr = $this->select();
	    printr($pkarr);
            return $field ? $pkarr[$field] : $pkarr;
	}
        /*
         * 取得pk话题列表
         */
        public function pk_list(){
            $count = $this->count();// 查询满足要求的总记录数
            $page = new \Think\Page($count,6);
            $show = $page->show();
            $list = $this->order('pubdate')->limit($page->firstRow.','.$page->listRows)->select();
            $data = array('show'=>$show,'list'=>$list);
            return $data;
        }
}