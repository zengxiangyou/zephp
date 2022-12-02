<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2016-1-19
 @todo:   
*/

class z_core_func_get {
	
	
	/**
	 * 客户端ip
	 * @param string $reLong
	 * @return Ambigous <unknown, string>|Ambigous <string, number>
	 */
    function clientIP ($reLong = FALSE) {
        $ip = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $k = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if ($k != 'unknown') {
                if (preg_match('/[\d\.]{7,15}/', $k, $m)) $ip = $m[0];
            }
        }
        if ($ip == '') {
            if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']) {
                $k = $_SERVER['HTTP_CLIENT_IP'];
                if ($k != 'unknown') $ip = $k;
            }
        }
        if ($ip == '') {
            if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
                $k = $_SERVER['REMOTE_ADDR'];
                if ($k != 'unknown') $ip = $k;
            }
        }
        if ($reLong) {
            $long = ip2long($ip);
            return $long ? $long : 0;
        }
        if ($ip == '::1') return '127.0.0.1';
        return $ip;
    }
	
	
}