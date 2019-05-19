<?php
session_start();
$s_id = $_COOKIE['id'];
$r_id = $_POST['id'];
$array = array();
$s_name = $_COOKIE['name'];
// $_SESSION['show'];
require("../db/db.php");
	$con = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);
	// $sender = $con -> query("SELECT uname FROM users WHERE sender_id = '$s_id' ");
	// while ($row = mysql_fetch_array($sender)) {
	// 		$s_name = $row[0];	
	// }
	if(!isset($_SESSION['show'])){
		$result = $con -> query("SELECT * FROM message WHERE (sender_id = '$s_id' AND receiver_id = '$r_id') ORDER BY id DESC LIMIT 10 ");
		while ($row = mysqli_fetch_array($result)) {
				$_SESSION['show'] = $row[0];
		}
	}
	else {
		$count = isset($_SESSION['show'])?$_SESSION['show']:'';
		$result = $con -> query("SELECT * FROM message WHERE sender_id = '$s_id' AND receiver_id = '$r_id' AND id < '$count' ORDER BY id DESC LIMIT 10 ");
		while ($row = mysqli_fetch_array($result)) {
				$array[] = $s_name.':'.$row[2].':'.$row[1];	
				$_SESSION['show'] = $row[0];
		}
	}
	echo json_encode($array);
	// echo json_encode($array);
?>