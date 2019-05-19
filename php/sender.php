<?php
session_start();
	// $time = $_POST['ctime'];
	// $message = $_POST['messg'];
	$array = array();
	$idd="";
	require("../db/db.php");
	$con = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);
	if(!isset($_COOKIE['id'])){
		$result = $con->query("SELECT * FROM message");
		while($row = mysqli_fetch_array($result)) {
			$array[] = $row[1]."-".$row[3].": ".$row[2];
			$idd = $row[0];
		}
		$_SESSION['m_id'] = $idd;
		// echo json_encode($array);

	}
	else {
		$id = $_SESSION['id'];
		$result = $con->query("SELECT * FROM messages WHERE sender_id = '$s_id' AND receiver_id = '$r_id' AND id > '$id'");
		while($row = mysqli_fetch_array($result)) {
			$array[] = $row[1]."-".$row[3].": ".$row[2];
			$_SESSION['m_id'] = $row[0];
		}
		echo json_encode($array);
	}


?>
