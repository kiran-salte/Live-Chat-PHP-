<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "setting.php";
session_start();
$username =  $_SESSION['username'];


if(isset($_POST['id_friend_remove'])) {
    $id_friend_remove = mysqli_real_escape_string($conn,$_POST['id_friend_remove']);
    $sql = ("delete from friends where to_id = '".$id_friend_remove."' or from_id = '".$id_friend_remove."'");
    mysqli_query($conn,$sql);
    exit();
}


if(isset($_POST['update_profile_id'])) {
    if(!empty($_POST['update_profile_id']) && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['dob']) && !empty($_POST['phone'])){
        $fname = $_REQUEST['fname']; 
        $lname = $_REQUEST['lname'];
        $address = $_REQUEST['address'];
        $birthdate = date("Y-m-d", strtotime($_REQUEST['dob']));
        $gender = $_REQUEST['gender'];
        $phone =  $_REQUEST['phone'];
        $profilepic = $_FILES['profilepic']['name'];
        
       $folder="/xampp/htdocs/Chat/images/";
       if(!empty($_FILES['profilepic']['tmp_name'])) {
        move_uploaded_file($_FILES['profilepic']['tmp_name'], "$folder".$_FILES['profilepic']['name']);
        $pic = ",`profilepicture` = '".mysqli_real_escape_string($conn,$profilepic)."'";
       }
       
        $sql = ("UPDATE `users` set `user_firstname` = '".mysqli_real_escape_string($conn,$fname)."',`user_lastname` = '".mysqli_real_escape_string($conn,$lname)."',`address` = '".mysqli_real_escape_string($conn,$address)."',`birthdate` = '".$birthdate."',`gender` = '".mysqli_real_escape_string($conn,$gender)."',`phone` = '".$phone."' ".$pic." WHERE id = '".$_POST['update_profile_id']."'");
        if(mysqli_query($conn,$sql)) {
        /*    echo "<script type='text/javascript'>alert('You have been successfully Register..');</script>";*/
            header("Location: profilePage.php");
            exit;
        }else {
            die(mysqli_connect_error($conn));
        }
    }
}
if(isset($_REQUEST['logout'])){
    session_start();
    unset($_SESSION['userid']);
    echo 'logout';
    //header("Location: index.php");
    exit;
}

if(isset($_REQUEST['wallmsg'])){
    $message = $_REQUEST['message'];
    $userid = $_REQUEST['id'];
    $time = time();
    $sql = ("INSERT INTO publicmessage (userid,sendername,message,time) values ('".mysqli_real_escape_string($conn,$userid)."','".mysqli_real_escape_string($conn,$username)."','".mysqli_real_escape_string($conn,$message)."',".time().")");
    mysqli_query($conn,$sql);
    exit;
}
if(isset($_POST['status_message'])) {
    if(!empty($_POST['status_message']) && !empty($_POST['to_id']) && !empty($_POST['from_id'])) {
        $update = 0;
        $time = time();
        if ($_POST['from_id'] == $_POST['to_id']) {
            $update = sanitize_status($_POST['status_message']);
        }else {
            $sql = "SELECT * FROM `friends` WHERE (from_id = '".$_POST['from_id']."' and to_id = '".$_POST['to_id']."') or (from_id = '".$_POST['to_id']."' and to_id = '".$_POST['from_id']."') and is_confirm = 1 ";
            //echo $sql;
            if ($result = mysqli_query($conn,$sql)) {
                $count = mysqli_num_rows($result);
                if($count > 0){
                    $update = sanitize_status($_POST['status_message']);
                }else{
                    echo 'nobuddy';
                    exit;
                }   
            }
        }
        //$update = sanitize_status($_POST['status_message'] , $_POST['to_id'] , $_POST['from_id']);
        if($update == 1) {
            echo 'failed';
            exit;
        }else {
            $sql = ("INSERT INTO wall_update (from_id,to_id,post_message,time) values ('".mysqli_real_escape_string($conn,$_POST['from_id'])."','".mysqli_real_escape_string($conn,$_POST['to_id'])."','".mysqli_real_escape_string($conn,$_POST['status_message'])."',".$time.")");
            if(mysqli_query($conn,$sql)) {
                echo 'success';
                exit;
            }
        }
    }
}
if(isset($_POST['relationship_friends'])) {
    /*mysqli_real_escape_string($conn,$message)*/
    if(!empty($_POST['from']) && !empty($_POST['to'])) {
        $sql = ("select id from friends where from_id = ".mysqli_real_escape_string($conn,$_POST['from'])." and to_id = ".mysqli_real_escape_string($conn,$_POST['to']));
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_array($result);
        if(empty($row)) {
            $sql = ("INSERT INTO `friends`(`from_id`, `to_id`, `is_confirm`, `relationship`) VALUES ('".mysqli_real_escape_string($conn,$_POST['from'])."','".mysqli_real_escape_string($conn,$_POST['to'])."','0','".mysqli_real_escape_string($conn,$_POST['relationship_friends'])."') ");
            if(mysqli_query($conn,$sql)) {
                echo  'success';
            }
        }
    }
    exit;
}

if(isset($_POST['id_friend_request_list']) && !empty($_POST['id_friend_request_list'])) {
    $userid = $_POST['id_friend_request_list'];
    $friends_array = array();
    $sql = ("select DISTINCT users.id id, concat(users.user_firstname,' ',users.user_lastname) username, users.profilepicture avatar, friends.relationship relationship from friends join users on friends.from_id = users.id where is_confirm = 0 and friends.to_id = ".mysqli_real_escape_string($conn,$userid)." order by username asc");
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_array($result)) {
        $friends_array[] = $row; 
    }
    echo json_encode($friends_array);
    exit;
}
if(isset($_POST['id_friend_request']) && !empty($_POST['id_friend_request'])) {
    $userid = $_POST['id_friend_request'];
    $sql = ("select id from friends where to_id = ".mysqli_real_escape_string($conn,$userid)." and is_confirm = 0");
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);
    echo json_encode($row['id']);
    exit;
}
if(isset($_POST['add_friend'])) {
    if(!empty($_POST['friend_id']) && !empty($_POST['myid'])) {
        $sql = ("UPDATE `friends` SET `is_confirm`= 1 WHERE from_id = ".mysqli_real_escape_string($conn,$_POST['friend_id'])." AND  to_id = ".mysqli_real_escape_string($conn,$_POST['myid'])."");
        if(mysqli_query($conn,$sql)) {
            echo $_POST['friend_id'];
        }else {
            echo "failed";
        }
    }
}
if(isset($_POST['reject_friend'])) {
    if(!empty($_POST['friend_id']) && !empty($_POST['myid'])) {
        $sql = ("DELETE FROM `friends` WHERE from_id = ".mysqli_real_escape_string($conn,$_POST['friend_id'])." AND  to_id = ".mysqli_real_escape_string($conn,$_POST['myid'])."");
        if(mysqli_query($conn,$sql)) {
            echo 'success';
        }else {
            echo "failed";
        }
    }
}

function loginFuntion($username,$password) {
    $userid = 0;
    include_once 'setting.php';
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
	$sql = ("SELECT * FROM `users` WHERE email ='".$username."'");
    } else {
	$sql = ("SELECT * FROM `users` WHERE user_firstname ='".$username."'");
    }
    
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($result);
    if($row['password'] == $password) {
        $userid = $row['id'];
    }
    //echo "<script type='text/javascript'>alert('".$userid."');</script>";
    return $userid;
    
}

function getWords(){
    include_once 'setting.php';
    global $bannedWords1;
    global $bannedWords2;
    global $bannedWords3;
    global $familyBanwords;
    global $conn;
    
    $sql = "SELECT * FROM bannedword1";
    $query = mysqli_query($conn,$sql);

     while ($row = mysqli_fetch_assoc($query)) {
        array_push($bannedWords1,$row['bword1']);
    }
    
    /**********************************/
    
    $sql = "SELECT * FROM bannedword2";
    $query = mysqli_query($conn,$sql);

     while ($row = mysqli_fetch_assoc($query)) {
        array_push($bannedWords2,$row['bword2']);
    }
    
    /**********************************/
    
    $sql = "SELECT * FROM bannedword3";
    $query = mysqli_query($conn,$sql);

     while ($row = mysqli_fetch_assoc($query)) {
        array_push($bannedWords3,$row['bword3']);
    }
    
    /*****************************************/
    
     $sql = "SELECT * FROM familybanned";
     $query = mysqli_query($conn,$sql);

    while ($row = mysqli_fetch_assoc($query)) {
        array_push($familyBanwords,$row['famword3']);
    }
    
}


function sanitize_status($text){
    global $bannedWords1;
    global $bannedWords2;
    global $bannedWords3;
    global $familyBanwords;

    getWords();
       
    $count1 = 0;
    $count1 = getCount($bannedWords1, $text);
           //echo $text." count ".$count1;
    $text = trim($text);
           
           $count2 = 0;
           $count2 = getCount($bannedWords2, $text);
    $text = trim($text);
           
           $count3 = 0;
           $count3 = getCount($bannedWords3, $text);
           /*for ($i=0;$i < count($bannedWords3);$i++) {
               if (stripos($text,$bannedWords3[$i]) || in_array(strtolower($text), $bannedWords3)) {
                  $count3++; 
               }
    }*/
    $text = trim($text);
           
           //echo "count1 ".$count1."count2 ".$count1."count3 ".$count3;
           
           if($count1 > 0 || $count2 > 0 || $count3 > 0){
               saveWordCount($count1,$count2,$count3);
               return 1;
           }else{
                return $text;
           }
      
}

function sanitize_core($text,$to_id, $from_id,$f=0) {
    global $bannedWords1;
    global $bannedWords2;
    global $bannedWords3;
    global $familyBanwords;
    global $friendsBanwords;
    $total_ban = 0;   
    
    getWords();
    
    $count1 = 0;
    $count1 = getCount($bannedWords1, $text);
    for ($i=0;$i < count($bannedWords1);$i++) {
            /*if (strpos($text,$bannedWords1[$i]) !== false || in_array(strtolower($text), $bannedWords1)) {
               $count1++; 
            }*/
            $text = str_ireplace(' '.$bannedWords1[$i].' ',' '.$bannedWords1[$i][0].str_repeat("*",strlen($bannedWords1[$i])-1).' ',' '.$text.' ');
    }
    $text = trim($text);
        
        $count2 = 0;
        $count2 = getCount($bannedWords2, $text);
        for ($i=0;$i < count($bannedWords2);$i++) {
            /*if (strpos($text,$bannedWords2[$i]) !== false || in_array(strtolower($text), $bannedWords2)) {
               $count2++; 
            }*/
        $text = str_ireplace(' '.$bannedWords2[$i].' ',' '.$bannedWords2[$i][0].str_repeat("*",strlen($bannedWords2[$i])-1).' ',' '.$text.' ');
    }
    $text = trim($text);
        
        $count3 = 0;
        $count3 = getCount($bannedWords3, $text);
        for ($i=0;$i < count($bannedWords3);$i++) {
            /*if (strpos($text,$bannedWords3[$i]) !== false || in_array(strtolower($text), $bannedWords3)) {
               $f
               count3++; 
            }*/
        $text = str_ireplace(' '.$bannedWords3[$i].' ',' '.$bannedWords3[$i][0].str_repeat("*",strlen($bannedWords3[$i])-1).' ',' '.$text.' ');
    }
    $text = trim($text);
    
    if($f == 1) {
        for ($i=0;$i < count($familyBanwords);$i++) {
                $text = str_ireplace(' '.$familyBanwords[$i].' ',' '.$familyBanwords[$i][0].str_repeat("*",strlen($familyBanwords[$i])-1).' ',' '.$text.' ');
        }
        $text = trim($text);   
    }elseif($f = 2){
        for ($i=0;$i < count($friendsBanwords);$i++) {
                $text = str_ireplace(' '.$friendsBanwords[$i].' ',' '.$friendsBanwords[$i][0].str_repeat("*",strlen($friendsBanwords[$i])-1).' ',' '.$text.' ');
        }
        $text = trim($text);   
    }

    $total_ban = $count1 + $count2 + $count3;
    //echo "Total". $total_ban. " count1 ". $count1. " count2 ".$count2 ." count3 ".$count3;
    saveWordCount($count1,$count2,$count3);
    blockCondition($total_ban,$to_id,$from_id);       
    return $text;
}

function getCount($bannedWords1, $text){
    $matches = array();
    $matchFound = preg_match_all(
                    "/\b(" . implode($bannedWords1,"|") . ")\b/i", 
                    $text, 
                    $matches
                  );
        $c = 0;
        if ($matchFound) {
          $words = array_unique($matches[0]);
          foreach($words as $word) {
            $c++;
          }
          return $c;
        }
}

function saveWordCount($count1,$count2,$count3) {
    global $conn;
    $type1 = 0;
    $type2 = 0;
    $type3 = 0;
    $sql = "select * from banword_count where id = ".$_SESSION['userid'];
    $query =  mysqli_query($conn,$sql);

    $row = mysqli_fetch_assoc($query);
    $row_count = mysqli_num_rows($query);
    if($row_count > 0){
        $type1 = $row['type1'] + $count1;
        $type2 = $row['type2'] + $count2;
        $type3 = $row['type3'] + $count3;  

        $sql = "UPDATE banword_count SET type1 = ".$type1.", type2 = ".$type2." , type3 = ".$type3." WHERE id= ".$_SESSION['userid']."";
        $query = mysqli_query($conn,$sql); 
    }else{
        $type1 = $row['type1'] + $count1;
        $type2 = $row['type2'] + $count2;
        $type3 = $row['type3'] + $count3;
       $sql = "INSERT into banword_count(id, type1 , type2, type3) values(".$_SESSION['userid']. " , ".$type1." , ".$type2." , ".$type3.")";
       $query = mysqli_query($conn,$sql); 
    }
}


function blockCondition($total_ban,$to_id,$from_id){
    global $conn;
    $total = 0;
    if($total_ban != 0){
        if($total_ban > 3 ){
            $sql = "select * from blockuser where from_id = ".$from_id." and to_id = ".$to_id." and block_status = 0";
            $query =  mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($query);
            $row_count = mysqli_num_rows($query);
            if($row_count > 0){
                 $sql = "UPDATE blockuser SET block_status = 1, block_count = ".$total_ban."  WHERE from_id = ".$from_id." and to_id = ".$to_id." ";
                 $query = mysqli_query($conn,$sql); 
             }else{
                 $sql = "INSERT INTO blockuser (`from_id`,`to_id`,`block_count`,`block_status`) VALUES (".$from_id." , ".$to_id.", ".$total_ban." ,1)";
                 $result = mysqli_query($conn, $sql); 
             }  
        }else{
            $sql = "select * from blockuser where from_id = ".$from_id." and to_id = ".$to_id." ";
            $query =  mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($query);
            $row_count = mysqli_num_rows($query);
            $total = $total_ban + $row['block_count'];
            if($row_count > 0){
 
                if($total > 3){

                    $sql = "UPDATE blockuser SET block_status = 1, block_count = ".$total." WHERE from_id = ".$from_id." and to_id = ".$to_id." ";

                    $query = mysqli_query($conn,$sql); 
                }else{

                    $sql = "UPDATE blockuser SET block_status = 0, block_count = ".$total." WHERE from_id = ".$from_id." and to_id = ".$to_id." ";
                    $query = mysqli_query($conn,$sql); 
                }     
            }else{

                $sql = "INSERT INTO blockuser (`from_id`,`to_id`,`block_count`,`block_status`) VALUES (".$from_id." , ".$to_id." , ".$total.", 0)";
                $result = mysqli_query($conn, $sql); 
            }
        }
    }
}
  

if(isset($_POST['post_id'])) {
    $sql = "DELETE FROM `wall_update` WHERE `post_id` = ".mysqli_real_escape_string($conn,$_POST['post_id']);
    if(mysqli_query($conn,$sql)) {
        echo "success";
    }else {
        echo "failed";
    }
}
