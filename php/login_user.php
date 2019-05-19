<?php
    session_start();
    // unset($_SESSION['show']);
    include('../db/db.php');
    $con = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);
    $array = array();
    if(isset($_POST['submit'])){
            $name = $_POST['uname'];
            $pass = $_POST['password'];
            $result = $con -> query("SELECT * FROM users WHERE  uname = '$name' AND password = '$pass'");
            $count = mysqli_num_rows($result);
            if($count == 0){
                echo "invalid username or password";
            }
            else{
                while($row = mysqli_fetch_array($result)){
                   setcookie("id",$row[0],time()+3600);
                   setcookie("name",$row[1],time()+3600);
                   echo "successfully LoggedIn";
                }
            }
    }           
            
            // echo $my = $_SESSION['name'];
            // unset($array[$my]);
            // $array = array_values($array);
            // print_r($array);
        
?>