<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @author: zengxy.com 1559261757@qq.com
 @final:  2018-11-09
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
	
	$format = false;
	$fm1 = '';
	$fm2 = '';
	if ($g != '') {
	    if (preg_match('/^(.*?)\[(\w+)\](.*?)$/i', $g, $m)) {
	        $format = true;
	        $fm1 = $m[1];
	        $fm2 = $m[3];
	        $g = $m[2];
	    }
	}
	
	if ($g == '') $g = 'p';
	if (isset($a[$g])) {
		$p2 = intval($a[$g]);
		if ($p2 > 1) $p = $p2;
		if ($p2 > $pages) $p = $pages;
	}
	$arr[$g] = $p;

	$a[$g] = 1;
	$arr['first'] = !$format ? z_fn_url($a) : $fm1 . $a[$g] . $fm2;

	$last = '';
	if ($pages > 1) {
		$a[$g] = $pages;
		$last = !$format ? z_fn_url($a) : $fm1 . $a[$g] . $fm2;
	}
	$arr['last'] = $last;
	
	$a[$g] = (($p - 1) > 0) ? $p - 1 : 1;
	$arr['prev'] = !$format ? z_fn_url($a) : $fm1 . $a[$g] . $fm2;
	
	$a[$g] = $p + 1 < $pages ? $p + 1 : $pages;
	$arr['next'] = !$format ? z_fn_url($a) : $fm1 . $a[$g] . $fm2;

	$pageList = array();
	if ($pages > 1) {
		$p1 = 4;
		for ($i = $p1; $i > 0; $i--) {
			$a1 = $p - $i;
			if ($a1 > 1) {
				$a[$g] = $a1;
				$pageList[$a1] = !$format ? z_fn_url($a) : $fm1 . $a[$g] . $fm2;
				$p1--;
			}
		}
		$p2 = 4 + $p1;
		$start = $p != 1 ? 0 : 1;
		for ($i = $start; $i < $p2; $i++) {
			$a1 = $p + $i;
			if ($a1 < $pages) {
				$a[$g] = $a1;
				$pageList[$a1] = !$format ? z_fn_url($a) : $fm1 . $a[$g] . $fm2;
			}
		}
	}
	$arr['list'] = $pageList;

	unset($a[$g]);
	$arr['url'] = !$format ? z_fn_url($a) : $fm1 . $a[$g] . $fm2;

	if ($pages > 1) {
		$sql = $sql . ' limit ' . (($p - 1) * $pagesize) . ',' . $pagesize;
	}

	return array(
		'sql' => $sql,
		'page' => $arr
	);
}
