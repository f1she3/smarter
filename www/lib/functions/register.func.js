module.exports = isUsed = function(username, callback){
	let crypto = require('crypto');
	return getCon((err, con) => {
		if(err){
			let errObj = new Error(err.code);
			errObj.name = 'server';

			return callback(errObj);
		}
		let shasum = crypto.createHash('sha512');
		let usernameHash = shasum.update(username).digest('hex');
		return con.query('SELECT id FROM users WHERE BINARY username  = ?', [usernameHash], (dbError, dbResult) => {
			if(dbError){
				let errObj = new Error(dbError.code);
				errObj.name = 'server';

				return callback(errObj);
			}
			// Valid username
			if(dbResult.length === 0){
				return callback(false);
			}else{
				return callback(new Error('this username is already used'));
			}
		});
		conn.release();
	});
},checkFormats = function(username, callback){
	usernamePattern = new RegExp(/^[a-zA-Z0-9_@[\]éè-]+$/);
	if(!usernamePattern.test(username)){
		return callback(new Error('invalid username'));
	}else{
		return callback(false);
	}
},register = function(username, password, repeatPassword, callback){
	// Password
	let bcrypt = require('bcrypt');
	// SHA-512
	let crypto = require('crypto');
	if(username === undefined){
		return callback(new Error('please choose a username'));
	}
	if(password === undefined){
		return callback(new Error('please choose a password'));
	}
	if(password.length < 8){
		return callback(new Error('your password must be at least 8 chars long'));
	}
	if(repeatPassword === undefined){
		return callback(new Error('please retype your password'));
	}
	if(password !== repeatPassword){
		return callback(new Error('the passwords are different'));
	}
	return getCon((dbErr, dbCon) => {
		if(dbErr){
			let errObj = new Error(dbErr.code);
			errObj.name = 'server';

			return callback(errObj);
		}
		return checkFormats(username, (error) => {
			if(error){
				return callback(error);
			}
			return isUsed(username, (err, res) => {
				if(err){
					return callback(err);
				}
				return bcrypt.hash(password, 10, (passErr, hash) => {
					if(passErr){
						let errObj = new Error(passErr.code);
						errObj.name = 'server';

						return callback(errObj);
					}
					let shasum = crypto.createHash('sha512');
					let usernameHash = shasum.update(username).digest('hex');
					return dbCon.query('INSERT INTO users (username, password, regDate) VALUES (?, \
						?, NOW())', [usernameHash, hash], (queryError, dbResult) => {
						if(queryError){
							let errObj = new Error(queryError.code);
							errObj.name = 'server';

							return callback(errObj);
						}else{
							return callback(false);
						}
					});
				});
			});
		});
		dbCon.release();
	});
};
