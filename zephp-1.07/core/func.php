<?php
/**
 * Copyright (c) 2015-2020 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-20
 @todo:   
*/


//取配置
function _reg ($n, $val = NULL) {
    return zz::reg($n, $val);
}

//取设置
function _set ($n, $val = NULL) {
	return zz::set($n, $val);
}

//取id
function _id ($n) {
	$n = isset($_GET[$n]) ? trim($_GET[$n]) : ((isset($_POST[$n]) && is_string($_POST[$n])) ? trim($_POST[$n]) : '');
	return ($n != '' && preg_match('/^\d+$/', $n)) ? $n : 0;
}

//是否id
function _isId ($i) {
	return (is_string($i) || is_numeric($i)) ? preg_match('/^\d+$/', $i) : false;
}

//post
function _post ($n) {
	return isset($_POST[$n]) ? (is_string($_POST[$n]) ? trim($_POST[$n]) : $_POST[$n]) : '';
}

//get
function _get ($n) {
	return isset($_GET[$n]) ? trim($_GET[$n]) : '';
}

//简单过滤
function _key ($str) {
    return preg_replace('/\s+|\'|\"|\*|\;|\\\\|\%|\<|\>/is', '', $str);
}

//解码
function _html ($str) {
	return zz::incClass('core-func-html')->htmlDecode($str);
}

//编码
function _htmlEncode ($str, $isSearch = FALSE) {
	return zz::incClass('core-func-html')->htmlEncode($str, $isSearch);
}

//过滤
function _htmlFilter ($str) {
    return zz::incClass('core-func-htmlfilter')->htmlFilter($str);
}

//cookie
function _cookie ($nick, $val = NULL, $expire = 0, $path = '', $domain = '', $secure = FALSE) {
	return zz::incClass('core-func-cookie')->cookie($nick, $val, $expire, $path, $domain, $secure);
}

//解码json
function _json ($str) {
    return $str != '' ? json_decode($str . '', true) : array();
}

//编码json
function _jsonEncode ($arr, $newCheck = FALSE) {
    return zz::incClass('core-func-jsonencode')->jencode($arr, $newCheck);
}

