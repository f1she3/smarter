let express = require('express');
let router = express.Router();

router.get('/', (request, response) => {
	loadFunc(request.originalUrl);
	getRegDate(request.session.username, (error, result) => {
		if(error){
			request.flash(error.name, error.message);
			response.render('pages/error');
		}else{
			response.render('pages/account', {
				username: request.session.username,
				regDate: result
			});
		}
	})
});
module.exports = router;
