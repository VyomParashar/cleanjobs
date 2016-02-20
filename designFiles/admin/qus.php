<style type="text/css">
	section#main{width:100%;margin-left:0;}
</style>
<article class="module width_full">
	<header class="filter-box"><h1><a style="color:#000;text-decoration:none;" href="<?php echo PRJ_BROOT;?>index.php?section=admin&action=qus"><?php echo $title?></a></h1></header>
	<div class="actions-box" style="text-align:right"><a href="javascript:;" onclick="tabInit(this);" title="Add New Customer" dhref="<?php echo PRJ_BROOT;?>index.php?section=admin&action=editQus" data="new_entry"><b>+</b> Add Question</a></div>
	<div class="tab_container">
		<div class="tabs_cont">
			<a href="javascript:;" dhref="<?php echo PRJ_BROOT;?>index.php?section=customers&action=index&job=displayGrid" data="list" class="selected">Questions</a>
			<a href="javascript:;" dhref="<?php echo PRJ_BROOT;?>index.php?section=customers&action=editCust" data="new_entry">Add New Question</a>
		</div>
		<div class="tabs_data_cont" style="padding-top:0;margin-top: -10px;">
			<div id="gridContent" class="tabs_data list selected">
				<?php include('gridQus.php');?>
			</div>
			<div class="tabs_data new_entry">
				
			</div>
		</div>
	</div>
</article>
