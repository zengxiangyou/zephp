<?php
/**
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-21
 @todo:   
*/


//取远程资源
function z_fn_curl ($url, $str = NULL, $timeout = 30, $setArr = array()) {
	return zz::incClass('core-func-curl')->urlGet($url, $str, $timeout, $setArr);
}

//拼接url
function z_fn_url ($arr, $forpost = FALSE) {
	return zz::incClass('core-func-str')->url($arr, $forpost);
}

//拼接sql
function z_fn_sql ($arr) {
	return zz::incClass('core-func-str')->sql($arr);
}

//随机密码
function z_fn_pwd ($len = 8, $str = '') {
	return zz::incClass('core-func-str')->pwd($len, $str);
}

//时间
function z_fn_time ($t, $type = 0) {
	return zz::incClass('core-func-str')->time($t, $type);
}

//用户名
function z_fn_isNick ($str) {
	return is_string($str) ? preg_match('/^[a-z][a-z0-9_]*$/i', $str) : 0;
}

//加密
function z_fn_encode ($txt, $key = '') {
	return zz::incClass('core-func-encode')->encode($txt, $key);
}

//解密
function z_fn_decode ($txt, $key = '') {
	return zz::incClass('core-func-encode')->decode($txt, $key);
}

//上传
function z_fn_upload ($checkArr, $updir, $fname = '', $streamFix = '') {
	return zz::incClass('core-func-upload')->upload($checkArr, $updir, $fname, $streamFix);
}

//上传检查
function z_fn_uploadCheck ($postname, $maxsize = 1024, $types = '') {
	return zz::incClass('core-func-upload')->uploadCheck($postname, $maxsize, $types);
}

//xml转数组
function z_fn_xml ($str) {
	return zz::incClass('core-func-xml')->xmlToArray($str);
}

//生成 xml
function z_fn_xmlSet ($rootItem, $arr, $item = 'item') {
	return zz::incClass('core-func-xml')->arrayToXml($rootItem, $arr, $item);
}

//防注入
function z_fn_noInject ($str, $isSearch = FALSE) {
	return zz::incClass('core-func-htmlsafe')->noInject($str, $isSearch);
}

//防xss
function z_fn_noXss ($str) {
    return zz::incClass('core-func-htmlsafe')->noXss($str);
}

//ip
function z_fn_ip ($reLong = FALSE) {
	return zz::incClass('core-func-get')->clientIP($reLong);
}

//跳转js
function z_fn_go ($url = '', $msg = '') {
    return zz::incClass('core-func-link')->go($url, $msg);
}

