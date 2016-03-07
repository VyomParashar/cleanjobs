<h1 class="home_page_title">&nbsp;</h1>
<div class="ftrans login_sc form_cont">
	<form id="login_form" action="<?php echo $appObj->appUrl(array('section' => 'home', 'action' => 'login'))?>" method="post">
		<input type="hidden" name="login_form" value="yes" />
		<div class="errMsg" style="text-align:center;"></div>
		<div class="formRow"><input type="email" name="email" placeholder="Email Address" /></div>
		<div class="formRow"><input type="password" name="pwd" placeholder="Password" /></div>
		<div class="formRow submit_cont"><input type="submit" class="sub_butt" value="Sign In" /></div>
	</form>
	<div class="waiting">&nbsp;</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	jQuery('#login_form').submit(function(e){
		jQuery('.login_sc .errMsg').hide();
		var errMsg = '';
		var curEmail = jQuery('.login_sc input[name=email]').val();
		var curPwd = jQuery('.login_sc input[name=pwd]').val();
		if(curEmail == '')
			errMsg = 'Please provide your email.';
		else if(!validateEmail(curEmail))
			errMsg = 'Please provide valid email.';
		if(curPwd == '')
			errMsg = 'Please provide your password.';
		if(errMsg != '')
		{
			jQuery('.login_sc .errMsg').html(errMsg).show();
			return false;
		}
		jQuery('.login_sc .waiting').show();
		jQuery.ajax({
			type:jQuery(this).attr('method'),
			url:jQuery(this).attr('action'),
			dataType:'json',
			data:jQuery(this).serialize(),
			success:function(data)
			{
				jQuery('.login_sc .waiting').hide();
				if(typeof(data.fine)!='undefined' && data.fine > 0)
					window.location.href=data.trgt;
				else
					jQuery('.login_sc .errMsg').html(data.errMsg).show();
			}
		});
		
		return false;
	});
});
</script>