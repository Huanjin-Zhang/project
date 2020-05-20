<?php
	//Create connection
	$conn = new mysqli("127.0.0.1:3306","root","zhanghuanjin","dbproject1");
	//Check connection
	if ($conn->connect_error){
		die("Connection failed: ". $conn->connect_error);
		exit;
	}
?>