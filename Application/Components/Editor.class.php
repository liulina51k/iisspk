<?php
/**
 * editor.class.php
 * 取得编辑器
 * @writer	kelly
 * @time 2009-6-5
 */
class Editor
{
      /**
	  * 取得默认编辑器
	  */
	  public static function getEditor($fname, $fvalue='',$nheight="350",$etype="Basic",$isfullpage="false")
	  {

	       return self::getFCK($fname,$fvalue,$nheight,$etype,$isfullpage);

	  }

	  /**
	  *  取得FCK编辑器
	  */
	  public static function getFCK($fname, $fvalue='', $nheight="350", $etype="Basic", $isfullpage="false")
	  {
			require_once(APP_PATH.'Components'.DS.'FCKeditor/fckeditor.php');
			$fck = new FCKeditor($fname);
			$fck->BasePath		= 'http://'.$_SERVER["HTTP_HOST"].'/Application/Components/FCKeditor/';//IConfig::BASE.'/include/FCKeditor/' ;
			$fck->Width		= '100%' ;
			$fck->Height		= $nheight ;
			$fck->ToolbarSet	= $etype ;
			$fck->Config['FullPage'] = $isfullpage;
            //启用XHTML
			$fck->Config['EnableXHTML'] = 'true';
			$fck->Config['EnableSourceXHTML'] = 'true';

			$fck->Value = $fvalue ;

			return $fck->CreateHtml();
	  }

	  /**
	  * 取得xheditor编辑器，主要用于投稿，博客留言等
	  */
	  public static function getXh($xname, $text='', $name = 'message', $rows='12', $cols='80',$style='width:80%')
	  {
		  $xheditor = '<script type="text/javascript" src="'.APP_PATH.'Components/xheditor/xheditor.js"></script>';

		  $xheditor .= "<textarea id='$name' name='$name' class='$xname' rows='$rows' cols='$cols' style='$style'>{$text}</textarea>";

		  return $xheditor;
	  }

	  /**
	  * 取得xheditor编辑器，主要用于投稿，博客留言等 无文件加载版
	  */
	  public static function getXhj($xname, $text='', $name = 'message', $rows='12', $cols='80',$style='width:80%')
	  {
		  $xheditor = "<textarea id='$name' name='$name' class='$xname' rows='$rows' cols='$cols' style='$style'>{$text}</textarea>";
		  return $xheditor;
	  }

}

