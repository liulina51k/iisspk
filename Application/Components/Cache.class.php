<?php
/**
 * cache.class.php
 * 文件缓存类
 *
 * 缓存文件的读、写 主要是针对data文件下的文件
 * @writer	liketian
 * @time 2009-5-12
 * @edittime 2009-5-14 ,kelly
 * 需要引用xml.class.php文件
 */

class Cache
{

	private $cache_dir = 'Data/';  //存放缓存文件的位于根目录的位置
	private $cache_Folder = 'admin,sysdata';//存放缓存文件的文件夹
	private $_objfile;
	//private $cache_time = 604800;//缓存时间 1天=86400秒 默认7天

	/**
	* 构造函数
	* @param string cachedirname:缓存文件夹
	* @param int $expireTime:缓存的时间
	*/
	function __construct($cachefile){
        $this->cache_dir = APP_PATH.$this->cache_dir;
        import("Components.File");
        $this -> _objfile = new \File($this->cache_dir.$cachefile);
	}


	/**
	* 获得缓存文件，此文件为数组形式存放
	* @param string $cachefile:缓存文件名
	*/
    public function getArrayCache($cachefile,$cachetime=604800)
	{
		//获取缓存文件		
		if(file_exists($this->cache_dir.$cachefile)){
			//$lasttime = filemtime($this->cache_dir.$cachefile);
			//2010-10-19增加缓存时间判断
			include $this->cache_dir.$cachefile;
			return $value;
			/*
			if(time()-$lasttime<$cachetime){				
				
			}else{
				@unlink($this->cache_dir.$cachefile);//删除缓存文件
				return false;
			}
			*/
		}

		return false;

	}

	/**
	* 把数组转换成缓存文件
	*/
	public function arrayToFile($cachefile,$arrvalue)
	{
		//写入缓存目录的文件
		if(is_dir($this->cache_dir)){
           $this -> _objfile -> createFolder();
		}

		$s = "<?php\r\n";
		$s .= '$value = '.var_export($arrvalue, TRUE).";\r\n";
		$this -> _objfile -> write($s);

	}

	/**
	* 把XML文件写入缓存文件
	*/
	public function xmlToFile($cachefile, $xmlinfo){

		//写入缓存目录的文件
		if(is_dir($this->cache_dir)){
           $this -> _objfile -> createFolder();
		}

		$this -> _objfile -> write($xmlinfo);
	}

    /**
	*  删除缓存文件或文件夹下所有文件
	*/

	public function delCache($cachefile = ''){

        $filepath='';

        if(empty($cachefile)){

		    $filepath = $this->cache_dir;

		}else{

            $filepath = $this->cache_dir.$cachefile;

		}

		if(is_dir($filepath)){

           $this -> _objfile -> delFolder($filepath, 0);
        }else{

		   $this -> _objfile -> delete($filepath);

		}
	}

}
