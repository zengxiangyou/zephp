<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 * @author zengxy.com 1559261757@qq.com
 * @final 2020-06-02
 * @todo mysql数据库操作类
 */

class z_core_db_mysql {
	
	private $conn = NULL; //连接
	private $i = TRUE; //兼容
	private $conns = array();
	
	private $host = ''; //主机
	private $user = ''; //用户名
	private $pwd = ''; //密码
	private $dbname = ''; //数据库
	private $port = '3306'; //默认端口3306(localhost使用)
	private $charset = 'utf8'; //默认编码
	
	function __construct(){
	    if (!function_exists('mysqli_connect')) $this->i = FALSE;
	}
	
	function __destruct(){
	    if ($this->conns) {
	        foreach ($this->conns as $conn) {
	            $this->i ? mysqli_close($conn) : mysql_close($conn);
	        }
	    }
	    unset($this->conns);
	    unset($this->conn);
	}
	
	public function __get($n){
	    if (isset($this->$n)) return $this->$n;
	    return NULL;
	}
	
	//连接，(主机, 用户名, 密码, 数据库, 端口, 编码)
    function init($host, $user = '', $pwd = '', $dbname = '', $port = '', $charset = ''){
	    if (!is_string($host) || $host == '') return FALSE;
	    if (!is_null($this->conn)) {
	        if ($user == '') {
	            if ($host == $this->dbname) return TRUE;
	            return $this->_selectdb($host);
	        }
	        if ($host == $this->host) {
	            if ($user == $this->user) {
	                if ($dbname == $this->dbname) return TRUE;
	                return $this->_selectdb($dbname);
	            }
	        }
	    }
	    $this->host = $host;
	    $this->user = $user;
	    $this->pwd = $pwd;
	    $this->dbname = $dbname;
	    if ($port != '') $this->port = $port;
	    if ($charset != '') $this->charset = $charset;
	    $k = $host . $user;
	    if (isset($this->conns[$k])) {
	        $this->conn = $this->conns[$k];
	        return $this->_selectdb($dbname);
	    }
	    $conn = $this->_conndb();
	    if ($conn) {
	        $this->conns[$k] = $conn;
	        $this->conn = $conn;
	        return TRUE;
	    }
		return FALSE;
	}
	
	//连接库
	private function _conndb(){
	    if ($this->host != '' && $this->user != '' && $this->pwd != '') {
	        if ($this->i) {
	            $conn = @mysqli_connect($this->host, $this->user, $this->pwd, $this->dbname, $this->port);
	            if ($conn) {
	                if (mysqli_set_charset($conn, $this->charset)) return $conn;
	            }
	        } else {
	            if (function_exists('mysql_connect')) {
	                $conn = @mysql_connect($this->host . ':' . $this->port, $this->user, $this->pwd);
	                if($conn) {
	                    if (mysql_select_db($this->dbname, $conn)) {
	                        if (mysql_set_charset($this->charset, $conn)) return $conn;
	                    }
	                }
	            }
	        }
	    }
	    return FALSE;
	}
	
	//选择表
	private function _selectdb($dbname){
	    $this->dbname = $dbname;
	    return $this->i ? mysqli_select_db($this->conn, $dbname) : mysql_select_db($dbname, $this->conn);
	}
	
	//查询
	function query ($sql, $unbuf = FALSE) {
	    if (!$this->conn) return FALSE;
	    if (!$unbuf) return $this->i ? mysqli_query($this->conn, $sql) : mysql_query($sql, $this->conn);
		return $this->query_unbuf($sql);
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
		return $r ? $r : array();
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
		$sql = preg_replace('/(select).*?(from.*)/isU', '$1 count(*) as row $2', $sql);
		$r = $this->get_one($sql);
		if ($r) return $r['row'];
		return 0;
	}
	
	//影响行数
	function get_rowsAffect(){
	    if ($this->conn) {
	        $row = $this->i ? mysqli_affected_rows($this->conn) : mysql_affected_rows($this->conn);
	        if ($row > 0) return $row;
	    }
	    return 0;
	}
	
	//写入
	function db_insert($table, $field){
	    if (is_array($field)) $field = $this->sql($field);
	    if ($this->query_unbuf("insert into `{$table}` set {$field}")) {
	        $id = $this->i ? mysqli_insert_id($this->conn) : mysql_insert_id($this->conn);
	        return $id ? $id : true;
	    }
	    return 0;
	}
	
	//写入，可批量
	function db_insertValue($table, $field, $option = ''){
	    if ($option != '') $option .= ' ';
	    $sql = is_string($field) ? $field : $this->sql($field);
	    if ($sql != '') return $this->query_unbuf("insert {$option}into `{$table}` set {$sql}");
	    return zz::incClass('core-db-mysqlinsert')->insert($table, $field, $option);
	}
	
	//更新
	function db_update($table, $field, $where){
	    if (is_array($field)) $field = $this->sql($field);
		return $this->query_unbuf("update `{$table}` set {$field} where {$where}");
	}
	
	//删除
	function db_delete($table, $where){
		return $this->query_unbuf("delete from `{$table}` where {$where}");
	}
	
	//拼接sql
	function sql ($arr) {
	    $str = '';
	    if (is_array($arr)) {
	        $i = false;
	        foreach ($arr as $k => $v) {
	            if ($i) $str .= ',';
	            else {
	                if (is_array($v)) return '';
	                $i = true;
	            }
	            $str .= "`{$k}`='{$v}'";
	        }
	    }
	    return $str;
	}
	
}