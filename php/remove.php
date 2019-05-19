<?php
session_start();
$r_id = $_POST['s_id'];
$s_id = $_COOKIE['id'];
require("../db/db.php");
	$con = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);
	$result = $con -> query("DELETE FROM `message` WHERE (`sender_id` = '$s_id' AND `receiver_id` = '$r_id') OR (`receiver_id` = '$s_id' AND `sender_id` = '$r_id')");
	if($result){
		echo "success";
	}
?>