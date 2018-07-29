var http = require('http');
httpServer = http.createServer(function(req, res){
	console.log('server access');
});
httpServer.listen(8080);
var mysql = require('mysql');
var con = mysql.createConnection({
	host: '127.0.0.1',
	user: 'root',
	password: '',
	database: 'smarter'
});
con.connect(function(err){
	if(err){
		console.log('Error connecting to DB :', err);
	}
});
var io = require('socket.io').listen(httpServer);
io.on('connection', function(socket){	
	console.log('user connected');
	socket.on('login', function(user){
		console.log('loggin attempt');
		con.query('SELECT password FROM users WHERE BINARY name = ?', [user.name], function(err, result, fields){
			if(err){
				throw err;
			// Correct username
			}else if(result.length > 0){
				var str = JSON.stringify(result[0]);
				var str_obj = JSON.parse(str);
				var bcrypt = require('bcrypt');
				str_obj.password = str_obj.password.replace(/^\$2y(.+)$/i, '$2a$1');
				console.log(str_obj.password);
				bcrypt.compare(user.password, str_obj.password, function(err, res) {
					console.log(res);
				});
			}else{
				console.log('nop');
			}
		});
	});
	socket.on('new_msg', function(message){
		date = new Date();
		message.y = date.getFullYear();
		message.mon = date.getUTCMonth();
		message.d = date.getDate();
		message.h = date.getHours();
		message.min = date.getMinutes();
		message.s = date.getSeconds();
		io.emit('new_msg', message);
	})
	socket.on('disconnect', function(){
		console.log('user disconnected');
	});
});
