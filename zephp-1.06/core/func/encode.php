<?php
/**
 * Copyright (c) 2015-2019 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-20
 @todo:   可逆加密
*/

class z_core_func_encode {
	
	/**
	 * 加密
	 * @param string $txt	明文
	 * @param string $key	安全密钥
	 * @return string
	 */
	function encode ($txt, $key = '') {
		if (empty($txt)) return '';
		$txt .= '';
		$rnd =  md5(uniqid(rand(), TRUE));
		$len = strlen($txt);
		$ren = strlen($rnd);
		$ctr = 0;
		$str = '';
		for($i = 0; $i < $len; $i++) {
			$ctr = $ctr == $ren ? 0 : $ctr;
			$str .= $rnd[$ctr] . ($txt[$i] ^ $rnd[$ctr++]);
		}
		$txt = base64_encode($this->_kecrypt($str, $key));
		return str_replace(array('+', '/', '='), array('-', '_', ''), $txt);
	}
	
	/**
	 * 解密
	 * @param string $txt	密文
	 * @param string $key	安全密钥
	 * @return string
	 */
	function decode($txt, $key = '') {
		if (empty($txt)) return '';
		$txt = str_replace(array('-', '_'), array('+', '/'), $txt);
		$txt = $this->_kecrypt(base64_decode($txt), $key);
		$len = strlen($txt);
		$str = '';
		for($i = 0; $i < $len; $i++) {
			$tmp = $txt[$i];
			$str .= $txt[++$i] ^ $tmp;
		}
		return $str;
	}
	
	/**
	 * 内部加密函数
	 * @param string $txt
	 * @param string $key
	 * @return string
	 */
	private function _kecrypt($txt, $key = '') {
		$key = sha1($key);
		$len = strlen($txt);
		$ken = strlen($key);
		$ctr = 0;
		$str = '';
		for($i = 0; $i < $len; $i++) {
			$ctr = $ctr == $ken ? 0 : $ctr;
			$str .= $txt[$i] ^ $key[$ctr++];
		}
		return $str;
	}
	
}