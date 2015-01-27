<?php
namespace Home\Model;
use Think\Model;
class PkModel extends Model{
	/**
	*  取得PK言论
	*/
	public function get_pk()
	{
	    static $pkarr = array();
	    
	    $pkarr = $this->select();
	    printr($pkarr);
		return $field ? $pkarr[$field] : $pkarr;
	}
}