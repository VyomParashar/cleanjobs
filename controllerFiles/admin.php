<?php
class admin extends main
{
	var $mainTable = 'users';
	
	function createUserSession($user)
	{
		$this->writeSession('admin_id',$user['id']);
		$this->writeSession('admin_name',$user['name']);
		$this->writeSession('admin_lname',$user['last_name']);
		$this->writeSession('admin_type',$user['u_type']);
		$this->writeSession('admin_email',$user['email']);
		$this->writeSession('admin_cid',$user['cust_id']);
		$this->writeSession('admin_ins_flag',$user['ins_flag']);
		$this->writeSession('admin_tzone',$user['tzone']);
		$this->writeSession('admin_login','1');
		$this->writeSession('admin_created',$user['created']);
		if($user['u_type'] > 0 && $user['cust_id'])
		{
			$customer = $this->db->getRow($this->db->tables->customers, 'where id="' . $user['cust_id'] . '"');
			$this->writeSession('admin_cust_name',$customer['name']);
		}
		if($user['crem'] == 'yes')
		{
			$c_time = time() + (300 * 24 * 3600);
			$token = md5($user['id'].$user['email'].$user['pwd'].SEC_SALT);
			setcookie('userc_id', $user['id'], $c_time, '/');
			setcookie('userc_token', $token, $c_time, '/');
		}
		else
		{
			setcookie('userc_id', '', '100', '/');
			setcookie('userc_token', '', '100', '/');
		}
		//$this->writeSession('front_login','1');
	}
	
	/* To show login page and redirect to dashbord page if admin already logged in */
	function index()
	{
		if($this->readSession('admin_login')=='1')
		{
			$this->redirect($this->appUrl(array('section'=>'admin', 'action' => 'dashboard')));
		}
		
		$this->set('title','Admin Login');
		$this->set('layout','adminLogin');
		
		if($_COOKIE['userc_id'] > 0 && trim($_COOKIE['userc_token']) != '')
		{
			$res = $this->db->getRow($this->db->tables->users, 'where id = "' . $_COOKIE['userc_id'] . '"');
			$token = md5($res['id'].$res['email'].$res['pwd'].SEC_SALT);
			if(is_array($res) && count($res) > 0 && $token == $_COOKIE['userc_token'])
			{
				$this->createUserSession($res);
				$this->redirect($this->appUrl(array('section'=>'admin', 'action' => 'dashboard')));
			}
			else
			{
				setcookie('userc_id', '', '100', '/');
				setcookie('userc_token', '', '100', '/');
			}
		}
	}
	
	/* To validate and create session for admin */
	function login()
	{
		if($this->readSession('admin_login')=='1')
		{
			$this->redirect($this->appUrl(array('section'=>'admin', 'action' => 'dashboard')));
		}
		
		if (empty($_POST['email']) || trim($_POST['email']) == '')
		{
			$msg['errMsg'] = 'Please enter email';
		}
		elseif(empty($_POST['pwd']) || trim($_POST['pwd']) == '')
		{
			$msg['errMsg'] = 'Please enter Password';
		}
		else
		{
			$res = $this->db->adminLogin(trim($_POST['email']),md5(trim($_POST['pwd'])));
			
			if(count($res)>0)
			{
				if($_POST['crem'] == 'yes')
					$res[0]['crem'] = 'yes';
				$this->createUserSession($res[0]);
				$msg['fineMsg'] = 'Successful';
			}
			else
				$msg['errMsg'] = 'Wrong username or password';
		}
		$msg['errMsg'] = '<div class="loginerr">' . $msg['errMsg'] . '<a href="javascript:;" onclick="jQuery(\'#message\').hide()">X</a></div>';
		$this->set('msg',json_encode($msg));
		
		$this->set('layout','ajax');
	}
	
	/* Forgot password feature */
	function submit_forgot_password()
	{
		$msg = array();
		if (empty($_POST['uname']) || trim($_POST['uname']) == '')
			$msg['errMsg'] = 'Please enter email address.';
		else
		{
			$user = $this->db->getUserByEmail(trim($_POST['uname']));
			if(is_array($user) && count($user) > 2)
			{
				$token = md5(time() . SEC_SALT);
				
				$this->db->updateData('users',array('token' => $token),'id="'.$user['id'].'"');
				
				$email_data = array(
					'site_name' => 'Clean Simple',
					'name' => $user['name'] . ' ' . $user['last_name'],
					'resetLink' => $this->appUrl(array('section'=>'home','action'=>'reset-password', 'vars' => array('var1' => $user['email'], 'var2' => $token)))
				);
				/*try {*/
					$mail = $this->getMail();
					$mail->AddAddress($user['email'], $email_data['name']);
					$mail->Subject = 'Clean Simple Password Reset';
					$mail->Body = $this->get_email_content('forgot_password', $email_data);
	
					$mail->Send();
					
					$msg['fineMsg'] = ' Please check you email, we have sent you password reset link.';
					$msg['intercom'] = array(
						'eventName' => 'Password Reset Request',
						'eventData' => array(
							'action_email' => $user['email']
						)
					);
				/*}catch(phpmailerException $e){
				  $msg['errMsg'] = $e->errorMessage(); //Pretty error messages from PHPMailer
				}catch (Exception $e){
				  $msg['errMsg'] = $e->getMessage(); //Boring error messages from anything else!
				}*/
			}
			else
				$msg['errMsg'] = 'This email address not found.';
		}
		$msg['errMsg'] = '<div class="loginerr">' . $msg['errMsg'] . '<a href="javascript:;" onclick="jQuery(this).closest(\'#message\').hide()">X</a></div>';
		$this->set('msg',json_encode($msg));
		$this->set('layout','ajax');
		$this->set('design','home/login');
	}
	
	function reset_password()
	{
		$data = array();
		$data['email'] = trim($_GET['var1']);
		$data['token'] = trim($_GET['var2']);
		$user = $this->db->getUserByToken($data['email'], $data['token']);
		if(!(is_array($user) && count($user) > 2))
			$data['error'] = 'Password reset link is not valid';
		
		$this->set('data', $data);
		$this->set('title','Reset Password');
		$this->set('layout','adminLogin');
	}
	
	function submit_reset_password()
	{
		if(trim($_POST['password']) == '')
			$msg['errMsg'] = 'Please enter your new password.';
		else if (trim($_POST['cpassword']) == '')
			$msg['errMsg'] = 'Please confirm your new password.';
		else if($_POST['password'] != $_POST['cpassword'])
			$msg['errMsg'] = 'Password and confirm password do not match.';
		else
		{
			$user = $this->db->getUserByToken($this->pdata['email'], $this->pdata['token']);
			if(is_array($user) && count($user) > 2)
			{
				$toSave = array(
					'pwd' => md5($this->pdata['password']),
					'token' => md5(time() . SEC_SALT)
				);
				$this->db->updateData('users',$toSave,'id="'.$user['id'].'"');
				$msg['fineMsg'] = 'Password reset successfully, you can now <a style="color: #fff;text-decoration: none;" href="' . PRJ_FROOT . '">login</a> with new password.';
				$msg['intercom'] = array(
						'eventName' => 'Successfully changed password',
						'eventData' => array(
							'action_email' => $this->pdata['email']
						)
					);
			}
			else
				$msg['errMsg'] = 'Its look like your reset link is not valid anymore.';
		}
		$msg['errMsg'] = '<div class="loginerr">' . $msg['errMsg'] . '<a href="javascript:;" onclick="jQuery(this).closest(\'#message\').hide()">X</a></div>';
		$this->set('msg',json_encode($msg));
		$this->set('layout','ajax');
		$this->set('design','home/login');
	}
	
	/* To make user logged out */
	function logout()
	{
		session_destroy();
		setcookie('userc_id', '', '100', '/');
		setcookie('userc_token', '', '100', '/');
		$this->redirect($this->appUrl(array('section'=>'admin')));
		die;
	}
	
	function dashboard()
	{
		$this->set('title','Admin Dashboard');
		
		if($this->readSession('admin_login')!='1')
			$this->redirect($this->appUrl(array('section'=>'admin')));
		else
			$this->redirect($this->appUrl(array('section' => 'admin', 'action' => 'qus')));
	}
	
	function qus()
	{
		$this->set('title','Questions');
		
		if($_GET['subJob']=='delete')
			$deleteRes = $this->db->deleteData($this->db->tables->adminQus,'id="'.$this->gdata['id'].'"');
		
		$cond = '1';
		
		$condArr = array();
		
		if(is_array($condArr) && count($condArr)>0)
			$cond = implode(' and ',$condArr);
		
		if(isset($this->pdata['gridid']) && count($this->pdata['gridid'])>0 && isset($this->pdata['button']))
		{
			if($this->pdata['button']=='Delete Selected' && is_array($this->pdata['gridid']))
			{
				foreach($this->pdata['gridid'] as $val)
				{
					$deleteRes = $this->db->deleteData($this->db->tables->adminQus,'id="'.$val.'"');
				}
			}
		}
		
		$orderBy = ''; 
		if(isset($this->paginate['col']) && $this->paginate['col']!='' && isset($this->paginate['dir']) && $this->paginate['dir']!='')
			$orderBy = ' order by '.$this->paginate['col'].' '.$this->paginate['dir'].' ';
	
		$result=$this->db->getAllData($this->db->tables->adminQus, $cond . $orderBy);
		$this->set('dataResult',$result);
		
		if($_GET['job']=='displayGrid')
		{
			$this->set('layout','ajax');
			$this->set('design','admin/gridQus');
		}
	}
	
	function editQus()
	{
		$id =(int) $this->gdata['id'];
		$this->set('title','Add Question');
		$this->set('layout','ajax');
		
		
		if($this->pdata['save_form'] == 'yes')
		{
			$errMsg = '';
			if(trim($this->pdata['anss'])=='')
				$errMsg = 'Please enter answers separated by comma(,)';
			if(trim($this->pdata['qss'])=='')
				$errMsg = 'Please enter question';
			
			if($errMsg=='')
			{
				$toSave = array(
					'qss' => $this->pdata['qss'],
					'anss' => $this->pdata['anss'],
					'a_type' => $this->pdata['a_type'],
					'q_type' => $this->pdata['q_type'],
					'created' => date('Y-m-d H:i:s')
				);
				if($this->pdata['id'] > 0)
					$editRes = $this->db->updateData($this->db->tables->adminQus, $toSave, 'id="' . $this->pdata['id'] . '"');
				else
					$editRes = $this->db->insertData($this->db->tables->adminQus, $toSave);
				?><script language="javascript1.2" type="text/javascript">
					window.parent.document.getElementById('errAdminForm').style.display='none';
					window.parent.document.getElementById('fineAdminForm').style.display='block';
				</script><?php
			}
			else
			{?><script language="javascrip t1.2" type="text/javascript">
					window.parent.document.getElementById('errAdminForm').innerHTML='<?php echo $errMsg;?>';
					window.parent.document.getElementById('errAdminForm').style.display='block';
					window.parent.showDiv('submitBut');
				</script><?php
			}
			exit;
		}
		else if($id > 0)
		{
			$data = $this->db->getRow($this->db->tables->adminQus, 'where id=' . $id);
			$this->set('title','Edit Question');
		}
		$this->set('data', $data);
	}
}