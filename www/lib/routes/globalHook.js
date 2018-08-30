let express = require('express');
let router = express.Router();
let fs = require('fs');

// Pages not visible by logged users
let notLoggedPages = ['login', 'register'];
// Pages not visible by not logged users
let loggedPages = ['chat'];
router.get('/*', (request, response, next) => {
	// Remove openning slash
	let pageName = request.originalUrl.substr(1);
	fs.access('lib/routes/' + pageName + '.js', (error) => {
		if(error){
			if(pageName !== 'favicon.ico'){
				fs.access('views/pages/' + pageName + '.ejs', (err) => {
					// No view / route exists 
					if(err){
						request.flash('404', "The page you are looking for doesn't exist");
						if(request.session.username === undefined){
							response.render('pages/error', {
								home: true,
								redirect: '/login',
								session: false
							});
						}else{
							response.render('pages/error', {
								home: true,
								redirect: '/chat',
								session: true
							});
						}
					// A view exists
					}else{
						if(request.session.username !== undefined){
							response.locals.session = true;
							if(notLoggedPages.indexOf(pageName) !== -1){
								response.locals.title = 'chat';
								response.redirect('/chat');

								return;
							}
						}else if(loggedPages.indexOf(pageName) !== -1){
							response.locals.session = false;
							request.flash('404', "The page you are looking for doesn't exist");
							response.render('pages/error', {
								home: true,
								redirect: '/login'
							});

							return;
						}
						response.locals.title = pageName;
						response.render('pages/' + pageName);
					}
				});
			}
		}else{
			if(request.session.username !== undefined){
				response.locals.session = true;
				if(notLoggedPages.indexOf(pageName) !== -1){
					response.locals.title = 'chat';
					response.redirect('/chat');

					return;
				}
			}else if(loggedPages.indexOf(pageName) !== -1){
				response.locals.session = false;
				request.flash('404', "The page you are looking for doesn't exist");
				response.render('pages/error', {
					home: true,
					redirect: '/login'
				});

				return;
			}
			response.locals.title = pageName;
			next();
		}
	});
});
router.post('/*', (request, response, next) => {
	let pageName = request.originalUrl.substr(1);
	if(request.session.username !== undefined){
		if(notLoggedPages.indexOf(pageName) !== -1){
			console.log("auth user can't access this page");
			request.flash('404', "The page you are looking for doesn't exist");
			response.render('pages/error');
		}else{
			next();
		}
	}else{
		if(loggedPages.indexOf(pageName) !== -1){
			console.log("unauth user can't access this page");
		}else{
			next();
		}
	}
});
module.exports = router;
