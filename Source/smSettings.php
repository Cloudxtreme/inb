<div class="urbangreymenu">
<h3 class="headerbar">Settings Sub-menu</h3>
<ul class="submenu">
  <li><a href="<?=BASEDIR ?>siteSettings.php">Site Settings</a></li>
  <li><a href="<?=BASEDIR ?>dbSettings.php?type=C">Company Settings</a></li>
  <li><a href="<?=BASEDIR ?>dbSettings.php?type=F">Business Settings</a></li>
  <li><a href="<?=BASEDIR ?>dbSettings.php?type=D">Display Settings</a></li>
  <li><a href="<?=BASEDIR ?>userSettings.php">User Settings</a></li>
  <?php if($_SESSION["user_accessKeys"]["canCreateUser"])
{ ?>
  <li><a href="<?=BASEDIR ?>createUser.php">Create new user</a></li>
  <?php } ?>
</ul>
</div>