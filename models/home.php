<?php
class homeModel extends dbModel
{
	/* get admin details according to username and password */
	function adminLogin($email, $pwd)
	{
		$email = $this->addSlashesINP($email);
		$pwd = $this->addSlashesINP($pwd);
		return $this->resultToArray("select * from users where (email='".$email."' or (username='".$email."' and u_type=0)) and pwd='".$pwd."' and status='1' and u_type <= 1");
	}
	
	function getUserByEmail($email = '')
	{
		$user = array();
		if(trim($email) != '')
			$user = $this->getRow('users','where email="'.$this->addSlashesINP($email).'" and status="1"');
		
		return $user;
	}
	
	function getUserByToken($userId = '', $token = '')
	{
		$user = $this->getRow('users','where id="'.$userId.'" and token="'.$this->addSlashesINP($token).'"');
		return $user;
	}
}