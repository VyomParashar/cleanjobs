<script type="text/javascript">
function copyToClipboard(elem, eObj) {
	jQuery(eObj).html('Copied!');
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}
</script><?php
function jobShow($curJob)
{
	global $appObj, $curUsr, $specArr;?>
<div class="welcome_cont jobs_list_s">
	<div class="jobs_list_s_inn">
		<h1 class="job_title"><?php echo $curJob['title']?> <a class="sub_butt appbut black" href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'job-post', 'vars' => array('var1' => $curJob['id'])))?>">Edit Job Details</a></h1>
		<div class="pubs_cont">
		<?php
		if($curJob['status'] == 1)
		{
			echo '<span class="pub_g"><strong>Published</strong> on ' . date('F j, Y', strtotime($curJob['s_date'])) . '</span>';
		}
		else
		{
			echo '<strong>Not Published</strong>';
		}
		echo ' <a class="publishchk status' . $curJob['status'] . '" href="' . $appObj->appUrl(array('section' => 'account', 'action' => 'status-jobs', 'vars' => array('var1' => $curJob['id']))) . '"></a>';?>
		</div><?php
		if($specArr[$curJob['id']]['cnt'] > 0)
		{
			echo '<div class="apps_jobs_cont">
			<div class="sitle">Top Applicants</div>
			';
			foreach($specArr[$curJob['id']]['apps'] as $curApp)
			{?><div class="apps_box">
					<div class="f_name"><?php echo ucwords($curApp['f_name'])?></div>
					<div class="l_name"><?php echo ucwords($curApp['l_name'])?></div>
					<div class="prog_bar_cont"><div class="prog_bar" style="width:<?php echo $curApp['per_fit']?>%"></div></div>
					<div class="app_box_bot"><span class="perc"><?php echo $curApp['per_fit']?></span><span class="xsmall">%FIT</span> <a class="sub_butt appbut grey" href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'applicant', 'vars' => array('var1' => $curApp['id'])))?>">View Applicant</a></div>
				</div><?php
			}
			echo '</div>';
		}?>
		<div class="applicants_box"><?php
		if($specArr[$curJob['id']]['cnt'] > 0)
		{
			echo 'There have been <span class="applicants_cnt_jb">' . $specArr[$curJob['id']]['cnt'] . '</span> applications submitted for this posting.';?>
			<a class="sub_butt appbut blue" style="margin-top:-5px;" href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'applicants', 'vars' => array('var1' => $curJob['id'])))?>">Show all <?php echo $specArr[$curJob['id']]['cnt']?> Applicants</a>
			<?php
		}
		else{?>
			Currently no application received for this job.<?php
		}?>
		</div>
		<div class="job_ft">
			Unique website address for this job posting is: <input id="copyInp_<?php echo $curJob['id']?>" type="text" class="txtinp" value="<?php echo $appObj->appUrl(array('section' => $curUsr['comp'], 'action' => 'job', 'vars' => array('var1' => $curJob['token'])))?>" /> <a class="sub_butt appbut grey" onclick="copyToClipboard(document.getElementById('copyInp_<?php echo $curJob['id']?>'), this);">Copy to Clipboard</a>
		</div>
	</div>
</div>
<?php
}
?>
<h1 class="account_h1" style="margin-bottom:15px;"> &nbsp; &nbsp; Jobs <a class="sub_butt appbut" style="margin-right:32px;" href="<?php echo $appObj->appUrl(array('section' => 'account', 'action' => 'job-post'))?>">Post New Job</a></h1>
<p style="font-size:14px;"> &nbsp; &nbsp; &nbsp; &nbsp; You currently have <strong class="pub_job_cnt"><?php echo (is_array($pubJobs) && count($pubJobs) > 0)?count($pubJobs):0;?></strong> published jobs that have collected <b><?php echo $totalCnt?></b> applicants.</p>
<div class="pubs_jobs_cont"><?php
if(is_array($pubJobs) && count($pubJobs) > 0)
{
	foreach($pubJobs as $curJob)
	{
		jobShow($curJob);
	}
}
else
{
	echo '<h4> &nbsp; &nbsp; &nbsp; &nbsp; No published jobs found.</h4>';
}
?></div>
<?php
if(is_array($unpubJobs) && count($unpubJobs) > 0)
{?><div class="unpubs_jobs_cont">
	<div class="unpub_tt"><span>Jobs below this line are not published</span></div><?php
	foreach($unpubJobs as $curJob)
	{
		jobShow($curJob);
	}
?></div><?php
}
?>