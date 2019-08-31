<?php
/**
 * Created by PhpStorm.
 * User: 周艺堃
 * Date: 2019/4/13
 * Time: 23:43
 */

$config['socket_type'] = 'tcp'; //`tcp` or `unix`
$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = '127.0.0.1';
$config['password'] = NULL;
$config['port'] = 6379;
$config['timeout'] = 0;