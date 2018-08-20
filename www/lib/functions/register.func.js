module.exports = isUsed = function(name, email, callback){
	getCon((err, con) => {
		if(err){
			var errObj = new Error(err.code);
			errObj.name = 'server';

			callback(errObj, false);
		}else{
			con.query('SELECT id FROM users WHERE BINARY name  = ?', [name], (error, dbResult) => {
				if(error){
					var errObj = new Error(error.code);
					errObj.name = 'server';

					callback(errObj, false);
				// Valid username
				}else if(dbResult.length === 0){
					con.query('SELECT id From users WHERE email = ?', [email], (err, dbRes) => {
						if(err){
							var errObj = new Error(error.code);
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
},checkFormat = function(field, inputStr, callback){
	if(field === 'username'){
		pattern = new RegExp('#^[a-zA-Z0-9_@[\]éè-]+$#');

		callback(pattern.test(inputStr), pattern.test(inputStr));
	}else if(field === 'email'){
		pattern = new RegExp('#^[a-z0-9._-]+@[a-z0-9._-]{2,20}\.[a-z]{2,4}$#');
		
		callback(pattern.test(inputStr), pattern.test(inputStr));
	}
},register = function(name, email, password, repeatPassword, callback){
	getCon((dbErr, dbCon) => {
		if(dbErr){
			var errObj = new Error(dbErr.code);
			errObj.name = 'server';

			callback(errObj, false);
		}else{
			isUsed(name, email, (error, result) => {
				if(error){
					console.log(error);
					callback(error, false);
				}else if(result){
				}
			})
		}
	})
};
