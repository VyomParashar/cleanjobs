<form method="post" action="<?php echo $appObj->appUrl(array('section'=>'admin','action'=>'qus'))?>" id="gridmain" name="gridmain" onsubmit="return false;">
<table border="0" width="100%" class="tablesorter" cellpadding="0" cellspacing="0" id="content-table" style="margin-top:0;">
		<thead>
			<tr>
				<th class="actions-box" colspan="9">
					<a href="javascript:void(0);" title="Delete Selected" onclick="if(confirm('Are you sure to delete selected questions?')){ ajaxButForm({'formid':'gridmain','url':'<?php echo $appObj->appUrl(array('section'=>'admin','action'=>'qus','vars'=>array('job'=>'displayGrid')),true)?>','type':'html'},this);}">Delete Selected</a>
				</th>
			</tr>
			<tr>
				<th style="width:10px;">
					<input type="checkbox" id="allids" name="allids" onclick="selectAllCheckboxes(this);" />
				</th>
				<th>
					<?php echo $appObj->ajaxSort('ID','id');?>
				</th>
				<th>
					<?php echo $appObj->ajaxSort('Question','qss');?>
				</th>
				<th>
					<?php echo $appObj->ajaxSort('Question Type','q_type');?>
				</th>
				<th>
					<?php echo $appObj->ajaxSort('Answer Type','a_type');?>
				</th>
				<?php /*?><th>
					<?php echo $appObj->ajaxSort('Status','status');?>
				</th><?php */?>
				<th style="text-align:right;">
					<a href="javascript:void(0);">Operations</a>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$k=0;
			foreach($dataResult as $curData)
			{?>
					<tr class="row<?php echo $k?>">
						<td>
							<input type="checkbox" class="allchecks" id="check<?php echo $curData['id']?>" name="gridid[]" value="<?php echo $curData['id']?>" />
						</td>
						<td>
							<?php echo $curData['id']?>
						</td>
						<td>
							<?php echo $curData['qss'];?>
						</td>
						<td>
							<?php echo ($curData['q_type']==1)?'Availability':'Background';?>
						</td>
						<td>
							<?php echo ($curData['a_type']==1)?'Check Box':'Select Box';?>
						</td>
						<td class="options-width">
							<a href="javascript:void(0);" title="Edit Question" onClick="tabInit(this);" data="new_entry" dhref="<?php echo PRJ_BROOT;?>index.php?section=admin&action=editQus&var1=<?php echo $_GET['var1']?>&id=<?php echo $curData['id']?>" class="ee">Edit</a>
							<a href="javascript:void(0);" title="Delete Question" onClick="if(confirm('Are you sure to delete this question? It will delete all related information.')){gridReq('<?php echo PRJ_BROOT;?>index.php?section=admin&action=qus','job=displayGrid&subJob=delete&id=<?php echo $curData['id']?>');}" class="dd">Delete</a>
						</td>
					</tr><?php
					$k=1-$k;
					}
					if(count($dataResult)==0)
					{	?>
					<tr>
						<td colspan="6">
							<b>No Rows Found</b>
						</td>
					</tr>
			<?php	}	?>
		</tbody>
</table>
	</form>
<footer>
	<div class="submit_link">
		 <?php echo $pagination;?>
	</div>
</footer>