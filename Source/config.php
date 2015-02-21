<?php
session_start();
ob_start();
global $configuration;
date_default_timezone_set("Asia/Calcutta");
//  DataBase Variables declars 
$str = base64_decode(file_get_contents("siteConfigs.php"));
$configs = explode(",",$str);
$kvps;
foreach($configs as $config)
{
	$kvp = explode("#i|b#",$config);
	$kvps[$kvp[0]] = $kvp[1];
}
$configuration['host'] = $kvps["host"];
$configuration['user'] = $kvps["user"];
$configuration['pass'] =$kvps["pass"];
$configuration['db'] = $kvps["db"];
$configuration['timezone'] = '+5:30';

//Generic Definations
define("SITE_NAME", $kvps["SITE_NAME"]);
define("AUTHOR", "Daniel Paul Rajsingh J");
define("SITE_OWNER", "www.daniepaul.com");
define("KEYWORDS", "daniepaul, daniel paul rajsingh, rini, rini esther,, personal site");
define("DESCRIPTION", "Personal site for Daniel and Rini");
define("WEBMASTER","services@daniepaul.com");

//Location Definations
define("BASEDIR","http://localhost/inb/");
//define("BASEDIR","http://192.168.2.3/inb/");
define("FILE_UPLOAD_LOCATION",BASEDIR."uploadFile/");
define("IMAGEPATH",BASEDIR."images/");

//Global Definations
define("FROMEMAILADDRESS","info@daniepaul.com");

define("COPYRIGHTYEAR",$kvps["COPYRIGHTYEAR"]);
define("COPYRIGHTNAME",$kvps["COPYRIGHTNAME"]);

//All messages
include('lib/messages.php');
include('lib/userfunctions.php');
?>