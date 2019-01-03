<?php session_start();
$sid = session_id();

if(isset($_SESSION["user_$sid"])){
	require('class.php');
	$mac = new Mac();
	$macList = $mac->getMac($_SESSION["user_$sid"]);
	echo $macList;
}
?>