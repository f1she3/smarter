module.exports = authCheck = function(username, password, callback){
	let bcrypt = require('bcrypt');
	let crypto = require('crypto');
	return getCon((error, connection) => {
		if(error){
			let errObj = new Error(error.code);
			errObj.name = 'server';

			return callback(errObj);
		}
		let shasum = crypto.createHash('sha512');
		let usernameHash = shasum.update(username).digest('hex');
		connection.query('SELECT password FROM users WHERE BINARY username = ?', [usernameHash], function(err, dbRes, fields){
			if(err){
				let errObj = new Error(err.code);
				errObj.name = 'server';

				callback(errObj);
			// Username validation
			}else if(dbRes.length > 0){
				let passwordStr = JSON.stringify(dbRes[0]);
				let passwordStrObj = JSON.parse(passwordStr);
				//passwordStrObj.password = passwordStrObj.password.replace(/^\$2y(.+)$/i, '$2a$1');
				return bcrypt.compare(password, passwordStrObj.password, (err, res) => {
					if(res){
						return callback(false, true);
					}else{
						return callback(new Error('Wrong username or password'));
					}
				});
			}else{
				return callback(new Error('Wrong username or password'));
			}
		});
		connection.release();
	});
}
