module.exports = function (app){
	app.post('/login', (routeReq, routeRes) => {
		// Load function file when needed
		loadFunc(routeReq.originalUrl);
		var name = routeReq.body.name;
		var password = routeReq.body.password;
		authCheck(name, password, (error, result) => {
			if(error){
				if(error.name === 'server'){
					routeRes.render('pages/error', {
						title: 'error',
						name: error.name,
						message: error.message
					});
				}else{
					routeRes.render('pages/login', {
						title: 'login',
						messageType: 'danger',
						message: error.message
					});
				}
			}else{
				routeRes.redirect('/chat');
			}
		});
	});
}
