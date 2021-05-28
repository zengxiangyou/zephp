<?php
/**
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-20
 @todo:   
*/

class z_core_func_cookie {
	
	/**
	 * 设置或返回cookie
	 * @param unknown $nick    键
	 * @param string $val      值
	 * @param number $expire   过期（秒）
	 * @param string $path     目录，默认"/"
	 * @param string $domain   域名
	 * @param string $secure   安全https
	 */
	function cookie ($nick, $val = NULL, $expire = 0, $path = '', $domain = '', $secure = FALSE) {
		if (is_null($val)) return isset($_COOKIE[$nick]) ? $_COOKIE[$nick] : '';
		if (is_string($val)) $val = trim($val);
		if ($expire != 0) $expire = time() + $expire;
		if ($path == '') $path = '/';
		if ($_SERVER['SERVER_PORT'] == 443) $secure = true;
		return setcookie($nick, $val, $expire, $path, $domain, $secure);
	}
	
}