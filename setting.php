<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = 'chatapp';
$port = 3306;


global $conn;
// Create connection
$conn = mysqli_connect($servername,$username,$password,$dbname,$port);

if (mysqli_connect_errno($conn)) {
    $conn = mysqli_connect($servername,$username,$password,$dbname,$port,'/tmp/mysql5.sock');
}

// Define Username and Password
define("ADMINUSER","chat");
define("ADMINPASS","chat");

/* BANNED START */

$bannedWords1 = array(  );
$bannedWords2 = array( );
$bannedWords3 = array( );
$familyBanwords = array( );

/* BANNED END */



/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
