<?php global $email_ddata;?>
<div style="width:643px;text-align:center;">
<a href="<?php echo $email_ddata['home_url']?>"><img src="<?php echo IMAGES?>front/email_conf_bck.png" alt="" /></a>
<div>&nbsp;</div>
<?php
foreach($email_ddata['pubJobs'] as $curJob)
{?>
	<p>Your published job <strong><?php echo $curJob['title']?></strong> has received <?php echo $email_ddata['specArr'][$curJob['id']]['cnt']?> new applications.</p>
	<?php
		if($email_ddata['specArr'][$curJob['id']]['cnt'] > 0)
		{
			echo '<p><span style="font-size:0.9em;">Top Applicants for this job:</span><br />';
			foreach($email_ddata['specArr'][$curJob['id']]['apps'] as $curApp)
			{?>
			<strong><?php echo ucwords($curApp['f_name'] . ' ' . $curApp['l_name'])?></strong> is a <strong><?php echo $curApp['per_fit']?>% fit</strong> for your job (<a style="font-size:0.9em;color:#1D6B91;" href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'applicant', 'vars' => array('var1' => $curApp['id'])))?>">view application</a>)<br /><?php
			}?>
			<a style="outline: none;display: inline-block;cursor: pointer;background-color: #5CC560;border: 0;color: #fff;padding: 7px 30px;margin-left: 6px;font-weight: bold;font-size: 14px;border-radius: 15px;text-decoration: none;" href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'applicants', 'vars' => array('var1' => $curJob['id'])))?>">View all applications for this job</a><?php
			echo '</p>';
		}?>
		<hr />
	<?php
}
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>