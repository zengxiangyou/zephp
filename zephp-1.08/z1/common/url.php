<?php
/**
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2020-04-01
 @todo: 
*/

//伪链接转换
function z1_common_urlGet(){
    $url = $_SERVER['QUERY_STRING'];
    if ($url != '' && preg_match('/^([\w\-\.\/\%]+)($|\&|\=)/i', $url, $m)) {
        $url = urldecode($m[1]);
        $dot = strrpos($url, '.');
        if ($dot) {
            if (isset($_GET[$url])) {
                unset($_GET[$url]);
            } else {
                $k = str_replace('.', '_', $url);
                unset($_GET[$k]);
            }
            if (strpos($url, '--')) {
                $url = str_replace('--', '/', $url);
            }
            $dirX = strpos($url, '/') ? '_' : '/';
            $arr = explode('_', $url);
            if ($arr) {
                $f = '';
                foreach ($arr as $r) {
                    if ($r != '') {
                        $i = strpos($r, '.');
                        if ($i) {
                            $key = substr($r, 0, $i);
                            $val = trim(substr($r, $i + 1));
                            $fix = strtolower($val);
                            if ($fix != 'html' && $fix != 'htm') {
                                if (!isset($_GET[$key])) {
                                    $_GET[$key] = $val;
                                }
                            } else {
                                if (strpos($f, '.')) {
                                    $f = $r . '_';
                                } else {
                                    $f .= $r . '_';
                                }
                            }
                        } else {
                            $i = strpos($r, '-');
                            if ($i) {
                                $key = substr($r, 0, $i);
                                if (!isset($_GET[$key])) {
                                    $_GET[$key] = trim(substr($r, $i + 1));
                                }
                            } else {
                                if (strpos($f, '.')) {
                                    $f = $r . $dirX;
                                } else {
                                    $f .= $r . $dirX;
                                }
                            }
                        }
                    }
                }
                if ($f != '') {
                    return trim($f, '._/');
                }
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
            if (strpos($k, '.') || strpos($r, '.')) {
                $url2 .= '&' . urlencode($k) . '=' . urlencode($r);
            } else {
                $url .= urlencode($k) . '.' . urlencode($r) . '_';
            }
        }
        $url = preg_match('/^[\w\-\.\/\%]+$/i', $html) ? $url . $html : rtrim($url, '_');
        $html = '?' . $url . $url2;
    }
    return $html;
}

