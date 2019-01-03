<?php 
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$email = strtolower($request->email);
$passwd = $request->passwd;

require('class.php');
$user = new User();
$user->signIn($email,$passwd);
?>