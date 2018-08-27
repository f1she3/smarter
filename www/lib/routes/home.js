let express = require('express');
let router = express.Router();

router.get('/', (routeReq, routeRes) => {
	routeRes.render('pages/home');
});
module.exports = router;
