<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2017年7月19日
 @todo: 
*/


//远程下载
function z1_common_download($f, $f2){
    if (is_string($f) && $f != '' && is_string($f2) && $f2 != '') {
        $dir = dirname($f2);
        if (!is_dir($dir)) zz::mkdirs($dir);
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_URL, $f);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $f_ = curl_exec($ch);
            curl_close($ch);
            if ($f_) {
                $f2_ = fopen($f2, 'wb');
                if ($f2_) {
                    fwrite($f2_, $f_);
                    fclose($f2_);
                    return true;
                }
            }
        } else {
            $f_ = fopen($f, 'rb');
            if ($f_) {
                $f2_ = fopen($f2, 'wb');
                if ($f2_) {
                    while (($k = fread($f_, 4096)) != false) {
                        fwrite($f2_, $k, 4096);
                    }
                    fclose($f_);
                    fclose($f2_);
                    return true;
                }
                fclose($f_);
            }
        }
    }
    return false;
}
