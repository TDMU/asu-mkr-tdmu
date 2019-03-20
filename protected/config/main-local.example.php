<?php

@include dirname(__FILE__) . '/universities.php';

/*$config['modules']['gii'] = array(
	'class'=>'system.gii.GiiModule',
	'password' => false,
	// If removed, Gii defaults to localhost only. Edit carefully to taste.
	'ipFilters'=>array('127.0.0.1','::1'),
);*/

$group = &$config['components']['db'];
$group = array_merge($group, array(
    'connectionString' => 'firebird:dbname=localhost:/path/to/db',
    'class' => 'ext.YiiFirebird.CFirebirdConnection',
    'username' => '',
    'password' => '',
));

$config['components']['log']['routes'][] = array(
    'class'=>'CWebLogRoute',
    'categories'=>'system.db.CDbCommand',
    'showInFireBug'=>false,
    'enabled'=>false
);
$config['components']['db']['enableProfiling'] = true;
$config['components']['db']['enableParamLogging'] = true;
