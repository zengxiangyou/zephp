<?php
/**
 * Copyright (c) 2015-2019 zengxy.com | Licensed MulanPSL v2
 @author: zengxy 1559261757@qq.com
 @final:  2015-5-24
 @todo:   
*/


//分页
function z1_common_pageTurning ($sql, $pagesize = 0, $g = '') {

	if (empty($pagesize)) {
		return array(
			'sql' => $sql,
			'page' => array()
		);
	}

	$arr = array();
	$sum = z_db_rows($sql);
	$arr['sum'] = $sum;
	$pages = ceil($sum / $pagesize);
	$pages = $pages > 0 ? $pages : 1;
	$arr['pages'] = $pages;
	$p = 1;
	
	$a = $_GET;
// 	if (!empty($a)) {
// 		foreach ($a as $k => $v) {
// 			$a[$k] = urlencode($v);
// 		}
// 	}
	
	if ($g == '') $g = 'p';
	if (isset($a[$g])) {
		$p2 = intval($a[$g]);
		if ($p2 > 1) $p = $p2;
		if ($p2 > $pages) $p = $pages;
	}
	$arr[$g] = $p;

	$a[$g] = 1;
	$arr['first'] = z_fn_url($a);

	$last = '';
	if ($pages > 1) {
		$a[$g] = $pages;
		$last = z_fn_url($a);
	}
	$arr['last'] = $last;
	
	$a[$g] = (($p - 1) > 0) ? $p - 1 : 1;
	$arr['prev'] = z_fn_url($a);
	
	$a[$g] = $p + 1 < $pages ? $p + 1 : $pages;
	$arr['next'] = z_fn_url($a);

	$pageList = array();
	if ($pages > 1) {
		$p1 = 4;
		for ($i = $p1; $i > 0; $i--) {
			$a1 = $p - $i;
			if ($a1 > 1) {
				$a[$g] = $a1;
				$pageList[$a1] = z_fn_url($a);
				$p1--;
			}
		}
		$p2 = 4 + $p1;
		$start = $p != 1 ? 0 : 1;
		for ($i = $start; $i < $p2; $i++) {
			$a1 = $p + $i;
			if ($a1 < $pages) {
				$a[$g] = $a1;
				$pageList[$a1] = z_fn_url($a);
			}
		}
	}
	$arr['list'] = $pageList;

	unset($a[$g]);
	$arr['url'] = z_fn_url($a);

	if ($pages > 1) {
		$sql = $sql . ' limit ' . (($p - 1) * $pagesize) . ',' . $pagesize;
	}

	return array(
		'sql' => $sql,
		'page' => $arr
	);
}
