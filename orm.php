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

	function generateSalt($lenght){
		$salt = "";
		$chars = Array("a","b","c","d","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","w","y","x","z","A","B","C","D","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","W","Y","X","Z","1","2","3","4","5","6","7","8","9","0");
		$charsLength = count($chars);
		for ($i=0; $i<$lenght; $i++) $salt .= $chars[rand(0, $charsLength - 1)];
		return $salt;
	}

	function persistUser($users, $user){
		$users[$user["username"]] = $user;
		write($users);
	}

	function write($users){
		$fp = fopen(getcwd()."/users.json", 'w');
		fwrite($fp, json_encode($users));
		fclose($fp);
	}
?>