let express = require('express');
let router = express.Router();
let path = require('path');

router.get('/', (routeReq, routeRes) => {
	/*
	if(routeReq.session.name !== undefined){
		routeRes.redirect('/chat');
	}else{
		routeRes.render('pages/register', {title: 'register'});
	}
	*/
	routeRes.render('pages/register');
});
router.post('/', (routeReq, routeRes) => {
	if(routeReq.session.name !== undefined){
		routeReq.flash('403', 'Unauthorized action');
		routeRes.render('pages/error', {title: 'error 403', home: true});
	}
	loadFunc(routeReq.originalUrl);
	let name = routeReq.body.name;
	let email = routeReq.body.email;
	let password = routeReq.body.password;
	let rPassword = routeReq.body.rPassword;
	register(name, email, password, rPassword, (err, res) => {
		if(err){
			if(err.name === 'server'){
				routeReq.flash(err.name, err.message);
				routeRes.render('pages/error', {title: 'error'});
			}else{
				routeReq.flash('danger', err.message);
				routeRes.render('pages/register', {title: 'register'});
			}
		}else{
			routeReq.flash('success', 'Your account was successfully created');
			routeRes.redirect('/login');
		}
			
	});
});
module.exports = router;
