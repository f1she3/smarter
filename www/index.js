'use strict';
let express = require('express');
let app = express();
let httpServer = require('http').createServer(app).listen(3000);
let session = require('express-session');
let RedisStore = require('connect-redis')(session);
let redis = require('redis').createClient();
let bodyParser = require('body-parser');
let fs = require('fs');
let path = require('path');
let io = require('socket.io').listen(httpServer);
let sharedsession = require('express-socket.io-session');
let sessionHandle = session({
	store: new RedisStore({
		host: '127.0.0.1',
		port: 6379,
		client: redis
	}),
	secret: 'smarter-key',
	resave: true,
	saveUninitialized: true,
	cookie: {secure: false}
});

// Main app functions
require(path.join(__dirname, 'lib', 'functions', 'app'));

// Template engine

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');

// Middlewares

app.use(bodyParser.urlencoded({ extended: false}));
app.use('/assets', express.static(path.join(__dirname, 'public')));
app.use(sessionHandle);
app.use(require(path.join(__dirname, 'lib/middlewares/flash')));
// Load routes
fs.readdir('lib/routes', (error, files) => {
	if(error){
		throw error;
	}else{
		files.forEach((file) => {
			let baseName = path.basename(file, '.js');
			app.use('/' + baseName, require(path.join(__dirname, 'lib', 'routes', file)));
		});
	}
});
// Global hook routes
app.use('/*', require(path.join(__dirname, 'lib', 'routes', 'globalHook')));
// Use session inside socket.io
io.use(sharedsession(sessionHandle));

io.on('connection', socket => {
	require(path.join(__dirname, 'lib', 'functions', 'chat.func'));
	let username = socket.handshake.session.username;
	getMsg((err, result) => {
		if(err){
			console.error(err);
		}else{
			// Reverse fetch array 
			// we fetch 30 results, so from result[29] to result[0]
			for(let k = 29; k > -1; k--){
				if(result[k] !== undefined){
					// Different timestamps
					let dateObj = new Date(result[k].date);
					let date = Array();
					date[0] = dateObj.getFullYear();
					date[1] = dateObj.getMonth();
					date[2] = dateObj.getDay();
					date[3] = dateObj.getHours();
					date[4] = dateObj.getMinutes();
					date[5] = dateObj.getSeconds();
					if(date[1] < 10){
						date[1] = '0' + date[1];
					}
					if(date[2] < 10){
						date[2] = '0' + date[2];
					}
					socket.emit('getNewMsg', result[k].sender, result[k].message, date);
				}
			}
		}
		socket.on('postNewMsg', message => {
			let dateObj = new Date();
			let date = Array();
			date[0] = dateObj.getFullYear();
			date[1] = dateObj.getMonth();
			date[2] = dateObj.getDay();
			date[3] = dateObj.getHours();
			date[4] = dateObj.getMinutes();
			date[5] = dateObj.getSeconds();
			if(date[1] < 10){
				date[1] = '0' + date[1];
			}
			if(date[2] < 10){
				date[2] = '0' + date[2];
			}
			insertMsg(username, message.message, error => {
				if(error){
					console.error(error);

					return;
				}
				io.sockets.emit('getNewMsg', socket.handshake.session.username, message.message, date);
			});
		});
		socket.on('isTyping', (status, writer) => {
			socket.broadcast.emit('isTyping', status, username);
		});
	});
});
