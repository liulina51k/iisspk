<?
/**
 * file.class.php
 * 文件操作类
 *
 * 文件的创建、读、写、删除，文件夹的创建、删除
 * @writer	liketian+kelly
 * @time 2009-5-14
 */
class File
{
	private $path;  // 文件路径(含有文件名)
    private $result; //对文件操作后的结果

	/**
	*  构造器
	*
	*/
	public function __construct( $path = '')
	{
        $this->path =  $path ?  $path :'';
	}

	/**
	* 获得文件路径
	*/
	public function getPath()
	{
	    return $this->path;
	}

	/**
	* 设置文件路径
	* @param string $path: 文件路径
	*/
	public function setPath($path)
	{
        $this->path = $path;
	}

    /**
	* 获得文件名
	*/
	public function getFile()
	{
	    return basename($this->path);
	}

	/**
	* 设置文件名
	* @param string $file:文件名
	*/
	public function setFile($file)
	{
		if(is_dir($this->path)){
            $this->path= $this->path.DS.$file;
		}else{
	        $this->path= dirname($this->path).DS.$file;
		}
	}

    /**
	* 取得文件后缀名
	*/
    public function getFileExt()
	{
        return $this->getExt(basename($this->path));
	}
    /**
	 * 取得文件后缀名
	 *
	 * @param string $file 文件的名字
	 * @return string 文件的后缀名
	 */
	public function getExt($file) {
		$dot = strrpos($file, '.') + 1;
		return substr($file, $dot);
	}

	/**
	 * 去除文件后缀名
	 *
	 * @param string $file 文件名
	 * @return string 没有后缀的文件名
	 */
	public static function stripExt($file) {
		return preg_replace('#\.[^.]*$#', '', $file);
	}

	/**
	 * 获得安全的文件名
	 *
	 * @param string $file 文件的名字（不含有路径）
	 * @return string 文件名
	 */
	public static function makeSafe($file) {
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
		return preg_replace($regex, '', $file);
	}

    /**
	*打开文件
	*@param string $readorwrite : 对文件执行操作的方式
	*/
	public function openfile ($readorwrite){
		//首先，查看该文件是否存在
		try {
			if (file_exists ($this->path)){
				//现在，要看看我们对这个文件是否能读写
				$proceed = false;
				if ($readorwrite == "r"){
					if (is_readable($this->path)){
						$proceed = true;
					}
				} elseif ($readorwrite == "w"){
					if (is_writable($this->path)){
						$proceed = true;
					}
				} else {
					if (is_readable($this->path) && is_writable($this->path)){
						$proceed = true;
					}
				}
				try {
					if ($proceed){
						//现在我们尝试打开文件
						try {
							if ($filepointer = fopen ($this->path, $readorwrite)){
								return $filepointer;
							} else {
								throw new IException(1, '“'.$this->getFile().'”文件不能打开');
							}
						} catch (IException $e) {
							$e->display();
						}
					} else {
						throw new IException(1, '“'.$this->getFile().'”文件,操作权限不够');
					}
				}catch (IException $e) {
					$e->display();
				}
			} else {
				throw new IException (1, '你要处理的“'.$this->getFile().'”文件不存在');
			}
		} catch (IException $e) {
			$e->display();
		}
	}

	/**
	*关闭文件
	*/
	public function closefile() {
		try {
			if (!fclose($this->path)){
				throw new IException(1,$this->path."无法关闭");
			}
		} catch (IException $e) {
			$e->display();
		}
	}

	/**
	*  读取文件
	*  把读取结果以字符串的形式返回
	*/
	public function read() {
		//尝试打开文件
		if(!$filepointer = $this->openfile ("r")){
		   return;
		}

		//读取文件内容
		if ($filepointer != false){
			return fread ($filepointer,filesize ($this->path));
		}

		//最后关闭文件
		$this->closefile ();
	}

	/**
	*写入文件
	*@param string $towrite:写到文件的字符串
	*返回文件内容
	*/
	public function write ($towrite) {


		if (!file_exists(dirname($this->path))){
           $this->createFolder();
		}

		//写入文件，并返回文件内容
		file_put_contents($this->path, $towrite);

	}

	/**
	*  把内容添加到文件里
	*  @param string $toappend: 要添加的内容
	*/
	public function append ($toappend) {
		//打开文件.
		$filepointer = $this->openfile ("a");

		//返回字符读取的文件内容
		if ($filepointer != false){
			return fwrite ($filepointer, $toappend);
		}

		//关闭文件
		$this->closefile ();
	}

	/**
	*  删除一个文件或一个文件数组
	*  @param mixed $file:混合类型一个文件名或一个文件名的数组
	*/
    public function delete ($file = array())
	{
	   $files = array();

       if(empty($file)){
		    $files[] = $this->path;
	   }else{
			if (is_array($file)) {
				$files = $file;
			} else {
				$files[] = $file;
			}
       }


		foreach ($files as $file)
		{

			$file = self::cleanPath($file);
            if(file_exists($file)){
				// 尝试修改文件权限
				@chmod($file, 0777);

				try{
					// 权限允许，我们开始删除文件
					if (@unlink($file)) {
						// Do nothing
					} else {
						$filename	= basename($file);
						throw new IException(1, $filename.'文件删除失败！');
						return false;
					}
				}catch(IException $e){
					$e->display();
				}
			}
		}

		return true;

	}

     /**
	 *  一个上传文移动到指定文件夹下
	 *
	 * @param string $src 上传的文件
	 * @param string $dest 要移动到的指定文件夹（可以包含文件名）
	 * @return boolean True on success
	 */
	public function upload($src, $dest)
	{

		$ret = false;

		// Ensure that the path is valid and clean
		$dest = self::cleanPath($dest);

		// Create the destination directory if it does not exist
		$baseDir = dirname($dest);
		if (!file_exists($baseDir)) {
			$this->createFolder($dest);
		}


        try{
			if (is_writeable($baseDir) && move_uploaded_file($src, $dest)) {
				$ret = true;
			} else {
                throw new IException(1,'文件上传失败！出现异常，可能操作权限不够');
			}
        }catch(IException $e){
		    $e->display();
		}
		return $ret;
	}

    /**
	 * 转换路径中的 / 或 \
	 *
	 * @static
	 * @param	string	$path:要转换的路径
	 * @param	string	$ds:系统目录分割符
	 * @return	string	:被整理后的路径
	 */
	public static function cleanPath($path, $ds=DS)
	{
		$path = trim($path);

		if (empty($path)) {
			$path = I_ROOT;
		} else {
			$path = preg_replace('#[/\\\\]+#', $ds, $path);
		}

		return $path;
	}


    /**
	* 创建目录--并且创建所有不存在的父目录
	*
	* @param string $path:要创建一个基础路径
	* @param int $mode:创建目录的权限
	* @return boolean True if successful
	*/

    public function createFolder($path = '', $mode = 0775)
    {

		 $path = $path ? $path : $this->path;
         $path =  self::cleanPath($path);
         // 返回路径中的目录部分
		 $dir = dirname($path);

		 $mdir = I_ROOT;
		 if(!is_dir($dir)){
			$dir = str_replace(I_ROOT, '', $dir);
            foreach( explode(DS, $dir ) as $val ) {
				 
				 $mdir .= DS.$val; 
			     if($val == ".." || $val == "." ) continue;

			     if(!file_exists( $mdir ) ) {
				     try{
						  $u=umask(0);
					      if(!@mkdir ($mdir, $mode)){
						      throw new IException(3,"创建目录 [".$mdir."]失败.");
					      }
						  umask($u);
				      } catch (IException $e){
					       $e->display();
				      }
			     }else{
					 // 尝试修改文件权限
				     @chmod($mdir, 0777);
				 }
		      }

		 }
	}


    /**
	* 列出文件和目录名
	* 本方法用来列出目录里的文件或目录switch为1时按字母顺序列出所有目录和文件
	* switch为2则只列出目录，switch为3时，只列出文件名
	*
	* @param string_type $path:目录路径
	* @param int_type $switch:显示类别
	*/
	public function listfilename($path,$switch)
	{
		$path   =   str_replace("\\","/",$path);
		$path   =   (substr($path,-1)=='/')?$dir=substr($path,0,-1):$path;

		if (!is_dir($path)) {
			 throw new IException (3,"文件".$path."目录不存在");
			 return   false;
		}

		$arr   =   array();

		if (file_exists($path)) {
			$dir = scandir($path);

			//如果switch为1则按字母顺序列出所有目录和文件
			if ($switch==1) {
				for ($i=0;$i<count(dir);$i++) {
					if ($dir[$i]!="." && $dir[$i]!="..") {
						$arr[] = $dir[$i] ;
					}
				}
			}

			if ($switch==2) { //switch为2则只列出目录
				for ($i=0;$i<count($dir);$i++)	{
					$x=is_dir($path.DS.$dir[$i]);
					if ($dir[$i]!="." && $dir[$i]!=".." && $dir[$i]!=".svn" && $x==true){
						$arr[] = $dir[$i];
					}
				}
			}

			if ($switch==3) { //switch为3时，只列出文件名
				for ($i=0;$i<count($dir);$i++)	{
					$x=is_dir($path.DS.$dir[$i]);
					if ($dir[$i]!="." && $dir[$i]!=".." && $x==false){
						$arr[] = $dir[$i];
					}
				}
			}
		}
		return $arr;
	}


	/**
	* 删除目录及目录里所有的文件夹和文件
	* 本方法删除pathname目录，包括该目录下所有的文件及子目录
	*
	* @param string_type $pathname:目录路径
	* @param int $self:1删除本身，0则相反
	*/
	public function delFolder($pathname, $self = 1)
	{
		try{
			if (!is_dir($pathname))	{
				throw new IException(3,"你要删除的目录‘$pathname’不存在");
			}

			$handle=opendir($pathname);
			while (($fileordir=readdir($handle)) !== false) {

				if ($fileordir!="." && $fileordir!="..") {
					try{
						is_dir($pathname.DS.$fileordir)?
						$this->delFolder($pathname.DS.$fileordir):
						unlink($pathname.DS.$fileordir);
					} catch (IException $e){
						throw new IException(3,"删除目录‘$pathname’失败，可能权限不够");//权限不足
					}
				}
			}

			if (readdir($handle) == false) {
				closedir($handle);

				if($self){
				    rmdir($pathname);
				}
			}
		} catch (IException $e){
			$e->display();
		}
	}

}