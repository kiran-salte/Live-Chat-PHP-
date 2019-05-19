<?php
    include 'db.php';
    session_start();
    if(empty($_SESSION['userid'])) {
      header("Location: /Chat/index.php");
    }
    $userid = $_SESSION['userid'];
    $row = array();
    if(!empty($userid)) {
      $row = getMyDeails($userid);
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
                            <input type="text" class="form-control" placeholder="Search" name="srch-term" onkeyup="DelayedSubmission()" id="srch-term" autocomplete="off" />
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
                        <a href="#postModal" role="button" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Block List</a>
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
                          <li><a id = "logout" href="php/logout.php">Logout</a></li>
                        </ul>
                      </li>
                    </ul>
                  	</nav>
                </div>
                <!-- /top nav -->
              
                <div class="padding">
                  <div class="full col-sm-3">
                    <p><h4>People you may know</h4></p>
                    <?php if(!empty($allUsers)) { foreach ($allUsers as $key => $value) : ?>
                    <div id = "people_<?php echo $key;?>" style="padding-top: 13%;">
                      <div class="col-sm-7">
                        <img src="<?php echo $folder.$value['pp'];?>" width = "20px" height = "20px"><span><?php echo $value['fn'].' '.$value['ln'];?></span>
                      </div>
                      <div class="col-sm-5">
                        <button id="<?php echo $userid.'_'.$key;?>" class="btn btn-primary add_to_circle">Add to circle</button>
                      </div>  
                    </div>
                    <?php endforeach;}?>
                  </div>
                  <div class="full col-sm-5">
                    <!-- content -->                      
                    <div class="row">
                     <!-- main col left --> 
                      <div class="well"> 
                           <form id = "form_post" action="" method="post" class="form-horizontal" role="form">
                            <h4 >What's New</h4>
                             <div class="form-group" style="padding:14px;">
                              <textarea id = "status_message" class="form-control" placeholder="Update your status"></textarea>
                              <input type="hidden" id="to_id" value="<?php echo $userid;?>" />
                              <input type="hidden" id="from_id" value="<?php echo $userid;?>" />
                            </div>
                            <button id = "wall_post" class="btn btn-primary pull-right" type="button">Post</button><ul class="list-inline"><li><a href=""><i class="glyphicon glyphicon-upload"></i></a></li><li><a href=""><i class="glyphicon glyphicon-camera"></i></a></li><li><a href=""><i class="glyphicon glyphicon-map-marker"></i></a></li></ul>
                          </form>
                      </div>
                        <?php 
                          $post = getMyStatus($userid);
                          $post = array_reverse($post);
                          foreach ($post as $value) :
                            $userDetails = getMyDeails($value['from_id']);
                        ?>
                        <div class="well" ><span id = "<?php echo $value['post_id'];?>" class = "delete-post"  title="delete">x</span>
                          <img src="<?php echo $folder.$userDetails['profilepicture'];?>" height="40px" width="40px" class="left"><h4 ><?php echo $userDetails['user_firstname'].' '.$userDetails['user_lastname'];?></h4><br>
                          <p><?php echo $value['post_message']; ?></p>
                        </div>
                        <?php
                          endforeach;
                        ?>
                    </div>
                  </div>
                    <div class="full col-sm-2">
                    <p><h4>Friends List</h4></p>
                    <?php if($block == 0 && !empty($allfriends)){ ?>
                    <?php foreach ($allfriends as $key => $value) : 
                            if($value['r'] == 'friends') :
                    ?>
                    <div id = "<?php echo $key;?>" style="padding-top: 13%;">
                      <div class="col-sm-12">
                        <a id = "<?php echo $key;?>"  class = 'li' href="#"><img src="<?php echo $folder.$value['pp'];?>" width = "20px" height = "20px"><span><?php echo $value['fn'];?></span>
                         </a>
                      </div>
                    </div>
                    <?php 
                    endif;
                    endforeach;?>
                  <?php }else{ ?>
                  <p><h6>No Friend in a list</h6></p>
                    <?php } ?>
                  </div>
                  
                    <div class="full col-sm-2">
                    <h4>Family List</h4>
                    <?php if($block == 0 && !empty($allfriends)){ ?>
                    <?php foreach ($allfriends as $key => $value) : 
                            if($value['r'] == 'family') :
                    ?>
                    <div id = "<?php echo $key;?>" style="padding-top: 13%;">
                      <div class="col-sm-12">
                        <a id = "<?php echo $key;?>"  class = 'li' href="#"><img src="<?php echo $folder.$value['pp'];?>" width = "20px" height = "20px"><span><?php echo $value['fn'];?></span>
                         </a>
                      </div>
                    </div>
                    <?php
                    endif;
                    endforeach;?>
                  <?php }else{ ?>
                  <p><h6>No Friend in a list</h6></p>
                    <?php } ?>
                  </div>  
                </div><!-- /padding -->
            </div>
            <!-- /main -->
          
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
            window.location.reload();
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
            window.location.reload();
          }
        });
      });
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
    </script>
    <script type="text/javascript" src ="js/chat.js"></script>
	</body>
</html>