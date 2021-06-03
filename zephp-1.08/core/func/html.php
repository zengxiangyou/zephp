<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-20
 @todo:   
*/

class z_core_func_html {
	
    
	/**
	 * 编码，入库前转义
	 * 放入like '%%'中的还要对专用字符_和%转义，放入key = ''中的只要一次转义字符
	 * @param string|array $str
	 * @return string|array
	 */
	function htmlEncode($str, $isSearch = FALSE){
		if (!is_array($str)) {
		    if (is_numeric($str)) return $str;
		    if (is_string($str)) {
		        if ($str == '') return $str;
		        $str = addslashes(trim($str));
		        if (!$isSearch) return $str;
		        return str_replace(array('_', '%'), array('\_', '\%'), $str);
		    }
		    return addslashes($str);
		}
		if (!empty($str)) foreach ($str as $k => $v) $str[$k] = $this->htmlEncode($v);
		return $str;
	}
	
	
	/**
	 * 解码，还原html
	 * @param string|array $str
	 * @return string|array
	 */
    function htmlDecode ($str) {
		if (!is_array($str)) {
		    if (is_numeric($str) || $str == '' || !is_string($str)) return $str;
		    return stripslashes($str);
		}
		if (!empty($str)) {
		    foreach ($str as $k => $v) {
		        if (!is_array($v)) {
		            if (is_numeric($v) || $v == '' || !is_string($v)) continue;
		            $str[$k] = stripslashes($v);
		        } else {
		            $str[$k] = $this->htmlDecode($v);
		        }
		    }
		}
		return $str;
	}
	
	
}
