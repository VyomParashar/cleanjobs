<div class="apply_job_cnt" style="padding-bottom:0;">
	<span class="j_title"><?php echo $curUser['comp_name']?></span>
	<span class="j_op">Job Opening</span>
	<h1 class="job_title" style="margin-bottom: 20px;"><?php echo $jobDet['title']?></h1>
	<a class="sub_butt read" href="javascript:;">Read Job Description</a><?php
	if($jobDet['status'] == 1){?><a class="sub_butt apply" href="javascript:;">Apply Now</a><?php
	}
	else
	{?>
		<div id="message" for="fmsg" style="max-width:600px;margin:20px auto 0 auto;"><div class="loginerr">Thank you for your interest.  We are not currently accepting applications for this position.</div></div>
	<?php
	}?>
</div>
<div class="jb_desc_cont apply_box" style="display:none;">
	<div class="j_title">Job Description</div>
	<div class="apply_box_inn">
		<?php echo $jobDet['j_desc'];
		if($jobDet['status'] == 1){?>
		<div class="ff_row" style="text-align:center;padding:20px 0;">
			<a class="sub_butt apply" href="javascript:;">Apply Now</a>
		</div><?php
		}?>
	</div>
</div>
<form id="qs_form" target="resultInFrame" action="<?php echo $appObj->appUrl(array('section' => $curUser['comp'], 'action' => 'job', 'vars' => array('var1' => $jobDet['token'])))?>" method="post" enctype="multipart/form-data">
<div class="jb_form_cont apply_box">
	<div class="j_title">STEP 1: Your Contact Information</div>
	<div class="apply_box_inn" style="max-width:445px;margin:0 auto;">
		<div class="ff_row">
			<label>First Name:</label>
			<input type="text" class="req" name="f_name" />
		</div>
		<div class="ff_row">
			<label>Last Name:</label>
			<input type="text" name="l_name" />
		</div>
		<div class="ff_row">
			<label>Email:</label>
			<input type="email" class="req" name="c_email" />
		</div>
		<div class="ff_row">
			<label>Cell Number:</label>
			<input type="tel" name="phn" />
		</div>
		<div class="ff_row">
			<label>Country:</label>
			<?php /*?><input type="text" name="cntry" /><?php */?>
            <select class="req" name="cntry">
				<option value="">- Select -</option><?php
				foreach($appObj->countries as $cntry)
				{
					echo '<option>' . $cntry . '</option>';
				}
				?></select>
		</div>
		<div class="ff_row">
			<label>Province/State:</label>
			<?php /*?><input type="text" name="state" /><?php */?>
            <select name="state">
                <option value="">- Select -</option><?php
                foreach($appObj->usaStates as $state)
                {
                    echo '<option class="usa">' . $state . '</option>';
                }
                foreach($appObj->canadaStates as $state)
                {
                    echo '<option class="canada">' . $state . '</option>';
                }
            ?></select>
		</div>
		<div class="ff_row">
			<label>City/Town:</label>
			<input type="text" name="city" />
		</div>
		<div class="ff_row">
			<label>What is the best time to call you?</label>
			<select name="b_time" class="req">
				<option value=""></option>
				<option value="Morning">Morning</option>
				<option value="Afternoon">Afternoon</option>
				<option value="Evening">Evening</option>
			</select>
		</div>
		<div class="ff_row" style="padding:20px 0;text-align:center;">
			<a class="sub_butt nxt" href="javascript:;">Go to Step 2</a>
		</div>
	</div>
</div>
<div class="jb_form_cont_nxt apply_box">
	<div class="j_title">STEP 2: Background & Availability</div>
	<div class="apply_box_inn" style="max-width:445px;margin:0 auto;">
		<?php /*?><div class="ff_row">
        	<label>Upload Resume</label>
            <div class="q_ans"><input type="file" name="resume" /></div>
        </div><?php */?>
	<?php
		$mergedArr = array_merge($backQus, $availQus);
		if(is_array($mergedArr) && count($mergedArr) > 0)
		{
			foreach($mergedArr as $curQus)
			{
				$req = $jobDet['quss'][$curQus['id']]['q_req'];
				echo '<div class="ff_row">
						<label>' . $curQus['qss'] . '</label>';
				$anss = explode(',', $curQus['anss']);	
				if($curQus['a_type'] == 1)
				{
					echo '<div class="q_ans';
					if($req == 0)
						echo ' reqchk';
					echo '">';
					foreach($anss as $idx => $ans)
					{
						echo '<div class="ans_chk"><span class="job_chk_ll">' . $ans . '</span> <input type="checkbox" class="job_chk" name="qus['.$curQus['id'].'][]" value="' . $ans . '" /></div>';
					}
					echo '</div>';
				}
				else
				{
					echo '<div class="q_ans"><select name="qus['.$curQus['id'].']"';
					
					if($req == 0)
						echo ' class="req"';
					
					echo '><option value="">Select</option>';
					foreach($anss as $ans)
					{
						$ans = trim($ans);
						echo '<option value="' . $ans . '">' . $ans . '</option>';
					}
					echo '</select></div>';
				}
				echo '</div>';
			}
		}
		?>
        <div class="ff_row">
			<label>Attach Resume <?php if($jobDet['q_res_req'] == 1 || trim($jobDet['q_res_best']) ==''){ echo '(Optional)';} ?></label>
			<div class="q_ans">
            	<input type="file" name="resume" />
            </div>
		</div>
		<div class="ff_row" style="padding:20px 0;">
			<input type="submit" class="sub_butt" value="Submit" />
			<input type="hidden" name="job_apply" value="yes" />
		</div>
	</div>
</div>
<div class="thnk_you">
	<h1 class="tn_mn" style="margin-top:0;">Thank You!</h1>
	<p>Your application has been submitted to<br />
	<strong><?php echo $curUser['comp_name']?></strong></p>
	<p>We appreciate your interest, but only qualified applicants will be notified.</p>
	<p><strong>Have a great day!</strong></p>
</div>
</form>
<script type="text/javascript">
function processDataRes(data)
{
	jQuery('#mask').hide();
	if(typeof(data.fine)!='undefined' && data.fine > 0)
	{
		jQuery('.jb_form_cont_nxt').hide();
		jQuery('.thnk_you').show();
	}
	else
		alert(data.error);
}
function chkCntrySt(cntry)
{
	if(cntry == 'United States')
	{
		jQuery('.usa').show();
		jQuery('.canada').hide();
	}
	else if(cntry == 'Canada')
	{
		jQuery('.usa').hide();
		jQuery('.canada').show();
	}
	else
	{
		jQuery('.usa,.canada').hide();
		jQuery('select[name=state]').val('');
	}
}

jQuery(document).ready(function(e) {
	jQuery('.sub_butt.read').click(function(e) {
		jQuery('.apply_job_cnt .sub_butt,.jb_form_cont,.jb_form_cont_nxt').hide();
		jQuery('.jb_desc_cont').show();
	});
	jQuery('.sub_butt.apply').click(function(e) {
		jQuery('.apply_job_cnt .sub_butt,.jb_desc_cont,.jb_form_cont_nxt').hide();
		jQuery('.jb_form_cont').show();
	});
	jQuery('.sub_butt.nxt').click(function(e) {
		var err = 0;
		jQuery('.jb_form_cont label, .jb_form_cont .req').removeAttr('style');
		
		jQuery('.jb_form_cont .req').each(function(index, element) {
			if(jQuery(this).val() == '')
			{
				jQuery(this).css('border-color', '#f00');
				err++;
			}
		});
		
		jQuery('.jb_form_cont .reqchk').each(function(index, element) {
			var chk = jQuery(this).find("input[type=checkbox]:checked")[0];
			if(chk.val() == '')
			{
				jQuery(this).find('label').css('color', '#f00');
				err++;
			}
		});
		
		if(err > 0)
			return false;
		jQuery('.apply_job_cnt .sub_butt,.jb_desc_cont,.jb_form_cont').hide();
		jQuery('.jb_form_cont_nxt').show();
	});
	jQuery('#qs_form').submit(function(e) {
		var err = 0;
		jQuery('.jb_form_cont_nxt label, .jb_form_cont_nxt .req').removeAttr('style');
		
		jQuery('.jb_form_cont_nxt .req').each(function(index, element) {
			if(jQuery(this).val() == '')
			{
				jQuery(this).css('border-color', '#f00');
				err++;
			}
		});
		
		/*jQuery('.jb_form_cont_nxt .reqchk').each(function(index, element) {
			var chk = jQuery(this).find("input[type=checkbox]:checked");
			if(chk.val() == '')
			{
				jQuery(this).find('label').css('color', '#f00');
				err++;
			}
		});*/
		
		if(err > 0)
			return false;
		
		jQuery('#mask').show();
		/*jQuery.ajax({
			type:jQuery(this).attr('method'),
			url:jQuery(this).attr('action'),
			dataType:'json',
			data:jQuery(this).serialize(),
			success:function(data)
			{
				
			}
		});*/
		
		//return false;
	});
	jQuery('.ff_row').click(function(e) {
		jQuery(this).children('label').removeAttr('style');
	});
	jQuery('.jb_form_cont .req,.jb_form_cont_nxt .req').focus(function(e) {
		jQuery(this).removeAttr('style');
	});
	
	jQuery('select[name=cntry]').change(function(e) {
		var cntry = jQuery(this).val();
		chkCntrySt(cntry);
	});
	
	chkCntrySt('');
});

</script>