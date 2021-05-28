<?php
/**
 Copyright (C) 2015-2018 Zengxiangyou. All Rights Reserved.
 @link: http://www.zephp.com/
 @author: zengxy.com 1559261757@qq.com
 @final: 2017-10-20
 @todo: The core of the all.
*/

error_reporting(E_ALL & ~E_NOTICE);
header('Content-Type:text/html;charset=utf-8');
date_default_timezone_set('PRC');
define('zzROOT', dirname(__FILE__) . '/');

class zz {
	
	private static $obj = NULL;
	private static $_obj = FALSE;
	public static $rewrite = 0;
	public static $pass = 0;
	
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
	
	//版本
	public static function version ($k = '') {
	    if (is_file(zzROOT . 'v.php')) {
	        $v = include(zzROOT . 'v.php');
	        if ($k == '') return $v;
	        if (isset($v[$k])) return $v[$k];
	    }
	    return 0;
	}
	
	//错误
	public static function err ($nick = '', $val = NULL) {
		self::$_obj || self::thisObject();
		if (!empty($nick)) is_null($val) ? self::$obj->errArr[] = $nick : self::$obj->errArr[$nick] = $val;
		return self::$obj->errArr;
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
	            if ($dir && preg_match('/^(\w+)_/i', $nick, $m)) {
	                $k = $m[1];
	                if (isset(self::$obj->regArr['#' . $k])) {
	                    $r = self::$obj->regArr['#' . $k];
	                    if (isset($r[$nick])) $val = $r[$nick];
	                } else {
	                    foreach ($dir as $d) {
	                        $f = $d . $k . '.php';
	                        if (is_file($f)) {
	                            $r = include($f);
	                            if ($r) {
	                                self::$obj->regArr['#' . $k] = $r;
	                                if (isset($r[$nick])) $val = $r[$nick];
	                            }
	                            break;
	                        }
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
	    array_unshift(self::$obj->regArr['#'], rtrim($dir, '/ ') . '/');
	}
	
	//加载php
	public static function incPhp ($f, $root = '', $f2 = '') {
		self::$_obj || self::thisObject();
		if (!isset(self::$obj->phpArr[$f])) {
			if (empty($f)) return '';
			$f2 = ($f2 == '') ? trim($f) : trim($f2);
			$f3 = '';
			if (!preg_match('/\.php$/', $f2)) {
				$root = $root == '' ? zzROOT : rtrim($root, '/') . '/';
				$k = 'z';
				$i = strpos($f2, ':');
				if ($i > 0) {
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
			$cla = $className == '' ? $cla : $className;
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
				$f2 = str_replace(array(' ', '\\', '-'), array('', '/', '/'), trim($f2));
				$f2 = rtrim($root, '/ ') . '/' . ltrim($f2, '/');
			}
			if (!preg_match('/\.\w+$/i', $f2)) $f2 .= '.php';
			if (is_file($f2)) {
				if (preg_match('/\.php$/i', $f2)) {
					self::$obj->dataArr[$f] = include($f2);
				} else {
					self::$obj->dataArr[$f] = self::read($f2);
				}
			} else {
				self::$obj->dataArr[$f] = preg_match('/\.php$/i', $f2) ? array() : '';
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
	
    //删文件
	public static function del ($f, $safeRoot = '') {
		if ($f != '' && $f != '/' && $safeRoot != '/') {
		    if (substr($f, (strlen($f) - 1), 1) != '/') {
		        if (is_file($f)) return unlink($f);
		    } else {
		        $fRoot = realpath(dirname($f));
		        $safeRoot = realpath($safeRoot);
		        if (!empty($fRoot) && !empty($safeRoot) && stripos($fRoot, $safeRoot) === 0) {
		            if (is_dir($f)) {
		                $op = opendir($f);
		                while (($f2 = readdir($op)) != false) {
		                    if ($f2 != '.' && $f2 != '..') {
		                        $f2 = $f . '/' . $f2;
		                        if (!is_dir($f2)) {
		                            unlink($f2);
		                        } else {
		                            self::del($f2 . '/', $safeRoot);
		                        }
		                    }
		                }
		                closedir($op);
		                return rmdir($f);
		            }
		        }
		    }
		}
		return false;
	}
	
	//复制
	public static function copy ($f, $f2 = '') {
		if (empty($f)) return false;
		if ($f2 == '') $f2 = './';
		$k = substr($f, (strlen($f) - 1), 1);
		if ($k != '/' && $k != '\\') {
			if (!is_file($f)) return false;
			$k = substr($f2, (strlen($f2) - 1), 1);
			if ($k != '/' && $k != '\\') {
				$dir = dirname($f2);
				if (!is_dir($dir)) self::mkdirs($dir);
				if (!copy($f, $f2)) return false;
			} else {
				if (preg_match('/(^|\/|\\\\)([^\\\\\/]+)$/i', $f, $m)) {
					if (!is_dir($f2)) self::mkdirs($f2);
					if (!copy($f, $f2 . $m[2])) return false;
				}
			}
		} else {
			if (!is_dir($f)) return false;
			$f2 = rtrim($f2, '/\\ ') . '/';
			if (!is_dir($f2)) self::mkdirs($f2);
			$dir = opendir($f);
			if (!$dir) return false;
			while (($file = readdir($dir)) != false) {
				if (($file != '.') && ($file != '..')) {
					if (is_dir($f . $file) ) {
						if (!self::copy($f . $file . '/', $f2 . $file)) return false;
					} else {
						if (!copy($f . $file, $f2 . $file)) return false;
					}
				}
			}
			closedir($dir);
		}
		return true;
	}
	
	
}