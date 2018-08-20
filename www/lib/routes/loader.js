module.exports = function(app){
	var fs = require('fs');
	var path = require('path');
	require(path.join('..', 'functions', 'app'));
	// Root page is login
	app.get('/', (request, result) => {
		result.redirect('/login');
	});
	// Validate input page
	app.param('pageName', (request, result, next, name) => {
		fs.access('views/pages/' + request.params.pageName + '.ejs', (err) => {
			if(!err){
				next();
			}else{
				result.render('pages/error', {
					title: 'error404',
					name: '404',
					message: 'The page you are looking for is not available',
					home: true
				});
			}
		})
	});
	// Render input page
	app.get('/:pageName', (request, result) => {
		result.render(path.join('pages', request.params.pageName), {title: request.params.pageName});
	});
	// Load all routes
	fs.readdir(__dirname, (error, files) => {
		if(error){
			throw error;
		}else{
			files.forEach((file) => {
				if(file !== 'loader.js'){
					require(path.join(__dirname, file))(app);
				}
			});
		}
	});
}
