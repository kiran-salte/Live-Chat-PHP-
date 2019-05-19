$(document).ready(function(){
	var id;
	var c=0;
	var previousTarget = [];
	var mid = [];
	var div_id ;
	var name;
	var names = getCookie('name');
	var chatbox = getCookie('chatbox');;
	// $.cookie("name","sandeep");
	// console.log($.cookie("name"));
	$('body').append('<div id="fixed"></div>');
	$.ajax({
		type:"POST",
		url:"/Chat/php/state.php",
		success : function (response) {
			response = JSON.parse(response);
			/*console.log(response);*/
			for(var i = 0;i < response.length;i++){
				var sender = response[i].split('-');
				var sender_id = sender[1];
				var chat_name = sender[0];

				$('#fixed').append('<div id = "divchat-'+sender_id+'" class = "divchat"></div>');
				$('#divchat-'+sender_id).append('<div id=	"header-'+i+'-'+sender_id+'" class = "header">'+chat_name+'<span id="close-'+chat_name+'-'+sender_id+'" class ="close">x</span><span id="mini-'+c+'-'+sender_id+'" class ="mini">-</span><span id="block-'+c+'-'+sender_id+'" class ="block"><a href="javascript:block_user('+ sender_id +');"><img src="/Chat/images/block.png" width="20px" height="20px"></a></span></div>');
				$('#divchat-'+sender_id).append('<div id = "chat-'+sender_id+'" class = "chat"></div>');
				$('#chat-'+sender_id).append('<div id = "remov-'+sender_id+'"" class = "remov">Delete</div');
				$('#chat-'+sender_id).append(' <div class="chatbox" id = "chatbox-'+sender_id+'""><ul class="ul" id="ul-'+sender_id+'"></ul></div>');
				$('#chat-'+sender_id).append('<input id = "msg-'+sender_id+'-'+chat_name+'" class ="message" name="message" type = "text" placeholder="send a message"/>');
				display(sender_id);
				previousTarget.push(sender_id);
			}
		}
	});
	$(document).on("click",".li",function(){
		if(getCookie('chatbox') == 5) {
			$('#fixed .divchat span.close').last().click();
		}
		id = $(this).attr('id');
		name = $('#'+id+' span').text();
		name = name.replace(/\s/g, '');
		var detect = detectClick();
		$('#msg-'+id).focus();
		if(detect != true){
			chatbox++;
			setCookie('chatbox',chatbox);
			$('#fixed').append('<div id = "divchat-'+id+'" class = "divchat"></div>');
			$('#divchat-'+id).append('<div id="header-'+c+'-'+id+'" class = "header">'+name+'<span id="close-'+name+'-'+id+'" class ="close">x</span><span id="mini-'+c+'-'+id+'" class ="mini">-</span><span id="block-'+c+'-'+id+'" class ="block"><a href="javascript:block_user('+ id +');"><img src="/Chat/images/block.png" width="20px" height="20px"></a></span></div>');
			$('#divchat-'+id).append('<div id = "chat-'+id+'" ></div>');
			$('#chat-'+id).append('<div id = "remov-'+id+'"" class = "remov">delete</div');
			$('#chat-'+id).append(' <div class="chatbox" id = "chatbox-'+id+'""><ul class="ul" id="ul-'+id+'"></ul></div>');
			$('#chat-'+id).append('<input id = "msg-'+id+'" class ="message" name="message" type = "text" placeholder="send a message"/>');
			c++;
			$('#msg-'+id).focus();
			display(id);
			showMore(id);
			div_id = name+"-"+id;
			$.ajax({
				type:"POST",
				url:"/Chat/php/state.php",
				data:{"d_id":div_id},
				success : function (response) {
				}
			});
		}
	});
	$(document).on("keyup",".message",function(e){
		if(e.keyCode == 13){
			var idd = $(this).attr('id');
			var i = $(this).attr('id').split('-');
			// var cal = i[2];
			var uid = i[1];
			var message = $('#'+idd).val();
			var d = new Date();
			var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
			$('.message').val("");
			$.ajax({
				type : "POST",
				url : "/Chat/php/insert.php",
				data : {"message":message,"ctime":time,"rid":uid},
				success : function(response){
                    var res = $.trim(response);
                    //console.log(res);
                    if(res == 'b'){
                        alert("Sorry, you have been blocked from this user. Your messages will not be delivered.");
                    }else{
                    	var message = response.split('-');
                        $('#ul-'+uid).append('<li style="list-style-type:none;float:right;text-align:justify;">'+message[1]+'</li>');
                        $('div.chatbox').scrollTop($('div.chatbox')[0].scrollHeight);
                    }
				},
				error : function(response){
					console.log("error");
				}
			});	
			}
			// setInterval(interaction,3000);
		});	
	
	$(document).on("click",".close",function(){						/////////////////close chatbox
		var m = $(this).attr('id').split('-');
		chatbox--;
		setCookie('chatbox',chatbox);
		// console.log(m[2]);
		$.ajax ({
			type : "POST",
			url : "/Chat/php/state.php",
			data : {"name":m[1],"id":m[2]},
			success : function(response) {
				// console.log(response);
			},
			error : function() {
				console.log(error);
			}
		});
		previousTarget.splice(previousTarget.indexOf(m[2]),1);
		$('#divchat-'+m[2]).remove();
	
	});
	$('#min').click(function(){
		$('#list').slideToggle();
	});
    $(document).on("click",".mini",function(){
    	var m = $(this).attr('id').split('-');
		var box_id = "chat-"+m[2];
        // $("#"+box_id).toggle();
        $('#'+box_id).toggle();
        var new_value = $("#"+box_id).is(":visible") ? 'expanded' : box_id;
        /*console.log("New value: "+new_value);*/
        setCookie('showTop', new_value);
       
    });
	
	$(document).on("click",".remov",function(){
		var remov_id = $(this).attr('id').split('-');
		var s_id = remov_id[1];
		/*console.log(s_id);*/
		$.ajax({
			type:"POST",
			url:"/Chat/php/remove.php",
			data:{"s_id":s_id},
			success : function(response){
				// console.log(response);
				$('#chatbox-'+s_id).empty();
			}
		});
	});
	$(document).on("click","#logout",function(){
		deleteAllCookies();
		window.location.href = "";
	});

	function deleteAllCookies() {
		var c = document.cookie.split("; ");
		for (i in c) 
		document.cookie =/^[^=]+/.exec(c[i])[0]+"=;expires=Thu, 01 Jan 1970 00:00:00 GMT";    
	}
	function detectClick() {
		for(var i = 0; i < previousTarget.length; i++){
			if(id == previousTarget[i]) {
	        	return true;
	    	}
		}
	    	previousTarget.push(id);
			// console.log(previousTarget);
	    	return false;
	}
	function display(id) {
	$.ajax({
			type:"POST",
			url:"/Chat/php/fetch.php",
			data:{"rid":id},
			success : function (response) {
				response = JSON.parse(response);
				/*console.log(response);*/
				for(var i = response.length-1; i >= 0 ;i--){
					var string = response[i].split('-');
					if(string[1] == 's') {
						$('#ul-'+id).append('<li style="list-style-type:none;float:right;text-align:justify;padding:3px;">'+string[0]+'</li>');
						$('div.chatbox').scrollTop($('div.chatbox')[0].scrollHeight);
					}else if(string[1] == 'r') {
						$('#ul-'+id).append('<li style="list-style-type:none;float:left;text-align:justify;padding:3px;">'+string[0]+'</li>');
						$('div.chatbox').scrollTop($('div.chatbox')[0].scrollHeight);
					}
				}
			},
			error : function (response) {
				console.log("error_display");
			}
		});
	}

	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+d.toUTCString();
	    document.cookie = cname + "=" + cvalue + "; " + expires;
	}
	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0; i<ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1);
	        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
	    }
	    return "";
	}
	function showMore(id) {
		$('#chatbox-'+id).on('scroll',function(event){
			var chat = $(this).attr('id');
			var chat_id = chat.split('-');
			var i = chat_id[1];
			var top = $('#chatbox-'+id).scrollTop();
			if(top == 0){
				$('#chatbox-'+i).prepend('<div id = "show'+id+'" class = "show">Show more</div>');
				// console.log(i);
			}
			else if(top != 0)	{
				$('#show'+id).remove();
			}
			$('.show').click(function(){
				//console.log("inside");
				$.ajax({
					type : "POST",
					url : "/Chat/php/showmessage.php",
					data : {"id" : id},
					success : function(response){
						response = JSON.parse(response);
						if(response.length != 0){
							mid[id] = response[response.length-1]; 
							for(var j = response.length-1; j >= 0 ;j--){
									$('#ul-'+id).prepend('<li style="list-style-type:none;">'+response[j]+'</li>');
								}
						}
						else {
								$('#show'+id).remove();
							}
						//console.log(response);
						//console.log(id);
					},
					error : function(){
						console.log("error");
					}
				});
			});
		});
	}
	setTimeout(function() {
		setInterval(function() {
			$.ajax({
				type : "POST",
				url : "/Chat/php/send_message.php",
				success : function(response){
					if(response != '') {
						response = JSON.parse(response);
						//console.log("test");
						//console.log(response);
						$.each(response,function(index,value) {
							var message_array = value.split('-');
							if($('#ul-'+message_array[1]).length>0) {
								$('#ul-'+message_array[1]).append('<li style="list-style-type:none;float:left;">'+message_array[0]+'</li>');
								$('div.chatbox').scrollTop($('div.chatbox')[0].scrollHeight);	
							}else {	
								var id = message_array[1];
								var name = message_array[2];
								$('#fixed').append('<div id = "divchat-'+id+'" class = "divchat"></div>');
								$('#divchat-'+id).append('<div id="header-'+c+'-'+id+'" class = "header">'+name+'<span id="close-'+name+'-'+id+'" class ="close">x</span><span id="mini-'+c+'-'+id+'" class ="mini">-</span><span id="block-'+c+'-'+id+'" class ="block"><a href="javascript:block_user('+ id +');"><img src="/Chat/images/block.png" width="20px" height="20px"></a></span></div>');
								$('#divchat-'+id).append('<div id = "chat-'+id+'" ></div>');
								$('#chat-'+id).append('<div id = "remov-'+id+'"" class = "remov">delete</div');
								$('#chat-'+id).append(' <div class="chatbox" id = "chatbox-'+id+'""><ul class="ul" id="ul-'+id+'"></ul></div>');
								$('#chat-'+id).append('<input id = "msg-'+id+'" class ="message" name="message" type = "text" placeholder="send a message"/>');
								display(message_array[1]);
								$('#ul-'+message_array[1]).append('<li style="list-style-type:none;float:left;">'+message_array[0]+'</li>');
								$('div.chatbox').scrollTop($('div.chatbox')[0].scrollHeight);	
							}

						});
					}
				},
				error : function(){
					console.log("error");
				}
			});
		},10000);
	},15000);
});	


