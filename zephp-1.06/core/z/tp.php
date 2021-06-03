<?php
/**
 * Copyright (c) 2015-2019 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-24
 @todo:   
*/


//初始化
function z_tp_init ($config = array()) {
	zz::incClass('core-mvc-template')->init($config);
}

//注入变量
function z_tp_in ($nick, $val = '') {
	zz::incClass('core-mvc-template')->assign($nick, $val);
}

//显示模板
function z_tp_show ($file) {
	return zz::incClass('core-mvc-template')->show($file);
}

//取值
function z_tp_val ($key = '') {
	return zz::incClass('core-mvc-template')->getValue($key);
}

//替换
function z_tp_rep ($r1, $r2) {
	zz::incClass('core-mvc-template')->replace($r1, $r2);
}

//清缓存
function z_tp_clear () {
	zz::incClass('core-mvc-template')->clear();
}

