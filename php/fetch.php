<?php
session_start();
$s_id = $_COOKIE['id'];
$r_id = $_POST['rid'];
$array = array();
$s_name = $_COOKIE['name'];
require("../db/db.php");
	$con = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);
	$result = $con -> query("select id from message where `receiver_id` = '$s_id' order by id desc limit 1");
	$row = mysqli_fetch_array($result);
	$_SESSION['last_message'] = $row['id'];
	$result = $con -> query("SELECT * FROM message WHERE (sender_id = '$s_id' AND receiver_id = '$r_id') OR (sender_id = '$r_id' AND receiver_id = '$s_id')ORDER BY id DESC LIMIT 10 ");
	while ($row = mysqli_fetch_array($result)) {
		if($s_id == $row['sender_id']) {
			$array[] = $row['message'].'-s';
		}else if($s_id == $row['receiver_id']) {
			$array[] = $row['message'].'-r';
		} 
		$_SESSION['show'] = $row[0];
	}
	echo json_encode($array);
?>