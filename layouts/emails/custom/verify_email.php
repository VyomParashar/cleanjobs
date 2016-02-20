<?php global $email_ddata;?>
<div style="width:643px;text-align:center;">
<a href="<?php echo $email_ddata['home_url']?>"><img src="<?php echo IMAGES?>front/email_bck.png" alt="" /></a>
<div>&nbsp;</div>
<p>Your email confirmation is almost complete.<br />
Please click the link below to verify your email address to continue</p>
<p><a href="<?php echo $email_ddata['verifyLink']?>" style="background-color:#5CC560;color:#fff;padding:7px 30px;font-size:14px;border-radius:15px;">Confirm your email address</a></p>
<p>&nbsp;</p>
<p>If you prefer, you can copy this address and paste it in your browser.<br />
<?php echo $email_ddata['verifyLink']?></p>
<p>&nbsp;</p>
<hr />
<p>Powered by <a href="<?php echo $email_ddata['home_url']?>" style="background-color:#1E6C92;padding:5px 8px;text-transform:uppercase;font-size:13px;margin-left:7px;color:#fff;">Clean Simple</a></p>
<p>&nbsp;</p>
</div>
