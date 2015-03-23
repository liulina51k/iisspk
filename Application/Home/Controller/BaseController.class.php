<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
	public function __construct() {
        parent::__construct();
    }
	public function showmessage($msgkey, $url_forward='', $second=5){
		$message = $msgkey;
		$message = stristr($message, '</a>') == false ? "<a href=\"$url_forward\">$message</a>" : $message;
		if($url_forward) {
			$message .= "<script>setTimeout(\"window.location.href ='$url_forward';\", ".($second*1000).");</script>";
		}else{
			if($second>0){
				$message = str_replace("=\"\">", "=\"javascript:history.go(-1);\">", $message);
				//$message .= "<script>setTimeout(\"location.href='".IConfig::BASE."'\", ".($second*1000).");</script>";
			}
		}
	    $data['second'] = $second;
	    $data['message'] = $message;
	    $blogurl = C("BLOGURL");
		$this -> assign('second',$second);
		$this -> assign('message',$message);
		$this -> assign('blogurl',$blogurl);
		$this -> display("showmessage");
		exit();
	}
}
