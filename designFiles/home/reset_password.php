<h1 class="home_page_title">&nbsp;</h1>
<div class="ftrans login_sc form_cont">
	<?php
	if(trim($data['error']) != '')
	{
		echo '<div id="message" class="error"><div class="loginerr">' . $data['error'] . '</div></div>';
	}else{?><form id="login_form" action="<?php echo $appObj->appUrl(array('section'=>'home','action'=>'submit_reset_password'))?>" method="post">
		<input type="hidden" name="email" value="<?php echo $data['email']?>" />
		<input type="hidden" name="token" value="<?php echo $data['token']?>" />
		<div id="message" for="fmsg" style="display:none;"></div>
		<p style="color:#fff;text-align:center;font-size:1.2em;">Please enter your new password.</p>
		<div class="formRow"><input type="password" name="password" placeholder="Password" /></div>
		<div class="formRow"><input type="password" name="cpassword" placeholder="Confirm Password" /></div>
		<div class="formRow submit_cont"><input type="submit" class="sub_butt" value="Reset Password" /></div>
	</form><?php
	}?>
	<div class="waiting">&nbsp;</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	jQuery('#login_form').submit(function(e){
		jQuery('.login_sc .errMsg').hide();
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
					jQuery(formObj).html('<h3 class="msg">' + data.fineMsg + '</h3>');
				}
				else
					jQuery('#message[for=fmsg]').removeClass().addClass('error').html(data.errMsg).show();
			}
		});
		return false;
	});
});
</script>