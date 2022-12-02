<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2015-6-11
 @todo:   
*/


//执行，无缓存
function z_mysql_query ($sql) {
	return zz::incClass('core-db-mysql')->query_unbuf($sql);
}

//创建数据库
function z_mysql_databaseCreate ($database) {
    return zz::incClass('core-db-mysqltable')->database_create($database);
}

//查看数据库
function z_mysql_databaseShow(){
    return zz::incClass('core-db-mysqltable')->database_show();
}

//创建数据表
function z_mysql_tableCreate ($table, $fields, $auto = 1, $type = 'MyISAM') {
	return zz::incClass('core-db-mysqltable')->table_create($table, $fields, $auto, $type);
}

//创建数据表，用于新版phpmyadmin导出的数据
function z_mysql_tableCreate2 ($table, $fields, $add = '', $modify = '', $type = 'MyISAM') {
    return zz::incClass('core-db-mysqltable')->table_create2($table, $fields, $add, $modify, $type);
}

//删除表
function z_mysql_tableDelete ($table) {
	return zz::incClass('core-db-mysqltable')->table_delete($table);
}

//查看表
function z_mysql_tableShow () {
	return zz::incClass('core-db-mysqltable')->table_show();
}

//加字段
function z_mysql_fieldAdd ($table, $field) {
	return zz::incClass('core-db-mysqltable')->field_add($table, $field);
}

//编辑字段
function z_mysql_fieldRename ($table, $field, $newfield) {
	return zz::incClass('core-db-mysqltable')->field_rename($table, $field, $newfield);
}

//删除字段
function z_mysql_fieldDelete ($table, $field) {
	return zz::incClass('core-db-mysqltable')->field_delete($table, $field);
}

//查看字段
function z_mysql_fieldShow ($table) {
	return zz::incClass('core-db-mysqltable')->field_show($table);
}
