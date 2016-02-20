<article class="module width_full">
	<div class="filter-box"><h1>Settings</h1></div>
	<div>&nbsp;</div>
	<div class="tab_container">
<div class="formMainDiv">
	<div class="formInnerDiv">
    	<form action="<?php echo PRJ_BROOT;?>index.php?section=adminDet&action=settings" target="resultInFrame" method="post">
        	<div class="errMsg">
                <div class="errMsgShow" id="errAdminForm" style="display:none;">
                </div>
                <div class="fineMsgShow" id="fineAdminForm" style="display:none;">
                    Settings updated successfully.
                </div>
			</div>
			<div class="akform-row">
				<label>Timezone:</label>
				<select name="tzone"><?php
				foreach($appObj->timeZones as $curZone)
				{
					echo '<option ';
						if($data['tzone']==$curZone)
							echo 'selected="selected"';
						else if(trim($data['tzone']) == '' && $curZone == DEFAULT_TIMEZONE)
							echo 'selected="selected"';
						echo ' value="' . $curZone . '">' . $curZone . '</option>';
				}
				?></select>
			</div>
			<div class="akform-row">
			 	<label>Support Phone Number:</label>
				<input type="text" name="phn" value="<?php echo $data['settings']['phn']?>" />
			</div>
			<div class="akform-row">
			 	<label>Support Email:</label>
				<input type="text" name="email" value="<?php echo $data['settings']['email']?>" />
			</div>
           <div class="akform-row">
		   <input type="hidden" name="settings_new" value="yes" />
			 	    <input type="submit" value="Update" />
                </div>
        </form>
    </div>
</div></div></article>