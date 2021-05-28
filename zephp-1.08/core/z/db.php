<?php
/**
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
function z_db_query ($sql, $unbuf = FALSE) {
	return zz::incClass('core-db-mysql')->query($sql, $unbuf);
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

//影响行数
function z_db_rowsAffect () {
    return zz::incClass('core-db-mysql')->get_rowsAffect();
}

//写入
function z_db_insert ($table, $field) {
	return zz::incClass('core-db-mysql')->db_insert($table, $field);
}

//写入，可批量
function z_db_insertValue ($table, $field, $option = '') {
    return zz::incClass('core-db-mysql')->db_insertValue($table, $field, $option);
}

//延时写入，新版不支持，从属复制无效
function z_db_insertDelay ($table, $field) {
    return z_db_insertValue($table, $field, 'delayed');
}

//更新
function z_db_update ($table, $field, $where) {
	return zz::incClass('core-db-mysql')->db_update($table, $field, $where);
}

//删除
function z_db_delete ($table, $where) {
	return zz::incClass('core-db-mysql')->db_delete($table, $where);
}

