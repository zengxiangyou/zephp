<?php
/**
 * Copyright (c) 2015-2021 zengxy.com | Licensed MulanPSL v2
 @copyright: zengxy 1559261757@qq.com
 @final: 2019-09-29
 @todo: 
*/


//缓存
function z1_common_memcached ($key = '', $value = NULL, $timeout = 1800, $prefix = '', $server = array(), $option = array()) {
    if (class_exists('Memcached')) {
        $m = zz::set('memcached_Class' . $prefix);
        if (empty($m)) {
            $m = new Memcached();
            $sv = $m->getServerList();
            if(empty($sv)) {
                $opt = array(
                    Memcached::OPT_RECV_TIMEOUT => 1000,
                    Memcached::OPT_SEND_TIMEOUT => 3000,
                    Memcached::OPT_TCP_NODELAY => true,
                    Memcached::OPT_SERVER_FAILURE_LIMIT => 50,
                    Memcached::OPT_CONNECT_TIMEOUT => 500,
                    Memcached::OPT_RETRY_TIMEOUT => 300,
                    Memcached::OPT_DISTRIBUTION => Memcached::DISTRIBUTION_CONSISTENT,
                    Memcached::OPT_REMOVE_FAILED_SERVERS => true,
                    Memcached::OPT_LIBKETAMA_COMPATIBLE => true,
                    Memcached::OPT_COMPRESSION => true,
                    Memcached::OPT_PREFIX_KEY => $prefix != '' ? $prefix : substr(md5(__FILE__), 8, 8)
                );
                if (!empty($option)) $opt = $option + $opt;
                if (!$m->setOptions($opt)) return false;
                if (empty($server)) {
                    if (!$m->addServer('localhost', 11211)) return false;
                } else {
                    $svs = array();
                    foreach ($server as $s) {
                        if (is_array($s) && $s['host'] != '') {
                            $svs[] = array( trim($s['host']), intval($s['port']), intval($s['weight']) );
                        }
                    }
                    if (!empty($svs) && !$m->addServers($svs)) return false;
                }
                zz::set('memcached_Class' . $prefix, $m);
            }
        }
        if (!empty($m)) {
            if (!empty($key)) {
                if (is_null($value)) return $m->get($key);
                if ($value === '' || $timeout <= 0) return $m->delete($key);
                return $m->set($key, $value, time() + $timeout);
            }
            if ($key === '') return $m->getResultCode();
            if (is_null($key)) return $m->flush();
        }
    }
    return false;
}

