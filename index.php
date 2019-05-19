<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include "setting.php";
session_start();
if(isset($_REQUEST['login'])){
    if(!empty($_REQUEST['username']) && !empty($_REQUEST['password'])){
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $userid = 0;
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $sql = ("SELECT * FROM `users` WHERE email ='".$username."'");
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_array($result);
            if($row['password'] == $password) {
                $userid = $row['id'];
                $uname = $row['user_firstname'].' '.$row['user_lastname'];
                if($userid != 0){
                    setcookie("id",$userid);
                    setcookie("name",$uname);
                    session_start();
                    $_SESSION['userid'] = $userid;
                    $_SESSION['username'] = $username;
                    header("Location: Home.php");
                }
            }else{
                $error = "Invalid username or password!";
            }
        }else {
            $error = "Invalid Email!!";
        }
    }else{
        $error = "Please enter username password!";
    }
}

if(isset($_REQUEST['register'])){
    if(!empty($_REQUEST['email'])){
       $s = ("select * from users where email = '".$_REQUEST['email']."' ");
       $result = mysqli_query($conn,$s);
       $count = mysqli_num_rows($result);
       if($count == 0){
           if(!empty($_REQUEST['fname']) && !empty($_REQUEST['lname']) && !empty($_REQUEST['pass']) && !empty($_REQUEST['dob']) && !empty($_REQUEST['address']) && !empty($_REQUEST['gender']) && !empty($_REQUEST['phone']) && !empty($_FILES['profilepic']['name'])){
                $fname = $_REQUEST['fname'];
                $lname = $_REQUEST['lname'];
                $email = $_REQUEST['email'];
                $password = $_REQUEST['pass'];
                $address = $_REQUEST['address'];
                $birthdate = date("Y-m-d", strtotime($_REQUEST['dob']));
                $gender = $_REQUEST['gender'];
                $phone =  $_REQUEST['phone'];
                $profilepic = $_FILES['profilepic']['name'];

                $folder="/xampp/htdocs/Chat/images/";
                move_uploaded_file($_FILES['profilepic']['tmp_name'], "$folder".$_FILES['profilepic']['name']);
                $sql = ("INSERT INTO users (user_firstname,user_lastname,email,password,address,birthdate,gender,phone,profilepicture) values ('".mysqli_real_escape_string($conn,$fname)."','".mysqli_real_escape_string($conn,$lname)."','".mysqli_real_escape_string($conn,$email)."','".mysqli_real_escape_string($conn,$password)."','".mysqli_real_escape_string($conn,$address)."','".$birthdate."','".mysqli_real_escape_string($conn,$gender)."','".$phone."','".mysqli_real_escape_string($conn,$profilepic)."')");
                mysqli_query($conn,$sql);
                //echo "<script type='text/javascript'>alert('You have been successfully Register..');</script>";
                $userid = mysqli_insert_id($conn);
                setcookie("id", $userid);
                setcookie("name",$fname);
                session_start();
                $_SESSION['userid'] = $userid;
                $_SESSION['username'] = $email;
                header("Location: Home.php");
            }else{
                $registererror = "Please fill correct details...";
            }
       }else{
                $registererror = "This Email is already in use..Please try another..";
              }
    }else{
           $registererror = "Please enter email address....";
       }

    }

?>
<html>
    <head>
        <meta charset="windows-1252">
        <title></title>
        <link rel="stylesheet" href="css/login.css">
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <script>
        $(document).ready(function() {
          $("#datepicker").datepicker();

          $('#submit').click(function(){


                var phone = $('#phone').val();
                if(phone.length != 10){
                    alert("please enter correct Phone Number");
                    return false;
                }
	});

        });


     </script>


    </head>
    <body>
            <div class="container">
                <div class="header">
                    <div class="chat-image">
                        <img src="/Chat/images/livechat.png" alt="Mountain View" style="width:200px;height:60px;">
                    </div>
                    <div class="Login-box">
                        <form id="login" name="login" method="post" action="">
                            <input type="text" name="username" placeholder="Username" required=""/>
                            <input type="password" name="password" placeholder="Password" required=""/>
                            <input type="submit" name="login" value="Login" id="login">
                        </form>
                        <span style = "color:red;" ><?php if (isset($error)) {echo $error;}?></span>
                    </div>
                </div>
                        <div class="main">
                           <div class="form">
                               <h1> Sign Up Here </h1>
                               <h3> It's free and always will be. </h3>

                               <form id="registerform" name="register" method="post" action="" enctype="multipart/form-data">

                                    <input id="fname" name="fname" placeholder="Firstname" required="" tabindex="1" type="text">&nbsp;&nbsp;&nbsp;&nbsp;

                                    <input id="lname" name="lname" placeholder="Last name" required="" tabindex="1" type="text"><br>

                                    <input id="email" name="email" placeholder="example@domain.com" required="" type="email"><br>

                                    <input type="password" id="password" name="pass" id="pass" placeholder="Password" required="">&nbsp;&nbsp;&nbsp;&nbsp;

                                    <input type="password" id="repassword" name="repass" id="repass" placeholder="Re-password" required="">&nbsp;&nbsp;&nbsp;&nbsp;

                                    <input id="datepicker" name="dob" placeholder="Click here to pick BirthDate"/><br>

                                    <input type="Radio" Name="gender" Value="Male" required="">Male &nbsp;&nbsp;<input type="Radio" Name="gender" Value="Female" required="">Female<br>

                                    <input id="address" name="address" placeholder="Address" required="" type="text" ><br>

                                    <input id="phone" name="phone" placeholder="phone number" required="" type="text" ><br>

                                    <input type="file" name="profilepic" id="fileToUpload"><br>

                                    <input type="submit" name="register" id="submit" tabindex="5" value="Sign me up!"> <br>
                  </form>
                               <span style = "color:red;" ><?php if (isset($registererror)) {echo $registererror;}?></span>
                </div>
            </div>
            </div>

        <?php
           // echo 'Test';
        ?>
    </body>
</html>
