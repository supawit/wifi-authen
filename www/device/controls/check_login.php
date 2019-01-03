<?php session_start();
$sid = session_id();

if($_SESSION["login_$sid"])
{
	printf('$scope.username = "%s";', $_SESSION["user_$sid"]);
	require('config.php');
	printf('$scope.quota = "%s";', QUOTA);
}else{
	printf("document.location.href='controls/logout.php';");
}
?>