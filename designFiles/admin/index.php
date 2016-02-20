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
	<form action="<?php echo $appObj->appUrl(array('section'=>'admin','action'=>'login'))?>" id="adminLogin" method="post">
		<div class="frow"><input type="text" name="email" placeholder="username" class="login-inp" /></div>
		<div class="frow"><input type="password" name="pwd" placeholder="password" class="login-inp" /></div>
		<?php /*?><div class="frow"><label style="color:#fff;cursor:pointer;"><input type="checkbox" name="crem" value="yes" /> Keep me logged in</label></div><?php */?>		<div id="message" for="login-msg" style="display: none;">
		</div>
		<div class="frow"><input type="submit" name="submit" id="submit" class="submit-login" value="Login"  /></div>
		<?php /*?><div class="frow"><a href="javascript:;" style="color:#fff;text-decoration:none;" onclick="jQuery('#adminLogin').hide();jQuery('form.forgot_pwd_form').show();">Forgot Password?</a></div><?php */?>
	</form>
	<form action="<?php echo $appObj->appUrl(array('section'=>'admin','action'=>'submit_forgot_password'))?>" class="formToSubmit forgot_pwd_form" style="display:none;" method="post">
		<div class="frow"><input type="text" name="uname" placeholder="email" class="login-inp" /></div>
		<div id="message" for="fmsg" style="display:none;">
		</div>
		<div class="frow"><input type="submit" name="submit" class="submit-login" value="Recover Password"  /></div>
	</form>
	</div>
 	<!--  end login-inner -->
	<div class="clear"></div>
 </div>
 <div class="login-footer">&copy; 2016 Clean Simple Inc.<br /><br />Version 1.9.1</div>
 <!--  end loginbox -->