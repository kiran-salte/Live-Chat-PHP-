<?php
session_start();
require("../db/db.php");
//echo 'id:'.$_COOKIE['id'];
$s_id = $_COOKIE['id'];
$s_name = $_COOKIE['name'];
$message = array();
$last_message = @$_SESSION['last_message'];
$con = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);
$q = $con -> query("select * from `message` where `receiver_id` = ".$s_id." order by id");
while ($result = mysqli_fetch_array($q)) {
	if($result['id'] > $last_message){
		$message[] = $result['message'].'-'.$result['sender_id'].'-'.$result['name'];
		$last_id = $result['id'];
	}
}
if(isset($last_id)) {
	$_SESSION['last_message'] = $last_id;
}
if(!empty($message)) {
	echo json_encode($message);
	exit;
}

?>