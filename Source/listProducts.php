<?php
$showSubMenu = true;
$IsListStyling = true;
$pgGroup = "Products";
include_once("includes/header.php");
$result = mysql_query("select a.productID,a.productName,a.uniqueReference,(select sum(quantity) from stocks where productID=a.productID)inStock,b.subCategoryName,(select categoryName from categories where categoryID=b.categoryID)categoryName from products as a, subcategories as b where b.subCategoryID=a.subCategoryID and a.status=1");
if(isset($_REQUEST["msg"]))
	echo "<span style=\"color:red\">".$_REQUEST["msg"]."</span>";
?>
<script type="text/javascript">
function confirmDeletion(name)
{
	return confirm("Are you sure you want to delete \""+name+"\" ?");
}
</script>
<h2>Products</h2>
The list of products available in the CRM. You can edit their details by clicking the edit button for the product.<br />
<br />

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>ProdID</th>
			<th>Product Name</th>
			<th>Category</th>
			<th>In Stock</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
    <?php
	while($row=mysql_fetch_array($result))
	{
	?>
    <tr><td><?=$row["uniqueReference"] ?></td>
    <td><?=$row["productName"] ?></td>
    <td><?=$row["categoryName"] ?> >> <?=$row["subCategoryName"] ?></td>
    <td align="center"><?=$row["inStock"]!=""?$row["inStock"]:"0" ?></td>
    <td><a href="<?=BASEDIR ?>viewProduct.php?productID=<?=$row["productID"]?>"><img src="images/001_07.png" alt="View" title="View" /></a>&nbsp;&nbsp;<a href="<?=BASEDIR ?>newProduct.php?id=<?=$row["productID"]?>"><img src="<?=BASEDIR ?>images/001_45.png" alt="Edit" title="Edit" /></a>&nbsp;&nbsp;<a href="<?=BASEDIR ?>functions.php?action=deleteProduct&id=<?=$row["productID"]?>" onclick="return confirmDeletion('<?=$row["productName"] ?>')"><img src="<?=BASEDIR ?>images/001_49.png" alt="Delete" title="Delete" /></a></td>
    </tr>
    <?php } ?>
	</tbody>
</table>
<?php
include_once("includes/footer.php");
?>
