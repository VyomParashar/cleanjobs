<?php
error_reporting(E_ALL);
/* include the configuration file */
define('WEB_ROOT_ABS', dirname(__FILE__) . '/');
require_once("./config/config.php");
require_once(WEB_ROOT . "config/route.php");
require_once(WEB_ROOT . "loader.php");