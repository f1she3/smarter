module.exports = getUserInfos = function(username, callback){
	let crypto = require('crypto');
	return getCon((error, connection) => {
		if(error){
			let errObj = new Error(error.code);
			errObj.name = 'server';

			return callback(errObj);
		}
		let shasum = crypto.createHash('sha512');
		let usernameHash = shasum.update(username).digest('hex');
		connection.query('SELECT regDate, rank FROM users WHERE BINARY username = ?', [usernameHash], function(err, dbRes, fields){
			if(err){
				let errObj = new Error(err.code);
				errObj.name = 'server';

				callback(errObj);
			// Username validation
			}else{
				let infos = Array();
				console.log(dbRes[0]);
				infos[0] = dbRes[0].regDate;
				infos[1] = dbRes[0].rank;
				connection.query('SELECT COUNT(*) AS friendsCount FROM friends WHERE BINARY sender = ? \
				OR BINARY contact = ?', [usernameHash, usernameHash], function(err, dbRes, fields){
					connection.release();
					if(err){
						let errObj = new Error(err.code);
						errObj.name = 'server';

						callback(errObj);
					// Username validation
					}else{
						infos[2] = dbRes[0].friendsCount;
						
						return callback(false, infos);
					}
				});
			}
		});
	});
}
