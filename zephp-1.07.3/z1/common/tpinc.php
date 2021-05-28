<?php
/**
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2017年9月15日
 @todo: 
*/


//加载类
function z1_common_tpInc ($root, $file, $cla = '') {
    if (is_string($root) && is_string($file)) {
        $root = rtrim($root, ' /\\');
        $file = str_replace(array(' ', '/', '\\', '.', ':', '?'), array('', '', '', '', '', ''), trim($file));
        if ($file != '') {
            $arr = explode('-', $file);
            if ($arr) {
                $f = '';
                $c = '';
                foreach ($arr as $r) {
                    if ($r != '') {
                        $f .= '/' . $r;
                        $c = $r;
                    }
                }
                if ($f != '') {
                    $f = $root . $f . '.php';
                    if (is_file($f)) {
                        include $f;
                        if ($cla != '') $c = $cla;
                        if (class_exists($c, false)) {
                            return new $c();
                        }
                    }
                }
            }
        }
    }
    return NULL;
}

//执行函数
function z1_common_tpIncRun ($root, $m, $f = '') {
    if (!empty($m)) {
        $cla = z1_common_tpInc($root, $m);
        if ($cla) {
            $func = 'init';
            if ($f != '' && preg_match('/^\w+$/i', $f)) $func = $f;
            if (method_exists($cla, $func)) {
                return $cla->$func();
            }
        }
    }
    return NULL;
}

