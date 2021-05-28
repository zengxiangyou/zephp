<?php
/**
 * @author: zengxy.com 1559261757@qq.com
 * @final:  2019年11月24日
 * @todo:   
 */

class z_zz_copy{
    
    //复制
    function cp($f, $f2 = ''){
        if (!empty($f)) {
            if ($f2 == '') $f2 = './';
            $k = substr($f, -1);
            if ($k != '/' && $k != '\\') {
                if (is_file($f)) {
                    $k = substr($f2, -1);
                    if ($k != '/' && $k != '\\') {
                        $dir = dirname($f2);
                        if (!is_dir($dir)) zz::mkdirs($dir);
                        if (copy($f, $f2)) return true;
                    } else {
                        if (preg_match('/(^|\/|\\\\)([^\/\\\\]+)$/i', $f, $m)) {
                            if (!is_dir($f2)) zz::mkdirs($f2);
                            if (copy($f, $f2 . $m[2])) return true;
                        }
                    }
                }
            } else {
                if (is_dir($f)) {
                    $f2 = rtrim($f2, ' /\\') . '/';
                    if (!is_dir($f2)) zz::mkdirs($f2);
                    $op = opendir($f);
                    if ($op) {
                        $ret = true;
                        while (($file = readdir($op)) != false) {
                            if (($file != '.') && ($file != '..')) {
                                if (is_dir($f . $file) ) {
                                    if (!$this->cp($f . $file . '/', $f2 . $file)) $ret = false;
                                } else {
                                    if (!copy($f . $file, $f2 . $file)) $ret = false;
                                }
                                if (!$ret) break;
                            }
                        }
                        closedir($op);
                        return $ret;
                    }
                }
            }
        }
        return false;
    }
    
}
