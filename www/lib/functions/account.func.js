module.exports = getRegDate = function(username, callback){
	let crypto = require('crypto');
	return getCon((error, connection) => {
		if(error){
			let errObj = new Error(error.code);
			errObj.name = 'server';

			return callback(errObj);
		}
		let shasum = crypto.createHash('sha512');
		let usernameHash = shasum.update(username).digest('hex');
		connection.query('SELECT regDate FROM users WHERE BINARY username = ?', [usernameHash], function(err, dbRes, fields){
			connection.release();
			if(err){
				let errObj = new Error(err.code);
				errObj.name = 'server';

				callback(errObj);
			// Username validation
			}else{
				let regDate = dbRes[0];
				return callback(false, regDate);
			}
		});
	});
}
