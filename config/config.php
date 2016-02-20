<?php
error_reporting(1);
session_start();
define('DEFAULT_TIMEZONE', 'America/Halifax');
date_default_timezone_set(DEFAULT_TIMEZONE);

define('HTACCESS','YES');

// to set does app use SMTP mail or simple mail
define('MAIL','SIMPLE');

$appHost = $_SERVER['HTTP_HOST'];
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define('PRJ_FROOT', $protocol . $appHost . '/matt/job/');
define('PRJ_BROOT',PRJ_FROOT);
define('SERVER_NAME',$_SERVER['HTTP_HOST']);
define('SITE_NAME','cleansimplejobs.co');
define('WEB_ROOT','./');
define('DESIGN',WEB_ROOT.'designFiles/');
define('CONTROLLER',WEB_ROOT.'controllerFiles/');
define('MODEL',WEB_ROOT.'models/');
define('LIB',WEB_ROOT.'libs/');
define('LAYOUT',WEB_ROOT.'layouts/');
define('CSS',PRJ_FROOT.'css/');
define('IMAGES',PRJ_FROOT.'multimedias/images/');
define('JS',PRJ_FROOT.'js/');