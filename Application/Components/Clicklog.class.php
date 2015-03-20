<?php
/**
* clicklog.class.php 网站点击统计类 文件记录方式
* 对页面点击数据的统计，
* @writer: guoyabing
* @time: 2012-5-29
*/

class Clicklog
{
    //文件操作对象
    private $_ofile;
    //日期操作对象
    private $_odate;
    //记录当前操作的文件
    private $_file;

    /**
    * 构造函数
    */
    public function __construct(){
    	 import("Components.Date");
         $this->_odate = new \Date();
         import("Components.File");
         $this->_ofile = new \File();
         $this->_file = "";
    }

    /**
    * 获取当前记录文件文件路径
    */
    public function getFile()
    {
        return $this->_file;
    }
    /**
    * 根据modelid设置当前记录文件路径
    */
    public function setFileByModelId($model)
    {
		$filename = str_replace('.', '_', $_SERVER['SERVER_ADDR']).'_'.date('YmdH',$this->_odate->getTime()).'_'.$model.'.txt';
        $file = APP_PATH.'Data'.DS.'log'.DS.$this->_odate->getFullYear().$this->_odate->getMonth().DS.$this->_odate->getDate().DS.$filename;
        $this->_file = $file;
        $this->_ofile->setFile($file);
        $this->_ofile->setPath($file);
    }
    /**
    * 写入记录文件
    */
    public function writeLog($infoid,$topid,$categoryid,$ip,$cookieid1,$cookieid2,$url){
        $logstr = $infoid.','.$topid.','.$categoryid.','.$ip.','.$cookieid1.','.$cookieid2.','.$url.','.$this->_odate->getTime()."\r\n";
        $this->_ofile->append($logstr);
    }
 
    /**
    * 检查记录文件是否存在，不存在则创建
    */
    public function checkFile()
    {
        $path = $this->_file;
        if(!file_exists($path)){
            $this->_ofile->createFolder($path);
        }
        if(!file_exists($this->_file)){
            touch($this->_file);
        }
    }
}
