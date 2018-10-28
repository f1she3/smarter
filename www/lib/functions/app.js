module.exports = getCon = function(callback){
	let mysql = require('mysql');
	let pool = mysql.createPool({
		connectionLimit: 100,
		host: '127.0.0.1',
		user: 'root',
		password: '',
		database: 'smarter',
		dateStrings: true,
		debug: false
	});
	pool.getConnection((error, connection) => {
		callback(error, connection);
	});
// require the function associated to a route
}, loadFunc = function(pageName){
	let path = require('path');
	pageName = pageName.substr(1);
	let mainDir = path.dirname(require.main.filename);
	require(path.join(mainDir, 'lib', 'functions', pageName + '.func.js'));
}
