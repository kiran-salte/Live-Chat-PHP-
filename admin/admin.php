<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//echo 'Test Admin';
include '../setting.php';
$uname = $_REQUEST['adminUsername'];
$upass = $_REQUEST['adminPassword'];
if(isset($_REQUEST['adminUsername']) && isset($_REQUEST['adminPassword'])){
    if(ADMINUSER == $uname && ADMINPASS == $upass){
        header("Location: dashboardAdmin.php");
        exit;
    }else{
        header("Location: index.php");
    }
}

              