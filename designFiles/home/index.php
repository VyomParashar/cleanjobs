<p>&nbsp;</p>
<h1 class="home_page_title">Finding great cleaners is really hard.<br />
We just made it simple!</h1>
<strong class="home_sub_title">Create a job posting and you decide on your ideal applicant.<br />
You advertise the position and we'll send you the best applicants.</strong>

<div class="home_email ftrans">
	<form id="reg_email" action="<?php echo $appObj->appUrl(array('section' => 'home', 'action' => 'submitEmail'))?>" method="post">
		<div class="errMsg" style="width:480px;text-align:center;"></div>
		<input type="email" name="email" placeholder="Add your email" />
		<input type="submit" value="Create your free account" />
	</form>
	<div class="reg_email_sucess">
		<h1 class="ftrans_h1">Check your email!</h1>
		<p>We sent you and email to verify your email address.</p>
		<p>It will have instructions on setting up your new account.</p>
	</div>
	<div class="waiting">&nbsp;</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	jQuery('#reg_email').submit(function(e){
		jQuery('.home_email .errMsg').hide();
		var errMsg = '';
		var curEmail = jQuery('#reg_email input[name=email]').val();
		if(curEmail == '')
		{
			errMsg = 'Please provide your email.';
		}
		else if(!validateEmail(curEmail))
		{
			errMsg = 'Please provide valid email';
		}
		if(errMsg != '')
		{
			jQuery('.home_email .errMsg').html(errMsg).show();
			return false;
		}
		jQuery('.home_email .waiting').show();
		jQuery.ajax({
			type : jQuery(this).attr('method'),
			url : jQuery(this).attr('action'),
			dataType : 'json',
			data: jQuery(this).serialize(),
			success : function(data)
			{
				jQuery('.home_email .waiting').hide();
				if(typeof(data.fine)!='undefined' && data.fine > 0)
				{
					jQuery('.home_sub_title').css({'visibility':'hidden'});
					jQuery('#reg_email,.home_page_title').hide();
					jQuery('.reg_email_sucess').show();
				}
				else
					jQuery('.home_email .errMsg').html(data.errMsg).show();
			}
		});
		
		return false;
	});
});
</script>