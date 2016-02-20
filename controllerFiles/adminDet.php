<?php
class adminDet extends main
{

	/* To redirect to login page if not loggedin */		
	function beforeAction()
	{
		if($this->readSession('admin_login')!='1')
		{
			$this->redirect($this->appUrl(array('section'=>'admin', 'action' => 'dashboard')));
		}
	}
	
	/* To get current accounts details of admin */
	function index()
	{
		$this->set('title','Change Your Password');
		
		$this->set('adminDet', $this->db->getRow('users',"where id=".$this->readSession('admin_id')));
	}
	
	/* To submit and update login details */
	function changeDet()
	{
		$errMsg = array();
		
		if($_POST['pwd']=='')
		{
			$errMsg[] = 'Please Enter your current password';
		}			
		if($_POST['npwd']=='')
		{
			$errMsg[] = 'Please Enter new password';
			
		}
		if($_POST['cpwd']=='')
		{
			$errMsg[] = 'Please Confirm password';
			
		}
		elseif($_POST['npwd']!=$_POST['cpwd'])
		{
			$errMsg[] = 'New password and confirm password does not match';
		}
		else
		{
			$curPwd = $this->db->getValue('users','pwd',"where id=".$this->readSession('admin_id'));
			
			if($curPwd!=md5($_POST['pwd']))
			{
				$errMsg[] = 'You entered wrong current password';
			}
		}
		if(count($errMsg)==0)
		{		
			$chgRes = $this->db->updatePwd($_POST['npwd'],$this->readSession('admin_id'));
			?><script language="javascript1.2" type="text/javascript">
				window.parent.document.getElementById('errAdminForm').style.display='none';
				window.parent.document.getElementById('fineAdminForm').style.display='block';
				setTimeout("window.parent.location='<?php echo $this->appUrl(array('section'=>'admin', 'action' => 'dashboard'));?>'",1000);
			</script><?php
		}
		else
		{?><script language="javascript1.2" type="text/javascript">
				window.parent.document.getElementById('errAdminForm').innerHTML='<?php echo $errMsg[0];?>';
				window.parent.document.getElementById('errAdminForm').style.display='block';
			</script><?php
		}
		die;
	}
	
	function settings()
	{
		$this->set('title','Settings');
		$cust_id = $this->readSession('admin_cid');
		$uId = $this->readSession('admin_id');
		if($cust_id <= 0)
			$this->redirect($this->appUrl(array('section'=>'admin', 'action' => 'dashboard')));
		if($this->pdata['settings_new'] == 'yes')
		{
			$tzone = $this->pdata['tzone'];
			unset($this->pdata['settings_new']);
			unset($this->pdata['tzone']);
			$chgRes = $this->db->updateData($this->db->tables->customers, $this->pdata, 'id="' . $cust_id . '"');
			$this->db->updateData($this->db->tables->users, array('tzone' => $tzone), 'id="' . $uId . '"');
			$this->writeSession('admin_tzone', $tzone);
			?><script language="javascript1.2" type="text/javascript">
				window.parent.document.getElementById('errAdminForm').style.display='none';
				window.parent.document.getElementById('fineAdminForm').style.display='block';
				setTimeout("window.parent.location='<?php echo PRJ_BROOT;?>'",1000);
			</script><?php
			exit;
		}
		$data['settings'] = $this->db->getRow($this->db->tables->customers,'where id="' . $cust_id . '"');
		$data['tzone'] = $this->readSession('admin_tzone');
		
		$this->set('data', $data);
	}
}