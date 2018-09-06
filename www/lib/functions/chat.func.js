module.exports = insertMsg = function(sender, message, callback){
	getCon((error, connection) => {
		if(error){
			callback(error);
		}else{
			connection.query('INSERT INTO chat (sender, message, date) VALUES (?, ?, NOW())', [sender, message], (err) => {
				if(err){
					callback(err);
				}else{
					callback(false);
				}
			});
		}
		connection.release();
	});
	
}, getMsg = function(callback, result){
	getCon((error, connection) => {
		if(error){
			callback(error);
		}else{
			connection.query('SELECT id, sender, UNIX_TIMESTAMP(date) as date, message FROM chat ORDER BY id DESC LIMIT 30', (err, rows) => {
				if(err){
					callback(err);
				}else{
					callback(false, rows);
				}
			})
		}
		connection.release();
	});
};
