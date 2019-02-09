let express = require('express');
let router = express.Router();
let fs = require('fs');

// Pages not visible by logged users
let notLoggedPages = ['login', 'register'];
// Pages not visible by not logged users
let loggedPages = ['chat', 'account'];
router.get('/*', (request, response, next) => {
	if(request.originalUrl === '/'){
		if(request.session.username === undefined){
			response.redirect('/login');

			return;
		}else{
			response.redirect('/chat');

			return;
		}
	}
	let pageName = request.originalUrl.substr(1);
	// Remove openning slash
	fs.access('public/js/' + pageName + '.js', (exists) => {
		if(pageName === 'favicon.ico'){
			return;	
		}
		if(!exists){
			// Used to determine if the view as
			// a related js script to include
			response.locals.jsPage = pageName;
		}
		fs.access('lib/routes/' + pageName + '.js', (error) => {
			// No route exists
			if(error){
				fs.access('views/pages/' + pageName + '.ejs', (err) => {
					// No view or route exists 
					if(err){
						request.flash('404', 'The page you are looking for does not exist');
						response.locals.title = 'error';
						response.locals.home = true;
						if(request.session.username === undefined){
							response.render('pages/error', {
								redirect: '/login',
								session: false
							});
						}else{
							response.render('pages/error', {
								redirect: '/chat',
								session: true
							});
						}
					// A view exists
					}else{
						if(pageName === 'chat' && request.session.username !== undefined){
							response.locals.title = pageName + ' @' + request.session.username;
						}else{
							response.locals.title = pageName;
						}
						// Used to know wich page the
						// user is on
						response.locals.pageName = pageName;
						if(request.session.username !== undefined){
							response.locals.session = true;
							if(notLoggedPages.indexOf(pageName) !== -1){
								response.redirect('/chat');

								return;
							}
						}else if(loggedPages.indexOf(pageName) !== -1){
							response.locals.session = false;
							request.flash('404', 'The page you are looking for does not exist');
							response.render('pages/error', {
								home: true,
								redirect: '/login'
							});

							return;
						}
						response.render('pages/' + pageName);
					}
				});
			// A route exists
			}else{
				fs.access('lib/functions/' + pageName + '.func.js', (error) => {
					if(!error){
						loadFunc(pageName);
					}
					if(pageName === 'chat' && request.session.username !== undefined){
						response.locals.title = pageName + ' @' + request.session.username;
					}else{
						response.locals.title = pageName;
					}
					// Used to know wich page the
					// user is on
					response.locals.pageName = pageName;
					if(request.session.username !== undefined){
						response.locals.session = true;
						if(notLoggedPages.indexOf(pageName) !== -1){
							response.redirect('/chat');

							return;
						}
					}else if(loggedPages.indexOf(pageName) !== -1){
						response.locals.session = false;
						request.flash('404', 'The page you are looking for does not exist');
						response.render('pages/error', {
							home: true,
							redirect: '/login'
						});

						return;
					}
					next();
				});
			}
		});
	});
});
router.post('/*', (request, response, next) => {
	let pageName = request.originalUrl.substr(1);
	if(request.session.username !== undefined){
		if(notLoggedPages.indexOf(pageName) !== -1){
			request.flash('404', 'The page you are looking for does not exist');
			response.render('pages/error');
		}else{
			next();
		}
	}else{
		if(loggedPages.indexOf(pageName) !== -1){
			console.log('Guest users can not access this page');
		}else{
			next();
		}
	}
	response.locals.title = pageName;
});
module.exports = router;
