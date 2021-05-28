<?php
/**
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2017年9月22日
 @todo: 
*/


//权限
function z1_common_power($key = '', $keyroot = '', $power = NULL){
    if (!is_null($power)) {
        if ($key == '') return $power;
        if ($power === '') return false;
        if ($key != '' && $keyroot != '') {
            $key2 = $key;
            if (preg_match('/^(\w+)_/i', $key, $m)) $key2 = $m[1];
            $f = rtrim($keyroot, '/ ') . '/' . $key2;
            $r = zz::incData($f);
            if ($r && isset($r[$key])) {
                $key = str_replace(' ', '', $r[$key]);
                if ($key == '') return true;
                $a = explode(',', $key);
                if (in_array('!' . $power, $a)) return false;
                if (in_array($power, $a)) return true;
                if (preg_match_all('/([\<\>\=\|]+)(\d+)/i', $key, $m)) {
                    $pass = 0;
                    foreach ($m[1] as $k => $v) {
                        switch ($v) {
                            case '>': {
                                if ($power > $m[2][$k]) {
                                    if ($pass) return true;
                                    $pass = 1;
                                } else {
                                    $pass = 0;
                                }
                            }; break;
                            case '<': {
                                if ($power < $m[2][$k]) {
                                    if ($pass) return true;
                                    $pass = 1;
                                } else {
                                    $pass = 0;
                                }
                            }; break;
                            case '>=': {
                                if ($power >= $m[2][$k]) {
                                    if ($pass) return true;
                                    $pass = 1;
                                } else {
                                    $pass = 0;
                                }
                            }; break;
                            case '<=': {
                                if ($power <= $m[2][$k]) {
                                    if ($pass) return true;
                                    $pass = 1;
                                } else {
                                    $pass = 0;
                                }
                            }; break;
                            case '|>': {
                                if ($power > $m[2][$k]) return true;
                            }; break;
                            case '|<': {
                                if ($power < $m[2][$k]) return true;
                            }; break;
                            case '|>=': {
                                if ($power >= $m[2][$k]) return true;
                            }; break;
                            case '|<=': {
                                if ($power <= $m[2][$k]) return true;
                            }; break;
                        }
                    }
                    if ($pass) return true;
                }
            }
        }
    }
    return false;
}

