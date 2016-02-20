<article class="module width_full">
	<div class="filter-box"><h1>Change your account password</h1></div>
	<div>&nbsp;</div>
	<div class="tab_container">
<div class="formMainDiv">
	<div class="formInnerDiv">
    	<form action="<?php echo PRJ_BROOT;?>index.php?section=adminDet&action=changeDet" target="resultInFrame" method="post">
        	<div class="errMsg">
                <div class="errMsgShow" id="errAdminForm" style="display:none;">
                </div>
                <div class="fineMsgShow" id="fineAdminForm" style="display:none;">
                    Password is changed successfully.
                </div>
			</div>
			<div class="akform-row">
			 	<label>Current Password</label>
				<input type="password" id="name" name="pwd" />
			</div>
			<div class="akform-row">
			 	<label>New Password</label>
				<input type="password" id="name" name="npwd" />
			</div>
			<div class="akform-row">
			 	<label>Confirm New Password</label>
				<input type="password" id="name" name="cpwd" />
			</div>
           <div class="akform-row">
			 	    <input type="submit" id="submitBut" name="submitBut" value="Update" />
                </div>
        </form>
    </div>
</div></div></article>