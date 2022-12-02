<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2018-10-19
 @todo: 
*/

class z_core_func_htmlsafe{
    
    
    /**
     * 防xss跨站
     * @param unknown $str
     */
    function noXss ($str) {
        if (!is_array($str)) {
            if (is_numeric($str)) return $str;
            return htmlspecialchars($str, ENT_QUOTES);
        }
        if (!empty($str)) foreach ($str as $k => $v) $str[$k] = $this->noXss($v);
        return $str;
    }
    
    /**
     * 防sql注入，删除非法字符，用于搜索
     * @param string|array $str
     * @return string|array
     */
    function noInject ($str, $isSearch = FALSE) {
        if (!is_array($str)) {
            if (is_numeric($str)) return $str;
            if (is_string($str)) {
                if ($str == '') return $str;
                $str = preg_replace('/\'|\"|\*|\;|\\\\|\>|\</is', '', $str);
                $str = preg_replace('/\b(select|insert|update|delete|drop|create|union|join|into|and|or|from|where|like|order|by)\s+/is', '', $str);
                $str = preg_replace('/\b(grant|exec|truncate|declare|table|use|outfile)\s+/is', '', $str);
                $str = preg_replace('/(count|char|chr|in|values|load_file)\s*\(/is', '', $str);
                $str = addslashes(trim($str));
                if (!$isSearch) return $str;
                return str_replace(array('_', '%'), array('\_', '\%'), $str);
            }
            return addslashes($str);
        }
        if (!empty($str)) foreach ($str as $k => $v) $str[$k] = $this->noInject($v);
        return $str;
    }
    
    
}