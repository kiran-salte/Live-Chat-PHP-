<?php
require("../main.php");
require("../db/db.php");
require("../db.php");
$con = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);
$time = $_POST['ctime'];
$s_id = $_COOKIE['id'];
$s_name = $_COOKIE['name'];
$r_id = $_POST['rid'];
$relation = checkFriendship($s_id,$r_id);
if($relation != false ) {
    if($relation['relationship'] == "family") {
        $message = sanitize_core($_POST['message'],$s_id ,$r_id,1);    
    }elseif($relation['relationship'] == "friends") {
        $message = sanitize_core($_POST['message'],$s_id ,$r_id,2);
    }else {
        $message = sanitize_core($_POST['message'],$s_id ,$r_id);
    }
}    

//$array = array();
// $s_name = "";

        $block = $con -> query("SELECT * FROM blockuser WHERE  from_id = ".$r_id." and to_id = ".$s_id." and block_status = 1");
        //echo "SELECT * FROM blockuser WHERE  from_id = ".$r_id." and to_id = ".$s_id." ";
        if(($row = mysqli_num_rows($block)) > 0){
            echo "b";
        }else{
            $q = $con -> query("INSERT INTO message(`name`,`message`,`time`,`sender_id`,`receiver_id`) VALUES('$s_name','$message','$time','$s_id','$r_id')");
            if($q) {
                    echo "success-".$message;
            }else{
                    echo 'failed: '.$con->error;
            }
        }

?>