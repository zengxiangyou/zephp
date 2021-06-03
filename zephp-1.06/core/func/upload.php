<?php
/**
 * Copyright (c) 2015-2019 zengxy.com | Licensed MulanPSL v2
 * @author	zengxy 1559261757@qq.com
 * @final	2018-02-08
 * @todo	
 */

class z_core_func_upload {
    
    private $namekey = 0; //自加1
	
	//文件上传
	function upload ($checkArr, $updir, $fname = '', $streamFix = '') {
		$arr = (is_array($checkArr) && !empty($checkArr)) ? $checkArr : array();
		$arr['fname'] = '';
		if (isset($arr['check']) && $arr['check'] == true) {
		    $err = $arr['err'];
			if (is_string($updir) && $updir != '/' && $updir != '\\') {
				$updir = !empty($updir) ? rtrim($updir, '/ ') . '/' : './';
				if (!is_dir($updir)) {
				    if (!mkdir($updir, 0755, true)) $err = 'upload_03';
				}
				if ($err == '0') {
				    if ($fname == '') {
				        $month = $this->_monthDir($updir);
				        if ($month != '') {
				            $fname = $month . date('YmdHis') . $this->namekey . mt_rand() . '.' . $arr['type'];
				            $f = $updir . $fname;
				            if (!is_file($f)) {
				                if (move_uploaded_file($arr['tname'], $f)) {
				                    $arr['fname'] = $fname;
				                } else {
				                    $err = 'upload_06';
				                }
				            } else {
				                $err = 'upload_05';
				            }
				        } else {
				            $err = 'upload_04';
				        }
				    } else {
				        $fname = str_replace(array('/'), array('_'), $fname) . '.' . $arr['type'];
				        if (move_uploaded_file($arr['tname'], $updir . $fname)) {
				            $arr['fname'] = $fname;
				        } else {
				            $err = 'upload_07';
				        }
				    }
				} else {
				    if ($err == 'stream') {
				        if ($streamFix != '') {
				            if ($fname == '') {
				                $month = $this->_monthDir($updir);
				                if ($month != '') {
				                    $fname = $month . date('YmdHis') . $this->namekey . mt_rand() . '.' . $streamFix;
				                    $f = $updir . $fname;
				                    if (!is_file($f)) {
				                        if ($this->_fwrite($f, $arr['tname'], $arr['size'])) {
				                            $arr['fname'] = $fname;
				                        } else {
				                            $err = 'upload_08';
				                        }
				                    } else {
				                        $err = 'upload_05';
				                    }
				                } else {
				                    $err = 'upload_04';
				                }
				            } else {
				                $fname = str_replace(array('/'), array('_'), $fname) . '.' . $streamFix;
				                if ($this->_fwrite($updir . $fname, $arr['tname'], $arr['size'])) {
				                    $arr['fname'] = $fname;
				                } else {
				                    $err = 'upload_08';
				                }
				            }
				        } else {
				            $err = 'upload_09';
				        }
				    }
				}
			}
			$arr['err'] = $err;
		}
		return $arr;
	}
	
	//写入
	private function _fwrite($f, $tname, $size){
	    $pass = false;
	    $fp = fopen($tname, 'r');
	    if ($fp) {
	        $file = fread($fp, $size);
	        fclose($fp);
	        $fp2 = fopen($f, 'w');
	        if ($fp2) {
	            if (fwrite($fp2, $file)) $pass = true;
	            fclose($fp2);
	            unset($file);
	        }
	    }
	    return $pass;
	}
	
	//月份目录
	private function _monthDir($updir){
	    $month = date('Ymd') . '/';
	    $updir .= $month;
	    if (!is_dir($updir)) {
	        if (!mkdir($updir, 0755)) return '';
	    }
	    return $month;
	}
	
	//是否合法文件
	function uploadCheck ($postname, $maxsize = 1024, $types = '') {
		$file = !is_array($postname) ? $_FILES[$postname] : $postname;
		$arr = array();
		$arr['check'] = false;
		$arr['err'] = 1;
		if (!empty($file)) {
		    $this->namekey++;
			if ($file['error'] == '0') {
				$size = $file['size'];
				$type = $file['type'];
				$name = $file['name'];
				$arr['size'] = $size;
				$arr['type'] = $type;
				$arr['title'] = $name;
				$arr['tname'] = $file['tmp_name'];
				if ($size < ($maxsize * 1024)) {
					if (strtolower($type) != 'application/octet-stream') {
					    $ty = array('jpg', 'jpeg', 'png');
					    if ($types != '') {
					        $ty = explode(',', $types);
					        foreach ($ty as $k => $v) {
					            $ty[$k] = strtolower(trim($v));
					        }
					    }
					    $dot = strrpos($name, '.');
					    if ($dot) {
					        $title = substr($name, 0, $dot);
					        $type = strtolower(substr($name, $dot + 1));
					        if (in_array($type, $ty)) {
					            $arr['check'] = true;
					            $arr['type'] = $type;
					            $arr['title'] = $title;
					            $arr['err'] = '0';
					        } else {
					            $arr['err'] = 'upload_02';
					        }
					    }
					} else {
					    $arr['check'] = true;
					    $arr['err'] = 'stream';
					}
				} else {
					$arr['err'] = 'upload_01';
				}
			} else {
				$arr['err'] = $file['error'];
			}
		}
		return $arr;
	}
	
}
