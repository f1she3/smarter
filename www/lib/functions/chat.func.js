module.exports = insertMsg = function(sender, message, callback){
	getCon((error, connection) => {
		if(error){
			callback(error);
		}else{
			connection.query('INSERT INTO chat (sender, message, date) VALUES (?, ?, NOW())', 
				[sender, message], (err) => {
				connection.release();
				if(err){
					callback(err);
				}else{
					callback(false);
				}
			});
		}
	});
	
}, getMsg = function(callback, result){
	getCon((error, connection) => {
		if(error){
			callback(error);
		}else{
			connection.query('SELECT id, sender, UNIX_TIMESTAMP(date) as date, message FROM chat \
			ORDER BY id DESC LIMIT 30', (err, rows) => {
				connection.release();
				if(err){
					callback(err);
				}else{
					callback(false, rows);
				}
			});
		}
	});
};
