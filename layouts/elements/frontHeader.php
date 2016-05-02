<header class="front">
	<div class="inner_header">
		<div class="hleft"><a href="<?php echo $appObj->appUrl();?>"><img width="190" src="<?php echo IMAGES?>front/cleaningjobs_logo.png" alt="Clean Simple" /> <?php /*?><span>cleaningjobs.co</span><?php */?></a></div>
		<div class="hright"><?php
		if($appObj->readSession('front_login')=='1'){?>
			<a href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'jobs'))?>">Jobs</a> &nbsp; &nbsp;
			<a href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'logout'))?>">Logout</a> &nbsp; &nbsp;
			<a class="sett" href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'settings'))?>"><img src="<?php echo IMAGES?>front/gear-icon.png" alt="" /></a>
		<?php
		}else{?>
			<a class="hdSign" href="<?php echo $appObj->appUrl(array('section'=>'login'))?>">Sign In</a>
		<?php
		}?>
		</div>
	</div>
</header>