<?php
/**

基础----------------

//引入zephp核心类
require 'zephp/zz.class.php';
注：这是一个包含静态方法的类，可自动触发对自身类的实例化，实现对变量的数据存储

//获取版本信息
zz::version($k = '');
注：返回'zephp/v.php'文件内的数组，$k为取其中某一字段，$k空时返回全部的一个数组

//设置和返回错误信息
zz::err($nick = '', $val = NULL);
注：当不存在参数时，返回全部错误信息的一个数组

//设置和返回数据
zz::set($nick = NULL, $val = NULL);
注：$val不存在时为返回数据，方便在其他函数内部调用所设数据

//返回设置的数据
zz::setGet($nick, $key = '');
注：$nick为字符串时，返回zz::set()所设的数据，$nick为数组时，则返回这个数组的$key字段值

//设置数据
zz::setDef($nick, $def = NULL);
注：如果zz::set()中不存在$nick健则加入这组设置，即不会覆盖之前的设置

//设置或返回注册的数据
zz::reg($nick, $val = NULL);
注：$val为空时，会自动在已设置的目录内查找注册文件，$nick格式为"文件名_关键词"，整个字符串为一个字段

//配置注册文件所在目录
zz::regDir($dir);
注：可多次调用，即可存在多个目录

//引入一个PHP源文件
zz::incPhp($f, $root = '', $f2 = '');
注：$f可用"-"代替"/"，不用写".php"，$root为所在目录，$f2存在时代替$f的路径，这时$f仅作为唯一标识
注2：为确保$f标识的唯一性，可在前面加"字符:"，确保不会重复加载源文件

//引入且实例化一个类
zz::incClass($f, $root = '', $className = '');
注：会先调用zz::incPhp()，类名为$f的"-"改为"_"，或使用$className类名

//获取配置数据
zz::incData($f, $root = '');
注：$f为相对路径，如果是php文件则引入为数组，否则直接读取文本

//读取文件
zz::read($f);

//写入文件
zz::write($f, $data, $cover = TRUE);
注：$data为数组时会在前面"return"以便于调用，$cover为0时不覆盖已存在文件

//新建目录
zz::mkdirs($dir);

//删除文件
zz::del($f, $safeRoot = '');
注：$f以"/"结尾时为删除目录，$safeRoot不为空只允许删除这个目录下的文件

//复制文件
zz::copy($f, $f2 = '');
注：$f2空时复制到当前运行目录


模板引擎----------------------------------
zz::incPhp('core-z-tp');

//初始化
z_tp_init($config = array());
注：$config数组内字段template和cache为模板和缓存的目录

//注入变量
z_tp_in($nick, $val = '');
注：$nick可为数组，模板中调用方法：{$nick}
注2：调用数组方法：{$nick.val}

//显示模板
z_tp_show($file);
注：$file为template字段设定的目录下的html模板文件
注2：模板中引用其他模板的方法：{= other.html }

//取值
z_tp_val($key = '');
注：返回内部变量值

//替换
z_tp_rep($r1, $r2);
注：引入生成缓存前的正则替换规则

//清缓存
z_tp_clear();
注：清除cache目录的缓存文件，显示模板时，如果文件不存在时会自动生成


数据库----------------------
zz::incPhp('core-z-db');
zz::incPhp('core-z-mysql');

//初始化
z_db_init($host, $user = '', $pwd = '', $dbname = '', $port = '', $charset = '');
注：$host本地地址为localhost

//执行
z_db_query($sql);
注：sql语句用双引号时，$变量会被运行替换

//错误
z_db_error();
注：返回上一条语句执行的错误

//一条数据
z_db_one($sql);
注：返回一条数据的一个数组，语句会自动加入"limit 1"

//数据列表
z_db_list($sql);
注：建议语句中包含limit

//数据总数
z_db_rows($sql);
注：语句如select * from ...

//添加
z_db_insert($table, $field);
注：插入一条数据，$field可为数组（字段要与表中字段对应）

//延时写入
z_db_insertDelay($table, $field);

//更新
z_db_update($table, $field, $where);
注：$where条件如"id=1"

//删除
z_db_delete($table, $where);

//执行，无缓存
z_mysql_query($sql);
注：无数据返回，可用于更新、删除

//创建数据库
z_mysql_databaseCreate($database);

//查看数据库
z_mysql_databaseShow();
注：返回服务器上所有数据库名的一个数组

//创建数据表
z_mysql_tableCreate($table, $fields, $auto = 1, $type = 'MyISAM');
注：$fields为导出数据库时的字符串

//创建数据表，用于新版phpmyadmin导出的数据
z_mysql_tableCreate2($table, $fields, $add = '', $modify = '', $type = 'MyISAM');

//删除表
z_mysql_tableDelete($table);

//查看表
z_mysql_tableShow();
注：返回当前数据库内所有表名的一个数组

//加字段
z_mysql_fieldAdd($table, $field);

//编辑字段
z_mysql_fieldRename($table, $field, $newfield);

//删除字段
z_mysql_fieldDelete($table, $field);

//查看字段
z_mysql_fieldShow($table);
注：返回当前表内所有字段的一个数组


常用------------------------------
zz::incPhp('core-z-fn');

//取远程资源
z_fn_curl($url, $str = NULL, $timeout = 30, $setArr = array());
注：$str=null时为get方式，否则为post方式

//拼接url
z_fn_url($arr, $forpost = FALSE);
注：$arr为数组，$forpost为1时字符串前不加"?"

//拼接sql
z_fn_sql($arr);

//随机密码
z_fn_pwd($len = 8, $str = '');
注：$str不为空时，字符为在$str内随机获取

//时间
z_fn_time($t, $type = 0);
注：格式化时间，$type为1时"Y-m-d H:i"

//用户名
z_fn_isNick($str);

//加密
z_fn_encode($txt, $key = '');
注：$key为私密，解密时也要传入这个$key

//解密
z_fn_decode($txt, $key = '');

//上传
z_fn_upload($checkArr, $updir, $fname = '', $streamFix = '');
注：$checkArr为z_fn_uploadCheck()返回的数组，$streamFix为流文件时保存的后缀（当上传手机截图时）

//上传检查
z_fn_uploadCheck($postname, $maxsize = 1024, $types = '');
注：$types默认"jpg,jpeg,png"

//xml转数组
z_fn_xml($str);

//生成 xml
z_fn_xmlSet($rootItem, $arr, $item = 'item');
注：$item键为数字时的标签名

//防注入
z_fn_noInject($str, $isSearch = FALSE);
注：防sql注入，放入搜索语句时$isSearch为1

//防xss
z_fn_noXss($str);
注：让注入到html内的js失效

//获取ip
z_fn_ip($reLong = FALSE);

//跳转
z_fn_go($url = '', $msg = '');


常用（敏捷）---------------------
zz::incPhp('core-func');

//取配置
_reg($n, $val = NULL);

//取设置
_set($n, $val = NULL);

//取id
_id($n);
注：获取get或post提交的数字，非数字返回0

//是否id
_isId($i);
注：正则判断是否为数字，0也返回true

//post
_post($n);
注：$_POST[]

//get
_get($n);
注：$_GET[]与去前后空格

//简单过滤
_key($str);
注：用于搜索，保留简单安全字符串

//解码
_html($str);

//编码
_htmlEncode($str, $isSearch = FALSE);
注：安全入库

//过滤
_htmlFilter($str);
注：对用户输入的内容自动删除一些危险html代码

//cookie
_cookie($nick, $val = NULL, $expire = 0, $path = '', $domain = '', $secure = FALSE);
注：$val空时为返回，$expire为秒，86400为一天

//解码json
_json($str);
注：返回数组

//编码json
_jsonEncode($arr, $newCheck = FALSE);
注：$newCheck为1时使用php的对中文不转码的新特性，但不一定满足应用需求


简单开始-----------------------------------------

require 'zephp/zz.class.php';

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


应用模块----------------------
目录：zephp/z1/common/

//返回IP所在地区
zz::incPhp('z1-common-area');
z1_common_areaGet($ip = '');
注：$ip空时自动获取当前IP

//返回客户端浏览器信息
zz::incPhp('z1-common-client');
z1_common_client();

//返回目录大小
zz::incPhp('z1-common-dir');
z1_common_dirSize($dir);

//远程下载
zz::incPhp('z1-common-download');
z1_common_download($f, $f2);
注：$f远程路径，$f2本地保存文件路径

//按比例压缩图片
zz::incPhp('z1-common-image');
z1_common_imageResize($f, $maxWidth = 1000, $maxHeight = 2000, $quality = 70, $limit = 100);
注：压缩后还是保存为$f路径

//memcached缓存
zz::incPhp('z1-common-memcached');
z1_common_memcached($key = '', $value = NULL, $timeout = 3600, $prefix = '', $server = array());
注：$key为null时清空

//分页
zz::incPhp('z1-common-pageTurning');
z1_common_pageTurning($sql, $pagesize = 0, $g = '');

//处理模板中的文件路径
zz::incPhp('z1-common-path');
z1_common_pathRe($str, $key, $host = ''); //加根路径
z1_common_pathUn($str, $key); //去根路径
注：$key为查询替换的标识

//权限
zz::incPhp('z1-common-power');
z1_common_power($key = '', $keyroot = '', $power = NULL);
注：$key字段值与$power数字值进行大小比较，$key中"_"前部分为$keyroot目录下的文件名， $power为当前用户的权限id

//数据表
zz::incPhp('z1-common-table');
z1_common_tableImport($f, $dbname = ''); //导入数据表，文件中以换行分隔
z1_common_tableDelete($table, $dbname = ''); //删除表
z1_common_tableDown($tables = '', $where = ''); //导出数据库
注：$dbname空时为当前数据库

//调用模板
zz::incPhp('z1-common-tp');
z1_common_tpRePath($set, $path = ''); //初始化，$set为数组，必须配置template和cache所在目录
z1_common_tpLangChange($arr, $lang = ''); //自改语言，数组的健没有以$lang结尾的健值自动替换为以$lang结尾的数据
注：模板中的相对文件路径自动替换为相对根目录的文件路径

//MVC实现
zz::incPhp('z1-common-tpinc');
z1_common_tpInc($root, $file, $cla = ''); //加载类
z1_common_tpIncRun($root, $m, $f = ''); //执行函数
注：$root为模块目录，$m为执行类文件（"-"连接目录名，最后一个为文件名），$f为类中的方法

//解压缩
zz::incPhp('z1-common-zip');
z1_common_zipOpen($f, $dir = './'); //解压
z1_common_zipDown($f); //下载zip文件
z1_common_zipAdd($f, $dir); //压缩目录






*/