<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Bills";
include_once("includes/header.php");
//print_r($_POST);

$billID = $_REQUEST["billID"];
$result_settings = mysql_query("select * from settings order by id");
$result_bill = mysql_query("select * from bills where billID=".$billID);
?>
<table cellspacing="2" cellpadding="3" width="650" style="font-size:8pt; font-family:Verdana, Arial, Helvetica, sans-serif" class="formTable" >
<tr><td align="center" colspan="2" style="border:2px solid #000000; border-left:0px; border-right:0px; border-top:0px"><h2><?php echo mysql_result($result_settings,0,"value"); ?></h2><?php echo mysql_result($result_settings,1,"value"); ?><br />
<?php echo mysql_result($result_settings,4,"value"); ?> || <?php echo mysql_result($result_settings,5,"value"); ?></td></tr>
<tr><td>To:<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="font-size:9pt"><?=mysql_result($result_bill,0,"customerName") ?></b></td><td align="right">Bill #:<?=$billID?><br />
Date : <?=date("d-m-Y",strtotime(mysql_result($result_bill,0,"billDate"))) ?>
</td></tr>
<tr><td style="padding:0px;border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px" colspan="2">
<table width="100%" cellpadding="2" cellspacing="1" style="font-size:10pt">
<tr><th>Item</th><th>Rate</th><th>Discount</th><th>Quantity</th><th>Line Total</th></tr>
<?php
$result_billItems = mysql_query("select * from billitems where billID=".$billID);
while($row_billItems = mysql_fetch_array($result_billItems))
{
?>
	<tr><td width="250"><?=$row_billItems["ProductName"] ?></td><td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer($row_billItems["Rate"]) ?></td><td align="right"><?=$row_billItems["individualDiscountPercentage"] ?>%</td><td width="50" valign="top" align="right"><?=$row_billItems["Quantity"] ?></td><td valign="bottom" align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer($row_billItems["LineTotal"]) ?></td></tr>
<?php } ?>
<?php
$result_rbillItems = mysql_query("select a.*,b.Quantity as Quant from billitems as a,returnbill as b where a.billItemID = b.LineItemID and b.returnedBillID=".$billID);
while($row_rbillItems = mysql_fetch_array($result_rbillItems))
{
?>
	<tr style="color:red"><td><?=$row_rbillItems["ProductName"] ?></td><td align="right"><img src="images/rs_r.png" alt="Rs." /><?=roundNumer($row_rbillItems["Rate"]) ?></td><td align="right"><?=$row_rbillItems["individualDiscountPercentage"] ?>%</td><td width="50" valign="top" align="right">-<?=$row_rbillItems["Quant"] ?></td><td valign="bottom" align="right"><img src="images/rs_r.png" alt="Rs." />-<?=roundNumer($row_rbillItems["Rate"]*$row_rbillItems["Quant"]*(1-$row_rbillItems["individualDiscountPercentage"]/100)) ?></td></tr>
<?php } ?>

<?php if(mysql_result($result_bill,0,"returnAmount") != 0) { ?>
    <tr><td colspan="4" align="right" style="padding-right:15px">Purchased Total</td><td align="right" style="border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px;"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"totalAmount")+mysql_result($result_bill,0,"returnAmount")) ?></td></tr>
    <tr style="color:red"><td colspan="4" align="right" style="padding-right:15px">Returned Total</td><td align="right"><img src="images/rs_r.png" alt="Rs." />-<?=roundNumer(mysql_result($result_bill,0,"returnAmount")) ?></td></tr>
   <?php } ?> 
    
    <tr><td colspan="4" align="right" style="padding-right:15px">Total</td><td align="right" style="border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px;"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"totalAmount")) ?></td></tr>
    
    <?php if(mysql_result($result_bill,0,"DiscountPercentage") != 0) { ?>
    <tr><td colspan="4" align="right" style="padding-right:15px">Amount after discount @ <?=mysql_result($result_bill,0,"DiscountPercentage") ?>%</td><td align="right" style="border:1px solid #000000; border-left:0px; border-right:0px; border-bottom:0px;"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"rateBeforeTax")) ?></td></tr>
    <?php } ?>
    
    <tr><td colspan="4" align="right" style="padding-right:15px;">VAT @ <?=mysql_result($result_bill,0,"taxPercentage") ?>%</td><td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"NetAmount")*mysql_result($result_bill,0,"taxPercentage")/100) ?></td></tr>
  
    <tr><td colspan="4" align="right" style="padding-right:15px; font-weight:bold">Net Amount</td><td align="right"  style="border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px; font-weight:bold"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"NetAmount")) ?></td></tr>    
</table>

</td></tr>
</table>
<?php
include_once("includes/footer.php");
?>
