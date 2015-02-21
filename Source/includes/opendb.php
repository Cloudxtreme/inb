<?php
$connect_db = mysql_connect($GLOBALS['configuration']['host'],$GLOBALS['configuration']['user'],$GLOBALS['configuration']['pass']) or die($messages["dbConnectionError"]);
mysql_select_db($GLOBALS['configuration']['db'],$connect_db) or die($messages["dbFindError"]);
mysql_query("SET time_zone = '".$GLOBALS['configuration']['timezone']."'");
?>