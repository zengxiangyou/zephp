<?php
/**
 * Copyright (c) 2015-2020 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy 1559261757@qq.com
 @final: 2016年10月7日
 @todo: 
*/

class z_core_func_link {
    
    //跳转
    function go ($url = '', $msg = '') {
        switch ($url) {
            case '': $url2 = 'history.back();'; break;
            case '-1': $url2 = 'location.replace(document.referrer);'; break;
            default: {
                $url2 = str_replace(array("'"), array(''), $url);
                $url2 = "location.replace('{$url2}');";
            }
        }
        switch ($msg) {
            case '': echo "<script>{$url2}</script>"; break;
            case '301': header("Location: {$url}", TRUE, 301); break;
            default: {
                $msg = str_replace(array("'"), array(''), $msg);
                echo "<script>alert('{$msg}');{$url2}</script>";
            }
        }
        return TRUE;
    }
    
}