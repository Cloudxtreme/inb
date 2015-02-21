<?php
$showSubMenu = true;
$IsListStyling = true;
$pgGroup = "Purchases";
include_once("includes/header.php");
$result = mysql_query("select *,(totalAmount*(1-discount/100)*(1+taxPercentage/100))ActualTotal,(select vendorName from vendor where vendorID=purchasevoucher.vendorID)vendorName from purchasevoucher");
if(isset($_REQUEST["msg"]))
	echo "<span style=\"color:red\">".$_REQUEST["msg"]."</span>";
?>
<script type="text/javascript">
function confirmDeletion(name)
{
	return confirm("Are you sure you want to delete \""+name+"\" ?");
}
</script>
<h2>Purchase Orders</h2>
The list of purchase orders available in the CRM. You can edit their details by clicking the edit button.<br />
<br />

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>PO Number</th>
			<th>From Vendor</th>
			<th>No of Items</th>
            <th>Total Amount</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
    <?php
	while($row=mysql_fetch_array($result))
	{
	?>
    <tr><td>PO<?=$row["purchaseID"] ?></td>
    <td><?=$row["vendorName"] ?></td>
    <td><?=$row["noOfItems"] ?></td>
    <td><img src="images/rs.png" alt="Rs." /> <?=roundNumer($row["ActualTotal"])?></td>
    <td><a href="<?=BASEDIR ?>viewPurchase.php?POID=<?=$row["purchaseID"]?>"><img src="images/001_07.png" alt="View" title="View" /></a>&nbsp;&nbsp;<?php /*?><a href="<?=BASEDIR ?>newPurchase.php?id=<?=$row["purchaseID"]?>"><img src="<?=BASEDIR ?>images/001_45.png" alt="Edit" title="Edit" /></a>&nbsp;&nbsp;<a href="<?=BASEDIR ?>functions.php?action=deletePurchase&id=<?=$row["purchaseID"]?>" onclick="return confirmDeletion('Purchase and stock from the vendor, <?=$row["vendorName"] ?>')"><img src="<?=BASEDIR ?>images/001_49.png" alt="Delete" title="Delete" /></a><?php */?></td>
    </tr>
    <?php } ?>
	</tbody>
</table>
<?php
include_once("includes/footer.php");
?>
