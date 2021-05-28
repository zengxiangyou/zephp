<?php
/**
 @copyright: zengxy 1559261757@qq.com
 @final: 2020-06-09
 @todo: 
*/


//压缩图片
function z1_common_imageResize ($f, $maxWidth = 1000, $maxHeight = 1500, $quality = 70, $limit = 150) {
    $pass = false;
    if ($f != '' && function_exists('imagesx')) {
        if (is_file($f)) {
            $size = getimagesize($f);
            if ($size) {
                $m = explode('/', $size['mime']);
                $type = $m[1];
                $im = null;
                switch ($type) {
                    case 'jpg':
                    case 'jpeg': {
                        $im = imagecreatefromjpeg($f);
                    }; break;
                    case 'png': {
                        $im = imagecreatefrompng($f);
                    }; break;
                    case 'bmp': {
                        $im = imagecreatefromwbmp($f);
                    }; break;
                    case 'gif': {
                        $im = imagecreatefromgif($f);
                    }; break;
                }
                if (!empty($im)) {
                    $w = imagesx($im);
                    $h = imagesy($im);
                    $bi = 1;
                    if ($w > $maxWidth && $h > $maxHeight) {
                        $bi = $maxWidth / $w;
                        $bi2 = $maxHeight / $h;
                        if ($bi > $bi2) $bi = $bi2;
                    } else {
                        if ($w > $maxWidth) $bi = $maxWidth / $w;
                        if ($h > $maxHeight) $bi = $maxHeight / $h;
                    }
                    $fsize = filesize($f);
                    $limit *= 1024;
                    if ($bi < 1 || $fsize > $limit) {
                        $w2 = $w * $bi;
                        $h2 = $h * $bi;
                        if (function_exists('imagecopyresampled')) {
                            $im2 = imagecreatetruecolor($w2, $h2);
                            imagecopyresampled($im2, $im, 0, 0, 0, 0, $w2, $h2, $w, $h);
                        } else {
                            $im2 = imagecreate($w2, $h2);
                            imagecopyresized($im2, $im, 0, 0, 0, 0, $w2, $h2, $w, $h);
                        }
                        $f2 = $f . '.' . $type;
                        if (imagejpeg($im2, $f2, $quality)) {
                            if (filesize($f2) < $fsize) {
                                if (rename($f2, $f)) $pass = true;
                            } else {
                                unlink($f2);
                            }
                        }
                        imagedestroy($im2);
                    }
                    imagedestroy($im);
                }
            }
        }
    }
    return $pass;
}

