module.exports = isUsed = function(username, callback){
	let crypto = require('crypto');
	return getCon((err, con) => {
		if(err){
			let errObj = new Error(err.code);
			errObj.name = 'server';

			return callback(errObj, false);
		}
		let shasum = crypto.createHash('sha512');
		let usernameHash = shasum.update(username).digest('hex');
		return con.query('SELECT id FROM users WHERE BINARY username  = ?', [usernameHash], (dbError, dbResult) => {
			if(dbError){
				let errObj = new Error(dbError.code);
				errObj.name = 'server';

				return callback(errObj, false);
			}
			// Valid username
			if(dbResult.length === 0){
				return callback(false, true);
			}else{
				return callback(new Error('This username is already used'), false);
			}
		});
	});
},checkFormats = function(username, callback){
	usernamePattern = new RegExp(/^[a-zA-Z0-9_@[\]éè-]+$/);
	if(!usernamePattern.test(username)){
		return callback(new Error('Username not valid'), false);
	}else{
		return callback(false, true);
	}
},register = function(username, password, repeatPassword, callback){
	// Password
	let bcrypt = require('bcrypt');
	// SHA-512
	let crypto = require('crypto');
	if(username === undefined){
		return callback(new Error('Please enter a username'), false);
	}
	if(username.length < 4){
		return callback(new Error('Your username must be at least 4 chars long'), false);
	}
	if(password === undefined){
		return callback(new Error('Please enter a password'), false);
	}
	if(password.length < 8){
		return callback(new Error('Your password must be at least 8 chars long'), false);
	}
	if(repeatPassword === undefined){
		return callback(new Error('Please repeat your password'), false);
	}
	if(password !== repeatPassword){
		return callback(new Error('The passwords are different'), false);
	}
	return getCon((dbErr, dbCon) => {
		if(dbErr){
			let errObj = new Error(dbErr.code);
			errObj.name = 'server';

			return callback(errObj, false);
		}
		return checkFormats(username, (error, result) => {
			if(error){
				return callback(error, result);
			}
			return isUsed(username, (err, res) => {
				if(err){
					return callback(err, false);
				}
				return bcrypt.hash(password, 10, (passErr, hash) => {
					if(passErr){
						let errObj = new Error(passErr.code);
						errObj.name = 'server';

						return callback(errObj, false);
					}
					let shasum = crypto.createHash('sha512');
					let usernameHash = shasum.update(username).digest('hex');
					return dbCon.query('INSERT INTO users (username, password, regDate) VALUES (?, \
						?, NOW())', [usernameHash, hash], (queryError, dbResult) => {
						if(queryError){
							let errObj = new Error(queryError.code);
							errObj.name = 'server';

							return callback(errObj, false);
						}else{
							return callback(false, true);
						}
					});
				});
			});
		});
		dbCon.release();
	});
};
