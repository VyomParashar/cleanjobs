<h1 class="home_page_title">&nbsp;</h1>
<div class="ftrans login_sc form_cont">
	<form id="login_form" action="<?php echo $appObj->appUrl(array('section' => 'home', 'action' => 'login'))?>" method="post">
		<input type="hidden" name="login_form" value="yes" />
		<div class="errMsg" style="text-align:center;"></div>
		<div class="formRow"><input type="email" name="email" placeholder="Email Address" /></div>
		<div class="formRow"><input type="password" name="pwd" placeholder="Password" /></div>
		<div class="formRow submit_cont"><input type="submit" class="sub_butt" value="Sign In" /></div>
		<div class="formRow" style="text-align:center;"><a href="javascript:;" style="color:#fff;text-decoration:none;font-size:1.1em;" onclick="jQuery('#login_form').hide();jQuery('form.forgot_pwd_form').show();">Forgot Password?</a></div>
	</form>
	<form action="<?php echo $appObj->appUrl(array('section'=>'home','action'=>'submit_forgot_password'))?>" class="formToSubmitNew forgot_pwd_form" style="display:none;" method="post">
		<div class="errMsg" style="text-align:center;"></div>
		<div class="formRow"><input type="text" name="uname" placeholder="email" class="login-inp" /></div>
		<div id="message" for="fmsg" style="display:none;">
		</div>
		<div class="formRow submit_cont"><input type="submit" name="submit" class="sub_butt" value="Recover Password"  /></div>
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
	
	jQuery('.formToSubmitNew').submit(function(e) {
		jQuery('.login_sc .errMsg').hide();
		var errMsg = '';
		var curEmail = jQuery('.login_sc input[name=uname]').val();
		if(curEmail == '')
			errMsg = 'Please provide your email.';
		else if(!validateEmail(curEmail))
			errMsg = 'Please provide valid email.';
		if(errMsg != '')
		{
			jQuery('.login_sc .errMsg').html(errMsg).show();
			return false;
		}
		jQuery('.login_sc .waiting').show();
		var formObj = this;
		jQuery.ajax({
			type : 'POST',
			url : jQuery(formObj).attr('action'),
			dataType : 'json',
			data: jQuery(formObj).serialize(),
			success : function(data)
			{
				jQuery('.login_sc .waiting').hide();
				if(typeof(data.fineMsg) != 'undefined' && data.fineMsg != '')
				{
					jQuery(formObj).html('<h5 class="msg">' + data.fineMsg + '</h5>');
				}
				else
					jQuery('#message[for=fmsg]').removeClass().addClass('error').html(data.errMsg).show();
			}
		});
		return false;
	});
});
</script>