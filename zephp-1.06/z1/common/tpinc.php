<?php
/**
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2017年9月15日
 @todo: 
*/


//加载类
function z1_common_tpInc ($root, $file, $cla = '') {
    if (is_string($root) && is_string($file)) {
        $root = rtrim($root, '/ ');
        $file = str_replace(array(' ', '/', '\\', '.', '--'), array('', '', '', '', '-'), $file);
        if ($file != '') {
            $a = explode('-', $file);
            if (!empty($a)) {
                $file = '';
                foreach ($a as $r) {
                    if (!empty($r)) {
                        $root .= '/' . $r;
                        $file = $r;
                    }
                }
                $root .= '.php';
                if (is_file($root)) {
                    include_once $root;
                    $cla = $cla == '' ? $file : $cla;
                    if ($cla != '' && class_exists($cla)) {
                        return new $cla();
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
        $cla = z1_common_tpInc($root, trim($m));
        if ($cla) {
            $f = !empty($f) ? trim($f) : 'init';
            if (preg_match('/^\w+$/i', $f)) return $cla->$f();
        }
    }
    return NULL;
}

