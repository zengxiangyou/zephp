<?php
/**
 * Copyright (C) 2015-2022 Zengxiangyou, All Rights Reserved.
 * Licensed http://license.coscl.org.cn/MulanPSL2
 * @link    http://www.zephp.com/
 * @author  zengxy.com <1559261757@qq.com>
 * @final   2022-04-18
 * @todo    The core of the all.
 */

error_reporting(E_ALL & ~E_NOTICE);
header('Content-Type:text/html;charset=UTF-8');
date_default_timezone_set('PRC');
define('zzROOT', __DIR__ . '/');

class zz {
	
	private static $obj = NULL;
	private static $_obj = FALSE;
	
	public static $rewrite = 0;
	public static $pass = 0;
	public static $local = 0;
	public static $debug = 0;
	
	public $dataArr = array();
	public $phpArr = array();
	public $classArr = array();
	public $setArr = array();
	public $errArr = array();
	public $regArr = array( '#' => array() );
	
	function __construct(){
		
	}
	
	function __destruct(){
	    unset($this->dataArr);
	    unset($this->phpArr);
	    unset($this->classArr);
	    unset($this->setArr);
	    unset($this->errArr);
	    unset($this->regArr);
	    self::$obj = NULL;
	    unset($GLOBALS);
	}
	
	public static function thisObject () {
		if (!self::$_obj) {
			self::$_obj = TRUE;
			$cla = __CLASS__;
			self::$obj = new $cla();
		}
		return self::$obj;
	}
	
	//错误
	public static function err ($nick = '', $val = NULL) {
		self::$_obj || self::thisObject();
		if ($nick != '') self::$obj->errArr[$nick] = is_null($val) ? $nick : $val;
		return self::$obj->errArr;
	}
	
	//取错误
	public static function errGet ($nick) {
	    self::$_obj || self::thisObject();
	    return isset(self::$obj->errArr[$nick]) ? self::$obj->errArr[$nick] : '';
	}
	
    //设置
	public static function set ($nick = NULL, $val = NULL) {
		self::$_obj || self::thisObject();
		if (!is_null($nick)) {
		    if (!is_array($nick)) {
		        if (!is_null($val)) self::$obj->setArr[$nick] = $val;
		        return self::$obj->setArr[$nick];
		    } else {
		        self::$obj->setArr = $nick + self::$obj->setArr;
		    }
		}
		return self::$obj->setArr;
	}
	
	//只取设置
	public static function setGet ($nick, $key = '') {
		if (is_array($nick)) return ($key != '') ? $nick[$key] : $nick;
		self::$_obj || self::thisObject();
		$a = self::$obj->setArr[$nick];
		return (is_array($a) && $key != '') ? $a[$key] : $a;
	}
	
	//改默认
	public static function setDef ($nick, $def = NULL) {
	    self::$_obj || self::thisObject();
	    if (!isset(self::$obj->setArr[$nick])) self::$obj->setArr[$nick] = $def;
	    return self::$obj->setArr[$nick];
	}
	
	//注册表
	public static function reg ($nick, $val = NULL) {
	    self::$_obj || self::thisObject();
	    if (!is_array($nick)) {
	        if (is_null($val)) {
	            if (isset(self::$obj->regArr[$nick])) return self::$obj->regArr[$nick];
	            $dir = self::$obj->regArr['#'];
	            if ($dir && preg_match('/^([\w\.\-]+)[_\.\-]/i', $nick, $m)) {
	                $k = $m[1];
	                if (isset(self::$obj->regArr['#' . $k])) {
	                    $r = self::$obj->regArr['#' . $k];
	                    if (isset($r[$nick])) $val = $r[$nick];
	                } else {
	                    $farr = array();
	                    foreach ($dir as $d) {
	                        $f = $d . $k . '.php';
	                        if (is_file($f)) {
	                            $r = include($f);
	                            if ($r) {
	                                $farr += $r;
	                            }
	                        }
	                    }
	                    if ($farr) {
	                        self::$obj->regArr['#' . $k] = $farr;
	                        if (isset($farr[$nick])) $val = $farr[$nick];
	                        unset($farr);
	                    }
	                    
	                }
	            }
	        }
	        self::$obj->regArr[$nick] = $val;
	    } else {
	        self::$obj->regArr = $nick + self::$obj->regArr;
	    }
	    return $val;
	}
	
	//注册目录
	public static function regDir ($dir) {
	    self::$_obj || self::thisObject();
	    array_unshift(self::$obj->regArr['#'], rtrim($dir, ' /\\') . '/');
	}
	
	//加载php
	public static function incPhp ($f, $root = '', $f2 = '') {
		self::$_obj || self::thisObject();
		if (!isset(self::$obj->phpArr[$f])) {
			if (empty($f)) return '';
			$f2 = ($f2 == '') ? trim($f) : trim($f2);
			$f3 = '';
			if (!preg_match('/\.php$/', $f2)) {
				$root = $root == '' ? zzROOT : rtrim($root, ' /\\') . '/';
				$k = 'z';
				$i = strpos($f2, ':');
				if ($i) {
					$k = trim(substr($f2, 0, $i));
					$f2 = trim(substr($f2, $i + 1));
				}
				$f2 = str_replace(array(' ', '\\', '-'), array('', '/', '/'), $f2);
				$f2 = trim($f2, '/');
				$f3 = $k . '_' . str_replace('/', '_', $f2);
				$f2 = $root . $f2 . '.php';
			}
			if (is_file($f2)) {
				self::$obj->phpArr[$f] = $f3;
				include $f2;
			}
		}
		return isset(self::$obj->phpArr[$f]) ? self::$obj->phpArr[$f] : '';
	}
	
	//加载类
	public static function incClass ($f, $root = '', $className = '') {
		self::$_obj || self::thisObject();
		if (!isset(self::$obj->classArr[$f])) {
			$cla = self::incPhp($f, $root);
			if ($className != '') $cla = str_replace('-', '_', $className);
			if ($cla != '' && class_exists($cla)) {
				self::$obj->classArr[$f] = new $cla();
			} else {
				die('incClass ' . $f);
			}
		}
		return self::$obj->classArr[$f];
	}
	
	//加载缓存
	public static function incData ($f, $root = '') {
		self::$_obj || self::thisObject();
		if (!isset(self::$obj->dataArr[$f])) {
			if (empty($f)) return array();
			$f2 = $f;
			if ($root != '') {
				if (strpos($f2, ':') > 0) $f2 = substr($f2, strrpos($f2, ':') + 1);
				$f2 = str_replace(array(' ', '\\'), array('', '/'), trim($f2));
				$f2 = rtrim($root, ' /\\') . '/' . trim($f2, '/');
			}
			if (!preg_match('/\.\w+$/i', $f2)) $f2 .= '.php';
			if (preg_match('/\.php$/i', $f2)) {
			    self::$obj->dataArr[$f] = array();
			    if (is_file($f2)) {
			        $a = include($f2);
			        if (is_array($a)) self::$obj->dataArr[$f] = $a;
			    }
			} else {
			    self::$obj->dataArr[$f] = is_file($f2) ? self::read($f2) : '';
			}
		}
		return self::$obj->dataArr[$f];
	}
	
	//读文件
	public static function read ($f) {
		$val = '';
		if (is_string($f) && is_file($f) && is_readable($f)) {
			if (function_exists('file_get_contents')) {
				$val = file_get_contents($f);
			} else {
				$r = fopen($f, 'rb');
				if ($r) {
				    while (($k = fread($r, 4096)) != false) {
				        $val .= $k;
				    }
				    fclose($r);
				}
			}
		}
		return $val;
	}
	
	//写文件
	public static function write ($f, $data, $cover = TRUE) {
		$rt = 0;
		if (is_string($f)) {
			$f2 = dirname($f);
			if (!is_dir($f2)) self::mkdirs($f2);
			if (is_dir($f2) && is_writable($f2)) {
				if (!$cover && is_file($f)) return $rt;
				if (is_file($f) && !is_writable($f)) return $rt;
				$str = !is_array($data) ? $data : '<?php return '. var_export($data, 1) .';';
				if (function_exists('file_put_contents')) {
					$rt = file_put_contents($f, $str);
				} else {
					$w = fopen($f, 'wb');
					if ($w) {
					    flock($w, LOCK_EX + LOCK_NB);
					    $rt = fwrite($w, $str);
					    flock($w, LOCK_UN + LOCK_NB);
					    fclose($w);
					}
				}
				if ($rt && function_exists('opcache_invalidate')) {
				    opcache_invalidate($f);
				}
			}
		}
		return $rt;
	}
	
	//建目录
	public static function mkdirs ($dir) {
	    if (!is_dir($dir)) {
	        if (!self::mkdirs(dirname($dir))) return false;
	        if (!mkdir($dir) || !chmod($dir, 0777)) return false;
	    }
	    return true;
	}
	
	//延时
	public static function timeout($f, $time = 3600){
	    if ($f != '') {
	        if (is_file($f)) {
	            if (time() - filemtime($f) > $time) {
	                if (touch($f)) return true;
	            }
	        } else {
	            self::write($f, time());
	        }
	    }
	    return false;
	}
	
    //删文件
	public static function del ($f, $safeRoot = '') {
		return self::incClass('zz-del')->del($f, $safeRoot);
	}
	
	//复制
	public static function copy ($f, $f2 = '') {
	    return self::incClass('zz-copy')->cp($f, $f2);
	}
	
	//版本
	public static function version ($k = '') {
	    return self::incClass('zz-version')->v($k);
	}
	
}
