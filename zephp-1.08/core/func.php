<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @author: zengxy.com 1559261757@qq.com
 @final:  2019-11-29
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

//是否安全字符
function _isW($k){
    return (is_string($k) || is_numeric($k)) ? preg_match('/^[a-z0-9_\.\,]+$/i', $k) : false;
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
    return preg_replace('/\s+|\'|\"|\*|\;|\\\\|\%|\<|\>/isU', '', $str);
}

//去引号
function _keyTitle ($str) {
    return preg_replace('/\'|\"|\n|\r/iU', '', $str);
}

//安全链接
function _keyUrl ($url) {
    return preg_replace('/\"|\'|\>|\<|\n|\r/iU', '', str_replace("\\", '/', trim($url)));
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

//截取
function _cut($str, $len = 0, $encode = 'UTF-8'){
    return $len > 0 ? mb_substr($str, 0, $len, $encode) : mb_substr($str, $len, NULL, $encode);
}

//错误
function _err($n, $val = NULL){
    return is_null($val) ? zz::errGet($n) : zz::err($n, $val);
}

//长度
function _len($str, $encode = 'UTF-8'){
    return mb_strlen($str, $encode);
}

//包含
function _on($str, $k){
    if (function_exists('str_contains')) return $k != '' && str_contains($str, $k);
    return $k != '' && mb_strpos($str, $k) !== false;
}

