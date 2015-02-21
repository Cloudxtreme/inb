<?php
$showSubMenu = true;
$IsShowGraph = true;
$IsShowDatePicker = true;
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
	$.post('ajaxProcess.php?dateRange='+val, { "request":"getCategorySaleRpt"},
		  function(data){
				   dataArray = new Array(data["result"].length);
				   for(var i=0; i < data["result"].length; i++)
					{
						dataArray[i] = {name:data["result"][i]["name"],y:parseInt(data["result"][i]["data"])};
					}
					chart.series[0].remove(false);
					chart.addSeries({type:'pie',name:'sales',data:dataArray});
			 },"json");
	}
	
}
</script>
<h2>Reports</h2>
<div align="right">
Select a Date or Range <input type="text" value="<?=$dateValue ?>" class="fullCalander sceneCalander"/>
</div>
<script type="text/javascript">
var chart;
$(function () {
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },
            title: {
                text: 'Sale across categories'
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.point.name +'</b>: '+ this.y;
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.y ;
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Sales',
            }],
			exporting : {enabled:false}
        });
    });
    
});
</script>
<div id="container" style="width: 400px; height: 400px;"></div>
<script>
refreshReport('<?=$dateValue ?>')
</script>
</td></tr></table>
<?php
include_once("includes/footer.php");
?>
