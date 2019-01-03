<?php session_start();
$sid = session_id();

if(isset($_SESSION["user_$sid"])){
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$id = strtolower($request->id);

	require('class.php');
	$mac = new Mac();
	$mac->delMac($id);
}
?>