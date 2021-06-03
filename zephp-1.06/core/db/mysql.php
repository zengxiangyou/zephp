<?php
/**
 * Copyright (c) 2015-2019 zengxy.com | Licensed MulanPSL v2
 * @author zengxy 1559261757@qq.com
 * @final 2016-07-22
 * @todo mysql数据库操作类
 */

class z_core_db_mysql {
	
	private $conn = NULL; //连接
	private $i = TRUE; //兼容
	
	private $host = ''; //主机
	private $dbname = ''; //数据库
	private $port = '3306'; //默认端口3306(localhost使用)
	private $charset = 'utf8'; //默认编码
	
	
	function __construct(){
	    if (!function_exists('mysqli_connect')) $this->i = FALSE;
	}
	
	function __destruct(){
		if ($this->conn) {
		    $this->i ? mysqli_close($this->conn) : mysql_close($this->conn);
		    unset($this->conn);
		}
	}
	
	public function __get($n){
	    if (isset($this->$n)) return $this->$n;
	    return NULL;
	}
	
	/**
	 * 
	 * @param unknown $host    主机
	 * @param unknown $user    用户名
	 * @param unknown $pwd     密码
	 * @param unknown $dbname  数据库
	 * @param string $charset  编码
	 * @param string $port     端口
	 * @return boolean
	 */
    function init($host, $user = '', $pwd = '', $dbname = '', $port = '', $charset = ''){
	    if (!is_string($host) || $host == '') return FALSE;
	    if ($user == '') {
	        if ($host == $this->dbname) return true;
	        return $this->_selectdb($host);
	    }
	    if ($host == $this->host) {
	        if ($dbname == $this->dbname) return true;
	        return $this->_selectdb($dbname);
	    }
	    $this->host = $host;
	    $this->dbname = $dbname;
	    if ($port != '') $this->port = $port;
	    if ($charset != '') $this->charset = $charset;
	    if ($this->i) {
	        $this->conn = @mysqli_connect($host, $user, $pwd, $dbname, $this->port);
	        if ($this->conn) return mysqli_set_charset($this->conn, $this->charset);
	    } else {
	        $this->conn = @mysql_connect($host, $user, $pwd);
	        if($this->conn && mysql_select_db($dbname, $this->conn)) return mysql_set_charset($this->charset, $this->conn);
	    }
		return FALSE;
	}
	
	//选择表
	private function _selectdb($dbname){
	    if (!$this->conn) return FALSE;
	    $this->dbname = $dbname;
	    return $this->i ? mysqli_select_db($this->conn, $dbname) : mysql_select_db($dbname, $this->conn);
	}
	
	//查询
	function query ($sql) {
	    if (!$this->conn) return FALSE;
		return $this->i ? mysqli_query($this->conn, $sql) : mysql_query($sql, $this->conn);
	}
	
	//查询，无缓存
	function query_unbuf ($sql) {
	    if (!$this->conn) return FALSE;
		return $this->i ? mysqli_real_query($this->conn, $sql) : mysql_unbuffered_query($sql, $this->conn);
	}
	
    //出错信息
	function get_error(){
	    if (!$this->conn) return '';
	    $err = 0;
	    if ($this->i) {
	        $err = mysqli_errno($this->conn) > 0 ? mysqli_errno($this->conn) . ',' . mysqli_error($this->conn) : 0;
	    } else {
	        $err = mysql_errno($this->conn) > 0 ? mysql_errno($this->conn) . ',' . mysql_error($this->conn) : 0;
	    }
	    return $err;
	}
	
	//取一条数据
	function get_one($sql){
	    $r = array();
	    if ($this->conn) {
	        if (stripos($sql, 'limit ') === FALSE) $sql .= ' limit 1';
	        if ($this->i) {
	            $rs = mysqli_query($this->conn, $sql);
	            if ($rs) {
	                $r = mysqli_fetch_assoc($rs);
	                mysqli_free_result($rs);
	            }
	        } else {
	            $rs = mysql_query($sql, $this->conn);
	            if ($rs) {
	                $r = mysql_fetch_assoc($rs);
	                mysql_free_result($rs);
	            }
	        }
	    }
		return $r;
	}
	
	//取数组
	function get_arr($sql){
	    $arr = array();
	    if ($this->conn) {
	        if ($this->i) {
	            $rs = mysqli_query($this->conn, $sql);
	            if ($rs) {
	                while(($rows = mysqli_fetch_assoc($rs)) != false){
	                    $arr[] = $rows;
	                }
	                mysqli_free_result($rs);
	            }
	        } else {
	            $rs = mysql_query($sql, $this->conn);
	            if ($rs) {
	                while(($rows = mysql_fetch_assoc($rs)) != false){
	                    $arr[] = $rows;
	                }
	                mysql_free_result($rs);
	            }
	        }
	    }
		return $arr;
	}
	
    //数据总数
	function get_rows($sql){
		$sql = preg_replace('/(select).*?(from.*)/i', '$1 count(*) as row $2', $sql);
		$r = $this->get_one($sql);
		if ($r) return $r['row'];
		return 0;
	}
	
	//添加
	function db_insert($table, $field) {
	    if ($this->query_unbuf("insert into `{$table}` set {$field}")) {
	        $id = $this->i ? mysqli_insert_id($this->conn) : mysql_insert_id($this->conn);
	        return $id ? $id : true;
	    }
	    return 0;
	}
	
	//延时写入
	function db_insertDelay($table, $field) {
	    return $this->query_unbuf("insert delayed into `{$table}` set {$field}");
	}
	
	//更新
	function db_update($table, $field, $where){
		return $this->query_unbuf("update `{$table}` set {$field} where {$where}");
	}
	
	//删除
	function db_delete($table, $where) {
		return $this->query_unbuf("delete from `{$table}` where {$where}");
	}
	
	
	//创建数据库
	function database_create($database){
		$sql = "CREATE DATABASE IF NOT EXISTS `{$database}` default character set {$this->charset} COLLATE {$this->charset}_general_ci";
		return $this->query_unbuf($sql);
	}
	
	//查看数据库
	function database_show(){
		return $this->_show('show databases');
	}
	
	//创建数据表
	function table_create($table, $fields, $auto = 1, $type = 'MyISAM'){
	    $aut = $auto && is_numeric($auto) ? ' AUTO_INCREMENT=' . $auto : '';
	    $sql = "CREATE TABLE IF NOT EXISTS `{$table}` ({$fields}) ENGINE={$type} DEFAULT CHARSET={$this->charset}{$aut}";
	    return $this->query_unbuf($sql);
	}
	
	//创建数据表，用于新版phpmyadmin导出的数据
	function table_create2($table, $fields, $add = '', $modify = '', $type = 'MyISAM'){
		$sql = "CREATE TABLE IF NOT EXISTS `{$table}` ({$fields}) ENGINE={$type} DEFAULT CHARSET={$this->charset}";
		$r = $this->query_unbuf($sql);
		if ($r) {
		    if ($add != '') $r = $this->query_unbuf("ALTER TABLE `{$table}` {$add}");
		    if ($modify != '' && $r) $r = $this->query_unbuf("ALTER TABLE `{$table}` {$modify}");
		    return $r;
		}
		return FALSE;
	}
	
	//删除表
	function table_delete($table){
		$a = explode(',', $table);
		foreach ($a as $k => $v) $a[$k] = '`' . $v . '`';
		$table = implode(',', $a);
		return $this->query_unbuf("drop table IF EXISTS {$table}");
	}
	
	//表重命名
	function table_rename($table, $newtable){
		return $this->query_unbuf("rename table `{$table}` to `{$newtable}`");
	}
	
	//查看表
	function table_show(){
		return $this->_show('show tables');
	}
	
	
	//加字段
	function field_add($table, $field){
		return $this->query_unbuf("alter table `{$table}` add ". $field);
	}
	
	//编辑字段
	function field_rename($table, $field, $newfield){
		return $this->query_unbuf("alter table `{$table}` CHANGE `{$field}` {$newfield}");
	}
	
	//删除字段
	function field_delete($table, $field){
		return $this->query_unbuf("alter table `{$table}` drop column ".$field);
	}
	
	//查看字段
	function field_show($table){
		return $this->get_arr("show columns from `{$table}`");
	}
	
	
	//查看
	private function _show($sql){
	    $a = array();
		$rs = $this->get_arr($sql);
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