<?php
/**
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-24
 @todo:   
*/

die('Closed');

require '../zz.class.php';

define('DB_HOST', '');	//主机地址
define('DB_USER', '');	//用户
define('DB_PWD', '');	//密码
define('DB_NAME', '');	//数据库名
define('DB_PORT', '');	//端口

zz::incPhp('core-z-db');
zz::incPhp('core-z-tp');
zz::incPhp('core-z-fn');
zz::incPhp('core-func');

z_db_init(DB_HOST, DB_USER, DB_PWD, DB_NAME, DB_PORT);
z_tp_init(array(
	'template' => './'
	,'cache' => './template_c'
));

z_tp_in('key', 'Hello World!');
z_tp_show('index.html');

