<?php session_start();
$sid = session_id();
unset($_SESSION["user_$sid"]);
unset($_SESSION["login_$sid"]);	
session_destroy();
header('location:../index.html');
?>