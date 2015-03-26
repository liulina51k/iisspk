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
	    	$pkarr['list'][$k]['pkurl'] = IISSSITE.'/pk/index/'.$v['id'];
	    }
	    
	    import("Components.Comment");
	    $ocomment = new \Comment();//生成评论类对象

	    import("Components.User");
        $ouser = new \User();
        $userid  = $ouser -> getUserid();
        $username   = $ouser -> getUserName();
        
        $nowtime  = getDayAMPM();
        
        //获取论坛中心地址 和 个人用户中心地址
		$bbsurl  = C("BBSURL");
		$userurl = C("USERURL");
		$pkarr['goodcomm'] = $ocomment->getPKReComment(1,$pid, 0);//取得最佳观点
		$pkarr['badcomm']  = $ocomment->getPKReComment(1,$pid, 1);//取得反方最佳观点
		
		//取得图示效果值
		//计算结果
		$pkarr['result'] = $this->pkResult($pid, $pkarr['info']['agreevote'], $pkarr['info']['opposevote']);
		$pkarr['result'] = $pkarr['result']<0 ? 0 : $pkarr['result']>1 ? 1 : $pkarr['result'];
		$data = array('username'=>$username,'userid'=>$userid,'nowtime'=>$nowtime,'bbsurl'=>$bbsurl,'userurl'=>$userurl);
		$pkarr['data'] = $data;
	    return $pkarr;
	}
    /*
     * 取得pk话题列表
     */
    public function pk_list(){
    	global $_IGLOBAL;
        $count = $this->count();// 查询满足要求的总记录数
        $page = new \Think\Page($count,12); //生成tp自带的分页类对象
        $list = $this->order('pubdate desc')->limit($page->firstRow.','.$page->listRows)->select();//取得列表
        
        foreach($list as $k => $v){
        	$list[$k]['point_good'] = floor(($v['agreevote']/($v['agreevote']+$v['opposevote']))*100);
        	$list[$k]['point_bad'] = floor(($v['opposevote']/($v['agreevote']+$v['opposevote']))*100);
        	$list[$k]['pkurl'] = IISSSITE.'/pk/index/'.$v['id'];
        }
        
        $p = I('p',1);//获得p参数的值
        $curpage = $p;
        $showpk  = pkPage($count,12,$curpage,makesiteurl('','pk','plist','{pagenum}'),1);//取得分页url样式
        $showpkt = showPage($count,12,$curpage,makesiteurl('','pkt','plist','{pagenum}'),'pkt');//取得分页url样式
        $refer=explode('/',$_IGLOBAL['refer']);
		$refer=isset($refer['5']) ? IISSSITE.'/pk/index/'.$refer['5'] : $_IGLOBAL['refer'];
        $data = array('showpk'=>$showpk,'showpkt'=>$showpkt,'list'=>$list,'refer'=>$refer);
        return $data;
    }
    /*
     * 取得pk正方评论列表
     */
    public function comment_goodlist($pid){
        $pknowinfo = $this->where("id=$pid")->select();
        $pkoldlist = $this->where("id!=$pid")->order('pubdate desc')->limit(8)->select();
        
        foreach($pkoldlist as $k => $v){
        	$pkoldlist[$k]['point_good'] = floor(($v['agreevote']/($v['agreevote']+$v['opposevote']))*100);
        	$pkoldlist[$k]['point_bad']  = floor(($v['opposevote']/($v['agreevote']+$v['opposevote']))*100);
        	$pkoldlist[$k]['pkurl']      = IISSSITE.'/pk/index/'.$v['id'];
        }
       
        $p = I('p',1);//获得p参数的值
        $curpage = $p;
        if($curpage < 1)
             $curpage = 1;
        $ppp = 5;
        import("Components.Comment");
	    $ocomment = new \Comment();//生成评论类对象
        //正方评论列表
        
		$goodcomm = $ocomment->getPKCommentList(-1, $pid, $ppp, $curpage, 0);
		foreach($goodcomm['artlist'] as $k=>$v){
			$goodcomm['artlist'][$k]['postip'] = self::getAddress($v['postip']);
			if(!empty($v['quote'])){
				$goodcomm['artlist'][$k]['quotehtml'] = self::getQuote($v['quote'], $v['categoryid']);
			}
		}
		
        $goodcount = $ocomment->getCommCount($pid,-1,0);//取得评论总数
        $showpk = showPage($goodcount,$ppp,$curpage,IISSSITE."/pk/app/$pid/{pagenum}",'pkt');
		$showpkt = showPage($goodcount,$ppp,$curpage,IISSSITE."/pkt/app/$pid/{pagenum}",'pkt');
        $data = array('pknowinfo'=>$pknowinfo,'pkoldlist'=>$pkoldlist,'goodcomm'=>$goodcomm,'showpk'=>$showpk,'showpkt'=>$showpkt);
        return $data;
    }
    /*
     * 取得pk反方评论列表
     */
    public function comment_badlist($pid){
        $pknowinfo = $this->where("id=$pid")->select();
        $pkoldlist = $this->where("id!=$pid")->order('pubdate desc')->limit(8)->select();
        
        foreach($pkoldlist as $k => $v){
        	$pkoldlist[$k]['point_good'] = floor(($v['agreevote']/($v['agreevote']+$v['opposevote']))*100);
        	$pkoldlist[$k]['point_bad']  = floor(($v['opposevote']/($v['agreevote']+$v['opposevote']))*100);
        	$pkoldlist[$k]['pkurl']      = IISSSITE.'/pk/index/'.$v['id'];
        }
       
        $p = I('p',1);//获得p参数的值
        $curpage = $p;
        if($curpage < 1)
             $curpage = 1;
        $ppp = 5;
        import("Components.Comment");
	    $ocomment = new \Comment();//生成评论类对象
        //正方评论列表
		$badcomm = $ocomment->getPKCommentList(-1, $pid, $ppp, $curpage, 1);
		foreach($badcomm['artlist'] as $k=>$v){
			$badcomm['artlist'][$k]['postip'] = self::getAddress($v['postip']);
			if(!empty($v['quote'])){
				$badcomm['artlist'][$k]['quotehtml'] = self::getQuote($v['quote'], $v['categoryid']);
			}
		}
		
        $badcount = $ocomment->getCommCount($pid,-1,1);//取得评论总数
		$showpk = showPage($badcount,$ppp,$curpage,IISSSITE."/pk/opp/$pid/{pagenum}",'pkt');
		$showpkt = showPage($badcount,$ppp,$curpage,IISSSITE."/pkt/opp/$pid/{pagenum}",'pkt');
        $data = array('pknowinfo'=>$pknowinfo,'pkoldlist'=>$pkoldlist,'badcomm'=>$badcomm,'showpk'=>$showpk,'showpkt'=>$showpkt);
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
		import("Components.Comment");
	    $ocomment = new \Comment();//生成评论类对象

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