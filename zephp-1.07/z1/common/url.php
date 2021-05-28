<?php
/**
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2018年9月28日
 @todo: 
*/


//伪链接转换
function z1_common_urlGet(){
    $url = $_SERVER['QUERY_STRING'];
    if ($url != '' && preg_match('/^([\w\-\.]+)(\&|$)/i', $url, $m)) {
        $url = $m[1];
        if (isset($_GET[$url])) {
            unset($_GET[$url]);
        } else {
            $k = str_replace('.', '_', $url);
            unset($_GET[$k]);
        }
        $arr = explode('_', $url);
        if ($arr) {
            $f = '';
            foreach ($arr as $r) {
                if ($r != '') {
                    $i = stripos($r, '-');
                    if ($i) {
                        $_GET[substr($r, 0, $i)] = substr($r, $i + 1);
                    } else {
                        $f .= $f == '' ? $r . '/' : $r . '_';
                    }
                }
            }
            if ($f != '') {
                return trim($f, '._/');
            }
        }
    }
    return '';
}

//拼接伪链接
function z1_common_urlSet($arr, $html = ''){
    if ($arr) {
        $url = '';
        $url2 = '';
        foreach ($arr as $k => $r) {
            if (strpos($k, '_') || strpos($r, '_')) {
                $url2 .= '&' . urlencode($k) . '=' . urlencode($r);
            } else {
                $url .= urlencode($k) . '-' . urlencode($r) . '_';
            }
        }
        $url = preg_match('/^[\w\.\/]+$/i', $html) ? $url . str_replace('/', '_', $html) : rtrim($url, '_');
        return '?' . $url . $url2;
    }
    return '';
}


