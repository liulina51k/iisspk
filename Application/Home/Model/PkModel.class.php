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
        $page = new \Think\Page($count,12);
        $style="
       <div class='xy'>
			<div class='pages_fy'>
			<div class='pages'>
			%FIRST%
			%UP_PAGE%
			%LINK_PAGE%			
			%DOWN_PAGE%
			%END%
			</div></div>
		</div>";
		$page->lastSuffix=false;//定义尾页不为总数
		$page->setConfig('first','首页');
        $page->setConfig('last','尾页');
        $page->setConfig('next','下一页');
        $page->setConfig('prev','上一页');
        $page->setConfig('theme',$style);
        $show = $page->show();
        $list = $this->order('pubdate')->limit($page->firstRow.','.$page->listRows)->select();
        foreach($list as $k => $v){
        	$list[$k]['point_good'] = floor(($v['agreevote']/($v['agreevote']+$v['opposevote']))*100);
        	$list[$k]['point_bad'] = floor(($v['opposevote']/($v['agreevote']+$v['opposevote']))*100);
        	$list[$k]['pkurl'] = 'http://www.iisspk.com/index.php/Home/Pk/pk_home';
        }
        
        $page = new \Think\Page($count,6);
        
       /* $pkid = $this -> getField('id');
        $curpage = $pkid ? $pkid : 1;
        $show = showPage($count,12,$curpage,makesiteurl('html','Home','Pk','pk_list',$curpage));
        printr($show);*/
        $data = array('show'=>$show,'list'=>$list);
        return $data;
    }
    public function ajax_pkvote(){
    	printr(1);
    }
}