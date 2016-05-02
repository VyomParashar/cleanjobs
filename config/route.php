<?php
global $secArr;
$secArr = array('', 'verify', 'login', 'account', 'admin', 'adminDet', 'home');
if(!in_array(trim($_GET['section']), $secArr))
{
	$_GET['s_comp_code'] = $_GET['section'];
	$_GET['section'] = 'comp';
}
else if($_GET['section'] == 'verify')
{
	$_GET['var2'] = $_GET['var1'];
	$_GET['var1'] = $_GET['action'];
	$_GET['action'] = 'verify';
	$_GET['section'] = 'home';
}
else if($_GET['section'] == 'login')
{
	$_GET['action'] = 'login';
	$_GET['section'] = 'home';
}