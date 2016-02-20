<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $title?></title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo CSS?>style.css" type="text/css" media="screen" />

<script type="text/javascript">
	var prj_broot = "<?php echo PRJ_BROOT?>";
	var curUserName = '<?php echo $appObj->readSession('admin_name') . ' ' . $appObj->readSession('admin_lname')?>';
    var curUserEmail = '<?php echo $appObj->readSession('admin_email')?>';
    var curUserCreated = '<?php echo strtotime($appObj->readSession('admin_created'))?>';
</script>

<script src="<?php echo PRJ_BROOT?>js/jquery-1.11.1.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo PRJ_BROOT?>js/sys.js"></script>
</head>

<body>
<!-- Google Tag Manager -->
<?php /*?><noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-K8SC3V"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K8SC3V');</script><?php */?>
<!-- End Google Tag Manager --><?php
include(LAYOUT.'elements/header.php');
?>
<section id="main" class="column">
	<div id="content">
		<?php include_once(DESIGN.$GLOBALS['design'].'.php');?>
	</div>
	<div class="clear">&nbsp;</div>
</section>
<div id="mask" class="add_mask">&nbsp;</div>
<div id="ajaxDiv" class="ajaxDiv">
	<h3>Please wait</h3><br />
	<img src="<?php echo PRJ_BROOT?>multimedias/images/ajax/ajax.gif" title="Loader" alt="Loader" />
</div>
<article id="ajaxResContainer">
	<a href="javascript:void(0);" class="ajaxPopClose" onclick="hideDiv('ajaxResContainer'); hideMask(); makeLoadingDiv('showRep');">X</a>
	<div id="showRep">
		<h3 style='padding:10px;'>Loading...</h3>
	</div>
</article>
<iframe id="resultInFrame" name="resultInFrame" style="display:none;"></iframe>
</body>
</html>