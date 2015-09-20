<?php
	$current_script = basename(__FILE__);
	$resource = $_SERVER['REQUEST_URI'];
	$offset = 0;
	$pos = strpos($resource, $current_script);
	if($pos !== false) $offset = strlen($current_script);
	$params = substr($resource,$pos+$offset+1,strlen($resource));
	echo $params=="";


	/*echo "<br>".$_SERVER['REQUEST_METHOD'];
	$resource = $_SERVER['REQUEST_URI'];
	echo $resource;
	$path = explode('/',$resource);
	var_dump($path);
	$elem = array_shift($path);
	$elem = array_shift($path);
	echo $elem;
	
	var_dump($elem);

	$pos = strpos($resource,$current);

	echo "<br>".strpos($resource,$current);

	$params = substr($resource,$pos+strlen($current)+1,strlen($resource));
	echo "<br>".$params;

	echo "<br>".substr($resource,1,strlen($resource));*/

?>