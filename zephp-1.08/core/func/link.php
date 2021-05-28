<?php
/**
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2019-10-23
 @todo: 
*/

class z_core_func_link {
    
    //跳转
    function go ($url = '', $msg = '') {
        switch ($url) {
            case '': $url2 = 'history.back();'; break;
            case '-1': $url2 = 'location.replace(document.referrer);'; break;
            default: {
                $url = str_replace("\\", '/', trim($url));
                $url = str_replace(array('"', "'", '<', '>', "\n", "\r"), '', $url);
                $url2 = "location.replace('{$url}');";
            }
        }
        switch ($msg) {
            case '': echo "<script>{$url2}</script>"; break;
            case '301': header("Location: {$url}", TRUE, 301); break;
            default: {
                $msg = str_replace(array('"', "'", "\n", "\r"), '', trim($msg, " \\"));
                echo "<script>alert('{$msg}');{$url2}</script>";
            }
        }
        return TRUE;
    }
    
}