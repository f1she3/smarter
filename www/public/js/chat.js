$(document).ready(function(){
	let socket = io();
	// Send a message
	$('form').submit(function(event){
		event.preventDefault();
		let message = $('#message').val();
		if($('#message').val() === ''){
			$('#message').focus();
			return false;
		}
		socket.emit('postNewMsg', {
			message : $('#message').val()
		})
		$('#message').val('');
		$('#message').focus();
	})
	// Get a message
	socket.on('getNewMsg', function(message){
		if(message.mon < 10){
			message.mon = '0' + message.mon;
		}
		$('#public_messages').prepend("<span>" + message.message + "</span><hr>");
		$('#public_messages').prepend("<div class=\'pull-right\'>" + message.h + "h " + message.min + ":" + message.s + ", le " + message.d + "/" + message.mon + " " + message.y + "</span>");
		$('#public_messages').prepend("<span class=\'name\'><strong>" + message.name + "</strong> : </span>");
	})
})
