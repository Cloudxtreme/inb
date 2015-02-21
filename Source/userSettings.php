<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Settings";
include_once("includes/header.php");

if(isset($_POST["updateSiteConfig"]))
{
	$kvps2;
	$kvps2[0] = "host#i|b#".$_POST["dbHost"];
	$kvps2[1] = "user#i|b#".$_POST["dbUser"];
	$kvps2[2] = "pass#i|b#".$_POST["dbPass"];
	$kvps2[3] = "db#i|b#".$_POST["db"];
	$kvps2[4] = "SITE_NAME#i|b#".$_POST["siteTitle"];
	$kvps2[5] = "COPYRIGHTYEAR#i|b#".$_POST["copyYear"];
	$kvps2[6] = "COPYRIGHTNAME#i|b#".$_POST["copyName"];
 	$str2 = base64_encode(implode(",",$kvps2));
	file_put_contents("siteConfigs.php", $str2);
}
?>
<h2>Site Settings</h2>
<form id="formValidation" action="siteSettings.php" method="post">
<table class="formTable" cellspacing="2" cellpadding="3">
<tr><td colspan="2"><h4>Database Configurations</h4></td></tr>
<tr><td>Host</td><td><input type="text" name="dbHost" id="dbHost" value="<?=$kvps["host"]?>" /></td></tr>
<tr><td>Useername</td><td><input type="text" name="dbUser" id="dbUser" value="<?=$kvps["user"]?>" /></td></tr>
<tr><td>Password</td><td><input type="password" name="dbPass" id="dbPass" value="<?=$kvps["pass"]?>" /></td></tr>
<tr><td>Database Name</td><td><input type="text" name="db" id="db" value="<?=$kvps["db"]?>" /></td></tr>
<tr><td colspan="2"><h4>Site Configurations</h4></td></tr>
<tr><td>Site Title</td><td><input type="text" name="siteTitle" id="siteTitle" value="<?=$kvps["SITE_NAME"]?>" /></td></tr>
<tr><td>Copyright year</td><td><input type="text" name="copyYear" id="copyYear" value="<?=$kvps["COPYRIGHTYEAR"]?>" /></td></tr>
<tr><td>Copyright Name</td><td><input type="text" name="copyName" id="copyName" value="<?=$kvps["COPYRIGHTNAME"]?>" /></td></tr
><tr><td colspan="2"><input type="submit" name="createNewUser" id="createNewUser" value="Create" /> <input type="reset" value="Reset" /></td></tr></table></form>
<?php
include_once("includes/footer.php");
?>
