<?php
include_once("config.php");
include_once("includes/opendb.php");
if(isset($_POST["btnLogin"]) && $_POST["btnLogin"] == "Login")
{
	$uName = $_POST["username"];
	$pWord = $_POST["password"];
	$sql = "select * from users where username='".$uName."' and password='".$pWord."' and status=1";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0)
	{
		$accessKeys = array(
			"Customers" => false,
			"Vendors" => false,
			"Products" => false,
			"Purchases" => false,
			"Bills" => false,
			"Reports" => false,
			"Settings" => false,
			"canCreateUser" => false,
		);
		if(mysql_result($result,0,"loginType") == "SUPER")
		{
			$_SESSION["superAdmin"] = true;
			$accessKeys = array(
				"Customers" => true,
				"Vendors" => true,
				"Products" => true,
				"Purchases" => true,
				"Bills" => true,
				"Reports" => true,
				"Settings" => true,
				"canCreateUser" => true,
		);
		}
		else
		{
			$loginTypes = split(";",mysql_result($result,0,"loginType"));
			foreach($loginTypes as $loginType)
			{
				$split_type = split("-",$loginType);
				if($split_type[1]=="1")
					$accessKeys[$split_type[0]] = true;
			}
		}
		$_SESSION["user_accessKeys"] = $accessKeys;
		$_SESSION["user_id"] = mysql_result($result,0,"uid");
		$_SESSION["user_name"] = mysql_result($result,0,"name");
		
		//other initial cleanups
		
		//update return bills
		$resultSettings = mysql_query("select * from settings where id=12");
		$returnDays = 15;
		if(mysql_num_rows($resultSettings) > 0)
			$returnDays = mysql_result($resultSettings,0,"value");
		//echo "update bills set archived=1 where archived=0 and billDate<='".date("Y-m-d 00:00:00",mktime(0, 0, 0, date("m")  , date("d")-$returnDays, date("Y")))."'";
		mysql_query("update bills set archived=1 where archived=0 and billDate<='".date("Y-m-d 00:00:00",mktime(0, 0, 0, date("m")  , date("d")-$returnDays, date("Y")))."'");
		
		header("Location:home.php");
	}
	else
	{
		header("Location:index.php?error=Login Failed. Please try again.");
	}
}

if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] != "")
{
	if($_REQUEST["action"] == "deleteCustomer" && $_REQUEST["id"] != "")
	{
		mysql_query("update customers set status=0 where customerID=".$_REQUEST["id"]);
		if(mysql_affected_rows() > 0)
			header("Location:listCustomers.php?msg=Deleted Successfully");
		else
			header("Location:listCustomers.php?msg=Cannot delete the data. Please try again later.");
	}
	
	if($_REQUEST["action"] == "deleteVendor" && $_REQUEST["id"] != "")
	{
		mysql_query("update vendor set status=0 where vendorID=".$_REQUEST["id"]);
		if(mysql_affected_rows() > 0)
			header("Location:listVendors.php?msg=Deleted Successfully");
		else
			header("Location:listVendors.php?msg=Cannot delete the data. Please try again later.");
	}
	
	if($_REQUEST["action"] == "deleteCategory" && $_REQUEST["id"] != "")
	{
		mysql_query("update categories set status=0 where categoryID=".$_REQUEST["id"]);
		if(mysql_affected_rows() > 0)
			header("Location:listCategories.php?msg=Deleted Successfully");
		else
			header("Location:listCategories.php?msg=Cannot delete the data. Please try again later.");
	}
	
	if($_REQUEST["action"] == "deleteSubCategory" && $_REQUEST["id"] != "")
	{
		mysql_query("update subcategories set status=0 where subCategoryID=".$_REQUEST["id"]);
		if(mysql_affected_rows() > 0)
			header("Location:listSubCategories.php?msg=Deleted Successfully");
		else
			header("Location:listSubCategories.php?msg=Cannot delete the data. Please try again later.");
	}
	
	if($_REQUEST["action"] == "deleteProduct" && $_REQUEST["id"] != "")
	{
		mysql_query("update products set status=0 where productID=".$_REQUEST["id"]);
		if(mysql_affected_rows() > 0)
			header("Location:listProducts.php?msg=Deleted Successfully");
		else
			header("Location:listProducts.php?msg=Cannot delete the data. Please try again later.");
	}
}

include_once("includes/closedb.php");
?>