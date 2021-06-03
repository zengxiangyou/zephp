<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 * @author	zengxy.com 1559261757@qq.com
 * @final	2021-02-10
 * @todo	模板引擎
 */


class z_core_mvc_template {
	
	private $assignValue = array();	//注入变量
	private $value = array(
		'isinit' => FALSE, //已初始化
		'r1' => array(), //正则替换
		'r2' => array(), //替换为
	);
	private $config = array(
		'template' => './', //模板目录
		'cache' => './template_c/', //编译目录
		'cachedepth' => 2, //目录深度，0为不限
		'php' => TRUE, //支持php
		'check' => TRUE, //更新检查
	    'checktime' => 5, //检查间隔（秒）
	);
	
	
	function __construct() {
		
	}
	
	function __destruct() {
		unset($this->assignValue);
		unset($this->value);
		unset($this->config);
	}
	
	//初始化
	function init ($set = array()) {
		$this->config = $set + $this->config;
		$k = $this->config['template'];
		if (is_dir($k)) {
			$this->config['template'] = rtrim($k, ' /\\') . '/';
			$k = $this->config['cache'];
			if ($k != '') {
				$this->config['cache'] = rtrim($k, ' /\\') . '/';
				$this->value['isinit'] = true;
			}
		}
	}
	
	//取设置
	function getValue ($key = '') {
		return $key != '' ? $this->value[$key] : $this->value;
	}
	
	//注入变量
	function assign ($nick, $val = '') {
		if (is_array($nick)) {
			foreach ($nick as $k => $v) {
				$this->assignValue[$k] = $v;
			}
		} else {
			$this->assignValue[$nick] = $val;
		}
	}
	
	//正则替换
	function replace ($r1, $r2) {
		if (!empty($r1) && isset($r2)) {
			$this->value['r1'][] = $r1;
			$this->value['r2'][] = $r2;
		}
	}
	
	/**
	 * 解释模板
	 * @param unknown $file__	模板
	 * @param string $html	生成静态文件
	 */
	function show ($file__) {
		$htm = '';
		if ($this->value['isinit']) {
		    $file__ = trim($file__, ' /\\');
		    $ft__ = $file__;
			$ft2__ = $this->config['template'] . $file__;
			if (is_file($ft2__)) {
				if (zz::$rewrite) ob_start();
				$dep_ = $this->config['cachedepth'];
				if ($dep_) {
				    $f_ = '';
				    $ff_ = explode('/', str_replace('\\', '/', $file__));
				    if (count($ff_) > $dep_ + 1) {
				        foreach ($ff_ as $i => $v) {
				            if ($i < $dep_) {
				                $f_ .= $i > 0 ? '/' . $v : $v;
				                unset($ff_[$i]);
				            } else {
				                $f_ .= '/' . implode('_', $ff_);
				                break;
				            }
				        }
				        $file__ = $f_;
				    }
				    unset($ff_);
				}
				$file__ = $this->config['cache'] . $file__ . '.php';
				$pass__ = 0;
				$time__ = 0;
				$rebuild__ = false;
				if (is_file($file__)) {
				    $time__ = filemtime($file__);
				    if ($this->config['check'] && $this->config['checktime'] > 0) {
				        if ((time() - $time__) <= $this->config['checktime']) {
				            extract($this->assignValue, EXTR_OVERWRITE);
				            include $file__;
				            if (zz::$rewrite) {
				                $htm = ob_get_contents();
				                ob_end_clean();
				            }
				            return $htm;
				        }
				    }
				}
				if ($time__ > 0) {
				    $pass__ = 1;
				    if ($this->config['check']) {
				        if ($time__ > filemtime($ft2__)) {
				            extract($this->assignValue, EXTR_OVERWRITE);
				            $incArr__ = array();
				            include $file__;
				            if (is_array($incArr__) && !empty($incArr__)) {
				                foreach ($incArr__ as $v) {
				                    $v = $this->config['template'] . $v;
				                    if (!is_file($v) || $time__ < filemtime($v)) {
				                        if (zz::$rewrite) {
				                            $pass__ = 0;
				                            ob_end_clean();
				                            ob_start();
				                        } else {
				                            $rebuild__ = true;
				                        }
				                        break;
				                    }
				                }
				            }
				        } else {
				            $pass__ = 0;
				        }
				    } else {
				        extract($this->assignValue, EXTR_OVERWRITE);
				        include $file__;
				    }
				} 
				if ($pass__ == 0 || $rebuild__) {
				    if (strtolower(substr($ft__, -3)) != 'php') {
				        $obj = zz::incClass('core-mvc-templateCompile');
				        $html = $obj->inc($ft__, $this->config['template']);
				        if (!empty($html)) {
				            $html = $obj->compile($html, $this->config['php']);
				            if (!empty($this->value['r1'])) {
				                $html = preg_replace($this->value['r1'], $this->value['r2'], $html);
				            }
				            unset($obj->incArr[0]);
				            $html .= '<?php $incArr__ = ' . var_export($obj->incArr, 1) . '; ?>';
				            $r = zz::write($file__, $html);
				            unset($html);
				            unset($obj);
				            if ($r) {
				                if ($rebuild__) {
				                    echo "<script>location.reload()</script>";
				                } else {
				                    extract($this->assignValue, EXTR_OVERWRITE);
				                    include $file__;
				                }
				            }
				        }
				    }
				} else {
				    if ($this->config['check'] && $this->config['checktime'] > 0) {
				        if (is_file($file__)) touch($file__);
				    }
				}
				if (zz::$rewrite) {
					$htm = ob_get_contents();
					ob_end_clean();
				}
			} else {
				zz::err('tp_show_001');
			}
		}
		return $htm;
	}
	
	//清除缓存
	function clear(){
		if ($this->value['isinit']) {
			$arr = glob($this->config['cache'] . '*');
			if (!empty($arr)) {
				foreach ($arr as $v) {
				    if (is_dir($v)) $v .= '/';
					zz::del($v);
				}
			}
		}
	}
	
}