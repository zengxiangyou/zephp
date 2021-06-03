<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2017年8月14日
 @todo: 
*/

class z_core_func_jsonencode{
    
    /**
     * 编码json
     * @param $arr      数组
     * @return string
     */
    function jencode ($arr, $newCheck = FALSE) {
        if ($newCheck && defined('JSON_UNESCAPED_UNICODE')) return json_encode($arr, JSON_UNESCAPED_UNICODE);
        return urldecode(json_encode($this->_urlencode($arr)));
    }
    
    //利用urlencode转码
    private function _urlencode ($arr) {
        if (!is_array($arr)) return $arr != '' && !is_numeric($arr) ? urlencode(addslashes(stripslashes($arr))) : $arr;
        if (!empty($arr)) foreach ($arr as $k => $v) $arr[$k] = $this->_urlencode($v);
        return $arr;
    }
    
}