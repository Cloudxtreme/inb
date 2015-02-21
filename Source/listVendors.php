<?php
$showSubMenu = true;
$IsListStyling = true;
$pgGroup = "Vendors";
include_once("includes/header.php");
$result = mysql_query("select * from vendor");
if(isset($_REQUEST["msg"]))
	echo "<span style=\"color:red\">".$_REQUEST["msg"]."</span>";
?>
<script type="text/javascript">
function confirmDeletion(name)
{
	return confirm("Are you sure you want to delete \""+name+"\" ?");
}
</script>
<h2>Vendors</h2>
The list of vendors registered in the CRM. You can edit their details by clicking the edit button for the vendor.<br />
<br />

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Vendor Name</th>
			<th>Phone Number</th>
			<th>Email Address</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
    <?php
	while($row=mysql_fetch_array($result))
	{
	?>
    <tr><td><?=$row["vendorName"] ?></td>
    <td><?=$row["vendorPhone1"] ?>&nbsp;&nbsp; <?=$row["vendorPhone2"] ?></td>
    <td><?=$row["vendorEmail1"] ?>&nbsp;&nbsp; <?=$row["vendorEmail2"] ?></td>
    <td><a href="<?=BASEDIR ?>newVendor.php?id=<?=$row["vendorID"]?>"><img src="<?=BASEDIR ?>images/001_45.png" alt="Edit" title="Edit" /></a>&nbsp;&nbsp;<a href="<?=BASEDIR ?>functions.php?action=deleteVendor&id=<?=$row["vendorID"]?>" onclick="return confirmDeletion('<?=$row["vendorName"] ?>')"><img src="<?=BASEDIR ?>images/001_49.png" alt="Delete" title="Delete" /></a></td>
    </tr>
    <?php } ?>
	</tbody>
</table>
<?php
include_once("includes/footer.php");
?>
