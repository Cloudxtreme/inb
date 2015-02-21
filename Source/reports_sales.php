<?php
$showSubMenu = true;
$IsShowDatePicker = true;
$IsListStyling = true;
$pgGroup = "Reports";
include_once("includes/header.php");
$dateValue = date("j/n/Y",mktime(0, 0, 0, date("m")  , date("d")-30, date("Y")))." to ".date("j/n/Y");
if(isset($_REQUEST["dateRange"]) && $_REQUEST["dateRange"] != "")
	$dateValue = $_REQUEST["dateRange"];
?><table width="100%" height="100%"><tr><td align="left" valign="top">
<script type="text/javascript">
function refreshReport(val)
{
	if(val != "")
	{
		$.post('ajaxProcess.php?dateRange='+val, { "request":"getSalesRpt"},
		  function(data){
				//$('#example').dataTable().fnClearTable(false);
			   for(var i=0; i < data["result"].length; i++)
				{
					if(data["result"][i]["billID"] != undefined)
					{
						$('#example').dataTable().fnAddData( [
								data["result"][i]["bDate"],
								data["result"][i]["billID"],
								roundNumber(parseFloat(data["result"][i]["rateBeforeTax"]),2),
								roundNumber(parseFloat(data["result"][i]["tax"]),2) ], false );
					}
				}
				$('#example').dataTable().fnDraw(true);
			 },"json");
	}
	
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
</script>
<h2>Reports</h2>
<div align="right">
Select a Date or Range <input type="text" value="<?=$dateValue ?>" class="fullCalander sceneCalander"/>
</div><br />
<br />

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Bill Date</th>
			<th>Bill Number</th>
            <th>Taxable Amount</th>
			<th>Tax</th>
		</tr>
	</thead>
	<tbody>

	</tbody>
</table>
<script>
refreshReport('<?=$dateValue ?>')
</script>
</td></tr></table>
<?php
include_once("includes/footer.php");
?>
