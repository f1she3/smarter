module.exports = getCon = function(callback){
	var mysql = require('mysql');
	var pool = mysql.createPool({
		connectionLimit: 100,
		host: '127.0.0.1',
		user: 'root',
		password: '',
		database: '',
		debug: false
	});
	pool.getConnection((error, connection) => {
		callback(error, connection);
	});
}, loadFunc = function(pageName){
	var path = require('path');
	pageName = pageName.substr(1);
	var mainDir = path.dirname(require.main.filename);
	require(path.join(mainDir, 'lib', 'functions', pageName + '.func.js'));
}
