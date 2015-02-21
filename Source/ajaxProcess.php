<?php
include_once("config.php");
include_once("includes/opendb.php");
echo '{"result" : ';
if(isset($_REQUEST["request"]) && $_REQUEST["request"] == "getCustomerInfo")
{
	$customerID = $_REQUEST["customer"];
	$sql = "select * from customers where customerID = ".$customerID;
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0)
	{
		echo '[';
		echo '{';
		
		echo '"customerName" : "'.mysql_result($result,0,"customerName").'",';
		echo '"customerPhone1" : "'.mysql_result($result,0,"customerPhone1").'",';
		echo '"customerPhone2" : "'.mysql_result($result,0,"customerPhone2").'",';
		echo '"customerEmail1" : "'.mysql_result($result,0,"customerEmail1").'",';
		echo '"customerEmail2" : "'.mysql_result($result,0,"customerEmail2").'",';
		echo '"postalAddress" : "'.mysql_result($result,0,"postalAddress").'"';
		
		echo '}';
		echo ']';
	}
	else
	{
		echo '"F"';
	}
}

if(isset($_REQUEST["request"]) && $_REQUEST["request"] == "getBilledProduct")
{
	$billID = $_REQUEST["billID"];
	$sql = "select * from billitems where billID = ".$billID;
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0)
	{
		echo '[';
		$i = 0;
		while($rows = mysql_fetch_assoc($result))
		{
			if($i++ > 0)
				echo ",";
			echo json_encode($rows);
		}
		echo ']';
	}
	else
	{
		echo '"F"';
	}
}

if(isset($_REQUEST["request"]) && $_REQUEST["request"] == "getSubCategories")
{
	$categoryID = $_REQUEST["categoryID"];
	$sql = "select * from subcategories where categoryID=".$categoryID." and status=1";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0)
	{
		echo '[';
		$i = 0;
		while($rows = mysql_fetch_assoc($result))
		{
			if($i++ > 0)
				echo ",";
			echo json_encode($rows);
		}
		echo ']';
	}
}

if(isset($_REQUEST["request"]) && $_REQUEST["request"] == "getCategorySaleRpt")
{
	$dateValue = date("j/n/Y",mktime(0, 0, 0, date("m")  , date("d")-30, date("Y")))." to ".date("j/n/Y");
	if(isset($_REQUEST["dateRange"]) && $_REQUEST["dateRange"] != "")
		$dateValue = $_REQUEST["dateRange"];
	$fromDate = date("Y-m-d 00:00:00");
	$toDate = date("Y-m-d 23:59:59");
	try
	{
	if(strpos($dateValue,"to") > 0)
	{
		$split = explode("to",$dateValue);
		$frm_sp = explode("/",trim($split[0]));
		$fromDate = date("Y-m-d 00:00:00",mktime(0, 0, 0, $frm_sp[1]  , $frm_sp[0], $frm_sp[2]));
		$to_sp = explode("/",trim($split[1]));
		$toDate = date("Y-m-d 23:59:59",mktime(0, 0, 0, $to_sp[1]  , $to_sp[0], $to_sp[2]));
	}
	elseif(strpos($dateValue,"/") > 0)
	{
		$frm_sp = explode("/",trim($dateValue));
		$fromDate = date("Y-m-d 00:00:00",mktime(0, 0, 0, $frm_sp[1]  , $frm_sp[0], $frm_sp[2]));
		$toDate = date("Y-m-d 23:59:59",mktime(0, 0, 0, $frm_sp[1]  , $frm_sp[0], $frm_sp[2]));
	}
	
	}
	catch(Exception $e)
	{
		$fromDate = date("Y-m-d 00:00:00");
		$toDate = date("Y-m-d 23:59:59");
	}
	
	$date = " a.billID in (select billID from bills where billDate >= '".$fromDate."' and billDate <= '".$toDate."') ";
	
	$result_cat = mysql_query("select sum(a.Quantity)data,(select categoryName from categories where categoryID=c.categoryID)name from billitems as a,products as b,subcategories as c where b.productID = a.productID and c.subCategoryID=b.subCategoryID and ".$date." group by c.categoryID order by data desc limit 0,5");
	$result_total = mysql_query("select sum(a.Quantity)total from billitems as a where ".$date);
	$others = mysql_result($result_total,0,"total");
	if(mysql_num_rows($result_cat) > 0)
	{
		echo '[';
		$i = 0;
		while($row_cat = mysql_fetch_assoc($result_cat)) {
			if($i++ > 0)
				echo ",";
			echo json_encode($row_cat);
			$others = $others - $row_cat["data"];
		}
		if($others > 0) {
         echo ',{"data":"'.$others.'","name":"Others"}';
        }
		echo ']';
	}
	else
	{
		echo '"F"';
	}
}

if(isset($_REQUEST["request"]) && $_REQUEST["request"] == "getRevenueRpt")
{
	$dateValue = date("j/n/Y",mktime(0, 0, 0, date("m")  , date("d")-30, date("Y")))." to ".date("j/n/Y");
	if(isset($_REQUEST["dateRange"]) && $_REQUEST["dateRange"] != "")
		$dateValue = $_REQUEST["dateRange"];
	$fromDate = date("Y-m-d 00:00:00");
	$toDate = date("Y-m-d 23:59:59");
	try
	{
	if(strpos($dateValue,"to") > 0)
	{
		$split = explode("to",$dateValue);
		$frm_sp = explode("/",trim($split[0]));
		$fromDate = date("Y-m-d 00:00:00",mktime(0, 0, 0, $frm_sp[1]  , $frm_sp[0], $frm_sp[2]));
		$to_sp = explode("/",trim($split[1]));
		$toDate = date("Y-m-d 23:59:59",mktime(0, 0, 0, $to_sp[1]  , $to_sp[0], $to_sp[2]));
	}
	elseif(strpos($dateValue,"/") > 0)
	{
		$frm_sp = explode("/",trim($dateValue));
		$fromDate = date("Y-m-d 00:00:00",mktime(0, 0, 0, $frm_sp[1]  , $frm_sp[0], $frm_sp[2]));
		$toDate = date("Y-m-d 23:59:59",mktime(0, 0, 0, $frm_sp[1]  , $frm_sp[0], $frm_sp[2]));
	}
	
	}
	catch(Exception $e)
	{
		$fromDate = date("Y-m-d 00:00:00");
		$toDate = date("Y-m-d 23:59:59");
	}
	
	$date = " billDate >= '".$fromDate."' and billDate <= '".$toDate."' ";
	
	$result_cat = mysql_query("select sum(a.rateBeforeTax)data,DATE(billDate)name from bills as a where ".$date." group by DATE(billDate) order by name");
	if(mysql_num_rows($result_cat) > 0)
	{
		echo '[';
		$i = 0;
		while($row_cat = mysql_fetch_assoc($result_cat)) {
			if($i++ > 0)
				echo ",";
			echo json_encode($row_cat);
		}
		echo '],';
		echo '"startDate":"'.$fromDate.'",';
		echo '"endDate":"'.$toDate.'"';
	}
	else
	{
		echo '"F"';
	}
}

if(isset($_REQUEST["request"]) && $_REQUEST["request"] == "getSalesRpt")
{
	$dateValue = date("j/n/Y",mktime(0, 0, 0, date("m")  , date("d")-30, date("Y")))." to ".date("j/n/Y");
	if(isset($_REQUEST["dateRange"]) && $_REQUEST["dateRange"] != "")
		$dateValue = $_REQUEST["dateRange"];
	$fromDate = date("Y-m-d 00:00:00");
	$toDate = date("Y-m-d 23:59:59");
	try
	{
	if(strpos($dateValue,"to") > 0)
	{
		$split = explode("to",$dateValue);
		$frm_sp = explode("/",trim($split[0]));
		$fromDate = date("Y-m-d 00:00:00",mktime(0, 0, 0, $frm_sp[1]  , $frm_sp[0], $frm_sp[2]));
		$to_sp = explode("/",trim($split[1]));
		$toDate = date("Y-m-d 23:59:59",mktime(0, 0, 0, $to_sp[1]  , $to_sp[0], $to_sp[2]));
	}
	elseif(strpos($dateValue,"/") > 0)
	{
		$frm_sp = explode("/",trim($dateValue));
		$fromDate = date("Y-m-d 00:00:00",mktime(0, 0, 0, $frm_sp[1]  , $frm_sp[0], $frm_sp[2]));
		$toDate = date("Y-m-d 23:59:59",mktime(0, 0, 0, $frm_sp[1]  , $frm_sp[0], $frm_sp[2]));
	}
	
	}
	catch(Exception $e)
	{
		$fromDate = date("Y-m-d 00:00:00");
		$toDate = date("Y-m-d 23:59:59");
	}
	
	$date = " billDate >= '".$fromDate."' and billDate <= '".$toDate."' ";
	
	$result_cat = mysql_query("select rateBeforeTax,DATE(billDate)bDate,(rateBeforeTax*taxPercentage/100)tax,billID from bills where ".$date." order by billDate");
	if(mysql_num_rows($result_cat) > 0)
	{
		echo '[';
		$i = 0;
		while($row_cat = mysql_fetch_assoc($result_cat)) {
			if($i++ > 0)
				echo ",";
			echo json_encode($row_cat);
		}
		echo '],';
		echo '"startDate":"'.$fromDate.'",';
		echo '"endDate":"'.$toDate.'"';
	}
	else
	{
		echo '"F"';
	}
}

	echo '}';
include_once("includes/closedb.php");
exit();
?>