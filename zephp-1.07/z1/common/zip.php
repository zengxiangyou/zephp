<?php
/**
 * Copyright (c) 2015-2020 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2019-09-19
 @todo:   
*/


//解压缩
function z1_common_zipOpen ($f, $dir = './') {
	$zip = new ZipArchive;
	if ($zip && is_file($f)) {
		if ($zip->open($f) === TRUE) {
			$r = $zip->extractTo($dir);
			$zip->close();
			return $r;
		}
	}
	return false;
}

//下载zip
function z1_common_zipDown ($f) {
	if (is_file($f)) {
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header('Content-disposition: attachment; filename=' . basename($f));
		header("Content-Type: application/zip");
		header("Content-Transfer-Encoding: binary");
		header('Content-Length: ' . filesize($f));
		@readfile($f);
	}
}

//压缩目录
function z1_common_zipAdd ($f, $dir, $incName = TRUE) {
    $zip = new ZipArchive();
    if ($zip && $f != '' && is_dir($dir)) {
        if (is_file($f)) unlink($f);
        if ($zip->open($f, ZipArchive::CREATE) === TRUE) {
            $dir = rtrim($dir, ' /\\');
            $dir2 = '';
            if ($incName && preg_match('/([^\/\\\\]+)$/i', $dir, $m)) {
                $dir2 = $m[1];
            }
            z1_common_zipAdd_($dir, $zip, $dir2);
            $zip->close();
            if (is_file($f)) return true;
        }
    }
    return false;
}

//递归目录
function z1_common_zipAdd_ ($dir, $zip, $dir2 = '') {
    if (is_dir($dir) && $zip) {
        $hd = opendir($dir);
        while (($f = readdir($hd)) !== FALSE) {
            if ($f != "." && $f != "..") {
                $dirF = $dir . '/' . $f;
                $f2 = $dir2 != '' ? $dir2 . '/' . $f : $f;
                if (is_dir($dirF)) {
                    z1_common_zipAdd_($dirF, $zip, $f2);
                } else {
                    //$zip->addFile($dirF, $f2);
                    $zip->addFromString($f2, file_get_contents($dirF));
                }
            }
        }
        @closedir($dir);
    }
}
