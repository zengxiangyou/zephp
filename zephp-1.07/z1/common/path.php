<?php
/**
 * Copyright (c) 2015-2020 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2015-8-31
 @todo:   
*/

//加根路径
function z1_common_pathRe ($str, $key, $host = '') {
	if (!is_array($str)) {
		if (is_string($str) && stripos($str, $key) !== false) {
			$key = str_replace(array('/'), array('\/'), $key);
			return preg_replace('/([\'\"\(]|^)[^\'\"\>\)]*('. $key .')/is', '\\1'. $host . '\\2', $str);
		}
		return $str;
	}
	foreach ($str as $k => $v) {
	    if (is_numeric($v) || (is_string($v) && ($v == '' || stripos($v, $key) === false))) continue;
	    $str[$k] = z1_common_pathRe($v, $key, $host);
	}
	return $str;
}

//纯路径
function z1_common_pathUn ($str, $key) {
	if (!is_array($str)) {
		if (is_string($str) && stripos($str, $key) !== false) {
			$key = str_replace(array('/'), array('\/'), $key);
			return preg_replace('/([\'\"\(]|^)[^\'\"\>\)]*('. $key .'\/)/is', '\\1\\2', $str);
		}
		return $str;
	}
	foreach ($str as $k => $v) {
	    if (is_numeric($v) || (is_string($v) && ($v == '' || stripos($v, $key) === false))) continue;
	    $str[$k] = z1_common_pathUn($v, $key);
	}
	return $str;
}