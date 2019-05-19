<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."setting.php";
include_once dirname(__FILE__).DIRECTORY_SEPARATOR."functions.php";


if(isset($_REQUEST['userlist_admin'])){
	$sql = "select * from users";
	$query = mysqli_query($conn,$sql);
	
	while($row = mysqli_fetch_array($query)){
            echo '<td  style= "text-align: center;" >'.$row['user_firstname'].'</td>';
            echo '<td style= "text-align: center;" >'.$row['user_lastname'].'</td>';
            echo '<td  style= "text-align: center;">'.$row['email'].'</td>';
            echo '<td style= "text-align: center;">'.$row['address'].'</td>';
            echo '<td style= "text-align: center;">'.$row['gender'].'</td>';
            echo '<td style= "text-align: center;">'.$row['birthdate'].'</td>';
            echo '<td style= "text-align: center;">'.$row['phone'].'</td>';
            echo '</tr>';		
	}
	exit;
}

if(isset($_REQUEST['add'])){
    
    if($_REQUEST['bannedwords1']){
        //echo $_REQUEST['bannedwords1'];
        $bw1 = array();
        $bw1 = explode(",",$_POST['bannedwords1']);
        
        $sql = "DELETE FROM bannedword1";
        $query = mysqli_query($conn,$sql);
        
        for($i = 0; $i < count($bw1); $i++){
            if(!empty($bw1[$i])){
                $sql = ("INSERT INTO bannedword1 (`bword1`) VALUES ('$bw1[$i]')\n");
                $query = mysqli_query($conn,$sql);
            }
        }    
    }
    
    if($_REQUEST['bannedwords2']){
        //echo $_REQUEST['bannedwords1'];
        $bw2 = array();
        $bw2 = explode(",",$_POST['bannedwords2']);
        
        $sql = "DELETE FROM bannedword2";
        $query = mysqli_query($conn,$sql);
        
        for($i = 0; $i < count($bw2); $i++){
            if(!empty($bw2[$i])){
                $sql = ("INSERT INTO bannedword2 (`bword2`) VALUES ('$bw2[$i]')\n");
                $query = mysqli_query($conn,$sql);
            }
        }    
    }
    
    if($_REQUEST['bannedwords3']){
        //echo $_REQUEST['bannedwords1'];
        $bw3 = array();
        $bw3 = explode(",",$_POST['bannedwords3']);
        
        $sql = "DELETE FROM bannedword3";
        $query = mysqli_query($conn,$sql);
        
        for($i = 0; $i < count($bw3); $i++){
            if(!empty($bw3[$i])){
                $sql = ("INSERT INTO bannedword3 (`bword3`) VALUES ('$bw3[$i]')\n");
                $query = mysqli_query($conn,$sql);
            }
        }    
    }
    
    if($_REQUEST['familybanned']){
        //echo $_REQUEST['bannedwords1'];
        $fbw = array();
        $fbw = explode(",",$_POST['familybanned']);
        
        $sql = "DELETE FROM familybanned";
        $query = mysqli_query($conn,$sql);
        
        for($i = 0; $i < count($fbw); $i++){
            if(!empty($fbw[$i])){
                $sql = ("INSERT INTO familybanned (`famword3`) VALUES ('$fbw[$i]')\n");
                $query = mysqli_query($conn,$sql);
            }
        }    
    }
    
    
    header("Location: dashboardAdmin.php");
    exit;
}

if(isset($_REQUEST['graph_userlist'])){
    
    $sql = "select users.id, user_firstname, user_lastname, type1, type2, type3 from banword_count join users on banword_count.id = users.id";
    
    $query = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_array($query)){
            echo '<tr id="graph_'.$row['id'].'">';
            echo '<td  style= "text-align: center;" >'.$row['user_firstname'].' '.$row['user_lastname'].'</td>';
            echo '<td  style= "text-align: center;">'.$row['type1'].'</td>';
            echo '<td style= "text-align: center;">'.$row['type2'].'</td>';
            echo '<td style= "text-align: center;">'.$row['type3'].'</td>';
            echo '<td style= "text-align: center;"><h5><a href="JavaScript:newPopup(\'http://localhost/Chat/admin/graph.php?id='.$row['id'].'\');">View Graph</a></h5></td>';
            echo '</tr>';		
	}
	exit;
}

if(isset($_REQUEST['ban_userlist'])){
    
    $sql = "select * from blockuser where block_status = 1";
    
    $query = mysqli_query($conn,$sql);
    $count = 1;
    if($query) {
    	while($row = mysqli_fetch_array($query)){
                echo '<tr id="graph_'.$row['id'].'">';
                echo '<td  style= "text-align: center;">'.$count.'</td>';
                  $sql = "select user_firstname uf, user_lastname ul from users where id= ".$row['from_id']." ";
                  $q1 = mysqli_query($conn,$sql);
                  $from =  mysqli_fetch_array($q1);
                  $from_username = $from['uf'].' '.$from['ul'];
                echo '<td  style= "text-align: center;">'.$from_username.'</td>';
                  $sql = "select user_firstname uf, user_lastname ul from users where id= ".$row['to_id']." ";
                  $q2 = mysqli_query($conn,$sql);
                  $to =  mysqli_fetch_array($q2);
                  $to_username = $to['uf'].' '.$to['ul'];
                echo '<td style= "text-align: center;">'.$to_username.'</td>';
                echo '<td style= "text-align: center;"><input type="button" id="unblock_user_'.$row['to_id'].'" value="Unblock" onclick="unblock('.$row['from_id'].','.$row['to_id'].')"/></td>';
                echo '</tr>';
                $count++;
    	}
    }
	exit;
}

if(isset($_REQUEST['unblock'])){
      $sql = "DELETE FROM blockuser WHERE from_id = ".$_REQUEST['fromid']." and to_id = ".$_REQUEST['toid']." ";
      $query = mysqli_query($conn,$sql);
      exit;    
}     




 function addwords(){
     
    $words1 = array();
    $words2 = array();
    $words3 = array();
    $wordsfamily = array();
    
    $inputWords = explode(",",$_POST['bannedwords1']);
    foreach ($inputWords as $word) {
	$word = preg_replace("/\s+/"," ",str_replace("'","",$word));
	if (!empty($word) && $word != "'" && $word != "," && $word != " ") {
            array_push($words1,$word);
	}
    }
    $words1 = "'".implode("','",$words1)."'";
    
    if ($words1 == "''") { $words1 = ''; }
    
    
    //word type 2
   
    $inputWords = explode(",",$_POST['bannedwords2']);
    foreach ($inputWords as $word) {
	$word = preg_replace("/\s+/"," ",str_replace("'","",$word));
	if (!empty($word) && $word != "'" && $word != "," && $word != " ") {
            array_push($words2,$word);
	}
    }
    $words2 = "'".implode("','",$words2)."'";
    
    if ($words2 == "''") { $words2 = ''; }
   
    
    //word type 3
    
     $inputWords = explode(",",$_POST['bannedwords3']);
    foreach ($inputWords as $word) {
	$word = preg_replace("/\s+/"," ",str_replace("'","",$word));
	if (!empty($word) && $word != "'" && $word != "," && $word != " ") {
            array_push($words3,$word);
	}
    }
    $words3 = "'".implode("','",$words3)."'";
    
    if ($words3 == "''") { $words3 = ''; }
    
    
    //words for family
    
     $inputWords = explode(",",$_POST['familybanned']);
    foreach ($inputWords as $word) {
	$word = preg_replace("/\s+/"," ",str_replace("'","",$word));
	if (!empty($word) && $word != "'" && $word != "," && $word != " ") {
            array_push($wordsfamily,$word);
	}
    }
    $wordsfamily = "'".implode("','",$wordsfamily)."'";
    
    if ($wordsfamily == "''") { $wordsfamily = ''; }
   
    
       
    $data = '$bannedWords1 = array( '.$words1.' );'."\r\n".'$bannedWords2 = array( '.$words2.' );'."\r\n".'$bannedWords3 = array( '.$words3.' );'."\r\n".'$familyBanwords = array( '.$wordsfamily.' );';
    configeditor('BANNED',$data);
    header("Location: dashboardAdmin.php");
}