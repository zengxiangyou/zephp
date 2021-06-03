<?php
/**
 * Copyright (c) 2015-2020 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2018年9月17日
 @todo: 
*/


//运行
function z1_common_apiMethod($nick, $arr, $root = ''){
    if ($nick != '' && preg_match('/^\w+$/i', $nick)) {
        $a = explode('_', $nick);
        $len = count($a) - 1;
        if ($len > 0) {
            $cla = $a[$len - 1];
            $func = $a[$len];
            $f = '';
            for ($i = 0; $i < $len; $i++) {
                $f .= '/' . $a[$i];
            }
            $f = rtrim($root, ' /\\') . $f . '.php';
            if (is_file($f)) {
                include $f;
                if (class_exists($cla, false)) {
                    $c = new $cla();
                    if (method_exists($c, $func)) {
                        return $c->$func($arr);
                    }
                }
            }
        }
    }
    return false;
}

//签名
function z1_common_apiSign($arr, $key = ''){
    $token = '';
    if ($arr) {
        ksort($arr);
        foreach ($arr as $k => $v) {
            $token .= $k . trim($v);
        }
        $token = strtolower(md5($token . '&' . $key));
    }
    return $token;
}


