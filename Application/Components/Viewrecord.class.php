<?php
/**
* viewrecord.clase.php
* 此类针对表iiss_viewrecord的操作
* @writer: kelly
* @time: 2009-6-13
*/
class Viewrecord
{
    //数据库对象
    private $_db, $_slavedb;

    /**
	* 构造函数
	*/
	public function __construct(){
         $this->_db = M();
		 //$this->_slavedb = & IFactory::getSlaveDB();
	}

    //取得数据表名称
    public static function getTableName() {
         global $_IGLOBAL;
         
         import("Components.Table");
		 $otable = new \Table();//生成表对象
         
         $now = date('Ym',$_IGLOBAL['timestamp']);
         
         $table = $otable->getTableName('iiss_viewrecord_'.$now);
         return $table;
    }

    /**
	* 取得getViewRecord的信息
	*/
	public function getViewRecord($modelid, $infoid){
         $table=$this->getTableName();
         $where = 'modelid='.$modelid.' and infoid='.$infoid;
		 $sql = 'select vid,count,dateline from '.$table.' where '.$where.' order by dateline desc Limit 1';

		 $query = $this->_db->query($sql);
         if($query){
		    return $query[0];
		 }else{
		    return false;
		 }
	}
	/**
	* 取得getViewRecord的点击数量
	*/
	public function getSum($modelid, $infoid, $starttime = 0, $endtime = 0){

		 $where = 'modelid='.$modelid.' and infoid='.$infoid;
		 $where .= $starttime > 0 ? 'and dateline>='.$starttime : '';
		 $where .= $endtime > 0 ? 'and dateline<='.$endtime : '';

		 $sql = 'select sum(`count`) as sumval from '.$table.' where '.$where;
         $query = $this->_db->query($sql);
         if($value = $this->_db->fetch_array($query)){
		    return $value['sumval'];
		 }else{
		    return false;
		 }

	}

	/**
	* 添加新点击
	*/
	public function add($modelid, $topid, $categoryid , $infoid, $num=1, $userid=0){
        global $_IGLOBAL,$table;
		if(empty($topid) || empty($categoryid) || empty($infoid)) return false;

	    $view = $this->getViewRecord($modelid, $infoid);
        $table = $this->getTableName();
        $now = date('y-m-d',$_IGLOBAL['timestamp']);
        $dateline = date('y-m-d',$view['dateline']);

		if($view && $dateline == $now){
			$sql = 'update '.$table.' set count=count+'.$num.',dateline='.$_IGLOBAL['timestamp'].' where vid='.$view['vid'];
		}else{
			$sql = "insert into ".$table." (modelid,topid,categoryid, infoid, count, userid, dateline) value ($modelid,$topid,$categoryid,$infoid,$num,$userid,".$_IGLOBAL['timestamp'].")";
		}
        $this->_db->execute($sql);
		return true;
	}

    /**
	*  删除tag
	*/
	public function del($modelid, $infoid){
		if(empty($infoid)) return false;
		$table = $this->getTableName();
		$where = 'modelid='.$modelid.' and infoid='.$infoid;
		$sql = 'delete from '.$table.' where '.$where;
		$query = $this->_db->query($sql);
		return true;
	}
	/**
	* 获取信息排行
	*/
	public function getTopList($num, $whe){
		$table = $this->getTableName();
		$artlist = array();
		$sql = "SELECT infoid FROM $table WHERE $whe GROUP BY infoid ORDER BY sum( count ) DESC LIMIT 0 , $num";
		$query = $this->_db->query($sql);

		while($value=$this->_db->fetch_array($query)){
			$key = $value['infoid'];
			$artlist[$key] = $value['infoid'];
		}
		return $artlist;
	}
}
