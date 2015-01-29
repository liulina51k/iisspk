<?php

//定义通用路径分割符
define('DS', DIRECTORY_SEPARATOR);

//定义全局数组
$_IGOLBAL = $_ISMDATA = array();

//程序根目录
$siteroot = explode(DS, dirname(__FILE__));
array_pop($siteroot);
define('I_ROOT', implode(DS, $siteroot));

$mtime = explode(' ', microtime());
$_IGLOBAL['timestamp'] = $mtime[1];

//初始化
$_IGLOBAL['supe_uid'] = 0;
$_IGLOBAL['supe_username'] = '';
$_IGLOBAL['inajax'] = empty($_GET['inajax'])? 0 : intval($_GET['inajax']);
$_IGLOBAL['refer'] = empty($_SERVER['HTTP_REFERER'])? '' : $_SERVER['HTTP_REFERER'];


//处理REQUEST_URI
if(!isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
	if(isset($_SERVER['QUERY_STRING'])) $_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
}

//时间本地化
//date_default_timezone_set ('Etc/GMT-8');
//设置编码格式
header('Content-Type:text/html;charset=utf-8');