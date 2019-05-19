<?php
include_once "setting.php";

function getAllUserDetails($userid) {
	global $conn;
	$user_list = array();
    $friendIds = array();
    
    $sql = "SELECT to_id as friendid FROM `friends` WHERE from_id = '".$userid."' union SELECT from_id as myfrndids FROM `friends` WHERE to_id = '".$userid."' ";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_array($result)){
        array_push($friendIds, $row[0]);
    }
    
    $sql = "SELECT * FROM users where id <> ".$userid." ORDER BY RAND() limit 10";
	$result = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_array($result)){
        if(!in_array($row['id'], $friendIds)){
            $user_list[$row['id']] = array("fn"=>$row['user_firstname'],"ln"=>$row['user_lastname'],"e"=>$row['email'],"p"=>$row['password'],"a"=>$row['address'],"b"=>$row['birthdate'],"g"=>$row['gender'],"ph"=>$row['phone'],"pp"=>$row['profilepicture']);
        }		
	}	
	if(!empty($user_list)) {
		return json_encode($user_list);
	}else {
		return 0;
	}
}
function getAllOnlineFriends($userid) {
	global $conn;
	$friend_list = array();
	$sql = "(select DISTINCT users.id id, concat(users.user_firstname,' ',users.user_lastname) username, users.profilepicture avatar, users.email email, users.gender gender, users.phone phone,friends.relationship relationship from friends join users on friends.to_id = users.id where is_confirm = 1 and friends.from_id = '".mysqli_real_escape_string($conn,$userid)."') union (select DISTINCT users.id id, concat(users.user_firstname,' ',users.user_lastname) username, users.profilepicture avatar, users.email email, users.gender gender, users.phone phone,friends.relationship relationship from friends join users on friends.from_id = users.id where is_confirm = 1 and friends.to_id = '".mysqli_real_escape_string($conn,$userid)."') order by username asc";
        $result = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_array($result)){
		$friend_list[$row['id']] = array("id"=>$row['id'],"fn"=>$row['username'],"e"=>$row['email'],"g"=>$row['gender'],"ph"=>$row['phone'],"pp"=>$row['avatar'],"r"=>$row['relationship']);
	}	
	if(!empty($friend_list)) {
		return json_encode($friend_list);
	}else {
		return 0;
	}
}
function checkFriendship($userid,$friend_id) {
    global $conn;
    $sql = "SELECT * FROM `friends` where (`from_id` = $userid and `to_id` = $friend_id) or (`from_id` = $friend_id and `to_id` = $userid)";
    if($result = mysqli_query($conn,$sql)) {
        $friend = mysqli_fetch_array($result);
        if(!empty($friend)) {
            return array("is_friend" => $friend['is_confirm'],"relationship" => $friend['relationship']);
        }else {
            return false;
        }
    }
}
function getBlockFriends($userid) {
	global $conn;
	$friend_list = array();
	$sql = "select DISTINCT users.id id, concat(users.user_firstname,' ',users.user_lastname) username, users.profilepicture avatar, users.email email, users.gender gender, users.phone phone from blockuser join users on blockuser.to_id = users.id where blockuser.from_id = '".mysqli_real_escape_string($conn,$userid)."' and block_status = 1 order by username asc";
	//echo $sql;
    $result = mysqli_query($conn,$sql);
	if($result) {
        while($row = mysqli_fetch_array($result)){
    		$friend_list[$row['id']] = array("id"=>$row['id'],"fn"=>$row['username'],"e"=>$row['email'],"g"=>$row['gender'],"ph"=>$row['phone'],"pp"=>$row['avatar']);
    	}	
    	if(!empty($friend_list)) {
    		return json_encode($friend_list);
    	}else {
    		return 0;
    	}
    }
}

function getMyDeails($userid) {
	global $conn;
	$sql = "select * from users where id =".$userid;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(!empty($row)) {
    	return $row;
    }
}

if(isset($_REQUEST['unblock'])){
    $to_id = $_REQUEST['id'];
    session_start();
    $id = $_SESSION['userid'];
    $sql = "DELETE FROM blockuser WHERE to_id = ".$to_id." and from_id = ".$id." ";
    $result = mysqli_query($conn, $sql);
    exit;
}

if(isset($_REQUEST['search'])){
    $text = $_REQUEST['text'];
     $folder = "/Chat/images/";
     session_start();
     $id = $_SESSION['userid'];
     if(!empty($text)){
        $sql = "select * from users where user_firstname like '".$text."%' and id <> '".$id."' order by id desc";
        $result = mysqli_query($conn, $sql);
        $i = 1;
        $search_users = '';
        if($row = (mysqli_num_rows($result)) > 0){
                while ($frnds = mysqli_fetch_array($result)) {
                   //$search_users .= "<div style='margin-bottom: 5px;border-bottom: 1px solid #D3D3D3'><span class='image_span' style='margin-left: 4px;display: none;'><img src='".$folder.$row['profilepicture']."' height='20px' width='20px'/></span><div class='users'><b>".$frnds['user_firstname']." ".$frnds['user_lastname']."</b></div><div style='cursor: pointer' id = '".$frnds['address']."' class='add_div'><span class='add_content'>".$frnds['address']."</span></div></div>"; 
                    $search_users .= "<ul class='display_box' id='profile_".$frnds['id']." align='left'><a href = '/Chat/profilePage.php?id=".$frnds['id']."'><li>";
                    $search_users .= "<img src='".$folder.$frnds['profilepicture']."' height='20px' width='20px'/>";
                    $search_users .= "<b>".$frnds['user_firstname']." ".$frnds['user_lastname']."</b><br>";
                    $search_users .= " ".$frnds['address']." ";
                    $search_users .= "</li></a></ul>";
                }       
        }else{
            $search_users .= "<ul class='display_box' align='left'>No result Found</ul>";
        }
        echo $search_users;
     }
    exit;
}

if(isset($_REQUEST['blockuser'])){
    $to_id = $_REQUEST['to'];
    session_start();
    $id = $_SESSION['userid'];
    $sql = "select * from blockuser where `from_id` = ".$id." and `to_id` = ".$to_id." ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_num_rows($result);
    if($row < 1){
         $sql = "INSERT INTO blockuser (`from_id`,`to_id`) VALUES (".$id." , ".$to_id.")";
         $result = mysqli_query($conn, $sql);    
    }
    exit;
}

//if(isset($_REQUEST['chatbox_msg'])){
//    $message = $_REQUEST['message'];
//    $to = $_REQUEST['to'];
//    session_start();
//    $id = $_SESSION['userid'];
//    $time = time();
//    $sql = ("INSERT INTO chat (`from`, `to`, `message`, `sent`) VALUES ('".mysqli_real_escape_string($conn,$id)."','".mysqli_real_escape_string($conn,$to)."','".mysqli_real_escape_string($conn,$message)."',".time().")");
//    mysqli_query($conn,$sql);
//    exit;
//}


function getMyStatus($userid) {
	global $conn;
	$post = array();
	$sql = "select * from wall_update where to_id =".$userid;
    $result = mysqli_query($conn, $sql);
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
    	$post[$i] = $row;
    	$i++; 
    }
    return $post;
}

?>

