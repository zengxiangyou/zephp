<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @author: zengxy.com 1559261757@qq.com
 @final:  2019-11-14
 @todo:   
*/

class z_core_func_str {
	
	//拼接url
    function url ($arr, $forpost = FALSE) {
        if (is_array($arr) && !empty($arr)) {
            $str = http_build_query($arr);
            return !$forpost ? '?' . $str : $str;
        }
        return '';
	}
	
	//随机密码
	function pwd ($len = 8, $str = '') {
		$str2 = '';
		if (is_numeric($len)) {
		    if ($str == '' || !is_string($str)) $str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $len2 = strlen($str) - 1;
		    for ($i = 0; $i < $len; $i++) {
		        $str2 .= substr($str, mt_rand(0, $len2), 1);
		    }
		}
		return $str2;
	}
	
	//格式化时间
	function time ($t, $type = 0) {
		if (preg_match('/^\d+$/', $t)) {
			if (is_numeric($type)) {
				switch ($type) {
					case 0: $t = date('Y-m-d', $t); break;
					case 1: $t = date('Y-m-d H:i', $t); break;
					case 2: $t = date('Y-m-d H:i:s', $t); break;
				}
			} else {
				$t = date($type, $t);
			}
		}
		return $t;
	}
	
}