<p>&nbsp;</p>
<h1 class="home_page_title">Finding great cleaners is really hard.<br />
We just made it simple!</h1>
<strong class="home_sub_title">Create a job posting and you decide on your ideal applicant.<br />
You advertise the position and we'll send you the best applicants.</strong>
<div class="home_vid_img">
	<img src="<?php echo IMAGES?>front/video_image.jpg" alt="" onclick="vidClick(this);" />
	<script charset="ISO-8859-1" src="//fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding akvid" style="padding:56.25% 0 0 0;position:relative;display:none; background:#aaa;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><span class="wistia_embed wistia_async_po3kqujk0q popover=true popoverAnimateThumbnail=true videoFoam=true" style="display:inline-block;height:100%;width:100%">&nbsp;</span></div></div>
</div>
<div class="home_email ftrans">
	<form id="reg_email" action="<?php echo $appObj->appUrl(array('section' => 'home', 'action' => 'submitEmail'))?>" method="post">
		<div class="errMsg" style="text-align:center;"></div>
		<input type="email" name="email" placeholder="Add your email" />
		<input type="submit" value="Create your free account" />
	</form>
	<div class="reg_email_sucess">
		<h1 class="ftrans_h1">Check your email!</h1>
		<p>We sent you an email to verify your email address.</p>
		<p>It will have instructions on setting up your new account.</p>
	</div>
	<div class="waiting">&nbsp;</div>
</div>
<div class="home_boxs_cont">
	<div class="boxs right">
    	<div class="img">
        	<img src="<?php echo IMAGES?>front/step1.png" alt="Step 1" />
        </div>
        <div class="txt">
        	<h3>Step 1</h3>
            <p>Name and provide a description for your job posting as you already do.</p>
        </div>
    </div>
    <div class="boxs">
    	<div class="img">
        	<img src="<?php echo IMAGES?>front/step2.png" alt="Step 2" />
        </div>
        <div class="txt">
        	<h3>Step 2</h3>
            <p>Pick the best possible answers to each of the screening questions.</p>
        </div>
    </div>
    <div class="boxs right">
    	<div class="img">
        	<img src="<?php echo IMAGES?>front/step3.png" alt="Step 3" />
        </div>
        <div class="txt">
        	<h3>Step 3</h3>
            <p>Provide any answers you would like us to screen applicants out for.</p>
        </div>
    </div>
    <div class="boxs">
    	<div class="img">
        	<img src="<?php echo IMAGES?>front/step4.png" alt="Step 4" />
        </div>
        <div class="txt">
        	<h3>Step 4</h3>
            <p>Advertise your job's new application page where you normally do, and we'll notify and rank the best fit for all applicants so you can connect with the best cleaners fast!</p>
        </div>
    </div>
</div>
<script type="text/javascript">
function vidClick(obj)
{
	jQuery(obj).hide();
	jQuery('.home_vid_img .akvid').show();
}
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