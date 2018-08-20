'use strict';
var express = require('express');
var app = express();
var httpServer = require('http').createServer(app);
var bodyParser = require('body-parser');
var fs = require('fs');
var path = require('path');
var io = require('socket.io').listen(httpServer);
httpServer.listen(3000);

// Template engine

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');

// Middlewares

app.use(bodyParser.urlencoded({ extended: false}));
app.use('/assets', express.static(path.join(__dirname, 'public')));

// Load routes and associated functions

require(path.join(__dirname, 'lib/routes/loader'))(app);
