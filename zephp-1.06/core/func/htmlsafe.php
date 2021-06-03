<?php
/**
 * Copyright (c) 2015-2019 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2017年5月24日
 @todo: 
*/

class z_core_func_htmlsafe{
    
    
    /**
     * 防xss跨站
     * @param unknown $str
     */
    function noXss ($str) {
        if (!is_array($str)) {
            if (is_numeric($str) || $str == '' || !is_string($str)) return $str;
            return preg_replace('/\'|\"|\>|\</is', '', $str);
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
            if (is_numeric($str) || $str == '' || !is_string($str)) return $str;
            $str = preg_replace('/select|insert|update|delete|union|into|and|or|load_file|outfile|exec|truncate|declare|\'|\"|\/\*|\*|\.\.\/|\.\/|chr/is', '', $str);
            $str = addslashes(trim($str));
            if (!$isSearch) return $str;
            return str_replace(array('_', '%'), array('\_', '\%'), $str);
        }
        if (!empty($str)) foreach ($str as $k => $v) $str[$k] = $this->noInject($v);
        return $str;
    }
    
    
}