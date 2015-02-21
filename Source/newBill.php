<?php
$showSubMenu = false;
$IsAlternateTableStyle = true;
$IsAutoSuggest = true;
$pgGroup = "Bills";
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
if(isset($_REQUEST["msg"]))
	echo $_REQUEST["msg"];
?>
</span> 
<script>
/* Customer Related Javascripts */
var detafetched = false;
function updateCustomer(customerID){
	if(customerID != "" && $('#customer').val() != "")
	{
		$.post('ajaxProcess.php?customer='+customerID, { "request":"getCustomerInfo"},
		  function(data){
			   if(data["result"] != "F")
			   {
					$('#customerName').val(data["result"][0]["customerName"]);
					$('#customerPhone1').val(data["result"][0]["customerPhone1"]);
					$('#customerPhone2').val(data["result"][0]["customerPhone2"]);
					$('#customerEmail1').val(data["result"][0]["customerEmail1"]);
					$('#customerEmail2').val(data["result"][0]["customerEmail2"]);
					$('#postalAddressDisp').html(data["result"][0]["postalAddress"]);
					
					$('#postalAddress').css("display","none");
					$('#postalAddressDisp').css("display","inherit");
					
					$('#customerName').attr("readonly", "readonly");
					$('#customerPhone1').attr("readonly", "readonly");
					$('#customerPhone2').attr("readonly", "readonly");
					$('#customerEmail1').attr("readonly", "readonly");
					$('#customerEmail2').attr("readonly", "readonly");
					$('#postalAddress').attr("readonly", "readonly");
					detafetched = true
			   }
			   else
			   {
				   alert("Failed to fetch customer details. Please try again.");
			   }
			 },"json");
	}
	else
	{
		clearCustomerControls();
		detafetched = false;
	}
}

function CustomerKeyPress(){
	if(detafetched)
	{
			detafetched = false;
			clearCustomerControls();
	}
}

function clearCustomerControls(){
		$('#customerid').val('');
		$('#customerName').val('');
		$('#customerPhone1').val('');
		$('#customerPhone2').val('');
		$('#customerEmail1').val('');
		$('#customerEmail2').val('');
		$('#postalAddress').val('');
		
		$('#customerName').removeAttr("readonly"); 
		$('#customerPhone1').removeAttr("readonly"); 
		$('#customerPhone2').removeAttr("readonly"); 
		$('#customerEmail1').removeAttr("readonly"); 
		$('#customerEmail2').removeAttr("readonly"); 
		$('#postalAddress').removeAttr("readonly"); 
		
				$('#postalAddressDisp').css("display","none");
				$('#postalAddress').css("display","inherit");
}

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
		$('#PID1-'+rowID).val(data);
		$('#PID2-'+rowID).val(data);		
		$('#ProdCode-'+rowID).val(splits[1]);
		$('#ProdName-'+rowID).val(splits[2]);
		$('#Rate-'+rowID).val(roundNumber(splits[4],2));
		$('#Quantity-'+rowID).val('1');
		$('#Discount-'+rowID).val('0');
		$('#DRate-'+rowID).val(roundNumber(splits[4],2));
		$('#CP-'+rowID).val(splits[3]);
		$('#stockID-'+rowID).val(splits[5]);
		$('#stockQuantity-'+rowID).val(splits[6]);
		$('#productID-'+rowID).val(splits[0]);
		$('#Quantity-'+rowID).focus();
	}
	else
	{
		clearProductControls(rowID);
	}
	calculateRow(rowID);
}

function clearProductControls(rowID){
		$('#PID1-'+rowID).val('');
		$('#PID2-'+rowID).val('');	
		$('#ProdCode-'+rowID).val('');
		$('#ProdName-'+rowID).val('');
		$('#Rate-'+rowID).val('0.00');
		$('#Quantity-'+rowID).val('0');
		$('#Discount-'+rowID).val('0');
		$('#DRate-'+rowID).val('0.00');
		$('#LTotal-'+rowID).val('0.00');
		$('#CP-'+rowID).val('0.00');
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
	if($('#Discount-'+rowID).val() == "")
		$('#Discount-'+rowID).val('0');
	if($('#Quantity-'+rowID).val() == "")
		$('#Quantity-'+rowID).val('0');
		
	var inStockQuantity = parseInt($('#stockQuantity-'+rowID).val());
	var purchasedQuantity = parseInt($('#Quantity-'+rowID).val());
	
	if(inStockQuantity < purchasedQuantity)
	{
		alert("Your request is more than available in stock. Quantity reduced to maximum available in stock");
		$('#Quantity-'+rowID).val($('#stockQuantity-'+rowID).val());
	}
		
	var discountRate = parseFloat($('#Rate-'+rowID).val())*(1-(parseFloat($('#Discount-'+rowID).val())/100));
	$('#DRate-'+rowID).val(roundNumber(discountRate,2));
	var costPr = parseFloat($('#CP-'+rowID).val());
	if(discountRate < costPr)
	{
		$('#DRate-'+rowID).addClass("error");
		$('#DRate-'+rowID).attr("title","The discount is very high. Please reduce the discount");
	}
	else
	{
		$('#DRate-'+rowID).removeClass("error");
		$('#DRate-'+rowID).removeAttr("title"); 
	}
	var LineTotal = parseFloat($('#DRate-'+rowID).val())*parseInt($('#Quantity-'+rowID).val());
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
	
	$('table#billedProductLineItems > tbody > tr').each(function(index) {
		var rowID = $(this).attr("id");
		var splits = rowID.split("-");
		returnTotal += parseFloat($('#RLTotal-'+splits[1]).val());
	});
	
	$('#purchaseTotal').val(roundNumber(total,2));
	$('#returnTotal').val(roundNumber(returnTotal,2));
	$('#total').val(roundNumber(total-returnTotal,2));
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
		if($('#ProdCode-'+splits[1]).val() == "")
		{
			stopCreation = true;
		}
	});
	if(stopCreation)
	{
		return true;
	}

	var rowID = $('table#productLineItems > tbody > tr').size() + 1;
	var innerht = '<tr id="r-'+rowID+'"><td><a href="javascript:removeRow(\''+rowID+'\')"><img src="images/001_02.png" alt="Remove" border="0" title="Remove" height="15" /></a></td><td><div class="ausu-suggest" style="width:100px"><input type="text" autocomplete="off"  class="validate[required]" name="ProdCode[]" id="ProdCode-'+rowID+'" onkeypress="checkProductKeyPress('+rowID+')" value="" style="width:100px;border:1px solid #999" onblur="fetchPdoruct(\''+rowID+'\',document.getElementById(\'PID1-'+rowID+'\').value, this.value)" /><input type="hidden" name="PID1-'+rowID+'" id="PID1-'+rowID+'" value="" /></div><span style="float:left"> - </span><div class="ausu-suggest" style="width:300px"><input type="text" autocomplete="off"  onblur="fetchPdoruct(\''+rowID+'\',document.getElementById(\'PID2-'+rowID+'\').value, this.value)" onkeypress="checkProductKeyPress('+rowID+')" name="ProdName[]" id="ProdName-'+rowID+'" value="" style="width:300;border:1px solid #999" /><input type="hidden" name="PID2-'+rowID+'" id="PID2-'+rowID+'" value="" /></div></td><td align="right"><input type="hidden" name="productID[]" id="productID-'+rowID+'" value="" /><input type="hidden" name="stockID[]" id="stockID-'+rowID+'" value="" /><input type="hidden" name="stockQuantity[]" id="stockQuantity-'+rowID+'" value="" /><img src="images/rs.png" alt="Rs." /><input type="text" name="Rate[]" id="Rate-'+rowID+'" value="0.00" style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /><input type="hidden" name="CP[]" id="CP-'+rowID+'" value="0.00" /></td><td align="center"><input type="text" class="validate[custom[number]]" onkeypress="return isDecimalKey(event)" name="Discount[]" id="Discount-'+rowID+'" value="0"  style="width:50;border:1px solid #999" onblur="calculateRow('+rowID+')" />%</td><td align="right"><img src="images/rs.png" alt="Rs." /><input type="text" name="DRate[]" id="DRate-'+rowID+'" value="0.00" style="width:75;background-color:inherit; border:none; text-align:right" readonly="readonly" /></td><td align="center"><input type="text" class="validate[custom[number]]" name="Quantity[]" onkeypress="return isNumberKey(event)" id="Quantity-'+rowID+'" onblur="calculateRow('+rowID+')" value="0"  style="width:35;border:1px solid #999" /></td><td align="right" width="100"><img src="images/rs.png" alt="Rs." /><input type="text" name="LTotal[]" id="LTotal-'+rowID+'" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td></tr>';
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

/* Returns Related Javascripts */

var returnsInRows = new Array();

function addNewBillProduct(){
	var stopCreation = false;
	$('table#billedProductLineItems > tbody > tr').each(function(index) {
		var rowID = $(this).attr("id");
		var splits = rowID.split("-");
		if($('#BillCode-'+splits[1]).val() == "")
		{
			stopCreation = true;
		}
	});
	if(stopCreation)
	{
		return true;
	}

	var rowID = $('table#productLineItems > tbody > tr').size() + 1;
	var innerht = '<tr id="Rr-'+rowID+'"><td><a href="javascript:removeBilledRow(\''+rowID+'\')"><img src="images/001_02.png" alt="Remove" border="0" title="Remove" height="15" /></a></td><td><div class="ausu-suggest" style="width:100px"><input type="text" autocomplete="off"  class="validate[required]" name="BillCode[]" id="BillCode-'+rowID+'" onkeypress="checkReturnKeyPress('+rowID+')" value="" style="width:100px;border:1px solid #999" onblur="fetchBilledPdoruct(\''+rowID+'\',document.getElementById(\'BillID-'+rowID+'\').value,this.value)" /><input type="hidden" name="BillID-'+rowID+'" id="BillID-'+rowID+'" value="" /></div><span style="float:left"> - </span><div style="float:left"><select id="BillLine-'+rowID+'" name="BillLine[]"  style="width:300px;border:1px solid #999; padding:2px" onchange="OnLineItemSelected(\''+rowID+'\')"></select></div></td><td align="right"><input type="hidden" name="billLineItemID[]" id="billLineItemID-'+rowID+'" value="" /><input type="hidden" name="quantityPurchased[]" id="quantityPurchased-'+rowID+'" value="" /><input type="hidden" name="RStockID[]" id="RStockID-'+rowID+'" value="" /><img src="images/rs.png" alt="Rs." /><input type="text" name="RRate[]" id="RRate-'+rowID+'" value="0.00" style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td><td align="center"><input type="text" name="RDiscount[]" id="RDiscount-'+rowID+'" value="0"  style="width:50;background:inherit; border:none; text-align:right" readonly="readonly"/>%</td><td align="right"><img src="images/rs.png" alt="Rs." /><input type="text" name="RDRate[]" id="RDRate-'+rowID+'" value="0.00" style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td><td align="center"><input type="text" name="RQuantity[]"  class="validate[custom[number]]" onkeypress="return isNumberKey(event)" id="RQuantity-'+rowID+'" onblur="calculateReturnRow('+rowID+')" value="0"  style="width:35;border:1px solid #999" /></td><td align="right" width="100"><img src="images/rs.png" alt="Rs." /><input type="text" name="RLTotal[]" id="RLTotal-'+rowID+'" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td></tr>';
				$('table#billedProductLineItems > tbody').append(innerht);
				    $.fn.autosugguest({  
           className: 'ausu-suggest',
          methodType: 'POST',
            minChars: 2,
              rtnIDs: true,
            dataFile: 'data.php'
    });
		alertnateRows();
		$('#BillCode-'+rowID).focus();
}

function removeBilledRow(rowID){
    $('#Rr-' + rowID).remove();
	calculateTotal();
}

function fetchBilledPdoruct(rowID, data_val, value){
	
	if(data_val!="" && value != "")
	{
		
		$.post('ajaxProcess.php?billID='+data_val, { "request":"getBilledProduct"},
		  function(data){
			   if(data["result"] != "F")
			   {	
					$('#BillLine-'+rowID)
						.find('option')
						.remove()
						.end();
					var initialValue = "";
			   		for(var i=0; i < data["result"].length; i++)
					{
						var liItem = '<option value="'+data["result"][i]["billItemID"]+';InB;'+data["result"][i]["Rate"]+';InB;'+data["result"][i]["individualDiscountPercentage"]+';InB;'+data["result"][i]["Quantity"]+';InB;'+data["result"][i]["stockID"]+'">'+data["result"][i]["ProductName"]+' @ '+data["result"][i]["Rate"]+'</option>'
						$('#BillLine-'+rowID).append(liItem);
						if(initialValue == "")
							initialValue = data["result"][i]["billItemID"]+';InB;'+data["result"][i]["Rate"]+';InB;'+data["result"][i]["individualDiscountPercentage"]+';InB;'+data["result"][i]["Quantity"]+';InB;'+data["result"][i]["stockID"];
					}
					$('#BillLine-'+rowID).val(initialValue);
					$('#BillCode-'+rowID).val(data_val);
					OnLineItemSelected(rowID);
			   }
			   else
			   {
				   alert("Failed to fetch data. Please try again.");
			   }
			 },"json");
	}
	else
	{
		clearReturnControls(rowID);
	}
	calculateReturnRow(rowID);
}

function OnLineItemSelected(rowID){
	var billLineVal = $('#BillLine-'+rowID).val();
	var splits = billLineVal.split(";InB;");
	
	$('#billLineItemID-'+rowID).val(splits[0]);
	$('#quantityPurchased-'+rowID).val(splits[3]);
	$('#RRate-'+rowID).val(splits[1]);
	$('#RQuantity-'+rowID).val('0');
	$('#RDiscount-'+rowID).val(splits[2]);
	$('#RDRate-'+rowID).val('0.00');
	$('#RLTotal-'+rowID).val('0.00');
	$('#RStockID-'+rowID).val(splits[4]);
	$('#RQuantity-'+rowID).focus();
}

function checkReturnKeyPress(rowID){
	if($('#BillID-'+rowID).val() != "")
	{
		productInRows[parseInt(rowID)] = undefined;
		clearProductControls(rowID);
		calculateRow(rowID);
	}
}

function clearReturnControls(rowID){
	$('#BillCode-'+rowID).val('');
	$('#BillID-'+rowID).val('');
	$('#BillLine-'+rowID).val('');	
	$('#billLineItemID-'+rowID).val('');
	$('#quantityPurchased-'+rowID).val('0');
	$('#RRate-'+rowID).val('0.00');
	$('#RQuantity-'+rowID).val('0');
	$('#RDiscount-'+rowID).val('0');
	$('#RDRate-'+rowID).val('0.00');
	$('#RLTotal-'+rowID).val('0.00');		
}

function calculateReturnRow(rowID){
	
	if($('#RDiscount-'+rowID).val() == "")
		$('#RDiscount-'+rowID).val('0');
	if($('#RQuantity-'+rowID).val() == "")
		$('#RQuantity-'+rowID).val('0');
		
	var returnedQuantity = parseInt($('#RQuantity-'+rowID).val());
	var purchasedQuantity = parseInt($('#quantityPurchased-'+rowID).val());
	
	if(returnedQuantity > purchasedQuantity)
	{
		alert("Cannot return more than purchased. Quantity reduced to purchased value");
		$('#RQuantity-'+rowID).val($('#quantityPurchased-'+rowID).val());
	}
		
	var discountRate = parseFloat($('#RRate-'+rowID).val())*(1-(parseFloat($('#RDiscount-'+rowID).val())/100));
	$('#RDRate-'+rowID).val(roundNumber(discountRate,2));
	var LineTotal = parseFloat($('#RDRate-'+rowID).val())*parseInt($('#RQuantity-'+rowID).val());
	$('#RLTotal-'+rowID).val(roundNumber(LineTotal,2));
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
<h2>New Bill</h2>
<form action="createBill.php" method="post" id="formValidation">
  <table class="formTable" cellspacing="2" cellpadding="3" width="1000">
    <tr>
      <td colspan="2"><h4>Customer Details</h4>
        Details of the customer</td>
    </tr>
    <tr>
      <td colspan="2" align="right"><b>Search for Name/Mobile</b><br />
        Please type the name or the mobile number to get the auto suggestion.<br />
        If you need to create a new customer, please clear the text in this box.<br />
        <div class="ausu-suggest" style="width:400px; float:right" >
          <input type="text" value="" name="customer" id="customer" autocomplete="off" style="width:400px" onkeypress="CustomerKeyPress()" onblur="updateCustomer(document.getElementById('customerid').value)" />
          <input type="hidden" name="customerID" id="customerid"/>
        </div>
        <br />
        <br />
        <br /></td>
    </tr>
    <tr>
      <td>Name : </td>
      <td><input type="text" name="customerName" id="customerName" class="validate[required]" value="" style="width:400px" /></td>
    </tr>
    <tr>
      <td>Phone / Mobile : </td>
      <td>1)
        <input type="text" name="customerPhone1" id="customerPhone1" class="validate[required]" value="" />
        &nbsp;&nbsp;2)
        <input type="text" name="customerPhone2" id="customerPhone2" value="" /></td>
    </tr>
    <tr>
      <td>Email : </td>
      <td>1)
        <input type="text" name="customerEmail1" id="customerEmail1" value="" class="validate[custom[email]]" />
        &nbsp;&nbsp;2)
        <input type="text" name="customerEmail2" id="customerEmail2" value="" class="validate[custom[email]]" /></td>
    </tr>
    <tr>
      <td>Address : </td>
      <td style="height:75px"><textarea style="width:350px; height:70px" name="postalAddress" id="postalAddress"></textarea><div id="postalAddressDisp" style="display:none; border: 1px solid #999999; background:#FFFFFF;width:350px; height:70px; padding:2px"></div></td>
    </tr>
    <tr>
      <td colspan="2"><h4>Purchase Details</h4>
        Materials purchased/returned by the customer.</td>
    </tr>
    <tr>
      <td colspan="2" style="background-color:#FFF">
    <div id="tabs">
	<ul>
		<li><a href="#tabs-1">Purchase Products</a></li>
		<li><a href="#tabs-2">Return Goods</a></li>
	</ul>
	<div id="tabs-1">
      
      <table style="font-size:8pt;" width="100%" cellpadding="2" cellspacing="2" id="productLineItems">
          <thead>
            <tr>
              <th>&nbsp;</th>
              <th>Product</th>
              <th>Rate</th>
              <th>Discount</th>
              <th>Dis. Rate</th>
              <th>Quantity</th>
              <th width="100">Line Total</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
        <a style="float:left" href="javascript:addNewProduct()"><br />
<img src="images/001_01.png" alt="Add" border="0" style="vertical-align:middle" /> Add Another Product</a>
     </div>
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
     </div>
        <table align="right" cellpadding="2" cellspacing="2" style="font-size:8pt;">
          <tr>
            <td align="right" style="border-top:1px solid #000000">Purchase Total(A)</td>
            <td width="100" align="right" style="border-top:1px solid #000000"><img src="images/rs.png" alt="Rs." />
              <input type="text" name="purchaseTotal" id="purchaseTotal" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="right">Return Value(B)</td>
            <td width="100" align="right"><img src="images/rs_r.png" alt="Rs." />
              <input type="text" name="returnTotal" id="returnTotal" value="0.00"  style="width:75;background:inherit; border:none; text-align:right; color:red" readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="right" style="border-top:1px solid #000000">Total(C = A - B)</td>
            <td width="100" align="right" style="border-top:1px solid #000000"><img src="images/rs.png" alt="Rs." />
              <input type="text" name="total" id="total" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="right">Discount(D) @ <input type="text" name="tDiscount" onkeypress="return isDecimalKey(event)" id="tDiscount" value="0"  style="width:50;border:1px solid #999; text-align:right" onblur="calculateTotal()" />%</td>
            <td width="100" align="right"><img src="images/rs.png" alt="Rs." /> <input type="text" name="discountValue" id="discountValue" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="right" style="border-top:1px solid #000000">Total after Discount(E = C-D)</td>
            <td width="100" align="right" style="border-top:1px solid #000000"><img src="images/rs.png" alt="Rs." />
              <input type="text" name="totalADis" id="totalADis" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="right">Tax(T) @ <input type="text" name="tax" id="tax" value="<?=$VAT ?>"  style="width:30px;background:inherit; border:none; text-align:right" readonly="readonly" />%</td>
            <td width="100" align="right"><img src="images/rs.png" alt="Rs." /> <input type="text" name="taxValue" id="taxValue" value="0.00"  style="width:75;background:inherit; border:none; text-align:right" readonly="readonly" />
           </td>
          </tr>
          <tr style="font-size:10pt; font-weight:bold">
            <td align="right" style="border-top:3px solid #000000; border-bottom:3px solid #000000">Net Amount (E+T)</td>
            <td width="100" align="right" style="border-top:3px solid #000000; border-bottom:3px solid #000000"><img src="images/rs.png" alt="Rs." />
              <input type="text" name="net" id="net" value="0.00"  style="width:75;background:inherit; border:none; text-align:right;font-weight:bold" readonly="readonly" /></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" name="createNewBill" id="createNewBill" value="Generate" />
        <input type="reset" value="Reset" /></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
addNewProduct();
addNewBillProduct();
$('#customer').focus();
</script>
<?php
include_once("includes/footer.php");
?>
