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
		$this->set('title','Clean Simple Jobs');
		$this->set('layout','front');
	}
	
	function welcome()
	{
		$this->set('title','Welcome to Clean Simple Jobs');
		
	}
	function jobs()
	{
		$this->job_post();
		$this->set('design','account/job_post');
	}
	
	function job_post()
	{
		$this->set('title','Post Job');
		if($this->gdata['var1'] <= 0)
		{
			$backQus = $this->db->getAllData($this->db->tables->adminQus, 'q_type=0 order by id asc');
			$this->set('backQus', $backQus);
			$availQus = $this->db->getAllData($this->db->tables->adminQus, 'q_type=1 order by id asc');
			$this->set('availQus', $availQus);
		}
		else if($this->gdata['var1'] == 1)
		{
			
		}
		else if($this->gdata['var1'] == 2)
		{
			
		}
		else if($this->gdata['var1'] == 3)
		{
			
		}
		else if($this->gdata['var1'] == 4)
		{
			
		}
	}
	
	function post_job()
	{
		$post = serialize($_POST);
		$this->db->insertData($this->db->tables->jobs, array('desc' => $post));
		$this->redirect($this->appUrl(array('section'=>'account', 'action'=>'posted-job')));
	}
	
	function posted_job()
	{
		$this->set('title','Job Posted');
	}
	
	function logout()
	{
		session_destroy();
		$this->redirect($this->appUrl());
		exit;
	}
}