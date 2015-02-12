<?php
/*
 *  公共操作函数
 */
/*
 * 用于调试输出
 */
function printr($msg){
	echo '<pre>';
	print_r($msg);die;
	echo '</pre>';
}
/**
* 字符串解密加密
*/
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;	// 随机密钥长度 取值 0-32;
				// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
				// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
				// 当此值为 0 时，则不产生随机密钥

	$key = md5($key ? $key : 'c2J0r9pfn72975jeE4E6HdP11cN6a625Y5Kfd3U2yb0cA7s3Bf28l418Z67fb2J3');
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
/*
 * 获取时间
 */
function getDayAMPM() {
	  $hour = (int)date("H", time());

	  if($hour>=6 && $hour<=12){
	
		  $showtxt = '上午';
	  }elseif($hour>12 && $hour<= 18){
	      $showtxt = '下午';
	  }else{
	      $showtxt = '晚上';
	  }
	
	  return $showtxt;
}
/**
*  清空cookie
*/
function clearcookie() {
	global $_IGLOBAL;

	ssetcookie('auth', '', -86400 * 365);
	$_IGLOBAL['supe_uid'] = 0;
	$_IGLOBAL['supe_username'] = '';
	$_IGLOBAL['member'] = array();
}

/*
*  cookie设置
*/
function ssetcookie($var, $value, $life=0) {
	global $_IGLOBAL, $_SERVER;
	setcookie($var, $value, $life?($_IGLOBAL['timestamp']+$life):0, C('COOKIE_PATH'), C('COOKIE_DOMAIN'), $_SERVER['SERVER_PORT']==443?1:0);
}

//产生form防伪码
function formhash() {
	global $_IGLOBAL;

	if(empty($_IGLOBAL['formhash'])) {
		//$hashadd = defined('IN_ADMINIISS') ? 'Only For Chinaiiss' : '';
		$hashadd = '';
		$_IGLOBAL['formhash'] = substr(md5(substr($_IGLOBAL['timestamp'], 0, -7).'|'.$_IGLOBAL['supe_uid'].'|'.$hashadd), 8, 8);
	}
	return $_IGLOBAL['formhash'];
}

//判断提交是否正确
function submitcheck($var) {
    
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == formhash()) {
			return true;
		} else {
			showmessage('你的行为疑是非法操作，被终止！', $_SERVER['HTTP_REFERER'], 5);
		}
	} else {
		return false;
	}
}

//编码转换
function siconv($str, $out_charset, $in_charset='') {

	$in_charset = empty($in_charset)?'utf-8':strtoupper($in_charset);
	$out_charset = strtoupper($out_charset);
	if($in_charset != $out_charset) {
		if (function_exists('iconv') && (@$outstr = iconv("$in_charset//IGNORE", "$out_charset//IGNORE", $str))) {
			return $outstr;
		} elseif (function_exists('mb_convert_encoding') && (@$outstr = mb_convert_encoding($str, $out_charset, $in_charset))) {
			return $outstr;
		}
	}
	return $str;//转换失败
}

//设置静态页面的URL名称
function makesiteurl($fix = ''){
	$url = '';
    $num_args = func_num_args();
    
	if($num_args<4){
	    return '';
	}

	for($i = 1; $i<$num_args; $i++){
		 $url .= func_get_arg($i).'/';
	}
	$fix = $fix ? '.'.$fix : '';
	return 'http://chinaiisspk.com/'.substr($url,0,-1).$fix;
}
/**
* 取得客户端IP
*/
function getClientIP($format=0) {
	global $_IGLOBAL;

	if(empty($_IGLOBAL['clientip'])) {
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
		$_IGLOBAL['clientip'] = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
	}

	return $_IGLOBAL['clientip'];

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
