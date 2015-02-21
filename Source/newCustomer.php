<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Customers";
include_once("includes/header.php");
?>
<span style="color:red">
<?php
if(isset($_POST["newCustomer"]))
{
	$reid = ($_POST["newCustomer"] == "Edit")?"id=".$_POST["customerID"]."&":"";
	if($_POST["customerName"] != "" && $_POST["customerPhone1"] != "")
	{
 		$sql = "insert into customers (customerName,customerPhone1,customerPhone2,customerEmail1,customerEmail2,postalAddress) values ('".htmlentities($_POST["customerName"],ENT_QUOTES)."','".htmlentities($_POST["customerPhone1"],ENT_QUOTES)."','".htmlentities($_POST["customerPhone2"],ENT_QUOTES)."','".htmlentities($_POST["customerEmail1"],ENT_QUOTES)."','".htmlentities($_POST["customerEmail1"],ENT_QUOTES)."','".htmlentities(str_replace("\n"," ",str_replace("\r\n"," ",$_POST["postalAddress"])),ENT_QUOTES)."')";
		if($_POST["newCustomer"] == "Edit")
		{
	 		$sql = "update customers set customerName='".htmlentities($_POST["customerName"],ENT_QUOTES)."',customerPhone1='".htmlentities($_POST["customerPhone1"],ENT_QUOTES)."',customerPhone2='".htmlentities($_POST["customerPhone2"],ENT_QUOTES)."',customerEmail1='".htmlentities($_POST["customerEmail1"],ENT_QUOTES)."',customerEmail2='".htmlentities($_POST["customerEmail2"],ENT_QUOTES)."',postalAddress='".htmlentities(str_replace("\n"," ",str_replace("\r\n"," ",$_POST["postalAddress"])),ENT_QUOTES)."' where customerID=".$_POST["customerID"];
		}
		if(!mysql_query($sql))
		{
				echo "hi";
			header("Location:newCustomer.php?".$reid."msg=Customer not ".($_POST["newCustomer"] == "Edit")?"Edited":"Created"." at this time. Please try again after some time.");
		}
		else
		{
			$ce = ($_POST["newCustomer"] == "Edit")?"Edited":"Created";
		 	header("Location:listCustomers.php?msg=".$ce ." successfully.");
		}
	}
	else
	{
		header("Location:newCustomer.php?".$reid."msg=Please enter all the fields");
	}
}

$isEdit = false;
$resultset;
if(isset($_REQUEST["id"]) && $_REQUEST["id"] != "")
{
	$resultset = mysql_query("select * from customers where customerID=".$_REQUEST["id"]);
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
<h2>Create new customer<span><a href="<?=BASEDIR ?>listCustomers.php"><img src="images/001_23.png" style="vertical-align:middle" border="0" alt="Back to List" title="Back to List"  /></a></span></h2>
<form action="newCustomer.php" method="post" id="formValidation">
<input type="hidden" name="customerID" id="customerID" value="<?=$isEdit?mysql_result($resultset,0,"customerID"):"" ?>" />
<table class="formTable" cellspacing="2" cellpadding="3">
<tr><td colspan="2"><h4>Customer Details</h4>Details of the customer</td></tr>
 <tr>
      <td>Name : </td>
      <td><input type="text" name="customerName" id="customerName" class="validate[required]" value="<?=$isEdit?mysql_result($resultset,0,"customerName"):"" ?>"/></td>
    </tr>
    <tr>
      <td>Phone / Mobile (1): </td>
      <td><input type="text" name="customerPhone1" id="customerPhone1" class="validate[required,custom[phone]]" value="<?=$isEdit?mysql_result($resultset,0,"customerPhone1"):"" ?>" /></td>
    </tr>
        <tr>
      <td>Phone / Mobile (2): </td>
      <td><input type="text" name="customerPhone2" id="customerPhone2" class="validate[custom[phone]]" value="<?=$isEdit?mysql_result($resultset,0,"customerPhone2"):"" ?>" /></td>
    </tr>
    <tr>
      <td>Email (1): </td>
      <td><input type="text" name="customerEmail1" id="customerEmail1" value="<?=$isEdit?mysql_result($resultset,0,"customerEmail1"):"" ?>" class="validate[custom[email]]" /></td>
    </tr>
        <tr>
      <td>Email (2): </td>
      <td><input type="text" name="customerEmail2" id="customerEmail2" value="<?=$isEdit?mysql_result($resultset,0,"customerEmail2"):"" ?>" class="validate[custom[email]]" /></td>
    </tr>
    <tr>
      <td>Address : </td>
      <td><textarea style="width:350px; height:70px" name="postalAddress" id="postalAddress"><?=$isEdit?mysql_result($resultset,0,"postalAddress"):"" ?></textarea></td>
    </tr>
<tr><td colspan="2"><input type="submit" name="newCustomer" id="newCustomer" value="<?=$isEdit?"Edit":"Create" ?>" /> <input type="reset" value="Reset" /></td></tr></table></form>
<?php
include_once("includes/footer.php");
?>
