<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Products";
include_once("includes/header.php");
?>
<span style="color:red">
<?php
if(isset($_POST["newSubCategory"]))
{
	$reid = ($_POST["newSubCategory"] == "Edit")?"id=".$_POST["subCategoryID"]."&":"";
	if($_POST["subCategoryName"] != "")
	{
 		$sql = "insert into subcategories (subCategoryName,categoryID) values ('".htmlentities($_POST["subCategoryName"],ENT_QUOTES)."',".htmlentities($_POST["categoryID"],ENT_QUOTES).")";
		if($_POST["newSubCategory"] == "Edit")
		{
	 		$sql = "update subcategories set subCategoryName='".htmlentities($_POST["subCategoryName"],ENT_QUOTES)."' and categoryID=".htmlentities($_POST["categoryID"],ENT_QUOTES)." where subCategoryID=".$_POST["subCategoryID"];
		}

		if(!mysql_query($sql))
		{
			header("Location:newSubCategory.php?".$reid."msg=Category not ".($_POST["newSubCategory"] == "Edit")?"Edited":"Created"." at this time. Please try again after some time.");
		}
		else
		{
			$ce = ($_POST["newSubCategory"] == "Edit")?"Edited":"Created";
		 	header("Location:listSubCategories.php?msg=".$ce ." successfully.");
		}
	}
	else
	{
		header("Location:newSubCategory.php?".$reid."msg=Please enter all the fields");
	}
}

$isEdit = false;
$resultset;
if(isset($_REQUEST["id"]) && $_REQUEST["id"] != "")
{
	$resultset = mysql_query("select * from subcategories where subCategoryID=".$_REQUEST["id"]);
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
</span>
<h2>Create new subCategory
<span><a href="<?=BASEDIR ?>listSubCategories.php"><img src="images/001_23.png" style="vertical-align:middle" border="0" alt="Back to List" title="Back to List"  /></a></span>
</h2>
<form action="newSubCategory.php" method="post" id="formValidation">
<input type="hidden" name="subCategoryID" id="subCategoryID" value="<?=$isEdit?mysql_result($resultset,0,"subCategoryID"):"" ?>" />
<table class="formTable" cellspacing="2" cellpadding="3">
 <tr>
      <td>Category : </td>
      <td><select name="categoryID" id="categoryID" class="validate[required]">
      <option value="">Select a category</option>
      <?php
	  	$result_cats = mysql_query("select * from categories where status=1");
		while($row_cats = mysql_fetch_array($result_cats))
		{
	  ?>
      <option value="<?=$row_cats["categoryID"] ?>" <?php if($isEdit) { if($row_cats["categoryID"]==mysql_result($resultset,0,"categoryID")) {?>selected="selected"<?php } }?>><?=$row_cats["categoryName"] ?></option>
      
      <?php } ?>
      </select></td>
    </tr>
 <tr>
      <td>Name : </td>
      <td><input type="text" name="subCategoryName" id="subCategoryName" class="validate[required]" value="<?=$isEdit?mysql_result($resultset,0,"subCategoryName"):"" ?>"/></td>
    </tr>
<tr><td colspan="2"><input type="submit" name="newSubCategory" id="newSubCategory" value="<?=$isEdit?"Edit":"Create" ?>" /> <input type="reset" value="Reset" /></td></tr></table></form>
<?php
include_once("includes/footer.php");
?>
