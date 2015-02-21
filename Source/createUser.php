<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Settings";
include_once("includes/header.php");
?>
<span style="color:red">
<?php
  //Check for session
if(!$_SESSION["user_accessKeys"]["canCreateUser"])
{
?>
<h1>No Access</h1>
You are not authorized to view this page.
<?php
include_once("includes/footer.php");
die("");
}

if(isset($_POST["createNewUser"]))
{
	if($_POST["username"] != "" && $_POST["password"] != "")
	{
		$accessTypes = implode(";",$_POST["loginType"]);
 		$sql = "insert into users (name,username,password,email,loginType) values ('".htmlentities($_POST["name"],ENT_QUOTES)."','".htmlentities($_POST["username"],ENT_QUOTES)."','".htmlentities($_POST["password"],ENT_QUOTES)."','".htmlentities($_POST["email"],ENT_QUOTES)."','".htmlentities($accessTypes,ENT_QUOTES)."')";
		if(!mysql_query($sql))
		{
			header("Location:createUser.php?msg=User not created at this time. Please try again after some time.");
		}
		else
		{
		 	header("Location:createUser.php?msg=User created successfully.");
		}
	}
	else
	{
		header("Location:createUser.php?msg=Please enter all the fields");
	}
}
if(isset($_REQUEST["msg"]))
	echo $_REQUEST["msg"];
?>
</span>
<h2>Create new user</h2>
<form action="createUser.php" method="post" id="formValidation">
<table class="formTable" cellspacing="2" cellpadding="3">
<tr><td colspan="2"><h4>User Settings</h4>Basic user details</td></tr>
<tr><td>Name</td><td><input type="text" name="name" id="name" value="" class="validate[required]" /></td></tr>
<tr><td>Username</td><td><input type="text" name="username" id="username" value="" class="validate[required]" /></td></tr>
<tr><td>Password</td><td><input type="text" name="password" id="password" value="" class="validate[required]" /></td></tr>
<tr><td>Email Address</td><td><input type="text" name="email" id="email" value="" class="validate[custom[email]]" /></td></tr>
<tr><td colspan="2"><h4>Access Configurations</h4>Restrict user access to specific sections of the site</td></tr>
<tr><td colspan="2"><input type="checkbox" class="validate[minCheckbox[1]]" name="loginType[]" id="Customers" value="Customers-1"  /> Customer Section<br />
<input type="checkbox" class="validate[minCheckbox[1]]" name="loginType[]" id="Vendors" value="Vendors-1"  /> Vendor Section<br />
<input type="checkbox" class="validate[minCheckbox[1]]" name="loginType[]" id="Products" value="Products-1"  /> Product Section<br />
<input type="checkbox" class="validate[minCheckbox[1]]" name="loginType[]" id="Purchases" value="Purchases-1"  /> Purchase Section<br />
<input type="checkbox" class="validate[minCheckbox[1]]" name="loginType[]" id="Bills" value="Bills-1"  /> Bill Section<br />
<input type="checkbox" class="validate[minCheckbox[1]]" name="loginType[]" id="Reports" value="Reports-1"  /> Reports Section<br />
<input type="checkbox" class="validate[minCheckbox[1]]" name="loginType[]" id="Settings" value="Settings-1"  /> Settings Section<br />
</td></tr>
<tr><td colspan="2"><br />
<input type="checkbox" class="validate[minCheckbox[1]]" name="loginType[]" id="canCreateUser" value="canCreateUser-1"  /> User can create new users<br />
<br />
</td></tr>
<tr><td colspan="2"><input type="submit" name="createNewUser" id="createNewUser" value="Create" /> <input type="reset" value="Reset" /></td></tr></table></form>
<?php
include_once("includes/footer.php");
?>
