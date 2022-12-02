<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 * @author: zengxy.com 1559261757@qq.com
 * @final:  2019年11月7日
 * @todo:   
 */

class z_core_db_mysqlinsert{
    
    private $mysql = NULL; //基本类
    
    function __construct(){
        $this->mysql = zz::incClass('core-db-mysql');
    }
    
    //标准sql写入，可批量
    function insert($table, $arr, $option = ''){
        if ($table != '' && is_array($arr)) {
            if (!empty($arr)) {
                $field = '';
                $value = '';
                $j = 0;
                foreach ($arr as $r) {
                    if (is_array($r)) {
                        if (!empty($r)) {
                            $val = '';
                            $i = 0;
                            foreach ($r as $k => $v) {
                                if (!$j) {
                                    if (!is_numeric($k)) {
                                        $field .= "`{$k}`,";
                                    }
                                }
                                $i ? $val .= ',' : $i++;
                                $val .= "'{$v}'";
                            }
                            $j ? $value .= ',' : $j++;
                            $value .= "(" . $val . ")";
                        }
                    } else {
                        $i = 0;
                        foreach ($arr as $k => $v) {
                            if (!is_numeric($k)) {
                                $field .= "`{$k}`,";
                            }
                            $i ? $value .= ',' : $i++;
                            $value .= "'{$v}'";
                        }
                        $value = "(" . $value . ")";
                        break;
                    }
                }
                if ($field != '') {
                    $field = ' (' . rtrim($field, ',') . ')';
                }
                if ($option != '') $option = trim($option) . ' ';
                if ($value != '') {
                    $sql = "insert {$option}into `{$table}`{$field} values " . $value;
                    return $this->mysql->query_unbuf($sql);
                }
            }
        }
        return FALSE;
    }
    
}
