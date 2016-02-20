<?php
	class dbModel extends parentDbModel
	{
		var $bookFrq = array(
			1 => 'Daily',
			2 => 'Every weekday (Monday to Friday)',
			3 => 'Every Monday, Wednesday, and Friday',
			4 => 'Every Tuesday and Thursday',
			5 => 'Weekly',
			6 => 'Monthly',
			7 => 'Yearly'
		);
		function __construct()
		{
			parent::__construct();
			//$this->executeQuery("SET GLOBAL time_zone = 'America/Halifax'");
			//$this->executeQuery("SET time_zone = '-4:00'");
		}
		
		function getLocation($id)
		{
			$loc = $this->getRow($this->tables->locations, 'where id = "' . $id . '"');
			if(trim($loc['loc_timezone']) == '')
				$loc['loc_timezone'] = DEFAULT_TIMEZONE;
			return $loc;
		}
	}