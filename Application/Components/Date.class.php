<?php
/**
* Date类
* 提供一种简单，明确的基本方法来处理日期
* @writer : kelly
* @time : 2009-4-2
*/

class Date
{
	//本地的日期/时间在几秒钟内
    //时间戳
    protected $_time;

    ////静态方法

    /**
	*格式化时间成时间戳
    *@param string $date 时间格式字符串
	*return 时间戳
	*/
    public static function parse($date)
    {
        return strtotime($date);
    }

	/**
	*返回一个本地日期和时间表的PRC时间戳
	*@param int $year 年
	*@param int $month 月 (1-12)
	*@param int $day 日
	*@param int $hour 时(可选)
	*@param int $minutes 分(可选)
	*@param int $seconds 秒(可选)
	*注意：以上参数顺序不能变
	*返回一个本地日期和时间表的PRC时间戳
	*注意，在Windows上无法处理UTC时间1970年1月1日12.a.m以前的日期或时间
	*/
    public static function PRC($year, $month, $day)
    {
        $hours = $minutes = $seconds = 0;
        $num_args = func_num_args();

        if($num_args > 3)
            $hours = func_get_arg(3);
        if($num_args > 4)
            $minutes = func_get_arg(4);
        if($num_args > 5)
            $seconds = func_get_arg(5);

        return mktime($hours, $minutes, $seconds, $month, $day, $year);
    }

    /**
    *构造函数
	*参数情况：
	*1。没有: Date实例对应于当前的本地日期和时间
	*2，1个，int: 一个本地的时间戳
	*3，1个，string: 参数解释为RFC-1123格式的一个本地时间戳（例如Web.8 May 1996 17:46:40-0500）
	*4, 2-6个 int : 4位的年数，月（1-12），日（0-31），小时（0-23），分钟（0-59），秒（0-59）
	*
	*/
    public function __construct()
    {
       $num_args = func_num_args();

       if($num_args > 0){
           $args = func_get_args();

           if(is_array($args[0]) ){
               $args = $args[0];
               $num_args = count($args);
           }
           if($num_args > 1){
              $seconds = $minutes = $hours = $day = $month = $year = 0;
		   }
       }

       switch($num_args){
          case 6:
              $seconds = $args[5];
          case 5:
              $minutes = $args[4];
          case 4:
              $hours = $args[3];
          case 3:
              $day = $args[2];
          case 2:
              $month = $args[1];
              $year = $args[0];
              $this->_time = mktime($hours, $minutes, $seconds, $month, $day , $year);
              break;
          case 1:
              if( is_int($args[0]) ){
                 $this->_time = $args[0];
              }elseif( is_string($args[0]) ){
                 $this->_time = strtotime($args[0]);
              }
              break;
          case 0:
              $this->_time = mktime();
              break;
        }

        //  偏移时间加到本地时间昊
        //  $localNow = new Date();
        //  $UTCNow = $localNow->setMinutes(
        //              $localNow->getMinutes() + $localNow->getTimeZoneOffset()
        //                                 );

        $temp = gettimeofday();
        $this->_offset = (int)$temp["minuteswest"];
    }

    //格式化的日期
    public function toString()
    {
         return date('Y-m-d H:i:s', $this->_time);
    }

    // 返回这个月的第几天 (1-31)
    public function getDate()
    {
         return (int)date("j", $this->_time );
    }

    // 一周的第几天 (0=Sunday, 6=Saturday)
    public function getDay()
    {
        return (int)date("w", $this->_time );
    }

    // 返回四位数字的年
    public function getFullYear()
    {
        return (int)date("Y", $this->_time );
    }

    //  返回小时数 (0-23)
    public function getHours()
    {
        return (int)date("H", $this->_time );
    }

    // 返回分钟数 (0-59)
    public function getMinutes()
    {
        return (int)date("i", $this->_time );
    }

    // 返回月数 (1-12)
    public function getMonth()
    {
        $temp = (int)date("n", $this->_time );
        return $temp;
    }

    //  返回秒数 (0-59)
    public function getSeconds()
    {
        return (int)date("s", $this->_time );
    }

    //  返回时间戳
    public function getTime()
    {
        return $this->_time;
    }


    // 设置日期的天   (1-31)
    public function setDate($date)
    {
        $this->_time = mktime(
                             $this->getHours(),
                             $this->getMinutes(),
                             $this->getSeconds(),
                             $this->getMonth(),
                             $date,
                             $this->getFullYear()
                            );

        return $this->_time ;
    }

    //设置四位的年
	public function setFullYear($year)
	{
	    $this->_time = mktime(
			                  $this->getHours(),
                              $this->getMinutes(),
                              $this->getSeconds(),
                              $this->getMonth(),
                              $this->getDate(),
                              $year
			                 );
	}
    // 设置日期的小时(0-23)
    public function setHours($hours)
    {
       $this->_time = mktime(
                            $hours,
                            $this->getMinutes(),
                            $this->getSeconds(),
                            $this->getMonth(),
                            $this->getDate(),
                            $this->getFullYear()
                           );

      return $this->_time ;
    }

    // 设置日期的分钟 (0-59)
    public function setMinutes($minutes)
    {
       $this->_time = mktime(
                            $this->getHours(),
                            $minutes,
                            $this->getSeconds(),
                            $this->getMonth(),
                            $this->getDate(),
                            $this->getFullYear()
                           );

       return $this->_time ;
    }

    // 设置日期的月份 (1-12)
    public function setMonth($month)
    {
       $this->_time = mktime(
                            $this->getHours(),
                            $this->getMinutes(),
                            $this->getSeconds(),
                            $month,
                            $this->getDate(),
                            $this->getFullYear()
                            );

       return $this->_time ;
    }

    // 设置日期的分钟 (0-59)
    public function setSeconds($seconds)
    {
        $this->_time = mktime(
                             $this->getHours(),
                             $this->getMinutes(),
                             $seconds,
                             $this->getMonth() + 1,
                             $this->getDate(),
                             $this->getFullYear()
                             );

        return $this->_time ;
     }


    //设置时间以来的秒数的Unix时代
    public function setTime($time)
    {
       $this->_time = $time;

       return $this->_time ;
    }


    // getTime的同名函数
    public function valueOf()
    {
       return $this->_time ;
    }
}