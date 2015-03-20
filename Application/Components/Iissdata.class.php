<?php
/**
* IIissdata 网站数据类
* 此类针对网站在模板或其他常用数据的操作，生成缓存文件，并读取缓存文件
* @writer: kelly
* @time: 2009-7-30
*/

class Iissdata
{
	 /**
	 * 构造函数
	 */
     public function __construct(){}
   
     /**
	 * 取得头部导航数据
	 */
	 public static function getSiteNavi()
	 {
		   static $dataarr = array();

           if(empty($dataarr)){
               import("Components.Cache");
		       $objcache = new \Cache('iissdata/data_navi.php');
			   if(! $dataarr = $objcache->getArrayCache('iissdata/data_navi.php'))
			   {
					$db = M();
					$sql = "SELECT * FROM iiss_navi where isshow=0 ORDER BY pid,sortnum";
					$query = $db->query($sql);

					$arrnavi = array();
					$subarr = array();
					foreach($query as $k=>$row) {
						$naviid = $row['naviid'];
						$pid = $row['pid'];
						if($row['pid'] != 0){				
							$subarr[$pid][] = $row;
						}else{
							$arrnavi[$naviid] = $row;
						}
					}

					foreach($arrnavi as $key=>$val){
						$arrnavi[$key]['sub'] = isset($subarr[$key]) ? $subarr[$key] : '';
					}

					$dataarr = $arrnavi;
					//把结果写入缓存
					$objcache->arrayToFile('iissdata/data_navi.php',$dataarr);
			   }
		   }


		   return $dataarr;

	 }

	 /**
	 * 取得底部导航
	 */

	  public static function getBottomNavi()
	 {
		   static $dataarr = array();

           if(empty($dataarr)){
			   $objcache = & IFactory::getCache();

			   if(! $dataarr = $objcache->getArrayCache('iissdata/data_bottom.php'))
			   {

					$db = & IFactory::getDB();
					$sql = "SELECT * FROM iiss_bottom where isshow=1 ORDER BY orderid";
					$query = $db->query($sql);

					$arrnavi = array();
					while($row = $db->fetch_array($query)){
						$arrnavi[] = $row;
					}

					$dataarr = $arrnavi;
					//把结果写入缓存
					$objcache->arrayToFile('iissdata/data_bottom.php',$dataarr);
			   }
		   }


		   return $dataarr;

	 }

     /**
	 * 删除系统数据
	 */
	 public static function delDataValue($var=''){

		 $objcache = & IFactory::getCache();

		 if(!empty($var)){
		    $objcache->delCache('iissdata/data_'.$var.'.php');
		 }else{
            $objcache->delCache('iissdata');
		 }

	 }

}