<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="<?php echo JS;?>tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		menubar:false,
		selector: ".tinymce",
		height : 290,
		relative_urls: false,
        mode : "specific_textareas",
		editor_selector : "tinymce",
		remove_script_host: false,
		//theme : "modern",
		plugins: [
			"advlist autolink lists link anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime paste"
		],
		relative_urls: false,
		toolbar: "bold underline italic | link | bullist numlist"
	});
</script>
<style type="text/css"><?php
if($jobDet['id'] > 0){?>
h1.account_h1{margin:0 10px;background:#fff;}
h1.account_h1.top_h{background:transparent;margin:30px 0;}
.welcome_cont{margin:0 10px;}
<?php
}
?></style>
<form action="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'post-job'))?>" method="post">
<div class="step_cont step1"><?php
if($jobDet['id'] > 0){?>
	<input type="hidden" name="job_id" value="<?php echo $jobDet['id']?>" />
	<h1 class="account_h1 top_h"> &nbsp; &nbsp; Edit Job Details</h1>
	<h1 class="account_h1" style="padding-top:20px;"> &nbsp; &nbsp; Title & Description
		<div class="pubs_cont" style="float:right">
			<?php
			if($jobDet['status'] == 1)
			{
				echo '<span class="pub_g"><strong>Published</strong> on ' . date('F j, Y', strtotime($jobDet['s_date'])) . '</span>';
			}
			else
			{
				echo '<strong>Not Published</strong>';
			}
			echo ' <a class="publishchk status' . $jobDet['status'] . '" style="vertical-align:text-bottom;" href="' . $appObj->appUrl(array('section' => 'account', 'action' => 'status-jobs', 'vars' => array('var1' => $jobDet['id'], 'var2' => 'job'))) . '"></a> &nbsp; &nbsp; ';?>
		</div>
	</h1><?php
}else{?>
	<h1 class="account_h1"> &nbsp; &nbsp; Provide Job Name and Description<br />
	<strong class="strong_acc"> &nbsp; &nbsp; &nbsp; &nbsp; STEP 1 of 4</strong></h1><?php
}?>
	<div class="welcome_cont job_post">
		<div class="formRow"><label class="ll">Title:</label> <input type="text" name="title" value="<?php echo $jobDet['title']?>" placeholder="Title goes here. Example: Winter Night Shift Cleaner" /></div>
		<div class="formRow"><label class="ll">Description:</label> <div class="ffield"><textarea id="desc_id" class="tinymce" name="desc" placeholder="Description of the job here."><?php echo $jobDet['j_desc']?></textarea></div></div>
		<div class="t_d_err"><strong>Attention:</strong> You must include title and description when adding a new job posting.</div><?php
if($jobDet['id'] <= 0){?>
		<div class="formRow submit_cont"><a class="sub_butt stepChng" step="2">Proceed to Step 2</a></div><?php
}?>
	</div>
</div>
<div class="step_cont step2"<?php
if($jobDet['id'] <= 0){?> style="display:none"<?php
}?>>
	<h1 class="account_h1"> &nbsp; &nbsp; Background Questions<?php
if($jobDet['id'] <= 0){?><br />
	<strong class="strong_acc"> &nbsp; &nbsp; &nbsp; &nbsp; STEP 2 of 4</strong><?php
}?></h1>
	<div class="welcome_cont job_post"><?php
		if(is_array($backQus) && count($backQus) > 0)
		{?><table class="post_qus_list" cellpadding="0" cellspacing="0">
			<thead>
				<th style="width:40%;"></th>
				<th style="width:10%;">Required</th>
				<th style="width:25%;">Best Answer</th>
				<th style="width:25%;">Screen out if answer is</th>
			</thead>
			<tbody><?php
			$cnt = 0;
			foreach($backQus as $curQus)
			{?><tr>
					<td class="q"><?php echo $curQus['qss']?></td>
					<td><input type="hidden" name="quss[<?php echo $cnt?>][q_id]" value="<?php echo $curQus['id']?>" /><input type="checkbox" class="req_chk" name="quss[<?php echo $cnt?>][req]" value="1" <?php if($jobDet['quss'][$curQus['id']]['q_req'] == 1){ echo 'checked'; }?> /></td>
					<?php
					$anss = explode(',', $curQus['anss']);
					if(is_array($anss) && count($anss) > 0)
					{
						$ansOpts = '';
						foreach($anss as $ans)
						{
							$ans = trim($ans);
							$ansOpts .= '<option';
							if($ans == $jobDet['quss'][$curQus['id']]['best_ans'])
								$ansOpts .= ' #best_ans#';
							if($ans == $jobDet['quss'][$curQus['id']]['filt_ans'])
								$ansOpts .= ' #filt_ans#';
							$ansOpts .= ' value="' . $ans . '">' . $ans . '</option>';
						}
						
						if($curQus['a_type'] == 1)
						{
							$best_ans_arr =@ explode(',', $jobDet['quss'][$curQus['id']]['best_ans']);
							$filt_ans_arr =@ explode(',', $jobDet['quss'][$curQus['id']]['filt_ans']);
							?><td style="text-align:right;padding-right:25px;"><?php
							foreach($anss as $idx => $ans)
							{
								$ans = trim($ans);
								if($idx != 0)
								{
									echo '</td></tr><td colspan="2"></td><td style="text-align:right;padding-right:25px;">';
								}
								
								echo '<span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" name="quss[' . $cnt . '][best_ans][]" value="' . $ans . '"';
								if(in_array($ans, $best_ans_arr))
									echo ' checked';
								echo ' /></td>
							<td style="text-align:right;"><span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" name="quss[' . $cnt . '][screen_out][]" value="' . $ans . '"';
								if(in_array($ans, $filt_ans_arr))
									echo ' checked';
								echo ' />';
							}
						}
						else
						{?><td><?php
							echo '<select name="quss[' . $cnt . '][best_ans]">';
							echo str_replace('#best_ans#', 'selected', $ansOpts);
							echo '</select></td>
							<td>
							
							<select name="quss[' . $cnt . '][screen_out]"><option value="">Don\'t Screen</option>';
							echo str_replace('#filt_ans#', 'selected', $ansOpts);
							echo '</select>';
						}
					}else{?><td><?php }?></td>
				</tr><?php
					$cnt++;
				}?>
                <tr>
                <td class="q">Attach resume</td>
                <td><input type="checkbox" class="req_chk" <?php if($jobDet['q_res_req'] == 1 || trim($jobDet['q_res_best']) ==''){echo 'checked';}?> name="q_res_req" value="1" /></td>
                <td><select name="q_res_best"><option <?php if(trim($jobDet['q_res_best']) == 'Resume Attached'){echo 'selected';}?> value="Resume Attached">Resume Attached</option><option <?php if(trim($jobDet['q_res_best']) == 'No Resume Attached'){echo 'selected';}?> value="No Resume Attached">No Resume Attached</option></select></td>
                <td><select name="q_res_screen"><option value="">Don't Screen</option><option value="Resume Attached" <?php if(trim($jobDet['q_res_screen']) == 'Resume Attached'){echo 'selected';}?>>Resume Attached</option><option value="No Resume Attached" <?php if(trim($jobDet['q_res_screen']) == 'No Resume Attached'){echo 'selected';}?>>No Resume Attached</option></select></td>
                </tr>
			</tbody>
		</table><?php
			if($jobDet['id'] <= 0)
			{?>
		<div class="formRow submit_cont"><a class="sub_butt stepChng prevChng" step="1">< Back to Step 1</a><a class="sub_butt stepChng" step="3">Proceed to Step 3</a></div><?php
			}
		}else{?>
			<h4>Sorry no background question found.</h4>
		<?php
		}?>
	</div>
</div>
<div class="step_cont step3"<?php
if($jobDet['id'] <= 0){?> style="display:none"<?php
}?>>
	<h1 class="account_h1"> &nbsp; &nbsp; Availability<?php
if($jobDet['id'] <= 0){?><br />
	<strong class="strong_acc"> &nbsp; &nbsp; &nbsp; &nbsp; STEP 3 of 4</strong><?php
}?></h1>
	<div class="welcome_cont job_post"><?php
		if(is_array($availQus) && count($availQus) > 0)
		{?><table class="post_qus_list" cellpadding="0" cellspacing="0">
			<thead>
				<th style="width:40%;"></th>
				<th style="width:10%;">Required</th>
				<th style="width:25%;">Best Answer</th>
				<th style="width:25%;">Screen out if answer is</th>
			</thead>
			<tbody><?php
			foreach($availQus as $curQus)
			{?><tr>
					<td class="q"><?php echo $curQus['qss']?></td>
					<td><input type="hidden" name="quss[<?php echo $cnt?>][q_id]" value="<?php echo $curQus['id']?>" /><input type="checkbox" class="req_chk" name="quss[<?php echo $cnt?>][req]" value="1" <?php if($jobDet['quss'][$curQus['id']]['q_req'] == 1){ echo 'checked'; }?> /></td><?php
					$anss = explode(',', $curQus['anss']);
					if(is_array($anss) && count($anss) > 0)
					{
						$ansOpts = '';
						foreach($anss as $ans)
						{
							$ans = trim($ans);
							$ansOpts .= '<option';
							if($ans == $jobDet['quss'][$curQus['id']]['best_ans'])
								$ansOpts .= ' #best_ans#';
							if($ans == $jobDet['quss'][$curQus['id']]['filt_ans'])
								$ansOpts .= ' #filt_ans#';
							$ansOpts .= ' value="' . $ans . '">' . $ans . '</option>';
						}
						if($curQus['a_type'] == 1)
						{
							$best_ans_arr =@ explode(',', $jobDet['quss'][$curQus['id']]['best_ans']);
							$filt_ans_arr =@ explode(',', $jobDet['quss'][$curQus['id']]['filt_ans']);
							?><td style="text-align:right;padding-right:25px;"><?php
							foreach($anss as $idx => $ans)
							{
								$ans = trim($ans);
								if($idx != 0)
								{
									echo '</td></tr><td colspan="2"></td><td style="text-align:right;padding-right:25px;">';
								}
								
								echo '<span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" name="quss[' . $cnt . '][best_ans][]" value="' . $ans . '"';
								if(in_array($ans, $best_ans_arr))
									echo ' checked';
								echo ' /></td>
							<td style="text-align:right;"><span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" name="quss[' . $cnt . '][screen_out][]" value="' . $ans . '"';
								if(in_array($ans, $filt_ans_arr))
									echo ' checked';
								echo ' />';
							}
						}
						else
						{?><td><?php
							echo '<select name="quss[' . $cnt . '][best_ans]">';
							echo str_replace('#best_ans#', 'selected', $ansOpts);
							echo '</select></td>
							<td>
							
							<select name="quss[' . $cnt . '][screen_out]"><option value="">Don\'t Screen</option>';
							echo str_replace('#filt_ans#', 'selected', $ansOpts);
							echo '</select>';
						}
					}else{?><td><?php }?></td>
				</tr><?php
					$cnt++;
				}?>
			</tbody>
		</table><?php
			if($jobDet['id'] <= 0){?>
		<div class="formRow submit_cont"><a style="background:#eee;" class="sub_butt stepChng prevChng" step="1">< Back to Step 1</a><a class="sub_butt stepChng prevChng" step="2">< Back to Step 2</a><a class="sub_butt stepChng" step="4">Proceed to Step 4</a></div><?php
			}else
			{?><div class="formRow submit_cont">
				<input type="submit" class="sub_butt" name="submit" value="SAVE CHANGES" style="padding: 9px 30px;width:189px" /><br />
				<div style="display:inline-block;width:250px;text-align:center;padding-top:60px;">
				<a href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'copy-jobs', 'vars' => array('var1' => $jobDet['id'], 'var2' => 'job')))?>" style="color:#1e6c92;font-size:14px;text-decoration:none;">Copy This Job</a> &nbsp; | &nbsp; <a href="javascript:;" onclick="confirm('Are you sure you want to delete this job posting? Applicants will not be deleted, but all job details will be lost.',function(){ window.location.href = '<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'delete-jobs', 'vars' => array('var1' => $jobDet['id'], 'var2' => 'job')))?>';})" style="color:#1e6c92;font-size:14px;text-decoration:none;">Delete This Job</a></div>
			</div>
			<?php
			}
		}else{?>
			<h4>Sorry no background question found.</h4>
		<?php
		}?>
		<p>&nbsp;</p>
	</div>
</div>
<div class="step_cont step4" style="display:none">
	<h1 class="account_h1"> &nbsp; &nbsp; Preview<?php
			if($jobDet['id'] <= 0){?><br />
	<strong class="strong_acc"> &nbsp; &nbsp; &nbsp; &nbsp; STEP 4 of 4</strong><?php
			}?></h1>
	<div class="welcome_cont job_post">
		<h1 class="prev_h1"></h1>
		<div class="prev_desc"></div><?php
		$mergedArr = array_merge($backQus, $availQus);
		if(is_array($mergedArr) && count($mergedArr) > 0)
		{
			foreach($mergedArr as $curQus)
			{
				echo '<div class="quest_cont">
						<div class="q_qus">' . $curQus['qss'] . '</div>
						<div class="q_ans">';
				$anss = explode(',', $curQus['anss']);	
				if($curQus['a_type'] == 1)
				{
					foreach($anss as $idx => $ans)
					{
						echo '<div class="ans_chk"><span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" /></div>';
					}
				}
				else
				{
					echo '<select><option value="">Select</option>';
					foreach($anss as $ans)
					{
						$ans = trim($ans);
						echo '<option value="' . $ans . '">' . $ans . '</option>';
					}
					echo '</select>';
				}
				echo '</div>
				</div>';
			}
		}
		
		if($jobDet['id'] <= 0){?><div class="formRow submit_cont"><a style="background:#eee;" class="sub_butt stepChng prevChng" step="1">< Back to Step 1</a><a class="sub_butt stepChng prevChng" step="2">< Back to Step 2</a><a style="background:#ccc;" class="sub_butt stepChng prevChng" step="3">< Back to Step 3</a><input type="submit" class="sub_butt" value="SAVE and PUBLISH" name="submit" style="padding: 9px 30px;" /></div>
		<div class="formRow submit_cont">
			<input type="submit" class="sub_butt" name="submit" value="SAVE only" style="padding: 9px 30px;width:189px" /><br />
			<span style="display:inline-block;width:184px; text-align:center;">You can publish this later from the jobs view.</span>
		</div><?php
		}?>
		<p>&nbsp;</p>
	</div>
</div>
</form>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	jQuery('.stepChng').click(function(e) {
		var stepNo = jQuery(this).attr('step');
		jQuery('.t_d_err').hide();
		var title = jQuery('.step1 input[name=title]').val();
		var desc = tinyMCE.get('desc_id').getContent();
		if(stepNo == 2)
		{
			if(title == '' || desc == '')
			{
				jQuery('.t_d_err').show();
				return false;
			}
		}
		if(stepNo == 4)
		{
			var title = jQuery('.step1 input[name=title]').val();
			jQuery('.step4 .prev_h1').html(title);
			jQuery('.step4 .prev_desc').html(desc);
		}
		jQuery('.step_cont').hide();
		jQuery('.step_cont.step' + stepNo).show();
	});
});
</script>



