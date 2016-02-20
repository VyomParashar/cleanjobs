<p>&nbsp;</p>
<div class="home_email form_cont ftrans"><?php
if($uData['id'] > 0){?>
	<h1 class="ftrans_h1">Confirm your details</h1>
	<form id="acc_reg" action="<?php echo $appObj->appUrl(array('section' => 'home', 'action' => 'verify', 'vars' => array('var1' => $appObj->gdata['var1'],
	'var2' => $appObj->gdata['var2'])))?>" method="post">
		<div class="errMsg" style="width:480px;text-align:center;"></div>
		<div class="formRow">
			<label class="lb">Email:</label> <?php echo $uData['email']?>
		</div>
		<div class="formRow">
			<label class="lb">Password:</label>
			<input type="password" name="pwd" placeholder="Password" />
		</div>
		<div class="formRow">
			<label class="lb">Confirm Password:</label>
			<input type="password" name="cpwd" placeholder="Confirm Password" /> <span class="msg_pwd">Password do not match</span>
		</div>
		<div class="formRow">
			<label class="lb">Company Name:</label>
			<input type="text" name="comp_name" placeholder="Company Name" />
		</div>
		<div class="formRow">
			<label class="lb">cleaningjobs.co/:</label>
			<input type="text" name="comp" placeholder="add your custom web address" /> <span class="smallProcess comp"></span> <span class="msg_web">Not Available</span>
		</div>
		<div>&nbsp;</div>
		<div class="submit_cont">
			<input type="hidden" name="acc_reg" value="yes" />
			<input type="submit" class="sub_butt" value="Create your free account" />
		</div>
	</form>
	<div class="waiting">&nbsp;</div><?php
}else{?>
<h1 class="ftrans_h1">Invalid token</h1>
<?php
}?>
</div>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	jQuery('input[name=comp]').keyup(function(e) {
		var curComp = jQuery(this).val();
		jQuery('.msg_web').hide();
		if(curComp == '')
		{
			return;
		}
			
		jQuery('.smallProcess.comp').css({'display':'inline-block'});
		jQuery.ajax({
			type : 'get',
			url : '<?php echo $appObj->appUrl(array('section' => 'home', 'action' => 'unique_comp'))?>',
			dataType : 'json',
			data: 'comp=' + curComp,
			success : function(data)
			{
				jQuery('.smallProcess.comp').hide();
				if(typeof(data.fine)!='undefined' && data.fine > 0)
					jQuery('.msg_web').addClass('chk').html('Available').show();
				else
					jQuery('.msg_web').removeClass('chk').html('Not Available').show();
			}
		});
	});
	jQuery('#acc_reg').submit(function(e){
		jQuery('.msg_pwd,.msg_web').hide();
		var errMsg = '';
		var curPwd = jQuery('input[name=pwd]').val();
		var curcPwd = jQuery('input[name=cpwd]').val();
		var curCompName = jQuery('input[name=comp_name]').val();
		var curComp = jQuery('input[name=comp]').val();
		if(curPwd == '')
			errMsg = 'Please enter password.';
		else if(curPwd != curcPwd)
			errMsg = 'Password not match.';
		if(errMsg != '')
		{
			jQuery('.msg_pwd').html(errMsg).show();
			return false;
		}
		if(jQuery('input[name=comp]').val() == '')
		{
			jQuery('.msg_web').removeClass('chk').html('Required').show();
			return false;
		}
		jQuery('.home_email .add_mask').show();
		jQuery.ajax({
			type : jQuery(this).attr('method'),
			url : jQuery(this).attr('action'),
			dataType : 'json',
			data: jQuery(this).serialize(),
			success : function(data)
			{
				jQuery('.home_email .add_mask').hide();
				if(typeof(data.fine)!='undefined' && data.fine > 0)
					window.location.href=data.trgt;
				else
					jQuery('.home_email .errMsg').html(data.errMsg).show();
			}
		});
		
		return false;
	});
});
</script>