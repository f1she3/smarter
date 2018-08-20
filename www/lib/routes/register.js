module.exports = function(app){
	app.post('/register', (routeReq, routeRes) => {
		// Load function file when needed
		loadFunc(routeReq.originalUrl);
		var name = routeReq.body.name;
		var email = routeReq.body.email;
		var password = routeReq.body.password;
		var rPassword = routeReq.body.rPassword;
		register(name, email, password, rPassword, (err, res) => {
				if(err){
					if(err.name === 'server'){
						routeRes.render('pages/error', {
							title: 'error',
							name: err.name,
							message: err.message
						});
					}else{
						routeRes.render('pages/register', {
							title: 'register',
							messageType: 'danger',
							message: err.message
						});
					}
				}else{
					
				}
				
		});
	});
}
