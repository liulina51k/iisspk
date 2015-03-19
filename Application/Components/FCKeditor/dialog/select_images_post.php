<?php
require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/../image.func.php");
if(empty($imgfile))
{
	$imgfile='';
}
if(!is_uploaded_file($imgfile))
{
	ShowMsg("你没有选择上传的文件!".$imgfile,"-1");
	exit();
}
$imgfile_name = trim(ereg_replace("[ \r\n\t\*\%\\/\?><\|\":]{1,}",'',$imgfile_name));
if(!eregi("\.(".$cfg_imgtype.")",$imgfile_name))
{
	ShowMsg("你所上传的图片类型不在许可列表，请更改系统对扩展名限定的配置！","-1");
	exit();
}
$nowtme = time();
$sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png","image/xpng","image/wbmp");
$imgfile_type = strtolower(trim($imgfile_type));
if(!in_array($imgfile_type,$sparr))
{
	ShowMsg("上传的图片格式错误，请使用JPEG、GIF、PNG、WBMP格式的其中一种！","-1");
	exit();
}
$mdir = MyDate("ymd",$nowtme);
if(!is_dir($cfg_basedir.$activepath."/$mdir"))
{
	MkdirAll($cfg_basedir.$activepath."/$mdir",$cfg_dir_purview);
	CloseFtp();
}
$filename_name = $cuserLogin->getUserID().'_'.dd2char(MyDate("His",$nowtme).mt_rand(100,999));
$filename = $mdir.'/'.$filename_name;
$fs = explode('.',$imgfile_name);
$filename = $filename.'.'.$fs[count($fs)-1];
$filename_name = $filename_name.'.'.$fs[count($fs)-1];
$fullfilename = $cfg_basedir.$activepath."/".$filename;
move_uploaded_file($imgfile,$fullfilename) or die("上传文件到 $fullfilename 失败！");
@unlink($imgfile);
if(empty($resize))
{
	$resize = 0;
}
if($resize==1)
{
	if(in_array($imgfile_type,$cfg_photo_typenames))
	{
		ImageResize($fullfilename,$iwidth,$iheight);
	}
}
else
{
	if(in_array($imgfile_type,$cfg_photo_typenames))
	{
		WaterImg($fullfilename,'up');
	}
}

$info = '';
$sizes[0] = 0; $sizes[1] = 0;
$sizes = getimagesize($fullfilename,$info);
$imgwidthValue = $sizes[0];
$imgheightValue = $sizes[1];
$imgsize = filesize($fullfilename);
$inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
  VALUES ('0','$filename','".$activepath."/".$filename."','1','$imgwidthValue','$imgheightValue','0','{$imgsize}','{$nowtme}','".$cuserLogin->getUserID()."'); ";
$dsql->ExecuteNoneQuery($inquery);
ShowMsg("成功上传一幅图片！","select_images.php?imgstick=$imgstick&comeback=".urlencode($filename_name)."&v=$v&f=$f&activepath=".urlencode($activepath)."/$mdir&d=".time());
exit();
?>