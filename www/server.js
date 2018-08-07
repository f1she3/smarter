var express = require('express');
var app = express();
var httpServer = require('http').createServer(app);
var bodyParser = require('body-parser');
var mysql = require('mysql');
var fs = require('fs');
var bcrypt = require('bcrypt');
var io = require('socket.io').listen(httpServer);
httpServer.listen(3000);

// Template engine

app.set('view engine', 'ejs');

// Middlewares

app.use(bodyParser.urlencoded({ extended: false}));
app.use('/assets', express.static('public'));

// Routes

app.get('/:pageName', (request, result) => {
	fs.access('views/pages/' + request.params.pageName + '.ejs', (err) => {
		if(!err){
			result.render('pages/' + request.params.pageName, {page: request.params.pageName});
		}else{
			result.render('pages/error404', {page: 'error'})
		}
	})
});
app.get('/', (request, result) => {
	result.redirect('/login');
});
var con = mysql.createConnection({
	host: '127.0.0.1',
	user: 'root',
	password: '',
	database: ''
});
con.connect(function(err){
	if(err){
		console.log('Error connecting to DB :', err);
	}
});
app.post('/login', (request, result) => {
	if(!request.body.name){
		result.render('pages/login', {message: 'Please enter your username'})	
	}
	if(!request.body.password){
		result.render('pages/login', {message: 'Please enter your password'})	
	}
	//socket.on('login', function(user){
	con.query('SELECT password FROM users WHERE BINARY name = ?', [request.body.name], function(err, db_res, fields){
		if(err){
			throw err;
		// Valid username
		}else if(db_res.length > 0){
			var str = JSON.stringify(db_res[0]);
			var str_obj = JSON.parse(str);
			str_obj.password = str_obj.password.replace(/^\$2y(.+)$/i, '$2a$1');
			bcrypt.compare(request.body.password, str_obj.password, function(err, res) {
				if(res){

				}else{
					result.render('pages/login', {message: 'Wrong username or password'});
				}
			});
		}else{
			result.render('pages/login', {message: 'Wrong username or password'});
		}
	});
});
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
	})
	socket.on('disconnect', function(){
		console.log('user disconnected');
	});
});
