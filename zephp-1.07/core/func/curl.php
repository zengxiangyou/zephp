<?php
/**
 * Copyright (c) 2015-2020 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2017-07-31
 @todo:   
*/

class z_core_func_curl {
	
    /**
     * 发送及远程请求，可先用urlExists()判断链接是否存在
     * @param unknown $url      链接
     * @param unknown $str      内容post
     * @param number $timeout   超时
     * @param array $optArr     选项curl
     * @return mixed|string     content|''
     */
	function urlGet ($url, $str = NULL, $timeout = 30, $optArr = array()) {
		if (is_string($url) && $url != '') {
			if (is_null($str)) {
				if (function_exists('curl_init')) {
					$ch = curl_init();
					if ($ch) {
					    curl_setopt($ch, CURLOPT_URL, $url);
					    if (stripos($url, 'https') === 0) {
					        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false );
					        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					        if (defined('CURL_SSLVERSION_TLSv1')) {
					            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
					        } else {
					            curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
					        }
					    }
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
					    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
					    if (defined('CURL_IPRESOLVE_V4')) curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
					    if (!empty($optArr)) {
					        foreach ($optArr as $k => $v) {
					            curl_setopt($ch, $k, $v);
					        }
					    }
					    $msg = curl_exec($ch);
					    if ($msg) {
					        curl_close($ch);
					        return $msg;
					    }
//     					echo curl_error($ch);
//     					var_dump(curl_getinfo($ch));
					    curl_close($ch);
					}
				} else {
					if (ini_get("allow_url_fopen") == "1" && function_exists('file_get_contents')) {
						$opts = array(
							'http'=>array(
								'method' => 'GET',
								'timeout' => $timeout
							)
						);
						$context = stream_context_create($opts);
						return file_get_contents($url, false, $context);
					}
				}
			} else {
			    if (function_exists('curl_init')) {
			        $ch = curl_init();
			        if ($ch) {
			            curl_setopt($ch, CURLOPT_URL, $url);
			            curl_setopt($ch, CURLOPT_POST, true);
			            if (is_array($str)) {
			                $str = defined('JSON_UNESCAPED_UNICODE') ? json_encode($str, JSON_UNESCAPED_UNICODE) : urldecode(json_encode($this->_urlencode($str)));
			            }
			            curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
			            if (stripos($url, 'https') === 0) {
			                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false );
			                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证
			                if (defined('CURL_SSLVERSION_TLSv1')) {
			                    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
			                } else {
			                    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
			                }
			            }
			            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //原生输出
			            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); //连接前等待，0为无限等待
			            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); //执行的最长秒数
			            if (defined('CURL_IPRESOLVE_V4')) curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			            if (!empty($optArr)) {
			                foreach ($optArr as $k => $v) {
			                    curl_setopt($ch, $k, $v);
			                }
			            }
			            $msg = curl_exec($ch);
			            if ($msg) {
			                curl_close($ch);
			                return $msg;
			            }
//     				    echo curl_error($ch);
//     				    var_dump(curl_getinfo($ch));
			            curl_close($ch);
			        }
			    }
			}
		}
		return '';
	}
	
	/**
	 * 判断远程文件是否存在
	 * @param string $url	链接
	 * @return boolean
	 */
	function urlExists($url){
		if (is_string($url) && $url != '') {
		    if (function_exists('curl_init')) {
		        $ch = curl_init($url);
		        if ($ch) {
		            curl_setopt($ch, CURLOPT_NOBODY, true);
		            if (curl_exec($ch)){
		                if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
		                    curl_close($ch);
		                    return true;
		                }
		            }
		            curl_close($ch);
		        }
		    } else {
		        if (ini_get('allow_url_fopen') == '1' && function_exists('file_get_contents')) {
		            if (file_get_contents($url, null, null, -1, 1)) {
		                return true;
		            }
		        }
		    }
		}
		return false;
	}
	
	//转码
	private function _urlencode ($arr) {
	    if (!is_array($arr)) return $arr != '' && !is_numeric($arr) ? urlencode(addslashes(stripslashes($arr))) : $arr;
	    if (!empty($arr)) foreach ($arr as $k => $v) $arr[$k] = $this->_urlencode($v);
	    return $arr;
	}
	
}