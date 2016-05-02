<?php global $email_ddata;?>
<div style="width:643px;text-align:center;">
<a href="<?php echo $email_ddata['home_url']?>"><img src="<?php echo IMAGES?>front/email_conf_bck.png" alt="" /></a>
<div>&nbsp;</div>
<h1>Thank You!</h1>
<p>Your application for <strong><?php echo $email_ddata['job_name']?></strong> has been submitted to<br />
<strong><?php echo $email_ddata['comp_name']?></strong></p>
<p>We appreciate your application, but only qualified applicants will be notified.</p>
<p><a href="<?php echo $email_ddata['comp_lnk']?>" style="color:#1D6B91;">View other job postings from <?php echo $email_ddata['comp_name']?></a></p>
<p>Have a great day!</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>