<?php
class account extends main
{
	/* To redirect to login page if not loggedin */		
	function beforeAction()
	{
		if($this->readSession('front_login')!='1')
			$this->redirect($this->appUrl(array('section'=>'home', 'action'=>'login')));
		$this->set('layout','account');
	}
	
	function index()
	{
		$this->set('title','Cleaningjobs.co - Find Janitorial Cleaners Fast, For Free');
		$this->set('layout','front');
	}
	
	function welcome()
	{
		$this->set('title','Welcome to Clean Simple Jobs');
		
	}
	function jobs()
	{
		$u_id = $this->readSession('front_id');
		$this->set('title','My Jobs');
		$pubJobs = $this->db->getAllData($this->db->tables->jobs, 'status=1 and u_id="' . $u_id . '" order by s_date desc');
		$this->set('pubJobs', $pubJobs);
		$unpubJobs = $this->db->getAllData($this->db->tables->jobs, 'status=0 and u_id="' . $u_id . '" order by created desc');
		$this->set('unpubJobs', $unpubJobs);
		
		$allJobs = array_merge($pubJobs, $unpubJobs);
		$totalCnt = 0;
		$specArr = array();
		if(is_array($allJobs) && count($allJobs) > 0)
		{
			foreach($allJobs as $curJob)
			{
				$appsChk = $this->db->cntRows($this->db->tables->applicants,'job_id="' . $curJob['id'] . '" and status="1"');
				$totalCnt = $totalCnt + $appsChk;
				$specArr[$curJob['id']]['cnt'] = $appsChk;
				
				$appsTop = $this->db->getAllData($this->db->tables->applicants,'job_id="' . $curJob['id'] . '" and status="1" order by per_fit desc limit 0,3');
				$specArr[$curJob['id']]['apps'] = $appsTop;
			}
		}
		
		$curUsr = $this->db->getRow($this->db->tables->users, 'where id = "' . $this->readSession('front_id') . '"');
		$this->set('curUsr', $curUsr);
		$this->set('totalCnt', $totalCnt);
		$this->set('specArr', $specArr);
	}
	
	function status_jobs()
	{
		$jobId = $this->gdata['var1'];
		$u_id = $this->readSession('front_id');
		$this->db->executeQuery('update ' . $this->db->tables->jobs . ' set status = (1 - status), s_date="' . date('Y-m-d H:i:s') . '" where id="' . $jobId . '" and u_id="' . $u_id . '"');
		
		$redirectUrl = $this->appUrl(array('section' => 'account', 'action' => 'jobs'));
		if($this->gdata['var2'] == 'job')
			$redirectUrl = $this->appUrl(array('section' => 'account', 'action' => 'job-post', 'vars' => array('var1' => $jobId)));
		$this->redirect($redirectUrl);
	}
	
	function delete_jobs()
	{
		$jobId = $this->gdata['var1'];
		$u_id = $this->readSession('front_id');
		$deleteRes = $this->db->deleteData($this->db->tables->jobs,'id="'.$jobId.'" and u_id="' . $u_id . '"');
		if($deleteRes)
			$deleteRes = $this->db->deleteData($this->db->tables->jobQus,'j_id="'.$jobId.'"');
		$this->redirect($this->appUrl(array('section'=>'account', 'action'=>'jobs')));
	}
	
	function copy_jobs()
	{
		$jobId = $this->gdata['var1'];
		$u_id = $this->readSession('front_id');
		
		$this->db->executeQuery('INSERT INTO ' . $this->db->tables->jobs . 
						' (u_id, title, j_desc, status, s_date, token, created) SELECT "' . $u_id . '", CONCAT("Copy of ",title), j_desc, status, "' . 
						date('Y-m-d H:i:s') . '", "' . md5(time() . SEC_SALT . $jobId) . '", "' . date('Y-m-d H:i:s') . '" FROM ' . $this->db->tables->jobs . ' WHERE id="' . $jobId . '"');
		$new_job_id = $this->db->lastid();
		$this->db->executeQuery('INSERT INTO ' . $this->db->tables->jobQus . 
						' (j_id, q_id, q_req, best_ans, filt_ans) SELECT "' . $new_job_id . '", q_id, q_req, best_ans, filt_ans FROM ' . $this->db->tables->jobQus . ' WHERE j_id="' . $jobId . '"');
		$redirectUrl = $this->appUrl(array('section'=>'account', 'action'=>'job-post', 'vars' => array('var1' => $new_job_id)));
		$this->redirect($redirectUrl);
	}
	
	function job_post()
	{
		$this->set('title','Post Job');
		
		$backQus = $this->db->getAllData($this->db->tables->adminQus, 'q_type=0 order by id asc');
		$this->set('backQus', $backQus);
		$availQus = $this->db->getAllData($this->db->tables->adminQus, 'q_type=1 order by id asc');
		$this->set('availQus', $availQus);
		if($this->gdata['var1'] > 0)
		{
			$jobId = $this->gdata['var1'];
			$u_id = $this->readSession('front_id');
			$jobDet = $this->db->getRow($this->db->tables->jobs, 'where id="' . $jobId . '" and u_id="' . $u_id . '"');
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
			$this->set('jobDet', $jobDet);
		}
	}
	
	function post_job()
	{
		$status = 1;
		if($this->pdata['submit'] == 'SAVE only')
			$status = 0;
		$toSave = array(
				'title' => $this->pdata['title'],
				'j_desc' => $this->pdata['desc'],
				'q_res_req' => $this->pdata['q_res_req'],
				'q_res_best' => $this->pdata['q_res_best'],
				'q_res_screen' => $this->pdata['q_res_screen'],
				'u_id' => $this->readSession('front_id')
			);
		if($this->pdata['job_id'] > 0)
		{
			$this->db->updateData($this->db->tables->jobs, $toSave, 'id="' . $this->pdata['job_id'] . '"');
			$job_id = $this->pdata['job_id'];
		}
		else
		{
			$toSave['token'] = md5(time() . SEC_SALT . $this->pdata['title']);
			$toSave['created'] = date('Y-m-d H:i:s');
			$toSave['status'] = $status;
			$toSave['s_date'] = date('Y-m-d H:i:s');
			$this->db->insertData($this->db->tables->jobs, $toSave);
			$job_id = $this->db->lastid();
		}
		
		if(is_array($this->pdata['quss']) && count($this->pdata['quss']) > 0)
		{
			foreach($this->pdata['quss'] as $cur)
			{
				$best_ans = $cur['best_ans'];
				$screen_out = $cur['screen_out'];
				if(is_array($cur['best_ans']) && count($cur['best_ans']) > 0)
					$best_ans = implode(',', $best_ans);
				if(is_array($cur['screen_out']) && count($cur['screen_out']) > 0)
					$screen_out = implode(',', $screen_out);
				
				$toSave = array(
					'j_id' => $job_id,
					'q_id' => $cur['q_id'],
					'q_req' => $cur['req'],
					'best_ans' => $best_ans,
					'filt_ans' => $screen_out
				);
				if($this->pdata['job_id'] > 0)
				{
					$this->db->updateData($this->db->tables->jobQus, $toSave, 'j_id="' . $job_id . '" and q_id="' . $cur['q_id'] . '"');
				}
				else
				{
					$this->db->insertData($this->db->tables->jobQus, $toSave);
				}
			}
		}
		
		if($this->pdata['job_id'] > 0)
			$this->resetJobsApplicants($this->pdata['job_id']);
		//$toRet = $this->appUrl(array('section'=>'account', 'action'=>'jobs'));
		$toRet = $this->appUrl(array('section'=>'account', 'action'=>'posted-job', 'vars' => array('var1' => $job_id)));
		if($this->pdata['job_id'] > 0)
			$toRet = $this->appUrl(array('section'=>'account', 'action'=>'job-post', 'vars' => array('var1' => $this->pdata['job_id'])));
		$this->redirect($toRet);
	}
	
	function posted_job()
	{
		$this->set('title','Job Posted');
		$u_id = $this->readSession('front_id');
		$job_id = $this->gdata['var1'];
		$blockChk = $this->db->cntRows($this->db->tables->applicants, 'email not in (select email from ' . $this->db->tables->blockedApplicants .
		' where u_id="' . $u_id . '")');
		$allJobQus = $this->db->getAllData($this->db->tables->jobQus, 'j_id="' . $job_id . '"');
		$wCond = array();
		if(is_array($allJobQus) && count($allJobQus) > 0)
		{
			foreach($allJobQus as $curJobQus)
			{
				if(trim($curJobQus['best_ans']) != '')
				{
					$wCond[] = '(q_id)';
				}
			}
		}
		$finalQry = 'app_id in (' . $u_id . ')';
	}
	
	function applicants()
	{
		$this->set('title', 'Applicants');
		$jobId = $this->addSlashesINP($this->gdata['var1']);
		$jobDet = $this->db->getRow($this->db->tables->jobs, 'where id="'.$jobId.'"');
		if($jobDet['id'] <= 0)
			$this->redirect($this->appUrl());
		$orderBy = '';
		if(isset($this->paginate['col']) && $this->paginate['col']!='' && isset($this->paginate['dir']) && $this->paginate['dir']!='')
			$orderBy = ' order by '.$this->paginate['col'].' '.$this->paginate['dir'].' ';
		else
			$orderBy = ' order by per_fit desc';
	
		$applicants = $this->db->getAllData($this->db->tables->applicants, 'job_id="' . $jobDet['id'] . '" and status = "1"' . $orderBy);
		$this->set('applicants', $applicants);
		
		$this->set('jobDet', $jobDet);
		if($this->gdata['job']=='displayGrid')
		{
			$this->set('layout','ajax');
			$this->set('design','account/applicants_grid');
			
		}
	}
	
	function del_apps()
	{
		$app_id =(int) $this->gdata['var1'];
		$u_id = $this->readSession('front_id');
		$jobDet = $this->db->getRow($this->db->tables->applicants . ' as app join ' . $this->db->tables->jobs . ' as j on j.id=app.job_id', 'where app.id="'.$app_id.'"', 'j.*');
		if($u_id == $jobDet['u_id'])
			$this->db->updateData($this->db->tables->applicants, array('status' => 2), 'id="' . $app_id . '"');
		exit;
	}
	
	function block_app()
	{
		$app_id =(int) $this->gdata['var1'];
		$u_id = $this->readSession('front_id');
		$jobDet = $this->db->getRow($this->db->tables->applicants . ' as app join ' . $this->db->tables->jobs . ' as j on j.id=app.job_id', 'where app.id="'.$app_id.'"', 'j.u_id,app.email');
		if($u_id == $jobDet['u_id'])
		{
			$blockChk = $this->db->cntRows($this->db->tables->blockedApplicants,'u_id="' . $u_id . '" and email="'.$jobDet['email'].'"');
			if($blockChk <= 0)
				$this->db->insertData($this->db->tables->blockedApplicants, array('u_id' => $u_id, 'email' => $jobDet['email']), 'id="' . $app_id . '"');
		}
		exit;
	}
	
	function applicant()
	{
		$app_id =(int) $this->gdata['var1'];
		$jobDet = $this->db->getRow($this->db->tables->applicants . ' as app join ' . $this->db->tables->jobs . ' as j on j.id=app.job_id', 'where app.id="'.$app_id.'"', 'j.u_id, j.title, app.*');
		$jobQus = $this->db->getAllData($this->db->tables->jobQus . ' as j join ' . $this->db->tables->adminQus . ' as aq on aq.id=j.q_id', 'j.j_id="' . $jobDet['job_id'] . '" order by id asc', 'j.*,aq.qss');
		$jobQusArr = array();
		if(is_array($jobQus) && count($jobQus) > 0)
		{
			foreach($jobQus as $tQus)
			{
				$jobQusArr[$tQus['q_id']] = $tQus;
			}
		}
		
		$jobQus = $this->db->getAllData($this->db->tables->applicantsQus, 'app_id="' . $app_id . '"');
		$this->set('jobDet', $jobDet);
		$this->set('jobQusArr', $jobQusArr);
		$this->set('jobQus', $jobQus);
		
		$this->set('title', 'Application Details');
	}
	
	function settings()
	{
		$this->set('title', 'Account Settings');
		
		$u_id = $this->readSession('front_id');
		if($this->pdata['settings_page'] == 'yes')
		{
			$imgFile = $errMsg = '';
			if(trim($this->pdata['name'])=='')
				$errMsg = 'Please enter your first name';
			else if(trim($this->pdata['email'])=='')
				$errMsg = 'Please enter email address';
			else if(!$this->isValidEmail($this->pdata['email']))
				$errMsg = 'Please enter valid email address';
			else if(trim($this->pdata['comp_name'])=='')
				$errMsg = 'Please enter your business name';
			else if(is_uploaded_file($_FILES['c_logo']['tmp_name']))
			{
				$allowedExtensions = array('png','jpg','gif','jpeg','bmp');
				if(in_array(strtolower(end(explode(".", $_FILES['c_logo']['name']))), $allowedExtensions))
				{
					if($_FILES['c_logo']['size'] <= 2097152)
					{
						$imgFile = 'img uploaded';
					}
					else
					{
						$errMsg = 'Logo size must be less than 2MB.';
					}
				}
				else
				{
					$errMsg = 'Only png,jpg,gif,bmp extensions are allowed for Logo.';
				}
			}
			else if($this->pdata['pwd'] != $this->pdata['c_pwd'])
				$errMsg = 'Password and confirm password do not match.';
			
			if($errMsg=='')
			{
				$toSave = array(
					'name' => $this->pdata['name'],
					'last_name' => $this->pdata['last_name'],
					'email' => $this->pdata['email'],
					'comp_name' => $this->pdata['comp_name'],
					'city' => $this->pdata['city'],
					'state' => $this->pdata['state'],
					'cntry' => $this->pdata['cntry'],
					'email_noti' => $this->pdata['email_noti']
				);
				$msg = 'Settings updated';
				if(trim($this->pdata['pwd']) != '')
				{
					$toSave['pwd'] = md5($this->pdata['pwd']);
					$msg = 'Settings and password updated';
				}
				
				if($imgFile == 'img uploaded')
				{
					$toSave['c_logo'] = $_FILES['c_logo']['name'];
					$fileName = $u_id.'_'.$_FILES['c_logo']['name'];
					
					$fileThumbMoved = $this->resizeImage($_FILES["c_logo"]["tmp_name"], WEB_ROOT.'multimedias/images/comp_logos/'.$fileName, 480, 360);
				}
				$editRes = $this->db->updateData($this->db->tables->users, $toSave, 'id="' . $u_id . '"');
				$this->writeSession('sett_msg', $msg);
				?><script type="text/javascript">
					window.parent.document.getElementById('form_err').style.display='none';
					window.parent.location.reload();
				</script><?php
			}
			else
			{?><script type="text/javascript">
					window.parent.document.getElementById('form_err').innerHTML='<?php echo $errMsg;?>';
					window.parent.document.getElementById('form_err').style.display='block';
				</script><?php
			}
			exit;
		}
		else
		{
			$curUser = $this->db->getRow($this->db->tables->users, 'where id="' . $u_id . '"');
			$this->set('curUser', $curUser);
		}
	}
	
	function resetJobsApplicants($job_id)
	{
		$jobDet = $this->db->getRow($this->db->tables->jobs, 'where id="'.$job_id.'"');
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
			$getApps = $this->db->getAllData($this->db->tables->applicants, 'job_id="' . $jobDet['id'] . '"');
			if(is_array($getApps) && count($getApps) > 0)
			{
				foreach($getApps as $curApp)
				{
					$appQuss = $this->db->getAllData($this->db->tables->applicantsQus, 'app_id="' . $curApp['id'] . '"');
					$status = 1;
					$totalQus = $per_fit = 0;
					if(is_array($appQuss) && count($appQuss) > 0)
					{
						$totalMatch = $totalQus = 0;
						foreach($appQuss as $curQus)
						{
							$idx = $curQus['q_id'];
							$totalQus++;
							$anssM = $anss = explode(',', $curQus['anss']);
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
						}
						$per_fit = round(($totalMatch / $totalQus) * 100);
					}
					$u_id = $this->readSession('front_id');
					$blockChk = $this->db->cntRows($this->db->tables->blockedApplicants,'u_id = "' . $u_id . '" and email="'.$curApp['email'].'"');
					if($blockChk > 0)
						$status = 0;
					$this->db->updateData($this->db->tables->applicants, array('per_fit' => $per_fit, 'status' => $status), 'id="' . $curApp['id'] . '"');
				}
			}
		}
	}
	
	function logout()
	{
		session_destroy();
		$this->redirect($this->appUrl());
		exit;
	}
}