<?php
/**
 * @author: zengxy.com 1559261757@qq.com
 * @final:  2019年11月24日
 * @todo:   
 */

class z_zz_del{
    
    //删除
    function del($f, $safeRoot = ''){
        $f = is_string($f) ? trim($f) : '';
        if ($f != '' && $f != '/' && $f != '\\') {
            $k = substr($f, -1);
            if ($k != '/' && $k != '\\') {
                if (is_file($f)) return unlink($f);
            } else {
                if ($safeRoot != '/' && $safeRoot != '\\') {
                    $fRoot = realpath($f);
                    $safeRoot = realpath($safeRoot);
                    if (!empty($fRoot) && !empty($safeRoot)) {
                        if ($fRoot != $safeRoot && stripos($fRoot, $safeRoot) === 0) {
                            if (is_dir($f)) {
                                $op = opendir($f);
                                while (($f2 = readdir($op)) != false) {
                                    if ($f2 != '.' && $f2 != '..') {
                                        $f2 = $f . '/' . $f2;
                                        if (!is_dir($f2)) {
                                            unlink($f2);
                                        } else {
                                            $this->del($f2 . '/', $safeRoot);
                                        }
                                    }
                                }
                                closedir($op);
                                return rmdir($f);
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
    
}
