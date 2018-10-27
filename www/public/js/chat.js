$(document).ready(function(){
	let socket = io();
	var isTyping = 0;
	// Send a message
	$('#message').keyup(function(event){
		if($('#message').val() === ''){
			isTyping = 0;
			socket.emit('isTyping', false);
		}else{
			if(isTyping === 0){
				isTyping = 1;
				socket.emit('isTyping', true);
			}
		}
	});
	$('#message').keypress(function(event){
		if(event.keyCode === 13){
			if($('#message').val() !== ''){
				$('form').submit();
			}

			return false;
		}else{
			return true;
		}
	});
	$('form').submit(function(event){
		event.preventDefault();
		let message = $('#message').val();
		if($('#message').val() === ''){
			$('#message').focus();

			return false;
		}
		socket.emit('postNewMsg', {
			message : $('#message').val()
		});
		$('#message').val('');
		$('#message').focus();
	});
	socket.on('isTyping', (status, writer) => {
		if(status){
			$('#publicMessages').prepend('<span id="typing">' + writer + ' is typing ...<br><br></span>');
		}else{
			$('#typing').remove();
		}
	});
	// Get a message
	socket.on('getNewMsg', (sender, message) => {
		if(message.mon < 10){
			message.mon = '0' + message.mon;
		}
		$('#publicMessages').prepend('<span>' + message.message + '</span><hr>');
		$('#publicMessages').prepend('<span class="name"><strong>' + sender + '</strong> : </span>');
		$('#publicMessages').prepend('<div class="pull-right">' + message.h + 'h ' + message.min + ', le ' + message.d + '/' + message.mon + ' ' + message.y + '</span>');
	});
});
