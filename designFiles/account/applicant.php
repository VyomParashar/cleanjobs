<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<style type="text/css">
.rm_app,.block_app{}
</style>
<h1 class="account_h1" style="margin-bottom:15px;"> &nbsp; &nbsp; Application Details</h1>
<div id="gridContent" class="welcome_cont jobs_list_s">
	<div class="job_title"><?php echo $jobDet['title']?></div>
	<div class="applicant_info">
		<h2 class="app_name"><?php echo ucwords(trim($jobDet['f_name'] . ' ' . $jobDet['l_name']))?> 
		<div class="opt_apps" style="display:inline-block;vertical-align:middle;">
			<div class="act_opt">...<div class="more_opt_apps"><a class="rm_app" href="javascript:;" ohref="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'del-apps', 'vars' => array('var1' => $jobDet['id'])))?>">Remove from this job</a><a class="block_app" href="javascript:;" ohref="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'block-app', 'vars' => array('var1' => $jobDet['id'])))?>">Block this applicant</a></div>
			</div>
		</div></h2>
		<div class="ot_info">
			<?php echo (((trim($jobDet['city']) == '')?'':$jobDet['city']) . ', ') . (((trim($jobDet['state']) == '')?'':$jobDet['state']) . ', ') . $jobDet['cntry']?><br />
			<a href="mailto:<?php echo $jobDet['email']?>"><?php echo $jobDet['email']?></a><br />
			<a href="tel:<?php echo $jobDet['phn']?>"><?php echo $jobDet['phn']?></a><?php
			if(trim($jobDet['resume']) != ''){
            ?><a target="_blank" href="<?php echo IMAGES . 'cln_resumes/' . $jobDet['id'] . '_' . $jobDet['resume'];?>">Download Attached Resume</a><?php
			}?>
		</div>
	</div>
	<table class="front_list" cellpadding="0" cellspacing="0">
	<thead>
		<th style="width:50%;">Question</th>
		<th>Preferred Answer</th>
		<th>Supplied Answer</th>
	</thead>
	<tbody><?php
	if(is_array($jobQus) && count($jobQus) > 0)
	{
		foreach($jobQus as $curQus)
		{
			$bestAns = array_map('trim',explode(',', $jobQusArr[$curQus['q_id']]['best_ans']));
			$givenAns = array_map('trim', explode(',', $curQus['anss']));
			$givenAnsCnt = count($givenAns);
			$intrAns = array_intersect($givenAns, $bestAns);
			$intrAnsCnt = count($intrAns);?>
		<tr>
			<td><?php echo $jobQusArr[$curQus['q_id']]['qss']?></td>
			<td><?php echo $jobQusArr[$curQus['q_id']]['best_ans']?></td>
			<td class="supp_anss"><?php if($givenAnsCnt == $intrAnsCnt){echo $curQus['anss'];}else{echo '<span>' .$curQus['anss'] . '</span>';}?></td>
		</tr>
	<?php
		}
	}
	?></tbody>
	</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
<script type="text/javascript">
jQuery(document).ready(function(e){
	jQuery('.rm_app,.block_app').click(function(e) {
		var curUrl = jQuery(this).attr('ohref');
		if(jQuery(this).hasClass('rm_app'))
		{
			confirm('Removing this applicant will not remove them from your company database just from this job posting.<br /><br />Do you want to remove this applicant?', function(){
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