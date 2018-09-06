let express = require('express');
let router = express.Router();

router.get('/', (request, response) => {
	response.render('pages/login');
});
router.post('/', (request, response) => {
	loadFunc(request.originalUrl);
	let username = request.body.username;
	let password = request.body.password;
	authCheck(username, password, (error) => {
		if(error){
			if(error.name === 'server'){
				request.flash(error.name, error.message);
				response.render('pages/error');
			}else{
				request.flash('danger', error.message);
				response.render('pages/login');
			}
		}else{
			request.flash('success', 'Logged');
			request.session.username = username;
			response.redirect('/chat');
		}
	});
});
module.exports = router;
