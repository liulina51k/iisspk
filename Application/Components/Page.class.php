<?
namespace Components;

class Page
{
	 /**
	 *  文章正文页分页
	 */
	 public function artMutiPage($num, $curpage, $url)
	 {
		 $mutipage = '';
		 $perpage = 1;

		 if($num > $perpage){

			$page = 10;
			$offset = $page/2;
			$pages = @ceil($num / $perpage);

			if($page > $pages){
			    $start=1;
				$total=$pages;
			}else{
				$start = $curpage - $offset;
				$total = $start + $page -1;

				if($start < 1){
					$total = $curpage +1 - $start;
					$start = 1;
					if($total - $start < $page){
					    $total = $page;
					}
				}elseif($total > $pages){
					$start = $pages - $page + 1;
					$total = $pages;
				}

			}


			 if($curpage>1){
				$mutipage .= '<a href="'.(($curpage-2) > 1 ? str_replace('.html', '_'.($curpage-2).'.html',$url) :$url).'"><<上一页</a>';
			 }

			 $mutipage .=  '<span id="pagenum">'.$this->pageNumList($start, $total, $curpage, $url, 'nowpage').'</span>';

			if($curpage < $total){

			   $mutipage .= '<a href="'.str_replace('.html', '_'.$curpage.'.html',$url).'">下一页>></a>';
			}
		 }

		return $mutipage;

	 }

	 /**
	 * 正文数字列
	 */
	 public function pageNumList($start, $total, $cur, $url, $curstyle='', $linkstyle = '', $linktag='', $num=0)
	 {
         $numlist = '';
		 for($i=$start; $i<=$total; $i++){
             $cururl = ($i-1<=0) ?  $url : str_replace('.html', '_'.($i-1).'.html', $url);
			 if($i==$num){
				 $cururl = str_replace('#pic','',$cururl);
			 }
             $numlist .= '<a target="_self" href="';
             $numlist .= $cur == $i && !empty($curstyle) ? '#" class="'.$curstyle.'"' : $cururl.'"'.(!empty($linkstyle) ? ' class="'.$linkstyle.'"' : '');
			 $numlist .= '">'.$i.'</a>$nbsp;';
		 }

         return $numlist;
	 }


     /**
	 * 列表数字列
	 */
	 public function pageListNum($start, $total, $cur, $url, $curstyle='', $linkstyle = '', $linktag='')
	 {
         $numlist = '';
		 for($i=$start; $i<=$total; $i++){
             $cururl =  str_replace('.html', '/'.$i.'.html', $url);
             $numlist .= '<a href="';
             $numlist .= $cur == $i && !empty($curstyle) ? '#" class="'.$curstyle.'"' : $cururl.'"'.(!empty($linkstyle) ? ' class="'.$linkstyle.'"' : '');
			 $numlist .= '>'.$i.'</a>';
		 }

         return $numlist;
	 }
	 /**
	 * 评论数字列
	 */
	 public function pageNumComm($start, $total, $cur, $url, $curstyle='', $linkstyle = '', $linktag='')
	 {
         $numlist = '';
		 for($i=$start; $i<=$total; $i++){
             $cururl =  str_replace('{pagenum}', ''.$i, $url);
             $numlist .= '<a href="';
             $numlist .= ($cur == $i && !empty($curstyle)) ? '#" class="'.$curstyle.'"' : $cururl.'"'.(!empty($linkstyle) ? ' class="'.$linkstyle.'"' : '');
			 $numlist .= '>'.$i.'</a>';
		 }

         return $numlist;
	 }
	 /**
	 * 列表分页
	 */
	 public function artListMutiPage($num, $perpage, $curpage, $url)
	 {
         $mutipage = '';

		 if($num > $perpage){

			$page = 10;
			$offset = $page/2;
			$pages = @ceil($num / $perpage);

			if($page > $pages){
			    $start=1;
				$total=$pages;
			}else{
				$start = $curpage - $offset;
				$total = $start + $page -1;

				if($start < 1){
					$total = $curpage +1 - $start;
					$start = 1;
					if($total - $start < $page){
					    $total = $page;
					}
				}elseif($total > $pages){
					$start = $pages - $page + 1;
					$total = $pages;
				}

			}

			if($curpage>1){
				$mutipage .= '<a href="'.str_replace('.html', '/'.($curpage-1).'.html',$url).'"><<上一页</a>';
			}

			$mutipage .=  '<span id="pagenum">'.$this->pageListNum($start, $total, $curpage, $url, 'nowpage').'</span>';

			if($curpage < $total){

			   $mutipage .= '<a href="'.str_replace('.html', '/'.($curpage+1).'.html',$url).'">下一页>></a>';
			}

		 }else{
			$multipage = 1;
		 }

		return $mutipage;

	 }

	 /**
	 *  图片正文页分页
	 */
	 public function imgMutiPage($num, $curpage, $url)
	 {

         $mutipage = '';
		 $perpage = 1;

		  if($num > $perpage){

			$page = 10;
			$offset = $page/2;
			$pages = @ceil($num / $perpage);

			if($page > $pages){
			    $start=1;
				$total=$pages;
			}else{
				$start = $curpage - $offset;
				$total = $start + $page -1;

				if($start < 1){
					$total = $curpage +1 - $start;
					$start = 1;
					if($total - $start < $page){
					    $total = $page;
					}
				}elseif($total > $pages){
					$start = $pages - $page + 1;
					$total = $pages;
				}

			}
	//{$prefolder} 图片路径，用于不同图片展示
	        $mutipage .= '<a href="$prevpage"><img height="27px" class="button" src="'.IConfig::BASE.'/images/{$prefolder}pic_pr.jpg" /></a>';

			$mutipage .=  $this->pageNumList($start, $total, $curpage, $url, 'pagefonts', 'pagefont', '', $num);

			$mutipage .= '<a href="$nextpage"><img height="27px" class="button" src="'.IConfig::BASE.'/images/{$prefolder}pic_ne.jpg" /></a>'; 


		 }else{
			$multipage = 1;
		 }
	 
		 

		return $mutipage;

	 }


	 /**
	 * 评论页分页
	 * param int $total：为记录数
	 */
	 public function commentsPage($num, $perpage, $curpage, $url)
	 {

		 $mutipage = '';

		 if($num > $perpage){

            //一个列表显示几个数字
			$pn = 10;
			$offset = $pn/2;
			$pages = @ceil($num / $perpage);
            $total = 0;
			if($pn > $pages){
			    $start=1;
				$total=$pages;
			}else{
				$start = $curpage - $offset;
				$total = $start + $pn -1;

				if($start < 1){
					$total = $curpage +1 - $start;
					$start = 1;
					if($total - $start < $pn){
					   $total = $pn;
					}
				}elseif($total > $pages){
					$start = $pages - $pn + 1;
					$total = $pages;
				}

			}
			if($curpage>1){
				$mutipage .= '<a href="'.str_replace('{pagenum}', '1', $url).'">[首页]</a>';
				$mutipage .= '<a href="'.str_replace('{pagenum}', ($curpage-1).'', $url).'">[上一页]</a>';
			}

			$mutipage .=  '<span id="pagenum">'.$this->pageNumComm($start, $total, $curpage, $url, 'nowpage').'</span>';

			if($curpage < $pages){

			   $mutipage .= '<a href="'.str_replace('{pagenum}', ($curpage+1).'', $url).'">[下一页]</a>';
			   $mutipage .= '<a href="'.str_replace('{pagenum}', $pages.'', $url).'">[末页]</a>';
			}

		 }else{
			$multipage = 1;
		 }

		return $mutipage;

	 }


	 /**
	 * 搜索分页
	 * param int $total：为记录数
	 */
	 public function searchMutiPage($num, $perpage, $curpage, $url)
	 {
         $mutipage = '';

	     $url .= strpos($url, '?') ? '&' : '?';
         $url = strpos($url, 'page=') ? preg_replace('/[\?|&]page=\d+/', '',  $url) : $url;

		 if($num > $perpage){

			$page = 10;
			$offset = $page/2;
			$pages = @ceil($num / $perpage);

			if($page > $pages){
			    $start=1;
				$total=$pages;
			}else{
				$start = $curpage - $offset;
				$total = $start + $page -1;

				if($start < 1){
					$total = $curpage +1 - $start;
					$start = 1;
					if($total - $start < $page){
					    $total = $page;
					}
				}elseif($total > $pages){
					$start = $pages - $page + 1;
					$total = $pages;
				}

			}

			if($curpage>1){
				$mutipage .= '<a href="'.$url.'page='.($curpage-1).'"><<上一页</a>';
			}

			$mutipage .=  '<span id="pagenum">'.$this->pageNumComm($start, $total, $curpage, $url.'page={pagenum}', 'nowpage').'</span>';

			if($curpage < $total){

			   $mutipage .= '<a href="'.$url.'page='.($curpage+1).'">下一页>></a>';
			}

		 }else{
			$multipage = 1;
		 }

		return $mutipage;

	 }

	 /*pk分页*/
	 public function pkPage($num, $perpage, $curpage, $url, $type=0)
	 {

		 $mutipage = '';

		 if($num > $perpage){

            //一个列表显示几个数字
			$pn = 10;
			$offset = $pn/2;
			$pages = @ceil($num / $perpage);
            $total = 0;
			if($pn > $pages){
			    $start=1;
				$total=$pages;
			}else{
				$start = $curpage - $offset;
				$total = $start + $pn -1;

				if($start < 1){
					$total = $curpage +1 - $start;
					$start = 1;
					if($total - $start < $pn){
					   $total = $pn;
					}
				}elseif($total > $pages){
					$start = $pages - $pn + 1;
					$total = $pages;
				}

			}
			if($type){
				$fblock = '';
				$pblock = '&lt;&lt;上一页';
				$nblock = '下一页&gt;&gt;';
				$lblock = '';
			}else{
				$fblock = '[首页]';
				$pblock = '[上一页]';
				$nblock = '[下一页]';
				$lblock = '[末页]';
			}
			if($curpage>1){
				$mutipage .= '<a href="'.str_replace('{pagenum}', '1', $url).'">'.$fblock.'</a>';
				$mutipage .= '<a href="'.str_replace('{pagenum}', ($curpage-1), $url).'">'.$pblock.'</a>';
			}
			if($type){
				$mutipage .=  '<span id="pagenum">'.$this->pageNumComm($start, $total, $curpage, $url, 'nowpage').'</span>';
			}else{
				$mutipage .=  $this->pageNumComm($start, $total, $curpage, $url);
			}

			if($curpage < $pages){
			   
			   $mutipage .= '<a href="'.str_replace('{pagenum}', ($curpage+1), $url).'">'.$nblock.'</a>';
			   $mutipage .= '<a href="'.str_replace('{pagenum}', $pages, $url).'">'.$lblock.'</a>';
			}

		 }else{
			$multipage = 1;
		 }

		return $mutipage;

	 }
    //
	public function conListMutiPage($num, $perpage, $curpage, $url){
		$mutipage = '<div class="pagebox">';

		if($num > $perpage){

			$page = 5;
			$pages = @ceil($num / $perpage);

			if($page > $pages){
				$start=1;
				$total=$pages;
			}else{
				if($curpage > $page){
					$start = ($curpage%$page==0) ? (floor($curpage/$page)-1)*$page+1 : floor($curpage/$page)*$page+1;
					$total = $pages>=$start+$page-1 ? $start+$page-1 : $pages;
				}else{
					$start = 1;
					$total = $page;
				}
			}		

			if($curpage>1){
				if($curpage > $page && $curpage <= $pages){
					$mutipage .= '<span><a href="'.str_replace('{pagenum}', ($start-$page),$url).'">上5页</a></span>';
				}
				$mutipage .= '<span><a href="'.str_replace('{pagenum}',($curpage-1),$url).'">上一页</a></span>';
			}else{
				$mutipage .= '<span class="gray_block">上一页</span>';
			}

			$mutipage .=  $this->ListNum($start, $total, $curpage, $url, 'onlist');

			if($curpage < $pages){
				$mutipage .= '<span ><a href="'.str_replace('{pagenum}', ($curpage+1),$url).'">下一页</a></span>';
				if($pages > $total){
					$mutipage .= '<span><a href="'.str_replace('{pagenum}',($start+$page),$url).'">下5页</a></span>';
				}
			}

		}else{
			$multipage = 1;
		}
		$mutipage.='</div>';
			//printr($mutipage);
		return $mutipage;

	}

	public function ListNum($start, $total, $cur, $url, $curstyle=''){
		$numlist = '';
		for($i=$start; $i<=$total; $i++){
			$cururl =  str_replace('{pagenum}',$i, $url);
			if($cur == $i){
				$numlist .= '<span class="'.$curstyle.'">'.$i.'</span>';
			 }else{
				$numlist .= '<span><a href="'.$cururl.'">'.$i.'</a></span>';
			 }
		}

		return $numlist;
	}

    public function wapPage($num, $perpage, $curpage, $url ,$allbtn = 0){
        $mutipage = '';
        $pages = @ceil($num / $perpage);
        if($pages > 1)
        {
            $mutipage .= '<div class="pages">';
        }
        if($num > $perpage && $curpage<$pages)
        {
            $mutipage .= '<a class="on_choose" href="'.$url.'/'.($curpage+1).'" target="_self">下页</a> ';
        }else if($num > $perpage){
            $mutipage .= '<a>下页</a> ';
        }
        if($curpage > 1)
        {
            $mutipage .= '<a class="on_choose" href="'.$url.'/'.($curpage-1).'" target="_self">上页</a>';
        }else if($pages >1 && $curpage == 1){
            $mutipage .= '<a>上页</a> '; 
        }
        if($curpage !=1)
        {
            $mutipage .= ' <a class="on_choose" href="'.$url.'/1" target="_self" >首页</a> ';
        }else if ($num > $perpage){
            $mutipage .= '<a >首页</a> ';
        }
        if($curpage !=$pages && $pages !=0)
        {
            $mutipage .= '<a class="on_choose" href="'.$url.'/'.$pages.'" target="_self" >末页</a> ';
        }else if($curpage == $pages && $pages >1){
            $mutipage .= '<a>末页</a> ';
        }
        if($allbtn == 1 && $pages > 1 && $curpage != $pages){
            $mutipage .=' &nbsp;&nbsp;<a class="on_choose" href="'.$url.'/all" target="_self" >[查看全文]</a> ';
        }
        if($pages > 1)
        {
            $mutipage .= '</div>';
        }
        if($pages > 1)
        {
            $mutipage .= '<div class="pages1">
                 <form action="'.$url.'" method="post">
                <span>'.$curpage.'/'.$pages.'页</span>&nbsp;&nbsp;
                <span>跳到</span>&nbsp;&nbsp;
                    <input id="pagejump" width="20px" height="20px" name="pagejump" type="text" class="txt" />&nbsp;&nbsp;
                  <span>页</span>&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="submit" url="'.$url.'" class="pagejumpbtn" value="跳转" class="btn" onclick="" />
                </form>
            </div>';
        }
        return $mutipage;
    }
	
		/*
	 * 网站统一分页
	* @param I $num,进行分页的总数
	* @param I $perpage,每页显示条数
	* @param I $curpage,当前页
	* @param S $url,分页的url地址
	* @param S $style,分页链接跳转样式(评论comment，正文页mutipage，文章列表页list,专题列表页spec,pk台列表页pkt,PK台查看全部评论页app,全球议事厅评论底部conference,	“你知道吗”我的问答中心knowcenter,评论正文页commentart,专栏作家列表页send,普通图库正文页picview)
	* @return S,返回分页字符串
	*/	
	function showPage($num, $perpageNum="20" , $curpage="1" , $url , $style="comment") {
    	if($num < 0 || $perpageNum <= 0 || $curpage < 1 || $url ==''){
    		return false;
    	}
    	//判断是否是这三种
    	if(!in_array($style, array('comment','mutipage','list','spec','pkt','app','conference','knowcenter','commentart','send','picview'))){
    		return false;
    	}
    
    	//总页数
    	$pageCount = ceil ( $num / $perpageNum );
    
    	if($pageCount<=1) return '';
    
    	//分页字符串
    	$result = '<div class="pages_fy"><div class="pages">';
    
    
    	//上一页
    	if ($curpage > 1) {
    		if ($style == "list" || $style == "spec") {
    			$href = str_replace ( '.html', '/' . ($curpage - 1) . '.html', $url );
    		} else if ($style == "mutipage" || $style == "picview") {
    			$href = ($curpage-2<=0) ?  $url : str_replace('.html', '_'.($curpage - 2).'.html', $url);
    		} else {
    			$href = str_replace ( '{pagenum}', ($curpage - 1), $url );
    		}
    
    		$result .= '<a href="' . $href . '">上一页</a>';
    	}
    
    	//总体页码
    	//小于等于10页时，页码全部展示。
    	if ( $pageCount <= 10) {
    		for($i = 1; $i <= $pageCount; $i ++) {
    			if ($i == $curpage) {
    				$result .= "<span class='cur'>" . $i . "</span>";
    			} else {
    				if ($style == "list" || $style == "spec") {
    					$href = str_replace ( '.html', '/' . $i . '.html', $url );
    				} else if ($style == "mutipage" || $style == "picview") {
    					$href = ($i-1<=0) ?  $url : str_replace('.html', '_'.($i - 1).'.html', $url);
    				} else {
    					$href = str_replace ( '{pagenum}', $i, $url );
    				}
    
    				$result .= "<a href=" . $href . ">" . $i . "</a>";
    			}
    		}
    	} else {
			//第一页和第二页
			for($i = 1; $i <= 2; $i ++) {
				if ($i == $curpage) {
					$result .= "<span class='cur'>" . $curpage . "</span>";
				} else {
					if ($style == "list" || $style == "spec") {
						$result .= "<a href=" . str_replace ( '.html', '/' . $i . '.html', $url ) . ">$i</a>";
					} else if ($style == "mutipage" || $style == "picview") {
						if($i==1){
							$result .= "<a href=" . $url . ">1</a>";
						}else{
							$result .= "<a href=" . str_replace ( '.html', '_' . ($i - 1) . '.html', $url ) . ">$i</a>";
						}
					} else {
						$result .= "<a href=" . str_replace ( '{pagenum}', $i, $url ) . ">$i</a>";
					}
				}
			}    		
    		
    		if($curpage<6){
    			//当前页是否是第三页
    			if($curpage==3){
    				$result .= "<span class='cur'>" . $curpage . "</span>";
    				//第4，5页
    				if ($style == "list" || $style == "spec") {
						$result .= "<a href=" . str_replace ( '.html', '/' . 4 . '.html', $url ) . ">4</a>";
						$result .= "<a href=" . str_replace ( '.html', '/' . 5 . '.html', $url ) . ">5</a>";
					} else if ($style == "mutipage" || $style == "picview") {
						$result .= "<a href=" . str_replace ( '.html', '_' .  3 . '.html', $url ) . ">4</a>";
						$result .= "<a href=" . str_replace ( '.html', '_' .  4 . '.html', $url ) . ">5</a>";
					} else {
						$result .= "<a href=" . str_replace ( '{pagenum}', 4, $url ) . ">4</a>";
						$result .= "<a href=" . str_replace ( '{pagenum}', 5, $url ) . ">5</a>";
					}
				}else{
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' . 3 . '.html', $url ) . ">3</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' . 2 . '.html', $url ) . ">3</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', 3, $url ) . ">3</a>";
    				}
    			}
    			
    			//当前页是否是第二页
    			if($curpage==2){
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' . 4 . '.html', $url ) . ">4</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' . 3 . '.html', $url ) . ">4</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', 4, $url ) . ">4</a>";
    				}
    			}
    			//当前页是否是第四页
    			if($curpage==4){
    				$result .= "<span class='cur'>" . $curpage . "</span>";
    				//第5,6页
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' . 5 . '.html', $url ) . ">5</a>";
    					$result .= "<a href=" . str_replace ( '.html', '/' . 6 . '.html', $url ) . ">6</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' .  4 . '.html', $url ) . ">5</a>";
    					$result .= "<a href=" . str_replace ( '.html', '_' .  5 . '.html', $url ) . ">6</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', 5, $url ) . ">5</a>";
    					$result .= "<a href=" . str_replace ( '{pagenum}', 6, $url ) . ">6</a>";
    				}
    			}
    			//当前页是否是第五页
    			if($curpage==5){
    				//第四页
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' . 4 . '.html', $url ) . ">4</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' . 3 . '.html', $url ) . ">4</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', 4, $url ) . ">4</a>";
    				}
    				$result .= "<span class='cur'>" . $curpage . "</span>";
    				//加5,6页
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' . 6 . '.html', $url ) . ">6</a>";
    					$result .= "<a href=" . str_replace ( '.html', '/' . 7 . '.html', $url ) . ">7</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' . 5 . '.html', $url ) . ">6</a>";
    					$result .= "<a href=" . str_replace ( '.html', '_' . 6 . '.html', $url ) . ">7</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', 6, $url ) . ">6</a>";
    					$result .= "<a href=" . str_replace ( '{pagenum}', 7, $url ) . ">7</a>";
    				}
    			}
    			$result .= "<span style='border:0px solid;'>...</span>";
    		}else if($curpage>=6 && $curpage<=$pageCount-5){
				if ($style == "list" || $style == "spec") {
					$hrefPre = str_replace ( '.html', '/' . ($curpage - 1) . '.html', $url );
					$hrefNext=str_replace ( '.html', '/' . ($curpage + 1) . '.html', $url );
				} else if ($style == "mutipage" || $style == "picview") {
					$hrefPre = ($curpage-2<=0) ?  $url : str_replace('.html', '_'.($curpage - 2) .'.html', $url);
					$hrefNext=str_replace ( '.html', '_' . $curpage  . '.html', $url );
				} else {
					$hrefPre = str_replace ( '{pagenum}', ($curpage - 1), $url );
					$hrefNext=str_replace ( '{pagenum}', ($curpage + 1), $url );
				}
				$result .= "<span style='border:0px solid;'>...</span><a href=" . $hrefPre . ">" . ($curpage - 1) . "</a>";
				$result .= "<span class='cur'>" . $curpage . "</span>";
				$result .= "<a href=" . $hrefNext . ">" . ($curpage + 1) . "</a><span style='border:0px solid;'>...</span>";
    		}else{
    			$result .= "<span style='border:0px solid;'>...</span>";
    			//当前页是否是倒数第五页
    			if($curpage==$pageCount-4){
    				//倒数第七页，第六页
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' . ($pageCount-6) . '.html', $url ) . ">".($pageCount-6) . "</a>";
    					$result .= "<a href=" . str_replace ( '.html', '/' . ($pageCount-5) . '.html', $url ) . ">".($pageCount-5) . "</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' .($pageCount-7). '.html', $url ) . ">".($pageCount-6) . "</a>";
    					$result .= "<a href=" . str_replace ( '.html', '_' .($pageCount-6). '.html', $url ) . ">".($pageCount-5) . "</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', ($pageCount-6), $url ) . ">".($pageCount-6) . "</a>";
    					$result .= "<a href=" . str_replace ( '{pagenum}',($pageCount-5), $url ) . ">".($pageCount-5). "</a>";
    				}
    				$result .= "<span class='cur'>" . $curpage . "</span>";
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' . ($curpage+1) . '.html', $url ) . ">". ($curpage+1) . "</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' . $curpage . '.html', $url ) . ">".($curpage+1) ."</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', ($curpage+1), $url ) . ">".($curpage+1). "</a>";
    				}
    			}
    			
    			//当前页是否是倒数第四页
    			if($curpage==$pageCount-3){
    				//倒数第六页，第五页
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' . ($pageCount-5) . '.html', $url ) . ">" .($pageCount-5). "</a>";
    					$result .= "<a href=" . str_replace ( '.html', '/' . ($pageCount-4) . '.html', $url ) . ">" .($pageCount-4). "</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' . ($pageCount-6) . '.html', $url ) . ">" .($pageCount-5) . "</a>";
    					$result .= "<a href=" . str_replace ( '.html', '_' . ($pageCount-5) . '.html', $url ) . ">" .($pageCount-4). "</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', ($pageCount-5), $url ) . ">" .($pageCount-5) . "</a>";
    					$result .= "<a href=" . str_replace ( '{pagenum}', ($pageCount-4), $url ) . ">" .($pageCount-4) . "</a>";
    				}
    				$result .= "<span class='cur'>" . $curpage . "</span>";
    			}
    			
    			
    			//当前页是否是倒数第二页
    			if($curpage==$pageCount-1){
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' .($pageCount-3) . '.html', $url ) . ">".($pageCount-3)."</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' .($pageCount-4) . '.html', $url ) . ">".($pageCount-3) . "</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', ($pageCount-3), $url ) . ">".($pageCount-3). "</a>";
    				}
    			}
    			
    			//当前页是否是倒数第三页
    			if($curpage==$pageCount-2){
    				//倒数第五页，倒数第六页
    				if ($style == "list" || $style == "spec") {
						$result .= "<a href=" . str_replace ( '.html', '/' . ($pageCount-4) . '.html', $url ) . ">" . ($pageCount-4) . "</a>";
						$result .= "<a href=" . str_replace ( '.html', '/' . ($pageCount-3) . '.html', $url ) . ">" . ($pageCount-3) . "</a>";
					} else if ($style == "mutipage" || $style == "picview") {
						$result .= "<a href=" . str_replace ( '.html', '_'. ($pageCount-5) . '.html', $url ) . ">" . ($pageCount-4) . "</a>";
						$result .= "<a href=" . str_replace ( '.html', '_' . ($pageCount-4) . '.html', $url ) . ">" . ($pageCount-3) . "</a>";
					} else {
						$result .= "<a href=" . str_replace ( '{pagenum}', ($pageCount-4), $url ) . ">" . ($pageCount-4) . "</a>";
						$result .= "<a href=" . str_replace ( '{pagenum}',($pageCount-3), $url ) . ">" . ($pageCount-3) . "</a>";
					}
					$result .= "<span class='cur'>" . $curpage . "</span>";
				}else{
    				if ($style == "list" || $style == "spec") {
    					$result .= "<a href=" . str_replace ( '.html', '/' . ($pageCount-2) . '.html' , $url ) . ">" . ($pageCount-2) . "</a>";
    				} else if ($style == "mutipage" || $style == "picview") {
    					$result .= "<a href=" . str_replace ( '.html', '_' . ($pageCount-3) . '.html', $url ) . "> ". ($pageCount-2) . "</a>";
    				} else {
    					$result .= "<a href=" . str_replace ( '{pagenum}', ($pageCount-2), $url ) . ">" .($pageCount-2) . "</a>";
    				}
    			}
    		}
    		
    		
    		
    		
			//最后一页和倒数第二页
			for($i = $pageCount-1; $i <= $pageCount; $i ++) {
				if ($i == $curpage) {
					$result .= "<span class='cur'>" . $i . "</span>";
				} else {
					if ($style == "list" || $style == "spec") {
						$result .= "<a href=" . str_replace ( '.html', '/' . $i . '.html', $url ) . ">$i</a>";
					} else if ($style == "mutipage" || $style == "picview") {
						$result .= "<a href=" . str_replace ( '.html', '_' . ($i-1) . '.html', $url ) . ">$i</a>";
					} else {
						$result .= "<a href=" . str_replace ( '{pagenum}', $i, $url ) . ">$i</a>";
					}
				}
			}
    	}
    
    	//展示下一页
    	if ($curpage < $pageCount) {
    		if ($style == "list" || $style == "spec") {
    			$href = str_replace ( '.html', '/' . ($curpage + 1) . '.html', $url );
    		} else if ($style == "mutipage" || $style == "picview") {
    			$href = str_replace ( '.html', '_' . $curpage . '.html', $url );
    		} else {
    			$href = str_replace ( '{pagenum}', ($curpage + 1), $url );
    		}
    		$result .= '<a href="' . $href . '">下一页</a>';
    	}
		if ( $pageCount > 10) {
			$result .= "<span class='jump_page'><p class='fl'>至</p> <input class='int_jump' type='text'id='int_jump'/> 页</span>";
			$result .= '<a href="###" target="_self" id="jump" onclick="page(' . "'$style'" .');">跳转</a>';
			$result .= '<input type="hidden" id="pagecount" value="' . $pageCount . '" ><input type="hidden" id="pageUrl" value="'.$url.'"><input type="hidden" id="style" value="' . $style . '" >';
		}
    	
    	$result .= '</div></div>';
    	return $result;
    }
    
    //军迷健康正文页分页
	function junmiPage($num, $perpageNum="20" , $curpage="1" , $url , $style="comment", $gohome=0) {
    	if($num < 0 || $perpageNum <= 0 || $curpage < 1 || $url ==''){
    		return false;
    	}
    	//判断是否是这三种
    	if(!in_array($style, array('comment','mutipage','list','spec','pkt','app','conference','knowcenter','commentart','send','picview'))){
    		return false;
    	}
    
    	//总页数
    	$pageCount = ceil ( $num / $perpageNum );
    
    	if($pageCount<=1) return '';
    
    	//分页字符串
    	$result = '<div class="pages_fy"><div class="pages"><span>共'.$pageCount.'页</span>&nbsp;';
    
		if($curpage>10){
			$result .= "<a href='" . $url . "'>首页</a>&nbsp;";
		}
    	//上一页
    	if ($curpage > 1) {
    		if ($style == "list" || $style == "spec") {
    			$href = str_replace ( '.html', '/' . ($curpage - 1) . '.html', $url );
    		} else if ($style == "mutipage" || $style == "picview") {
    			$href = ($curpage-2<=0) ?  $url : str_replace('.html', '_'.($curpage - 2).'.html', $url);
    		} else {
    			$href = str_replace ( '{pagenum}', ($curpage - 1), $url );
    		}
    
    		$result .= '<a href="' . $href . '">上一页</a>&nbsp;';
    	}
    
    	//总体页码
    	//小于等于10页时，页码全部展示。
    	if ( $pageCount <= 11) {
    		for($i = 1; $i <= $pageCount; $i ++) {
    			if ($i == $curpage) {
    				$result .= "<span class='cur'>" . $i . "</span>&nbsp;";
    			} else {
    				if ($style == "list" || $style == "spec") {
    					$href = str_replace ( '.html', '/' . $i . '.html', $url );
    				} else if ($style == "mutipage" || $style == "picview") {
    					$href = ($i-1<=0) ?  $url : str_replace('.html', '_'.($i - 1).'.html', $url);
    				} else {
    					$href = str_replace ( '{pagenum}', $i, $url );
    				}
    
    				$result .= "<a href=" . $href . ">" . $i . "</a>&nbsp;";
    			}
    		}
    	} else {
			
			if($curpage>10){
				$start = $curpage-5;
				$end = $curpage+6;
				if($end>$pageCount){
					$end = $pageCount;
				}
			}else{
				$start = 1;
				$end = 12;
			}
			//第一页和第二页
			for($i = $start; $i <= $end; $i++) {
				if ($i == $curpage) {
					$result .= "<span class='cur'>" . $curpage . "</span>";
				} else {
					if ($style == "list" || $style == "spec") {
						$result .= "<a href=" . str_replace ( '.html', '/' . $i . '.html', $url ) . ">$i</a>&nbsp;";
					} else if ($style == "mutipage" || $style == "picview") {
						if($i==1){
							$result .= "<a href=" . $url . ">1</a>&nbsp;";
						}else{
							$result .= "<a href=" . str_replace ( '.html', '_' . ($i - 1) . '.html', $url ) . ">$i</a>&nbsp;";
						}
					} else {
						$result .= "<a href=" . str_replace ( '{pagenum}', $i, $url ) . ">$i</a>&nbsp;";
					}
				}
    		}
    	}
		if($gohome==1){
			$result .= "<a href='" . IConfig::BASE . "'>" . $i . "</a><a href='" . IConfig::BASE . "'>下一页</a>";
		}
    	//展示下一页
    	if ($curpage < $pageCount) {
    		if ($style == "list" || $style == "spec") {
    			$href = str_replace ( '.html', '/' . ($curpage + 1) . '.html', $url );
    		} else if ($style == "mutipage" || $style == "picview") {
    			$href = str_replace ( '.html', '_' . $curpage . '.html', $url );
    		} else {
    			$href = str_replace ( '{pagenum}', ($curpage + 1), $url );
    		}
    		$result .= '<a href="' . $href . '">下一页</a>';
    	}
		
		/*
		if ( $pageCount > 10) {
			$result .= "<span class='jump_page'><p class='fl'>至</p> <input class='int_jump' type='text'id='int_jump'/> 页</span>";
			$result .= '<a href="###" target="_self" id="jump" onclick="page(' . "'$style'" .');">跳转</a>';
			$result .= '<input type="hidden" id="pagecount" value="' . $pageCount . '" ><input type="hidden" id="pageUrl" value="'.$url.'"><input type="hidden" id="style" value="' . $style . '" >';
		}
    	*/
    	$result .= '</div></div>';
    	return $result;
    }
}