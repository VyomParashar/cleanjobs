<?php
	class adminDetModel extends dbModel
	{
		/* update admin login details */
		function updatePwd($pwd,$id)
		{
			return $this->executeQuery("update users set pwd='".md5($pwd)."' where id=".$id);
		}
	}