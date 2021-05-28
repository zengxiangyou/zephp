<?php
/**
 @author: zengxy 1005592906@qq.com
 @final:  2015-10-15
 @todo:   
*/


//目录大小
function z1_common_dirSize ($dir) {
	$a = array();
	if (empty($dir) || !is_dir($dir)) return $a;
	$a['sum'] = 0;
	$a['size'] = 0;
	if (class_exists('RecursiveDirectoryIterator') && class_exists('RecursiveIteratorIterator')) {
		$ite = new RecursiveDirectoryIterator($dir);
		if ($ite) {
			$ite2 = new RecursiveIteratorIterator($ite, RecursiveIteratorIterator::LEAVES_ONLY);
			foreach ($ite2 as $r) {
				$f = $r->getFilename();
				if ($f == '.' || $f == '..') continue;
				$a['sum']++;
				$a['size'] += $r->getSize();
			}
		}
	} else {
		$handle = opendir($dir);
		$dir = rtrim($dir, '/\\ ') . '/';
		while (($f = readdir($handle)) != false) {
			if($f != '.' && $f != '..') {
				$f = $dir . $f;
				if(is_dir($f)) {
					$b = z1_common_dirSize($f);
					if (!empty($b)) {
						$a['sum'] += $b['sum'];
						$a['size'] += $b['size'];
					}
				} else {
					$a['sum']++;
					$a['size'] += filesize($f);
				}
			}
				
		}
		closedir($handle);
	}
	return $a;
}

//单位转换
function z1_common_dir2size ($size) {
	$kb = 1024;
	$mb = 1024 * $kb;
	$gb = 1024 * $mb;
	$tb = 1024 * $gb;
	if ($size < $kb) {
		return $size . ' B';
	} else if ($size < $mb) {
		return round($size/$kb, 2) . ' KB';
	} else if ($size < $gb) {
		return round($size/$mb, 2) . ' MB';
	} else if ($size < $tb) {
		return round($size/$gb, 2) . ' GB';
	} else {
		return round($size/$tb, 2) . ' TB';
	}
}
