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

// Main app functions
require(path.join(__dirname, 'lib', 'functions', 'app'));

// Template engine

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');

// Middlewares

app.use(bodyParser.urlencoded({ extended: false}));
app.use('/assets', express.static(path.join(__dirname, 'public')));
app.use(session({
	store: new RedisStore({
		host: '127.0.0.1',
		port: 6379,
		client: redis
	}),
	// Change this setting in Production env
	secret: 'smarter-key',
	resave: false,
	saveUninitialized: false,
	// Change this if you use HTTPS
	cookie: {secure: false}
}));
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

// Global app routing

app.get('/', (request, response) => {
	response.redirect('/login');
});
app.get('/*', (request, response, next) => {
	// Remove openning slash
	let pageName = request.originalUrl.substr(1);
	// 404 check
	fs.access('views/pages/' + pageName + '.ejs', (err) => {
		if(err){
			response.render('pages/error', {
				errorType: '404',
				errorMsg: "This page doesn't exist",
				home: true
			});
		}else{
			// Set the page's title
			response.locals.title = pageName;
			next();
		}
	})
})
