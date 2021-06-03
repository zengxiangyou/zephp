<?php
/**
 * Copyright (c) 2015-2019 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy 1559261757@qq.com
 @final: 2016年11月7日
 @todo: 
*/


//IP所在地区
function z1_common_areaGet($ip = ''){
    $ip = ($ip == '') ? z_fn_ip() : $ip;
    if (!empty($ip)) {
        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
        $url = z_fn_curl($url);
        $a = json_decode($url, 1);
        if (!empty($a) && is_array($a)) {
            if (isset($a['code']) && $a['code'] == '0') {
                if (isset($a['data']) && !empty($a['data'])) {
                    $a = $a['data'];
                    $city = (isset($a['city']) && $a['city'] != '') ? $a['city'] : '';
                    $region = (isset($a['region']) && $a['region'] != '') ? $a['region'] : '';
                    $city = ($region != $city) ? trim($region . ' ' . $city) : $region;
                    if ($city == '') $city = $a['country'];
                    return $city;
                }
            }
        }
    }
    return '';
}

