<header id="header">
	<div class="hleft"><a href="<?php echo $appObj->appUrl(array('section'=>'admin'));?>"><img width="102" src="<?php echo PRJ_BROOT?>multimedias/images/logo/logo_trans.png" alt="Clean Simple" /></a></div>
	<div class="hright">
		<?php if($appObj->readSession('admin_type') > 0){
			echo '<h2>' . $appObj->readSession('admin_cust_name') . '</h2>';
		}?>
		<div class="righthead">
			<ul>
				<li>
					<img src="<?php echo PRJ_BROOT?>multimedias/images/icons/icon_setting_gear_white.png" width="35" alt="Settings" />
					<div class="scont">
						<a href="<?php echo $appObj->appUrl(array('section'=>'adminDet'))?>">Password</a>
						<a href="<?php echo $appObj->appUrl(array('section'=>'admin','action'=>'logout'))?>">Sign Out</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
</header>