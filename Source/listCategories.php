<?php
$showSubMenu = true;
$IsListStyling = true;
$pgGroup = "Products";
include_once("includes/header.php");
$result = mysql_query("select categoryID,categoryName from categories where status=1");
if(isset($_REQUEST["msg"]))
	echo "<span style=\"color:red\">".$_REQUEST["msg"]."</span>";
?>
<script type="text/javascript">
function confirmDeletion(name)
{
	return confirm("Are you sure you want to delete \""+name+"\" ?");
}
</script>
<h2>Product Categories</h2>
<span style="float:right"><a href="<?=BASEDIR ?>newCategory.php"><img src="images/001_01.png" border="0" style="vertical-align:middle" /> Add New</a></span>
The list of Product categories. You can edit their details by clicking the edit button.<br />
<br />

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Category Name</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
    <?php
	while($row=mysql_fetch_array($result))
	{
	?>
    <tr> <td><?=$row["categoryName"] ?></td>
   <td><a href="<?=BASEDIR ?>listSubCategories.php?categoryID=<?=$row["categoryID"]?>"><img src="images/001_21.png" alt="View Sub Category" title="View Sub Categories" border="0" /></a>&nbsp;&nbsp;<a href="<?=BASEDIR ?>newCategory.php?id=<?=$row["categoryID"]?>"><img src="<?=BASEDIR ?>images/001_45.png" alt="Edit" title="Edit" /></a>&nbsp;&nbsp;<a href="<?=BASEDIR ?>functions.php?action=deleteCategory&id=<?=$row["categoryID"]?>" onclick="return confirmDeletion('<?=$row["categoryName"] ?>')"><img src="<?=BASEDIR ?>images/001_49.png" alt="Delete" title="Delete" /></a></td>
    </tr>
    </tr>
    <?php } ?>
	</tbody>
</table>
<?php
include_once("includes/footer.php");
?>
