
$(document).ready(function(){/* off-canvas sidebar toggle */
	$('[data-toggle=offcanvas]').click(function() {
	  	$(this).toggleClass('visible-xs text-center');
	    $(this).find('i').toggleClass('glyphicon-chevron-right glyphicon-chevron-left');
	    $('.row-offcanvas').toggleClass('active');
	    $('#lg-menu').toggleClass('hidden-xs').toggleClass('visible-xs');
	    $('#xs-menu').toggleClass('visible-xs').toggleClass('hidden-xs');
	    $('#btnShow').toggle();
	});
	$("#form_post #wall_post").click(function(){
	    var message = $('#status_message').val(),
        from_id = $('#from_id').val(),
        to_id = $('#to_id').val();
        if(message != "" && from_id != "" && to_id != "") {
	    	jQuery.ajax({
		        url: 'main.php',
		        type: 'POST',
		        data: {status_message: message,from_id:from_id,to_id:to_id},
		        success: function(data){
					console.log(data);
		        	if(data == 'success') {
		        		$('#status_message').val('');
		            	window.location.reload();	
		        	}else if(data == 'nobuddy'){
                                    $('#status_message').val('');
                                    alert("Sorry, You cannot post here");
                    }else if(data == 'failed') {
		        		alert('Failed to update status!!!! Your status message contains some words that have been banned.');
		        	}
		            
		        }
	    	});
	    }    
	});
	$("#modal_post_submit").click(function(){
	    var message = $('#modal_status_message').val(),
        from_id = $('#from_id').val(),
        to_id = $('#to_id').val();
        if(message != "" && from_id != "" && to_id != "") {
	    	jQuery.ajax({
		        url: 'main.php',
		        type: 'POST',
		        data: {status_message: message,from_id:from_id,to_id:to_id},
		        success: function(data){
		            $('#status_message').val('');
		            window.location.reload();
		        }
	    	});
	    }    
	});
	$('.add_to_circle').click(function(event) {
		var x = event.pageX;
        var y = event.pageY;
        var id = $(this).attr('id');
		$('body').append('<div id = "circle_'+id+'" class = "add_to_circle_div" ><span style = "marfont-weight: bold;">Add to circle</span><br><table><tr><td><input id = "friends" type = "radio"><span>Friends</span></td></tr><tr><td><input id = "family" type = "radio"><span>Family</span></td></tr></table></div>');
		$('#circle_'+id).css("left",x+"px").css("top",y+"px");
		$('#friends').is(':checked');
	});
	$(document).on("mouseleave", ".add_to_circle_div", function() {
		$('.add_to_circle_div').remove();
	});
	$(document).on("click","#friends",function() {
		var current_state = $(this).is(':checked');
		var id = $(this).closest('div').attr('id');
		var ids = id.split('_');
		var from = ids[1];
		var to = ids[2];
		var relationship = '';
		if(current_state == true) {
			relationship = 'friends';
		}
		ajaxToMain(from,to,relationship);
	});
	$(document).on("click","#family",function() {
		var current_state = $(this).is(':checked');
		var id = $(this).closest('div').attr('id');
		var relationship = '';
		var ids = id.split('_');
		var from = ids[1];
		var to = ids[2];
		if(current_state == true) {
			relationship = 'family';
		}
		ajaxToMain(from,to,relationship);
	});
	$(document).on("click",".delete-post",function() {
		var post_id = $(this).attr('id');
		jQuery.ajax({
            url: 'main.php',
            type: 'POST',
            data: {post_id: post_id},
            success: function(data){
            	if(data == "success") {
            		window.location.reload();
            	}
            }
		});
	});
	function ajaxToMain(from_id,to_id,current_state) {
		jQuery.ajax({
	        url: 'main.php',
	        type: 'POST',
	        data: {from:from_id,to:to_id,relationship_friends:current_state},
	        success: function(data){
	        	window.location.reload();
	        }
		});
	}
	 jQuery("#logout").click(function(){
        jQuery.ajax({
            url: 'main.php',
            type: 'POST',
            data: {logout: "logout"},
            success: function(data){
            	if(data == 'logout') {
            		window.location = '/Chat/index.php';  
            	}
            }
        });    
    });
    
    jQuery("#sendwalltext").click(function(){       
        var text = $('#walltext').val(); 
        $('#walltext').val(''); 
        var id = $('#walltext').attr("class");
        if(text.length > 0){
            jQuery.ajax({
                url: 'main.php',
                type: 'POST',
                data: {wallmsg: "wallmsg", message : text, id : id},
                success: function(data){
                    console.log(data);
                    //var message =  ('<div class="chat_chatboxmessage" id="chat_message_'+id+'"><span class="chat_chatboxmessagecontent'+id+'">'+message+'</span></div>');      
                    //$('#private_wall_msgs').append("<br/><div id='New_"+id+"'>"+text+"</div>");
                }
            }); 
        }
    });

    $.ajaxSetup ({
         cache: false	//use for i.e browser to clean cache
    });
    $(setInterval(function(){
        $('.refresh').load('view.php'); //this means that the items loaded by display.php will be prompted into the class refresh 
        $('.refresh').attr({ scrollTop: $('.refresh').attr('scrollHeight') }) //if the messages overflowed this line tells the textarea to focus the latest message	

        $('.refreshfriends').load('friends.php'); //this means that the items loaded by display.php will be prompted into the class refresh 
        $('.refreshfriends').attr({ scrollTop: $('.refreshfriends').attr('scrollHeight') }) //if the messages overflowed this line tells the textarea to focus the latest message    
    }, 12000));

});
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
function DelayedSubmission() {
	var input = $('#srch-term').val();
	if(input != '' || input != null) {
		var request = jQuery.ajax({
		type: "POST",
		      data:  {search:"search",text:input},
		      url: 'db.php',
		      dataType : 'html',
		      success: function(msg){
		      	jQuery('#display').html(msg).show();
		      }

		});
	}
}
function block_user(to){
jQuery.ajax({
url: 'db.php',
type: 'POST',
data: {blockuser: "blockuser", to : to},
success: function(data){
    alert("You have been successfully block the user");
     window.location.reload();
    //var message =  ('<div class="chat_chatboxmessage" id="chat_message_'+id+'"><span class="chat_chatboxmessagecontent'+id+'">'+message+'</span></div>');      
    //$('#private_wall_msgs').append("<br/><div id='New_"+id+"'>"+text+"</div>");
    }
}); 
}




