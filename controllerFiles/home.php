<?php
class home extends main
{
	var $secArr = array('verify', 'login', 'account', 'admin', 'adminDet', 'home');
	/* To redirect to login page if not loggedin */		
	function beforeAction()
	{
		if($this->readSession('front_login')=='1')
			$this->redirect($this->appUrl(array('section'=>'account', 'action' => 'jobs')));
	}
	
	function index()
	{
		global $bodyClass;
		$bodyClass[] = 'homePage';
		$this->set('title','Cleaningjobs.co - Find Janitorial Cleaners Fast, For Free');
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
		if($userChk <= 0 && !in_array($this->pdata['comp'], $this->secArr))
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
				if(trim($this->pdata['name']) == '')
					$toRep['errMsg'] = 'Please provide first name.';
				else if(trim($this->pdata['pwd']) == '')
					$toRep['errMsg'] = 'Please provide password.';
				else if($this->pdata['pwd'] != $this->pdata['cpwd'])
					$toRep['errMsg'] = 'Password does not match.';
				else if(trim($this->pdata['comp_name']) == '')
					$toRep['errMsg'] = 'Please provide company name.';
				else
				{
					$userChk = $this->db->cntRows('users','comp="'.$this->addSlashesINP($this->pdata['comp']).'"');
					if($userChk > 0 || in_array($this->pdata['comp'], $this->secArr) || !preg_match("/^[A-Za-z0-9-]+$/", $this->pdata['comp']))
						$toRep['errMsg'] = 'Please provide unique custom web address.';
					else
					{
						$token = md5(time() . SEC_SALT . 'alter');
						$tosave = array(
							'pwd' => md5($this->pdata['pwd']),
							'comp_name' => $this->pdata['comp_name'],
							'comp' => $this->pdata['comp'],
							'token' => $token,
							'name' => $this->pdata['name'],
							'last_name' => $this->pdata['last_name'],
							'cntry' => $this->pdata['cntry'],
							'state' => $this->pdata['state'],
							'city' => $this->pdata['city']
						);
						$this->db->updateData('users', $tosave, 'id="' . $uData['id'] . '"');
						$user = $this->db->getRow($this->db->tables->users, 'where id = "' . $uData['id'] . '"');
						$this->createSession($user);
						
						$mail = $this->getMail();
						$mail->AddAddress('cjsignup@sweptworks.com');
						/*$mail->AddAddress('harpreetgsingh5003@gmail.com');
						$mail->AddAddress('mcooper@sweptworks.com');
						$mail->AddAddress('mcooper@cleansimple.ca');*/
						$mail->Subject = 'New Cleaning Jobs Sign Up';
						$mail->Body = 'Hi Admin,<br /><br />
Details of new cleaning jobs signup are as follows:<br />
Company Name: ' . $user['comp_name'] . '<br />
First Name: ' . $user['name'] . '<br />
Last Name: ' . $user['last_name'] . '<br />
Email Address: ' . $user['email'] . '<br /><br />
Country: ' . $user['cntry'] . '<br />
State/Province: ' . $user['state'] . '<br />
City: ' . $user['city'] . '<br /><br />
Regards';
		
						$mail->Send();
				
						$toRep['fine'] = 1;
						$toRep['trgt'] = $this->appUrl(array('section'=>'account', 'action'=>'welcome', 'vars' => array('var1' => 'new')));
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
					$toRep['trgt'] = $this->appUrl(array('section'=>'account', 'action'=>'welcome'));
				}
				else
					$toRep['errMsg'] = 'Wrong email or password.';
			}
			
			echo json_encode($toRep);
			exit;
		}
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
					'site_name' => 'CleanSimpleJobs',
					'name' => $user['name'] . ' ' . $user['last_name'],
					'resetLink' => $this->appUrl(array('section'=>'home','action'=>'reset-password', 'vars' => array('var1' => $user['email'], 'var2' => $token)))
				);
				/*try {*/
					$mail = $this->getMail();
					$mail->AddAddress($user['email'], $email_data['name']);
					$mail->Subject = 'CleanSimpleJobs Password Reset';
					$mail->Body = $this->get_email_content('forgot_password', $email_data);
	
					$mail->Send();
					
					$msg['fineMsg'] = 'Please check your email, we have sent you a password reset link.';
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
		echo json_encode($msg);
		exit;
	}
	
	function reset_password()
	{
		$data = array();
		$data['email'] = trim($_GET['var1']);
		$data['token'] = trim($_GET['var2']);
		$user = $this->db->getUserByTokenEmail($data['email'], $data['token']);
		if(!(is_array($user) && count($user) > 2))
			$data['error'] = 'Password reset link is not valid';
		
		$this->set('data', $data);
		$this->set('title','Reset Password');
		$this->set('layout','front');
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
			$user = $this->db->getUserByTokenEmail($this->pdata['email'], $this->pdata['token']);
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
		echo json_encode($msg);
		exit;
	}
	
	function dailyEmails()
	{
		ini_set("max_execution_time", "90000");
		ini_set('memory_limit', '500M');
		$curDate = date('Y-m-d',strtotime("-1 days"));
		if($this->gdata['var1'] == 'sendinginfosdjjienndfgkjh')
		{
			$dailyUsers = $this->db->getAllData($this->db->tables->users, 'email_noti="2"');
			if(is_array($dailyUsers) && count($dailyUsers) > 0)
			{
				
				foreach($dailyUsers as $curUsr)
				{
					$u_id = $curUsr['id'];
					$pubJobs = $this->db->getAllData($this->db->tables->jobs, 'status=1 and u_id="' . $u_id . '" order by s_date desc');
					
					$totalCnt = 0;
					$specArr = array();
					if(is_array($pubJobs) && count($pubJobs) > 0)
					{
						foreach($pubJobs as $curJob)
						{
							$appsChk = $this->db->cntRows($this->db->tables->applicants,'job_id="' . $curJob['id'] . '" and status="1" and created >= "' . $curDate . '"');
							if($appsChk > 0)
							{
								$totalCnt = $totalCnt + $appsChk;
								$specArr[$curJob['id']]['cnt'] = $appsChk;
								
								$appsTop = $this->db->getAllData($this->db->tables->applicants,'job_id="' . $curJob['id'] . '" and status="1" and created >= "' . $curDate . '" order by per_fit desc limit 0,3');
								$specArr[$curJob['id']]['apps'] = $appsTop;
							}
						}
					}
					
					$email_data = array(
						'specArr' => $specArr,
						'curUsr' => $curUsr,
						'totalCnt' => $totalCnt,
						'pubJobs' => $pubJobs
					);
					
					$mail = $this->getMail();
					$mail->AddAddress($userDet['email']);
					$mail->Subject = 'Daily Applications - CleanSimpleJobs';
					$mail->Body = $this->get_email_content('all_applicants', $email_data);
	
					$mail->Send();
				}
			}
		}
		echo 'Emails sent';
		exit;
	}
}