$(document).ready(function(){
	var socket = io.connect('http://127.0.0.1:8080');
	// Send a message
	$('form').submit(function(event){
		event.preventDefault();
		var message = $("#message").val();
		if($("#message").val() === ''){
			$("#message").focus();
			return false;
		}
		socket.emit('new_msg', {
			name	: $("#name").val(),
			message : $("#message").val()
		})
		$('#message').val('');
		$('#message').focus();
	})
	// Get a message
	socket.on('new_msg', function(message){
		if(message.mon < 10){
			message.mon = "0" + message.mon;
		}
		$("#public_messages").prepend("<span>" + message.message + "</span><hr>");
		$("#public_messages").prepend("<div class=\"pull-right\">" + message.h + "h " + message.min + ":" + message.s + ", le " + message.d + "/" + message.mon + " " + message.y + "</span>");
		$("#public_messages").prepend("<span class=\"name\"><strong>" + message.name + "</strong> : </span>");
		
		//alert(message);
	})
})
