<?php
$showSubMenu = true;
$IsListStyling = true;
$pgGroup = "Bills";
include_once("includes/header.php");
$result = mysql_query("select *,(select customerName from customers where customerID=bills.customerID)customer from bills where status=1");
?>
<h2>Bills</h2>
The list of bills available in the CRM. You can edit their details by clicking the edit button.<br />
<br />

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Bill Number</th>
			<th>Bill Date</th>
			<th>Customer Name</th>
            <th>Net Amount</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
    <?php
	while($row=mysql_fetch_array($result))
	{
	?>
    <tr><td><?=$row["billID"] ?></td>
    <td><?=date("d-m-Y",strtotime($row["billDate"])) ?></td>
    <td><?=$row["customer"] ?></td>
    <td align="right"><img src="images/rs.png" alt="Rs." /> <?=$row["NetAmount"] ?></td>
    <td><a href="viewBill.php?billID=<?=$row["billID"] ?>"><img src="images/001_07.png" alt="View" title="View" /></a>&nbsp;&nbsp;<a href="printBill.php?billID=<?=$row["billID"] ?>"><img src="images/001_36.png" alt="Print" title="Print" /></a></td>
    </tr>
    <?php } ?>
	</tbody>
</table>
<?php
include_once("includes/footer.php");
?>
