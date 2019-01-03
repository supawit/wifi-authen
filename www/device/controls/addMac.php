<?php session_start();
$sid = session_id();

if(isset($_SESSION["user_$sid"])){
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$description = strtolower($request->description);
	$address = strtolower($request->address);
	
	require('class.php');
	$mac = new Mac();
	$mac->aadMac($_SESSION["user_$sid"],$description,$address);
}
?>