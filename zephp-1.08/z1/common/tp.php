<?php
/**
 * @author	zengxy 1559261757@qq.com
 * @final	2015-2-23
 * @todo	
 */


//模板初始化
function z1_common_tpRePath ($set, $path = '') {
    if (is_array($set) && isset($set['template']) && isset($set['cache'])) {
        z_tp_init($set);
    }
	if (z_tp_val('isinit')) {
		if ($path != '') $path = rtrim($path, ' /\\') . '/';
		$r1 = '/(href\=|src\=|url\()([\'\"]?)(?!http|\<|\/)([^>\'\"\)]+)(\.)(?=jpg|jpeg|png|gif|css|js)/iU';
		$r2 = '\\1\\2'. $path .'\\3\\4';
		z_tp_rep($r1, $r2);
	}
}

//自改语言
function z1_common_tpLangChange ($arr, $lang = '') {
    if ($lang != '') {
        if (is_array($arr) && !empty($arr)) {
            foreach ($arr as $k => $v) {
                if (is_array($v)) $arr[$k] = z1_common_tpLangChange($v, $lang);
                $i = strrpos($k, '_');
                if ($i) {
                    $j = substr($k, ($i + 1));
                    if ($j == $lang) {
                        $j = substr($k, 0, $i);
                        $arr[$j] = $v;
                    }
                }
            }
        }
    }
    return $arr;
}

