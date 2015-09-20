<?php
	include("orm.php");
	if(!isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["token"]) || ($_POST["password"] == "") || ($_POST["email"] == "") || ($_POST["token"] == "")) header("HTTP/1.1 400 Bad Request");
	else{
		$users = connect();
		if(!isset($users[$_POST["email"]])) header("HTTP/1.1 404 Not Found");
		else{
			if((!$users[$_POST["email"]]["recover"]) || ($users[$_POST["email"]]["salt"] != $_POST["token"])) header("HTTP/1.1 401 Unauthorized");
			else{
				$users[$_POST["email"]]["recover"] = false;
				$users[$_POST["email"]]["salt"] = generateSalt(10);
				$users[$_POST["email"]]["password"] = md5($users[$_POST["email"]]["salt"].$_POST["password"]);
				write($users);
				header("HTTP/1.1 200 OK");
			}
		}
	}
?>