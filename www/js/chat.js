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
	socket.on('getNewMsg', (sender, message, date) => {
		$('#publicMessages').prepend('<span>' + message + '</span><hr>');
		$('#publicMessages').prepend('<span class="name"><strong>' + sender + '</strong> : </span>');
		$('#publicMessages').prepend('<div class="pull-right">' + date[3] + 'h ' + date[4] + ',  ' + date[2] + '/' + date[1] + ' ' + date[0] + '</span>');
	});
});