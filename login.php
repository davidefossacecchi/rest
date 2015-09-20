<?php
	include("orm.php");
	$method = $_SERVER['REQUEST_METHOD'];

	switch ($method){
		case "GET":
			recover();
		break;
		case "POST":
			login();
		break;
		case "PUT":
			signup();
		break;
	}

	

	function recover(){
		if(!isset($_GET["email"]) || ($_GET["email"] == "")) header("HTTP/1.1 400 Bad Request");
		else{
			$username = $_GET["email"];
			$users = connect();
			if(isPresent($users, $username)){
				$user = $users[$username];
				mail($user["username"], "LoginApp - Password recovery", "You have requested to restore your password, go to".$_SERVER['HTTP_HOST']."/recover.html and insert:
					token = ".$user['salt']);
				$user["recover"] = true;
				persistUser($users,$user);
				header("HTTP/1.1 200 OK");
			}
			else header("HTTP/1.1 404 Not Found");
		}
	}

	function login(){
		if(!isset($_POST["email"]) || !isset($_POST["password"]) || ($_POST["password"] == "") || ($_POST["email"] == "")) header("HTTP/1.1 400 Bad Request");
		else{
			$username = $_POST["email"];
			$users = connect();
			if(isPresent($users, $username)){
				$user = $users[$username];
				$encPSW = md5($user["salt"].$_POST["password"]);
				if($encPSW == $user["password"]){
					session_start();
					$_SESSION["user"] = $user;
					header("HTTP/1.1 200 OK");
				}
				else header("HTTP/1.1 401 Unathorized");
			}
			else header("HTTP/1.1 401 Unathorized");
		}
	}

	function signup(){
		parse_str(file_get_contents("php://input"),$put);
		if(!isset($put["email"]) || !isset($put["password"]) || !isset($put["password_conf"]) || ($put["password"] == "") || ($put["email"] == "") || ($put["password_conf"] == "")) header("HTTP/1.1 400 Bad Request");
		else{
			$users = connect();
			if(isPresent($users, $put["email"])) header("HTTP/1.1 409 Conflict");
			else{
				$id = getMax($users)+1;
				$username = $put["email"];
				$salt = generateSalt(10);
				$encPSW = md5($salt.$put["password"]);
				$recover = false;
				$user = ["id" => $id, "username" => $username, "password" => $encPSW, "salt" => $salt, "recover"=>$recover];
				$users[$username] = $user;
				write($users);
				session_start();
				$_SESSION["user"] = $user;
				header("HTTP/1.1 201 Created");
			}
		}
	}
?>
