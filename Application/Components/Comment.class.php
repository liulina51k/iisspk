<?php
/**
* comment.clase.php 网站评论客户端接口类
*/
/*
* 客户端请求服务端接口基础类
* 负责http请求及处理返回值
*/
class CommentRequest {

	/**
	 * Contains the last HTTP status code returned. 
	 *
	 * @ignore
	 */
	public $http_code;
	/**
	 * Contains the last API call.
	 *
	 * @ignore
	 */
	public $url;
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = 'http://comment.enchinaiiss.com';//IConfig::COMMENT;
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 */
	public $timeout = 30;
	/**
	 * Set connect timeout.
	 *
	 * @ignore
	 */
	public $connecttimeout = 30;
	/**
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	public $ssl_verifypeer = FALSE;
	/**
	 * Respons format.
	 *
	 * @ignore
	 */
	public $format = 'json';
	/**
	 * Decode returned json data.
	 *
	 * @ignore
	 */
	public $decode_json = TRUE;
	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	public $http_info;
	/**
	 * Set the useragnet.
	 *
	 * @ignore
	 */
	public $useragent = '';

	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	public $debug = FALSE;
	
	public $remote_ip = '';
	/**
	 * boundary of multipart
	 * @ignore
	 */
	public static $boundary = '';

	function __construct() {
		$this->useragent = $_SERVER['HTTP_USER_AGENT'];
		
	}
	
	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	function get($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * POST wreapper for oAuthRequest.
	 *
	 * @return mixed
	 */
	function post($url, $parameters = array(), $multi = false) {
		$response = $this->oAuthRequest($url, 'POST', $parameters, $multi );
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * DELTE wrapper for oAuthReqeust.
	 *
	 * @return mixed
	 */
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore+
	 */
	function oAuthRequest($url, $method, $parameters, $multi = false) {

		if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
				$url = "{$this->host}/{$url}.{$this->format}";
		}
		switch ($method) {
			case 'GET':
				$url = $url . '?' . http_build_query($parameters);
				return $this->http($url, 'GET');
			default:
				$headers = array();
				if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
					$body = http_build_query($parameters);
				} else {
					$body = self::build_http_query_multi($parameters);
					$headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
				}
				return $this->http($url, $method, $body, $headers);
		}
	}

	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	function http($url, $method, $postfields = NULL, $headers = array()) {
		
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					$this->postdata = $postfields;
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
		}

		if ( !empty($this->remote_ip) ) {
			$headers[] = "API-RemoteIP: " . $this->remote_ip;
		}
		$headers[] = 'Host:comment.enchinaiiss.com';
		curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;

		if ($this->debug) {
			echo "=====post data======\r\n";
			var_dump($postfields);

			echo "=====headers======\r\n";
			print_r($headers);

			echo '=====request info====='."\r\n";
			print_r( curl_getinfo($ci) );

			echo '=====response====='."\r\n";
			print_r( $response );
		}
		curl_close ($ci);
		return $response;
	}

	/**
	 * Get the header info to store.
	 *
	 * @return int
	 * @ignore
	 */
	function getHeader($ch, $header) {
		$i = strpos($header, ':');
		if (!empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}

	/**
	 * @ignore
	 */
	public static function build_http_query_multi($params) {
		if (!$params) return '';

		uksort($params, 'strcmp');

		$pairs = array();

		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary. '--';
		$multipartbody = '';

		foreach ($params as $parameter => $value) {

			if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
				$url = ltrim( $value, '@' );
				$content = file_get_contents( $url );
				$array = explode( '?', basename( $url ) );
				$filename = $array[0];

				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
				$multipartbody .= "Content-Type: image/unknown\r\n\r\n";
				$multipartbody .= $content. "\r\n";
			} else {
				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}

		}
		$multipartbody .= $endMPboundary;
		return $multipartbody;
	}
}
/*
* 客户端评论接口类
* 评论相关操作
*/
class Comment
{
	private $_ocate;
	public  $oRequest = null;
	private $_tablename = 'iiss_infocomment';
	private $_commentdb = null;
	private $_fields = array('id', 'infoid', 'categoryid', 'modelid', 'userid', 'username', 'quoteuserid', 'quoteusername', 'flag', 'postip', 'ischeck', 'postdate', 'bad', 'good', 'type', 'quote', 'floor', 'position', 'isrecomm', 'isread', '`from`', 'subject', 'content');

	public function __construct(){
		
		//import('category', 'source');
		//$this->_ocate = & ICategory::getInstance();
		
		//$this->_commentdb = & IFactory::getCommentDB();
		
		$this->oRequest = new CommentRequest();
		$this->set_remote_ip(getClientIP());
		
	}

    /**
    * 获得单一实例
    */
  /*
    public static function getInstance()
    {
		static $instance;

		if(!is_object($instance)){
			$instance = new IComment();
		}

		return $instance;
    }*/
	/**
	 * 开启调试信息
	 *
	 *
	 * @access public
	 * @param bool $enable 是否开启调试信息
	 * @return void
	 */
	public function set_debug( $enable )
	{
		$this->oRequest->debug = $enable;
	}

	/**
	 * 设置用户IP
	 *
	 * @access public
	 * @param string $ip 用户IP
	 * @return bool IP为非法IP字符串时，返回false，否则返回true
	 */
	public function set_remote_ip( $ip )
	{
		if ( ip2long($ip) !== false ) {
			$this->oRequest->remote_ip = $ip;
		} else {
			return false;
		}
	}

	/**
	* 获取评论分类
	*/
	public function getCategory($categoryid = 0)
	{
		static $dataarr = array();
		if(empty($dataarr)){
			$oCache = & IFactory::getCache();
			if(! $dataarr = $oCache->getArrayCache($this->_cachefile)){
				//不获取转发和手机模型的分类
				$dataarr = $this->_ocate->filterCategory(array('modelid'=>array(14)), 0);
				ksort($dataarr);
				//一些定性的分类编号
				$dataarr['-1'] = array('categoryid'=>'-1', 'parentid'=>0, 'categoryname'=>'PK评论');
				$dataarr['-2'] = array('categoryid'=>'-2', 'parentid'=>0, 'categoryname'=>'言论VS评论');
				$dataarr['-7'] = array('categoryid'=>'-7', 'parentid'=>0, 'categoryname'=>'全球议事厅');
				$dataarr['-8'] = array('categoryid'=>'-8', 'parentid'=>0, 'categoryname'=>'南海');
				$dataarr['-9'] = array('categoryid'=>'-9', 'parentid'=>0, 'categoryname'=>'保钓');

				$oCache->arrayToFile($this->_cachefile,$dataarr);
				$this->writeSysData();
			}
		}
		if($categoryid){
			return isset($dataarr[$categoryid]) ? categoryid : array();
		}
		return $dataarr;
	}
	/**
	* 评论分类系统设置
	*/
	private function writeSysData(){

		$purarr = ISysdata::getDataValue('elsepurviews');

        $purarr['infocomment'] = array();

		$purarr['infocomment']['name'] = '评论栏目权限';

		$arrdata = $this->_ocate->filterCategory(array('modelid'=>array(4,14)), 0);
		ksort($arrdata);
				
		$arrdata['pk'] = array('categoryid'=>'-1', 'parentid'=>0, 'categoryname'=>'PK评论');
		$arrdata['vs'] = array('categoryid'=>'-2', 'parentid'=>0, 'categoryname'=>'言论VS评论');
		$arrdata['conference'] = array('categoryid'=>'-7', 'parentid'=>0, 'categoryname'=>'全球议事厅');
		$arrdata['nanhai'] = array('categoryid'=>'-8', 'parentid'=>0, 'categoryname'=>'南海');
		$arrdata['baodiao'] = array('categoryid'=>'-9', 'parentid'=>0, 'categoryname'=>'保钓');

		$purarr['infocomment']['value'] = $this->_ocate->getOptionList($arrdata);

		ISysdata::setDataValue('elsepurviews', $purarr);
	}
	/**
	* 清除栏目相关缓存
	*/
	public function delCache()
	{
		$objcache = & IFactory::getCache();
        $objcache->delCache($this->_cachefile);
		$this->writeSysData();
	}

	/**
	* 取得数据列表
	* @param string $field:所要获得的字段
	* @param string $where:条件组成
	* @param int $perpage:每页显示的个数
	* @param int $curpage:这是第几页
	*/
	public function getCommentList($field, $categoryid, $infoid='', $perpage = 0, $curpage=0, $returnarray=1, $where = '1', $order='order by postdate desc')
	{
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid,
			'perpage'=>$perpage,
			'curpage'=>$curpage,
			't'=>-1
		);
		return $this->oRequest->get('getCommentList', $param);
	}
	/**
	* 取得数据列表
	* @param string $field:所要获得的字段
	* @param string $where:条件组成
	* @param int $perpage:每页显示的个数
	* @param int $curpage:这是第几页
	*/
	public function getPKCommentList($categoryid, $infoid, $perpage=20, $curpage=1, $type=0)
	{
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid,
			'perpage'=>$perpage,
			'curpage'=>$curpage,
			't'=>$type
		);
		return $this->oRequest->get('getPKCommentList', $param);
	}
	/*
	* 防务精英评论列表
	*/
	public function getDefenseCommentList($categoryid, $infoid, $perpage = 10, $curpage=1, $type=1)
	{
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid,
			'perpage'=>$perpage,
			'curpage'=>$curpage,
			't'=>$type
		);
		return $this->oRequest->get('getDefenseCommentList', $param);
	}
	/*
	* 通用评论列表
	*/
	public function getAllCommentList($categoryid, $infoid, $perpage = 10, $curpage=1)
	{
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid,
			'perpage'=>$perpage,
			'curpage'=>$curpage
		);
		return $this->oRequest->get('getAllCommentList', $param);
	}
	//获取非热点评论
	public function getOtherHotCommentList($categoryid, $infoid, $perpage=20, $curpage=1, $type='hotlist')
	{
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid,
			'perpage'=>$perpage,
			'curpage'=>$curpage,
			't'=>$type
		);
		return $this->oRequest->get('getOtherHotCommentList', $param);
	}
	/**
	*获取精华评论
	*/
	public function getReCommmendList($categoryid, $infoid)
	{
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid
		);
		return $this->oRequest->get('getReCommmendList', $param);
	}
	/**
	*获取最热回复
	*/
	public function getHotCommentList($categoryid, $infoid)
	{
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid
		);
		return $this->oRequest->get('getHotCommentList', $param);
	}
	
	public function getHotCommentListNoFloor($categoryid, $infoid)
	{
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid
		);
		return $this->oRequest->get('getHotCommentListNoFloor', $param);
	}
	/*
	* 支持最多的最热回复
	*/
	public function getHotCommentListByGood($categoryid, $infoid)
	{
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid
		);
		return $this->oRequest->get('getHotCommentListByGood', $param);
	}
	
	/**
	*一周一世界获取最热回复 2012-7-12
	*/
	public function getWeekworldHotComment($categoryid, $infoid)
	{	
		$param = array(
			'categoryid'=>$categoryid,
			'infoid'=>$infoid
		);
		return $this->oRequest->get('getWeekworldHotComment', $param);
	}
	/*
	* PK关注评论
	* $num int 获取信息数量
	* $infoid int pk编号
	* $type int 评论类型 0 正方/1 反方
	* $day int 信息获取天数
	*/
	public function getPKReComment($num, $infoid, $type, $day=2)
	{
		$param = array(
			'num'=>$num,
			'infoid'=>$infoid,
			't'=>$type,
			'day'=>$day
		);printr($this->oRequest->get('getPKReComment', $param));
		return $this->oRequest->get('getPKReComment', $param);
	}
	/**
	* 取得固定数量的评论
	*/
	public function getCommentListNum($num, $categoryid, $infoid)
	{
		$param = array(
			'num'=>$num,
			'categoryid'=>$categoryid,
			'infoid'=>$infoid
		);
		return $this->oRequest->get('getCommentListNum', $param);
	}
	/**
	* 取得某一评论信息
	*/
	public function getComment($id, $isquote=0)
	{
		$param = array('id'=>$id, 'quote'=>$isquote);
		return $this->oRequest->get('getComment', $param);
	}
	/**
	* 获得我应该所在楼层
	*/
	public function getFloor($categoryid, $infoid, $type='all')
	{
		$strwhere = $type=='all' ? '' : "and type=$type";
		$sql = "select floor from $this->_tablename where categoryid=$categoryid and infoid=$infoid $strwhere order by id desc limit 1";
		$query = $this->_commentdb->query($sql);
		if($value = $this->_commentdb->fetch_array($query)){
		   return $value['floor'];
		}else{
		   return 0;
		}
	}
	//取得用户名，为空则返回战略网友
	public function getUsername($username){
	    return !empty($username) ? $username : '战略网网友';
	}
	
	/*
	* 获取用户评论数据
	* $userid int 用户编号
	* $modelid int 模型编号
	* $categoryid int 栏目编号
	*/
	public function getNumByUserId($userid, $modelid, $categoryid=0)
	{
		if(!empty($userid)){
			$strwhere = " AND modelid=$modelid";
			if($categoryid){
				$strwhere .=" AND $categoryid=$categoryid";
			}
			$sql = "SELECT COUNT(*) FROM $this->_tablename WHERE ischeck=1 AND userid=$userid $strwhere";
			$query = $this->_commentdb->query($sql);
			$value = $this->_commentdb->fetch_row($query);
			if(!empty($value)){
				return $value[0];
			}else{
				return 0;
			}
		}
	}
	/*
	* 获取某条信息评论数据
	* $infoid int 信息编号
	* $categoryid int 栏目编号
	* $type string 评论类型
	*/
	public function getNumByInfoId($infoid, $categoryid, $type='all')
	{
		$param = array(
			'ids'=>$infoid,
			'categoryid'=>$categoryid,
			't'=>$type
		);
		return $this->oRequest->get('getCommentNum', $param);
	}
	
	/**
	* 获取信息参与人数 支持数+评论数
	*/
	public function getAllCommUserCount($infoid, $categoryid)
	{
		$param = array(
			'infoid'=>$infoid,
			'categoryid'=>$categoryid
		);
		return $this->oRequest->get('getAllCommUserCount', $param);
	}
	
	/**
	* 获得评论统计数据
	*/
	public function getCommCount($infoid, $categoryid, $type='all')
	{
		return $this->getNumByInfoId($infoid, $categoryid, $type);
	}
	/*
	* 获取精华评论数量
	*/
	public function getNumByRecomm($infoid, $categoryid)
	{
		$param = array(
			'infoid'=>$infoid,
			'categoryid'=>$categoryid
		);
		return $this->oRequest->get('getNumByRecomm', $param);
	}
	/**
	* 获得引用的评论总支持数
	* $quoteid string 应用评论ID
	*/
	public function getGoodCount($quoteid)
	{
		$param = array(
			'id'=>$quoteid
		);
		return $this->oRequest->get('getGoodCount', $param);
	}
	/*
	* ===================================================================================================================
	*/
	//修改评论
	public function update($comments, $whe, $categoryid)
	{
		$whe = is_numeric($whe) ? 'id='.$whe : $whe;
		if(!empty($categoryid)){
			$this->_commentdb->query('update '.$this->_tablename.' set '.$comments.' where categoryid='.$categoryid. ' and '.$whe);
		}else{
			$this->_commentdb->query('update '.$this->_tablename.' set '.$comments.' where '.$whe);
		}
		return true;
	}

    /**
	* 写入数据库，添加评论
	*/
	public function insert($comments)
	{
		global $_IGLOBAL;
		$id = 0;
		$comments['postip'] = getClientIP();
		$comments['floor'] = $this->getFloor($comments['categoryid'], $comments['infoid'])+1;
		$comments['postdate'] = $_IGLOBAL['timestamp'];
		if(strstr($comments['content'], '<a')){
		
		}else{
			$id = $this->_commentdb->insertTable($this->_tablename, $comments, 1);
		}

		return $id;
	}
	/**
	* 删除信息评论
	* @param $infoid:mix 信息编号
	* @param $categoryid:int 栏目编号
	* return true/false
	*/
	public function delcomm($infoid, $categoryid, $flag=true)
	{

		if(is_array($infoid)){
			$strid = implode(',',$infoid);
			$param = array(
				'infoid'=>$strid,
				'categoryid'=>$categoryid,
				'flag'=>$flag
			);
			
		}else{
			$param = array(
				'infoid'=>$infoid,
				'categoryid'=>$categoryid,
				'flag'=>$flag
			);
		}

		return $this->oRequest->get('delCommentByInfo', $param);
	}

	/**
	* 删除指定评论
	* $param into id:评论编号
	*/
	public function delCommentById($id, $flag=true, $uid=0)
	{
		$param = array(
			'id'=>$id,
			'flag'=>$flag,
			'uid'=>$uid
		);
		return $this->oRequest->get('delCommentById', $param);
	}
	/**
	* 删除某个用户的评论
	* $param into id:评论编号
	*/
	public function delCommentByUserId($userid, $flag=false, $uid=0)
	{
		$param = array(
			'userid'=>$userid,
			'flag'=>$flag,
			'uid'=>$uid
		);
		return $this->oRequest->get('delCommentByUserId', $param);
	}
	/*
	* 审核评论
	* @id array 评论编号
	* @uid int 用户编号
	*/
	public function checkComment($id,$uid){

		$param = array(
			'id'=>$id,
			'uid'=>$uid
		);
		return $this->oRequest->post('checkComment', $param);
	}
	/*
	* 获取评论所在文章中的序号
	*@param $infoid 文章编号
	*@param $comentid 评论编号
	*@param $strwhere 获取评论的条件
	*return 序号
	*/
	public function getRowNumById($infoid, $comentid, $strwhere='1')
	{
		$param = array(
			'infoid'=>$infoid,
			'id'=>$infoid,
			'where'=>($strwhere=='1' ? '' : $strwhere)
		);
		return $this->oRequest->get('getRowNumById', $param);
	}
	//取得全站被评论最多的文章id 2012-6-26
	public function getInfo($num,$whe=''){
	   $pubtime = time();
	   
		//增加参数2012-6-26
		$where = !empty($whe) ? $whe : "date(FROM_UNIXTIME( `postdate`)) = DATE_SUB( curdate(), INTERVAL 2 DAY )";
		
		$sql = "SELECT infoid,categoryid,count(*) num  FROM iiss_infocomment WHERE ".$where." GROUP BY infoid, categoryid ORDER BY num DESC limit $num";

		$query = $this->_commentdb->query($sql);
		while($arrvalue = $this->_commentdb->fetch_array($query)){
		    $list[] = $arrvalue;
		}
		if(!empty($list)){
		return $list;
		}
	}
	
	//根据文章评论获得文章标题
	public function getArtTitle($info,$num){
		$db = & IFactory::getDB();
		$comment = array();
		for($i=0;$i<$num;$i++){
		  if($info[$i]['categoryid']>0){
		   $query = $db->query("select modelid from iiss_infocategory where categoryid = ".$info[$i]['categoryid']);	  
			$modelid = $db->fetch_array($query); 
			$query1 = $db->query("select modelmark from iiss_infomodel where modelid  = ".$modelid['modelid']);
			$table=$db->fetch_array($query1);
			$condition = $table['modelmark'];
			switch($condition){
				case 'article':
					//文章实例
					import('article', 'source');
					$oart = & IArticle::getInstance();
					$artsubject = $oart->getArt($info[$i]['infoid'], 'subject');
					$arturl = $oart->getArt($info[$i]['infoid'], 'url');
					$subject = array('subject'=>$artsubject, 'url'=>$arturl);
					break;
			   case 'image':
					//图片实例
					import('image', 'source');
					$oimg = & IImage::getInstance();
					$imgsubject = $oimg->getImg($info[$i]['infoid'], 'subject');
					$imgurl = $oimg->getImg($info[$i]['infoid'], 'url');
					$subject = array('subject'=>$imgsubject, 'url'=>$imgurl);
					break;
				case 'spec':
					//专题实例
					import('spec', 'source');
					$ospec = & ISpec::getInstance();
					$subject = $ospec->getSpec($info[$i]['infoid']);
					break;
				case 'jump':
					//跳转新闻实例
					import('jump', 'source');
					$ojump = & IJump::getInstance();
					$subject = $ojump->getJump($info[$i]['infoid']);
					break;
				case 'send':
					//新闻实例
					import('send', 'source');
					$osend = & ISend::getInstance();
					$subject = $osend->getArt($info[$i]['infoid']);
					break;
				case 'people':
					import('people', 'source');
					$opk = & IPeople::getInstance();
					$subject = $opeople->getPeople($info[$i]['infoid']);
					break; 
			}
		  }elseif($info[$i]['categoryid'] == -1){
				//pk实例
				import('pk', 'source');
				$opk = & IPK::getInstance();
				$title = $opk->getPk($info[$i]['infoid'],'title');
				$subject = array('subject'=>$title, 'url'=>IConfig::BASE.'/pk/index/'.$info[$i]['infoid']);

		  }elseif($info[$i]['categoryid'] == -7){
			   import('conference', 'source');
				$ocon = & IConference::getInstance();
				$consubject = $ocon->getCon($info[$i]['infoid'], 'subject');
				$subject = array('subject'=>$consubject, 'url'=>IConfig::BASE.'/conference/index/'.$info[$i]['infoid']);

		  }
		  if(!empty($subject)){
		  $comment[] = array_merge($info[$i],$subject);
		  }
		}
		return $comment;
	}
}