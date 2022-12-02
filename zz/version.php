<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 * @author: zengxy.com 1559261757@qq.com
 * @final:  2019年11月24日
 * @todo:   
 */

class z_zz_version{
    
    //版本
    function v($k = ''){
        $ver = array();
        if (is_file(zzROOT . 'v.php')) $ver = include(zzROOT . 'v.php');
        if ($k != '') return isset($ver[$k]) ? $ver[$k] : '';
        return $ver;
    }
    
}
