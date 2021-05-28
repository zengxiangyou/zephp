<?php
/**
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-20
 @todo:   
*/


/**
 * 可逆加密，from Discuz.
 * @param string $string		要加密或解密的内容
 * @param string $operation		'DECODE' or 'ENCODE'
 * @param string $key			安全密钥
 * @param number $ckey_length	可取0-32，0则不随机
 * @param number $expiry		让时间加入随机
 * @return string
 */
function z1_common_authcode ($string, $operation = 'DECODE', $key = '', $ckey_length = 4, $expiry = 1) {
	$key = sha1($key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	if ($operation == 'DECODE') $string = str_replace(array('-', '_'), array('+', '/'), $string);
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string . $keyb), 0, 16) . $string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for ($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for ($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for ($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if ($operation == 'DECODE') {
		if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($result));
	}
}

