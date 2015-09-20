<?php
	function connect(){
		$path = getcwd()."/users.json";
		$users = json_decode(file_get_contents($path),true);
		return $users;
	}

	function isPresent($users, $username){
		return isset($users[$username]);
	}

	function getMax($users){
		$max = -1;
		foreach($users as $user) if($user["id"] > $max) $max = $user["id"];
		return $max;
	}

	function getUser($users, $id){
		$empty="";
		foreach ($users as $user) if($user["id"] == $id) return $user;
		return $empty;
	}

	function alreadyPresent($users, $username, $id){
		foreach ($users as $user) if(($user["username"]==$username) && ($user["id"]!=$id)) return true;
		return false;
	}

	function deleteUser($users,$user){
		unset($users[$user["username"]]);
		write($users);
	}

	function generateSalt($lenght){
		$salt = "";
		$chars = Array("a","b","c","d","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","w","y","x","z","A","B","C","D","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","W","Y","X","Z","1","2","3","4","5","6","7","8","9","0");
		$charsLength = count($chars);
		for ($i=0; $i<$lenght; $i++) $salt .= $chars[rand(0, $charsLength - 1)];
		return $salt;
	}

	function persistUser($users, $user){
		$old_user = getUser($user,$user["id"]);
		if($old_user != "") unset($users[$old_user["username"]]);
		$users[$user["username"]] = $user;
		write($users);
	}

	function write($users){
		$fp = fopen(getcwd()."/users.json", 'w');
		fwrite($fp, json_encode($users));
		fclose($fp);
	}
?>