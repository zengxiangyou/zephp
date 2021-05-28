<?php
/**
 * @author	zengxy 1559261757@qq.com
 * @final	2020-03-28
 * @todo	
 */

class z_core_func_upload {
    
    private $typeDef = 'jpg,jpeg,png';
	
	//文件上传
	function upload ($checkArr, $updir, $fname = '') {
		$checkArr = (is_array($checkArr) && !empty($checkArr)) ? $checkArr : array();
		$arr = array();
		if (!empty($checkArr)) {
		    foreach ($checkArr as $a) {
		        if (!is_array($a)) break;
		        $arr[] = $this->upload($a, $updir, $fname);
		    }
		}
		if (!$arr) {
		    $arr = $checkArr;
		    $arr['fname'] = '';
		    if (isset($arr['check']) && $arr['check'] == true) {
		        $err = $arr['err'];
		        $postfix = $arr['type'];
		        $size = $arr['size'];
		        $tmp_name = $arr['tmp_name'];
		        if (is_string($updir) && $updir != '/' && $updir != '\\') {
		            $updir = !empty($updir) ? rtrim($updir, ' /\\') . '/' : './';
		            if (!is_dir($updir)) {
		                if (!mkdir($updir, 0755, true)) $err = 'upload_03'; //目录不存在
		            }
		            if ($err == '0') {
		                if ($fname == '') {
		                    $month = $this->_monthDir($updir);
		                    if ($month != '') {
		                        $dir = $updir . $month;
		                        $fi = $this->_filename($dir, $postfix);
		                        $f = $dir . $fi;
		                        if (!is_file($f)) {
		                            if (move_uploaded_file($tmp_name, $f)) {
		                                $arr['fname'] = $month . $fi;
		                            } else {
		                                $err = 'upload_06'; //上传失败
		                            }
		                        } else {
		                            $err = 'upload_05'; //文件重复
		                        }
		                    } else {
		                        $err = 'upload_04'; //创建每天目录失败
		                    }
		                } else {
		                    $fname = $this->_fileto($updir, $fname, $postfix);
		                    if (move_uploaded_file($tmp_name, $updir . $fname)) {
		                        $arr['fname'] = $fname;
		                    } else {
		                        $err = 'upload_07'; //指定上传失败
		                    }
		                }
		            } else {
		                if ($err == 'stream') {
		                    if ($fname == '') {
		                        $month = $this->_monthDir($updir);
		                        if ($month != '') {
		                            $dir = $updir . $month;
		                            $fi = $this->_filename($dir, $postfix);
		                            $f = $dir . $fi;
		                            if (!is_file($f)) {
		                                if ($this->_fwrite($f, $tmp_name, $size)) {
		                                    $arr['fname'] = $month . $fi;
		                                } else {
		                                    $err = 'upload_08'; //流上传失败
		                                }
		                            } else {
		                                $err = 'upload_05';
		                            }
		                        } else {
		                            $err = 'upload_04';
		                        }
		                    } else {
		                        $fname = $this->_fileto($updir, $fname, $postfix);
		                        if ($this->_fwrite($updir . $fname, $tmp_name, $size)) {
		                            $arr['fname'] = $fname;
		                        } else {
		                            $err = 'upload_08'; //指定流上传失败
		                        }
		                    }
		                }
		            }
		        }
		        $arr['err'] = $err;
		    }
		}
		return $arr;
	}
	
	//指定文件名
	private function _fileto($dir, $fname, $type){
	    if ($dir != '' && $fname != '' && $type != '') {
	        $dot = strrpos($fname, '.');
	        if ($dot) {
	            $type2 = strtolower(substr($fname, $dot + 1));
	            if ($type2 == $type) $type = '';
	        }
	        if ($type != '') $fname .= '.' . $type;
	        $dir = dirname($dir . $fname);
	        if (!is_dir($dir)) {
	            if (!mkdir($dir, 0755, true)) return '';
	        }
	        return $fname;
	    }
	    return '';
	}
	
	//文件名
	private function _filename($dir, $type){
	    if ($dir != '' && $type != '') {
	        for ($i = 0; $i < 5; $i++) {
	            $timeStr = floatval(PHP_VERSION) < 5 ? time() . mt_rand(0, 100000) : microtime(1) . mt_rand(0, 100);
	            $fname = date('Y') . $timeStr . '.' . $type;
	            $f = $dir . $fname;
	            if (!is_file($f)) return $fname;
	        }
	    }
	    return '';
	}
	
	//写入
	private function _fwrite($f, $tmp_name, $size){
	    $pass = false;
	    $fp = fopen($tmp_name, 'r');
	    if ($fp) {
	        $file = fread($fp, $size);
	        fclose($fp);
	        $fp2 = fopen($f, 'w');
	        if ($fp2) {
	            if (fwrite($fp2, $file)) $pass = true;
	            fclose($fp2);
	        }
	        unset($file);
	    }
	    return $pass;
	}
	
	//每天目录
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
		if (!empty($file)) {
		    foreach ($file as $fi) {
		        if (!is_array($fi)) break;
		        foreach ($file['name'] as $i => $name) {
		            $fi = array(
		                'name' => $name,
		                'type' => $file['type'][$i],
		                'tmp_name' => $file['tmp_name'][$i],
		                'error' => $file['error'][$i],
		                'size' => $file['size'][$i]
		            );
		            $arr[] = $this->uploadCheck($fi, $maxsize, $types);
		        }
		        break;
		    }
		}
		if (!$arr) {
		    $arr['check'] = false;
		    $arr['err'] = '1';
		    if (!empty($file) && isset($file['error'])) {
		        if ($file['error'] == '0') {
		            $type = strtolower($file['type']);
		            $size = $file['size'];
		            $name = $file['name'];
		            $arr['title'] = $name;
		            $arr['type'] = $type;
		            $arr['size'] = $size;
		            $arr['name'] = $name;
		            $arr['tmp_name'] = $file['tmp_name'];
		            if ($size <= ($maxsize * 1024)) {
		                if ($types == '') $types = $this->typeDef;
		                $typeArr = explode(',', str_replace(' ', '', strtolower($types)));
		                $dot = strrpos($name, '.');
		                if ($dot) {
		                    $title = substr($name, 0, $dot);
		                    $ty = strtolower(substr($name, $dot + 1));
		                    if (in_array($ty, $typeArr)) {
		                        $arr['check'] = true;
		                        $arr['err'] = '0';
		                        $arr['title'] = $title;
		                        $arr['type'] = $ty;
		                        if ($type == 'application/octet-stream') {
		                            $arr['err'] = 'stream'; //流传送
		                        }
		                    } else {
		                        $arr['err'] = 'upload_02'; //不许
		                    }
		                }
		            } else {
		                $arr['err'] = 'upload_01'; //太大
		            }
		        } else {
		            $arr['err'] = $file['error'];
		        }
		    }
		}
		return $arr;
	}
	
}
