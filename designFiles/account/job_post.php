<script type="text/javascript" src="<?php echo JS;?>tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		menubar:false,
		selector: ".tinymce",
		height : 290,
		relative_urls: false,
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
<form action="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'post-job'))?>" method="post">
<div class="step_cont step1">
	<h1 class="account_h1"> &nbsp; &nbsp; Provide Job Name and Description<br />
	<strong class="strong_acc"> &nbsp; &nbsp; &nbsp; &nbsp; STEP 1 of 4</strong></h1>
	<div class="welcome_cont job_post">
		<div class="formRow"><label class="ll">Title:</label> <input type="text" name="title" placeholder="Title goes here. Example: Winter Night Shift Cleaner" /></div>
		<div class="formRow"><label class="ll">Description:</label> <div class="ffield"><textarea id="desc_id" class="tinymce" name="desc" placeholder="Description of the job here."></textarea></div></div>
		<div class="formRow submit_cont"><a class="sub_butt stepChng" step="2">Proceed to Step 2</a></div>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
	</div>
</div>
<div class="step_cont step2" style="display:none">
	<h1 class="account_h1"> &nbsp; &nbsp; Background Questions<br />
	<strong class="strong_acc"> &nbsp; &nbsp; &nbsp; &nbsp; STEP 2 of 4</strong></h1>
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
					<td><input type="checkbox" class="req_chk" name="quss[<?php echo $cnt?>][req]" name="1" /></td>
					<td><?php
					$anss = explode(',', $curQus['anss']);
					if(is_array($anss) && count($anss) > 0)
					{
						$ansOpts = '';
						foreach($anss as $ans)
						{
							$ans = trim($ans);
							$ansOpts .= '<option';
							if($ans == $curJob['quss'][$curQus['id']]['best_ans'])
								$ansOpts .= ' #best_ans#';
							if($ans == $curJob['quss'][$curQus['id']]['filt_ans'])
								$ansOpts .= ' #filt_ans#';
							$ansOpts .= ' value="' . $ans . '">' . $ans . '</option>';
						}
						if($curQus['a_type'] == 1)
						{
							foreach($anss as $idx => $ans)
							{
								if($idx != 0)
								{
									echo '</td></tr><td colspan="2"></td><td>';
								}
									
								echo '<span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" name="quss[' . $cnt . '][req][' . $idx . ']" value="1" /></td>
							<td><span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" name="quss[' . $cnt . '][best_ans][' . $idx . ']" value="1" />';
							}
						}
						else
						{
							echo '<select name="quss[' . $cnt . '][best_ans]">';
							echo str_replace('#best_ans#', 'selected', $ansOpts);
							echo '</select></td>
							<td>
							
							<select name="quss[' . $cnt . '][best_ans]"><option value="">Don\'t Screen</option>';
							echo str_replace('#best_ans#', 'selected', $ansOpts);
							echo '</select>';
						}
					}?></td>
				</tr><?php
				}?>
			</tbody>
		</table>
		<div class="formRow submit_cont"><a class="sub_butt stepChng prevChng" step="1">< Back to Step 1</a><a class="sub_butt stepChng" step="3">Proceed to Step 3</a></div><?php
		}else{?>
			<h4>Sorry no background question found.</h4>
		<?php
		}?>
		<p>&nbsp;</p>
	</div>
</div>
<div class="step_cont step3" style="display:none">
	<h1 class="account_h1"> &nbsp; &nbsp; Availability<br />
	<strong class="strong_acc"> &nbsp; &nbsp; &nbsp; &nbsp; STEP 3 of 4</strong></h1>
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
			$cnt = 0;
			foreach($availQus as $curQus)
			{?><tr>
					<td class="q"><?php echo $curQus['qss']?></td>
					<td><input type="checkbox" class="req_chk" name="quss[<?php echo $cnt?>][req]" name="1" /></td>
					<?php
					$anss = explode(',', $curQus['anss']);
					if(is_array($anss) && count($anss) > 0)
					{
						$ansOpts = '';
						foreach($anss as $ans)
						{
							$ans = trim($ans);
							$ansOpts .= '<option';
							if($ans == $curJob['quss'][$curQus['id']]['best_ans'])
								$ansOpts .= ' #best_ans#';
							if($ans == $curJob['quss'][$curQus['id']]['filt_ans'])
								$ansOpts .= ' #filt_ans#';
							$ansOpts .= ' value="' . $ans . '">' . $ans . '</option>';
						}
						if($curQus['a_type'] == 1)
						{?><td style="text-align:right;padding-right:25px;"><?php
							foreach($anss as $idx => $ans)
							{
								if($idx != 0)
								{
									echo '</td></tr><td colspan="2"></td><td style="text-align:right;padding-right:25px;">';
								}
									
								echo '<span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" name="quss[' . $cnt . '][req][' . $idx . ']" value="1" /></td>
							<td style="text-align:right;"><span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" name="quss[' . $cnt . '][best_ans][' . $idx . ']" value="1" />';
							}
						}
						else
						{?><td><?php
							echo '<select name="quss[' . $cnt . '][best_ans]">';
							echo str_replace('#best_ans#', 'selected', $ansOpts);
							echo '</select></td>
							<td>
							
							<select name="quss[' . $cnt . '][best_ans]"><option value="">Don\'t Screen</option>';
							echo str_replace('#best_ans#', 'selected', $ansOpts);
							echo '</select>';
						}
					}else{?><td><?php }?></td>
				</tr><?php
				}?>
			</tbody>
		</table>
		<div class="formRow submit_cont"><a style="background:#eee;" class="sub_butt stepChng prevChng" step="1">< Back to Step 1</a><a class="sub_butt stepChng prevChng" step="2">< Back to Step 2</a><a class="sub_butt stepChng" step="4">Proceed to Step 4</a></div><?php
		}else{?>
			<h4>Sorry no background question found.</h4>
		<?php
		}?>
		<p>&nbsp;</p>
	</div>
</div>
<div class="step_cont step4" style="display:none">
	<h1 class="account_h1"> &nbsp; &nbsp; Preview<br />
	<strong class="strong_acc"> &nbsp; &nbsp; &nbsp; &nbsp; STEP 4 of 4</strong></h1>
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
		?><div class="formRow submit_cont"><a style="background:#eee;" class="sub_butt stepChng prevChng" step="1">< Back to Step 1</a><a class="sub_butt stepChng prevChng" step="2">< Back to Step 2</a><a style="background:#ccc;" class="sub_butt stepChng prevChng" step="3">< Back to Step 3</a><input type="submit" class="sub_butt" value="SAVE and PUBLISH" name="submit" style="padding: 9px 30px;" /></div>
		<div class="formRow submit_cont">
			<input type="submit" class="sub_butt" name="submit" value="SAVE only" style="padding: 9px 30px;width:189px" /><br />
			<span style="display:inline-block;width:184px; text-align:center;">You can publish this later from the jobs view.</span>
		</div>
		<p>&nbsp;</p>
	</div>
</div>
</form>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	jQuery('.stepChng').click(function(e) {
		var stepNo = jQuery(this).attr('step');
		if(stepNo == 2)
		{
			var title = jQuery('.step1 input[name=title]').val();
			if(title == '')
			{
				alert('Please provide job title.');
				return false;
			}
		}
		if(stepNo == 4)
		{
			var title = jQuery('.step1 input[name=title]').val();
			var desc = tinyMCE.get('desc_id').getContent();
			jQuery('.step4 .prev_h1').html(title);
			jQuery('.step4 .prev_desc').html(desc);
		}
		jQuery('.step_cont').hide();
		jQuery('.step_cont.step' + stepNo).show();
	});
});
</script>



