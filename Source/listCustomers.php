<?php
$showSubMenu = true;
$IsListStyling = true;
$pgGroup = "Customers";
include_once("includes/header.php");
$result = mysql_query("select * from customers where status=1");
if(isset($_REQUEST["msg"]))
	echo "<span style=\"color:red\">".$_REQUEST["msg"]."</span>";
?>
<script type="text/javascript">
function confirmDeletion(name)
{
	return confirm("Are you sure you want to delete \""+name+"\" ?");
}
</script>
<h2>Customers</h2>
The list of customers registered in the CRM. You can edit their details by clicking the edit button for the customer.<br />
<br />

<table cellpadding="4" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Customer Name</th>
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
    <tr><td><?=$row["customerName"] ?></td>
    <td><?=$row["customerPhone1"] ?>&nbsp;&nbsp; <?=$row["customerPhone2"] ?></td>
    <td><?=$row["customerEmail1"] ?>&nbsp;&nbsp; <?=$row["customerEmail2"] ?></td>
    <td><a href="<?=BASEDIR ?>newCustomer.php?id=<?=$row["customerID"]?>"><img src="<?=BASEDIR ?>images/001_45.png" alt="Edit" title="Edit" /></a>&nbsp;&nbsp;<a href="<?=BASEDIR ?>functions.php?action=deleteCustomer&id=<?=$row["customerID"]?>" onclick="return confirmDeletion('<?=$row["customerName"] ?>')"><img src="<?=BASEDIR ?>images/001_49.png" alt="Delete" title="Delete" /></a></td>
    </tr>
    <?php } ?>
	</tbody>
</table>
<?php
include_once("includes/footer.php");
?>
