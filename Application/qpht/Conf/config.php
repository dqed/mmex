<?php
return array(
	//'配置项'=>'配置值'
	'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/assets/img',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/assets/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/assets/js',
    ),
    'GAME_LIST' =>array(
    	'1'=>'血流麻将',
    	'2'=>'斗地主'
    ),
    //排序字段
    'ORDER_LIST'=>array(
    	'1'=>'login_time'
    ),
    'HANDLENUM'=>'5',
    'JWTKEY'    => 'DQweb006790',
    'DB_CONFIG1' => array(
	    'db_type'  => 'mysql',
	    'db_user'  => 'root',
	    'db_pwd'   => '123456',
	    'db_host'  => '127.0.0.1',
	    'db_port'  => '3306',
	    'db_name'  => 'qpht',
	    'db_charset'=> 'utf8',
	),
	'PAGE_NUM' => 20,
	'TOKEN_OUT_TIME'=>1000000,
);