<?php
$showSubMenu = true;
$IsListStyling = true;
$pgGroup = "Products";
include_once("includes/header.php");
$catID = "";
if(isset($_REQUEST["categoryID"]) && $_REQUEST["categoryID"] != "")
	$catID = " and b.categoryID=".$_REQUEST["categoryID"]." ";
$result = mysql_query("select b.subCategoryID,b.subCategoryName,(select categoryName from categories where categoryID=b.categoryID)categoryName from subcategories as b where b.status=1 and b.categoryID in (select categoryID from categories where status=1)".$catID);
if(isset($_REQUEST["msg"]))
	echo "<span style=\"color:red\">".$_REQUEST["msg"]."</span>";
?>
<script type="text/javascript">
function confirmDeletion(name)
{
	return confirm("Are you sure you want to delete \""+name+"\" ?");
}
</script>
<h2>Product Sub-Categories
<?php if($catID != "") { ?>
<a href="<?=BASEDIR ?>listSubCategories.php"><img src="images/001_39.png" alt="Reset All" title="Reset All" border="0" /></a>
<?php } ?>
</h2>
<span style="float:right"><a href="<?=BASEDIR ?>newSubCategory.php"><img src="images/001_01.png" border="0" style="vertical-align:middle" /> Add New</a></span>
The list of Product categories. You can edit their details by clicking the edit button.<br />
<br />

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Sub-Category Name</th>
            <th>Category Name</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
    <?php
	while($row=mysql_fetch_array($result))
	{
	?>
    <tr><td><?=$row["subCategoryName"] ?></td>
    <td><?=$row["categoryName"] ?></td>
     <td><a href="<?=BASEDIR ?>newSubCategory.php?id=<?=$row["subCategoryID"]?>"><img src="<?=BASEDIR ?>images/001_45.png" alt="Edit" title="Edit" /></a>&nbsp;&nbsp;<a href="<?=BASEDIR ?>functions.php?action=deleteSubCategory&id=<?=$row["subCategoryID"]?>" onclick="return confirmDeletion('<?=$row["subCategoryName"] ?>')"><img src="<?=BASEDIR ?>images/001_49.png" alt="Delete" title="Delete" /></a></td>
    </tr>
    <?php } ?>
	</tbody>
</table>
<?php
include_once("includes/footer.php");
?>
