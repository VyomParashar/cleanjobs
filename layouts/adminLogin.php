<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $title?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php echo PRJ_BROOT?>css/style.css" type="text/css" media="screen, projection" rel="stylesheet" />

<script type="text/javascript">
	var prj_broot = "<?php echo PRJ_BROOT?>";
	var curUserName = 'Not Logged In';
    var curUserEmail = '';
    var curUserCreated = '<?php echo time()?>';
</script>
<script type="text/javascript" src="<?php echo PRJ_BROOT?>js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="<?php echo PRJ_BROOT?>js/sys.js"></script>
</head>

<body id="login-bg">
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PC8MT9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PC8MT9');</script>
<!-- End Google Tag Manager -->
	<div id="login-holder">
		<?php include_once(DESIGN.$GLOBALS['design'].'.php');?>
	</div>
</body>
</html>