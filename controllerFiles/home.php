<?php
class home extends main
{
	/* To redirect to login page if not loggedin */		
	function beforeAction()
	{
		if($this->readSession('front_login')=='1')
			$this->redirect($this->appUrl(array('section'=>'account', 'action' => 'jobs')));
	}
	
	function index()
	{
		$this->set('title','Clean Simple Jobs');
		$this->set('layout','front');
	}
	
	function submitEmail()
	{
		$toRep = array('fine' => 0, 'errMsg' => '');
		
		if(!$this->isValidEmail($this->pdata['email']))
			$toRep['errMsg'] = 'Please provide valid email.';
		else
		{
			$userChk = $this->db->cntRows('users','email="'.$this->addSlashesINP($this->pdata['email']).'"');
			if($userChk>0)
				$toRep['errMsg'] = 'Account with this email already Exists';
			else
			{
				$token = md5(time() . SEC_SALT);
				$tosave = array(
					'email' => $this->pdata['email'],
					'token' => $token,
					'u_type' => 3,
					'created' => date('Y-m-d H:i:s')
				);
				$this->db->insertData('users',$tosave);
				$user_id = $this->db->lastid();
				$email_data = array(
					'site_name' => 'Clean Simple',
					'user_id' => $user_id,
					'home_url' => $this->appUrl(),
					'verifyLink' => $this->appUrl(array('section'=>'verify','action'=>$user_id, 'vars' => array('var1' => $token))),
					'status' => 1
				);
				
				$mail = $this->getMail();
				$mail->AddAddress($this->pdata['email']);
				$mail->Subject = 'Verify - Clean Simple';
				$mail->Body = $this->get_email_content('verify_email', $email_data);

				$mail->Send();
				$toRep['fine'] = 1;
			}
		}
		echo json_encode($toRep);
		exit;
	}
	
	function unique_comp()
	{
		$toRep = array('fine' => 0, 'errMsg' => '');
		$userChk = $this->db->cntRows('users','comp="'.$this->addSlashesINP($this->gdata['comp']).'"');
		if($userChk <= 0)
			$toRep['fine'] = 1;
		echo json_encode($toRep);
		exit;
	}
	
	function verify()
	{
		$this->set('layout','front');
		$this->set('title','Verify Account - Clean Simple');
		$uData = $this->db->getUserByToken($this->gdata['var1'], $this->gdata['var2']);
		$this->set('uData', $uData);
		if(trim($this->pdata['acc_reg']) == 'yes')
		{
			$toRep = array('fine' => 0, 'errMsg' => '');
			if($uData['id'] > 0)
			{
				if(trim($this->pdata['pwd']) == '')
					$toRep['errMsg'] = 'Please provide password.';
				else if($this->pdata['pwd'] != $this->pdata['cpwd'])
					$toRep['errMsg'] = 'Password does not match.';
				else if(trim($this->pdata['comp_name']) == '')
					$toRep['errMsg'] = 'Please provide company name.';
				else
				{
					$userChk = $this->db->cntRows('users','comp="'.$this->addSlashesINP($this->pdata['comp']).'"');
					if($userChk > 0)
						$toRep['errMsg'] = 'Please provide unique custom web address.';
					else
					{
						$token = md5(time() . SEC_SALT . 'alter');
						$tosave = array(
							'pwd' => md5($this->pdata['pwd']),
							'comp_name' => $this->pdata['comp_name'],
							'comp' => $this->pdata['comp'],
							'token' => $token
						);
						$this->db->updateData('users', $tosave, 'id="' . $uData['id'] . '"');
						$toRep['fine'] = 1;
						$toRep['trgt'] = $this->appUrl(array('section'=>'account','action'=>'welcome'));
					}
				}
			}
			else
			{
				$toRep['errMsg'] = 'Token is not valid anymore.';
			}
			
			echo json_encode($toRep);
			exit;
		}
	}
	
	function createSession($user)
	{
		$this->writeSession('front_id',$user['id']);
		$this->writeSession('front_name',$user['name']);
		$this->writeSession('front_lname',$user['last_name']);
		$this->writeSession('front_type',$user['u_type']);
		$this->writeSession('front_email',$user['email']);
		$this->writeSession('front_login','1');
		$this->writeSession('front_created',$user['created']);
	}
	
	function login()
	{
		$this->set('title','User Login');
		$this->set('layout','front');
		
		if($this->pdata['login_form'] == 'yes')
		{
			$toRep =  array('fine' => 0, 'errMsg' => '');
			$res = $this->db->userLogin(trim($_POST['email']),md5(trim($_POST['pwd'])));
			
			if(trim($this->pdata['email']) == '')
				$toRep['errMsg'] = 'Please provide valid email.';
			if(trim($this->pdata['pwd']) == '')
				$toRep['errMsg'] = 'Please provide password.';
			else
			{
				$email = trim($_POST['email']);
				$pwd = md5(trim($_POST['pwd']));
				$res = $this->db->userLogin($email, $pwd, 3);
				if(count($res)>0)
				{
					$this->createSession($res[0]);
					$toRep['fine'] = 1;
					$toRep['trgt'] = $this->appUrl(array('section'=>'account', 'action'=>'jobs'));
				}
				else
					$toRep['errMsg'] = 'Wrong email or password.';
			}
			
			echo json_encode($toRep);
			exit;
		}
	}
}