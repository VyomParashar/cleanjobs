<?php
class comp extends main
{
	var $curUser;
	
	/* To redirect to login page if not loggedin */		
	function beforeAction()
	{
		/*if($this->readSession('front_login')!='1')
			$this->redirect($this->appUrl(array('section'=>'home', 'action'=>'login')));*/
		$compUrl = $this->gdata['s_comp_code'];
		$this->curUser = $this->db->getRow($this->db->tables->users, 'where comp="'.$this->addSlashesINP($compUrl).'"');
		if($this->curUser['id'] <= 0)
			$this->redirect($this->appUrl());
		$this->set('curUser', $this->curUser);
	}
	
	function index()
	{
		$this->set('title','Job Openings - ' . $this->curUser['comp_name']);
		$pubJobs = $this->db->getAllData($this->db->tables->jobs, 'status=1 and u_id="' . $this->curUser['id'] . '" order by s_date desc');
		$this->set('pubJobs', $pubJobs);
		$this->set('layout','account');
	}
	
	function job()
	{
		$token = $this->gdata['var1'];
		$jobDet = $this->db->getRow($this->db->tables->jobs, 'where token="'.$this->addSlashesINP($token).'"');
		
		if($jobDet['id'] > 0)
		{
			$jobQus = $this->db->getAllData($this->db->tables->jobQus, 'j_id="' . $jobDet['id'] . '" order by id asc');
			if(is_array($jobQus) && count($jobQus) > 0)
			{
				foreach($jobQus as $tQus)
				{
					$jobDet['quss'][$tQus['q_id']] = $tQus;
				}
			}
		}
		else
			$this->redirect($this->appUrl());
		
		if($this->pdata['job_apply'] == 'yes')
		{
			$toRet = array(
					'error' => '',
					'fine' => 0
				);
			
			if(trim($this->pdata['f_name']) == '')
			{
				$toRet['error'] = 'Please provide your first name.';
			}
			else if(trim($this->pdata['c_email']) == '')
			{
				$toRet['error'] = 'Please provide your email address.';
			}
			else if(trim($this->pdata['b_time']) == '')
			{
				$toRet['error'] = 'Please provide time to call you.';
			}
			else
			{
				$toSave = array(
						'job_id' => $jobDet['id'],
						'f_name' => $this->pdata['f_name'],
						'l_name' => $this->pdata['l_name'],
						'email' => $this->pdata['c_email'],
						'phn' => $this->pdata['phn'],
						'cntry' => $this->pdata['cntry'],
						'state' => $this->pdata['state'],
						'city' => $this->pdata['city'],
						'b_time' => $this->pdata['b_time'],
						'status' => 1,
						'created' => date('Y-m-d H:i:s')
					);
				$this->db->insertData($this->db->tables->applicants, $toSave);
				$app_id = $this->db->lastid();
				$status = 1;
				$per_fit = 0;
				if(is_array($this->pdata['qus']) && count($this->pdata['qus']) > 0)
				{
					$totalMatch = $totalQus = 0;
					foreach($this->pdata['qus'] as $idx => $curQus)
					{
						$totalQus++;
						$anssM = $anss = $curQus;
						$bestAns = explode(',', $jobDet['quss'][$idx]['best_ans']);
						$filtAns = explode(',', $jobDet['quss'][$idx]['filt_ans']);
						if(is_array($anss) && count($anss) > 0)
							$anss = implode(',', $anss);
						else
							$anssM = array($anss);
						$matchArrBest = array_intersect($anssM, $bestAns);
						$matchArrFilt = array_intersect($anssM, $filtAns);
						if(count($matchArrFilt) > 0)
							$status = 0;
						if(count($matchArrBest) > 0)
							$totalMatch++;
						
						$toSave = array(
								'app_id' => $app_id,
								'q_id' => $idx,
								'anss' => $anss
							);
						$this->db->insertData($this->db->tables->applicantsQus, $toSave);
					}
					$per_fit = round(($totalMatch / $totalQus) * 100);
				}
				$blockChk = $this->db->cntRows($this->db->tables->blockedApplicants,'u_id = "' . $jobDet['u_id'] . '" and email="'.$this->addSlashesINP($this->pdata['c_email']).'"');
				if($blockChk > 0)
					$status = 0;
				$this->db->updateData($this->db->tables->applicants, array('per_fit' => $per_fit, 'status' => $status), 'id="' . $app_id . '"');

				$email_data = array(
					'site_name' => 'CleanSimpleJobs',
					'home_url' => $this->appUrl(),
					'job_name' => $jobDet['title'],
					'comp_name' => $this->curUser['comp_name'],
					'comp_lnk' => $this->appUrl(array('section'=>$this->curUser['comp']))
				);
				
				$mail = $this->getMail();
				$mail->AddAddress($this->pdata['c_email']);
				$mail->Subject = 'Application submitted - CleanSimpleJobs';
				$mail->Body = $this->get_email_content('application_conf', $email_data);

				$mail->Send();
				$userDet = $this->db->getRow($this->db->tables->users, 'where id="' . $jobDet['u_id'] . '"');
				if($userDet['email_noti'] <= 1 && $status == 1)
				{
					$email_data = array(
						'site_name' => 'CleanSimpleJobs',
						'home_url' => $this->appUrl(),
						'job_name' => $jobDet['title'],
						'app_name' => $this->pdata['f_name'],
						'fit' => $per_fit,
						'applk_lnk' => $this->appUrl(array('section'=>'account', 'action' => 'applicant', 'vars' => array('var1' => $app_id)))
					);
					
					$mail = $this->getMail();
					$mail->AddAddress($userDet['email']);
					$mail->Subject = 'New ' . $jobDet['title'] . ' Application - ' . ucwords($this->pdata['f_name']) . ' ' . $per_fit . ' Fit';
					$mail->Body = $this->get_email_content('applicant', $email_data);
	
					$mail->Send();
				}
				$toRet['fine'] = 1;
			}
			json_encode($toRet);
			exit;
		}
		else
		{
			$this->set('layout','front');
			
			$this->set('jobDet', $jobDet);
			$backQus = $this->db->getAllData($this->db->tables->adminQus, 'q_type=0 order by id asc');
			$this->set('backQus', $backQus);
			$availQus = $this->db->getAllData($this->db->tables->adminQus, 'q_type=1 order by id asc');
			$this->set('availQus', $availQus);
		}
	}
}