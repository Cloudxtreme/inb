<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Vendors";
include_once("includes/header.php");
?>
<span style="color:red">
<?php
if(isset($_POST["newVendor"]))
{
	$reid = ($_POST["newVendor"] == "Edit")?"id=".$_POST["vendorID"]."&":"";
	if($_POST["vendorName"] != "" && $_POST["vendorPhone1"] != "")
	{
 		$sql = "insert into vendor (vendorName,vendorPhone1,vendorPhone2,vendorEmail1,vendorEmail2,postalAddress) values ('".htmlentities($_POST["vendorName"],ENT_QUOTES)."','".htmlentities($_POST["vendorPhone1"],ENT_QUOTES)."','".htmlentities($_POST["vendorPhone2"],ENT_QUOTES)."','".htmlentities($_POST["vendorEmail1"],ENT_QUOTES)."','".htmlentities($_POST["vendorEmail1"],ENT_QUOTES)."','".htmlentities(str_replace("\n"," ",str_replace("\r\n"," ",$_POST["postalAddress"])),ENT_QUOTES)."')";
		if($_POST["newVendor"] == "Edit")
		{
	 		$sql = "update vendor set vendorName='".htmlentities($_POST["vendorName"],ENT_QUOTES)."',vendorPhone1='".htmlentities($_POST["vendorPhone1"],ENT_QUOTES)."',vendorPhone2='".htmlentities($_POST["vendorPhone2"],ENT_QUOTES)."',vendorEmail1='".htmlentities($_POST["vendorEmail1"],ENT_QUOTES)."',vendorEmail2='".htmlentities($_POST["vendorEmail2"],ENT_QUOTES)."',postalAddress='".htmlentities(str_replace("\n"," ",str_replace("\r\n"," ",$_POST["postalAddress"])),ENT_QUOTES)."' where vendorID=".$_POST["vendorID"];
		}
		if(!mysql_query($sql))
		{
				echo "hi";
			header("Location:newVendor.php?".$reid."msg=vendor not ".($_POST["newVendor"] == "Edit")?"Edited":"Created"." at this time. Please try again after some time.");
		}
		else
		{
			$ce = ($_POST["newVendor"] == "Edit")?"Edited":"Created";
		 	header("Location:listVendors.php?msg=".$ce ." successfully.");
		}
	}
	else
	{
		header("Location:listVendors.php?".$reid."msg=Please enter all the fields");
	}
}

$isEdit = false;
$resultset;
if(isset($_REQUEST["id"]) && $_REQUEST["id"] != "")
{
	$resultset = mysql_query("select * from vendor where vendorID=".$_REQUEST["id"]);
	if(mysql_num_rows($resultset) > 0)
	{
		$isEdit = true;
	}
	else
		echo "Incorrect request parameter. Switiching to create form.";
}

if(isset($_REQUEST["msg"]))
	echo $_REQUEST["msg"];
?>
</span>
<h2>Create new Vendor</h2>
<form action="newVendor.php" method="post" id="formValidation">
<input type="hidden" name="vendorID" id="vendorID" value="<?=$isEdit?mysql_result($resultset,0,"vendorID"):"" ?>" />
<table class="formTable" cellspacing="2" cellpadding="3">
<tr><td colspan="2"><h4>Vendor Details</h4></td></tr>
 <tr>
      <td>Name : </td>
      <td><input type="text" name="vendorName" id="vendorName" class="validate[required]" value="<?=$isEdit?mysql_result($resultset,0,"vendorName"):"" ?>"/></td>
    </tr>
    <tr>
      <td>Phone / Mobile (1): </td>
      <td><input type="text" name="vendorPhone1" id="vendorPhone1" class="validate[required,custom[phone]]" value="<?=$isEdit?mysql_result($resultset,0,"vendorPhone1"):"" ?>" /></td>
    </tr>
        <tr>
      <td>Phone / Mobile (2): </td>
      <td><input type="text" name="vendorPhone2" id="vendorPhone2" class="validate[custom[phone]]" value="<?=$isEdit?mysql_result($resultset,0,"vendorPhone2"):"" ?>" /></td>
    </tr>
    <tr>
      <td>Email (1): </td>
      <td><input type="text" name="vendorEmail1" id="vendorEmail1" value="<?=$isEdit?mysql_result($resultset,0,"vendorEmail1"):"" ?>" class="validate[custom[email]]" /></td>
    </tr>
        <tr>
      <td>Email (2): </td>
      <td><input type="text" name="vendorEmail2" id="vendorEmail2" value="<?=$isEdit?mysql_result($resultset,0,"vendorEmail2"):"" ?>" class="validate[custom[email]]" /></td>
    </tr>
    <tr>
      <td>Address : </td>
      <td><textarea style="width:350px; height:70px" name="postalAddress" id="postalAddress"><?=$isEdit?mysql_result($resultset,0,"postalAddress"):"" ?></textarea></td>
    </tr>
<tr><td colspan="2"><input type="submit" name="newVendor" id="newVendor" value="<?=$isEdit?"Edit":"Create" ?>" /> <input type="reset" value="Reset" /></td></tr></table></form>
<?php
include_once("includes/footer.php");
?>
