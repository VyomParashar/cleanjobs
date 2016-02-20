<?php
class parentDbModel
{
	var $dbValRest = array('now()');
	var $tables;
	function __construct()
	{
		global $appTables;
		$this->tables =(object) $appTables;
	}
	/* Return array from database query result */
	function resultToArray($qry)
	{
		$res = $this->executeQuery($qry);
		
		$result = array();
		
		while($cur=mysqli_fetch_array($res))
		{
			$result[] = $cur;
		}
		return $result;
	}
	
	/* To execute database query */
	function executeQuery($qrys)
	{
		global $dbConn;
		$res = mysqli_query($dbConn, $qrys);
		if(!$res)
		{
			echo mysqli_error($dbConn) . ' <====> ' . $qrys;
		}
		return $res;
	}
	
	/* To save database from special characters and sql injection */
	function addSlashesINP($str)
	{ 
		if(get_magic_quotes_gpc())
		{
			$str = stripslashes($str);
		}
		
		return addslashes(trim($str));
	}
	
	/* To get a value of a field from database */
	function getValue($trgtTbl,$trgtFld,$trgtOther='')
	{
		global $dbConn;
		$currData=$this->executeQuery("select ".$trgtFld." as wanted from ".$trgtTbl." ".$trgtOther);
		if(mysqli_num_rows($currData)>0)
		{
			$currVal=mysqli_fetch_array($currData) or die(mysqli_error($dbConn));
			return stripslashes($currVal['wanted']);
		}
		else
		{	return '';	}
	}
	
	/* To get a value of a Row from database */
	function getRow($trgtTbl,$trgtOther='',$fields='*')
	{
		global $dbConn;
		$currData=$this->executeQuery("select ".$fields." from ".$trgtTbl." ".$trgtOther.' limit 0,1');
		if(mysqli_num_rows($currData)>0)
		{
			$res = mysqli_fetch_assoc($currData) or die(mysqli_error($dbConn));
			
			return $res;
		}
		else
		{	return '';	}
	}
	
	/* For insert query */
	function insertData($tbl,$data)
	{
		if(is_array($data) && count($data)>0)
		{
			$values = array();
			foreach($data as $index=>$val)
			{
				/*if($val == 'now()')
				{
					$values[] = $index.'="'.date("Y-m-d H:i:s").'"';
				}
				else*/ if(in_array($val,$this->dbValRest))
				{
					$values[] = $index.'='.$val;
				}
				else
				{
					$values[] = $index.'="'.$this->addSlashesINP($val).'"';
				}
			}
			return $this->executeQuery("insert into ".$tbl." set ".implode(',',$values));
		}
	}
	
	/* For update query */
	function updateData($tbl,$data,$cond='1')
	{
		if(is_array($data) && count($data)>0)
		{
			$values = array();
			foreach($data as $index=>$val)
			{
				/*if($val == 'now()')
				{
					$values[] = $index.'="'.date("Y-m-d H:i:s").'"';
				}
				else*/ if(in_array($val,$this->dbValRest))
				{
					$values[] = $index.'='.$val;
				}
				else
				{
					$values[] = $index.'="'.$this->addSlashesINP($val).'"';
				}
			}
			return $this->executeQuery("update ".$tbl." set ".implode(',',$values)." where ".$cond);
		}
	}
	
	/* For delete query */
	function deleteData($tbl,$cond='1')
	{
		return $this->executeQuery("delete from ".$tbl." where ".$cond);
	}
	
	/* Return array from database from a table result */
	function getAllData($tbl,$cond='1',$fields='*')
	{
		$qry = "select ".$fields." from ".$tbl." where ".$cond;
		
		$res = $this->executeQuery($qry);
		
		$result = array();
		
		while($cur=mysqli_fetch_assoc($res))
		{
			$result[] = $cur;
		}
		return $result;
	}
	
	/* For last insert database id query */
	function lastid()
	{
		global $dbConn;
		return mysqli_insert_id($dbConn);
	}
	/* Return array from database query result */
	function cntRows($tbl, $cond=1)
	{
		$res = $this->executeQuery("select * from ".$tbl." where ".$cond);
		return mysqli_num_rows($res);
	}

	/* To get paginated results */
	function getPaginated($table,$orderBy,$start=0,$rpp=10,$cond='1',$fields='*')
	{
		return $this->resultToArray('select '.$fields.' from '.$table.' where '.$cond.' '.$orderBy.' limit '.$start.','.$rpp);
	}
	
	/* To get paginated results */
	function getPaginate($table,$orderBy,$start=0,$rpp=10,$cond='1',$fields='*')
	{
		return $this->resultToArray("select ".$fields." from ".$table." where ".$cond." ".$orderBy." limit ".$start.",".$rpp);
	}
}