let express = require('express');
let router = express.Router();

router.get('/', (request, response) => {
	/*
	if(request.session.name !== undefined){
		response.redirect('/chat');
		response.render('pages/login', {title: 'login'});
	}else{
		response.render('pages/login', {title: 'login'});
	}
	*/
	response.render('pages/login');
});
router.post('/', (request, response) => {
	// Refuse posted data when logged
	if(request.session.name !== undefined){
		request.flash('403', 'Unauthorized action');
		response.redirect('pages/error', {title: 'error 403', home: true});

		return;
	}
	loadFunc(request.originalUrl);
	let name = request.body.name;
	let password = request.body.password;
	authCheck(name, password, (error, result) => {
		if(error){
			if(error.name === 'server'){
				request.flash(error.name, error.message);
				response.render('pages/error', {title: 'error'});
			}else{
				request.flash('danger', error.message);
				response.render('pages/login', {title: 'login'});
				
			}
		}else{
			request.flash('success', 'Logged');
			request.session.name = name;
			response.redirect('/chat');
		}
	});
});
module.exports = router;
