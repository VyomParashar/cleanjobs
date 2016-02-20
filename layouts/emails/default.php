<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
	div#msgContainer
	{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
		color:#000000;
		width:100%;
		line-height:2;
	}
	a{text-decoration:none;}
	div#msgContainer a:link,div#msgContainer a:visited,div#msgContainer a:active
	{
		color:#0099CC;
		text-decoration:none;
	}
	
	div#msgContainer a:hover
	{
		color:#335566;
		text-decoration:underline;
	}
	
	div#msgContainer div#msgLogo
	{
		background-position:top left;
		background-repeat:no-repeat;
		width:100%;
		height:57px;
	}
	div#msgContainer div#msgContent
	{
		width:100%;
		float:left;
		padding-left:10px;
	}
	div#msgContainer div#msgContent span.msgContentHeading
	{
		width:100%;
		float:left;
		font-size:18px;
		font-weight:bold;
		color:#D60004;
		margin-bottom:10px;
	}
	
	div#msgContainer span.spanPoint
	{
		font-weight:bold;
	}
	
	div#msgContainer div#msgFooter
	{
		color:#3385AD;
		float:left;
		padding-top:10px;
	}
</style>
</head>
<body>
	<div id="msgContainer">
		<div id="msgContent"><?php
			global $email_design;
			if($email_design)
			include(LAYOUT . 'emails/custom/' . $email_design . '.php');?>
		</div>
	</div>
</body>
</html>
