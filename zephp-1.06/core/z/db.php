<?php
/**
 * Copyright (c) 2015-2019 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-22
 @todo:   
*/

//初始化
function z_db_init ($host, $user = '', $pwd = '', $dbname = '', $port = '', $charset = '') {
	if (!zz::incClass('core-db-mysql')->init($host, $user, $pwd, $dbname, $port, $charset)) {
		$err = z_db_error();
		if ($err == '') $err = 'no mysql';
		echo 'Error: ' . $err . '<br>';
		return false;
	}
	return true;
}

//执行
function z_db_query ($sql) {
	return zz::incClass('core-db-mysql')->query($sql);
}

//错误
function z_db_error () {
	return zz::incClass('core-db-mysql')->get_error();
}

//一条数据
function z_db_one ($sql) {
	return zz::incClass('core-db-mysql')->get_one($sql);
}

//数据列表
function z_db_list ($sql) {
	return zz::incClass('core-db-mysql')->get_arr($sql);
}

//数据总数
function z_db_rows ($sql) {
	return zz::incClass('core-db-mysql')->get_rows($sql);
}

//添加
function z_db_insert($table, $field) {
	if (is_array($field)) $field = zz::incClass('core-func-str')->sql($field);
	return zz::incClass('core-db-mysql')->db_insert($table, $field);
}

//延时写入
function z_db_insertDelay($table, $field) {
    if (is_array($field)) $field = zz::incClass('core-func-str')->sql($field);
    return zz::incClass('core-db-mysql')->db_insertDelay($table, $field);
}

//更新
function z_db_update($table, $field, $where){
	if (is_array($field)) $field = zz::incClass('core-func-str')->sql($field);
	return zz::incClass('core-db-mysql')->db_update($table, $field, $where);
}

//删除
function z_db_delete($table, $where) {
	return zz::incClass('core-db-mysql')->db_delete($table, $where);
}
