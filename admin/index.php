<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>

        <link rel="stylesheet" href="css/admin.css">

        <!--<script type="text/javascript">
            $(document).ready(function() {
                $("#login-button").click(function(){
                   $("#Username").val("");
                   $("#Password").val("");
                });
            });
        </script>-->
    </head>
    <body>
        <?php
            //echo 'Test Admin';
            //include '../config.php';
            include '../setting.php';
            print_r($_POST);
            if(isset($_POST['adminUsername'])&& isset($_POST['adminPassword'])){
                      $uname = $_POST['adminUsername'];
                      $upass = $_POST['adminPassword'];
                  }
            if(isset($_POST['login-button'])){
                if(ADMINUSER == $uname && ADMINPASS == $upass){
                    header("Location: dashboardAdmin.php");
                    exit;
                }else{
                  $message = "wrong Username or Password";
                  echo "<script type='text/javascript'>alert('$message');</script>";
                    //header("Location: index.php");
                }
            }
            // if(isset($_REQUEST['error'])){
            //     $message = "wrong Username or Password";
            //     echo "<script type='text/javascript'>alert('$message');</script>";
            //
            // }

            //header("Location: index.php");
            //echo "Username" . USERNAME . "Password" . PASSWORD;
        ?>

        <div class="wrapper">
            <div class="container">
                    <h1>Welcome</h1>

                    <div class="form">
                      <form method="post">
                        <input type="text" placeholder="Username" name="adminUsername" >
                        <input type="password" placeholder="Password" name="adminPassword">
                        <button type="submit" name="login-button" id="login-button">Login</button>
                      </form>
                    </div>
            </div>
        </div>

    </body>
</html>
