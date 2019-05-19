<?php
session_start();
$array = array();
include('../db/db.php');
$con = new mysqli(DBSERVER,DBUSER,DBPASS,DBNAME);
$my = $_COOKIE['name'];
            $names = $con -> query("SELECT * FROM users WHERE uname !=  '$my'");
            while($row = mysqli_fetch_array($names)){
                $array[] = $row[1].'-'.$row[0];
            }
            echo json_encode($array); 


?>