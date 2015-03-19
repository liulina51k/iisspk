<?php
/**
* table.clase.php
* 此类针对表iiss_table的操作，取得表信息
* @time: 2011-5-19
*/

class Table
{
    //数据库对象
    private $_db;

    /**
	* 构造函数
	*/
	public function __construct(){
         $this->_db = M();
	}
	
	/**
	* 取得 数据表名称 table_name
    * 为了能获得表名
	*/
	public function getTableName($table)
	{
		global $_IGLOBAL;
		//获取当前月份 查看数据表中是否有这个表名 如果有则返回 如果没有则新建子表
		$result = $this->_db->table("iiss_table")->where("table_name='$table'")->getField("table_name");
		if($result){
			return $result;
		}else{
			$this->add($table);
			return $table;
		}
	}
    /**
	* 添加数据 添加新的数据表
	*/
	public function add($table)
	{
		global $_IGLOBAL;
		$sql="insert into iiss_table(table_name,create_table) values('".$table."','".$_IGLOBAL['timestamp']."')";
		if($this->_db->execute($sql)){
			$sql1="CREATE TABLE `".$table."` ( `vid` mediumint(10) NOT NULL AUTO_INCREMENT, `modelid` smallint(6) NOT NULL DEFAULT '0', `topid` smallint(6) NOT NULL DEFAULT '0', `categoryid` smallint(6) NOT NULL DEFAULT '0',  `infoid` mediumint(8) unsigned NOT NULL DEFAULT '0', `count` mediumint(8) unsigned NOT NULL DEFAULT '0', `userid` int(10) NOT NULL, `dateline` int(10) unsigned NOT NULL DEFAULT '0', PRIMARY KEY (`vid`), KEY `modelid` (`modelid`,`infoid`,`dateline`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ";
			$this->_db->execute($sql1);
			return true;
		}else{
			return false;
		}
	}
}