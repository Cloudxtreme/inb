<?php
include_once("config.php");
include_once("includes/opendb.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<title><?php echo SITE_NAME; ?></title>
<meta name="description" content="<?php echo DESCRIPTION; ?>" />
<meta name="keywords" content="<?php echo KEYWORDS; ?>" />
<meta name="author" content="<?php echo AUTHOR; ?>" />
<link rel="stylesheet" id="style-css" href="<?=BASEDIR?>css/style.css" type="text/css" media="screen">

<link rel="stylesheet" id="style-css" href="<?=BASEDIR?>css/style_p.css" type="text/css" media="print">
<script src="<?=BASEDIR?>js/jquery.min.js" type="text/javascript"></script>
<?php if($IsAlternateTableStyle) { ?>
<link rel="stylesheet" href="<?=BASEDIR?>css/validationEngine.jquery.css" type="text/css"/>
<script src="<?=BASEDIR?>js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=BASEDIR?>js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#formValidation").validationEngine();
    });
</script>
<script src="<?=BASEDIR?>js/scripts.js" type="text/javascript"></script>
<?php } ?>

<?php if($IsAutoSuggest) { ?>
<link rel="stylesheet" type="text/css" href="<?=BASEDIR?>css/style_autocomplete.css" />
<style>
	.ausu-suggest	{width: 280px;}
</style>
<script type="text/javascript" src="<?=BASEDIR?>js/jquery.ausu-autosuggest.js"></script>

	<link rel="stylesheet" href="<?=BASEDIR?>css/jquery.ui.tabs.css">
    <link rel="stylesheet" href="<?=BASEDIR?>css/jquery.ui.theme.css">
	<script src="<?=BASEDIR?>js/jquery.ui.core.js"></script>
	<script src="<?=BASEDIR?>js/jquery.ui.widget.js"></script>
	<script src="<?=BASEDIR?>js/jquery.ui.tabs.js"></script>
	<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>

<script>
$(document).ready(function() {
    $.fn.autosugguest({  
           className: 'ausu-suggest',
          methodType: 'POST',
            minChars: 2,
              rtnIDs: true,
            dataFile: 'data.php'
    });
});
</script>
<?php } ?>
<?php if($IsShowGraph) { ?>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>
<?php } ?>
<?php if($IsShowDatePicker) { ?>
		<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" />
		<link rel="stylesheet" href="css/ui.daterangepicker.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/date.js"></script>
		<script type="text/javascript" src="js/daterangepicker.jQuery.js"></script>
		<script type="text/javascript">	
			$(function(){
				  $('.fullCalander').daterangepicker({
					dateFormat : 'd/m/yy',
					rangeSplitter : 'to',
					onClose: function(){
						refreshReport($('.fullCalander').val());
						}
				  }); 
			 });
		</script>
<?php } ?>
<?php if($IsListStyling) { ?>
<style type="text/css" media="screen">
			/*@import "css/site_jui.ccss.css";*/
			@import "css/demo_table_jui.css";
			@import "css/jquery-ui-1.7.2.custom.css";
			
			/*
			 * Override styles needed due to the mix of three different CSS sources! For proper examples
			 * please see the themes example in the 'Examples' section of this site
			 */
			.dataTables_info { padding-top: 0; }
			.dataTables_paginate { padding-top: 0; }
			.css_right { float: right; }
			#example_wrapper .fg-toolbar { font-size: 0.8em }
			#theme_links span { float: left; padding: 2px 10px; }
			#example_wrapper { -webkit-box-shadow: 2px 2px 6px #666; box-shadow: 2px 2px 6px #666; border-radius: 5px; }
			#example tbody {
				border-left: 1px solid #AAA;
				border-right: 1px solid #AAA;
			}
			#example thead th:first-child { border-left: 1px solid #AAA; }
			#example thead th:last-child { border-right: 1px solid #AAA; }
		</style>
<script type="text/javascript" src="<?=BASEDIR?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript">	
			$(document).ready( function() {
				$('#example').dataTable( {
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				} );

				SyntaxHighlighter.config.clipboardSwf = '<?=BASEDIR?>js/clipboard.swf';
				SyntaxHighlighter.all();
			} );
		</script>
<?php } ?>
</head>
<body>
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" class="layout">
  <tr>
    <td colspan="2" height="25" class="top1"><div id="rightFloat" style="float:right; margin-right:10px">
    <?php if(isset($_SESSION["user_id"])) { ?>
    <a href="<?=BASEDIR ?>logout.php">Logout</a>
    <?php } ?>
    
    &nbsp;|&nbsp; &copy; <?=COPYRIGHTYEAR ?>, <?=COPYRIGHTNAME ?></div><?php if(isset($_SESSION["user_name"])) { ?>Hello, <?php echo $_SESSION["user_name"]; ?><?php } ?></td>
  </tr>
  <tr class="top">
    <td colspan="2" height="120" class="top" valign="bottom"><img src="images/logo.png" alt="InB" border="0" /><br/>
    
   
<?php
//Check for session
if(!isset($_SESSION["user_id"]) && !$DoNotCheckSession)
{
?>
</td></tr>
<tr><td align="center" colspan="2"><h1>No Access</h1>
<a href="<?=BASEDIR; ?>index.php">Login again.</a>
<?php
include_once("includes/footer.php");
die("");
}

if(!$DoNotCheckSession)
{
?>
      <div>
        <ul class="menu">
          <li><a href="<?=BASEDIR?>home.php">Home</a></li>
          <?php if($_SESSION["user_accessKeys"]["Customers"]) { ?><li><a href="<?=BASEDIR ?>listCustomers.php">Customers</a></li><?php } ?>
          <?php if($_SESSION["user_accessKeys"]["Vendors"]) { ?><li><a href="<?=BASEDIR ?>listVendors.php">Vendors</a></li><?php } ?>
          <?php if($_SESSION["user_accessKeys"]["Products"]) { ?><li><a href="<?=BASEDIR ?>listProducts.php">Products</a></li><?php } ?>
          <?php if($_SESSION["user_accessKeys"]["Purchases"]) { ?><li><a href="<?=BASEDIR ?>listPO.php">Purchases</a></li><?php } ?>
          <?php if($_SESSION["user_accessKeys"]["Bills"]) { ?><li><a href="<?=BASEDIR ?>listBills.php">Bills</a></li><?php } ?>
          <?php if($_SESSION["user_accessKeys"]["Reports"]) { ?><li><a href="<?=BASEDIR ?>reports.php">Reports</a></li><?php } ?>
          <?php if($_SESSION["user_accessKeys"]["Settings"]) { ?><li><a href="<?=BASEDIR ?>siteSettings.php">Settings</a></li><?php } ?>          
        </ul>
      </div>
 <?php }  ?>     
 </td>
  </tr>
  <?php
  //Check for session
if(isset($pgGroup) && !$_SESSION["user_accessKeys"][$pgGroup])
{
?>
<tr><td align="center" colspan="2"><h1>No Access</h1>
You are not authorized to view this page.
<?php
include_once("includes/footer.php");
die("");
}
?>
  <tr>
  <?php if($showSubMenu) { ?>
  <td width="200" valign="top" class="left">
  	<?php if(isset($pgGroup)) {
	include_once("sm".$pgGroup.".php");
	} ?>
    </td>
    <td valign="top" style="padding:10px;" class="right">
   <?php } else { ?>
   <td colspan="2" valign="middle" align="center" class="right">
   <?php } ?>