var http = require('http');
httpServer = http.createServer(function(req, res){
	console.log('access');
});
httpServer.listen(8080);
var io = require('socket.io').listen(httpServer);
io.on('connection', function(socket){	
	console.log('user connected');
	socket.on('new_msg', function(message){
		date = new Date();
		message.y = date.getFullYear();
		message.mon = date.getUTCMonth();
		message.d = date.getDate();
		message.h = date.getHours();
		message.min = date.getMinutes();
		message.s = date.getSeconds();
		io.emit('new_msg', message);
		console.log(message);
	})
	socket.on('disconnect', function(){
		console.log('user disconnected');
	});
});
