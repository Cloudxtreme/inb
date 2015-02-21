<?php
$showSubMenu = true;

$pgGroup = "Bills";
include_once("includes/header.php");
//print_r($_POST);

$billID = $_REQUEST["billID"];
$result_settings = mysql_query("select * from settings order by id");
$result_bill = mysql_query("select * from bills where billID=".$billID);
?>
<table cellspacing="0" cellpadding="3" width="350" style="font-size:8pt; font-family:Verdana, Arial, Helvetica, sans-serif">
<tr><td align="center" colspan="2" style="border:2px solid #000000; border-left:0px; border-right:0px; border-top:0px"><h2><?php echo mysql_result($result_settings,0,"value"); ?></h2><?php echo mysql_result($result_settings,1,"value"); ?><br />
<?php echo mysql_result($result_settings,4,"value"); ?> || <?php echo mysql_result($result_settings,5,"value"); ?></td></tr>
<tr><td>To:<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="font-size:9pt"><?=mysql_result($result_bill,0,"customerName") ?></b></td><td align="right">Bill #:<?=$billID?><br />
Date : <?=date("d-m-Y",strtotime(mysql_result($result_bill,0,"billDate"))) ?>
</td></tr>
<tr><td style="padding:0px;border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px" colspan="2">
<table width="100%" cellpadding="0" cellspacing="0" style="font-size:10pt">
<?php
$result_billItems = mysql_query("select * from billitems where billID=".$billID);
while($row_billItems = mysql_fetch_array($result_billItems))
{
?>
	<tr><td width="25" valign="top"><?=$row_billItems["Quantity"] ?></td><td><div style="overflow:hidden; width:250px;"><?=$row_billItems["ProductName"] ?></div>@<img src="images/rs.png" alt="Rs." /><?=roundNumer($row_billItems["Rate"]) ?> & discount @<?=$row_billItems["individualDiscountPercentage"] ?>%</td><td valign="bottom" align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer($row_billItems["LineTotal"]) ?></td></tr>
    <tr><td colspan="3" height="8" style="font-size:1px;">&nbsp;</td></tr>
<?php } ?>
<?php
$result_rbillItems = mysql_query("select a.*,b.Quantity as Quant from billitems as a,returnbill as b where a.billItemID = b.LineItemID and b.returnedBillID=".$billID);
while($row_rbillItems = mysql_fetch_array($result_rbillItems))
{
?>
	<tr><td width="25" valign="top">-<?=$row_rbillItems["Quant"] ?></td><td><div style="overflow:hidden; width:250px;"><?=$row_rbillItems["ProductName"] ?></div>@<img src="images/rs.png" alt="Rs." /><?=roundNumer($row_rbillItems["Rate"]) ?> & discount @<?=$row_rbillItems["individualDiscountPercentage"] ?>%</td><td valign="bottom" align="right"><img src="images/rs.png" alt="Rs." />-<?=roundNumer($row_rbillItems["Rate"]*$row_rbillItems["Quant"]*(1-$row_rbillItems["individualDiscountPercentage"]/100)) ?></td></tr>
    <tr><td colspan="3" height="8" style="font-size:1px;">&nbsp;</td></tr>
<?php } ?>

<?php if(mysql_result($result_bill,0,"returnAmount") != 0) { ?>
    <tr><td colspan="2" height="8" style="font-size:1px;">&nbsp;</td><td align="right" style="border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px;font-size:1px;">&nbsp;</td></tr>
    <tr><td colspan="2" align="right" style="padding-right:15px">Purchased Total</td><td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"totalAmount")+mysql_result($result_bill,0,"returnAmount")) ?></td></tr>
    <tr><td colspan="2" align="right" style="padding-right:15px">Returned Total</td><td align="right"><img src="images/rs.png" alt="Rs." />-<?=roundNumer(mysql_result($result_bill,0,"returnAmount")) ?></td></tr>
   <?php } ?> 
    
    <tr><td colspan="2" height="8" style="font-size:1px;">&nbsp;</td><td align="right" style="border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px;font-size:1px;">&nbsp;</td></tr>
    <tr><td colspan="2" align="right" style="padding-right:15px">Total</td><td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"totalAmount")) ?></td></tr>
    
    <?php if(mysql_result($result_bill,0,"DiscountPercentage") != 0) { ?>
        <tr><td colspan="2" height="8" style="font-size:1px;">&nbsp;</td><td align="right" style="border:1px solid #000000; border-left:0px; border-right:0px; border-bottom:0px;font-size:1px;">&nbsp;</td></tr>
    <tr><td colspan="2" align="right" style="padding-right:15px">Amount after discount @ <?=mysql_result($result_bill,0,"DiscountPercentage") ?>%</td><td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"rateBeforeTax")) ?></td></tr>
    <?php } ?>
    
    <tr><td colspan="2" align="right" style="padding-right:15px;">VAT @ <?=mysql_result($result_bill,0,"taxPercentage") ?>%</td><td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"NetAmount")*mysql_result($result_bill,0,"taxPercentage")/100) ?></td></tr>
    <tr><td colspan="2" height="8" style="font-size:1px;">&nbsp;</td><td align="right" style="border:2px solid #000000; border-left:0px; border-right:0px; border-bottom:0px;font-size:1px;">&nbsp;</td></tr>    
    <tr><td colspan="2" align="right" style="padding-right:15px; font-weight:bold">Net Amount</td><td align="right" style="font-weight:bold"><img src="images/rs.png" alt="Rs." /><?=roundNumer(mysql_result($result_bill,0,"NetAmount")) ?></td></tr>    
</table>

</td></tr>
</table>
<?php
include_once("includes/footer.php");
?>
