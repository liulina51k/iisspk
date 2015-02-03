<?php
namespace Home\Model;
use Think\Model;

class PkModel extends Model{
      /**
	*  取得PK言论
	*/
	public function get_pk($pid){
	    $pkarr['info'] = $this->where("id=$pid")->select();
	    $pkarr['list'] = $this->where("id!=$pid")->order("pubdate desc")->limit(14)->select();
	    foreach($pkarr['list'] as $k=>$v){
	    	$pkarr['list'][$k]['point_good'] = floor(($v['agreevote']/($v['agreevote']+$v['opposevote']))*100);
        	$pkarr['list'][$k]['point_bad']  = floor(($v['opposevote']/($v['agreevote']+$v['opposevote']))*100);
	    	$pkarr['list'][$k]['pkurl'] = 'http://www.iisspk.com/Pk/index/'.$v['id'];
	    }
	    
	    import("Components.Comment");
	    $ocomment = new \Comment();//生成评论类对象

		$pkarr['goodcomm'] = $ocomment->getPKReComment(1,$pid, 0);//取得最佳观点
		$pkarr['badcomm'] = $ocomment->getPKReComment(1,$pid, 1);//取得反方最佳观点
		
		//取得图示效果值
		//计算结果
		$pkarr['result'] = $this->pkResult($pid, $pkarr['info']['agreevote'], $pkarr['info']['opposevote']);
		$pkarr['result'] = $pkarr['result']<0 ? 0 : $pkarr['result']>1 ? 1 : $pkarr['result'];
		
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
        	$list[$k]['pkurl'] = 'http://www.iisspk.com/Pk/index/'.$v['id'];
        }
        
        $p = I('p',1);//获得p参数的值
        $curpage = $p;
        $show = showPage($count,12,$curpage,makesiteurl('','Pkt','plist','{pagenum}'),'pkt');//取得分页url样式
        $data = array('show'=>$show,'list'=>$list);
        return $data;
    }
    /*
     * 取得pk评论列表
     */
    public function comment_list(){
        //$count = $this->count();// 查询满足要求的总记录数
        $id = I('id');
        $pknowinfo = $this->where("id=$id")->select();
        $pkoldlist = $this->where("id!=$id")->limit(8)->select();
   
        foreach($pkoldlist as $k => $v){
        	$pkoldlistt[$k]['point_good'] = floor(($v['agreevote']/($v['agreevote']+$v['opposevote']))*100);
        	$pkoldlist[$k]['point_bad'] = floor(($v['opposevote']/($v['agreevote']+$v['opposevote']))*100);
        	$pkoldlist[$k]['pkurl'] = 'http://www.iisspk.com/Pk/index/'.$v['id'];
        }
        
        $p = I('p',1);//获得p参数的值
        $curpage = $p;
        $ppp = 5;
        import("Components.Comment");
	    $ocomment = new \Comment();//生成评论类对象
        //正方评论
		$goodcomm = $ocomment->getPKCommentList(-1, $id, $ppp, $curpage, 0);

		foreach($goodcomm['artlist'] as $k=>$v){
			$goodcomm['artlist'][$k]['postip'] = self::getAddress($v['postip']);
			if(!empty($v['quote'])){
				$goodcomm['artlist'][$k]['quotehtml'] = self::getQuote($v['quote'], $v['categoryid']);
			}
		}
       
        $goodcount = $ocomment->getCommCount($id,-1,0);
		$show = showPage($goodcount,5,$curpage, makesiteurl('','Pkt','app','{pagenum}'),'pkt');
        $data = array('pknowinfo'=>$pknowinfo,'pkoldlist'=>$pkoldlist,'goodcomm'=>$goodcomm,'show'=>$show);
        return $data;
    }
    public function getAddress($ip){
		import("Components.IpLocation");
	    $iplocation = new \IpLocation();//生成ip类对象
	    
		$separator = $iplocation->separate(1000);//分成1000块
	
		$location = $iplocation->getlocation($ip, $separator);//没有分块的查询
	
		$location['country'] = siconv($location['country'].$location['area'],'utf-8','gb2312');
	
		return $location['country'];
   }
   public function getQuote($quoteid, $categoryid)
	{
		global $ocomment;

		$quotehtml = '';
		$quote=explode(',',$quoteid);
		foreach($quote as $key=>$iterm){
			$value = $ocomment->getComment($iterm);
			if(!empty($value)){
				$quotehtml = '<h3 class="h3"><p>引用：'.$value['username'].'</p><p>'.str_replace(array("\r\n", "\n", "\r"), '<br />', $value['content']).'</p></h3>';
			}
		}

		return $quotehtml;
	}
	public function pkResult($id, $agreevote = -1, $opposevote = -1)
	{
	   import("Components.Comment");
	   $ocomment = new \Comment();//生成评论类对象

       if($agreevote<0 || $opposevote<0){
			$agreevote = $this->where("id=$id")->getField('agreevote');
			$opposevote = $this->where("id=$id")->getField('opposevote');
	   }

	   //PK的正文评论数
	   $agrcount = $ocomment->getCommCount($id, -1, 0);
	   //取得PK的反方评论数
	   $oppcount = $ocomment->getCommCount($id, -1, 1);

	   return 0.5 + (($agreevote+$agrcount*35)-($opposevote+$oppcount*35)) / 5600;
	}
}