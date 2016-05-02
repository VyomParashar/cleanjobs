<footer class="front">
	<div class="footer_inner">
		<div class="fleft">
			<?php if(is_array($GLOBALS['bodyClass']) && in_array('homePage', $GLOBALS['bodyClass'])){
				?>Powered by <a href="http://www.sweptworks.com" target="_blank"><img width="130" src="<?php echo IMAGES?>logo/power_swept_blue.png" alt="Swept" title="Swept" /></a><?php
			}else{?>Powered by <a href="http://www.sweptworks.com" target="_blank"><img src="<?php echo IMAGES?>logo/swept.png" class="foot_logo" alt="Swept" title="Swept" /></a><?php }?>
		</div>
		<div class="fright">
			<?php /*?><ul>
				<li><a href="#">About</a></li>
				<li><a href="#">Privacy</a></li>
				<li><a href="#">Legal</a></li>
			</ul><?php */?>
		</div>
	</div>
</footer>