<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Purchases";
include_once("includes/header.php");
//print_r($_POST);

$poID = $_REQUEST["POID"];
$result_po = mysql_query("select a.*,(select vendorName from vendor where vendorID=a.vendorID)vendorName from purchasevoucher as a where a.purchaseID=".$poID);
?>
<h2>View Purchase Order<span><a href="<?=BASEDIR ?>listPO.php"><img src="images/001_23.png" style="vertical-align:middle" border="0" alt="Back to List" title="Back to List"  /></a></h2>
<table cellspacing="2" cellpadding="3" width="650" style="font-size:8pt; font-family:Verdana, Arial, Helvetica, sans-serif" class="formTable" >
<tr><td align="center" colspan="2" style="border:2px solid #000000; border-left:0px; border-right:0px; border-top:0px"><h2><?=mysql_result($result_po,0,"vendorName") ?></h2></td></tr>
<tr><td colspan="2" align="right">Bill #:<?=mysql_result($result_po,0,"billNo")?><br />
Date : <?=date("d-m-Y",strtotime(mysql_result($result_po,0,"arrivedOn"))) ?>
</td></tr>
<tr><td style="padding:0px;border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px" colspan="2">
<table width="100%" cellpadding="2" cellspacing="1" style="font-size:10pt">
<tr><th>Item</th><th>CP</th><th>SP</th><th>Quantity</th><th>Line Total</th></tr>
<?php
$result_poItems = mysql_query("select a.*,(select productName from products where productID=a.productID)productName from stocks as a where a.purchaseID=".$poID);
while($row_billItems = mysql_fetch_array($result_poItems))
{
?>
	<tr><td width="250"><?=$row_billItems["productName"] ?></td><td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer($row_billItems["CPRate"]) ?></td><td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer($row_billItems["SPRate"]) ?></td><td width="50" valign="top" align="right"><?=$row_billItems["quantity"] ?></td><td valign="bottom" align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer($row_billItems["CPRate"]*$row_billItems["quantity"]) ?></td></tr>
<?php } ?>
    <tr><td colspan="4" align="right" style="padding-right:15px">Total</td><td align="right" style="border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px;"><img src="images/rs.png" alt="Rs." /><?=roundNumer($total=mysql_result($result_po,0,"totalAmount")) ?></td></tr>
    
    <?php if(mysql_result($result_po,0,"discount") != 0) { ?>
    <tr><td colspan="4" align="right" style="padding-right:15px">Amount after discount @ <?=mysql_result($result_po,0,"discount") ?>%</td><td align="right" style="border:1px solid #000000; border-left:0px; border-right:0px; border-bottom:0px;"><img src="images/rs.png" alt="Rs." /><?=roundNumer($total = $total*(1-(mysql_result($result_po,0,"discount")/100))) ?></td></tr>
    <?php } ?>
    
    <tr><td colspan="4" align="right" style="padding-right:15px;">Tax @ <?=mysql_result($result_po,0,"taxPercentage") ?>%</td><td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer($tax=$total*mysql_result($result_po,0,"taxPercentage")/100) ?></td></tr>
  
    <tr><td colspan="4" align="right" style="padding-right:15px; font-weight:bold">Net Amount</td><td align="right"  style="border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px; font-weight:bold"><img src="images/rs.png" alt="Rs." /><?=roundNumer($total+$tax) ?></td></tr>    
</table>

</td></tr>
</table>
<?php
include_once("includes/footer.php");
?>
