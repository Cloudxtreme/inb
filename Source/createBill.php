<?php
$showSubMenu = true;
$pgGroup = "Bills";
include_once("includes/header.php");
//print_r($_POST);

$customerID = $_POST["customerID"];

if($customerID == "")
{
	$address = htmlspecialchars(str_replace("\n"," ",str_replace("\r\n"," ",$_POST['postalAddress']))); 
	$insert_customer = "insert into customers (customerName,customerPhone1,customerPhone2,customerEmail1,customerEmail2,postalAddress) values ('".htmlentities($_POST['customerName'], ENT_QUOTES)."','".htmlentities($_POST['customerPhone1'], ENT_QUOTES)."','".htmlentities($_POST['customerPhone2'], ENT_QUOTES)."','".htmlentities($_POST['customerEmail1'], ENT_QUOTES)."','".htmlentities($_POST['customerEmail2'], ENT_QUOTES)."','".$address."')";
	$result_customers = mysql_query($insert_customer);
	if(mysql_affected_rows() > 0)
	{
		$customerID = mysql_insert_id();
	}
}

$sql_bill = "insert into bills (customerName,customerID,rateBeforeTax,taxPercentage,NetAmount,DiscountPercentage,totalAmount,returnAmount,BillAddress,PhoneNumber,billedBy) values ('".htmlentities($_POST["customerName"], ENT_QUOTES)."', ".$customerID.", '".$_POST["totalADis"]."', '".$_POST["tax"]."', '".$_POST["net"]."', '".$_POST["tDiscount"]."', '".$_POST["total"]."', '".$_POST["returnTotal"]."', '".$_POST["postalAddress"]."', '".htmlentities($_POST["customerPhone1"], ENT_QUOTES)."',".$_SESSION["user_id"].")";
mysql_query($sql_bill);
if(mysql_affected_rows() > 0)
{
	$billID = mysql_insert_id();
	$sql_billItem = "insert into billitems (billID,productID,stockID,Rate,individualDiscountPercentage,Quantity,LineTotal,ProductName) values";
	$values = "";
	for($i = 0; $i < sizeof($_POST["productID"]); $i++)
	{
		if($_POST["Quantity"][$i] > 0)
		{
			if($values != "")
				$values .= " , ";
			$values .= "(".$billID.",".$_POST["productID"][$i].",".$_POST["stockID"][$i].",'".$_POST["Rate"][$i]."','".$_POST["Discount"][$i]."','".$_POST["Quantity"][$i]."','".$_POST["LTotal"][$i]."','".htmlentities($_POST["ProdName"][$i], ENT_QUOTES)."')";
			mysql_query("update stocks set quantity=quantity-".$_POST["Quantity"][$i]." where stockID=".$_POST["stockID"][$i]);
		}
	}
	if($values != "")
	{
		$sql_billItem .= $values;
		$result_billItem = mysql_query($sql_billItem);
	}
	
	$sql_RbillItem = "insert into returnbill (LineItemID,Quantity,returnedBillID) values";
	$Rvalues = "";
	for($i = 0; $i < sizeof($_POST["billLineItemID"]); $i++)
	{
		if($_POST["RQuantity"][$i] > 0)
		{
		if($Rvalues != "")
			$Rvalues .= " , ";
		$Rvalues .= "(".$_POST["billLineItemID"][$i].",".$_POST["RQuantity"][$i].",".$billID.")";
		mysql_query("update stocks set quantity=quantity+".$_POST["RQuantity"][$i]." where stockID=".$_POST["RStockID"][$i]);
		}
	}
	if($Rvalues != "")
	{
		$sql_RbillItem .= $Rvalues;
		$result_RbillItem = mysql_query($sql_RbillItem);
	}
	header("Location:printBill.php?billID=".$billID);
}
else
{
	echo "Failed to print the bill, please try again by refreshing the page.";
}
?>
<?php
include_once("includes/footer.php");
?>
