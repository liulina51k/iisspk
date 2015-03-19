<?php
/**
* hours.clase.php
* 此类针对表iiss_hours的操作，取得与用户有关的数据
* @writer: kelly
* @time: 2011-4-19
*/
use Think\Model;
class Hours extends Model
{
    //数据库对象
    private $_db, $_slavedb;

    /**
	* 构造函数
	*/
	public function __construct(){
     
	}

    /**
	* 取得gethours的信息
	*/
	public function gethours($modelid,$categoryid){
		$this->_slavedb = $this->db(2,"DB_CONFIG2");
		$where = 'modelid='.$modelid.' and categoryid='.$categoryid;
		$sql = 'select hid,dateline from iiss_hours where '.$where.' order by dateline desc LIMIT 1';

		$query = $this->_slavedb->query($sql);
		if($query){
			return $query[0];
		}else{
			return false;
		}
	}

	/**
	* 添加新点击
	*/
	public function add($modelid,$topid,$categoryid,$num=1){
		$topid = intval($topid);
		if(empty($topid)) return false;
		$this->_db = M();
        global $_IGLOBAL;
	    $view = $this->gethours($modelid,$categoryid);
        $now=$_IGLOBAL['timestamp'];
        $dateline=date('y-m-d H',$view['dateline']);
		if($view && $dateline == date('y-m-d H',$now)){
           $sql = "update iiss_hours set count=count+$num,dateline=".$now." where hid=".$view['hid'];
		}else{
           $sql = "insert into iiss_hours (modelid,topid,categoryid,count,dateline) value ($modelid,$topid,$categoryid,$num,".$now.")";
		}

        $this->_db->execute($sql);
		return true;
	}
}