<?php
$showSubMenu = true;
$IsAlternateTableStyle = true;
$pgGroup = "Settings";
include_once("includes/header.php");

if(isset($_POST["updateSiteConfig"]))
{
	$postData = $_POST;
	$keys = array_keys($postData);
	foreach($keys as $key)
	{
		if($key != "updateSiteConfig")
		{
			$sql_add = "update settings set value='".$postData[$key]."' where id=".substr($key,1);
		 	mysql_query($sql_add);
		}
	}
	header("location:dbSettings.php?type=".$_REQUEST["type"]."&msg=Updated successfully.");
}
?>
<?php
$name = "Company";
switch($_REQUEST["type"])
{
	case "C":
		$name = "Company";
	break;
	case "F":
		$name = "Business";
	break;
	case "D":
		$name = "Display";
	break;
}
$sql = "select * from settings where type='".$_REQUEST['type']."'";
$result = mysql_query($sql);
if(mysql_num_rows($result) > 0)
{
?>
<h2><?=$name ?> Settings</h2>
<form action="<?php echo $PHP_SELF;?>?type=<?=$_REQUEST["type"]?>" method="post" id="formValidation">
  <table class="formTable" cellspacing="2" cellpadding="3">
    <?php
while($row=mysql_fetch_array($result))
{
?>
    <tr>
      <td><?php echo $row["key"]; ?></td>
      <td><input type="text" name="s<?php echo $row["id"]; ?>" id="s<?php echo $row["id"]; ?>" value="<?php echo $row["value"]; ?>" <?php if($row["mandate"]=="1") { ?> class="validate[required]" <?php } ?> /></td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="2"><input type="submit" name="updateSiteConfig" id="updateSiteConfig" value="Update" />
        <input type="reset" value="Reset" /></td>
    </tr>
  </table>
</form>
<?php } else {?>
No settings available
<?php } ?>
<?php
include_once("includes/footer.php");
?>
