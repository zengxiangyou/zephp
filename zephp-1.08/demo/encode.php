<?php
/**
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2018年9月1日
 @todo: 
*/


// 注意：严格按版本号进行备份


//核心源码加密
class z_encode{
    
    public $num = 0;
    
    private $dir = '';
    private $dir2 = '';
    
    //源码加密
    function encode($dir){
        if (!function_exists('tonyenc_encode')) return ;
        if (is_dir($dir)) {
            $dir = realpath($dir);
            if ($this->dir == '') $this->dir = $dir;
            if ($this->dir2 == '') $this->dir2 = $dir . '_encode';
            $op = opendir($dir);
            while (($f = readdir($op)) !== false) {
                if ($f != '.' && $f != '..') {
                    if ($f == 'demo') continue;
                    $f = $dir . '/' . $f;
                    $this->encode($f);
                }
            }
            closedir($op);
        } else {
            if ($this->dir != '' && $this->dir2 != '' && $this->dir != $this->dir2) {
                $f = str_replace($this->dir, $this->dir2, $dir);
                $dir2 = dirname($f);
                if (!is_dir($dir2)) mkdir($dir2, 0777, true);
                if (copy($dir, $f)) {
                    if (preg_match('/\.php$/i', $f)) {
                        $f2 = file_get_contents($f);
                        $f2 = preg_replace('/\/\*.*?\*\//is', '', $f2);
                        $f2 = tonyenc_encode($f2);
                        if (file_put_contents($f, $f2)) {
                            echo 'encode: ' . $f . '<br>';
                            $this->num++;
                        }
                    }
                }
            }
        }
    }
    
    //复制
    function copy($f, $f2 = ''){
        if ($this->dir != '' && $this->dir2 != '' && $this->dir != $this->dir2) {
            if ($f2 == '') $f2 = $f;
            $f = $this->dir . '/' . $f;
            $f2 = $this->dir2 . '/' . $f2;
            $dir2 = dirname($f2);
            if (!is_dir($dir2)) mkdir($dir2, 0777, true);
            if (copy($f, $f2)) {
                echo 'copy: ' . $f2 . '<br>';
                $this->num++;
            }
        }
    }

}

$enc = new z_encode();
$enc->encode('../../zephp');
$enc->copy('v.php');
$enc->copy('demo/tech.txt', 'tech.php');
$enc->copy('demo/function.txt', 'function.php');
echo $enc->num;




