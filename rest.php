<?php
	include("orm.php");
	session_start();
	if(!isset($_SESSION["user"])){
		header("HTTP/1.1 401 Unathorized");
		session_unset();
		session_destroy();
	}
	else{
		$user = $_SESSION["user"];
		$method = $_SERVER['REQUEST_METHOD'];

		$current_script = basename(__FILE__);
		$resource = $_SERVER['REQUEST_URI'];
		$offset = 0;
		$pos = strpos($resource, $current_script);
		if($pos !== false) $offset = strlen($current_script);
		$resource_id = substr($resource,$pos+$offset+1,strlen($resource));

		switch ($method){
			case "GET":
				read($resource_id);
			break;
			case "POST":
				create();
			break;
			case "PUT":
				if($user["id"] != $resource_id) header("HTTP/1.1 401 Unathorized"); 
				else update($resource_id);
			break;
			case "DELETE":
				if($user["id"] != $resource_id) header("HTTP/1.1 401 Unathorized"); 
				else delete($resource_id);
			break;
		}
	}

	function create(){
		if(!isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["password_conf"]) || ($_POST["password"] == "") || ($_POST["email"] == "") || ($_POST["password_conf"] == "")) header("HTTP/1.1 400 Bad Request");
		else{
			$users = connect();
			if(isPresent($users, $_POST["email"])) header("HTTP/1.1 409 Conflict");
			else{
				$id = getMax($users)+1;
				$username = $_POST["email"];
				$salt = generateSalt(10);
				$encPSW = md5($salt.$put["password"]);
				$recover = false;
				$users[$username] = ["id" => $id, "username" => $username, "password" => $encPSW, "salt" => $salt, "recover"=>$recover];
				write($users);
				header("HTTP/1.1 201 Created");
			}
		}
	}

	function delete($res){
		if($res == "") header("HTTP/1.1 400 Bad Request");
		else{
			$users = connect();
			$del_user = getUser($users, $res);
			if($del_user == "") header("HTTP/1.1 400 Bad Request");
			else{
				deleteUser($users, $del_user);
				header("HTTP/1.1 200 OK");
			}
		}
	}

	function read($res){
		if($res == "") header("HTTP/1.1 400 Bad Request");
		else{
			$users = connect();
			$user = getUser($users, $res);
			if($user == "") header("HTTP/1.1 400 Bad Request");
			else{
				echo json_encode($user);
				header("HTTP/1.1 200 OK");
			}
		}
	}

	function update($res){
		parse_str(file_get_contents("php://input"),$put);
		if(!isset($put["email"]) || !isset($put["password"]) || ($put["password"] == "") || ($put["email"] == "") || ($res == "")) header("HTTP/1.1 400 Bad Request");
		else{
			$users = connect();
			if(alreadyPresent($users, $put["email"],$res)) header("HTTP/1.1 409 Conflict");
			else{
				$user = getUser($users, $res);
				if($user == "") header("HTTP/1.1 400 Bad Request");
				else{
					$user["salt"] = generateSalt(10);
					$user["password"] = md5($user["salt"].$put["password"]);
					$user["username"] = $put["email"];
					$user["recover"] = false;
					persistUser($users, $user);
					echo json_encode($user);
					header("HTTP/1.1 200 OK");
				}
			}
		}
	}
?>