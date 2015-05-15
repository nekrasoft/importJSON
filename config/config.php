<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$config = array();
$config['db'] = array('host' => '127.0.0.1', 'port' => 3306, 'name' => 'idfly', 'user' => 'root', 'pass' => '');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__) . '/..') . '/');

ini_set('error_log', ROOT . 'logs/import-error.log');

date_default_timezone_set('Europe/Moscow');

spl_autoload_register(function ($classname) {
    $path = str_replace('_', DS, $classname) . '.php';
    $path = ROOT . 'lib' . DS . $path;
    include_once $path;
});
