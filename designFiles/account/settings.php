<style type="text/css">
.strong_title{font-size:1.1em;}
a.comp_lnk{color:#4A8AA6;font-size:1.2em;text-decoration:none;}
.apply_box_inn .ff_row label{display:inline-block;width:150px;}
</style>
<h1 class="account_h1" style="margin-bottom:15px;margin-bottom:10px;"> &nbsp; &nbsp; Account Settings</h1>
<div class="welcome_cont">
	<form enctype="multipart/form-data" target="resultInFrame" action="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'settings'))?>" method="post">
	<div id="form_err" class="t_d_err" style="margin-left: 0;"></div>
	<input type="hidden" name="settings_page" value="yes" />
	<div class="settings_cont apply_box" style="display:block;">
		<div class="apply_box_inn">
			<div class="ff_row">
				<h1 style="margin-top:0;font-size:2.2em;"><?php echo $curUser['comp_name']?></h1><?php
				$compUrl = $appObj->appUrl(array('section' => $curUser['comp']));
				?>
				<a href="<?php echo $compUrl?>" class="comp_lnk"><?php echo str_replace(array('http://', 'https://'), array('', ''), $compUrl)?></a>
			</div>
			<div class="ff_row">
				<?php
				if(trim($curUser['c_logo']) != ''){?>
				<img src="<?php echo IMAGES?>comp_logos/<?php echo $curUser['id'] . '_' . $curUser['c_logo']?>" width="200" alt="" /><?php
				}
				else{
					echo '<div class="no_logo_img">Your Logo</div>';
				}?>
				<div class="logo_img_r">
					<input type="file" name="c_logo" value="" placeholder="Upload Your Logo" />
					<p class="desc">Maximum logo dimensions are 480px wide by 360px high. The uploaded file will be resized to fit within those constraints.</p>
				</div>
			</div>
			<div class="ff_row">
				<strong class="strong_title">Account Information</strong>
			</div>
			<div class="ff_row">
				<label>First Name:</label>
				<input type="text" class="req" name="name" value="<?php echo $curUser['name']?>" />
			</div>
			<div class="ff_row">
				<label>Last Name:</label>
				<input type="text" class="req" name="last_name" value="<?php echo $curUser['last_name']?>" />
			</div>
			<div class="ff_row">
				<label>Emails:</label>
				<input type="text" class="req" name="email" value="<?php echo $curUser['email']?>" />
			</div>
			<div class="ff_row chng_pwd_lnk">
				<a href="javascript:;" class="comp_lnk" style="padding-left:170px;font-size:1.1em;" onclick="jQuery('.chng_pwd_lnk').hide();jQuery('.chng_pwd_cont').show();">Change Password</a>
			</div>
			<div class="ff_row chng_pwd_cont">
				<label>New Password:</label>
				<input type="password" class="req" name="pwd" />
			</div>
			<div class="ff_row chng_pwd_cont">
				<label>Confirm Password:</label>
				<input type="password" class="req" name="c_pwd" />
			</div>
			<p>&nbsp;</p>
			<div class="ff_row">
				<strong class="strong_title">Company Information</strong>
			</div>
			<div class="ff_row">
				<label>Business Name:</label>
				<input type="text" class="req" name="comp_name" value="<?php echo $curUser['comp_name']?>" />
			</div>
			<div class="ff_row">
				<label>City/Town:</label>
				<input type="text" class="req" name="city" value="<?php echo $curUser['city']?>" />
			</div>
			<div class="ff_row">
				<label>State/Province:</label>
				<select class="req" name="state">
					<option value="">- Select -</option><?php
					foreach($appObj->usaStates as $state)
					{
						echo '<option class="usa"';
						if($state == $curUser['state'])
							echo ' selected';
						echo '>' . $state . '</option>';
					}
					foreach($appObj->canadaStates as $state)
					{
						echo '<option class="canada"';
						if($state == $curUser['state'])
							echo ' selected';
						echo '>' . $state . '</option>';
					}
				?></select>
			</div>
			<div class="ff_row">
				<label>Country:</label>
				<select class="req" name="cntry">
				<option value="">- Select -</option><?php
				foreach($appObj->countries as $cntry)
				{
					echo '<option';
					if($cntry == $curUser['cntry'])
						echo ' selected';
					echo '>' . $cntry . '</option>';
				}
				?></select>
			</div>
			<p>&nbsp;</p>
			<div class="ff_row">
				<strong class="strong_title">Email Settings</strong>
			</div>
			<div class="ff_row">
				Cleaningjobs.co can email you when someone applies for a job you have published. How often would you like to receive this email?
			</div>
			<div class="ff_row">
				<div class="ans_chk" style="width:350px;"><input type="radio" class="job_chk" name="email_noti" <?php if($curUser['email_noti'] == 1){ echo ' checked '; }?> value="1"> <span class="job_chk_ll">When application is submitted (right away)</span></div>
				<div class="ans_chk" style="width:350px;"><input type="radio" class="job_chk" name="email_noti" <?php if($curUser['email_noti'] == 2){ echo ' checked '; }?> value="2"> <span class="job_chk_ll">Once daily (Collection of all applicants that day)</span></div>
			</div>
			<p>&nbsp;</p>
			<div class="ff_row">
				<input type="submit" class="sub_butt small_butt" value="Save Changes" />
				<?php
				if(trim($appObj->readSession('sett_msg')) != '')
				{?>
				<br /><div class="sett_msg"><?php
				echo $appObj->readSession('sett_msg');
				$appObj->writeSession('sett_msg', '');
				?></div>
				<?php	
				}?>
			</div>
		</div>
	</div>
	</form>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
</div>
<script type="text/javascript">
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
	jQuery('select[name=cntry]').change(function(e) {
		var cntry = jQuery(this).val();
		chkCntrySt(cntry);
	});
	chkCntrySt('<?php echo $curUser['cntry']?>');
});
</script>