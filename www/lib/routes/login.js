let express = require('express');
let router = express.Router();

router.get('/', (request, response) => {
	response.render('pages/login');
});
router.post('/', (request, response) => {
	loadFunc(request.originalUrl);
	let name = request.body.name;
	let password = request.body.password;
	authCheck(name, password, (error, result) => {
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
			request.session.name = name;
			response.redirect('/chat');
		}
	});
});
module.exports = router;
