let express = require('express');
let router = express.Router();

router.get('/', (request, response) => {
	loadFunc(request.originalUrl);
	getUserInfos(request.session.username, (error, result) => {
		if(error){
			request.flash(error.name, error.message);
			response.render('pages/error');
		}else{
			let date = new Date(result[0]);
			let res = Array();
			res[0] = date.getFullYear();
			res[1] = date.getMonth();
			if(res[1] < 10){
				res[1] = "0" + res[1];
			}
			res[2] = date.getDay();
			if(res[2] < 10){
				res[2] = "0" + res[2];
			}
			getRankList((rank) => {
				response.render('pages/account', {
					username: request.session.username,
					regDate: res,
					friendsCount: result[2],
					rank: rank[result[1]]
				});
			})
		}
	});
});
module.exports = router;
