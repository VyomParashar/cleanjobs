<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $title?></title>

<link rel="stylesheet" href="<?php echo CSS?>front.css" type="text/css" media="screen" />
<script src="<?php echo PRJ_BROOT?>js/jquery-1.11.1.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo PRJ_BROOT?>js/front.js"></script>
</head>

<body class="front"><?php
include(LAYOUT.'elements/frontHeader.php');
?>
<section id="main" class="column">
	<div id="content">
		<?php include_once(DESIGN.$GLOBALS['design'].'.php');?>
	</div>
	<div class="clear">&nbsp;</div>
</section><?php
include(LAYOUT.'elements/frontFooter.php');
?>
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