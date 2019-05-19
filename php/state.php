<?php 
session_start();
// set cookie
unset($_SESSION['show']); // unset session to assign new a value
if(isset($_POST['d_id'])) {
	$div_id = $_POST['d_id'];
	setcookie($div_id,$div_id);
}
$a = array();
// isset cookie 
require("../db/db.php");
	$con = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);
	$result = $con -> query("SELECT id,user_firstname,user_lastname FROM users");
	while($row = mysqli_fetch_array($result)){
		$i = $row['user_firstname'].$row['user_lastname']."-".$row['id'];
		if(isset($_COOKIE[$i])) {
			$a[] = $_COOKIE[$i];
		}
	}
	echo json_encode($a);
// close divchat n unset cookie
if(isset($_POST['name']) && isset($_POST['id'])) {
		$name = $_POST['name'];
		$id = $_POST['id'];
		$j = $name."-".$id;
		$time = time()-3600;
		setcookie($j,'',$time);
		unset($_SESSION['show']); // unset session to assign new a value

	}
?>