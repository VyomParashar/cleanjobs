<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'matt_jobs';

global $dbConn;
$dbConn = mysqli_connect($host, $user, $password, $database);
if(mysqli_connect_errno())
	echo 'Connection Error: '.mysqli_connect_error();

// security salt
define('SEC_SALT', "ŸÐ —“W†Óü÷I½Q-+=jRocW");
define('ADMIN_EMAIL','akash5003@gmail.com');
define('EMAIL_FROM','noreply@cleansimple.co');

global $appTables;
$appTables = array(
'adminQus' => 'admin_qus',
'users' => 'users',
'jobQus' => 'job_qus',
'jobs' => 'jobs'
);