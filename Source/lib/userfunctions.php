<?php
function selfURL() {
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];}

function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }

function getMonthName($intMonth,$type){
$key = $intMonth-1;
$small_month = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
$longmonth_month = array("January","February","March","April","May","June","July","August","September","October","November","December");
if(strtolower(trim($type))=="small")
	return $small_month[$key];
else
	return $longmonth_month[$key];}

class paging{
	
	var $koneksi;
	var $p;
	var $page;
	var $q;
	var $query;
	var $next;
	var $prev;
	var $number;
	function paging($baris, $langkah, $prev, $next, $number,$pagename,$function)
	{
		$this->next=$next;
		$this->prev=$prev;
		$this->pagename=$pagename;
		$this->number=$number;
		$this->p["baris"]=$baris;
		$this->p["langkah"]=$langkah;
		$this->functionname = $function;
		$_SERVER["QUERY_STRING"]=preg_replace("/&".$this->pagename."=[0-9]*/","",$_SERVER["QUERY_STRING"]);
		$thumbpage = 1;
		if(isset($_REQUEST['thumbpage']))
		{
				$thumbpage = $_REQUEST['thumbpage'];
		}
		if (empty($_GET[$pagename])) {
			$this->page=1;
		} else {
			$this->page=$_GET[$pagename];
		}
	}

	function query($query)
	{
	
		$kondisi=false;
		// only select
		if (!preg_match("/^[\s]*select*/i",$query)) {
			$query="select ".$query;
			
		}

		$querytemp = mysql_query($query);
		$this->p["count"]= mysql_num_rows($querytemp);

		// total page
		$this->p["total_page"]=ceil($this->p["count"]/$this->p["baris"]);
		
		// filter page
		if  ($this->page<=1)
			$this->page=1;
			
		elseif ($this->page>$this->p["total_page"])
			$this->page=$this->p["total_page"];

		// awal data yang diambil
		$this->p["mulai"]=$this->page*$this->p["baris"]-$this->p["baris"];

		$query=$query." limit ".$this->p["mulai"]."," .$this->p["baris"];
		
		$query=mysql_query($query) or die("Query Error");
		$this->query=$query;
		//echo $query;
	}
	
	function result()
	{
		return $result=mysql_fetch_object($this->query);		
	}

	function result_assoc()
	{
		return mysql_fetch_assoc($this->query);
	}

	function print_no()
	{
		$number=$this->p["mulai"]+=1;
		return $number;
	}
	
	function print_color($color1,$color2)
	{
		if (empty($this->p["count_color"]))
			$this->p["count_color"] = 0;
		if ( $this->p["count_color"]++ % 2 == 0 ) {
			return $color=$color1;
		} else {
			return $color=$color2;
		}
	}

	function result_count()
	{
		return $result=mysql_num_rows($this->query);
		
	}
	function print_info()
	{
		$page=array();
		$page["start"]=$this->p["mulai"]+1;
		$page["end"]=$this->p["mulai"]+$this->p["baris"];
		$page["total"]=$this->p["count"];
		$page["total_pages"]=$this->p["total_page"];
			if ($page["end"] > $page["total"]) {
				$page["end"]=$page["total"];
			}
			if (empty($this->p["count"])) {
				$page["start"]=0;
			}

		return $page;
	}

	function print_link($printTotal=false)
	{
		//generate template
		function number($i,$number)
		{
			return ereg_replace("^(.*)%%number%%(.*)$","$i",$number);
		}
		$print_link = false;

		if ($this->p["count"]>$this->p["baris"]) 
		{
			$print_link .= "<div class=\"wp-pagenavi\">";
			if($printTotal)
				$print_link .= "<span class=\"pages\">Page ".$this->page." of ".$this->p["total_page"]."</span>";
			// print prev
			if($this->prev != "")
			{
				if ($this->page>1)
				{
					$addfunction = "";
					if(trim($this->functionname) != "")
					{
						$setfunction = str_replace("<page>",($this->page-1),$this->functionname);
						$addfunction = " onclick=\"".$setfunction."; return false;\"";
					}
				$print_link .= "<a  class=\"page larger\" $addfunction href=\"".$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"]."&".$this->pagename."=".($this->page-1)."\">Previous</a>";
				}
				else
				$print_link.= "<span class=\"disabled\">Previous</span>";	
			}
			// set number
			if($this->page==$this->p["total_page"])
			$this->p["bawah"]=$this->page-9;
			elseif($this->page==($this->p["total_page"]-1))
			$this->p["bawah"]=$this->page-8;
			elseif($this->page==($this->p["total_page"]-2))
			$this->p["bawah"]=$this->page-7;
			elseif($this->page==($this->p["total_page"]-3))
			$this->p["bawah"]=$this->page-6;
			elseif($this->page==($this->p["total_page"]-4))
			$this->p["bawah"]=$this->page-5;
			elseif($this->page==($this->p["total_page"]-5))
			$this->p["bawah"]=$this->page-4;
			else
			$this->p["bawah"]=$this->page-$this->p["langkah"];
				if ($this->p["bawah"]<1) $this->p["bawah"]=1;

			if($this->page==1 or $this->page==2 or $this->page==3 or $this->page==4 or $this->page==5 )
			$this->p["atas"]=10;
			elseif($this->page==6)
						
			$this->p["atas"]=11;
			
			else
			$this->p["atas"]=$this->page+($this->p["langkah"]+1);
			
				if ($this->p["atas"]>$this->p["total_page"]) $this->p["atas"]=$this->p["total_page"];

			// print start
			if ($this->page<>1)
			{
				for ($i=$this->p["bawah"];$i<=$this->page-1;$i++)
				{
				$addfunction = "";
				if(trim($this->functionname) != "")
				{
					$setfunction = str_replace("<page>",$i,$this->functionname);
					$addfunction = " onclick=\"".$setfunction."; return false;\"";
				}
					$print_link .="<a  class=\"page larger\"  $addfunction href=\"".$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"]."&".$this->pagename."=$i\">".number($i,$this->number)."</a>";
				}
			}
			// print active
			if ($this->p["total_page"]>1)
				$print_link .= "<span class=\"current\">".number($this->page,$this->number)."</span>";

			// print end
			for ($i=$this->page+1;$i<=$this->p["atas"];$i++)
			{
				$addfunction = "";
				if(trim($this->functionname) != "")
				{
					$setfunction = str_replace("<page>",$i,$this->functionname);
					$addfunction = " onclick=\"".$setfunction."; return false;\"";
				}
			$print_link .= "<a  class=\"page larger\"  $addfunction href=\"".$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"]."&".$this->pagename."=$i\">".number($i,$this->number)."</a>";
			}

			// print next
			if($this->next != "")
			{
				if ($this->page<$this->p["total_page"])
				{
								$addfunction = "";
					if(trim($this->functionname) != "")
					{
						$setfunction = str_replace("<page>",($this->page+1),$this->functionname);
						$addfunction = " onclick=\"".$setfunction."; return false;\"";
					}
				$print_link .= "<a  class=\"page larger\"  $addfunction href=\"".$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"]."&".$this->pagename."=".($this->page+1)."\">Next</a>";
				}
				else
				$print_link.= "<span class=\"disabled\">Next</span>";
			}
$print_link.= "</div>";	
			return $print_link;
		}	
		
	}}

function cookieelogin($email,$password,$isalive){
	$error = "";
	if(trim($email) != "" && trim($password) != "")
	{
		$sql_login = "select * from users where `email`='$email' and `password`='$password'";
		$result_login = mysql_query($sql_login);
		if(mysql_num_rows($result_login) > 0)
		{
			if(mysql_result($result_login,0,"status") == "1" || mysql_result($result_login,0,"status") == "4")
			{
				$_SESSION['uid'] = mysql_result($result_login,0,"uid");
				$_SESSION['email'] = mysql_result($result_login,0,"email");
				$_SESSION['name'] = mysql_result($result_login,0,"name");
				$_SESSION['photo'] = mysql_result($result_login,0,"photo");
				if(mysql_result($result_login,0,"status") == "4")
					$_SESSION['isadmin'] = "true";
				else
					$_SESSION['isadmin'] = "false";
				if(strtolower(trim($isalive)) == "on")
					{
						$Month = 2592000 + time();
						//this adds 30 days to the current time
						setcookie("cookieeemail", $email, $Month);
						setcookie("cookieepassword", base64_encode($password), $Month);
					}
			}
			else
				$error = $messages["errorBlockedUser"];
		}
		else
			$error = $messages["errorWrongCredentials"];
	}
	else
		$error = $messages["errorNoCredentials"];
if($error != "")		
{
$Month = time() - 2592000;
setcookie("cookieeemail", "", $Month);
setcookie("cookieepassword","",$Month);
	header("location:index.php?error=".$error);
}}

function generateinvite($url,$coverphoto,$albumname,$albumdate,$albumlocation,$albumdesc){
$template = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>Daniepaul.com - Mail Invite</title></head><body style=\"background-color:#ffffff; margin:15px; color:#000000\"><div style=\"border:1px solid #cccccc; padding:5px\"><span style=\"font-size:13pt; font-family:Arial; font-weight:bold\">You are invited to view Daniel's photo album: <a href=\"$url\">$albumname</a></span><table width=\"100%\" cellpadding=\"5\" style=\"background-color:#eeeeee; border:1px solid #cccccc; margin:10px 0px 10px 0px; font-size:10pt; font-family:arial\"><tr><td width=\"150\" valign=\"middle\"><img src=\"".BASEDIR."albumcovers/$coverphoto\" alt=\"Album Cover\" border=\"0\" style=\"border:1px solid #cccccc; background-color:#ffffff; padding:5px\"/></td><td valign=\"top\"><b style='font-size:11pt'>$albumname</b><br/>$albumlocation ,$albumdate<br>$albumdesc<hr style=\"border:0px; border-bottom:1px; border-style:dotted;\"/><a href=\"$url\">View Album</a></td></tr></table><span style=\"font-family:Arial;font-size:8pt; color:#aaaaaa\">If you are having problems viewing this email, copy and paste the following into your browser:<br/><a href=\"$url\">$url</a></span><br /></div></body></html>";
return $template;}

function sendemail($from,$to,$subject,$message){
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0'."\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
				
				// Additional headers
//				$headers .= 'To: '.$to_email. "\r\n";
				$headers .= 'From: '.$from."\r\n";
				$headers .= 'Reply-To: '.$from. "\r\n";
				$headers .= 'Return-Path: '.$from."\r\n";
				$headers .= "X-Priority: 1 (Highest)\r\n"; 
				$headers .= "X-MSMail-Priority: High\r\n"; 
				$headers .= "Importance: High\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();

//				$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//				$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
				
				// Mail it
				$value = mail($to, $subject, $message, $headers);
				return $value;}

function getRealIpAddr(){
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;}


function validEmail($email){
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         $isValid = false;
      }
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
      {
         if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
//      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
//      {
//         $isValid = false;
//      }
   }
   return $isValid;}
   
   
//Function to seperate multiple tags one line
function fix_newlines_for_clean_html($fixthistext)
{
	$fixthistext_array = explode("\n", $fixthistext);
	foreach ($fixthistext_array as $unfixedtextkey => $unfixedtextvalue)
	{
		//Makes sure empty lines are ignores
		if (!preg_match("/^(\s)*$/", $unfixedtextvalue))
		{
			$fixedtextvalue = preg_replace("/>(\s|\t)*</U", ">\n<", $unfixedtextvalue);
			$fixedtext_array[$unfixedtextkey] = $fixedtextvalue;
		}
	}
	if(sizeof($fixedtext_array) > 1)
	 $output = implode("\n", $fixedtext_array);
	else
	 $output = $fixedtext_array[0];
	return $output;
}

function clean_html_code($uncleanhtml)
{
if(!strstr($uncleanhtml,"<p>"))
{
$uncleanhtml = "<p>".$uncleanhtml;
$uncleanhtml = str_replace("<br /><br />","</p><p>",$uncleanhtml);
}

	//Set wanted indentation
	$uncleanhtml = strip_tags($uncleanhtml,"<p>");
	$indent = "    ";


	//Uses previous function to seperate tags
	$fixed_uncleanhtml = fix_newlines_for_clean_html($uncleanhtml);
	$uncleanhtml_array = explode("\n", $fixed_uncleanhtml);
	//Sets no indentation
	$indentlevel = 0;
	foreach ($uncleanhtml_array as $uncleanhtml_key => $currentuncleanhtml)
	{
		//Removes all indentation
		$currentuncleanhtml = preg_replace("/\t+/", "", $currentuncleanhtml);
		$currentuncleanhtml = preg_replace("/^\s+/", "", $currentuncleanhtml);
		
		$replaceindent = "";
		
		//Sets the indentation from current indentlevel
		for ($o = 0; $o < $indentlevel; $o++)
		{
			$replaceindent .= $indent;
		}
		
		//If self-closing tag, simply apply indent
		if (preg_match("/<(.+)\/>/", $currentuncleanhtml))
		{ 
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
		}
		//If doctype declaration, simply apply indent
		else if (preg_match("/<!(.*)>/", $currentuncleanhtml))
		{ 
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
		}
		//If opening AND closing tag on same line, simply apply indent
		else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml))
		{ 
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
		}
		//If closing HTML tag or closing JavaScript clams, decrease indentation and then apply the new level
		else if (preg_match("/<\/(.*)>/", $currentuncleanhtml) || preg_match("/^(\s|\t)*\}{1}(\s|\t)*$/", $currentuncleanhtml))
		{
			$indentlevel--;
			$replaceindent = "";
			for ($o = 0; $o < $indentlevel; $o++)
			{
				$replaceindent .= $indent;
			}
			
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
		}
		//If opening HTML tag AND not a stand-alone tag, or opening JavaScript clams, increase indentation and then apply new level
		else if ((preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $currentuncleanhtml)) || preg_match("/^(\s|\t)*\{{1}(\s|\t)*$/", $currentuncleanhtml))
		{
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			
			$indentlevel++;
			$replaceindent = "";
			for ($o = 0; $o < $indentlevel; $o++)
			{
				$replaceindent .= $indent;
			}
		}
		else
		//Else, only apply indentation
		{$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;}
	}
	//Return single string seperated by newline
	return implode("\n", $cleanhtml_array);	
}

function roundNumer($number)
{
	if(strstr($number,"."))
	{
		$substr1 = explode(".",$number);
		if(strlen($substr1[1]) == 0)
			$number .= "00";
		elseif(strlen($substr1[1]) == 1)
			$number .= "0";
		elseif(strlen($substr1[1]) > 2)
			$number = $substr1[0].".".substr($substr1[1],0,2);
	}
	else
	{
		$number .= ".00";
	}

return $number;
}
?>
