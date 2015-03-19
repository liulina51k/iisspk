<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'               =>  'mysql',     // 数据库类型
	'DB_HOST'               =>  '127.0.0.1', // 服务器地址
	'DB_NAME'               =>  'cis',          // 数据库名
	'DB_USER'               =>  'root',      // 用户名
	'DB_PWD'                =>  'root',          // 密码
	'DB_PORT'               =>  '3306',        // 端口
	'DB_PREFIX'             =>  'iiss_',    // 数据库表前缀
	'DB_CHARSET'            =>  'utf8',      // 数据库编码
	'DB_DEBUG'              =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
	//数据库配置2
    'DB_CONFIG2' => 'mysql://root:root@localhost:3306/cis',
	// 开启路由
    'URL_ROUTER_ON'   => true, 
    'URL_ROUTE_RULES'=>array(
       'pkt/index/:id' => 'Pkt/index',
       'pkt/plist/:p' => 'Pkt/plist',
       'pkt/app/:id/:p' => 'Pkt/app',
       'pkt/opp/:id/:p' => 'Pkt/opp',
       'pk/index/:id' => 'Pk/index',
       'pk/plist/:p' => 'Pk/plist',
       'pk/app/:id/:p' => 'Pk/app',
       'pk/opp/:id/:p' => 'Pk/opp',
       'conference/index/:id' => 'Conference/index',
       'conference/preview/:id' => 'Conference/preview',
    ),
);