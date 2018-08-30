let express = require('express');
let router = express.Router();

router.get('/', (request, response) => {
	request.session.name = undefined;
	request.flash('success', 'Successfully logged out');
	response.redirect('/login');
})
module.exports = router;
