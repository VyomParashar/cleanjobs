<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "fa7af8f9-4567-4f56-a497-501591b24ac6", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<style type="text/css">
body.account{background:#fff;}
.job_lst_tb a.j_title{color:#1e6c92;text-decoration:none;
    font-size: 15px;
    font-weight: bold;}
</style>
<div class="welcome_cont">
	<div class="job_list_title"><?php
		if(trim($curUser['c_logo']) != ''){?>
		<img src="<?php echo IMAGES?>comp_logos/<?php echo $curUser['id'] . '_' . $curUser['c_logo']?>" width="200" alt="" /><?php
		}
		else{
			?><img style="border:1px solid #ccc" src="<?php echo IMAGES?>logo/logonot.jpg" alt="" /><?php
		}?>
		
		<h1 class="account_h1">Job Openings</h1>
	</div>
	<h1 class="account_h1 job_list_address">
		<strong class="strong_acc" style="font-size:22px;"><?php echo $curUser['comp_name']?></strong><br />
		<span style="font-size:14px;font-weight:normal;padding-top:3px;"><?php echo $curUser['address']?></span>
	</h1><?php
	if(is_array($pubJobs) && count($pubJobs) > 0)
	{
	?>
	<table class="job_lst_tb job_lst_tb_only" width="100%">
		<tbody><?php
		foreach($pubJobs as $curJob)
		{
			$curJobUrl = $appObj->appUrl(array('section' => $curUser['comp'], 'action' => 'job', 'vars' => array('var1' => $curJob['token'])));
			echo '<tr><td class="a"><a class="j_title" href="' . $curJobUrl . '">' . $curJob['title'] . '</a></td><td class="b"><a class="sub_butt appbut" href="' . $appObj->appUrl(array('section' => $curUser['comp'], 'action' => 'job', 'vars' => array('var1' => $curJob['token']))). '">Apply Now</a></td><td class="c">
			<span class="sh_tg">Share this job</span> <span class="st_facebook_large" displayText="Facebook" st_url="' . $curJobUrl . '"></span>
<span class="st_twitter_large" displayText="Tweet" st_url="' . $curJobUrl . '"></span></td></tr>';
		}
		?></tbody>
	</table><?php
	}
	else
	{
		echo '<h4>No job opening.</h4>';
	}
	?>
</div>