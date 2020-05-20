<?php
# For database connection
function db_connect(){
	try{
		$result = mysql_connect('localhost','root','zhanghuanjin');
		mysql_select_db('video');
	}catch(Exception $e){
		echo $e -> message;
		exit;
	}

	if(!$result){
		return false;
	}
	return $result;
}

?>