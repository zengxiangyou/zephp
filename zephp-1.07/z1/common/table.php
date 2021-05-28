<?php
/**
 @author: zengxy 1005592906@qq.com
 @final:  2015-10-15
 @todo:   
*/


//导入数据表，文件中以换行分隔
function z1_common_tableImport($f, $dbname = ''){
	$pass = 1;
	if ($dbname != '') {
		$row = z_db_rows("SELECT * FROM information_schema.SCHEMATA where SCHEMA_NAME='{$dbname}' limit 1");
		if (!$row) {
		    zz::incPhp('core-z-mysql');
			if (!z_mysql_databaseCreate($dbname)) $pass = 0;
		}
		if ($pass) $pass = z_db_init($dbname);
	}
	if ($pass) {
		$pass = 0;
		$sql = zz::read($f);
		if (!empty($sql)) {
			$sql = preg_replace('/\/\*.*?\*\//is', '', $sql);
			$sql = preg_replace('/\-\-.*?(\n|\$)/is', '', $sql);
			if (preg_match_all('/(SET|CREATE|INSERT|ALTER|DROP)(.*?);(\s*\n|\s*$)/is', $sql, $m)) {
				foreach ($m[1] as $k => $r) {
					$r = $r . $m[2][$k];
					if (!z_db_query($r)) {
						zz::err('sql_err_001', $r . ' - ' . z_db_error());
						$pass = 0;
						break;
					};
					$pass = 1;
				}
			}
		}
	}
	return $pass;
}

//删除表
function z1_common_tableDelete($table, $dbname = ''){
	$pass = 1;
	if ($dbname != '') $pass = z_db_init($dbname);
	if ($pass) {
	    zz::incPhp('core-z-mysql');
	    $pass = z_mysql_tableDelete($table);
	}
	return $pass;
}


//导出数据库
function z1_common_tableDown ($tables = '', $where = '') {
    $dbArr = array();
    if ($tables != '') {
        $dbArr = explode(',', $tables);
    } else {
        $db = z_db_list("show tables");
        foreach ($db as $rs) {
            foreach ($rs as $k) {
                $dbArr[] = $k;
            }
        }
    }
    $mysql = '';
    if (!empty($dbArr)) {
        foreach ($dbArr as $k) {
            $rs = z_db_list('show create table `'. trim($k) .'`');
            foreach ($rs as $r) {
                $mysql .= "\r\n" . str_replace(array('CREATE TABLE'), array('CREATE TABLE IF NOT EXISTS'), $r['Create Table']) . ";\r\n";
                $rs2 = z_db_list("select * from `{$r['Table']}` {$where}");
                foreach ($rs2 as $r2) {
                    $keys = array_keys($r2);
                    $keys = array_map('addslashes', $keys);
                    $keys = join('`,`', $keys);
                    $vals = array_values($r2);
                    $vals = array_map('addslashes', $vals);
                    $vals = join("','", $vals);
                    $mysql .= "insert into `{$r['Table']}`(`{$keys}`) values('{$vals}');\r\n";
                }
            }
        }
    }
    return $mysql;
}

