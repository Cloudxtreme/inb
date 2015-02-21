<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Products";
include_once("includes/header.php");
?>
<span style="color:red">
<?php
if(isset($_POST["newCategory"]))
{
	$reid = ($_POST["newCategory"] == "Edit")?"id=".$_POST["categoryID"]."&":"";
	if($_POST["categoryName"] != "")
	{
 		$sql = "insert into categories (categoryName) values ('".htmlentities($_POST["categoryName"],ENT_QUOTES)."')";
		if($_POST["newCategory"] == "Edit")
		{
	 		$sql = "update categories set categoryName='".htmlentities($_POST["categoryName"],ENT_QUOTES)."' where categoryID=".$_POST["categoryID"];
		}

		if(!mysql_query($sql))
		{
			header("Location:newCategory.php?".$reid."msg=Category not ".($_POST["newCategory"] == "Edit")?"Edited":"Created"." at this time. Please try again after some time.");
		}
		else
		{
			if($_POST["newCategory"] != "Edit")
			{
				$cat_id = mysql_insert_id();
				mysql_query("insert into subCategories (subCategoryName,categoryID) values ('General',".$cat_id.")");
			}
			$ce = ($_POST["newCategory"] == "Edit")?"Edited":"Created";
		 	header("Location:listCategories.php?msg=".$ce ." successfully.");
		}
	}
	else
	{
		header("Location:newCategory.php?".$reid."msg=Please enter all the fields");
	}
}

$isEdit = false;
$resultset;
if(isset($_REQUEST["id"]) && $_REQUEST["id"] != "")
{
	$resultset = mysql_query("select * from categories where categoryID=".$_REQUEST["id"]);
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
<h2>Create new category
<span><a href="<?=BASEDIR ?>listCategories.php"><img src="images/001_23.png" style="vertical-align:middle" border="0" alt="Back to List" title="Back to List"  /></a></span>
</h2>
<form action="newCategory.php" method="post" id="formValidation">
<input type="hidden" name="categoryID" id="categoryID" value="<?=$isEdit?mysql_result($resultset,0,"categoryID"):"" ?>" />
<table class="formTable" cellspacing="2" cellpadding="3">
<tr><td colspan="2"><h4>Category Details</h4>Details of the category</td></tr>
 <tr>
      <td>Name : </td>
      <td><input type="text" name="categoryName" id="categoryName" class="validate[required]" value="<?=$isEdit?mysql_result($resultset,0,"categoryName"):"" ?>"/></td>
    </tr>
    </tr>
<tr><td colspan="2"><input type="submit" name="newCategory" id="newCategory" value="<?=$isEdit?"Edit":"Create" ?>" /> <input type="reset" value="Reset" /></td></tr></table></form>
<?php
include_once("includes/footer.php");
?>
