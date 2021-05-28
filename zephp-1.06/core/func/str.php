<?php
/**
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-21
 @todo:   
*/

class z_core_func_str {
	
	/**
	 * 拼接url
	 * @param array $arr
	 * @return string
	 */
    function url ($arr, $forpost = FALSE) {
		$str = '';
		if (is_array($arr) && !empty($arr)) {
			foreach ($arr as $k => $v) {
				$str .= $k . '=' . urlencode($v) . '&';
			}
			$str = rtrim($str, '&');
			if (!$forpost) $str = '?' . $str;
		}
		return !$forpost ? preg_replace('/[\'\"\>\<]/is', '', $str) : $str;
	}
	
	/**
	 * 拼接sql
	 * @param array $arr
	 * @return string
	 */
	function sql ($arr) {
		$str = '';
		if (is_array($arr) && !empty($arr)) {
			foreach ($arr as $k => $v) {
				$str .= "`{$k}` = '{$v}',";
			}
			$str = rtrim($str, ',');
		}
		return $str;
	}
	
	/**
	 * 随机密码
	 * @param number $len
	 * @param string $str	采用字符
	 * @return string
	 */
	function pwd ($len = 8, $str = '') {
		$str2 = '';
		if (is_numeric($len)) {
			if ($str == '') {
				$len = ($len > 0 && $len <= 32) ? $len : 8;
				$str2 = substr(md5(uniqid()), mt_rand(0, (32 - $len)), $len);
			} else {
				if (is_string($str)) {
					$len2 = strlen($str) - 1;
					for ($i = 0; $i < $len; $i++) {
						$str2 .= substr($str, mt_rand(0, $len2), 1);
					}
				}
			}
		}
		return $str2;
	}
	
	/**
	 * 格式化时间
	 * @param Number $t
	 * @param number $type
	 * @return string
	 */
	function time ($t, $type = 0) {
		if (preg_match('/^\d+$/', $t)) {
			if (is_numeric($type)) {
				switch ($type) {
					case 0: $t = date('Y-m-d', $t); break;
					case 1: $t = date('Y-m-d H:i', $t); break;
					case 2: $t = date('Y-m-d H:i:s', $t); break;
				}
			} else {
				$t = date($type, $t);
			}
		}
		return $t;
	}
	
}