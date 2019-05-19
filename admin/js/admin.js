/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function() {

    jQuery("#analytics").click(function(){
        jQuery.ajax({
            url: 'analytics.php',
            type: 'POST',
            data: {analytics: "analytics"},
            success: (function(data){
                console.log(data);
                var response = JSON.parse(data);
                jQuery("#tabcontents").empty();
                jQuery("#tabcontents").append('<table class = "analytics-table"><tr><th>Level1</th><th>Non-Neutral</th><th>Neutral</th></tr><tr><td>Precision</td><td>'+response["badword"][0]+'</td><td>'+response["goodwords"][0]+'</td></tr><tr><td>Recall</td><td>'+response["badword"][1]+'</td><td>'+response["goodwords"][1]+'</td></tr><tr><td>F(measure)</td><td>'+response["badword"][4]+'</td><td>'+response["goodwords"][4]+'</td></tr></table><br>');
                jQuery("#tabcontents").append('<table class = "analytics-table"><tr><th>Level2</th><th>Non-Neutral</th><th>Neutral</th></tr><tr><td>Precision</td><td>'+response["badword"][0]+'</td><td>'+response["goodwords"][0]+'</td></tr><tr><td>Recall</td><td>'+response["badword"][2]+'</td><td>'+response["goodwords"][2]+'</td></tr><tr><td>F(measure)</td><td>'+response["badword"][5]+'</td><td>'+response["goodwords"][5]+'</td></tr></table><br>');
                jQuery("#tabcontents").append('<table class = "analytics-table"><tr><th>Level3</th><th>Non-Neutral</th><th>Neutral</th></tr><tr><td>Precision</td><td>'+response["badword"][0]+'</td><td>'+response["goodwords"][0]+'</td></tr><tr><td>Recall</td><td>'+response["badword"][3]+'</td><td>'+response["goodwords"][3]+'</td></tr><tr><td>F(measure)</td><td>'+response["badword"][5]+'</td><td>'+response["goodwords"][5]+'</td></tr></table>');
            })
        });
    });
    
   jQuery('#users').click(function(){
		jQuery.ajax({
			url: 'dbtransaction.php',
			type: 'POST',
			data: {userlist_admin: "userlist_admin"},
			success: (function(data){
                                //console.log("data" + data);
				jQuery("#tabcontents").empty();
				jQuery("#tabcontents").append('<div id="main"><table id="dataTableExample" class="display"><thead><tr><th>User Firstname</th><th>User Lastname</th><th>Email</th><th>Address</th><th>Gender</th><th>BirthDate</th><th>Phone</th></tr></thead><tbody>'+data+'</tbody></table></div>');		
                                oTable = $('#dataTableExample').dataTable({
					"bJQueryUI": false,
					"bAutoWidth": false,
					"sPaginationType": "full_numbers",
					"sDom": '<"H"fl>t<"F"ip>',
				});
                            })
                        });	
                    });
                    
              
    jQuery('#addwords').click(function(){
            window.location.href = "http://localhost/Chat/admin/dashboardAdmin.php";
       }); 
       
     jQuery('#logout').click(function(){
            window.location.href = "http://localhost/Chat/admin/index.php";
       });        
       
    jQuery("#bangraph").click(function(){
           jQuery.ajax({
                url: 'dbtransaction.php',
                type: 'POST',
                data: {graph_userlist: "graph_userlist"},
                success: (function(data){
                    jQuery("#tabcontents").empty();
                    jQuery("#tabcontents").append('<div id="main"><table id="dataTableExample" class="display"><thead><tr><th>UserName</th><th>Type 1</th><th>Type 2</th><th>Type 3</th><th>View Graph</th></tr></thead><tbody>'+data+'</tbody></table></div>');		
                oTable = $('#dataTableExample').dataTable({
					"bJQueryUI": false,
					"bAutoWidth": false,
					"sPaginationType": "full_numbers",
					"sDom": '<"H"fl>t<"F"ip>',
				});
            })
        });
    });
            
        jQuery("#unblock").click(function(){
           jQuery.ajax({
                url: 'dbtransaction.php',
                type: 'POST',
                data: {ban_userlist: "ban_userlist"},
                success: (function(data){
                    jQuery("#tabcontents").empty();
                    jQuery("#tabcontents").append('<div id="main"><table id="dataTableExample" class="display"><thead><tr><th>Serial No</th><th>Source id</th><th>Destination id</th><th>Block Status</th></tr></thead><tbody>'+data+'</tbody></table></div>');		
                    oTable = $('#dataTableExample').dataTable({
                    "bJQueryUI": false,
                    "bAutoWidth": false,
                    "sPaginationType": "full_numbers",
                    "sDom": '<"H"fl>t<"F"ip>',
                });
            })
        });
    });
});

    
    

