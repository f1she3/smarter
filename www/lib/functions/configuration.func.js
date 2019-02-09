module.exports = updatePassword = function(username, password, callback){
	let bcrypt = require('bcrypt');
	let crypto = require('crypto');
	return getCon((error, connection) => {
		if(error){
			let errObj = new Error(error.code);
			errObj.name = 'server';

			return callback(errObj);
		}
		return bcrypt.hash(password, 10, (passErr, hash) => {
			if(passErr){
				let errObj = new Error(passErr.code);
				errObj.name = 'server';

				return callback(errObj);
			}
			let shasum = crypto.createHash('sha512');
			let usernameHash = shasum.update(username).digest('hex');
			return connection.query('UPDATE users SET password = ? WHERE BINARY username = ?', [hash, usernameHash], function(dbErr, dbRes){
				if(dbErr){
					let errObj = new Error(dbErr.code);
					errObj.name = 'server';

					return callback(errObj);
				// Username validation
				}else{
					return callback(false);
				}
				connection.release();
			});
		});
	});

}
