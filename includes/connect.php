<?php
$dest_host = "localhost";
$dest_db  = "theembro_2embroidery";
$dest_usr = "root";
$dest_pwd = "";

$ddb = mysql_connect($dest_host,$dest_usr,$dest_pwd);
mysql_select_db($dest_db,$ddb);
?>