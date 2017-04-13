<?php

defined('MRZPN_EXECUTE') or define('MRZPN_EXECUTE', md5(uniqid()));
defined('ROOT') or define('ROOT', dirname(__DIR__));
defined('WEBROOT') or define('WEBROOT', implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ .'/autoroute.php';
require_once __DIR__ .'/page.php';

$page = new page();
$page->actions();

$autoroute = new autoroute();
