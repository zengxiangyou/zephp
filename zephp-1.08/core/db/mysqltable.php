<?php
/**
 * @author: zengxy.com 1559261757@qq.com
 * @final:  2019年11月7日
 * @todo:   
 */

class z_core_db_mysqltable{
    
    private $mysql = NULL; //基本类
    
    function __construct(){
        $this->mysql = zz::incClass('core-db-mysql');
    }
    
    //创建数据库
    function database_create($database){
        $sql = "CREATE DATABASE IF NOT EXISTS `{$database}` default character set {$this->mysql->charset} COLLATE {$this->mysql->charset}_general_ci";
        return $this->mysql->query_unbuf($sql);
    }
    
    //查看数据库
    function database_show(){
        return $this->_show('show databases');
    }
    
    //创建数据表
    function table_create($table, $fields, $auto = 1, $type = 'MyISAM'){
        $aut = $auto && is_numeric($auto) ? ' AUTO_INCREMENT=' . $auto : '';
        $sql = "CREATE TABLE IF NOT EXISTS `{$table}` ({$fields}) ENGINE={$type} DEFAULT CHARSET={$this->mysql->charset}{$aut}";
        return $this->mysql->query_unbuf($sql);
    }
    
    //创建数据表，用于新版phpmyadmin导出的数据
    function table_create2($table, $fields, $add = '', $modify = '', $type = 'MyISAM'){
        $sql = "CREATE TABLE IF NOT EXISTS `{$table}` ({$fields}) ENGINE={$type} DEFAULT CHARSET={$this->mysql->charset}";
        $r = $this->mysql->query_unbuf($sql);
        if ($r) {
            if ($add != '') $r = $this->mysql->query_unbuf("ALTER TABLE `{$table}` {$add}");
            if ($modify != '' && $r) $r = $this->mysql->query_unbuf("ALTER TABLE `{$table}` {$modify}");
            return $r;
        }
        return FALSE;
    }
    
    //删除表
    function table_delete($table){
        $a = explode(',', $table);
        foreach ($a as $k => $v) $a[$k] = '`' . trim($v) . '`';
        $table = implode(',', $a);
        return $this->mysql->query_unbuf("drop table IF EXISTS {$table}");
    }
    
    //表重命名
    function table_rename($table, $newtable){
        return $this->mysql->query_unbuf("rename table `{$table}` to `{$newtable}`");
    }
    
    //查看表
    function table_show(){
        return $this->_show('show tables');
    }
    
    //加字段
    function field_add($table, $field){
        return $this->mysql->query_unbuf("alter table `{$table}` add ". $field);
    }
    
    //编辑字段
    function field_rename($table, $field, $newfield){
        return $this->mysql->query_unbuf("alter table `{$table}` CHANGE `{$field}` {$newfield}");
    }
    
    //删除字段
    function field_delete($table, $field){
        return $this->mysql->query_unbuf("alter table `{$table}` drop column ".$field);
    }
    
    //查看字段
    function field_show($table){
        return $this->mysql->get_arr("show columns from `{$table}`");
    }
    
    //查看
    private function _show($sql){
        $a = array();
        $rs = $this->mysql->get_arr($sql);
        if ($rs) {
            foreach ($rs as $r) {
                foreach ($r as $v) {
                    $a[] = $v;
                    break;
                }
            }
        }
        return $a;
    }
    
}
