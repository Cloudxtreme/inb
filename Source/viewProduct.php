<?php
$showSubMenu = true;
$IsListStyling = true;
$pgGroup = "Products";
include_once("includes/header.php");
$result_prod = mysql_query("select a.productID,a.Description,a.productName,a.uniqueReference,(select sum(quantity) from stocks where productID=a.productID)inStock,b.subCategoryName,(select categoryName from categories where categoryID=b.categoryID)categoryName from products as a, subcategories as b where b.subCategoryID=a.subCategoryID and a.productID=".$_REQUEST["productID"]." and a.status=1");
if(mysql_num_rows($result_prod) > 0)
{
$result = mysql_query("select *,(select vendorName from vendor where vendorID=stocks.vendorID)vendorName from stocks where productID=".$_REQUEST["productID"]." order by quantity desc");
if(isset($_REQUEST["msg"]))
	echo "<span style=\"color:red\">".$_REQUEST["msg"]."</span>";
?>
<h2><?=html_entity_decode(mysql_result($result_prod,0,"productName")) ?> <a href="newProduct.php?id=<?=mysql_result($result_prod,0,"productID") ?>"><img src="images/001_45.png" alt="Edit" title="Edit"  /></a></h2>
<?=html_entity_decode(nl2br(mysql_result($result_prod,0,"Description"))) ?><br />
Category : <?=html_entity_decode(mysql_result($result_prod,0,"categoryName")) ?><br />
Sub Category : <?=html_entity_decode(mysql_result($result_prod,0,"subCategoryName")) ?><br /><br/>
<h3>Total Quantity: <em><?=html_entity_decode(mysql_result($result_prod,0,"inStock")) ?></em></h3>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th width="100">Batch Date</th>
			<th>Vendor</th>
			<th width="100">CP Rate</th>
			<th width="100">SP Rate</th>
			<th width="75">Quantity</th>
		</tr>
	</thead>
	<tbody>
    <?php
	while($row=mysql_fetch_array($result))
	{
	?>
    <tr><td><?=date("d-m-Y",strtotime($row["arrivedOn"])) ?></td>
    <td><?=$row["vendorName"] ?></td>
    <td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer($row["CPRate"]) ?></td>
    <td align="right"><img src="images/rs.png" alt="Rs." /><?=roundNumer($row["SPRate"]) ?></td>
    <td align="center"><?=$row["quantity"] ?></td>
    </tr>
    <?php } ?>
	</tbody>
</table>
<?php } else {?>
Product not found.
<?php } ?>
<?php
include_once("includes/footer.php");
?>
