<?php
    include 'db.php';
    session_start();
    if(empty($_SESSION['userid'])) {
      header("Location: /Chat/index.php");
    }
    
    $userid = $_SESSION['userid'];
    if(!empty($_GET['id'])) {
      $_SESSION['friends_profile_id'] = $_GET['id'];  
    }
    
    $row = array();
    $friend_details = array();
    if(!empty($userid)) {
      $row = getMyDeails($userid);
      $friend_details = getMyDeails($_SESSION['friends_profile_id']);
      $users = getAllUserDetails($userid);
      $allUsers = json_decode($users,true);
      $friend = getAllOnlineFriends($userid);
      $allfriends = json_decode($friend,true);
      $block = getBlockFriends($userid);
      $blockeduser = json_decode($block,true);  
    }
    $folder = "/Chat/images/";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Live Chat</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
                
<link rel="stylesheet" type="text/css" href="css/chat.css">
	</head>
	<body>
<div class="wrapper">
    <div class="box">
        <div class="row row-offcanvas row-offcanvas-left">
          <!-- main right col -->
            <div class="column col-sm-12 col-xs-12" id="main">
                
                <!-- top nav -->
              	<div class="navbar navbar-blue navbar-static-top">  
                    <div class="navbar-header">
                      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle</span>
                        <span class="icon-bar"></span>
          				<span class="icon-bar"></span>
          				<span class="icon-bar"></span>
                      </button>
                      <a href="/Chat/Home.php" class="navbar-brand logo">L</a>
                  	</div>
                  	<nav class="collapse navbar-collapse" role="navigation">
                    <form class="navbar-form navbar-left">
                        <div class="input-group input-group-sm" style="max-width:360px;">
                            <input  id="srch-term" type="text" class="form-control" placeholder="Search" onkeyup="DelayedSubmission()" autocomplete="off" />
                          <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                          </div>
                        </div>
                        <div id="display"></div>
                    </form>
                    <ul class="nav navbar-nav">
                      <li>
                        <a href="/Chat/profilePage.php?id=<?php echo $_SESSION['userid']; ?>" id = "profile-link"><img src="<?php echo $folder.$row['profilepicture'];?>" width = "20px" height= "20px"><span><?php echo $row['user_firstname'];?></span></a>
                      </li>
                      <li>
                        <a href="/Chat/Home.php"><i class="glyphicon glyphicon-home"></i> Home</a>
                      </li>
                      <li>
                        <a href="#postModal" role="button" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Block</a>
                      </li>
                    </ul>
                    <ul class="nav navbar-nav">
                      <li class="dropdown">
                        <a href="#" id = "friend_request" class="dropdown-toggle" data-toggle="dropdown">Friends</a>
                        <ul id = "friend_request_dropdown" class="dropdown-menu" >
                        </ul>
                      </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span>&xvee;</span></a>
                        <ul class="dropdown-menu">
                          <li><a id = "logout" href="">Logout</a></li>
                        </ul>
                      </li>
                    </ul>
                  	</nav>
                </div>
                <!-- /top nav -->
              
                <div class="padding">
                  <div class="full col-sm-3">
                    <div id = "people_<?php echo $key;?>" >
                      <div class="col-sm-7">
                          <img src="<?php echo $folder.$friend_details['profilepicture'];?>" width = "150px" height = "150px"><span><br><?php echo $friend_details['user_firstname'].' '.$friend_details['user_lastname'];?></span>
                          </br>
                          <?php if($_SESSION['friends_profile_id'] == $_SESSION['userid']) :?>
                            <p><a href="#editModal" role="button" data-toggle="modal" class="btn btn-primary">Edit profile</a></p>
                          </br>
                          <?php endif;?>
                          <?php $is_friend = checkFriendship($userid,$_SESSION['friends_profile_id']);
                            if($is_friend['is_friend'] == 1) : ?>
                              <button id = "unfriend" class="btn btn-primary">delete friendship</button>
                            <?php /*elseif($is_friend === false) : */?>
                              <!-- <button id = "<?php echo $userid.'_'.$_SESSION['friends_profile_id']?>" class="btn btn-primary add_to_circle">Add to circle</button> -->
                            <?php endif;?>
                      </div> 
                    </div>
                    
                    
                  </div>
                  <div class="full col-sm-6">
                    <!-- content -->                      
                    <div class="row">
                     <!-- main col left --> 
                      <div class="well"> 
                           <form id = "form_post" action="" method="post" class="form-horizontal" role="form">
                             <div class="form-group" style="padding:14px;">
                              <textarea id = "status_message" class="form-control" placeholder="Write Something..."></textarea>
                              <input type="hidden" id="to_id" value="<?php echo $_SESSION['friends_profile_id'];?>" />
                              <input type="hidden" id="from_id" value="<?php echo $userid;?>" />
                            </div>
                            <button id = "wall_post" class="btn btn-primary pull-right" type="button">Post</button><ul class="list-inline"><li><a href=""><i class="glyphicon glyphicon-upload"></i></a></li><li><a href=""><i class="glyphicon glyphicon-camera"></i></a></li><li><a href=""><i class="glyphicon glyphicon-map-marker"></i></a></li></ul>
                          </form>
                      </div>
                        <?php 
                          $post = getMyStatus($_SESSION['friends_profile_id']);
                          $post = array_reverse($post);
                          foreach ($post as $value) :
                            $userDetails = getMyDeails($value['from_id']);
                        ?>
                        <div class="well">
                          <span id = "<?php echo $value['post_id'];?>" class = "delete-post"  title="delete">x</span>
                          <img src="<?php echo $folder.$userDetails['profilepicture'];?>" height="40px" width="40px" class="left"><h4 ><?php echo $userDetails['user_firstname'].' '.$userDetails['user_lastname'];?></h4><br>
                          <p><?php echo $value['post_message']; ?></p>
                        </div>
                        <?php
                          endforeach;
                        ?>
                    </div>
                  </div>
                    <div class="full col-sm-3">
                    
                  </div>
                    
                </div><!-- /padding -->
            </div>
            <!-- /main -->
          
        </div>
    </div>
</div>
<!--edit profile modal-->
<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <button id = "close_post" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      Edit your profile
      </div>
      <div class="modal-body">
          <form id="editprofile" name="register" method="post" action="main.php" class="form-group" enctype="multipart/form-data">           
            <span><label for="exampleInputEmail1">First Name:</label><input id="fname" name="fname" value="<?php echo $row['user_firstname'];?>" placeholder="Firstname" required="" tabindex="1" type="text"></span><br>
            
            <span><label for="exampleInputEmail1">Last Name :</label><input id="lname" name="lname" placeholder="Last name" required="" tabindex="1" value="<?php echo $row['user_lastname'];?>" type="text"></span><br>

            <span><label for="exampleInputEmail1">Email :</label><input id="email" name="email" value="<?php echo $row['email'];?>" placeholder="example@domain.com" required="" type="email" disabled></span><br>
                                 
            <span><label for="exampleInputEmail1">Date of birth :</label><input type="date" id="datepicker" value="<?php echo $row['birthdate'];?>" name="dob" placeholder="Click here to pick BirthDate"/></span><br>
           
            <span><input type="Radio" id = "male" Name="gender" Value="Male" <?php if($row['gender']=='Male'){echo "checked";};?>><label for="exampleInputEmail1">Male</label><input type="Radio" id = "male" Name="gender" Value="Female"><label for="exampleInputEmail1" <?php if($row['gender']=='Female'){echo "checked";};?>>Female</label></span><br>

            <span><label for="exampleInputEmail1">Address:</label><input id="address" name="address" placeholder="Address" required="" type="text" value="<?php echo $row['address'];?>"></span><br>
            
            <span><label for="exampleInputEmail1">Phone:</label><input id="phone" value="<?php echo $row['phone'];?>" name="phone" placeholder="phone number" required="" type="text" ></span><br>
           
            <span><label for="exampleInputEmail1">Profile picture:</label><input type="file" name="profilepic" id="fileToUpload"></span><br>
            <input type="hidden" id = "update_profile_id" name="update_profile_id" tabindex="5" value="<?php echo $_SESSION['userid'];?>">
          </form> 
      </div>
      <div class="modal-footer">
          <div>
          <button id = "modal_profile_submit" class="btn btn-primary btn-sm" data-dismiss="modal" aria-hidden="true">Save</button>
      </div>  
      </div>
  </div>
  </div>
</div>


<!--post modal-->
<div id="postModall" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <button id = "close_post" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			Update Status
      </div>
      <div class="modal-body">
          <form id = "modal_form" class="form center-block" >
            <div class="form-group">
              <textarea id = "modal_status_message" class="form-control input-lg" autofocus="" placeholder="What do you want to share?"></textarea>
              <input type="hidden" id="to_id" value="<?php echo $userid;?>" />
              <input type="hidden" id="from_id" value="<?php echo $userid;?>" />
            </div>
          </form>
      </div>
      <div class="modal-footer">
          <div>
          <button id = "modal_post_submit" class="btn btn-primary btn-sm" data-dismiss="modal" aria-hidden="true">Post</button>
            <ul class="pull-left list-inline"><li><a href=""><i class="glyphicon glyphicon-upload"></i></a></li><li><a href=""><i class="glyphicon glyphicon-camera"></i></a></li><li><a href=""><i class="glyphicon glyphicon-map-marker"></i></a></li></ul>
		  </div>	
      </div>
  </div>
  </div>
</div>
<!--post modal-->
<div id="postModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
      Block users
      </div>
      <div class="modal-body" style="min-height:50px;">
            <?php if(!empty($blockeduser)){ ?>
             <?php foreach ($blockeduser as $key => $value) : ?>
                    <div id = "<?php echo $key;?>" style="margin:10px 10px;">
                      <div class="col-sm-8">
                        <img src="<?php echo $folder.$value['pp'];?>" width = "20px" height = "20px"><span><?php echo $value['fn'];?></span>
                      </div>
                      <div class="col-sm-4">
                          <button class="btn btn-primary" onclick="unblock('<?php echo $value['id'];?>')">Unblock</button>
                      </div>
                    </div>
                    <?php endforeach;?>
                  <?php }else{ ?>
                  <p><h5>  No Block users in a list</h5></p>
                    <?php } ?>
      </div>
      <div class="modal-footer">
          <div>
          <button class="btn btn-primary btn-sm" data-dismiss="modal" aria-hidden="true">Close</button>
            <ul class="pull-left list-inline"><li><a href=""><i class="glyphicon glyphicon-upload"></i></a></li><li><a href=""><i class="glyphicon glyphicon-camera"></i></a></li><li><a href=""><i class="glyphicon glyphicon-map-marker"></i></a></li></ul>
      </div>  
      </div>
  </div>
  </div>
</div>

	<!-- script references -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/scripts.js"></script>
    <script>
      function unblock(id){
        jQuery.ajax({
          url: 'db.php',
          type: 'POST',
          data: {unblock: "unblock",id: id},
          success: (function(data){
            location.reload();
           })
        });
      }
      $("#friend_request").click(function() {
        var id = "<?php echo $_SESSION['userid'] ?>";
        jQuery.ajax({
          url: 'main.php',
          type: 'POST',
          data: {id_friend_request_list:id},
          success: function(data){
            data =  JSON.parse(data);
            if(data != "") {
              $('#friend_request_dropdown').html('');
              $.each(data,function(index,element) {
                if($('#friend_request_dropdown #'+element.id).length == 0) {
                 $('#friend_request_dropdown').append('<li id = "'+element.id+'"><a href="#"><img src="/Chat/images/'+element.avatar+'" width = "30px" height = "30px"> '+element.username+' <button id = "add_friend" data = "'+element.id+'">Add to '+element.relationship+'</button>&nbsp<button id = "reject_friend" data = "'+element.id+'">Reject</button></a></li>');
                }
              });
            }else {
              if($('#friend_request_dropdown #no_more').length == 0) {
                $('#friend_request_dropdown').append('<li id="no_more"><a href="#">No more pending request.</a></li>');
                $('#friend_request').css('color','#fff');
              }
            }
          }
        });
      });
      $(document).on("click","#add_friend",function() {
        var friend_id = $(this).attr('data');
        var myid = "<?php echo $_SESSION['userid'] ?>";
        jQuery.ajax({
          url: 'main.php',
          type: 'POST',
          data: {add_friend:'',friend_id:friend_id,myid:myid},
          success: function(data){
          }
        });
      });
      $(document).on("click","#reject_friend",function() {
        var friend_id = $(this).attr('data');
        var myid = "<?php echo $_SESSION['userid'] ?>";
        jQuery.ajax({
          url: 'main.php',
          type: 'POST',
          data: {reject_friend:'',friend_id:friend_id,myid:myid},
          success: function(data){
          }
        });
      });/*
      function DelayedSubmission() {
          var input = $('#srch-term').val();
          console.log(input);
        }*/
        
        
        
      setInterval(function() {
        var id = "<?php echo $_SESSION['userid'] ?>";
        jQuery.ajax({
          url: 'main.php',
          type: 'POST',
          data: {id_friend_request:id},
          success: function(data){
            if(data != 'null' ) {
              $('#friend_request').css('color','red');
            }
          }
        });
      },50000);            
      $('#unfriend').click(function() {
        var id = "<?php echo $_SESSION['friends_profile_id'] ?>";
        jQuery.ajax({
          url: 'main.php',
          type: 'POST',
          data: {id_friend_remove:id},
          success: function(data){
            window.location.reload();
          }
        });
      });
      $('#modal_profile_submit').click(function() {
        var fname = $('#fname').val(),
         lname = $('#lname').val(),
         email = $('#email').val(),
         dob = $('#datepicker').val(),
         phone = $('#phone').val();
        if($('#update_profile_id').val()!= '') {
         if(fname == '') {
          alert("Firstname can not be empty!");
         }else if(lname == '') {
          alert("Lastname can not be empty!");
         }else if(phone == '') {
          alert("Phone number can not be empty!");
         }else if(dob == '') {
          alert("Please enter Date of birth.");
         }else{
          $('#editprofile').submit();
         }
        }
      });
    </script>
    <script type="text/javascript" src ="js/chat.js"></script>
	</body>
</html>