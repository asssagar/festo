<?php 

$db = mysql_connect('www.ullon.com','mrpatch_rudi','2Unlimited') or die(mysql_error());
mysql_select_db('mrpatch_experiment') or die(mysql_error());

if(@$_POST['k']){
	$key = mysql_real_escape_string(@$_POST['k']);
	$value = mysql_real_escape_string(@$_POST['v']);
	$sql = "insert into diamond_cave(k,v) VALUES('$key','$value');";
	mysql_query($sql) or die(mysql_error().' ON '.$sql);
	die('1');
}
die('0');
?>