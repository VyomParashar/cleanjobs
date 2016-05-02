<?php global $email_ddata;?>
A request to reset your password has been made.<br />
If you did not make this request, simply ignore this email.<br /><br />
If you did make this request just click the link below:<br /><br />
<?php echo $email_ddata['resetLink']?><br /><br />
If the above link does not work try copying and pasting the following into your browser.<br /><br />
<?php echo $email_ddata['site_name']?>