<?php
return array(
	'DB_TYPE'              =>  'mysql',     // 数据库类型
	'DB_HOST'              =>  '127.0.0.1', // 服务器地址
	'DB_NAME'              =>  'myWeb',          // 数据库名
	'DB_USER'              =>  'root',      // 用户名
	'DB_PWD'               =>  '',          // 密码
	'DB_PORT'              =>  '3306',        // 端口
	'DB_PREFIX'            =>  'w_',    // 数据库表前缀

	'URL_CASE_INSENSITIVE' =>	true,
	'URL_MODEL'            =>	2,
	'URL_HTML_SUFFIX'      =>	'.html',
	//'URL_PATHINFO_DEPR'    =>	'/',
	'MODULE_ALLOW_LIST'    =>	array('Home','HwmAdmin'),
	'DEFAULT_MODULE'       =>	'Home', //默认分组
	'MODULE_DENY_LIST'     =>	array('Common'), //禁止访问分组
	'TMPL_L_DELIM'         =>	'{',
	'TMPL_R_DELIM'         =>	'}',

	'APP_SUB_DOMAIN_DEPLOY'   =>    1,
	'TMPL_PARSE_STRING' => array(
		'__PUBLIC__' =>'/Public'
	),
	//扩展函数库
	// 'LOAD_EXT_FILE'=>'indefinitely',
	//行为日志开关
	'BEHAVIOR_LOG' => false,
	'SHOW_PAGE_TRACE' =>true, 
	//每页条数
	'page_number'=>30
);