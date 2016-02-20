<div id="logo-login">
		
	</div>
	<!-- end logo -->
	
	<div class="clear"></div>
	
	<!--  start loginbox ................................................................................. -->
	<div id="loginbox">
	
	<!--  start login-inner -->
	<div id="login-inner">
		
		<div id="waiting" style="display: none;">
			Please wait<br />
			<img src="<?php echo PRJ_BROOT?>multimedias/images/ajax/ajax.gif" title="Loader" alt="Loader" />
		</div><div class="clear"></div>
		<img class="logo" src="<?php echo PRJ_BROOT?>multimedias/images/logo/login_logo.png" title="Clean Simple" alt="Clean Simple" />
	<?php
	if(trim($data['error']) != '')
	{
		echo '<div id="message" class="error"><div class="loginerr">' . $data['error'] . '</div></div>';
	}else{?><form action="<?php echo $appObj->appUrl(array('section'=>'admin','action'=>'submit_reset_password'))?>" class="formToSubmit forgot_pwd_form" method="post">
		<p style="color:#fff;">Please enter your new password.</p>
		<div class="frow"><input type="password" name="password" placeholder="Password" class="login-inp" /></div>
		<div class="frow"><input type="password" name="cpassword" placeholder="Confirm Password" class="login-inp" /></div>
		<div id="message" for="fmsg" style="display: none;">
		</div>
		<input type="hidden" name="email" value="<?php echo $data['email']?>" />
		<input type="hidden" name="token" value="<?php echo $data['token']?>" />
		<div class="frow"><input type="submit" name="submit" class="submit-login" value="Reset Password"  /></div>
	</form><?php
	}?>
	</div>
 	<!--  end login-inner -->
	<div class="clear"></div>
 </div>
 <div class="login-footer">&copy; 2015 Clean Simple Inc.<br /><br />Version 1.9</div>
 <!--  end loginbox -->