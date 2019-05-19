<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
         <link rel="stylesheet" href="css/tabcontent.css">
        <link rel="stylesheet" href="css/jquery.dataTables.css">
        <script src="js/jquery.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
        <script src="js/admin.js"></script>
        
        <script>
            
        function newPopup(url) {
            popupWindow = window.open(url,'popUpWindow','height=600,width=900,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')
        }
        
        
        function unblock(fromid,toid){
                jQuery.ajax({
                url: 'dbtransaction.php',
                type: 'POST',
                data: {unblock: "unblock",fromid: fromid,toid: toid},
                success: function(data){
                    alert("Successfully Unblock user");
                    location.reload();       
                }
            });               
        }
        </script>
        <style>
            .analytics-table {
                border-collapse: collapse;
            }
            .analytics-table,.analytics-table th,.analytics-table td {
               border: 1px solid black;
               padding: 10px;
            } 
        </style>
    </head>
   <body style="background:#F6F9FC; font-family:Arial;">
     
    <?php
    
    include_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."setting.php";
    
        global $bannedWords1;
	global $bannedWords2;
	global $bannedWords3;
        global $familyBanwords;
        global $ts;
        
        $words1 = array();
        $words2 = array();
        $words3 = array();
        $words4 = array();
        

	$bannedw1 = '';
        $bannedw2 = '';
        $bannedw3 = '';
        $familybanned = '';
        
        $sql = "SELECT * FROM bannedword1";
        $query = mysqli_query($conn,$sql);

        while ($row = mysqli_fetch_assoc($query)) {
            array_push($words1,$row['bword1']);
        }
	foreach ($words1 as $b) {
		$bannedw1 .= "".$b.',';
	}
        
 /***************************************************/       
        $sql = "SELECT * FROM bannedword2";
        $query = mysqli_query($conn,$sql);

        while ($row = mysqli_fetch_assoc($query)) {
            array_push($words2,$row['bword2']);
        }
	foreach ($words2 as $b) {
		$bannedw2 .= "".$b.',';
	}
        
        /*****************************************/
        
         $sql = "SELECT * FROM bannedword3";
        $query = mysqli_query($conn,$sql);

        while ($row = mysqli_fetch_assoc($query)) {
            array_push($words3,$row['bword3']);
        }
	foreach ($words3 as $b) {
	

    	$bannedw3 .= "".$b.',';
	}
        /****************************************/
        
        $sql = "SELECT * FROM familybanned";
        $query = mysqli_query($conn,$sql);

        while ($row = mysqli_fetch_assoc($query)) {
            array_push($words4,$row['famword3']);
        }
	foreach ($words4 as $b) {
		$familybanned .= "".$b.',';
	}
        
       
    ?>   
       
       
    <div style="width: 100%; margin: 0 auto;">
        <ul class="tabs" data-persist="true">
            <li id="addwords"><a href="#view1">Add Ban words</a></li>
            <li id="users" ><a href="#view2">Users</a></li>     
            <li id="unblock"><a href="#view3">Unblock Users</a></li>
            <li id="bangraph"><a href="#view4">Ban word Graph</a></li>
            
            <li id="logout"><a href="#view5">Logout</a></li>
        </ul>
        <div id="tabcontents" style="width: 100%; margin: 20px auto;" >
            <div id="view1">
                <form action="dbtransaction.php" method="post">
                    <h2>Banned words</h2>
                     <h3>You can add words to the abusive list. </h3>
                     <div>
                         <div id="centernav">
                             
                                <div class="title">Banned Type1 Words: <input type="text" class="inputbox" name="bannedwords1" value="<?php echo $bannedw1; ?>"></div>
                                 
                                 <div class="title">Banned Type2 Words: <input type="text" class="inputbox" name="bannedwords2"  value="<?php echo $bannedw2; ?>"></div>
                                     
                                 <div class="title">Banned Type3 Words: <input type="text" class="inputbox" name="bannedwords3"  value="<?php echo $bannedw3; ?>" ></div>
                                 
                                 <div class="title">Banned Words Family Members: <input type="text" class="inputbox" name="familybanned"  value="<?php echo $familybanned; ?>" ></div>
                         </div>
                         
                         <input type="submit" value="Add " class="button" name="add">
                    </div>  
                </form>	
        </div>
    </div>
        
</body>
</html>
