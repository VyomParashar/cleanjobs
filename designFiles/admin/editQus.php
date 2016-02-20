<h2><?php echo $title?></h2>
<div class="formMainDiv">
	<div class="formInnerDiv">
    	<form action="<?php	echo PRJ_BROOT;?>index.php?section=admin&action=editQus" target="resultInFrame" method="post" enctype="multipart/form-data">
        	<div class="errMsg">
                <div class="errMsgShow" id="errAdminForm" style="display:none;">
                </div>
                <div class="fineMsgShow" id="fineAdminForm" style="display:none;">
                    Question have been saved successfully.
                </div>
            </div>
			<fieldset>
				<div class="akform-row">
					<label>Question:</label>
					<input type="text" name="qss" value="<?php echo $data['qss']?>" />
					<input type="hidden" id="id" name="id" value="<?php echo $data['id'];?>" />
				</div>
				<div class="akform-row">
					<label>Question Type:</label>
					<label><input type="radio" name="q_type" <?php if($data['q_type'] != 1){echo 'checked';}?> value="0" /> Background</label>
					<label><input type="radio" name="q_type" <?php if($data['q_type'] == 1){echo 'checked';}?> value="1" /> Availability</label>
				</div>
				<div class="akform-row">
					<label>Answer Type:</label>
					<label><input type="radio" name="a_type" <?php if($data['a_type'] != 1){echo 'checked';}?> value="0" /> Select Box</label>
					<label><input type="radio" name="a_type" <?php if($data['a_type'] == 1){echo 'checked';}?> value="1" /> Check Box</label>
				</div>
				<div class="akform-row">
					<label>Answers:</label>
					<textarea name="anss"><?php echo $data['anss']?></textarea>
					<div style="margin-left:185px;">Answers separated by comma(,)</div>
				</div>
			</fieldset>
		 <div class="akform-row">
			<label>&nbsp;
			</label></div>
			<div class="akform-row">
				<label>&nbsp;</label>
				<input type="hidden" name="save_form" value="yes" />
				<input type="submit" class="form-submit" value="Submit" />
		   </div>
        </form>
    </div>
</div>