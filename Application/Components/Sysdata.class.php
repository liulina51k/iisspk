<?php
/**
* ISysdata 系统数据类
* 此类针对表iiss_sysdata的操作，进行读写删，生成缓存文件，并读取缓存文件
* @writer: kelly
* @time: 2009-4-3
*/

class Sysdata
{
    private $_objcache;
    
	public function __construct($var){
		 import("Components.Cache");
		 $this -> _objcache = new \Cache('sysdata/data_'.$var.'.php');
	}
     /*
	 * 获取缓存数据,先读取缓存，如果缓存不存在或过期就从数据库读取
	 */
	 public function &getDataValue($var, $varfield=''){
		 static $dataarr = array();
		 if(!array_key_exists($var, $dataarr)){

              if(! $dataarr[$var] = $this -> _objcache -> getArrayCache('sysdata/data_'.$var.'.php')){
                  $db = M();
				  $query = $db->query("SELECT * FROM iiss_sysdata WHERE var='$var' LIMIT 1");
				  if($query){
                       $dataarr[$var] = sstripslashes(mb_unserialize($query[0]['datavalue']));
					   //把结果写入缓存
					   $this -> _objcache -> arrayToFile('sysdata/data_'.$var.'.php', $dataarr[$var]);
				  }else{printr(1);
                       $dataarr[$var] = '';
				  }
			  }
		 }
													
		 if(empty($varfield)){
			  return $dataarr[$var];
		 }else{
			  return $dataarr[$var][$varfield];
		 }
	 }

	 /*
	 * 写入数据库数据并更新缓存
	 * $del 是否删除数据 0 不删除 进行差异比较进行更新 1 删除 不进行差异比较
	 * 差异比较 用于不同管理组对系统设置的需求
	 */
     public function setDataValue($var, $datavalue, $del=0){
		 $diffdata = array();
		 if(is_array($datavalue) && !$del){
			$odata = $this -> getDataValue($var);
			$diffdata = array_diff_key($odata, $datavalue);//2011-7-8 增加新提交的值与原值进行判断
		 }
		 if(!empty($diffdata)) $datavalue = array_merge($datavalue, $diffdata);
		 import("Components.Date");
		 $odate = new \Date();
         $objdata = $odate -> getDate();
		 $dataarr = array(
			        'var'       => $var,
			        'datavalue' => addslashes(serialize(sstripslashes($datavalue))),
			        'dateline'  => $objdata->getTime()
		            );

		 $db = M();

	     $query = $db->query("SELECT * FROM iiss_sysdata WHERE var='$var' LIMIT 1");

		 if($query){
             $db->updateTable('iiss_sysdata', $dataarr, array('var'=>$var));
		 }else{
             $db->insertTable('iiss_sysdata', $dataarr);
		 }

		 self::delDataValue($var);
	 }

     /**
	 * 删除系统数据
	 */
	 public function delDataValue($var=''){

		 if(!empty($var)){
		    $this -> _objcache->delCache('sysdata/data_'.$var.'.php');
		 }else{
            $this -> _objcache->delCache('sysdata');
		 }
 
	 }

}