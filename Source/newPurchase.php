<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$IsAutoSuggest = true;
$pgGroup = "Purchases";
include_once("includes/header.php");
$result = mysql_query("select * from settings where id=10");
$VAT = "0";
if(mysql_num_rows($result) > 0)
{
	$VAT = mysql_result($result,0,"value");
	if($VAT == "")
		$VAT = "0";
}
?>

<span style="color:red">
<?php
if(isset($_POST["createNewPurchase"]))
{
	if($_POST["vendorID"] != "" && $_POST["billNo"] != "")
	{
		$result_po = mysql_query("insert into purchasevoucher (vendorID,billNo,totalAmount,discount,taxPercentage) values (".htmlentities($_POST["vendorID"],ENT_QUOTES).",'".htmlentities($_POST["billNo"],ENT_QUOTES)."','".htmlentities($_POST["total"],ENT_QUOTES)."','".htmlentities($_POST["tDiscount"],ENT_QUOTES)."','".htmlentities($_POST["tax"],ENT_QUOTES)."')");
		if(mysql_affected_rows() > 0)
		{
			$POId = mysql_insert_id();
			$sql_billItem = "insert into stocks (productID,vendorID,purchaseID,quantity,CPRate,SPRate) values";
			$values = "";
			$i = 0;
			for($i = 0; $i < sizeof($_POST["PurproductID"]); $i++)
			{
				if($_POST["Quantity"][$i] > 0 && $_POST["SP"][$i] > 0 && $_POST["PurproductID"] != "")
				{
					if($values != "")
						$values .= " , ";
					$values .= "(".$_POST["PurproductID"][$i].",".$_POST["vendorID"].",".$POId.",'".$_POST["Quantity"][$i]."','".$_POST["CP"][$i]."','".$_POST["SP"][$i]."')";
				}
			}
			if($values != "")
			{
				$sql_billItem .= $values;
				$result_billItem = mysql_query($sql_billItem);
			}
			if($i > 0)
			{
				mysql_query("update purchasevoucher set noOfItems=".$i." where purchaseID = ".$POId);
			}
			header("Location:listPO.php?msg=Purchase added successfully.");
		}
		else
		{
			header("Location:newPurchase.php?msg=Error adding data. Please try again.");
		}
	}
	else
	{
		header("Location:newPurchase.php?msg=Please enter all the fields");
	}
		
}
if(isset($_REQUEST["msg"]))
	echo $_REQUEST["msg"];
?>
</span> 
<script>
/* Purchase Related Javascripts */
var productInRows = new Array();

function fetchPdoruct(rowID, data, value){
	if(productInRows[parseInt(rowID)] == undefined && data == "")
	{
		return true;
	}

	var splits = data.split("~INB~");
	if(splits[0]!="" && value != "")
	{
		if(productInRows[parseInt(rowID)] == splits[0])
		{
			return true;
		}
	
		productInRows[parseInt(rowID)] = splits[0];
		$('#PurPID1-'+rowID).val(data);
		$('#PurPID2-'+rowID).val(data);		
		$('#PurProdCode-'+rowID).val(splits[1]);
		$('#PurProdName-'+rowID).val(splits[2]);
		$('#PurproductID-'+rowID).val(splits[0]);
		$('#Quantity-'+rowID).val('1');
		$('#CP-'+rowID).focus();
	}
	else
	{
		clearProductControls(rowID);
	}
	calculateRow(rowID);
}

function clearProductControls(rowID){
		$('#PurPID1-'+rowID).val('');
		$('#PurPID2-'+rowID).val('');	
		$('#PurProdCode-'+rowID).val('');
		$('#PurProdName-'+rowID).val('');
		$('#PurproductID-'+rowID).val('');
		$('#Quantity-'+rowID).val('0');
		$('#CP-'+rowID).val('0.00');
		$('#SP-'+rowID).val('0.00');
		$('#LTotal-'+rowID).val('0.00');
		productInRows[parseInt(rowID)] = undefined;
}

function checkProductKeyPress(rowID){
	if(productInRows[parseInt(rowID)] != undefined)
	{
		productInRows[parseInt(rowID)] = undefined;
		clearProductControls(rowID);
		calculateRow(rowID);
	}
}

function calculateRow(rowID){
	if($('#CP-'+rowID).val() == "")
		$('#CP-'+rowID).val('0.00');
	if($('#SP-'+rowID).val() == "")
		$('#SP-'+rowID).val($('#CP-'+rowID).val());		
	if($('#Quantity-'+rowID).val() == "")
		$('#Quantity-'+rowID).val('0');
		
	if(parseFloat($('#SP-'+rowID).val()) < parseFloat($('#CP-'+rowID).val()))
	{
		$('#SP-'+rowID).attr("title","The selling price is less than the cost price. Please correct");
		$('#SP-'+rowID).addClass("error");
	}
	else
	{
		$('#SP-'+rowID).removeClass("error");
		$('#SP-'+rowID).removeAttr("title"); 
	}
		
	var LineTotal = parseFloat($('#CP-'+rowID).val())*parseInt($('#Quantity-'+rowID).val());
	$('#LTotal-'+rowID).val(roundNumber(LineTotal,2));
	calculateTotal();
}

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	result = " "+result;
	if(result.indexOf(".") ==-1)
		result = result+".00";
	else
	{
		var splt = result.split(".");
		if(splt[1].length == 1)
		{
			result = result+"0";
		}
	}
    result = result.replace(/^\s+|\s+$/g, "");
	return result;
}

function calculateTotal(){
	var total = 0;
	var returnTotal = 0;
	
	if($('#tDiscount').val() == "")
		$('#tDiscount').val('0');

	$('table#productLineItems > tbody > tr').each(function(index) {
		var rowID = $(this).attr("id");
		var splits = rowID.split("-");
		total += parseFloat($('#LTotal-'+splits[1]).val());
	});
	
	$('#total').val(roundNumber(total,2));
	$('#discountValue').val(roundNumber(parseFloat($('#total').val())*(parseFloat($('#tDiscount').val()/100)),2));
	$('#totalADis').val(roundNumber(parseFloat($('#total').val())*(1-(parseFloat($('#tDiscount').val())/100)),2));
	$('#taxValue').val(roundNumber(parseFloat($('#totalADis').val())*(parseFloat($('#tax').val())/100),2));
	$('#net').val(roundNumber(parseFloat($('#totalADis').val())*(1+(parseFloat($('#tax').val())/100)),2));
}

function addNewProduct(){
	var stopCreation = false;
	$('table#productLineItems > tbody > tr').each(function(index) {
		var rowID = $(this).attr("id");
		var splits = rowID.split("-");
		if($('#PurProdCode-'+splits[1]).val() == "")
		{
			stopCreation = true;
		}
	});
	if(stopCreation)
	{
		return true;
	}

	var rowID = $('table#productLineItems > tbody > tr').size() + 1;
	var innerht = '<tr id="r-'+rowID+'"><td><a href="javascript:removeRow(\''+rowID+'\')"><img src="images/001_02.png" alt="Remove" border="0" title="Remove" height="15" /></a></td><td><div class="ausu-suggest" style="width:100px"><input type="text" autocomplete="off"  class="validate[required]" name="PurProdCode[]" id="PurProdCode-'+rowID+'" onkeypress="checkProductKeyPress('+rowID+')" value="" style="width:100px;border:1px solid #999" onblur="fetchPdoruct(\''+rowID+'\',document.getElementById(\'PurPID1-'+rowID+'\').value, this.value)" /><input type="hidden" name="PurPID1-'+rowID+'" id="PurPID1-'+rowID+'" value="" /></div><span style="float:left"> - </span><div class="ausu-suggest" style="width:280px"><input type="text" autocomplete="off"  onblur="fetchPdoruct(\''+rowID+'\',document.getElementById(\'PID2-'+rowID+'\').value, this.value)" onkeypress="checkProductKeyPress('+rowID+')" name="PurProdName[]" id="PurProdName-'+rowID+'" value="" style="width:280;border:1px solid #999" /><input type="hidden" name="PurPID2-'+rowID+'" id="PurPID2-'+rowID+'" value="" /></div></td><td align="right"><input type="hidden" name="PurproductID[]" id="PurproductID-'+rowID+'" value="" /><img src="images/rs.png" alt="Rs." /><input type="text" name="CP[]" onblur="calculateRow('+rowID+')" onkeypress="return isDecimalKey(event)" id="CP-'+rowID+'" value="0.00" style="width:75;text-align:right" /></td><td align="right"><img src="images/rs.png" alt="Rs." /><input type="text" onkeypress="return isDecimalKey(event)" onblur="calculateRow('+rowID+')" name="SP[]" id="SP-'+rowID+'" value="0.00" style="width:75;text-align:right" /></td><td align="center"><input type="text" class="validate[custom[number]]" name="Quantity[]" onkeypress="return isNumberKey(event)" id="Quantity-'+rowID+'" onblur="calculateRow('+rowID+')" value="0"  style="width:35;border:1px solid #999" /></td><td align="right" width="100"><img src="images/rs.png" alt="Rs." /><input type="text" name="LTotal[]" id="LTotal-'+rowID+'" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td></tr>';
				$('table#productLineItems > tbody').append(innerht);
				    $.fn.autosugguest({  
           className: 'ausu-suggest',
          methodType: 'POST',
            minChars: 2,
              rtnIDs: true,
            dataFile: 'data.php'
    });
		alertnateRows();
		$('#ProdCode-'+rowID).focus();
}

function removeRow(rowID){
    $('#r-' + rowID).remove();
	if($('table#productLineItems > tbody > tr').size() <= 0)
	{
		addNewProduct();
		alertnateRows();
	}
	calculateTotal();
}

function isNumberKey(evt) {
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 48 || charCode > 57))
return false;

return true;
}

function isDecimalKey(evt) {
var charCode = (evt.which) ? evt.which : event.keyCode
if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode != 46)
return false;

return true;
}

</script>
<h2>New Purchase</h2>
<form action="newPurchase.php" method="post" id="formValidation">
  <table class="formTable" cellspacing="2" cellpadding="3" width="100%">
    <tr>
      <td>Vendor : </td>
      <td><select name="vendorID" id="vendorID" class="validate[required]" style="width:300px">
      <option value="">Select a vendor</option>
      <?php
	  	$result_vens = mysql_query("select * from vendor where status=1");
		while($row_vens = mysql_fetch_array($result_vens))
		{
	  ?>
      <option value="<?=$row_vens["vendorID"] ?>" <?php if($isEdit){ if($row_vens["vendorID"] == mysql_result($resultset,0,"vendorID")) { ?> selected="selected"<?php } } ?>><?=$row_vens["vendorName"] ?></option>
      <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td>Vendor Bill Number : </td>
      <td>
        <input type="text" name="billNo" id="billNo" class="validate[required]" value="" />
    </tr>
    <tr>
      <td colspan="2"><h4>Purchase Details</h4>
        Materials purchased/returned from the vendor.</td>
    </tr>
    <tr>
      <td colspan="2" style="background-color:#FFF">
<?php /*?>    <div id="tabs">
	<ul>
		<li><a href="#tabs-1">Purchase Products</a></li>
		<li><a href="#tabs-2">Return Goods</a></li>
	</ul>
	<div id="tabs-1"><?php */?>
      
      <table style="font-size:8pt;" width="100%" cellpadding="2" cellspacing="2" id="productLineItems">
          <thead>
            <tr>
              <th>&nbsp;</th>
              <th>Product</th>
              <th>CP Rate</th>
              <th>SP Rate</th>
              <th>Quantity</th>
              <th width="100">Line Total</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
        <a style="float:left" href="javascript:addNewProduct()"><br />
<img src="images/001_01.png" alt="Add" border="0" style="vertical-align:middle" /> Add Another Product</a>
 <?php /*?>    </div>
     <div id="tabs-2">
           <table style="font-size:8pt;" width="100%" cellpadding="2" cellspacing="2" id="billedProductLineItems">
          <thead>
            <tr>
              <th width="17">&nbsp;</th>
              <th>Product</th>
              <th width="100">Rate</th>
              <th  width="75">Discount</th>
              <th width="100">Dis. Rate</th>
              <th width="65">Quantity</th>
              <th width="100">Line Total</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
        <a style="float:left" href="javascript:addNewBillProduct()"><br />
<img src="images/001_01.png" alt="Add" border="0" style="vertical-align:middle" /> Add Another Bill Product</a>
     </div>
     </div><?php */?>
        <table align="right" cellpadding="2" cellspacing="2" style="font-size:8pt;">
          <tr>
            <td align="right" style="border-top:1px solid #000000">Total(A)</td>
            <td width="100" align="right" style="border-top:1px solid #000000"><img src="images/rs.png" alt="Rs." />
              <input type="text" name="total" id="total" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="right">Discount(D) @ <input type="text" name="tDiscount" onkeypress="return isDecimalKey(event)" id="tDiscount" value="0"  style="width:50;border:1px solid #999; text-align:right" onblur="calculateTotal()" />%</td>
            <td width="100" align="right"><img src="images/rs.png" alt="Rs." /> <input type="text" name="discountValue" id="discountValue" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="right" style="border-top:1px solid #000000">Total after Discount(B = A-D)</td>
            <td width="100" align="right" style="border-top:1px solid #000000"><img src="images/rs.png" alt="Rs." />
              <input type="text" name="totalADis" id="totalADis" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="right">Tax(T) @ <input type="text" name="tax" onkeypress="return isDecimalKey(event)" id="tax" value="<?=$VAT ?>"  onblur="calculateTotal()"  style="width:50px;text-align:right" />%</td>
            <td width="100" align="right"><img src="images/rs.png" alt="Rs." /> <input type="text" name="taxValue" id="taxValue" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" />
           </td>
          </tr>
          <tr style="font-size:10pt; font-weight:bold">
            <td align="right" style="border-top:3px solid #000000; border-bottom:3px solid #000000">Net Amount (B+T)</td>
            <td width="100" align="right" style="border-top:3px solid #000000; border-bottom:3px solid #000000"><img src="images/rs.png" alt="Rs." />
              <input type="text" name="net" id="net" value="0.00"  style="width:75;background:inherit; border:none; text-align:right;font-weight:bold" readonly="readonly" /></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" name="createNewPurchase" id="createNewPurchase" value="Generate" />
        <input type="reset" value="Reset" /></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
addNewProduct();
</script>
<?php
include_once("includes/footer.php");
?>
