<?php
include_once("config.php");
include_once("includes/opendb.php");
?>
<chart>
<?php
$returnDays = 30;
$date = " a.billID in (select billID from bills where billDate > '".date("Y-m-d 00:00:00",mktime(0, 0, 0, date("m")  , date("d")-$returnDays, date("Y")))."') ";

$result_cat = mysql_query("select sum(a.Quantity)data,(select categoryName from categories where categoryID=c.categoryID)name from billitems as a,products as b,subcategories as c where b.productID = a.productID and c.subCategoryID=b.subCategoryID and ".$date." group by c.categoryID");
$result_total = mysql_query("select sum(a.Quantity)total from billitems as a where ".$date);
$others = mysql_result($result_total,0,"total");
if(mysql_num_rows($result_cat) > 0)
{
?>
   <chart_type>pie</chart_type>
   <chart_data>
      <row>
         <string>Category</string>
         <?php $data=array(); $i=0; while($row_cat = mysql_fetch_array($result_cat)) { ?>
         <string><?=$row_cat["name"] ?></string>
         <?php
		 $others = $others - $row_cat["data"];
		 $data[$i] = $row_cat["data"];
		 $i++;
		  } ?>
          <?php if($others > 0) { ?>
          <string>Others</string>
          <?php } ?>
      </row>
      <row>
       		<string>Quantity Sold</string>
            <?php for($i=0; $i < sizeof($data); $i++) { ?>
         <number><?=$data[$i] ?></number>
         <?php } ?>
         <?php if($others > 0) { ?>
          <number><?=$others ?></number>
          <?php } ?>
      </row>
   </chart_data>
   <?php } ?>
</chart>
<?php
include_once("includes/closedb.php");
?>