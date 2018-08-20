module.exports = authCheck = function(username, password, callback){
	getCon((error, connection) =>{
		if(error){
			var errObj = new Error(error.code);
			errObj.name = 'server';

			return callback(errObj, false);
		}else{
			connection.query('SELECT password FROM users WHERE BINARY name = ?', [username], function(err, dbRes, fields){
				if(err){
					if(callback !== undefined){
						var errObj = new Error(err.code);
						errObj.name = 'server';

						callback(errObj, false);
					}else{
						throw err;
					}
				// Valid username
				}else if(dbRes.length > 0){
					var bcrypt = require('bcrypt');
					var passwordStr = JSON.stringify(dbRes[0]);
					var passwordStrObj = JSON.parse(passwordStr);
					passwordStrObj.password = passwordStrObj.password.replace(/^\$2y(.+)$/i, '$2a$1');
					return bcrypt.compare(password, passwordStrObj.password, (err, res) => {
						if(res){
							return callback(false, true);
						}else{
							return callback(new Error('Wrong username or password'), false);
						}
					});
				}else{
					return callback(new Error('Wrong username or password'), false);
				}
			});
			connection.release();
		}
	})
}
