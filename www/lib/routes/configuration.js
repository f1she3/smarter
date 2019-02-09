let express = require('express');
let router = express.Router();

router.get('/', (request, response) => {
	response.render('pages' + request.originalUrl);
});
router.post('/', (request, response) => {
	let username = request.session.username;
	let oldPassword = request.body.oldPassword;
	let newPassword = request.body.newPassword;
	let repeatPassword = request.body.repeatPassword;
	if(username === undefined){
		request.flash('danger', 'please type your current password');
		response.render('pages' + request.originalUrl);
	}
	if(oldPassword.length < 8){
		request.flash('danger', 'incorrect password');
		response.render('pages' + request.originalUrl);
	}
	if(newPassword.length < 8){
		request.flash('danger', 'your password must be at least 8 chars long');
		response.render('pages' + request.originalUrl);
	}
	if(newPassword === oldPassword){
		request.flash('danger', 'please choose a new password');
		response.render('pages' + request.originalUrl);
	}
	if(newPassword !== repeatPassword){
		request.flash('danger', 'the new passwords don\'t match');
		response.render('pages' + request.originalUrl);
	}
	authCheck(username, oldPassword, (error) => {
		if(error){
			if(error.name === 'server'){
				request.flash(error.name, error.message);
				response.render('pages/error');
			}else{
				request.flash('danger', 'current password incorrect');
				response.render('pages' + request.originalUrl);
			}
		}else{
			updatePassword(username, newPassword, (dbError) => {
				if(dbError){
					if(dbError.name === 'server'){
						request.flash(error.name, error.message);
						response.render('pages/error');
					}else{
						request.flash('danger', error.message);
						response.render('pages' + request.originalUrl);
					}
				}else{
					request.flash('success', 'password successfully changed');
					response.render('pages' + request.originalUrl);
				}
			});
		}
	});
});
module.exports = router;
