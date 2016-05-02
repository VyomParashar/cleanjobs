<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<div class="job_title"><?php echo $jobDet['title']?> <a class="sub_butt appbut black" href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'job-post', 'vars' => array('var1' => $jobDet['id'])))?>">Edit Job Details</a></div>
<div style="text-align:right;font-size:14px;"><strong>Published</strong> since <?php echo date('F j, Y', strtotime($jobDet['created']))?></div>
<table class="front_list" cellpadding="0" cellspacing="0">
	<thead>
		<th><?php echo $appObj->ajaxSort('Name','f_name');?></th>
		<th><?php echo $appObj->ajaxSort('Date','created');?></th>
		<th><?php echo $appObj->ajaxSort('Fit','per_fit');?></th>
		<th style="width:230px;"></th>
	</thead>
	<tbody><?php
	if(is_array($applicants) && count($applicants) > 0)
	{
		foreach($applicants as $curApp)
		{?><tr>
			<td><?php echo $curApp['f_name'] . ' ' . $curApp['l_name']?></td>
			<td><?php echo date('F d, Y', strtotime($curApp['created']))?></td>
			<td><?php echo $curApp['per_fit']?>%</td>
			<td class="opt_apps"><div class="act_opt">...<div class="more_opt_apps"><a class="rm_app" href="javascript:;" ohref="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'del-apps', 'vars' => array('var1' => $curApp['id'])))?>">Remove from this job</a><a class="block_app" href="javascript:;" ohref="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'block-app', 'vars' => array('var1' => $curApp['id'])))?>">Block this applicant</a></div>
			</div><a class="sub_butt appbut grey" href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'applicant', 'vars' => array('var1' => $curApp['id'])))?>">View Applicant</a></td>
		</tr><?php
		}
	}
	else
	{?>
		<tr>
			<td colspan="4">No applicant found.</td>
		</tr>
	<?php
	}?></tbody>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script type="text/javascript">
jQuery(document).ready(function(e){
	jQuery('.rm_app,.block_app').click(function(e) {
		var curUrl = jQuery(this).attr('ohref');
		if(jQuery(this).hasClass('rm_app'))
		{
			var objCur = this;
			confirm('Removing this applicant will not remove them from your company database just from this job posting.<br /><br />Do you want to remove this applicant?', function(){
				jQuery(objCur).closest('tr').remove();
				jQuery.ajax({
						type : 'get',
						url : curUrl,
						success : function(data)
						{}
					});
			})
			return false;
		}
		else if(!confirm('This action will block this applicant from applying to any job you have published now, or any new job you publish.<br /><br />Do you want to block this applicant?', function(){
				jQuery.ajax({
					type : 'get',
					url : curUrl,
					success : function(data)
					{}
				});
			}))
			return false;
	});
});
</script>