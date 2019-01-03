<?php
require('config.php');

class User {
	function User(){}
	
	function signIn($email,$passwd){	
		if(strlen($email)>0 && strlen($passwd)>0){
			$ldap = ldap_connect(AD_HOST) or die('$scope.style="alert alert-danger";'.'$scope.signin_status="Cloud not connect AD.";');
			if($ldap){
				$bind = ldap_bind($ldap,$email,$passwd);
				if($bind){
					session_start();
					$sid = session_id();
					$_SESSION["user_$sid"]=$email;
					$_SESSION["login_$sid"]=true;
					ldap_close($ldap);
					printf("document.location.href='main.html';");
				}else{
					unset($_SESSION["user_$sid"]);
					unset($_SESSION["login_$sid"]);
					session_destroy();
					ldap_close($ldap);
					printf('$scope.style="alert alert-danger";');
					printf('$scope.signin_status="Invalid Account!!!";');
				}
			}
		}else{
			printf('$scope.style="alert alert-danger";');
			printf('$scope.signin_status="Invalid Account!!!";');
		}
	}
	
}

class Mac {
	function Mac(){}
	
	function getMac($username){
		$mysql = new MySQLi();
		$mysql->connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		$mysql->set_charset('utf8');
		
		$sql = "
		SELECT * FROM `mac` WHERE `username` = '$username' ORDER BY `id` DESC
		";
		
		$result = $mysql->query($sql);
		$array[] = array(
			"id"=>"id",
			"username"=>"username",
			"macaddress"=>"macaddress",
			"description"=>"description",
			"vendor"=>"vendor",
			"addtime"=>"addtime",
			"updatetime"=>"updatetime"
		);
		if($result){
			if($result->num_rows > 0){
				while($obj = $result->fetch_assoc()){
					$array[] = $obj;
				}
			}
		}else
		{
			printf("%s",$mysql->error);
		}
		$mysql->close();
		$retval = json_encode($array);
//		$retval = "\$scope.mailGroupList = [".substr($json,0,-1)."];";		
		return $retval;
	}
	
	function delMac($id){
		$mysql = new MySQLi();
		$mysql->connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		$mysql->set_charset('utf8');
		
		$sql = "
		DELETE  FROM `mac` WHERE `id` = '$id'
		";
		
		$result = $mysql->query($sql);
		$mysql->close();
	}
	
	function aadMac($username,$description,$address){
		$mysql = new MySQLi();
		$mysql->connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		$mysql->set_charset('utf8');
		
		$count = $mysql->query("SELECT COUNT(*) FROM `mac` WHERE `username` = '$username'");
		if($count->num_rows>QUOTA){
			printf("alert(\"MAC Address Quota Limit Exceeded\");");
		}else{
			$sql = "
			INSERT INTO `mac`(`username`,`description`,`macaddress`)
			VALUES('$username','$description','$address')
			";
			
			$result = $mysql->query($sql);
						
			if($result){
				$macList = $this->getMac($username);
				printf('$scope.macList = %s',$macList);	
			}else{
				printf("alert(\"%s\");",$mysql->error);
			}
		}
		$mysql->close();		
	}
}
?>
