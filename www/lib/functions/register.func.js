module.exports = isUsed = function(name, email, callback){
	return getCon((err, con) => {
		if(err){
			var errObj = new Error(err.code);
			errObj.name = 'server';

			return callback(errObj, false);
		}
		return con.query('SELECT id FROM users WHERE BINARY name  = ?', [name], (dbError, dbResult) => {
			if(dbError){
				var errObj = new Error(dbError.code);
				errObj.name = 'server';

				return callback(errObj, false);
			// Valid username
			}
			if(dbResult.length === 0){
				var crypto = require('crypto');
				var shasum = crypto.createHash('sha512');
				emailHash = shasum.update(email).digest('hex');
				return con.query('SELECT id From users WHERE email = ?', [emailHash], (dbErr, dbRes) => {
					if(dbErr){
						var errObj = new Error(dbErr.code);
						errObj.name = 'server';

						return callback(errObj, false);
					}
					if(dbRes.length === 0){
						return callback(false, true);
					}else{
						return callback(new Error('This email address is already used'), false);
					}
				});
			}else{
				return callback(new Error('This username is already used'), false);
			}
		});
	});
},checkFormats = function(name, email, callback){
	namePattern = new RegExp(/^[a-zA-Z0-9_@[\]éè-]+$/);
	emailPattern = new RegExp(/^[a-z0-9._-]+@[a-z0-9._-]{2,20}\.[a-z]{2,4}$/);
	if(!namePattern.test(name)){
		return callback(new Error('Username not valid'), false);
	}else if(!emailPattern.test(email)){
		return callback(new Error('Email address not valid'), false);
	}else{
		return callback(false, true);
	}
},register = function(name, email, password, repeatPassword, callback){
	if(name === undefined){
		return callback(new Error('Please enter a username'), false);
	}
	if(name.length < 4){
		return callback(new Error('Your username must be at least 4 chars long'), false);
	}
	if(email === undefined){
		return callback(new Error('Please enter an email address'), false);
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
			var errObj = new Error(dbErr.code);
			errObj.name = 'server';

			return callback(errObj, false);
		}
		return checkFormats(name, email, (error, result) => {
			if(error){
				return callback(error, result);
			}
			return isUsed(name, email, (err, res) => {
				if(err){
					return callback(err, false);
				}
				var bcrypt = require('bcrypt');
				return bcrypt.hash(password, 10, (passErr, hash) => {
					if(passErr){
						var errObj = new Error(passErr.code);
						errObj.name = 'server';

						return callback(errObj, false);
					}
					var crypto = require('crypto');
					var shasum = crypto.createHash('sha512');
					emailHash = shasum.update(email).digest('hex');
					return dbCon.query('INSERT INTO users (name, email, password, reg_date) VALUES (?, \
						?, ?, NOW())', [name, emailHash, hash], (queryError, dbResult) => {
						if(queryError){
							var errObj = new Error(queryError.code);
							errObj.name = 'server';

							return callback(errObj, false);
						}else{
							return callback(false, true);
						}
					})
				})
			})
		})
		dbCon.release();
	})
};
