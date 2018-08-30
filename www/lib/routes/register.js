let express = require('express');
let router = express.Router();
let path = require('path');
router.get('/', (request, response) => {
	response.render('pages/register');
});
router.post('/', (request, response) => {
	loadFunc(request.originalUrl);
	let username = request.body.username;
	let password = request.body.password;
	let rPassword = request.body.rPassword;
	register(username, password, rPassword, (error, result) => {
		if(error){
			if(error.name === 'server'){
				console.log(error);
				request.flash(error.name, error.message);
				response.render('pages/error');
			}else{
				request.flash('danger', error.message);
				response.render('pages/register', {title: 'register'});
			}
		}else{
			request.session.username = username;
			response.redirect('/chat');
		}
	});
});
module.exports = router;
