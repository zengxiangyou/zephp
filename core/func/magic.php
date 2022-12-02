<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2017年9月14日
 @todo: 
*/

class z_core_func_magic{
    
    /**
     * 清除低版本php(<5.4)魔术杠(\)
     */
    function strip(){
        if (
            ( function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc() ) || 
            ( ini_get('magic_quotes_sybase') && ( strtolower(ini_get('magic_quotes_sybase')) != 'off' ) )
            ) {
            if (!empty($_GET)) foreach($_GET as $k => $v) $_GET[$k] = stripslashes($v);
            if (!empty($_POST)) foreach($_POST as $k => $v) $_POST[$k] = stripslashes($v);
            if (!empty($_COOKIE)) foreach($_COOKIE as $k => $v) $_COOKIE[$k] = stripslashes($v);
        }
    }
    
}