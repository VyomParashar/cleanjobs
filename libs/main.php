<?php
class main extends parentMain
{
	var $timeZones = array(
		'America/Adak',
		'America/Anchorage',
		'America/Anguilla',
		'America/Antigua',
		'America/Araguaina',
		'America/Argentina/Buenos_Aires',
		'America/Argentina/Catamarca',
		'America/Argentina/Cordoba',
		'America/Argentina/Jujuy',
		'America/Argentina/La_Rioja',
		'America/Argentina/Mendoza',
		'America/Argentina/Rio_Gallegos',
		'America/Argentina/Salta',
		'America/Argentina/San_Juan',
		'America/Argentina/San_Luis',
		'America/Argentina/Tucuman',
		'America/Argentina/Ushuaia',
		'America/Aruba',
		'America/Asuncion',
		'America/Atikokan',
		'America/Bahia',
		'America/Bahia_Banderas',
		'America/Barbados',
		'America/Belem',
		'America/Belize',
		'America/Blanc-Sablon',
		'America/Boa_Vista',
		'America/Bogota',
		'America/Boise',
		'America/Cambridge_Bay',
		'America/Campo_Grande',
		'America/Cancun',
		'America/Caracas',
		'America/Cayenne',
		'America/Cayman',
		'America/Chicago',
		'America/Chihuahua',
		'America/Costa_Rica',
		'America/Creston',
		'America/Cuiaba',
		'America/Curacao',
		'America/Danmarkshavn',
		'America/Dawson',
		'America/Dawson_Creek',
		'America/Denver',
		'America/Detroit',
		'America/Dominica',
		'America/Edmonton',
		'America/Eirunepe',
		'America/El_Salvador',
		'America/Fortaleza',
		'America/Glace_Bay',
		'America/Godthab',
		'America/Goose_Bay',
		'America/Grand_Turk',
		'America/Grenada',
		'America/Guadeloupe',
		'America/Guatemala',
		'America/Guayaquil',
		'America/Guyana',
		'America/Halifax',
		'America/Havana',
		'America/Hermosillo',
		'America/Indiana/Indianapolis',
		'America/Indiana/Knox',
		'America/Indiana/Marengo',
		'America/Indiana/Petersburg',
		'America/Indiana/Tell_City',
		'America/Indiana/Vevay',
		'America/Indiana/Vincennes',
		'America/Indiana/Winamac',
		'America/Inuvik',
		'America/Iqaluit',
		'America/Jamaica',
		'America/Juneau',
		'America/Kentucky/Louisville',
		'America/Kentucky/Monticello',
		'America/Kralendijk',
		'America/La_Paz',
		'America/Lima',
		'America/Los_Angeles',
		'America/Lower_Princes',
		'America/Maceio',
		'America/Managua',
		'America/Manaus',
		'America/Marigot',
		'America/Martinique',
		'America/Matamoros',
		'America/Mazatlan',
		'America/Menominee',
		'America/Merida',
		'America/Metlakatla',
		'America/Mexico_City',
		'America/Miquelon',
		'America/Moncton',
		'America/Monterrey',
		'America/Montevideo',
		'America/Montserrat',
		'America/Nassau',
		'America/New_York',
		'America/Nipigon',
		'America/Nome',
		'America/Noronha',
		'America/North_Dakota/Beulah',
		'America/North_Dakota/Center',
		'America/North_Dakota/New_Salem',
		'America/Ojinaga',
		'America/Panama',
		'America/Pangnirtung',
		'America/Paramaribo',
		'America/Phoenix',
		'America/Port-au-Prince',
		'America/Port_of_Spain',
		'America/Porto_Velho',
		'America/Puerto_Rico',
		'America/Rainy_River',
		'America/Rankin_Inlet',
		'America/Recife',
		'America/Regina',
		'America/Resolute',
		'America/Rio_Branco',
		'America/Santa_Isabel',
		'America/Santarem',
		'America/Santiago',
		'America/Santo_Domingo',
		'America/Sao_Paulo',
		'America/Scoresbysund',
		'America/Sitka',
		'America/St_Barthelemy',
		'America/St_Johns',
		'America/St_Kitts',
		'America/St_Lucia',
		'America/St_Thomas',
		'America/St_Vincent',
		'America/Swift_Current',
		'America/Tegucigalpa',
		'America/Thule',
		'America/Thunder_Bay',
		'America/Tijuana',
		'America/Toronto',
		'America/Tortola',
		'America/Vancouver',
		'America/Whitehorse',
		'America/Winnipeg',
		'America/Yakutat',
		'America/Yellowknife',
		'Pacific/Apia',
		'Pacific/Auckland',
		'Pacific/Chatham',
		'Pacific/Chuuk',
		'Pacific/Easter',
		'Pacific/Efate',
		'Pacific/Enderbury',
		'Pacific/Fakaofo',
		'Pacific/Fiji',
		'Pacific/Funafuti',
		'Pacific/Galapagos',
		'Pacific/Gambier',
		'Pacific/Guadalcanal',
		'Pacific/Guam',
		'Pacific/Honolulu',
		'Pacific/Johnston',
		'Pacific/Kiritimati',
		'Pacific/Kosrae',
		'Pacific/Kwajalein',
		'Pacific/Majuro',
		'Pacific/Marquesas',
		'Pacific/Midway',
		'Pacific/Nauru',
		'Pacific/Niue',
		'Pacific/Norfolk',
		'Pacific/Noumea',
		'Pacific/Pago_Pago',
		'Pacific/Palau',
		'Pacific/Pitcairn',
		'Pacific/Pohnpei',
		'Pacific/Port_Moresby',
		'Pacific/Rarotonga',
		'Pacific/Saipan',
		'Pacific/Tahiti',
		'Pacific/Tarawa',
		'Pacific/Tongatapu',
		'Pacific/Wake',
		'Pacific/Wallis'
	);
	function loadTwilioApi($sid, $token)
	{
		require_once(LIB . 'twilio/Twilio.php');
		try
		{
			$client = new Services_Twilio($sid, $token);
		}
		catch(Exception $e)
		{
			$client = false;
		}
		return $client;
	}
	
	function chat_sanitize($text)
	{
		$text = htmlspecialchars($text, ENT_QUOTES);
		$text = str_replace("\n\r","\n",$text);
		$text = str_replace("\r\n","\n",$text);
		$text = str_replace("\n","<br>",$text);
		return $text;
	}
	
	public function convertHrMin($time = 0, $format = '%s:%s')
	{
		$hours = floor($time);
		$minutes = (($time - $hours) * 60);
		if($hours == 0)
			$hours = 12;
		/*if($hours < 10)
			$hours = '0' . $hours;*/
		if($minutes < 10)
			$minutes = '0' . $minutes;
		return sprintf($format, $hours, $minutes);
	}
	
	public function convert12($hr = 0)
	{
		if($hr == 0 || $hr == 24)
		{
			return '12:00 AM';
		}
		elseif($hr == 12)
		{
			return '12:00 PM';
		}
		elseif($hr < 12)
		{
			return $this->convertHrMin($hr) . ' AM';
		}
		elseif($hr > 12 && $hr < 24)
		{
			$hr -= 12;
			return $this->convertHrMin($hr) . ' PM';
		}
		return $hr;
	}
	
	public function fromMgrTzCnvrt($dateTime, $timezone = DEFAULT_TIMEZONE)
	{
		$fromzone = DEFAULT_TIMEZONE;
		$tzone = $this->readSession('admin_tzone');
		if(trim($tzone) != '')
			$fromzone = $tzone;
		return $this->tzConvert($dateTime, $timezone, $fromzone);
	}
	
	public function toMgrTzCnvrt($dateTime, $fromzone = DEFAULT_TIMEZONE)
	{
		$timezone = DEFAULT_TIMEZONE;
		$tzone = $this->readSession('admin_tzone');
		if(trim($tzone) != '')
			$timezone = $tzone;
		return $this->tzConvert($dateTime, $timezone, $fromzone);
	}
	
	public function tzConvert($dateTime, $timezone, $fromzone = DEFAULT_TIMEZONE)
	{
		if($fromzone == $timezone)
			return $dateTime;
		$datetime = new DateTime($dateTime, new DateTimeZone($fromzone));
		$la_time = new DateTimeZone($timezone);
		$datetime->setTimezone($la_time);
		return $datetime->format('Y-m-d H:i:s');
	}
}