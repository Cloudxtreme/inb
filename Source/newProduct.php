<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Products";
include_once("includes/header.php");

function buildProductKey($id,$cat)
{
	$pre = "PR";
	$ret_str = "";
//	$words = explode(" ",trim($cat));
//	if(
	switch(strlen($id))
	{
		case "1":
			$ret_str = $pre."000".$id;
			break;
		case "2":
			$ret_str = $pre."00".$id;
			break;
		case "3":
			$ret_str = $pre."0".$id;
			break;
		default:
			$ret_str = $pre.$id;
			break;
	}
	
	return $ret_str;
}
?>
<span style="color:red">
<?php
if(isset($_POST["newProduct"]))
{
	$reid = ($_POST["newProduct"] == "Edit")?"id=".$_POST["productID"]."&":"";
	if($_POST["productName"] != "" && $_POST["subCategoryID"] != "")
	{
 		$sql = "insert into products (productName,Description,subCategoryID,uniqueReference,vendorID) values ('".htmlentities($_POST["productName"],ENT_QUOTES)."','".htmlentities($_POST["Description"],ENT_QUOTES)."','".htmlentities($_POST["subCategoryID"],ENT_QUOTES)."','0','".htmlentities($_POST["vendorID"],ENT_QUOTES)."')";
		if($_POST["newProduct"] == "Edit")
		{
	 		$sql = "update products set productName='".htmlentities($_POST["productName"],ENT_QUOTES)."',Description='".htmlentities($_POST["Description"],ENT_QUOTES)."',subCategoryID='".htmlentities($_POST["subCategoryID"],ENT_QUOTES)."',vendorID='".htmlentities($_POST["vendorID"],ENT_QUOTES)."' where productID=".$_POST["productID"];
		}
		if(!mysql_query($sql))
		{
			header("Location:newProduct.php?".$reid."msg=Product not ".($_POST["newProduct"] == "Edit")?"Edited":"Created"." at this time. Please try again after some time.");
		}
		else
		{
			if($_POST["newProduct"] != "Edit")
			{
				$productID = mysql_insert_id();
				mysql_query("update products set uniqueReference='".buildProductKey($productID,'')."' where productID=".$productID);
			}
			$ce = ($_POST["newProduct"] == "Edit")?"Edited":"Created";
		 	header("Location:listProducts.php?msg=".$ce ." successfully.");
		}
	}
	else
	{
		header("Location:newProduct.php?".$reid."msg=Please enter all the fields");
	}
}

$isEdit = false;
$resultset;
if(isset($_REQUEST["id"]) && $_REQUEST["id"] != "")
{
	$resultset = mysql_query("select *,(select categoryID from subcategories where subCategoryID=products.subCategoryID)categoryID from products where productID=".$_REQUEST["id"]);
	if(mysql_num_rows($resultset) > 0)
	{
		$isEdit = true;
	}
	else
		echo "Incorrect request parameter. Switiching to create form.";
}

if(isset($_REQUEST["msg"]))
	echo $_REQUEST["msg"];
?>

<h2>Create new product
<span><a href="<?=BASEDIR ?>listProducts.php"><img src="images/001_23.png" style="vertical-align:middle" border="0" alt="Back to List" title="Back to List"  /></a></span></h2>
<form action="newProduct.php" method="post" id="formValidation">
<input type="hidden" name="productID" id="productID" value="<?=$isEdit?mysql_result($resultset,0,"productID"):"" ?>" />
<table class="formTable" cellspacing="2" cellpadding="3">
<tr><td colspan="2"><h4>Product Details</h4>Details of the product</td></tr>
 <tr>
      <td>Name : </td>
      <td><input type="text" name="productName" id="productName" class="validate[required]" value="<?=$isEdit?mysql_result($resultset,0,"productName"):"" ?>"/></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><textarea style="width:350px; height:70px" name="Description" id="Description"><?=$isEdit?mysql_result($resultset,0,"Description"):"" ?></textarea></td>    </tr>
        <tr>
      <td>Category</td>
      <td><select name="categoryID" id="categoryID" class="validate[required]" style="width:300px" onchange="categorySelected(this.value,'')">
      <option value="">Select a category</option>
      <?php
	  	$result_cats = mysql_query("select * from categories where status=1");
		while($row_cats = mysql_fetch_array($result_cats))
		{
	  ?>
      <option value="<?=$row_cats["categoryID"] ?>" <?php if($isEdit){ if($row_cats["categoryID"] == mysql_result($resultset,0,"categoryID")) { ?> selected="selected"<?php } } ?>><?=$row_cats["categoryName"] ?></option>
      
      <?php } ?>
      </select></td>
    </tr>
    <tr>
      <td>SubCategory</td>
      <td><select name="subCategoryID" id="subCategoryID" class="validate[required]" style="width:300px">
      <option value="">Select a subcategory</option>
      <?php if($isEdit) {
		  $result_subCats = mysql_query("select * from subCategories where categoryID=".mysql_result($resultset,0,"categoryID")." and status=1");
		  
          while($row_subCats = mysql_fetch_array($result_subCats))
		{
	  ?>
      <option value="<?=$row_subCats["subCategoryID"] ?>" <?php if($isEdit){ if($row_subCats["subCategoryID"] == mysql_result($resultset,0,"subCategoryID")) { ?> selected="selected"<?php } } ?>><?=$row_subCats["subCategoryName"] ?></option>
      <?php }} ?>
      
      </select></td>
    </tr>
        <tr>
      <td>Vendor</td>
      <td><select name="vendorID" id="vendorID" style="width:300px">
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
<tr><td colspan="2"><input type="submit" name="newProduct" id="newProduct" value="<?=$isEdit?"Edit":"Create" ?>" /> <input type="reset" value="Reset" onclick="categorySelected('<?=($isEdit)?mysql_result($resultset,0,"categoryID"):''?>','<?=($isEdit)?mysql_result($resultset,0,"subCategoryID"):''?>')" /></td></tr></table></form>
</span>
<script type="text/javascript">

function categorySelected(val,subId)
{
	if(val == "")
	{
		$('#subCategoryID')
						.find('option')
						.remove()
						.end();
		$('#subCategoryID').append('<option value="">Select a sub category</option>');						
	}
	else
	{
		$.post('ajaxProcess.php?categoryID='+val, { "request":"getSubCategories"},
		  function(data){
			   if(data["result"] != "F")
			   {	
					$('#subCategoryID')
						.find('option')
						.remove()
						.end();
					$('#subCategoryID').append('<option value="">Select a sub category</option>');
			   		for(var i=0; i < data["result"].length; i++)
					{
						var sel = "";
						if(subId == data["result"][i]["subCategoryID"])
							sel = ' selected="selected" ';
						var liItem = '<option value="'+data["result"][i]["subCategoryID"]+'" '+sel+'>'+data["result"][i]["subCategoryName"]+'</option>'
						$('#subCategoryID').append(liItem);
					}
			   }
			   else
			   {
				   alert("Failed to fetch data. Please try again.");
			   }
			 },"json");
	}
}
</script>
<?php
include_once("includes/footer.php");
?>
