/* 
Created by: Kenrick Beckett

Name: Chat Engine
*/

var instanse = false;
var state;
var mes;
var file;

function Chat () {
    this.update = updateChat;
    this.send = sendChat;
	this.getState = getStateOfChat;
}
var msg=0;
//gets the state of the chat
function getStateOfChat(){
	if(!instanse){
		 instanse = true;
		 $.ajax({
			   type: "POST",
			   url: "chat/process.php",
			   data: {  
			   			'function': 'getState',
						'file': file
						},
			   dataType: "json",
			
			   success: function(data){
				   state = data.state;
				   instanse = false;
				   document.getElementById('chat-wrap').scrollTop = document.getElementById('chat-wrap').scrollHeight;
				   
                        				if($(".admin-content").length < 1){
                        					$(".ban").hide();
                        				}
                        				else{
                        					$(".ban").show()
                        				}
			   },
			});
	}	 
}
//Updates the chat
function updateChat(){
	 if(!instanse){
		 instanse = true;
	     $.ajax({
			   type: "POST",
			   url: "chat/process.php",
			   data: {  
			   			'function': 'update',
						'state': state,
						'file': file
						},
			   dataType: "json",
			   success: function(data){
				   if(data.text){
						for (var i = 0; i < data.text.length; i++) {
                            $('#chat-wrap').append($("<div style='border-bottom: dotted 1px #DCDCDC;display: flex;'>"+ data.text[i] +"</div>"));
                        }								  
				   }
				   if(msg == 1){
				   	msg=0;
				   	document.getElementById('chat-wrap').scrollTop = document.getElementById('chat-wrap').scrollHeight;
				   }


                        				if($(".admin-content").length < 1){
                        					$(".ban").hide();
                        				}
                        				else{
                        					$(".ban").show()
                        				}
				   instanse = false;
				   state = data.state;
			   },
			});
	 }
	 else {
		 setTimeout(updateChat, 1500);
	 }
}

//send the message
function sendChat(message, nickname, ava, id,admin,color)
{       

     $.ajax({
		   type: "POST",
		   url: "chat/process.php",
		   data: {  
		   			'function': 'send',
					'message': message,
					'nickname': nickname,
					'ava': ava,
					'file': file,
					'admin': admin,
					'color': color,
					'id': id
				 },
		   dataType: "json",
		   success: function(data){
		   	    updateChat();
		   		document.getElementById('chat-wrap').scrollTop = document.getElementById('chat-wrap').scrollHeight;
		   		msg = 1;
                        				if($(".admin-content").length < 1){
                        					$(".ban").hide();
                        				}
                        				else{
                        					$(".ban").show()
                        				}
		   },
		});
}
