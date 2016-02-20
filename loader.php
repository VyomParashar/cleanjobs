<?php
/* include all library files */
require_once(WEB_ROOT."config/database.php");
require_once(LIB."all.php");

/* Get controller and action request */	
if(isset($_GET['section']) && $_GET['section']!='')
{
	$GLOBALS['section'] = $_GET['section'];
	
	if(isset($_GET['action']) && $_GET['action']!='')
	{
		$GLOBALS['action'] = $_GET['action'];
	}
	else
	{
		$GLOBALS['action'] = 'index';
	}
}
else
{
	$GLOBALS['section'] = 'home';
		
	$GLOBALS['action'] = 'index';
}
$GLOBALS['section'] = str_replace(array('-'),array('_'),$GLOBALS['section']);
		
$GLOBALS['action'] = str_replace(array('-'),array('_'),$GLOBALS['action']);

/* include required model file */
if(file_exists(MODEL.$GLOBALS['section'].".php"))
{
	require_once(MODEL.$GLOBALS['section'].".php");
	
	$mdlClass = $GLOBALS['section'].'Model';
	
	$GLOBALS['dbObj'] = new $mdlClass();
}
/* include required controller file */
if(file_exists(CONTROLLER.$GLOBALS['section'].'.php'))
{
	include_once(CONTROLLER.$GLOBALS['section'].'.php');
}
else
{
		echo 'Controller '.$GLOBALS['section'].' not defined'; die;
}
/* Execute specific action */
if(!isset($GLOBALS['action']) || $GLOBALS['action']=='')
{
	$GLOBALS['action'] = 'index';
}
$GLOBALS['layout'] = 'default';

if(isset($_GET['applayout']) && $_GET['applayout']=='ajax')
{
	$GLOBALS['layout'] = 'ajax';
}

$GLOBALS['design'] = $GLOBALS['section'].'/'.$GLOBALS['action'];

$contObj = new $GLOBALS['section']();

$GLOBALS['appObj'] = $contObj;

if(method_exists($contObj,'beforeAction'))
{
	$contObj->beforeAction();
}

if(method_exists($contObj,$GLOBALS['action']))
{
	$contObj->$GLOBALS['action']();
}
else
{
	echo 'Action '.$GLOBALS['action'].' not defined'; die;
}
/* Call layout and design files */
include_once(LAYOUT.$GLOBALS['layout'].'.php');