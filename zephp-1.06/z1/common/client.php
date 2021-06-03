<?php
/**
 * Copyright (c) 2015-2019 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2017年10月24日
 @todo: 
*/


//客户端
function z1_common_client(){
    $client = '';
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if ($ua != '') {
        $uas = array();
        if (preg_match('/Windows NT (\d+\.\d+)/i', $ua, $m)) {
            switch ($m[1]) {
                case '5.0': $uas[] = 'Win2000'; break;
                case '5.1': $uas[] = 'WinXP'; break;
                case '5.2': $uas[] = 'Win2003'; break;
                case '6.0': $uas[] = 'WinVista'; break;
                case '6.1': $uas[] = 'Win7'; break;
                default: $uas[] = 'Win' . $m[1];
            }
        }
        if (preg_match('/(iPhone|iPad|Windows Phone|Nokia|BlackBerry)/i', $ua, $m)) $uas[] = $m[1];
        if (preg_match('/(Mac|Linux)/i', $ua, $m)) $uas[] = $m[1];
        if (preg_match('/MSIE|Firefox|Edge|Opera|Chrome|Safari/i', $ua)) {
            $u2 = '';
            if (preg_match('/(QQBrowser|TencentTraveler|Maxthon|TaoBrowser|360SE|LBBROWSER|The World|UCWEB|UCBrowser)/i', $ua, $m)) $u2 = $m[1];
            if ($u2 == '' && preg_match('/MSIE (\d+\.\d+)/i', $ua, $m)) {
                $u2 = 'IE' . (!preg_match('/rv\:(\d+\.\d+)/i', $ua, $m2) ? $m[1] : $m2[1]);
            }
            if ($u2 == '' && preg_match('/Firefox\/(\d+\.\d+)/i', $ua, $m)) $u2 = 'Firefox' . $m[1];
            if ($u2 == '' && preg_match('/Edge\/(\d+\.\d+)/i', $ua, $m)) $u2 = 'Edge' . $m[1];
            if ($u2 == '' && preg_match('/Opera\/(\d+\.\d+)/i', $ua, $m)) $u2 = 'Opera' . $m[1];
            if ($u2 == '' && preg_match('/Chrome\/(\d+\.\d+)/i', $ua, $m)) $u2 = 'Chrome' . $m[1];
            if ($u2 == '' && preg_match('/Safari\/(\d+\.\d+)/i', $ua, $m)) $u2 = 'Safari' . $m[1];
            if ($u2 != '') $uas[] = $u2;
        }
        if (preg_match('/Android (\d+\.\d+)/i', $ua, $m)) $uas[] = 'Android' . $m[1];
        if (preg_match('/Mobile/i', $ua, $m)) $uas[] = 'Mobile';
        if (preg_match('/MicroMessenger\/(\d+\.\d+)/i', $ua, $m)) $uas[] = 'Weixin' . $m[1];
        if ($uas) $client = implode(',', $uas);
    }
    return $client;
}