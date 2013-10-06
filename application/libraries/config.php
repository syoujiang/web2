<?php

define('QBOX_REDIRECT_URI',           '<RedirectURL>');
define('QBOX_AUTHORIZATION_ENDPOINT', '<AuthURL>');
define('QBOX_TOKEN_ENDPOINT',         'https://acc.qbox.me/oauth2/token');

define('QBOX_IO_HOST', 'http://iovip.qbox.me');
define('QBOX_UP_HOST', 'http://up.qbox.me');
define('QBOX_RS_HOST', 'http://rs.qbox.me');
define('QBOX_PU_HOST', 'http://pu.qbox.me:10200');
define('QBOX_EU_HOST', 'http://eu.qbox.me');

$QBOX_ACCESS_KEY	= 'LtcS2cGr8WfCpgfZGyX6YDmW4OjOEwt_rNGO0gog';
$QBOX_SECRET_KEY	= 'XDbYbJN3nkYlgnOulKbCOM_tDE3EUh50A0lpwq6o';

$QBOX_PUT_TIMEOUT	= 300000; // 300s = 5m
define('APPNAME', 'photo360');
define('HOMEPAGE', '/');
define('DOMAIN', $_SERVER["HTTP_HOST"]);
define('HOST', 'http://'.DOMAIN.'/');
$config = array(

    # DEBUG
    'error' => array(
        'reporting'       => 4095,
        'throw_exception' => true,
    ),

    # 数据库
    'db' => array(
        'adapter'            => 'mysql',
        'host'               => '127.0.0.1',
        'dbname'             => APPNAME . '_development',
        'username'           => APPNAME . '_devuser',
        'password'           => APPNAME . '_development_password',
        'charset'            => 'utf8',
        'use_pconnect'       => true,
        'use_buffered_query' => true,
        'throw_exception'    => true,
    ),

    # qiniu account
    'qbox' => array(
        'access_key' => 'LtcS2cGr8WfCpgfZGyX6YDmW4OjOEwt_rNGO0gog',
        'secret_key' => 'XDbYbJN3nkYlgnOulKbCOM_tDE3EUh50A0lpwq6o',
        'bucket'     => 'hhs',
    ),

);
