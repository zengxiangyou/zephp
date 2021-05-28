<?php
/**
 @author: zengxy 1559261757@qq.com
 @final:  2015-7-2
 @todo:   
*/

class z_core_func_xml {
	

	//加载xml
	public function xmlToArray ($xml) {
		$a = array();
		if (!empty($xml)) {
			function_exists('libxml_disable_entity_loader') && libxml_disable_entity_loader(true);
			$a = $this->_xmlToArray(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
		}
		return $a;
	}
	
	//xml转数组
	private function _xmlToArray ($xmlobject) {
		$arr = array();
		if (!empty($xmlobject)) {
			foreach ((array)$xmlobject as $k => $v) {
				$arr[$k] = !is_string($v) ? $this->_xmlToArray($v) : $v;
			}
		}
		return $arr;
	}

	//生成xml
	function arrayToXml ($rootItem, $arr, $item = 'item') {
	    if ($rootItem != '' && !empty($arr)) {
	        return "<{$rootItem}>". $this->_arrayToXml($arr, $item) ."</{$rootItem}>";
	    }
	    return '';
	}
	
	//数组转xml
	private function _arrayToXml ($arr, $item = 'item') {
	    if (empty($arr)) return '';
	    $str = '';
	    foreach ( $arr as $k => $v ) {
	        is_numeric($k) && ($k = $item);
	        if (is_array($v) || is_object($v)) {
	            $str .= "<{$k}>". $this->_arrayToXml($v, $item) ."</{$k}>";
	        } else {
	            if (is_numeric($v)) {
	                $str .= "<{$k}>{$v}</{$k}>";
	            } else {
	                $str .= "<{$k}><![CDATA[{$v}]]></{$k}>";
	            }
	        }
	    }
	    return $str;
	}
	
}