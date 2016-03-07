<?php
class homeModel extends dbModel
{
	/* get admin details according to username and password */
	function userLogin($email, $pwd, $u_type = 3)
	{
		$email = $this->addSlashesINP($email);
		$pwd = $this->addSlashesINP($pwd);
		return $this->resultToArray("select * from users where email='" . $email . "' and pwd='" . $pwd . "' and status='1' and u_type='" . $u_type . "'");
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