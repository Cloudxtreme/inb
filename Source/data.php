<?php
/*
 * AUSU jQuery-Ajax Autosuggest v1.0
 * Demo of a simple server-side request handler
 * Note: This is a very cumbersome code and should only be used as an example
 */

# Establish DB Connection
include_once("config.php");
include_once("includes/opendb.php");

# Assign local variables
$id     =   @$_POST['id'];          // The id of the input that submitted the request.
$data   =   @$_POST['data'];  // The value of the textbox.
$idSmall = "";
$rowID = "0";
if(strstr($id,"-"))
{
$id_s = explode("-",$id);
$idSmall = $id_s[0];
$rowID = $id_s[1];
}
if ($id && $data)
{
    if ($id=='customer')
    {
        $query  = "SELECT customerID,CONCAT_WS(' - ',customerName,customerPhone1,customerPhone2) as name
                  FROM customers
                  WHERE (customerName LIKE '%$data%' or customerPhone1 LIKE '%$data%' or customerPhone2 LIKE '%$data%') and status=1
                  LIMIT 5";
				  
		if(strstr($data,"-"))
		{
			$data_sp = explode("-",$data);
			$sqlWhere = "customerName LIKE '%".trim($data_sp[0])."%' ";
			if(sizeof($data_sp) > 1)
				$sqlWhere .= " AND (customerPhone1 LIKE '%".trim($data_sp[1])."%' or customerPhone2 LIKE '%".trim($data_sp[1])."%')";
			if(sizeof($data_sp) > 2)
				$sqlWhere .= " AND (customerPhone1 LIKE '%".trim($data_sp[2])."%' or customerPhone2 LIKE '%".trim($data_sp[2])."%')";
			$query = "SELECT customerID,CONCAT_WS(' - ',customerName,customerPhone1,customerPhone2) as name
                  FROM customers
                  WHERE (".$sqlWhere.") and status=1";
		}
		
        $result = mysql_query($query);

        $dataList = array();

        while ($row = mysql_fetch_array($result))
        {
            $toReturn   = $row['name'];
            $dataList[] = '<li id="' .$row['customerID'] . '"><a href="javascript:updateCustomer()">' . htmlentities($toReturn) . '</a></li>';
        }

        if (count($dataList)>=1)
        {
            $dataOutput = join("\r\n", $dataList);
            echo $dataOutput;
        }
        else
        {
            echo '<li><a href="javascript:void(0)">No Results</a></li>';
        }
    }
    elseif ($idSmall=='ProdCode')
    {
        $query  = "SELECT a.productID,a.uniqueReference,a.productName,b.CPRate,b.SPRate, b.stockID, b.quantity
                  FROM products as a, stocks as b
                  WHERE a.productID=b.productID and a.uniqueReference LIKE '%$data%' and a.status=1
                  LIMIT 10";

        $result = mysql_query($query);

        $dataList = array();

        while ($row = mysql_fetch_array($result))
        {
            $toReturn   = $row['uniqueReference']." @ ".$row['SPRate'];
			$toHide = $row['productID']."~INB~".$row['uniqueReference']."~INB~".$row['productName']."~INB~".$row['CPRate']."~INB~".$row['SPRate']."~INB~".$row['stockID']."~INB~".$row['quantity'];
            $dataList[] = '<li id="' .$toHide . '"><a href="javascript:fetchPdoruct(\''.$rowID.'\',\'' .$toHide . '\',\'' . htmlentities($toReturn) . '\')">' . htmlentities($toReturn) . '</a></li>';
        }

        if (count($dataList)>=1)
        {
            $dataOutput = join("\r\n", $dataList);
            echo $dataOutput;
        }
        else
        {
            echo '<li><a href="javascript:void(0)">No Results</a></li>';
        }
    }
	elseif ($idSmall=='ProdName')
    {
        $query  = "SELECT a.productID,a.uniqueReference,a.productName,b.CPRate,b.SPRate, b.stockID, b.quantity
                  FROM products as a, stocks as b
                  WHERE a.productID=b.productID and a.productName LIKE '%$data%' and a.status=1
                  LIMIT 10";

        $result = mysql_query($query);

        $dataList = array();

        while ($row = mysql_fetch_array($result))
        {
            $toReturn   = $row['productName']." @ ".$row['SPRate'];
			$toHide = $row['productID']."~INB~".$row['uniqueReference']."~INB~".$row['productName']."~INB~".$row['CPRate']."~INB~".$row['SPRate']."~INB~".$row['stockID']."~INB~".$row['quantity'];

            $dataList[] = '<li id="' .$toHide . '"><a href="javascript:fetchPdoruct(\''.$rowID.'\',\'' .$toHide . '\',\'' . htmlentities($toReturn) . '\')">' . htmlentities($toReturn) . '</a></li>';
        }

        if (count($dataList)>=1)
        {
            $dataOutput = join("\r\n", $dataList);
            echo $dataOutput;
        }
        else
        {
            echo '<li><a href="javascript:void(0)">No Results</a></li>';
        }
    }
	elseif ($idSmall=='BillCode')
    {	
        $query  = "SELECT billID,customerName,NetAmount
                  FROM bills
                  WHERE (customerName LIKE '%$data%' or billID like '$data%') and archived=0
                  LIMIT 10";

        $result = mysql_query($query);

        $dataList = array();

        while ($row = mysql_fetch_array($result))
        {
            $toReturn   = $row['billID']." - ".$row['customerName']." @ ".$row['NetAmount'];
			$toHide = $row['billID'];
            $dataList[] = '<li id="' .$toHide . '" style="width:250px"><a href="javascript:fetchBilledPdoruct(\''.$rowID.'\',\'' .$toHide . '\',\'' . htmlentities($toReturn) . '\')">' . htmlentities($toReturn) . '</a></li>';
        }

        if (count($dataList)>=1)
        {
            $dataOutput = join("\r\n", $dataList);
            echo $dataOutput;
        }
        else
        {
            echo '<li><a href="javascript:void(0)">No Results</a></li>';
        }
    }
	elseif ($idSmall=='PurProdCode')
    {
        $query  = "SELECT a.productID,a.uniqueReference,a.productName
                  FROM products as a
                  WHERE a.uniqueReference LIKE '%$data%' and a.status=1
                  LIMIT 10";

        $result = mysql_query($query);

        $dataList = array();

        while ($row = mysql_fetch_array($result))
        {
            $toReturn   = $row['uniqueReference'];
			$toHide = $row['productID']."~INB~".$row['uniqueReference']."~INB~".$row['productName'];
            $dataList[] = '<li id="' .$toHide . '"><a href="javascript:fetchPdoruct(\''.$rowID.'\',\'' .$toHide . '\',\'' . htmlentities($toReturn) . '\')">' . htmlentities($toReturn) . '</a></li>';
        }

        if (count($dataList)>=1)
        {
            $dataOutput = join("\r\n", $dataList);
            echo $dataOutput;
        }
        else
        {
            echo '<li><a href="javascript:void(0)">No Results</a></li>';
        }
    }
	elseif ($idSmall=='PurProdName')
    {
        $query  = "SELECT a.productID,a.uniqueReference,a.productName
                  FROM products as a
                  WHERE a.productName LIKE '%$data%' and a.status=1
                  LIMIT 10";

        $result = mysql_query($query);

        $dataList = array();

        while ($row = mysql_fetch_array($result))
        {
            $toReturn   = $row['productName'];
			$toHide = $row['productID']."~INB~".$row['uniqueReference']."~INB~".$row['productName'];

            $dataList[] = '<li id="' .$toHide . '"><a href="javascript:fetchPdoruct(\''.$rowID.'\',\'' .$toHide . '\',\'' . htmlentities($toReturn) . '\')">' . htmlentities($toReturn) . '</a></li>';
        }

        if (count($dataList)>=1)
        {
            $dataOutput = join("\r\n", $dataList);
            echo $dataOutput;
        }
        else
        {
            echo '<li><a href="javascript:void(0)">No Results</a></li>';
        }
    }
}
else
{
    echo 'Request Error';
}
include_once("includes/closedb.php");
?>

