module.exports = isUsed = function(name, email, callback){
	getCon((err, con) => {
		if(err){
			var errObj = new Error(err.code);
			errObj.name = 'server';

			callback(errObj, false);
		}else{
			con.query('SELECT id FROM users WHERE BINARY name  = ?', [name], (dbError, dbResult) => {
				if(dbError){
					var errObj = new Error(dbError.code);
					errObj.name = 'server';

					callback(errObj, false);
				// Valid username
				}else if(dbResult.length === 0){
					con.query('SELECT id From users WHERE email = ?', [email], (dbErr, dbRes) => {
						if(dbErr){
							var errObj = new Error(dbErr.code);
							errObj.name = 'server';

							callback(errObj, false);
						}else if(dbRes.length === 0){
							callback(false, true);
						}else{
							var errObj = new Error('This email address is already used');

							callback(errObj, false);
						}
					})
				}else{
					var errObj = new Error('This username is already used');

					callback(errObj, false);
				}
			})
		}
	})
},checkFormats = function(name, email, callback){
	namePattern = new RegExp(/^[a-zA-Z0-9_@[\]éè-]+$/);
	emailPattern = new RegExp(/^[a-z0-9._-]+@[a-z0-9._-]{2,20}\.[a-z]{2,4}$/);
	if(!namePattern.test(name)){
		return callback(new Error('Username not valid'), false);
	}else if(!emailPattern.test(email)){
		return callback(new Error('Email address not valid'), false);
	}else{
		callback(false, true);
	}
},register = function(name, email, password, repeatPassword, callback){
	if(name === undefined){
		var errObj = new Error('Please enter a username');

		return callback(errObj, false);
	}
	if(name.length < 4){
		var errObj = new Error('Your username must be at least 4 chars long');

		return callback(errObj, false);
	}
	if(email === undefined){
		var errObj = new Error('Please enter an email address');

		return callback(errObj, false);
	}
	if(password === undefined){
		var errObj = new Error('Please enter a password');

		return callback(errObj, false);
	}
	if(password.length < 8){
		var errObj = new Error('Your password must be at least 8 chars long');

		return callback(errObj, false);
	}
	if(repeatPassword === undefined){
		var errObj = new Error('Please repeat your password');

		return callback(errObj, false);
	}
	if(password !== repeatPassword){
		var errObj = new Error('The passwords are different');

		return callback(errObj, false);
	}
	getCon((dbErr, dbCon) => {
		if(dbErr){
			var errObj = new Error(dbErr.code);
			errObj.name = 'server';

			callback(errObj, false);
		}else{
			checkFormats(name, email, (error, result) => {
				if(error){
					callback(error, result);
				}else if(result){
					isUsed(name, email, (err, res) => {
						if(err){
							callback(err, false);
						}else if(res){
							var bcrypt = require('bcrypt');
							bcrypt.hash(password, 10, (passErr, hash) => {
								if(passErr){
									var errObj = new Error(passErr.code);
									errObj.name = 'server';

									callback(errObj, false);
								}else{
									console.log('ok');
									var crypto = require('crypto');
									var shasum = crypto.createHash('sha512');
									shasum.update(email);
									dbCon.query('INSERT INTO users (name, email, password, reg_date) VALUES (?, \
										?, ?, NOW())', [name, shasum.digest('hex'), hash], (queryError, dbResult) => {
										if(queryError){
											var errObj = new Error(queryError.code);
											errObj.name = 'server';

											callback(errObj, false);
										}else{
											callback(false, true);
										}
									})
								}
							})
						}
					})
				}
			})
		}
	})
};
