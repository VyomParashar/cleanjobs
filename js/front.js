var maskCreateStatus = false;
var parent_add_form_var = 2;
function disableButton(butId)
{
	document.getElementById(butId).disabled=true;
}
function enableButton(butId)
{
	document.getElementById(butId).disabled=false;
}
function showMask()
{
	document.getElementById('mask').style.display='block';
}
function hideMask()
{
	document.getElementById('mask').style.display = 'none';
}
function createElement()
{
	maskCreateStatus = true;
	var maskDiv = document.createElement('div');
	maskDiv.setAttribute('id','mask');
	maskDiv.setAttribute('class','mask');
	maskDiv.innerHTML = '&nbsp;';
	document.body.appendChild(maskDiv);
	document.getElementById('mask').style.display='block';
}
function showDiv(trgt)
{
	if(trgt=='respDivContainer' || trgt=='ajaxResContainer')
		showPop(trgt);
	else
		jQuery('#' + trgt).show();
}
function showPop(trgt)
{
	var xx = $(document).scrollTop() + 75;
	jQuery('#' + trgt).css({
		top: xx + 'px',
		display: 'block'
	});
}
function validateEmail(email)
{
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function hideDiv(trgt)
{
	document.getElementById(trgt).style.display = 'none';
}
function makeLoadingDiv(trgt)
{
	document.getElementById(trgt).innerHTML="<h3 style='padding:10px;'>Loading...</h3>";
}

function gridAjaxReq(urlSend,dataToSend)
{
	$('#mask').show();
	$('#ajaxDiv').show();
	
	$.ajax({
			type : 'get',
			url : urlSend,
			dataType : 'html',
			data: dataToSend,
			success : function(data)
			{
				document.getElementById('gridContent').innerHTML='';
				$('#mask').hide();
				$('#ajaxDiv').hide();
				document.getElementById('gridContent').innerHTML=data;
			}
		});
}

function ajaxData(urlSend,dataToSend,respDiv, callFunc)
{
	showMask();
	jQuery('#ajaxDiv').show();
	
	jQuery.ajax({
			type : 'get',
			url : urlSend,
			dataType : 'html',
			data: dataToSend,
			success : function(data)
			{
				jQuery('#ajaxDiv').hide();
				jQuery('#' + respDiv).html(data);
				if(typeof(callFunc)!='undefined')
					callFunc(data);
			}
		});
}

function ajaxDataForSwf(urlSend,dataToSend,respDiv)
{
showMask();
showDiv('ajaxDiv');
$.ajax({
type : 'get',
url : urlSend,
dataType : 'html',
data: dataToSend,
success : function(data)
{
hideMask();
$('#ajaxDiv').hide();
document.getElementById(respDiv).innerHTML=data;
}
});
}
function ajaxButForm(params,butt)
{
	$('#mask').show();
	$('#ajaxDiv').show();
	$.ajax({
		type : 'POST',
		url : params.url,
		data: $("form#"+params.formid).serialize()+'&button='+butt.title,
		success : function(hhh){ hideMask(); $('#ajaxDiv').hide(); $('#ajaxDiv').hide(); $('#gridContent').html(hhh); }
	});
	return false;
}
function ajaxForm(params)
{ $('#mask').show();
	$('#ajaxDiv').show();
	$.ajax({
		type : 'POST',
		url : params.url,
		data: $("#"+params.formid).serialize(),
		success : function(hhh){ hideMask(); $('#ajaxDiv').hide(); $('#ajaxDiv').hide(); $('#gridContent').html(hhh); }
	});
	return false;
}
function selectAllCheckboxes(chk)
{
	var checked_status = chk.checked;
	$("input.allchecks").each(function()
	{
		this.checked = checked_status;
	});
	if(checked_status)
		jQuery('.group-opts').css({display:'table-cell'});
	else
		jQuery('.group-opts').css({display:'none'});
}
function str_replace (search, replace, subject, count) {
	f = [].concat(search),
	r = [].concat(replace),
	s = subject,
	ra = r instanceof Array, sa = s instanceof Array;    s = [].concat(s);
	if (count) {
		this.window[count] = 0;
	}
	 for (i=0, sl=s.length; i < sl; i++) {
		if (s[i] === '') {
			continue;
		}
		for (j=0, fl=f.length; j < fl; j++) {            temp = s[i]+'';
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
			s[i] = (temp).split(f[j]).join(repl);
			if (count && s[i] !== temp) {
				this.window[count] += (temp.length-s[i].length)/f[j].length;}        }
	}
	return sa ? s : s[0];
}
function parent_add_form_cont(frmid,trgtid)
{
	tmpCont = $('#'+frmid).html();
	
	var tmpCont = str_replace(['#no#','block','<b>1</b>'], [parent_add_form_var,'none','<b>'+parent_add_form_var+'</b>'], tmpCont);
	
	$('#'+trgtid).append(tmpCont);
	parent_add_form_var_cc = parent_add_form_var;
	parent_add_form_var += 1;
	
	$('#formAddCont'+parent_add_form_var_cc).show();
}

function moveScreenTo(trgt)
{
	jQuery('html, body').animate({
			scrollTop: (jQuery(trgt).offset().top - 25)
		}, 1000);
}
function form_pop(obj)
{
	var dhref = jQuery(obj).attr('dhref');
	showDiv('ajaxResContainer');
	ajaxData(dhref, '', 'showRep', function(){
		jQuery('.akcal').datetimepicker({
			timeFormat: "HH:mm:ss",
			dateFormat: "yy-mm-dd"
		});
		jQuery('.akcalDate').datepicker({
			dateFormat: "yy-mm-dd"
		});
	});
}

/* Below is custom code specific to this app */
function ak_ajaxUpload(url)
{
	var imageUp = 1;
	$('input[name="attachment"]').ajaxfileupload({
	  'action': url,
	  'params': {
		'curimg': imageUp
	  },
	  'onComplete': function(response) {
		console.log('custom handler for file:'+response);
		if(typeof(response.fineMsg)!='undefined' && response.fineMsg == 'done')
		{
			var tmpName = $('input[name="atch_pic"]').val();
			$('.attach_name').append('<img src="' + response.file.fullPath + '" width="200" alt="' + response.file.up_name + '" />');
			$('input[name="atch_pic"]').val(tmpName + ',' + response.file.new_name);
		}
		else
			alert(response.errMsg);
		$('.attach_name_tmp span[class="img' + response.fileName + '"').remove();
	  },
	  'onStart': function() {
		  imageUp++;
		  var fileName = ($('input[name="attachment"]').val()).split(/(\\|\/)/g).pop();
		$('.attach_name_tmp').append('<span class="img' + fileName + '">' + fileName + '</span>');
	  },
	  'onCancel': function() {
		console.log('no file selected');
	  }
	});
}

function processMsgPosted(data)
{
	jQuery('#mask, #ajaxDiv').hide();
	if(typeof(data.fineMsg) != 'undefined' && data.fineMsg != '')
	{
		jQuery('#add_msg')[0].reset();
		jQuery('input[name=atch_pic]').val('');
		jQuery('.attach_name').html('');
	}
	else
		alert(data.errMsg);
}

function tabDataOnAjax(curObj)
{
	
	var idata = jQuery(curObj).attr('data');
	var dhref = jQuery(curObj).attr('dhref');
	var curObj = jQuery('a[data=' + idata + ']');
	if(typeof dhref !== typeof undefined && dhref !== false)
	{
		jQuery('#mask, #ajaxDiv').show();
		$.ajax({
			type : 'POST',
			url : dhref,
			success : function(hhh){
				jQuery('#mask, #ajaxDiv').hide();
				jQuery('.tab_container .tabs_data_cont .tabs_data.' + idata).html(hhh);
				jQuery('.tab_container .tabs_cont a,.tab_container .tabs_data_cont .tabs_data').removeClass('selected');
				jQuery(curObj).addClass('selected');
				jQuery('.tab_container .tabs_data_cont .tabs_data.' + idata).addClass('selected');
			}
		});
	}
	else
	{
		jQuery('.tab_container .tabs_cont a,.tab_container .tabs_data_cont .tabs_data').removeClass('selected');
		jQuery(curObj).addClass('selected');
		jQuery('.tab_container .tabs_data_cont .tabs_data.' + idata).addClass('selected');
	}
}
function fullNotes(obj)
{
	jQuery(obj).hide();
	jQuery(obj).closest('.notes_grid').css('max-height','1000000px');
}

function tabInit(curObj)
{
	tabDataOnAjax(curObj);
	return false;
}
var firstAllMsgChk = 0;
function checkNewChecks(newChksUrl)
{
	$.ajax({
		url : newChksUrl,
		dataType : 'json',
		success : function(data){
			if(typeof(data.msgs.alls)!='undefined' && data.msgs.alls > 0)
			{
				var i = 1;
				jQuery('.msgs_cnt').html(data.msgs.alls).css('display','inline-block');
				for(var key in data.msgs.locations)
				{
					if(data.msgs.locations[key] > 0)
					{
						jQuery('.msg_cnt.msgid' + key).html(data.msgs.locations[key] + ' NEW ').css('display','inline-block');
						jQuery('.msg_cnt.smsgid' + key).html(data.msgs.locations[key]).css('display','inline-block');
						jQuery('.msg_cnt.msgid' + key).closest('li').insertBefore('.locs_list ul li:nth-child(' + i + ')');
						i++;
					}
				}
			}
			else
				jQuery('.msgs_cnt,.msg_cnt').hide();
			
			if(typeof(data.supps.alls)!='undefined' && data.supps.alls > 0)
			{
				var i = 1;
				jQuery('.supps_cnt').html(data.supps.alls).css('display','inline-block');
				for(var key in data.supps.locations)
				{
					if(data.supps.locations[key] > 0)
					{
						jQuery('.supp_cnt.suppid' + key).html(data.supps.locations[key] + ' NEW ').css('display','inline-block');
						jQuery('.supp_cnt.ssuppid' + key).html(data.supps.locations[key]).css('display','inline-block');
					
						jQuery('.supp_cnt.suppid' + key).closest('li').insertBefore('.locs_list ul li:nth-child(' + i + ')');
						i++;
					}
				}
			}
			else
				jQuery('.supps_cnt,.supp_cnt').hide();
			
			if(typeof(data.alerts.alls)!='undefined' && data.alerts.alls > 0)
			{
				var i = 1;
				jQuery('.alerts_cnt').html(data.alerts.alls).css('display','inline-block');
				for(var key in data.alerts.locations)
				{
					if(data.alerts.locations[key] > 0)
					{
						jQuery('.alert_cnt.alertid' + key).html(data.alerts.locations[key] + ' NEW ').css('display','inline-block');
						jQuery('.alert_cnt.salertid' + key).html(data.alerts.locations[key]).css('display','inline-block');
					
						jQuery('.alert_cnt.alertid' + key).closest('li').insertBefore('.locs_list ul li:nth-child(' + i + ')');
						i++;
					}
				}
			}
			else
				jQuery('.alerts_cnt,.alert_cnt').hide();
			
			if(jQuery('.locs_list li:first-child a') && firstAllMsgChk == 0)
			{
				slidRightMsgs(jQuery('.locs_list ul li:first-child a'));
			}
			firstAllMsgChk = 2;
			setTimeout(function(){
				checkNewChecks(newChksUrl);
			}, 6000);
		}
	});
}
function allAppKeepChecking()
{
	var rightContH = jQuery('#main').height();
	var leftSideH = jQuery('#sidebar').height();
	if(leftSideH < rightContH)
		jQuery('#sidebar').height(rightContH);
	
	setTimeout(function(){
			allAppKeepChecking();
		}, 2000)
}

function setLoclistHieght()
{
	var winH = jQuery(window).height();
	var hH = jQuery('#header').height();
	jQuery('#content .locs_list,#content .main_msgs_cont').height((winH - hH));
}

function slidRightMsgs(obj)
{
	jQuery('.main_msgs_cont').html('<h1>Loading...</h1>');
	var curTab = jQuery(obj).attr('tab');
	if(typeof(curTab) == 'undefined' || curTab != 'yes')
	{
		jQuery('.locs_list li').removeClass('selected');
		jQuery(obj).closest('li').addClass('selected');
	}
	var curHref = jQuery(obj).attr('href');
	jQuery('.locs_list,.main_msgs_cont').removeClass('full-loc');
	
	$.ajax({
		type : 'get',
		url : curHref,
		dataType : 'html',
		data: 'single_loc_call=yes',
		success : function(data)
		{
			jQuery('.main_msgs_cont').html(data);
		}
	});
	return false;
}

function singleChklist()
{
	var cnt = 0;
		
	$("input.allchecks").each(function()
	{
		if(jQuery(this).is(':checked'))
			cnt++;
	});
	if(cnt > 0)
		jQuery('.group-opts').css({display:'table-cell'});
	else
	{
		$('#allids').attr('checked', false);
		jQuery('.group-opts').css({display:'none'});
	}
}

function gridReq(urlSend,dataToSend,container_selector)
{
	jQuery('#mask').show();
	
	jQuery.ajax({
			type : 'get',
			url : urlSend,
			dataType : 'html',
			data: dataToSend,
			success : function(data)
			{
				jQuery('#mask').hide();
				if(typeof(container_selector)!='undefined' && container_selector!='')
					jQuery(container_selector).html(data);
				else
					jQuery('#gridContent').html(data);
			}
		});
}

jQuery(document).ready(function(e) {
	jQuery('.tab_container .tabs_cont a').click(function(e) {
		tabDataOnAjax(this);
	});
	jQuery('input#loc_search').keyup(function(e){
		var urlSend = jQuery(this).attr('surl');
		var sVal = jQuery(this).val();
		jQuery('#loc_mask').show();
		jQuery.ajax({
			type : 'get',
			url : urlSend,
			dataType : 'html',
			data: 'job=displayGrid&lsearch=' + sVal,
			success : function(data)
			{
				jQuery('#loc_mask').hide();
				jQuery('#gridContent').html(data);
			}
		});
	});
	window.confirm = function (message, callback, caption) {
			caption = caption || 'Confirmation'
		
			jQuery(document.createElement('div')).attr({
				title: caption,
					'class': 'dialog'
			}).html(message).dialog({
				my: "center bottom",
 at: "center top",
				dialogClass: 'fixed',
				buttons: {
					"Yes": function () {
						jQuery(this).dialog('close');
						callback();
						return true;
					},
						"No": function () {
						jQuery(this).dialog('close');
						return false;
					}
				},
				close: function () {
					jQuery(this).remove();
				},
				draggable: false,
				modal: true,
				resizable: false,
				width: 'auto'
			});
		};
	
	jQuery('.formToSubmit').submit(function(e) {
		$('#waiting').show();
		$('#message').hide(0);
		var formObj = this;
		jQuery.ajax({
			type : 'POST',
			url : jQuery(formObj).attr('action'),
			dataType : 'json',
			data: jQuery(formObj).serialize(),
			success : function(data)
			{
				$('#waiting').hide();
				if(typeof(data.fineMsg) != 'undefined' && data.fineMsg != '')
				{
					jQuery(formObj).html('<h3 class="msg">' + data.fineMsg + '</h3>');
				}
				else
					$('#message[for=fmsg]').removeClass().addClass('error').html(data.errMsg).show();
			}
		});
		return false;
	});
	
	jQuery('.locs_list li a').click(function(e) {
		slidRightMsgs(this);
		return false;
	});
	setLoclistHieght();
	allAppKeepChecking();
});