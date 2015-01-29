<?php
namespace Home\Model;
use Think\Model;

class PkModel extends Model{
      /**
	*  取得PK言论
	*/
	public function get_pk(){
		$pid = I('pid');
	    $pkarr['info'] = $this->where("id=$pid")->select();
	    $pkarr['list'] = $this->where("id!=$pid")->order("pubdate")->limit(14)->select();
	    foreach($pkarr['list'] as $k=>$v){
	    	$pkarr['list'][$k]['point_good'] = floor(($v['agreevote']/($v['agreevote']+$v['opposevote']))*100);
        	$pkarr['list'][$k]['point_bad']  = floor(($v['opposevote']/($v['agreevote']+$v['opposevote']))*100);
	    	$pkarr['list'][$k]['pkurl'] = 'http://www.iisspk.com/index.php/Home/Pk/pk_home/pid/'.$v['id'];
	    }
	   //printr($pkarr);
	    return $pkarr;
	}
    /*
     * 取得pk话题列表
     */
    public function pk_list(){
        $count = $this->count();// 查询满足要求的总记录数
        $page = new \Think\Page($count,12); //生成tp自带的分页类对象
        $list = $this->order('pubdate')->limit($page->firstRow.','.$page->listRows)->select();//取得列表
        
        foreach($list as $k => $v){
        	$list[$k]['point_good'] = floor(($v['agreevote']/($v['agreevote']+$v['opposevote']))*100);
        	$list[$k]['point_bad'] = floor(($v['opposevote']/($v['agreevote']+$v['opposevote']))*100);
        	$list[$k]['pkurl'] = 'http://www.iisspk.com/index.php/Home/Pk/pk_home/pid/'.$v['id'];
        }
        
        $p = I('p',1);//获得p参数的值
        $curpage = $p;
        $show = showPage($count,12,$curpage,makesiteurl('html','Home','Pk','pk_list','{pagenum}'),'pkt');//取得分页url样式
        $data = array('show'=>$show,'list'=>$list);
        return $data;
    }
    public function ajax_pkvote(){
    	printr(1);
    }
}