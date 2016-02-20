<?php
class parentMain
{
	var $db;
	var $rdata;
	var $gdata;
	var $pdata;
	var $paginate=array('rpp'=>100,'page'=>1);
	var $roles = array('Super Admin', 'Admin', 'Cleaner', 'Client');
	
	/* Constructer to initialize database object */
	function __construct()
	{
		global $customConfig;
		$this->customConfig =(object) $customConfig;
		
		$this->db = $GLOBALS['dbObj'];
		$this->rdata = $_REQUEST;
		$this->gdata = $_GET;
		$this->pdata=$_POST;
		
		$accessRoot = ACCESS;
		if($accessRoot == 'back')
			$this->paginate['rpp'] = 100;

		foreach($this->gdata as $val)
		{
			if(strpos($val,'rpp:')!==false)
			{
				$this->paginationUrl($val);
			}
		}
	}
	
	/* To set global variable */
	function set($key,$value)
	{
		$GLOBALS[$key]=$value;
	}
	
	/* To get global variable */
	function get($key)
	{
		return $GLOBALS[$key];
	}
	
	/* To set session variable */
	function writeSession($key,$value)
	{
		$_SESSION[$key]=$value;
	}
	
	/* To get session variable */
	function readSession($key)
	{
		if(isset($_SESSION[$key]))
		{
			return $_SESSION[$key];
		}
		else
		{
			return '';
		}
	}
	
	/* unset session variables */
	function unsetSvar($key)
	{
		unset($_SESSION[$key]);
	}
	
	/* To redirect to a specific url */
	function redirect($url)
	{
		header("location:".$url);
		die;
	}
	
	/* To resize image */
	function resizeImage($img, $newfilename, $wdt=NULL, $height=NULL)
	{ 
	
		if(empty($height)){
			// Height is nit set so we are keeping the same aspect ratio.
			list($width, $heightt) = getimagesize($img);
			//if($width > $height){
				$w = $wdt;
				$h = ($heightt / $width) * $w;
		   /*}else{
				$w = $wdt;
				$h = $w;
				$w = ($width / $height) * $w;
			}*/
		}elseif(empty($wdt)){
			list($width, $heightt) = getimagesize($img);
			$h = $height;
			$w = ($width / $heightt) * $h;
		}else{
			// Both width and Height are set.
			// this will reshape to the new sizes.
			$w = $wdt;
			$h = $height;
		}
	 //Check if GD extension is loaded
	 if (!extension_loaded('gd') && !extension_loaded('gd2')) {
	  trigger_error("GD is not loaded", E_USER_WARNING);
	  return false;
	 }
	 //Get Image size info
	 $imgInfo = getimagesize($img);
	 switch ($imgInfo[2]) {
	  case 1: $im = imagecreatefromgif($img); break;
	  case 2: $im = imagecreatefromjpeg($img);  break;
	  case 3: $im = imagecreatefrompng($img); break;
	  default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
	 }
	 //If image dimension is smaller, do not resize
	 if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
	  $nHeight = $imgInfo[1];
	  $nWidth = $imgInfo[0];
	 }else{
	  if ($w/$imgInfo[0] < $h/$imgInfo[1]) {
	   $nWidth = $w;
	   $nHeight = $imgInfo[1]*($w/$imgInfo[0]);
	  }else{
	   $nWidth = $imgInfo[0]*($h/$imgInfo[1]);
	   $nHeight = $h;
	  }
	 }
	 $nWidth = round($nWidth);
	 $nHeight = round($nHeight);
	 $newImg = imagecreatetruecolor($nWidth, $nHeight);
	 /* Check if this image is PNG or GIF, then set if Transparent*/  
	 if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
	  imagealphablending($newImg, false);
	  imagesavealpha($newImg,true);
	  $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
	  imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
	 }
	 imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
	 
	 //Generate the file, and rename it to $newfilename
	 switch ($imgInfo[2]) {
	  case 1: imagegif($newImg,$newfilename); break;
	  case 2: imagejpeg($newImg,$newfilename);  break;
	  case 3: imagepng($newImg,$newfilename); break;
	  default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
	 }
	   return $newfilename;
	}

	/* To save database from special characters and sql injection */
	function addSlashesINP($str)
	{
		if(get_magic_quotes_gpc())
			$str = stripslashes($str);
		return addslashes(trim($str));
	}
	
	/* To make simple one field array from a complex array on basis of specific key in array */
	function arrayToField($arr,$field)
	{
		$result = array();
		
		foreach($arr as $curRow)
		{
			$result[] = $curRow[$field];
		}
		return $result;
	}
	
	//function to create email headers
	function genEHead($trgtFrom,$replyTo='')
	{
		$EmailHead="MIME-Version: 1.0\r\n";
		$EmailHead.="Content-type: text/html; charset=iso-8859-1\r\n";
		$EmailHead.="From: ".$trgtFrom."\r\n";
		if($replyTo!='')
		{
			$EmailHead.="Reply-To: ".$replyTo."\r\n";
		}
		return $EmailHead;
	}
	
	// To call some file from library directory
	function getLib($libname)
	{
		require_once(LIB.$libname.'.php');
	}
	
	// To generate application level url links
	function appUrl($url=array(),$ajax=false,$root='')
	{
		$curUrl = '';
		$vars = '';
		if(is_array($url['vars']) && count($url['vars'])>0 && (!isset($url['action']) || $url['action']==''))
		{
			$url['action'] = 'index';
		}
		if(isset($url['action']) && $url['action']!='' && (!isset($url['section']) || $url['section']==''))
		{
			$url['section'] = 'home';
		}
		if($ajax)
		{
			$url['vars']['applayout'] = 'ajax';
		}
		if($ajax || HTACCESS!='YES')
		{
			if(is_array($url['vars']) && count($url['vars'])>0)
			{
				foreach($url['vars'] as $index=>$val)
				{
					$vars .= '&'.$index.'='.$val;
				}
			}
			if($url['action']!='')
			{
				$action = '&action='.$url['action'];
			}
			if($url['section']!='')
			{
				$section = '?section='.$url['section'];
				$curUrl = 'index.php'.$section.$action.$vars;
			}
		}
		else
		{
			if(is_array($url['vars']) && count($url['vars'])>0)
			{
				foreach($url['vars'] as $index=>$val)
				{
					$vars .= '/'.$val;
				}
			}
			if($url['action']!='')
			{
				$action = '/'.$url['action'];
			}
			if($url['section']!='')
			{
				$curUrl = $url['section'].$action.$vars;
			}
		}
		
		$accessRoot = ACCESS;
		if($root!='')
		{
			$accessRoot = $root;
		}
		if($accessRoot=='back')
		{
			return PRJ_BROOT.$curUrl;
		}
		else
		{
			return PRJ_FROOT.$curUrl;
		}
	}
	
	// To set temprary app variable in session eg. for error msg
	function setFlash($val)
	{
		$this->writeSession('appFlashMsg',$val);
	}
	
	// To get temprary app variable in session eg. for error msg
	function getFlash()
	{
		$toRet = $this->readSession('appFlashMsg');
		$this->unsetSvar('appFlashMsg');
		return $toRet;
	}
	
	// For email validation return true for valid e-mail
	function isValidEmail($email)
	{
		$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
		if (eregi($pattern, trim($email)))
		{
			return true;
		}
		else
		{
			return false;
		}   
	}

	// return simple mail object
	function maildata()
	{
		$mail = new PHPMailer();
		$mail->SetFrom(EMAIL_FROM, SITE_NAME);
		$mail->IsHTML(true);
		return $mail;
	}

	//function to select smtp or simple mail function
	function getMail()
	{
		$mail = $this->maildata();
		
		if(defined('MAIL') && MAIL=='SMTP')
		{
			$mail->IsSMTP();
			$mail->SMTPDebug  = 1; 
			$mail->SMTPSecure = 'ssl';
			$mail->SMTPAuth = true;
			//Ask for HTML-friendly debug output
			$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
			$mail->Port       = 465;
			//Username to use for SMTP authentication
			$mail->Username = "";
			//Password to use for SMTP authentication
			$mail->Password = "";
		}
		else
		{
			$mail->IsMail();
		}
		
		return $mail;
	}
			
			// make tag urls
	function makeurl($str)
	{
		return str_replace(array(' ','_','*','.',',','/','\\',']','[','}','{',';','?','<','>','@','#','!','%','^','&','*','(',')','+','=','`','~','"',"'",':'),array('-','-','','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-'),$str);
	}

	//Function to set pagination value from request
	function paginationUrl($reqVal)
	{
		$jsonVal = '{"'.str_replace(array('|',':'),array('","','":"'),  $reqVal).'"}';

		$tempHld = json_decode($jsonVal);

		if(isset($tempHld->rpp) && $tempHld->rpp!='')
		{
			$this->paginate['rpp'] = (int)$tempHld->rpp;
			$this->paginate['rpp_update'] = 'yes';
		}
		if(isset($tempHld->page) && $tempHld->page!='')
		{
			$this->paginate['page'] = (int)$tempHld->page;
		}
					if(isset($tempHld->col) && $tempHld->col!='')
		{
			$this->paginate['col'] = $tempHld->col;
		}
					if(isset($tempHld->dir) && $tempHld->dir!='')
		{
			$this->paginate['dir'] = $tempHld->dir;
		}
	}
			
	/* To get paginated results */
	function getPaginated($table,$cond='1',$fields='*')
	{
		$rpp = $this->paginate['rpp'];
		$page = $this->paginate['page'];
		$start = ($page-1)*$rpp;
		$this->paginate['start'] = $start;
		$orderBy = '';
		
		if(isset($this->paginate['col']) && $this->paginate['col']!='' && isset($this->paginate['dir']) && $this->paginate['dir']!='')
			$orderBy = ' order by '.$this->paginate['col'].' '.$this->paginate['dir'].' ';
		$result=$this->db->getPaginated($table,$orderBy,$start,$rpp,$cond,$fields);
		$numResult = $this->db->cntRows($table,$cond);
		$this->paginate['noPages'] = ceil($numResult/$rpp);
		return $result;
	}
			
	/* To print pagination design */
	function getPaginatedHtml()
	{
		$allVars = array('var1','var2','var3','var4','var5','var6','var7','var8','var9');
		$tempVarArr = array();
		foreach($allVars as $cur)
		{
				if(isset($this->gdata[$cur]) && trim($this->gdata[$cur])!='' && strpos($this->gdata[$cur],'rpp:')===false)
				{
						$tempVarArr[$cur] = $this->gdata[$cur];
				}
		}

		$cnt = 1;

		if(is_array($tempVarArr) && count($tempVarArr)>0)
		{
				$cnt=count($tempVarArr)+1;
		}

		$colDir = '';

		if(isset($this->paginate['col']) && trim($this->paginate['col'])!='')
		{
				$colDir .= '|col:'.$this->paginate['col'];
		}
		if(isset($this->paginate['dir']) && trim($this->paginate['dir'])!='')
		{
				$colDir .= '|dir:'.$this->paginate['dir'];
		}

		$cntVar = 'var'.$cnt;

		$arrayUrl = array('section'=>$GLOBALS['section'],'action'=>$GLOBALS['action'],'vars'=>$tempVarArr);

		$arrayUrl['vars'][$cntVar] = "rpp:'+this.value+'|page:1".$colDir;

		$html = 'Results Per Page
			<select id="rpp" name="rpp" onchange="window.location.href=\''.$this->appUrl($arrayUrl).'\'">
				<option value="10">10</option>
				<option ';
				if($this->paginate['rpp']==25) { $html .= 'selected="selected"'; } 
						$html .= ' value="25">25</option> <option ';
		if($this->paginate['rpp']==50) { $html .= 'selected="selected"'; } 
						$html .= ' value="50">50</option> <option ';
		if($this->paginate['rpp']==100) { $html .= 'selected="selected"'; }
						$html .= ' value="100">100</option>
			</select>
			&nbsp;&nbsp;';
		if($this->paginate['noPages']>0)
		{
				if($this->paginate['page']>1)
				{	
						$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:1".$colDir;
						$html .= '<a href="'.$this->appUrl($arrayUrl).'"><img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-first.png" alt="first"/></a>';

						$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:".($this->paginate['page']-1).$colDir;
						$html .= ' <a href="'.$this->appUrl($arrayUrl).'"><img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-previous.png" alt="previous"/></a>';
				}
				else
				{
						$html .= '<img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-first-off.png" alt="first"/>
						<img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-previous-off.png" alt="previous"/>';
				}

				$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:'+this.value+'".$colDir;
				$html .= ' <select id="pageNo" name="pageNo" onchange="window.location.href=\''.$this->appUrl($arrayUrl).'\'">';
				   for($i=1;$i<=$this->paginate['noPages'];$i++)
				   {
								$html .= '<option value="'.$i.'" ';
								if($this->paginate['page']==$i) { $html .= 'selected="selected"'; }
								$html .= '>'.$i.'</option>';
						}
				$html .= '</select>
				<span class="gridof">of</span>&nbsp;<b>'.$this->paginate['noPages'].'</b>';
				if($this->paginate['page']==$this->paginate['noPages'] || $this->paginate['noPages']==1)
				{
						$html .= ' <img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-next-off.png" alt="next"/>
						<img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-last-off.png" alt="last"/>';
				}
				else
				{
						if(isset($this->paginate['page']) && $this->paginate['page']>0){
								$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:".($this->paginate['page']+1).$colDir;
								$html .= ' <a href="'.$this->appUrl($arrayUrl).'">';
						} else{
								$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:2".$colDir;
								$html .= ' <a href="'.$this->appUrl($arrayUrl).'">';
						}
						$html .= '<img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-next.png" alt="next"/></a>';

						$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:".$this->paginate['noPages'].$colDir;
						$html .= '<a href="'.$this->appUrl($arrayUrl).'"><img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-last.png" alt="last"/></a>';
				}
		}
		else
		{
				$html .= '<img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-first-off.png" alt="first"/>
				<img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-previous-off.png" alt="previous"/>
				0 <span class="gridof">of</span> 0
				<img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-next-off.png" alt="next"/>
				<img src="'.PRJ_BROOT.'multimedias/images/gridIcons/page-last-off.png" alt="last"/>';
		}
		
		return $html;
	}
	
	/* To print ajax pagination design */
	function getAjaxPaginatedHtml()
	{
		$allVars = array('var1','var2','var3','var4','var5','var6','var7','var8','var9');
		$tempVarArr = array();
		foreach($allVars as $cur)
		{
				if(isset($this->gdata[$cur]) && trim($this->gdata[$cur])!='' && strpos($this->gdata[$cur],'rpp:')===false)
				{
						$tempVarArr[$cur] = $this->gdata[$cur];
				}
		}

		$cnt = 1;

		if(is_array($tempVarArr) && count($tempVarArr)>0)
		{
				$cnt=count($tempVarArr)+1;
		}

		$colDir = '';

		if(isset($this->paginate['col']) && trim($this->paginate['col'])!='')
		{
				$colDir .= '|col:'.$this->paginate['col'];
		}
		if(isset($this->paginate['dir']) && trim($this->paginate['dir'])!='')
		{
				$colDir .= '|dir:'.$this->paginate['dir'];
		}

		$cntVar = 'var'.$cnt;
		
		$tempVarArr['job'] = 'displayGrid';
		if($this->gdata['loc_id'] > 0)
			$tempVarArr['loc_id'] = $this->gdata['loc_id'];
		if($this->gdata['lsearch'] > 0)
			$tempVarArr['lsearch'] = $this->gdata['lsearch'];

		$arrayUrl = array('section'=>$GLOBALS['section'],'action'=>$GLOBALS['action'],'vars'=>$tempVarArr);

		$arrayUrl['vars'][$cntVar] = "rpp:'+this.value+'|page:1".$colDir;

		$html = '<div class="page-info"> Results Per Page
			<select id="rpp" name="rpp" onchange="gridAjaxReq(\''.$this->appUrl($arrayUrl,true).'\');">
				<option value="10">10</option>
				<option ';
				if($this->paginate['rpp']==25) { $html .= 'selected="selected"'; } 
						$html .= ' value="25">25</option> <option ';
		if($this->paginate['rpp']==50) { $html .= 'selected="selected"'; } 
						$html .= ' value="50">50</option> <option ';
		if($this->paginate['rpp']==100) { $html .= 'selected="selected"'; }
						$html .= ' value="100">100</option>
			</select> </div> 
			&nbsp;&nbsp;';
		if($this->paginate['noPages']>0)
		{
				if($this->paginate['page']>1)
				{	
						$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:1".$colDir;
						$html .= '<a class="page-far-left" href="javascript:void(0);" onClick="gridAjaxReq(\''.$this->appUrl($arrayUrl,true).'\');"></a>';

						$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:".($this->paginate['page']-1).$colDir;
						$html .= ' <a class="page-left" href="javascript:void(0);" onClick="gridAjaxReq(\''.$this->appUrl($arrayUrl,true).'\');"></a>';
				}
				else
				{
						$html .= ' <a class="page-far-left"></a>
									<a class="page-left"></a> ';
				}

				$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:'+this.value+'".$colDir;
				$html .= '<div class="page-info"> <select id="pageNo" name="pageNo" onchange="gridAjaxReq(\''.$this->appUrl($arrayUrl,true).'\');">';
				   for($i=1;$i<=$this->paginate['noPages'];$i++)
				   {
								$html .= '<option value="'.$i.'" ';
								if($this->paginate['page']==$i) { $html .= 'selected="selected"'; }
								$html .= '>'.$i.'</option>';
						}
				$html .= '</select>
				<span class="gridof">of</span>&nbsp;<b>'.$this->paginate['noPages'].'</b> </div> ';
				if($this->paginate['page']==$this->paginate['noPages'] || $this->paginate['noPages']==1)
				{
						$html .= ' <a class="page-right"></a>
									<a class="page-far-right"></a> ';
				}
				else
				{
						if(isset($this->paginate['page']) && $this->paginate['page']>0){
								$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:".($this->paginate['page']+1).$colDir;
								$html .= ' <a class="page-right" href="javascript:void(0);" onClick="gridAjaxReq(\''.$this->appUrl($arrayUrl,true).'\');">';
						} else{
								$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:2".$colDir;
								$html .= ' <a class="page-right" href="javascript:void(0);" onClick="gridAjaxReq(\''.$this->appUrl($arrayUrl,true).'\');">';
						}
						$html .= '</a>';

						$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:".$this->paginate['noPages'].$colDir;
						$html .= '<a  class="page-far-right" href="javascript:void(0);" onClick="gridAjaxReq(\''.$this->appUrl($arrayUrl,true).'\');"></a>';
				}
		}
		else
		{
				$html .= '<a class="page-far-left"></a>
				<a class="page-left"></a>
			   <div class="page-info"> 0 <span class="gridof">of</span> 0 </div> 
				<a class="page-right"></a>
				<a class="page-far-right"></a>';
		}
		
		return $html;
	}
	
	// To generate sorting link
	function ajaxSort($lable,$tbl_field)
	{
		$allVars = array('var1','var2','var3','var4','var5','var6','var7','var8','var9');
		$tempVarArr = array();
		foreach($allVars as $cur)
		{
				if(isset($this->gdata[$cur]) && trim($this->gdata[$cur])!='' && strpos($this->gdata[$cur],'rpp:')===false)
				{
						$tempVarArr[$cur] = $this->gdata[$cur];
				}
		}

		$cnt = 1;

		if(is_array($tempVarArr) && count($tempVarArr)>0)
		{
				$cnt=count($tempVarArr)+1;
		}
		
		$sort = 'asc';
		$img = '';

		if($this->paginate['col']==$tbl_field && $this->paginate['dir']=='asc')
		{
				$sort = 'desc';
				$img = '&#9650;';
		}
		else if($this->paginate['col']==$tbl_field && $this->paginate['dir']=='desc')
		{
			$img = '&#9660;';
		}

		$cntVar = 'var'.$cnt;
		
		$tempVarArr['job'] = 'displayGrid';
		if($this->gdata['loc_id'] > 0)
			$tempVarArr['loc_id'] = $this->gdata['loc_id'];
		if($this->gdata['lsearch'] > 0)
			$tempVarArr['lsearch'] = $this->gdata['lsearch'];

		$arrayUrl = array('section'=>$GLOBALS['section'],'action'=>$GLOBALS['action'],'vars'=>$tempVarArr);

		$arrayUrl['vars'][$cntVar] = "rpp:".$this->paginate['rpp']."|page:".$this->paginate['page'].'|col:'.$tbl_field.'|dir:'.$sort;
		
		
		$html = '<a href="javascript:void(0);" onClick="gridReq(\''.$this->appUrl($arrayUrl,true).'\');">
		'.$lable.' <span class="akico">'.$img.'</span>
		</a>';
		
		return $html;
   }
   // get email file content with password variable
	function get_email_content($email = NULL, $data = array())
	{
		global $email_design, $email_ddata;
		$email_design = $email;
		$email_ddata = $data;
		ob_start();
		include(LAYOUT . 'emails/default.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	// get output of design file
	public function getDesign($view = '', $data)
	{
		global $appObj;
		if(trim($view) == '' || !file_exists(DESIGN.$view.'.php'))
			return '';
		ob_start();
		include_once(DESIGN.$view.'.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	// load model file and return model object
	function loadModel($models)
	{
		require_once(MODEL.$models.".php");
		$mdlClass = $models.'Model';
		$tempObj = new $mdlClass();
		return $tempObj;
	}
	
	// generate javascript mailto ready content
	function mailToReady($str)
	{
		/*$toRet = str_replace(array("\n", '"'), array("%0D%0A", "%22"), $content);
		return $toRet;*/
		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    	return strtr(rawurlencode($str), $revert);
	}
	
	function encrypt($string)
	{
		$key = SEC_SALT;
		$iv = mcrypt_create_iv(
			mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
			MCRYPT_DEV_URANDOM
		);
		
		$encrypted = base64_encode(
			$iv .
			mcrypt_encrypt(
				MCRYPT_RIJNDAEL_128,
				hash('sha256', $key, true),
				$string,
				MCRYPT_MODE_CBC,
				$iv
			)
		);
		return $encrypted;
	}
	function decrypt($encrypted)
	{
		$key = SEC_SALT;
		$data = base64_decode($encrypted);
		$iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
		
		$decrypted = rtrim(
			mcrypt_decrypt(
				MCRYPT_RIJNDAEL_128,
				hash('sha256', $key, true),
				substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
				MCRYPT_MODE_CBC,
				$iv
			),
			"\0"
		);
		return $decrypted;
	}
}