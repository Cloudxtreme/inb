<?php
$showSubMenu = false;
$DoNotCheckSession = true;
//$pgGroup = "Settings";
include_once("includes/header.php");
//Check for session
if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] != "")
{
	header("Location:home.php");
}
?>
<div style="background-color:#dddddd; border:2px dashed #666666; text-align:left; width:300px; padding:20px">
<form id="loginFrm" name="loginFrm" action="functions.php" method="post">
<h2 style="margin:0px;">Login</h2>Please login using your credentials.<br />
<br />
<?php if(isset($_REQUEST["error"])) { ?>
<span style="color:red"><?php echo $_REQUEST["error"]; ?></span><br/><br/>
<?php } ?>
Username : <input type="text" style="width:200px; padding:3px" name="username" /><br />
<br />
Password : <input type="password" style="width:200px; padding:3px" name="password" /><br />
<br />
<input type="submit" name="btnLogin" value="Login" style="padding:3px 20px; float:right" /><br />

</form>
</div>
<?php
include_once("includes/footer.php");
?>
